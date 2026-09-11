// node rules.js <page> <regex>  -> every stylesheet rule whose selector matches
const { chromium } = require('playwright');
(async () => {
  const [name, pat] = process.argv.slice(2);
  const b = await chromium.launch(); const p = await b.newPage();
  await p.setViewportSize({ width: 1280, height: 900 });
  await p.goto(`http://127.0.0.1:8099/${name}.html`, { waitUntil: 'load' });
  const out = await p.evaluate(pat => {
    const re = new RegExp(pat), res = [];
    const walk = (rules, media) => { for (const r of rules) {
      if (r.cssRules && r.media) walk(r.cssRules, r.media.mediaText);
      else if (r.selectorText && re.test(r.selectorText))
        res.push((media ? '@' + media + '  ' : '') + r.cssText.slice(0, 260));
    } };
    for (const ss of document.styleSheets) { try { walk(ss.cssRules, ''); } catch (e) {} }
    return res;
  }, pat);
  out.forEach(r => console.log('  ' + r));
  await b.close();
})();
