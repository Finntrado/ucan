const { chromium } = require('playwright');
(async () => { const b = await chromium.launch(); const p = await b.newPage();
  for (const [w, n] of [[1280, 'annual-forum-2025'], [412, 'annual-forum-2025'], [1280, 'city-mixers'], [412, 'city-mixers']]) {
    await p.setViewportSize({ width: w, height: 900 });
    await p.goto(`http://127.0.0.1:8099/${n}.html`, { waitUntil: 'load' });
    await p.evaluate(async () => { const h = document.body.scrollHeight; for (let y = 0; y < h; y += 700) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 40)); } });
    await p.waitForTimeout(1500);
    const r = await p.evaluate(() => { const a = [...document.querySelectorAll('.galx img, .mx-img img')].filter(i => !i.closest('[hidden]'));
      const picked = {}; a.forEach(i => { const m = (i.currentSrc || '').match(/-(\d+)\.webp$/); const k = m ? m[1] : 'other'; picked[k] = (picked[k] || 0) + 1; });
      return { n: a.length, loaded: a.filter(i => i.naturalWidth > 0).length, picked }; });
    const sel = n === 'city-mixers' ? '.mx-img .zoomhit' : '.galx .zoomhit';
    await p.locator(sel).first().scrollIntoViewIfNeeded(); await p.locator(sel).first().click(); await p.waitForTimeout(700);
    const lb = await p.evaluate(() => { const i = document.querySelector('.lbx img'); return (i.currentSrc || i.src).match(/-(\d+)\.webp$/)?.[1] + 'w natural ' + i.naturalWidth; });
    await p.keyboard.press('Escape');
    console.log(`${n.padEnd(18)} @${w}: ${r.loaded}/${r.n} loaded, sizes picked ${JSON.stringify(r.picked)}  | lightbox opens ${lb}`);
  }
  await b.close(); })();
