// Load pages with vercel.json's real Content-Security-Policy applied to our own
// responses (as Vercel would; third-party responses carry their own headers)
// and exercise the interactive parts. Any "Refused to ..." is a violation.
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');
const V = JSON.parse(fs.readFileSync(path.join(__dirname, '..', '..', 'standalone', 'vercel.json'), 'utf8'));
const CSP = V.headers[0].headers.find(h => h.key === 'Content-Security-Policy').value;
const PAGES = process.argv.slice(2).length ? process.argv.slice(2) :
  ['index', 'about', 'rfc', 'annual-forum-2025', 'newsletter', 'fellowship', 'newsletter-october-2025',
   'policy-webinars', 'city-champions', 'city-mixers', 'learning-network', 'fellow-blogs', 'our-people', 'privacy-policy'];

(async () => {
  const b = await chromium.launch(); const ctx = await b.newContext();
  await ctx.route('**/*', async route => {
    if (!route.request().url().startsWith('http://127.0.0.1:8099')) return route.continue();
    const r = await route.fetch();
    route.fulfill({ response: r, headers: { ...r.headers(), 'content-security-policy': CSP } });
  });
  const p = await ctx.newPage(); const viol = [];
  p.on('console', m => { if (/Content Security Policy|Refused to/i.test(m.text())) viol.push(m.text()); });
  for (const n of PAGES) {
    const before = viol.length;
    try {
    await p.goto(`http://127.0.0.1:8099/${n}.html`, { waitUntil: 'load' });
    await p.evaluate(() => document.fonts.ready);
    await p.evaluate(async () => { const h = document.body.scrollHeight;
      for (let y = 0; y < h; y += 800) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 40)); } });
    await p.waitForTimeout(700);
    const fonts = await p.evaluate(() => [...document.fonts].filter(f => f.status === 'loaded').map(f => f.family).filter((v, i, a) => a.indexOf(v) === i).join(','));
    await p.evaluate(() => {
      document.querySelector('.ytlite')?.click();
      document.querySelector('.zoomhit')?.click();
      const f = document.querySelector('#fnform');
      if (f) { f.querySelector('#f-email').value = 'a@b.in'; f.querySelector('#f-consent').checked = true;
               f.querySelector('button[type=submit]').click(); }
    });
    await p.waitForTimeout(500);
    const k = viol.length - before;
    console.log((k ? 'VIOLATION ' : 'ok        ') + n.padEnd(26) + ' fonts: ' + fonts + (k ? '  (' + k + ')' : ''));
    } catch (e) { console.log('SKIP      ' + n.padEnd(26) + ' (' + String(e).split('\n')[0].slice(0, 80) + ')'); }
  }
  if (viol.length) [...new Set(viol)].slice(0, 8).forEach(v => console.log('  ' + v.slice(0, 200)));
  else console.log('\nNo CSP violations.');
  await b.close();
})();
