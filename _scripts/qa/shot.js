// node shot.js <page> <selector|-> <out.png> [width]
// Scrolls the whole page first so lazy images and scroll reveals have fired.
const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

(async () => {
  const [name, sel, out, width] = process.argv.slice(2);
  const dir = path.join(__dirname, 'out');
  fs.mkdirSync(dir, { recursive: true });
  const b = await chromium.launch();
  const p = await b.newPage();
  await p.setViewportSize({ width: +(width || 1280), height: 900 });
  await p.goto(`http://127.0.0.1:8099/${name.replace(/\.html$/, '')}.html`, { waitUntil: 'load' });
  await p.evaluate(async () => {
    const h = document.body.scrollHeight;
    for (let y = 0; y < h; y += 600) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 50)); }
    window.scrollTo(0, 0);
  });
  await p.waitForTimeout(1200);
  const file = path.join(dir, out);
  if (!sel || sel === '-') await p.screenshot({ path: file, fullPage: true });
  else {
    const el = p.locator(sel).first();
    await el.scrollIntoViewIfNeeded();
    await p.waitForTimeout(500);
    await el.screenshot({ path: file });
  }
  console.log(file);
  await b.close();
})();
