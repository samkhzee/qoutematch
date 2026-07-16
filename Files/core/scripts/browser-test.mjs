import puppeteer from 'puppeteer';

const browser = await puppeteer.launch({ headless: true });
const page = await browser.newPage();
const errors = [];
page.on('pageerror', (err) => errors.push(err.message));
page.on('console', (msg) => {
    if (msg.type() === 'error') errors.push(`console: ${msg.text()}`);
});

await page.goto('http://127.0.0.1:8000', { waitUntil: 'networkidle0', timeout: 30000 });

const appHtml = await page.$eval('#app', (el) => el.innerHTML.trim());
const hasHeader = await page.$('#header');
const hasPreloader = await page.$('.preloader');
const preloaderVisible = hasPreloader
    ? await page.evaluate(() => {
          const el = document.querySelector('.preloader');
          const style = window.getComputedStyle(el);
          return style.display !== 'none' && style.opacity !== '0' && style.visibility !== 'hidden';
      })
    : false;

console.log('APP_INNER_HTML_LENGTH:', appHtml.length);
console.log('HAS_HEADER:', !!hasHeader);
console.log('HAS_PRELOADER:', !!hasPreloader);
console.log('PRELOADER_VISIBLE:', preloaderVisible);
console.log('ERRORS:', errors.length ? errors.join('\n') : 'none');

await browser.close();
