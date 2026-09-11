const { chromium } = require('playwright');
(async () => { const b = await chromium.launch(); const ctx = await b.newContext(); const p = await ctx.newPage();
  await p.setViewportSize({ width: 1280, height: 900 });
  const cdp = await ctx.newCDPSession(p); await cdp.send('Page.enable');
  const seen = []; cdp.on('Page.frameRequestedNavigation', e => seen.push(e.url));
  const errs = []; p.on('pageerror', e => errs.push(String(e)));
  await p.goto('http://127.0.0.1:8099/data-rights.html', { waitUntil: 'load' });
  for (const k of ['erase', 'withdraw']) {
    await p.locator(`.dr-go[data-req="${k}"]`).click(); await p.waitForTimeout(300);
    await p.fill('#dr-note', k === 'erase' ? 'Please also remove me from event lists.' : '');
    await p.locator('#dr-form button[type=submit]').click(); await p.waitForTimeout(500);
  }
  seen.filter(u => u.startsWith('mailto:')).forEach(u => { const q = new URL(u.replace('mailto:', 'mailto://x/'));
    console.log('TO:', u.slice(7, u.indexOf('?')), '\nSUBJECT:', decodeURIComponent(u.split('subject=')[1].split('&')[0]),
      '\nBODY:\n' + decodeURIComponent(u.split('body=')[1]).split('\n').map(l => '   ' + l).join('\n') + '\n'); });
  console.log('mailto navigations:', seen.filter(u => u.startsWith('mailto:')).length, '| page errors:', errs.length);
  await b.close(); })();
