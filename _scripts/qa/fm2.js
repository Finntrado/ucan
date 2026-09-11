const { chromium } = require('playwright');
const S = 'Turning individual effort into collective action across India’s cities and towns 0123456789';
(async () => {
  const b = await chromium.launch(); const p = await b.newPage();
  await p.goto('http://127.0.0.1:8099/learning-network.html', { waitUntil: 'load' });
  // force every weight to load
  await p.evaluate(async () => { for (const f of ['Archivo','Public Sans']) for (const w of [400,500,600,700,800])
    await document.fonts.load(`${w} 20px "${f}"`); });
  const r = await p.evaluate(S => {
    const sp = document.createElement('span');
    sp.style.cssText = 'position:absolute;left:-9999px;white-space:nowrap;font-size:100px'; sp.textContent = S;
    document.body.appendChild(sp);
    const W = (f, w) => { sp.style.fontFamily = f; sp.style.fontWeight = w; return sp.getBoundingClientRect().width; };
    const Z = (f, w) => { sp.textContent = '0'; const x = W(f, w); sp.textContent = S; return x; };
    const out = [];
    for (const w of [500, 600, 700, 800]) out.push(['Archivo', w, W('"Archivo"', w), W('Arial', 700), Z('"Archivo"', w), Z('Arial', 700)]);
    for (const w of [400, 500]) out.push(['Public Sans', w, W('"Public Sans"', w), W('Arial', 400), Z('"Public Sans"', w), Z('Arial', 400)]);
    for (const w of [600, 700]) out.push(['Public Sans', w, W('"Public Sans"', w), W('Arial', 700), Z('"Public Sans"', w), Z('Arial', 700)]);
    return out;
  }, S);
  r.forEach(([f, w, real, arial, z0, za]) => console.log(
    `${(f + ' ' + w).padEnd(16)} vs Arial${f === 'Archivo' || w >= 600 ? ' Bold' : '     '}: size-adjust ${(real / arial * 100).toFixed(1)}%   ("0": real ${z0.toFixed(1)} arial ${za.toFixed(1)})`));
  await b.close();
})();
