const { chromium } = require('playwright');
(async () => { const b = await chromium.launch(); const p = await b.newPage();
  await p.setViewportSize({ width: 1280, height: 900 });
  for (const n of ['city-champions', 'webinar-beyond-silos-the-case-for-collective-action']) {
    await p.goto(`http://127.0.0.1:8099/${n}.html`, { waitUntil: 'load' });
    const before = await p.evaluate(() => ({ iframes: document.querySelectorAll('iframe').length,
      tiles: [...document.querySelectorAll('.ytlite')].map(a => { const r = a.getBoundingClientRect(); return Math.round(r.width) + 'x' + Math.round(r.height); }) }));
    await p.locator('.ytlite').first().scrollIntoViewIfNeeded(); await p.locator('.ytlite').first().click(); await p.waitForTimeout(800);
    const after = await p.evaluate(() => { const f = document.querySelector('.vid iframe'); const r = f && f.getBoundingClientRect();
      return f ? `${f.src.slice(0, 70)} ${Math.round(r.width)}x${Math.round(r.height)}` : 'none'; });
    console.log(`${n}\n   before: ${before.iframes} iframes, facades ${before.tiles.join(' ')}\n   after click: ${after}`);
  }
  await b.close(); })();
