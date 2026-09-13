// Pixel-parity check: every page on the WordPress install vs the same page
// from serve.py (the Vercel-equivalent static server).
//   node wpparity.js [wpBase] [staticBase] [page...]
// For each page and width: identical viewport screenshots, zero failed
// requests, zero console/page errors on the WP side. Mismatching shots are
// saved to out/wpparity/ for a look.
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const WP = process.argv[2] || 'http://ucan-test.local';
const ST = process.argv[3] || 'http://localhost:8099';
let pages = process.argv.slice(4);
if (!pages.length) {
  pages = fs.readdirSync(path.join(__dirname, '..', '..', 'standalone'))
    .filter(f => f.endsWith('.html')).map(f => f.slice(0, -5));
}
const WIDTHS = [1440, 390];
const OUT = path.join(__dirname, 'out', 'wpparity');
fs.mkdirSync(OUT, { recursive: true });

async function shot(ctx, url, collect) {
  const page = await ctx.newPage();
  const errs = [];
  if (collect) {
    page.on('console', m => { if (m.type() === 'error') errs.push('console: ' + m.text()); });
    page.on('pageerror', e => errs.push('pageerror: ' + e.message));
    page.on('response', r => { if (r.status() >= 400) errs.push(r.status() + ' ' + r.url()); });
    page.on('requestfailed', r => errs.push('failed ' + r.url()));
  }
  const resp = await page.goto(url, { waitUntil: 'load', timeout: 60000 });
  if (collect && resp.status() !== 200) errs.push('page status ' + resp.status());
  await page.evaluate(() => document.fonts.ready);
  // lazy images below the fold never load, so waiting on every image hangs:
  // only wait on ones in the viewport, and never longer than 5s
  await page.evaluate(() => Promise.race([
    new Promise(r => setTimeout(r, 5000)),
    Promise.all([...document.images].filter(i => !i.complete && i.getBoundingClientRect().top < innerHeight)
      .map(i => new Promise(r => { i.onload = i.onerror = r; }))),
  ]));
  await page.waitForTimeout(500);
  // the homepage hero's network <canvas> is randomly generated per load
  // (differs even static-vs-static), so it is masked out of the comparison
  const buf = await page.screenshot({ mask: [page.locator('canvas')], maskColor: '#FF00FF' });
  await page.close();
  return { buf, errs };
}

(async () => {
  const browser = await chromium.launch();
  let bad = 0;
  for (const w of WIDTHS) {
    const ctx = await browser.newContext({ viewport: { width: w, height: 900 }, reducedMotion: 'reduce' });
    for (const p of pages) {
      const slug = p === 'index' ? '' : p;
      const a = await shot(ctx, `${WP}/${slug}`, true);
      const b = await shot(ctx, `${ST}/${slug}`, false);
      const same = a.buf.equals(b.buf);
      if (!same || a.errs.length) {
        bad++;
        console.log(`FAIL ${p} @${w}: ${same ? 'pixels identical' : 'PIXELS DIFFER'}`);
        a.errs.slice(0, 5).forEach(e => console.log('   ' + e));
        if (!same) {
          fs.writeFileSync(path.join(OUT, `${p}-${w}-wp.png`), a.buf);
          fs.writeFileSync(path.join(OUT, `${p}-${w}-static.png`), b.buf);
        }
      }
    }
    await ctx.close();
  }
  await browser.close();
  console.log(`${pages.length} pages x ${WIDTHS.length} widths, ${bad} failing`);
  process.exit(bad ? 1 : 0);
})();
