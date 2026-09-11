const { chromium } = require('playwright');
(async () => {
  const [w, n] = process.argv.slice(2);
  const b = await chromium.launch();
  const ctx = await b.newContext({ viewport: { width: +w, height: 900 } });
  await ctx.route('**/*', async r => { if (/fonts\.gstatic/.test(r.request().url())) await new Promise(x => setTimeout(x, 600)); r.continue(); });
  await ctx.addInitScript(() => { window.__e = [];
    new PerformanceObserver(l => l.getEntries().forEach(e => { if (e.hadRecentInput) return;
      window.__e.push({ v: +e.value.toFixed(4), t: Math.round(e.startTime), n: (e.sources||[]).length,
        s: (e.sources||[]).map(s => { let x = s.node; if (x && x.nodeType === 3) x = x.parentElement;
          return x ? x.tagName.toLowerCase() + '.' + String(x.className).split(' ')[0] + ' ' +
            Math.round(s.previousRect.x) + ',' + Math.round(s.previousRect.y) + ' ' + Math.round(s.previousRect.width) + 'x' + Math.round(s.previousRect.height) +
            ' -> ' + Math.round(s.currentRect.x) + ',' + Math.round(s.currentRect.y) + ' ' + Math.round(s.currentRect.width) + 'x' + Math.round(s.currentRect.height) : '?'; }) }); }))
      .observe({ type: 'layout-shift', buffered: true }); });
  const p = await ctx.newPage();
  await p.goto(`http://127.0.0.1:8099/${n}.html`, { waitUntil: 'load' }); await p.waitForTimeout(2000);
  (await p.evaluate(() => window.__e)).forEach(e => { console.log(`+${e.v} @${e.t}ms`); e.s.forEach(x => console.log('    ' + x)); });
  await b.close();
})();
