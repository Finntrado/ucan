// Gap from each section heading to the first text beneath it.
const { chromium } = require('playwright');
(async () => {
  const [name, w] = process.argv.slice(2);
  const b = await chromium.launch(); const p = await b.newPage();
  await p.setViewportSize({ width: +(w || 1280), height: 900 });
  await p.goto(`http://127.0.0.1:8099/${name}.html`, { waitUntil: 'load' });
  await p.evaluate(() => document.fonts.ready);
  const r = await p.evaluate(() => [...document.querySelectorAll('main h2, main h3.fx-h3-lg, main .cultivate h3')].map(h => {
    const hr = h.getBoundingClientRect();
    // the kicker above, and the first paragraph/list that follows in reading order
    const k = h.previousElementSibling && h.previousElementSibling.classList.contains('kicker') ? h.previousElementSibling : null;
    let n = h; const all = [...document.querySelectorAll('main p, main ul, main ol')];
    const next = all.find(e => e.getBoundingClientRect().top > hr.bottom - 1 && !e.classList.contains('kicker'));
    const cs = getComputedStyle(h);
    return { h: h.textContent.trim().slice(0, 40), tag: h.tagName, fs: cs.fontSize,
             kickerGap: k ? Math.round(hr.top - k.getBoundingClientRect().bottom) : null,
             textGap: next ? Math.round(next.getBoundingClientRect().top - hr.bottom) : null,
             next: next ? (next.className || next.tagName).toString().slice(0, 18) : '' };
  }));
  r.forEach(x => console.log(`${x.tag} ${x.fs.padEnd(6)} kicker→h ${String(x.kickerGap).padEnd(4)} h→text ${String(x.textGap).padEnd(4)} [${x.next.padEnd(18)}] ${x.h}`));
  await b.close();
})();
