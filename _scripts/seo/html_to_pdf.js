// node html_to_pdf.js in.html out.pdf   (run from _scripts/qa so playwright resolves)
const { chromium } = require(require('path').join(__dirname, '..', 'qa', 'node_modules', 'playwright'));
const path = require('path');
(async () => {
  const [src, out] = process.argv.slice(2);
  const browser = await chromium.launch();
  const page = await browser.newPage();
  await page.goto('file:///' + path.resolve(src).replace(/\\/g, '/'), { waitUntil: 'load' });
  await page.evaluate(() => document.fonts.ready);
  await page.pdf({
    path: out, format: 'A4', printBackground: true,
    margin: { top: '12mm', bottom: '16mm', left: '13mm', right: '13mm' },
    displayHeaderFooter: true, headerTemplate: '<span></span>',
    footerTemplate: '<div style="font:8px Arial;width:100%;text-align:center;color:#57564F">U-CAN · urban.org.in/urban-collaboration-checklist · Page <span class="pageNumber"></span> of <span class="totalPages"></span></div>',
  });
  await page.screenshot({ path: out.replace(/\.pdf$/, '.png'), fullPage: true });
  await browser.close();
})();
