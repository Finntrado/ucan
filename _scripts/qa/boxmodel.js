const { chromium } = require('playwright');
(async () => {
  const b = await chromium.launch(); const p = await b.newPage();
  await p.setViewportSize({ width: 1280, height: 900 });
  await p.goto('http://127.0.0.1:8099/fellowship.html', { waitUntil: 'load' });
  const r = await p.evaluate(() => {
    const out = [];
    for (const t of ['Program Highlights', 'Shaping Urban Futures', 'What It Aims to Cultivate']) {
      const h = [...document.querySelectorAll('main h2, main h3')].find(x => x.textContent.includes(t));
      const head = h.parentElement;
      const kids = [...head.children].map(c => { const s = getComputedStyle(c);
        return `${c.tagName.toLowerCase()}.${String(c.className).split(' ')[0]} mt=${s.marginTop} mb=${s.marginBottom}`; });
      const hs = getComputedStyle(head);
      out.push(`${t}\n   parent .${String(head.className).replace(/ /g,'.')} display=${hs.display} gap=${hs.rowGap} mb=${hs.marginBottom}\n   ` + kids.join('\n   '));
    }
    return out.join('\n');
  });
  console.log(r);
  await b.close();
})();
