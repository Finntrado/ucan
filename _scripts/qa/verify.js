// Structure + overflow check across every page at the widths the site must
// support. Usage: node verify.js            (all pages)
//                 node verify.js rfc index  (a subset)
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const DIR = path.join(__dirname, '..', '..', 'standalone');
const WIDTHS = [1440, 1180, 1024, 960, 900, 820, 768, 640, 560, 480, 390, 360, 320];
const BASE = 'http://127.0.0.1:8099';

(async () => {
  const only = process.argv.slice(2);
  const pages = only.length ? only
    : fs.readdirSync(DIR).filter(f => f.endsWith('.html')).map(f => f.slice(0, -5));

  const browser = await chromium.launch();
  const page = await browser.newPage();
  const errors = [];
  page.on('pageerror', e => errors.push(String(e)));
  page.on('console', m => { if (m.type() === 'error') errors.push('console: ' + m.text()); });

  let fails = 0;
  for (const name of pages) {
    const before = errors.length;
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto(`${BASE}/${name}.html`, { waitUntil: 'load' });

    const meta = await page.evaluate(() => {
      const ld = [...document.querySelectorAll('script[type="application/ld+json"]')];
      let ldOk = true;
      for (const s of ld) { try { JSON.parse(s.textContent); } catch { ldOk = false; } }
      return {
        h1: document.querySelectorAll('h1').length,
        ld: ld.length, ldOk,
        secNum: document.querySelectorAll('.sec-num, .sec-mark').length,
      };
    });

    const over = [];
    for (const w of WIDTHS) {
      await page.setViewportSize({ width: w, height: 900 });
      const d = await page.evaluate(() =>
        document.documentElement.scrollWidth - document.documentElement.clientWidth);
      if (d > 0) over.push(`${w}px:+${d}`);
    }

    // Remote images from urban.org.in occasionally fail to fetch; that's the
    // network, not the page, so don't count it.
    const jsErr = errors.slice(before)
      .filter(e => !/Failed to load resource|net::ERR_/.test(e)).length;
    const ok = meta.h1 === 1 && meta.ld >= 1 && meta.ldOk &&
               meta.secNum === 0 && jsErr === 0 && over.length === 0;
    if (!ok) {
      fails++;
      console.log(`FAIL  ${name.padEnd(34)} h1=${meta.h1} ld=${meta.ld} ` +
        `secnum=${meta.secNum} jsErr=${jsErr}` + (over.length ? '  OVERFLOW ' + over.join(' ') : ''));
      errors.slice(before).slice(0, 3).forEach(e => console.log('      ' + e.slice(0, 160)));
    }
  }
  console.log(fails ? `\n${fails} of ${pages.length} page(s) failed.` : `\nAll ${pages.length} pages passed.`);
  await browser.close();
  process.exit(fails ? 1 : 0);
})();
