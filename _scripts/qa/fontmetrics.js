// How far apart are each webfont and the local fallback that stands in for it
// before it loads? Width ratio -> the size-adjust the fallback needs.
const { chromium } = require('playwright');
const SAMPLE = 'Turning individual effort into collective action across India’s cities and towns 0123456789';
(async () => {
  const b = await chromium.launch(); const p = await b.newPage();
  await p.goto('http://127.0.0.1:8099/index.html', { waitUntil: 'load' });
  await p.evaluate(() => document.fonts.ready);
  const r = await p.evaluate(SAMPLE => {
    const span = document.createElement('span');
    span.style.cssText = 'position:absolute;left:-9999px;top:0;white-space:nowrap;font-size:100px';
    span.textContent = SAMPLE; document.body.appendChild(span);
    const w = (fam, wt) => { span.style.fontFamily = fam; span.style.fontWeight = wt; return span.getBoundingClientRect().width; };
    const rows = [];
    for (const [real, fb, wt] of [['Archivo', 'Arch Fallback', 700], ['Archivo', 'Arch Fallback', 800],
                                  ['Public Sans', 'PS Fallback', 400], ['Public Sans', 'PS Fallback', 600],
                                  ['Archivo', 'Arial', 700], ['Public Sans', 'Arial', 400]]) {
      rows.push({ pair: `${real} vs ${fb} @${wt}`, real: Math.round(w(`"${real}"`, wt)), fb: Math.round(w(`"${fb}"`, wt)) });
    }
    return rows;
  }, SAMPLE);
  r.forEach(x => console.log(`${x.pair.padEnd(34)} real ${x.real}  fallback ${x.fb}  ratio ${(x.real / x.fb).toFixed(4)}`));
  await b.close();
})();
