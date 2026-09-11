const { chromium } = require('playwright');
(async () => {
  const b = await chromium.launch(); const p = await b.newPage();
  await p.setViewportSize({ width: 1280, height: 900 });
  for (const n of ['index','our-people','our-members','impact','meet-the-fellows','profile-siddharth-pandit','newsletter','annual-forum-2025','about']) {
    await p.goto(`http://127.0.0.1:8099/${n}.html`, { waitUntil: 'load' });
    await p.evaluate(async () => { const h = document.body.scrollHeight;
      for (let y = 0; y < h; y += 700) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 40)); } });
    await p.waitForTimeout(1500);
    const r = await p.evaluate(() => { const a = [...document.querySelectorAll('img')].filter(i => i.getAttribute('src') && i.getAttribute('src').startsWith('assets/img/'));
      return { local: a.length, loaded: a.filter(i => i.complete && i.naturalWidth > 0).length,
               broken: a.filter(i => i.complete && i.naturalWidth === 0).map(i => i.getAttribute('src')).slice(0, 3) }; });
    console.log(`${n.padEnd(26)} local images ${r.loaded}/${r.local}` + (r.broken.length ? '  BROKEN ' + r.broken.join(', ') : ''));
  }
  await b.close();
})();
