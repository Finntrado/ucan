const { chromium } = require('playwright');
(async () => {
  const b = await chromium.launch(); const p = await b.newPage();
  const errs = []; p.on('pageerror', e => errs.push(String(e)));
  // CLS observer before any script runs
  await p.addInitScript(() => { window.__cls = 0;
    new PerformanceObserver(l => l.getEntries().forEach(e => { if (!e.hadRecentInput) window.__cls += e.value; }))
      .observe({ type: 'layout-shift', buffered: true }); });
  await p.setViewportSize({ width: 1440, height: 900 });
  await p.goto('http://127.0.0.1:8099/index.html', { waitUntil: 'load' });
  await p.waitForTimeout(2600);
  // move the cursor across the hero so the pull + links draw
  for (let x = 500; x <= 760; x += 20) { await p.mouse.move(x, 560); await p.waitForTimeout(30); }
  await p.waitForTimeout(500);
  await p.screenshot({ path: 'out/hero-after.png', clip: { x: 0, y: 0, width: 1440, height: 900 } });
  const info = await p.evaluate(() => {
    const c = document.querySelector('.hero-field'), h = document.querySelector('.hero h1');
    const px = c.getContext('2d').getImageData(0, 0, c.width, c.height).data;
    let ink = 0; for (let i = 3; i < px.length; i += 4) if (px[i] > 0) ink++;
    return { canvas: c.width + 'x' + c.height, painted: ink, h1W: Math.round(h.getBoundingClientRect().width),
             h1Lines: Math.round(h.getBoundingClientRect().height / parseFloat(getComputedStyle(h).lineHeight)),
             h1Font: getComputedStyle(h).fontSize, cls: window.__cls };
  });
  console.log(JSON.stringify(info), 'pageerrors:', errs.length);
  await p.setViewportSize({ width: 390, height: 844 });
  await p.goto('http://127.0.0.1:8099/index.html', { waitUntil: 'load' });
  await p.waitForTimeout(2200);
  await p.screenshot({ path: 'out/hero-mobile.png', clip: { x: 0, y: 0, width: 390, height: 844 } });
  const ctx2 = await b.newContext({ reducedMotion: 'reduce' }); const q = await ctx2.newPage();
  await q.goto('http://127.0.0.1:8099/index.html', { waitUntil: 'load' }); await q.waitForTimeout(1500);
  console.log('reduced-motion: word opacity', await q.evaluate(() => getComputedStyle(document.querySelector('.hw')).opacity));
  await b.close();
})();
