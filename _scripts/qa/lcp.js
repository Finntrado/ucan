(async () => {
  const { default: lighthouse } = await import('lighthouse');
  const { chromium } = require('playwright');
  const PORT = 9225; const browser = await chromium.launch({ args: [`--remote-debugging-port=${PORT}`] });
  for (const n of process.argv.slice(2)) {
    const r = await lighthouse(`http://127.0.0.1:8099/${n === 'index' ? '' : n}`, { port: PORT, output: 'json', logLevel: 'error', onlyCategories: ['performance'] });
    const a = r.lhr.audits;
    console.log(`== ${n}  perf ${Math.round(r.lhr.categories.performance.score * 100)}  LCP ${a['largest-contentful-paint'].displayValue}`);
    const el = a['largest-contentful-paint-element'];
    try { el.details.items.forEach(it => { if (it.items) it.items.forEach(x => { if (x.node) console.log('   element:', x.node.snippet.slice(0, 140)); if (x.phase) console.log(`   ${x.phase.padEnd(22)} ${Math.round(x.timing)} ms`); }); }); } catch (e) {}
    const ins = a['lcp-breakdown-insight'] || a['lcp-phases-insight'];
    if (ins && ins.details) console.log('   breakdown:', JSON.stringify(ins.details).slice(0, 500));
    ['render-blocking-insight', 'network-dependency-tree-insight', 'image-delivery-insight', 'unused-css-rules', 'font-display-insight']
      .forEach(k => { if (a[k] && a[k].score !== null && a[k].score < 1) console.log(`   x ${k}: ${a[k].displayValue || ''}`); });
  }
  await browser.close();
})();
