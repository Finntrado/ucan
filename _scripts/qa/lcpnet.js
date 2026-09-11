(async () => {
  const { default: lighthouse } = await import('lighthouse');
  const { chromium } = require('playwright');
  const PORT = 9226; const browser = await chromium.launch({ args: [`--remote-debugging-port=${PORT}`] });
  const n = process.argv[2] || 'index';
  const r = await lighthouse(`http://127.0.0.1:8099/${n === 'index' ? '' : n}`, { port: PORT, output: 'json', logLevel: 'error', onlyCategories: ['performance'] });
  const a = r.lhr.audits; const m = a.metrics.details.items[0];
  console.log(`observed FCP ${Math.round(m.observedFirstContentfulPaint)}ms  observed LCP ${Math.round(m.observedLargestContentfulPaint)}ms  | simulated FCP ${Math.round(m.firstContentfulPaint)} LCP ${Math.round(m.largestContentfulPaint)}`);
  const t0 = m.observedNavigationStart || 0;
  const reqs = a['network-requests'].details.items.filter(x => x.networkRequestTime !== undefined);
  reqs.sort((x, y) => x.networkRequestTime - y.networkRequestTime).forEach(x => {
    const s = Math.round(x.networkRequestTime), e = Math.round(x.networkEndTime);
    const pre = s <= m.observedLargestContentfulPaint ? '*' : ' ';
    console.log(`${pre} ${String(s).padStart(5)}-${String(e).padStart(5)}ms ${String(Math.round((x.transferSize || 0) / 1024)).padStart(4)}KB ${x.resourceType.padEnd(10)} ${x.priority.padEnd(8)} ${x.url.replace('http://127.0.0.1:8099/', '').slice(0, 70)}`);
  });
  await browser.close();
})();
