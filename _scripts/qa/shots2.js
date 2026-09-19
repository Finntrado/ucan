const { chromium } = require('playwright');
(async () => {
  const b = await chromium.launch(); const B = 'http://localhost:8099';
  for (const [name, w] of [['d', 1440], ['m', 390]]) {
    const p = await b.newPage({ viewport: { width: w, height: 900 } });
    await p.goto(B + '/citizen-centric-urban-solutions', { waitUntil: 'load' });
    await p.evaluate(() => document.querySelectorAll('.rv').forEach(e => e.classList.add('in')));
    for (const sel of ['.art-take', '.dg-fig', '.stats-wrap', '.faq', '.authorcard', '.art-related']) {
      const e = await p.$(sel); if (e) { await e.scrollIntoViewIfNeeded(); await e.screenshot({ path: `out/seo/${name}-${sel.replace('.', '')}.png` }); }
    }
    await p.close();
  }
  await b.close();
})();
