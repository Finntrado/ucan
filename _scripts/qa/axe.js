const { chromium } = require('playwright');
const fs = require('fs');
(async () => { const b = await chromium.launch(); const p = await b.newPage();
  await p.setViewportSize({ width: 412, height: 823 });
  await p.goto('http://127.0.0.1:8099/' + (process.argv[2] || 'index') + '.html', { waitUntil: 'load' });
  await p.addScriptTag({ content: fs.readFileSync('node_modules/axe-core/axe.min.js', 'utf8') });
  const r = await p.evaluate(async () => {
    const res = await axe.run(document, { runOnly: ['color-contrast'] });
    return res.violations.flatMap(v => v.nodes.map(n => ({ t: n.target.join(' '), d: n.any.map(a => JSON.stringify(a.data)).join(' '),
      rel: n.any.flatMap(a => a.relatedNodes.map(x => x.target.join(' '))).join(' | ') })));
  });
  r.slice(0, 5).forEach(x => console.log(x.t, '\n   ', x.d.slice(0, 200), '\n    related:', x.rel));
  // the footer's own geometry vs #connect
  console.log(await p.evaluate(() => { const f = document.querySelector('footer').getBoundingClientRect(), c = document.querySelector('#connect').getBoundingClientRect();
    return `footer y=${Math.round(f.top + scrollY)}..${Math.round(f.bottom + scrollY)}  #connect y=${Math.round(c.top + scrollY)}..${Math.round(c.bottom + scrollY)}`; }));
  await b.close(); })();
