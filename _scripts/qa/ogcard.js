// Renders a 1200x630 social-share card from the site's own logo (no external
// fetch), so og:image/twitter:image never depend on the old site staying up.
//   node ogcard.js <outfile> "<eyebrow>" "<title>"
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

(async () => {
  const [out, eyebrow, title] = process.argv.slice(2);
  const logoPath = path.join(__dirname, '..', '..', 'standalone', 'assets', 'img',
    'u-can-urban-collective-action-network-08e2484273.svg');
  const logoSvg = fs.readFileSync(logoPath, 'utf8');
  const html = `<!doctype html><html><head><meta charset="utf-8"><style>
    @font-face{font-family:'Archivo';font-weight:800;src:local('Arial Bold')}
    html,body{margin:0;width:1200px;height:630px;overflow:hidden}
    .card{width:1200px;height:630px;position:relative;background:#0E5348;
      display:flex;flex-direction:column;justify-content:center;padding:0 90px;
      box-sizing:border-box;font-family:Arial,sans-serif;color:#FBFAF6}
    .card::before{content:"";position:absolute;inset:0;
      background-image:radial-gradient(circle,rgba(251,250,246,.14) 1.6px,transparent 1.8px);
      background-size:26px 26px;opacity:.5}
    .card::after{content:"";position:absolute;right:-140px;top:-140px;width:520px;height:520px;
      border-radius:50%;background:radial-gradient(circle,rgba(78,198,178,.35),rgba(78,198,178,0) 70%)}
    .logo{position:relative;width:280px;height:auto;margin-bottom:44px;filter:brightness(0) invert(1)}
    .logo svg{width:100%;height:auto;display:block}
    .eyebrow{position:relative;font-weight:800;font-size:20px;letter-spacing:.14em;text-transform:uppercase;
      color:#CDDE71;margin:0 0 18px}
    .title{position:relative;font-weight:800;font-size:52px;line-height:1.18;letter-spacing:-.01em;
      max-width:920px;margin:0}
    </style></head><body><div class="card">
      <div class="logo">${logoSvg}</div>
      <p class="eyebrow">${eyebrow}</p>
      <h1 class="title">${title}</h1>
    </div></body></html>`;
  const b = await chromium.launch();
  const p = await b.newPage({ viewport: { width: 1200, height: 630 } });
  await p.setContent(html, { waitUntil: 'load' });
  await p.screenshot({ path: out, type: 'jpeg', quality: 90 });
  await b.close();
  console.log('wrote', out);
})();
