// Mobile menu regression test: node menutest.js <base> [page...]
// For each engine/page: scroll down, open menu -> menu on screen, last link reachable,
// page does not scroll behind it; close -> back at the same scroll position.
const pw = require('playwright');
const base = process.argv[2] || 'http://127.0.0.1:8099';
const pages = process.argv[3] === '--all' ? require('fs').readdirSync(__dirname + '/../../standalone').filter(f => f.endsWith('.html')).map(f => f.slice(0, -5)) : process.argv.slice(3).length ? process.argv.slice(3) : ['fellow-blogs', 'blog-the-urban-haze', 'about', '/'];
// real touch at the burger's coordinates: page.tap() auto-scrolls the element into view and fakes a scroll shift
const tapBurger = async p => { const r = await p.evaluate(() => { const b = document.querySelector('#burger').getBoundingClientRect(); return [b.left + b.width / 2, b.top + b.height / 2]; }); await p.touchscreen.tap(r[0], r[1]); };
(async () => {
  let bad = 0; const ok = (n, c, d) => { console.log((c ? 'ok    ' : 'FAIL  ') + n + (c ? '' : '  ' + JSON.stringify(d))); if (!c) bad++; };
  for (const eng of ['chromium', 'webkit']) {
    const b = await pw[eng].launch();
    for (const pg of pages) {
      const ctx = await b.newContext({ viewport: { width: 390, height: 664 }, hasTouch: true, deviceScaleFactor: 2, isMobile: eng === 'chromium' });
      const p = await ctx.newPage(); const errs = []; p.on('pageerror', e => errs.push(e.message));
      await p.goto(base.replace(/\/$/, '') + '/' + pg.replace(/^\//, ''), { waitUntil: 'load', timeout: 60000 });
      await p.evaluate(() => { document.documentElement.style.scrollBehavior = 'auto'; scrollTo(0, Math.min(1800, Math.floor((document.documentElement.scrollHeight - innerHeight) / 2))); }); await p.waitForTimeout(400);
      const y0 = await p.evaluate(() => scrollY);
      await tapBurger(p); await p.waitForTimeout(500);
      const o = await p.evaluate(() => { const s = document.querySelector('#msheet'), r = s.getBoundingClientRect();
        s.scrollTop = 1e6; const a = [...s.querySelectorAll('a')], l = a[a.length - 1].getBoundingClientRect();
        return { top: Math.round(r.top), bottom: Math.round(r.bottom), lastBottom: Math.round(l.bottom), vh: innerHeight, open: s.classList.contains('open') }; });
      const tag = eng + ' ' + pg;
      ok(tag + ': menu opens on screen', o.open && o.top >= 0 && o.top < 120, o);
      ok(tag + ': last menu link reachable', o.lastBottom <= o.vh && o.bottom <= o.vh, o);
      await p.mouse.wheel(0, 800).catch(() => {}); await p.waitForTimeout(300);
      await tapBurger(p); await p.waitForTimeout(500);
      const y1 = await p.evaluate(() => scrollY);
      ok(tag + ': close returns to same scroll position', Math.abs(y1 - y0) <= 2, { y0, y1 });
      const room = await p.evaluate(() => document.documentElement.scrollHeight - innerHeight);
      await p.evaluate(() => scrollTo(0, document.documentElement.scrollHeight)); await p.waitForTimeout(200);
      ok(tag + ': page scrolls normally after closing', room < 50 || (await p.evaluate(() => scrollY)) > y1 + 20, { room });
      ok(tag + ': no JS errors', !errs.length, errs);
      await ctx.close();
    }
    await b.close();
  }
  console.log(bad + ' failing'); process.exit(bad ? 1 : 0);
})();
