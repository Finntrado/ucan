// Every internal href/src must resolve to a file in standalone/.
const fs = require('fs');
const path = require('path');

const DIR = path.join(__dirname, '..', '..', 'standalone');
const pages = fs.readdirSync(DIR).filter(f => f.endsWith('.html'));
const have = new Set(pages.map(f => f.slice(0, -5)));

let assets = 0;
const walk = rel => {
  const abs = path.join(DIR, rel);
  if (!fs.existsSync(abs)) return;
  for (const e of fs.readdirSync(abs, { withFileTypes: true })) {
    const r = path.posix.join(rel, e.name);
    if (e.isDirectory()) walk(r); else { have.add(r); assets++; }
  }
};
['newsletters', 'assets'].forEach(walk);

const broken = [];
const corrupt = [];
for (const f of pages) {
  const s = fs.readFileSync(path.join(DIR, f), 'utf8');
  // A regex back-reference like \1 passed through a shell heredoc lands as a
  // literal 0x01 byte and silently eats the markup it was meant to keep.
  if (/[\x00-\x08\x0B\x0C\x0E-\x1F]/.test(s)) corrupt.push(f);
  for (const m of s.matchAll(/(?:href|src)="([^"#?][^"]*)"/g)) {
    let t = m[1];
    if (/^(https?:|mailto:|tel:|data:|#|\/\/)/.test(t) || t === '/') continue;
    t = t.replace(/^\.\//, '').split('#')[0].split('?')[0];
    if (!t || have.has(t) || have.has(t.replace(/\.html$/, '')) ||
        fs.existsSync(path.join(DIR, t))) continue;
    broken.push(`${f} -> ${t}`);
  }
}
console.log(`pages ${pages.length} | assets ${assets} | broken internal links ${broken.length}` +
            ` | pages with control bytes ${corrupt.length}`);
broken.slice(0, 25).forEach(b => console.log('   ' + b));
corrupt.forEach(f => console.log('   CONTROL BYTE in ' + f));
process.exit(broken.length || corrupt.length ? 1 : 0);
