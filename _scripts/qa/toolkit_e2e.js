// End-to-end test of the Collaboration Toolkit against a WordPress site running this theme.
// Usage: node toolkit_e2e.js http://127.0.0.1:8088 [adminUser adminPass]
const { chromium } = require('playwright');
const BASE = process.argv[2] || 'http://127.0.0.1:8088';
const [USER, PASS] = [process.argv[3], process.argv[4]];
const WIDTHS = [1440, 1024, 768, 390, 320];
let fails = 0;
const ok = (cond, msg) => { console.log((cond ? 'PASS ' : 'FAIL ') + msg); if (!cond) fails++; };

async function overflow(page, label) {
  for (const w of WIDTHS) {
    await page.setViewportSize({ width: w, height: 900 });
    await page.waitForTimeout(150);
    const ov = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
    ok(ov <= 0, `${label}: no horizontal overflow at ${w}px (${ov})`);
  }
  await page.setViewportSize({ width: 1280, height: 900 });
}

async function rateAll(page, pick) {
  const names = await page.$$eval('.tk-q input[type=radio][value="1"]', els => els.map(e => e.name));
  for (let i = 0; i < names.length; i++) {
    await page.check(`input[name="${names[i]}"][value="${pick(i)}"]`, { force: true });
  }
  return names.length;
}

(async () => {
  const browser = await chromium.launch();
  const errors = [];
  const watch = p => { p.on('response', r => { if (r.status() >= 400) console.log('HTTP', r.status(), r.url()); }); p.on('pageerror', e => errors.push(String(e))); p.on('console', m => { if (m.type() === 'error') errors.push(m.text()); }); };

  // ---------------- organisation A
  const ctxA = await browser.newContext(); const a = await ctxA.newPage(); watch(a);
  await a.goto(BASE + '/collaboration-toolkit', { waitUntil: 'networkidle' });
  ok(await a.locator('h1').count() === 1, 'exactly one h1');
  ok(await a.locator('.tk-sec').count() === 9, 'Parts 1+2 selected by default: 9 sections (5 signals + 4 scenarios)');
  await a.check('input[name="tk-part"][value="p3"]', { force: true });
  await a.uncheck('input[name="tk-stage"][value="p3s3"]', { force: true });
  await a.uncheck('input[name="tk-stage"][value="p3s4"]', { force: true });
  ok(await a.locator('.tk-sec[id^="sec-"]').count() === 11, 'Part 3 with 2 stages adds 2 sections');
  await overflow(a, 'form');
  await a.fill('#tk-org', 'Alpha Foundation');
  await a.fill('#tk-person', 'Asha Rao');
  await a.fill('#tk-partner', 'Beta Collective');
  // submit with nothing rated -> error + highlights
  await a.click('#tk-submit');
  ok((await a.textContent('#tk-err')).includes('rate every statement'), 'blocks submit until every statement is rated');
  const n = await rateAll(a, i => [5, 4, 4, 5, 2][i % 5]);
  ok(n === 4*5 + 4+4+5+5 + 3+3, `rated ${n} statements`);
  ok((await a.textContent('#tk-prog-t')).startsWith(`${n} of ${n}`), 'progress shows all rated');
  await a.fill('#act-p1s1', 'Hold a joint problem-mapping workshop in December.');
  await a.fill('textarea[data-sid="p3s1"][data-f="right"]', 'We gave the idea time.');
  await a.fill('#tk-email', 'asha@example.org');
  await a.click('#tk-submit');
  ok((await a.textContent('#tk-err')).includes('consent'), 'blocks submit without consent');
  await a.check('#tk-consent', { force: true });
  await a.click('#tk-submit');
  await a.waitForSelector('#tk-invite', { timeout: 15000 });
  const invite = await a.inputValue('#tk-invite');
  const selfUrl = await a.inputValue('#tk-self');
  ok(/\?pair=[A-Z2-9]{10}$/.test(invite), 'partner link issued: ' + invite);
  ok(/\?results=[a-f0-9]{32}$/.test(selfUrl), 'results link issued');
  ok(a.url().includes('?results='), 'address bar switched to the results link');
  await overflow(a, 'waiting screen');

  // ---------------- organisation B
  const ctxB = await browser.newContext(); const b = await ctxB.newPage(); watch(b);
  await b.goto(invite.replace(/^https?:\/\/[^/]+/, BASE), { waitUntil: 'networkidle' });
  ok((await b.textContent('.tk-invited')).includes('Alpha Foundation'), 'B sees who invited them');
  ok(await b.locator('.tk-pick').count() === 0, 'B cannot change the parts');
  ok(await b.locator('.tk-sec[id^="sec-"]').count() === 11, 'B gets the same 11 sections');
  ok(await b.inputValue('#tk-org') === 'Beta Collective', 'B organisation prefilled from A');
  ok(!(await b.content()).includes('problem-mapping workshop'), "B cannot see A's answers");
  await rateAll(b, i => [2, 4, 5, 1, 3][i % 5]);
  await b.fill('#tk-person', 'Bilal Khan');
  await b.fill('#tk-email', 'bilal@example.org');
  await b.check('#tk-consent', { force: true });
  await b.click('#tk-submit');
  await b.waitForSelector('.tk-stats', { timeout: 15000 });
  ok((await b.textContent('.tk-dark h2')).includes('Alpha Foundation & Beta Collective'), 'joint results shown to B at once');
  ok(await b.locator('.tk-ok').count() === 1, 'B told the results were emailed');
  ok(await b.locator('.tk-snap').count() === 3, 'a snapshot table per part');
  ok(await b.locator('.tk-tell p.hit').count() === 2, '"What does this tell you?" pattern highlighted in Parts 1 and 2');
  ok(await b.locator('.tk-gaps li').count() > 0, 'biggest differences listed');
  ok((await b.content()).includes('problem-mapping workshop'), "A's suggested action now shown");
  await overflow(b, 'results');

  // results link reopens, for either side
  const r = await browser.newPage(); watch(r);
  await r.goto(selfUrl.replace(/^https?:\/\/[^/]+/, BASE), { waitUntil: 'networkidle' });
  await r.waitForSelector('.tk-stats');
  ok(true, 'results link reopens the joint results');
  // the same partner link cannot be used twice
  const again = await browser.newPage();
  await again.goto(invite.replace(/^https?:\/\/[^/]+/, BASE), { waitUntil: 'networkidle' });
  ok((await again.textContent('#tk-app')).includes('already complete'), 'partner link refuses a second submission');
  // bad keys
  await again.goto(BASE + '/collaboration-toolkit?results=' + 'f'.repeat(32), { waitUntil: 'networkidle' });
  ok((await again.textContent('#tk-app')).includes('could not be found'), 'unknown results key handled');

  // print view hides the site chrome
  await r.emulateMedia({ media: 'print' });
  ok(await r.locator('header.bar').isHidden(), 'print/PDF view hides the header');

  // headers
  const res = await r.request.get(BASE + '/collaboration-toolkit');
  ok((res.headers()['x-robots-tag'] || '').includes('noindex'), 'X-Robots-Tag noindex');
  ok((res.headers()['referrer-policy'] || '') === 'no-referrer', 'Referrer-Policy no-referrer');

  // admin
  if (USER) {
    const ad = await browser.newPage(); watch(ad);
    await ad.goto(BASE + '/wp-login.php');
    await ad.fill('#user_login', USER); await ad.fill('#user_pass', PASS); await ad.click('#wp-submit');
    await ad.waitForLoadState('networkidle');
    await ad.goto(BASE + '/wp-admin/admin.php?page=ucan-toolkit');
    const t = await ad.textContent('#wpbody');
    ok(t.includes('Alpha Foundation') && t.includes('bilal@example.org') && t.includes('Both completed'), 'wp-admin lists the pair with contacts and status');
    ok((await ad.textContent('#adminmenu')).includes('Form submissions'), 'admin menu is "Form submissions"');
    ok((await ad.textContent('#adminmenu')).includes('Collaboration Toolkit'), 'Collaboration Toolkit submenu present');
    const href = await ad.getAttribute('a.page-title-action >> nth=0', 'href');
    const csv = await ad.request.get(href);
    const body = await csv.text();
    ok(csv.headers()['content-type'].includes('text/csv') && body.includes('Alpha Foundation') && body.includes('Shared Curiosity About the Problem (A)'), 'CSV export works');
    await ad.goto(BASE + '/wp-admin/admin.php?page=ucan-toolkit&status=waiting');
    ok(!(await ad.textContent('#wpbody table')).includes('Both completed'), 'status filter works');
  }

  ok(errors.length === 0, 'no console/page errors' + (errors.length ? ': ' + errors.join(' | ') : ''));
  await browser.close();
  console.log(fails ? `\n${fails} FAILED` : '\nALL PASSED');
  process.exit(fails ? 1 : 0);
})();
