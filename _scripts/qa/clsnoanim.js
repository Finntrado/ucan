const { chromium } = require('playwright');
(async () => {
  const b = await chromium.launch();
  for (const mode of ['animated', 'no-animation']) {
    const ctx = await b.newContext({ viewport: { width: 1440, height: 900 } });
    await ctx.route('**/*', async r => { if (/fonts\.gstatic/.test(r.request().url())) await new Promise(x => setTimeout(x, 600)); r.continue(); });
    await ctx.addInitScript(m => { window.__c = 0;
      if (m === 'no-animation') document.addEventListener('DOMContentLoaded', () => {
        const st = document.createElement('style'); st.textContent = '.hero h1 .hw{animation:none!important}'; document.head.appendChild(st); });
      new PerformanceObserver(l => l.getEntries().forEach(e => { if (!e.hadRecentInput) window.__c += e.value; }))
        .observe({ type: 'layout-shift', buffered: true }); }, mode);
    const p = await ctx.newPage();
    await p.goto('http://127.0.0.1:8099/index.html', { waitUntil: 'load' }); await p.waitForTimeout(2200);
    console.log(mode.padEnd(14), (await p.evaluate(() => window.__c)).toFixed(4));
    await ctx.close();
  }
  await b.close();
})();
