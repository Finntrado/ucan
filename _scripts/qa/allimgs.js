// Confirm every <img> on every page actually loads (no naturalWidth 0), after
// scrolling the whole page so lazy images fire.
const { chromium } = require('playwright');
const fs = require('fs');
const DIR = 'C:/Users/Chatw/Desktop/ucan/standalone';
(async () => {
  const b = await chromium.launch(); const p = await b.newPage();
  await p.setViewportSize({ width: 1280, height: 900 });
  const pages = fs.readdirSync(DIR).filter(f => f.endsWith('.html')).map(f => f.slice(0, -5));
  let totalImgs = 0, totalBroken = 0; const brokenBy = [];
  for (const n of pages) {
    await p.goto(`http://127.0.0.1:8099/${n}.html`, { waitUntil: 'load' });
    await p.evaluate(async () => { const h = document.body.scrollHeight;
      for (let y = 0; y < h; y += 700) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 25)); }
      // fire any day-tab / gallery panes
      document.querySelectorAll('[role="tab"]').forEach(t => t.click());
    });
    await p.waitForTimeout(900);
    const r = await p.evaluate(() => [...document.querySelectorAll('img')].map(i => ({
      src: i.currentSrc || i.src, w: i.naturalWidth, hidden: i.closest('[hidden]') !== null })));
    const live = r.filter(x => !x.hidden);
    totalImgs += live.length;
    const broken = live.filter(x => x.w === 0 && x.src && !x.src.startsWith('data:'));
    if (broken.length) { totalBroken += broken.length; brokenBy.push([n, broken.map(x => x.src)]); }
  }
  console.log(`pages ${pages.length} | <img> elements checked ${totalImgs} | broken (naturalWidth 0) ${totalBroken}`);
  brokenBy.forEach(([n, srcs]) => { console.log('  ' + n); srcs.slice(0, 5).forEach(s => console.log('     ' + s)); });
  await b.close();
})();
