const { chromium } = require('playwright');
(async () => { const b = await chromium.launch(); const p = await b.newPage(); await p.setViewportSize({ width: 1280, height: 900 });
  await p.goto('http://127.0.0.1:8099/privacy-policy.html', { waitUntil: 'load' });
  const ul = p.locator('main ul:not([class])', { hasText: 'With Service Providers' }).first();
  await ul.scrollIntoViewIfNeeded(); await p.waitForTimeout(300); await ul.screenshot({ path: 'out/pp-list.png' });
  // every mixed-content li across a few pages: does the text start on the same line as the bullet?
  for (const n of ['privacy-policy', 'terms-of-use', 'rfc', 'blog-the-urban-haze', 'newsletter-october-2025']) {
    await p.goto(`http://127.0.0.1:8099/${n}.html`, { waitUntil: 'load' });
    const r = await p.evaluate(() => { let ok = 0, bad = 0;
      document.querySelectorAll('main ul:not([class]) > li').forEach(li => {
        const dot = parseFloat(getComputedStyle(li, '::before').top) + li.getBoundingClientRect().top;
        const rg = document.createRange(); rg.selectNodeContents(li); const first = rg.getClientRects()[0];
        if (!first) return; (Math.abs((first.top + first.height / 2) - (dot + 3.5)) < 8 ? ok++ : bad++); });
      return { ok, bad }; });
    console.log(`${n.padEnd(26)} list items with bullet on the first text line: ${r.ok}  misaligned: ${r.bad}`);
  }
  await b.close(); })();
