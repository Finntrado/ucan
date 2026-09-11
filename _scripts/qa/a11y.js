// Failing Lighthouse accessibility items with the elements involved.
(async () => {
  const { default: lighthouse } = await import('lighthouse');
  const { chromium } = require('playwright');
  const PORT = 9224;
  const browser = await chromium.launch({ args: [`--remote-debugging-port=${PORT}`] });
  for (const n of process.argv.slice(2)) {
    // PRESET: with --consented, behave like a returning visitor who already answered the banner
    const flags = { port: PORT, output: 'json', logLevel: 'error', onlyCategories: ['accessibility'] };
    const cfg = process.env.CONSENTED ? { extends: 'lighthouse:default', settings: { onlyCategories: ['accessibility'] },
      } : undefined;
    const r = await lighthouse(`http://127.0.0.1:8099/${n === 'index' ? '' : n}`, flags, cfg);
    const L = r.lhr;
    console.log(`== ${n}  a11y ${Math.round(L.categories.accessibility.score * 100)}`);
    for (const ref of L.categories.accessibility.auditRefs) {
      const a = L.audits[ref.id];
      if (a.score === null || a.score === 1) continue;
      console.log(`  x ${ref.id}`);
      ((a.details && a.details.items) || []).slice(0, 8).forEach(i => {
        const nd = i.node || {};
        console.log(`      ${(nd.selector || '').slice(0, 60).padEnd(60)} ${(nd.snippet || '').slice(0, 90)}`);
        if (i.node && i.node.explanation) console.log(`         ${i.node.explanation.split('\n').slice(-1)[0].slice(0, 140)}`);
      });
    }
  }
  await browser.close();
})();
