// Runtime "standalone" audit. Loads pages with every non-local request
// BLOCKED and logged, exercises everything that can trigger a request
// (full scroll for lazy images, every show-all / filter / tab button,
// video facades, the lightbox, newsletter form submit, 11 s for the cookie
// banner), then fails on:
//   - any attempted request to a host other than the page's own
//   - any <img> that never loaded (naturalWidth 0) or <video> source error
//   - any HTTP >= 400 from the local server, console error or page error
//   node offline.js [base] [page...]      default base http://localhost:8099
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const BASE = process.argv[2] || 'http://localhost:8099';
let pages = process.argv.slice(3);
if (!pages.length) {
  pages = fs.readdirSync(path.join(__dirname, '..', '..', 'standalone'))
    .filter(f => f.endsWith('.html')).map(f => f.slice(0, -5));
}
const ORIGIN = new URL(BASE).origin;

(async () => {
  const browser = await chromium.launch();
  let bad = 0;
  for (const p of pages) {
    const ctx = await browser.newContext({ viewport: { width: 1280, height: 900 } });
    const problems = [];
    const popups = [];
    let page = null;
    await ctx.route('**/*', route => {
      const req = route.request();
      const u = req.url();
      if (u.startsWith(ORIGIN) || u.startsWith('data:') || u.startsWith('blob:')) return route.continue();
      // a facade click opens YouTube in a NEW TAB - a navigation the visitor
      // chose, not something this page loads. Blocked, but not a failure.
      // (Playwright throws on frame() for a popup's very first navigation -
      // that throw IS the new-tab case; the main page navigating away is
      // caught separately by the page.url() check below)
      let fromPage;
      try { fromPage = req.frame().page() === page; } catch (e) { fromPage = !req.isNavigationRequest(); }
      if (!fromPage) { popups.push(u); return route.abort(); }
      problems.push('EXTERNAL ' + req.resourceType() + ' ' + u);
      return route.abort();
    });
    ctx.on('page', np => { if (page && np !== page) setTimeout(() => np.close().catch(() => {}), 1500); });
    page = await ctx.newPage();
    page.on('console', m => { if (m.type() === 'error' && !/ERR_FAILED|net::/.test(m.text())) problems.push('console: ' + m.text()); });
    page.on('pageerror', e => problems.push('pageerror: ' + e.message));
    page.on('response', r => { if (r.status() >= 400) problems.push(r.status() + ' ' + r.url()); });

    try {
      await page.goto(`${BASE}/${p === 'index' ? '' : p}`, { waitUntil: 'load', timeout: 60000 });

      // reveal everything that is hidden behind a button
      await page.evaluate(() => {
        document.querySelectorAll('button, [role=tab], summary').forEach(b => {
          if (/cookie|reject|accept|burger|menu|close|nl-copy/i.test((b.id || '') + ' ' + b.className + ' ' + b.textContent)) return;
          try { b.click(); } catch (e) {}
        });
      });
      // scroll the whole page so every lazy image requests
      await page.evaluate(async () => {
        for (let y = 0; y < document.body.scrollHeight; y += 600) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 60)); }
      });
      await page.evaluate(() => Promise.race([
        new Promise(r => setTimeout(r, 8000)),
        Promise.all([...document.images].filter(i => !i.complete).map(i => new Promise(r => { i.onload = i.onerror = r; }))),
      ]));

      // video facades (clicked, not just present)
      const yt = await page.$$('.ytlite');
      for (const a of yt.slice(0, 2)) { await a.scrollIntoViewIfNeeded(); await a.click().catch(() => {}); await page.waitForTimeout(800); }
      // lightbox
      const lb = await page.$('[data-full]');
      if (lb) { await lb.scrollIntoViewIfNeeded(); await lb.click().catch(() => {}); await page.waitForTimeout(800); await page.keyboard.press('Escape'); }
      // local video: make it actually request its data
      await page.evaluate(() => document.querySelectorAll('video').forEach(v => { v.preload = 'auto'; v.load(); }));
      // newsletter form: valid email + consent, submit
      const form = await page.$('#fnform');
      if (form) {
        await page.fill('#fnform input[type=email]', 'test@example.com');
        // some pages style the consent box as a custom control, so .check()
        // cannot click it - set it the way a real click would
        await page.evaluate(() => {
          const cb = document.querySelector('#fnform input[type=checkbox]');
          if (cb && !cb.checked) { cb.checked = true; cb.dispatchEvent(new Event('change', { bubbles: true })); }
        });
        await page.evaluate(() => document.querySelector('#fnform').requestSubmit());
      }
      await page.waitForTimeout(p === 'index' ? 11000 : 1500); // cookie banner once, on the homepage

      const broken = await page.evaluate(() => [...document.images]
        .filter(i => i.complete && i.naturalWidth === 0 && i.getAttribute('src'))
        .map(i => i.getAttribute('src')));
      // serve.py (and LocalWP) occasionally drop a connection under the burst
      // of parallel image requests (CLAUDE.md §27 trap), so an image only
      // counts as broken if fetching it again directly also fails
      for (const s of broken) {
        const abs = new URL(s, page.url()).href;
        let st = 0;
        try { st = (await page.request.get(abs)).status(); } catch (e) {}
        if (st !== 200) problems.push('broken img ' + s + ' (refetch ' + st + ')');
      }
      const vidErr = await page.evaluate(() => [...document.querySelectorAll('video')].filter(v => v.error).map(v => v.currentSrc));
      vidErr.forEach(s => problems.push('video error ' + s));
      if (page.url().split('#')[0].replace(/\/$/, '') !== `${BASE}/${p === 'index' ? '' : p}`.replace(/\/$/, '')) {
        problems.push('page navigated away to ' + page.url());
      }
    } catch (e) {
      problems.push('harness: ' + e.message.split('\n')[0]);
    }
    if (problems.length) {
      bad++;
      console.log('FAIL ' + p);
      [...new Set(problems)].slice(0, 12).forEach(x => console.log('   ' + x));
    }
    await ctx.close();
  }
  await browser.close();
  console.log(`${pages.length} pages, ${bad} failing`);
  process.exit(bad ? 1 : 0);
})();
