// CLS across a set of pages. Fonts are delayed 600ms so the swap always
// happens after first paint - the slow-network case Lighthouse measures.
//   node clsmany.js [--strip=<data-ucan name>] page page ...
const { chromium } = require('playwright');
(async () => {
  const args = process.argv.slice(2);
  const strip = (args.find(a => a.startsWith('--strip=')) || '').slice(8);
  const pages = args.filter(a => !a.startsWith('--'));
  const b = await chromium.launch();
  let sum = 0;
  for (const w of [1440, 412]) {
    const ctx = await b.newContext({ viewport: { width: w, height: w > 800 ? 900 : 915 } });
    await ctx.route('**/*', async route => {
      const u = route.request().url();
      if (/fonts\.gstatic\.com/.test(u)) { await new Promise(r => setTimeout(r, 600)); return route.continue(); }
      if (strip && u.startsWith('http://127.0.0.1:8099') && /\.html$/.test(u)) {
        const r = await route.fetch(); let body = await r.text();
        body = body.replace(new RegExp('<style data-ucan="' + strip + '">[\\s\\S]*?</style>'), '');
        return route.fulfill({ response: r, body });
      }
      route.continue();
    });
    await ctx.addInitScript(() => { window.__cls = 0;
      new PerformanceObserver(l => l.getEntries().forEach(e => { if (!e.hadRecentInput) window.__cls += e.value; }))
        .observe({ type: 'layout-shift', buffered: true }); });
    const p = await ctx.newPage();
    const row = [];
    for (const n of pages) {
      await p.goto(`http://127.0.0.1:8099/${n}.html`, { waitUntil: 'load' });
      await p.waitForTimeout(1800);
      const c = await p.evaluate(() => window.__cls);
      sum += c; row.push(`${n}=${c.toFixed(3)}`);
    }
    console.log(`@${w}: ` + row.join('  '));
    await ctx.close();
  }
  console.log('sum', sum.toFixed(3));
  await b.close();
})();
