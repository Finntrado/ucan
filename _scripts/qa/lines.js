const { chromium } = require('playwright');
(async () => {
  const b = await chromium.launch();
  for (const w of [1440, 1180, 1024, 768, 412, 360]) {
    const out = [];
    for (const mode of ['fallback', 'archivo']) {
      const ctx = await b.newContext({ viewport: { width: w, height: 900 } });
      if (mode === 'fallback') await ctx.route(/fonts\.gstatic\.com/, r => r.abort());
      await ctx.addInitScript(() => document.addEventListener('DOMContentLoaded', () => {
        const s = document.createElement('style'); s.textContent = '.hero h1 .hw{animation:none!important}'; document.head.appendChild(s); }));
      const p = await ctx.newPage();
      await p.goto('http://127.0.0.1:8099/index.html', { waitUntil: 'load' });
      await p.evaluate(() => document.fonts.ready); await p.waitForTimeout(300);
      out.push(await p.evaluate(() => { const rows = {};
        document.querySelectorAll('.hero h1 .hw').forEach(s => { const y = Math.round(s.getBoundingClientRect().top / 10);
          (rows[y] = rows[y] || []).push(s.textContent); });
        return Object.values(rows).map(r => r.join(' ')).join(' / '); }));
      await ctx.close();
    }
    console.log(`@${w}${out[0] === out[1] ? '  same ' : '  DIFF '}\n   fallback: ${out[0]}\n   archivo : ${out[1]}`);
  }
  await b.close();
})();
