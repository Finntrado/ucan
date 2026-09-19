// Consent-gated GA: nothing requested before accept; accept loads it; reject stops it. Google is mocked.
const { chromium } = require('playwright');
const fs = require('fs');
const php = fs.readFileSync(__dirname + '/../../wp-theme/ucan/inc/analytics.php', 'utf8');
const js = php.split("<<<'JS'\n")[1].split('\nJS;')[0].replace('__ID__', 'G-TT92QE00S9');
(async () => {
  const b = await chromium.launch(); let bad = 0;
  const ok = (n, c) => { console.log((c ? 'ok    ' : 'FAIL  ') + n); if (!c) bad++; };
  const mk = async () => {
    const ctx = await b.newContext(); const p = await ctx.newPage(); const reqs = [];
    await p.route('**/*', r => { const u = r.request().url(); if (u.includes('googletagmanager')) { reqs.push(u); return r.fulfill({ status: 200, contentType: 'text/javascript', body: '' }); } return r.fulfill({ status: 200, contentType: 'text/html', body: '<html><body><button id="cc-accept">a</button><button id="cc-reject">r</button></body></html>' }); });
    await p.goto('https://www.urban.org.in/x'); return { p, reqs };
  };
  let { p, reqs } = await mk(); await p.evaluate(js); await p.waitForTimeout(300);
  ok('no consent: zero requests to Google', reqs.length === 0);
  await p.evaluate(() => { localStorage.setItem('ucan_consent_v1', JSON.stringify({ analytics: true })); });
  await p.click('#cc-accept'); await p.waitForTimeout(300);
  ok('accept: gtag.js requested once', reqs.length === 1 && reqs[0].includes('G-TT92QE00S9'));
  ok('accept: config has signals off', (await p.evaluate(() => JSON.stringify(window.dataLayer))).includes('allow_google_signals'));
  await p.evaluate(() => { document.cookie = '_ga=x; path=/'; document.cookie = '_ga_TT92QE00S9=y; path=/'; localStorage.setItem('ucan_consent_v1', JSON.stringify({ analytics: false })); });
  await p.click('#cc-reject'); await p.waitForTimeout(300);
  ok('reject: collection disabled + _ga cookies removed', (await p.evaluate(() => window['ga-disable-G-TT92QE00S9'] === true && !document.cookie.includes('_ga'))));
  ({ p, reqs } = await mk()); await p.evaluate(() => localStorage.setItem('ucan_consent_v1', JSON.stringify({ analytics: true })));
  await p.evaluate(js); await p.waitForTimeout(300);
  ok('returning visitor who accepted: loads on next page', reqs.length === 1);
  await b.close(); console.log(bad + ' failing'); process.exit(bad ? 1 : 0);
})();
