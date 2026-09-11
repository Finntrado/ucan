const { chromium } = require('playwright');
(async () => { const b = await chromium.launch(); const p = await b.newPage();
  await p.setViewportSize({ width: 1280, height: 900 });
  await p.goto('http://127.0.0.1:8099/fellow-blogs.html', { waitUntil: 'load' });
  const btn = p.locator('button.filter-btn.line, button', { hasText: /show all/i }).first();
  if (await btn.count()) { await btn.click(); await p.waitForTimeout(300); }
  await p.evaluate(async () => { const h = document.body.scrollHeight;
    for (let y = 0; y < h; y += 500) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 40)); } });
  await p.waitForTimeout(1500);
  const r = await p.evaluate(() => [...document.querySelectorAll('img')].filter(i => !i.closest('[hidden]'))
    .map(i => ({ src: i.currentSrc || i.src, w: i.naturalWidth })));
  const broken = r.filter(x => x.w === 0 && !x.src.startsWith('data:'));
  console.log('imgs', r.length, 'broken', broken.length);
  broken.slice(0, 5).forEach(x => console.log('  ' + x.src));
  await b.close(); })();
