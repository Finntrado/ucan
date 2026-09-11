const { chromium } = require('playwright');
(async () => { const b = await chromium.launch(); const p = await b.newPage(); await p.setViewportSize({ width: 1280, height: 900 });
  await p.goto('http://127.0.0.1:8099/data-rights.html', { waitUntil: 'load' });
  await p.addStyleTag({ content: '.bar{position:static!important}.rv{opacity:1!important;transform:none!important}' });
  await p.waitForTimeout(800);
  await p.screenshot({ path: 'out/dr-full.png', fullPage: true });
  await b.close(); })();
