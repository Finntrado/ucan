const { chromium } = require('playwright');
(async () => { const b = await chromium.launch(); const p = await b.newPage();
  for (const [w, out] of [[1280, 'why-new.png'], [412, 'why-new-m.png']]) {
    await p.setViewportSize({ width: w, height: 900 });
    await p.goto('http://127.0.0.1:8099/index.html', { waitUntil: 'load' });
    await p.addStyleTag({ content: '.bar{position:static!important}' });
    const el = p.locator('.why2'); await el.scrollIntoViewIfNeeded();
    await p.evaluate(async () => { const e = document.querySelector('.why2'); const r = e.getBoundingClientRect();
      for (let y = 0; y < r.height + 400; y += 200) { window.scrollBy(0, 200); await new Promise(r => setTimeout(r, 60)); } });
    await p.waitForTimeout(2200);
    await el.screenshot({ path: 'out/' + out });
  }
  await b.close(); })();
