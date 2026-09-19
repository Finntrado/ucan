// Does the header still fit with the sixth menu item? For each width: horizontal
// overflow, whether the desktop nav overlaps the Subscribe button, and whether
// any nav label wraps onto two lines.   node navwidth.js [base] [page]
const { chromium } = require('playwright');
const BASE = process.argv[2] || 'http://localhost:8099';
const PAGE = process.argv[3] || 'about';
(async () => {
  const b = await chromium.launch();
  let bad = 0;
  for (const w of [1101, 1140, 1180, 1220, 1240, 1280, 1366, 1440, 1100, 1024, 768, 390, 320]) {
    const p = await b.newPage({ viewport: { width: w, height: 900 } });
    await p.goto(`${BASE}/${PAGE}`, { waitUntil: 'load' });
    const r = await p.evaluate(() => {
      const de = document.documentElement;
      const nav = document.querySelector('.ucnav'), cta = document.querySelector('.bar-cta'), brand = document.querySelector('.bar .brand');
      const vis = nav && getComputedStyle(nav.closest('.ucnav-bar')).display !== 'none';
      const kids = vis ? [...nav.children] : [];
      const n = vis ? { left: Math.min(...kids.map(k => k.getBoundingClientRect().left)), right: Math.max(...kids.map(k => k.getBoundingClientRect().right)) } : null;
      const wraps = vis ? [...document.querySelectorAll('.ucnav > .ucnav-i > .ucnav-t, .ucnav > a.ucnav-t')].filter(e => e.getBoundingClientRect().height > 36).length : 0;
      return {
        overflow: de.scrollWidth - de.clientWidth,
        navVisible: !!vis,
        gapToCta: vis && cta ? Math.round(cta.getBoundingClientRect().left - n.right) : null,
        gapToBrand: vis && brand ? Math.round(n.left - brand.getBoundingClientRect().right) : null,
        wraps,
      };
    });
    const fail = r.overflow > 0 || r.wraps > 0 || (r.navVisible && (r.gapToCta < 8 || r.gapToBrand < 8));
    if (fail) bad++;
    console.log((fail ? 'FAIL ' : 'ok   ') + w + ' ' + JSON.stringify(r));
    await p.close();
  }
  await b.close();
  process.exit(bad ? 1 : 0);
})();
