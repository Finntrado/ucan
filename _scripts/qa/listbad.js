const { chromium } = require('playwright');
(async () => { const b = await chromium.launch(); const p = await b.newPage(); await p.setViewportSize({ width: 1280, height: 900 });
  for (const n of ['privacy-policy', 'terms-of-use']) {
    await p.goto(`http://127.0.0.1:8099/${n}.html`, { waitUntil: 'load' });
    const r = await p.evaluate(() => { const out = [];
      document.querySelectorAll('main ul:not([class]) > li').forEach(li => {
        const dot = parseFloat(getComputedStyle(li, '::before').top) + li.getBoundingClientRect().top;
        const rg = document.createRange(); rg.selectNodeContents(li); const first = rg.getClientRects()[0];
        if (!first) return; const d = Math.round((first.top + first.height / 2) - (dot + 3.5));
        if (Math.abs(d) >= 8) { const c = li.firstElementChild; out.push(`off by ${d}px  first child <${c ? c.tagName.toLowerCase() : 'text'}> mt=${c ? getComputedStyle(c).marginTop : '-'} | ${li.innerHTML.replace(/\s+/g, ' ').slice(0, 90)}`); } });
      return out.slice(0, 4); });
    console.log('== ' + n); r.forEach(x => console.log('   ' + x));
  }
  await b.close(); })();
