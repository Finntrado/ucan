// Rendered size of every logo tile, grouped, at three widths.
const { chromium } = require('playwright');
const GROUPS = {
  'index':             ['.logo-grid:not(.friends) > li', '.logo-grid.friends > li'],
  'our-members':       ['ul.mgrid:not([aria-label="Friends of U-CAN"]) > li', 'ul.mgrid[aria-label="Friends of U-CAN"] > li'],
  'annual-forum-2025': ['.ptrow > li'],
};
(async () => {
  const b = await chromium.launch(); const p = await b.newPage();
  for (const w of [1280, 820, 390]) {
    await p.setViewportSize({ width: w, height: 900 });
    for (const [n, sels] of Object.entries(GROUPS)) {
      await p.goto(`http://127.0.0.1:8099/${n}.html`, { waitUntil: 'load' });
      for (const sel of sels) {
        const r = await p.evaluate(sel => [...document.querySelectorAll(sel)].map(li => {
          const a = li.querySelector('a') || li, img = li.querySelector('img');
          const r = a.getBoundingClientRect(), s = img ? getComputedStyle(img) : {};
          return `${Math.round(r.width)}x${Math.round(r.height)} (img max ${s.maxWidth}/${s.maxHeight})`;
        }), sel);
        const u = [...new Set(r)];
        console.log(`@${w} ${n.padEnd(18)} ${sel.slice(0, 30).padEnd(30)} n=${String(r.length).padEnd(2)} ${u.slice(0, 3).join(' | ')}`);
      }
    }
  }
  await b.close();
})();
