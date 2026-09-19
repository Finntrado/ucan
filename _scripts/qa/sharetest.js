const { chromium } = require('playwright');
(async () => {
  const b = await chromium.launch();
  const ctx = await b.newContext({ viewport: { width: 1440, height: 900 }, permissions: ['clipboard-read', 'clipboard-write'] });
  const p = await ctx.newPage(); const errs = [];
  p.on('pageerror', e => errs.push(String(e))); p.on('console', m => { if (m.type() === 'error') errs.push(m.text()); });
  await p.goto('http://localhost:8099/urban-collaboration-checklist', { waitUntil: 'load' });
  await p.evaluate(() => document.querySelectorAll('.rv').forEach(e => e.classList.add('in')));
  const info = await p.evaluate(() => [...document.querySelectorAll('.art-share')].map(b => ({
    label: b.querySelector('.lbl').textContent,
    links: [...b.querySelectorAll('a')].map(a => a.textContent + ' -> ' + decodeURIComponent(a.getAttribute('href')).slice(0, 110)),
    copyVisible: !b.querySelector('.art-copy').hidden, nativeVisible: !b.querySelector('.art-native').hidden })));
  console.log(JSON.stringify(info, null, 1));
  await p.click('.art-share .art-copy');
  await p.waitForTimeout(300);
  console.log('label after click:', await p.textContent('.art-share .art-copy span'), '| clipboard:', await p.evaluate(() => navigator.clipboard.readText()));
  const el = await p.$('.art-share'); await el.screenshot({ path: 'out/seo/share-desktop.png' });
  const m = await b.newPage({ viewport: { width: 390, height: 844 } });
  await m.addInitScript(() => { navigator.share = () => Promise.resolve(); });
  await m.goto('http://localhost:8099/urban-collaboration-checklist', { waitUntil: 'load' });
  await m.evaluate(() => document.querySelectorAll('.rv').forEach(e => e.classList.add('in')));
  const e2 = await m.$('.art-share'); await e2.screenshot({ path: 'out/seo/share-mobile.png' });
  const e3 = await m.$('.art-share.bottom'); await e3.scrollIntoViewIfNeeded(); await e3.screenshot({ path: 'out/seo/share-bottom.png' });
  console.log('native btn on mobile:', await m.evaluate(() => !document.querySelector('.art-native').hidden), '| errors:', errs.length ? errs : 'none');
  await b.close();
})();
