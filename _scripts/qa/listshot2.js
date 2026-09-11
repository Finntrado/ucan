const { chromium } = require('playwright');
(async () => { const b = await chromium.launch(); const p = await b.newPage(); await p.setViewportSize({ width: 1280, height: 900 });
  await p.goto('http://127.0.0.1:8099/privacy-policy.html', { waitUntil: 'load' });
  const ul = p.locator('main ul:not([class])', { hasText: 'means a unique account' }).first();
  await ul.scrollIntoViewIfNeeded(); await p.waitForTimeout(300);
  const box = await ul.boundingBox(); await p.screenshot({ path: 'out/pp-list2.png', clip: { x: box.x - 10, y: box.y, width: Math.min(box.width + 20, 900), height: Math.min(box.height, 420) } });
  await b.close(); })();
