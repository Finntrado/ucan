// Which elements shift, and what their max-width is. Same slow-font scenario.
//   node clswho.js <width> page page ...
const { chromium } = require('playwright');
(async () => {
  const [w, ...pages] = process.argv.slice(2);
  const b = await chromium.launch();
  const ctx = await b.newContext({ viewport: { width: +w, height: +w > 800 ? 900 : 915 } });
  await ctx.route('**/*', async r => {
    if (/fonts\.gstatic\.com/.test(r.request().url())) await new Promise(x => setTimeout(x, 600));
    r.continue();
  });
  await ctx.addInitScript(() => { window.__s = [];
    new PerformanceObserver(l => l.getEntries().forEach(e => { if (e.hadRecentInput) return;
      (e.sources || []).forEach(s => { let n = s.node; if (!n) return;
        if (n.nodeType === 3) n = n.parentElement;
        const cs = getComputedStyle(n);
        window.__s.push({ v: e.value, el: n.tagName.toLowerCase() + (n.className && typeof n.className === 'string' ? '.' + n.className.trim().split(/\s+/).join('.') : '') +
          (n.id ? '#' + n.id : ''), mw: cs.maxWidth, dh: Math.round(s.currentRect.height - s.previousRect.height),
          dy: Math.round(s.currentRect.y - s.previousRect.y) }); }); }))
      .observe({ type: 'layout-shift', buffered: true }); });
  const p = await ctx.newPage();
  for (const n of pages) {
    await p.goto(`http://127.0.0.1:8099/${n}.html`, { waitUntil: 'load' });
    await p.waitForTimeout(1800);
    const s = await p.evaluate(() => window.__s);
    const seen = new Set();
    console.log(`== ${n} @${w}`);
    s.forEach(x => { const k = x.el; if (seen.has(k)) return; seen.add(k);
      console.log(`   ${x.el.slice(0, 60).padEnd(60)} max-width=${x.mw.padEnd(8)} dh=${x.dh} dy=${x.dy}`); });
  }
  await b.close();
})();
