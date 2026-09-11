// Mobile Lighthouse (the PageSpeed Insights configuration: Moto G Power,
// simulated slow 4G) against the local server.
//   node lh.js index about ...     -> scores + the metrics that drive them
//   node lh.js --detail index      -> also the failing audits
const fs = require('fs');
const path = require('path');

const CHROME = (() => {
  const base = path.join(process.env.LOCALAPPDATA, 'ms-playwright');
  for (const d of fs.readdirSync(base).filter(d => /^chromium-\d+$/.test(d)).sort().reverse())
    for (const sub of ['chrome-win64', 'chrome-win'])
      { const p = path.join(base, d, sub, 'chrome.exe'); if (fs.existsSync(p)) return p; }
})();

(async () => {
  const { default: lighthouse } = await import('lighthouse');
  const { chromium } = require('playwright');
  const args = process.argv.slice(2);
  const detail = args.includes('--detail');
  const pages = args.filter(a => !a.startsWith('--'));
  // chrome.exe can't be spawned directly on this machine; Playwright's launcher can
  const PORT = 9223;
  const browser = await chromium.launch({ args: [`--remote-debugging-port=${PORT}`] });
  const chrome = { port: PORT, kill: () => browser.close() };
  for (const n of pages) {
    const url = `http://127.0.0.1:8099/${n === 'index' ? '' : n}`;
    const r = await lighthouse(url, { port: chrome.port, output: 'json', logLevel: 'error',
      onlyCategories: ['performance', 'accessibility', 'best-practices', 'seo'] });
    const L = r.lhr, c = L.categories, a = L.audits;
    const sc = k => Math.round(c[k].score * 100);
    const m = k => a[k].displayValue;
    console.log(`${n.padEnd(22)} perf ${sc('performance')}  a11y ${sc('accessibility')}  bp ${sc('best-practices')}  seo ${sc('seo')}` +
      `   FCP ${m('first-contentful-paint')}  LCP ${m('largest-contentful-paint')}  TBT ${m('total-blocking-time')}  CLS ${m('cumulative-layout-shift')}  SI ${m('speed-index')}`);
    if (detail) {
      for (const [k, v] of Object.entries(a)) {
        if (v.score !== null && v.score < 0.9 && v.scoreDisplayMode !== 'informative' && v.scoreDisplayMode !== 'notApplicable')
          console.log(`     x ${k}: ${v.title}${v.displayValue ? ' — ' + v.displayValue : ''}`);
      }
      const lcp = a['largest-contentful-paint-element'];
      if (lcp && lcp.details && lcp.details.items && lcp.details.items[0] && lcp.details.items[0].items)
        console.log('     LCP element:', JSON.stringify(lcp.details.items[0].items[0].node && lcp.details.items[0].items[0].node.snippet).slice(0, 160));
    }
  }
  await chrome.kill();
})();
