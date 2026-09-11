const { chromium } = require('playwright');
(async () => {
  const b = await chromium.launch(); const p = await b.newPage();
  await p.addInitScript(() => { window.__shifts = [];
    new PerformanceObserver(l => l.getEntries().forEach(e => { if (e.hadRecentInput) return;
      window.__shifts.push({ v: +e.value.toFixed(4), t: Math.round(e.startTime),
        src: (e.sources || []).map(s => { const n = s.node; return n ? (n.nodeName + '.' + (n.className && n.className.baseVal === undefined ? n.className : '')).slice(0, 50) + ' ' + JSON.stringify(s.previousRect) + '->' + JSON.stringify(s.currentRect) : '?'; }) }); }))
      .observe({ type: 'layout-shift', buffered: true }); });
  await p.setViewportSize({ width: 1440, height: 900 });
  await p.goto(`http://127.0.0.1:8099/${process.argv[2] || 'index'}.html`, { waitUntil: 'load' });
  await p.waitForTimeout(3000);
  const s = await p.evaluate(() => window.__shifts);
  console.log('total', s.reduce((a, x) => a + x.v, 0).toFixed(4));
  s.forEach(x => { console.log(`  +${x.v} @${x.t}ms`); x.src.forEach(z => console.log('      ' + z.slice(0, 200))); });
  await b.close();
})();
