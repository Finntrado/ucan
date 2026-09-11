# -*- coding: utf-8 -*-
"""Serve Archivo and Public Sans from the site itself.

Every page fetched a Google Fonts stylesheet (render-blocking) which then
fetched the font files from a second host - two extra connections before
first paint, which PageSpeed priced at ~1.85 s on slow 4G. Both families are
SIL Open Font License, so self-hosting is permitted.

Downloads the woff2 files for the latin and latin-ext subsets (the browser
only fetches latin-ext if a page uses those characters), writes the
@font-face rules into each page head, preloads the two faces the first
screen needs, and removes the four Google Fonts <link>s. Re-runnable.
"""
import glob, io, os, re, time, urllib.request

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')
OUT = os.path.join(ROOT, 'assets', 'fonts')
UA = ('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 '
      '(KHTML, like Gecko) Chrome/130.0 Safari/537.36')
# union of every weight/style any page asked for
CSS_URL = ('https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,500;0,600;0,700;0,800;'
           '1,500;1,600&family=Public+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap')
KEEP = ('latin', 'latin-ext')
PRELOAD = [('Archivo', '700', 'normal'), ('Public Sans', '400', 'normal')]


def get(url):
    return urllib.request.urlopen(urllib.request.Request(url, headers={'User-Agent': UA}), timeout=60).read()


css = get(CSS_URL).decode('utf-8')
# Google's CSS labels each block with a /* subset */ comment
blocks = re.findall(r'/\*\s*([a-z-]+)\s*\*/\s*(@font-face\s*\{[^}]*\})', css)
os.makedirs(OUT, exist_ok=True)
for old in os.listdir(OUT):                       # clear the per-weight copies
    os.remove(os.path.join(OUT, old))
# Both families are variable fonts: every weight of a style points at the same
# file. Group by file so each is declared once with a weight range - otherwise
# the browser fetches the identical file once per weight.
groups = {}
for subset, block in blocks:
    if subset not in KEEP:
        continue
    fam = re.search(r"font-family:\s*'([^']+)'", block).group(1)
    wt = int(re.search(r'font-weight:\s*(\d+)', block).group(1))
    st = re.search(r'font-style:\s*(\w+)', block).group(1)
    url = re.search(r'src:\s*url\(([^)]+)\)', block).group(1)
    rng = re.search(r'unicode-range:\s*([^;]+);', block).group(1).strip()
    # Archivo's italic is close to an oblique, so the browser's synthesised
    # slant of the upright file looks near-identical - and saves a 38 KB file
    # that the homepage quote otherwise pulls in before first paint.
    if fam == 'Archivo' and st == 'italic':
        continue
    g = groups.setdefault((fam, st, subset, url), {'w': [], 'rng': rng})
    g['w'].append(wt)
faces, preload, total = [], [], 0
for (fam, st, subset, url), g in groups.items():
    lo, hi = min(g['w']), max(g['w'])
    name = '%s%s-%s.woff2' % (fam.lower().replace(' ', '-'), '-italic' if st == 'italic' else '', subset)
    path = os.path.join(OUT, name)
    if not os.path.exists(path):
        open(path, 'wb').write(get(url))
    total += os.path.getsize(path)
    wrange = str(lo) if lo == hi else '%d %d' % (lo, hi)
    faces.append("@font-face{font-family:'%s';font-style:%s;font-weight:%s;font-display:swap;"
                 "src:url(assets/fonts/%s) format('woff2');unicode-range:%s}" % (fam, st, wrange, name, g['rng']))
    if subset == 'latin' and st == 'normal' and fam in ('Archivo', 'Public Sans'):
        preload.append('<link rel="preload" href="assets/fonts/%s" as="font" type="font/woff2" crossorigin>' % name)

print('faces: %d  (%d KB on disk; a page downloads only the ones it uses)' % (len(faces), total // 1024))
HEAD = ''.join(preload) + '<style data-ucan="fonts">' + ''.join(faces) + '</style>'


def write(f, s):
    for _ in range(6):
        try:
            io.open(f, 'w', encoding='utf-8', newline='').write(s)
            return True
        except OSError:
            time.sleep(0.35)
    return False


n = 0
for f in sorted(glob.glob(os.path.join(ROOT, '*.html'))):
    s = io.open(f, encoding='utf-8', newline='').read()
    s = re.sub(r'<link[^>]*(?:fonts\.googleapis\.com|fonts\.gstatic\.com)[^>]*>\s*', '', s)
    s = re.sub(r'<link rel="preload" href="assets/fonts/[^"]+"[^>]*>', '', s)
    s = re.sub(r'<style data-ucan="fonts">.*?</style>', '', s, flags=re.S)
    # straight after <meta charset>/viewport so the font rules precede every stylesheet
    m = re.search(r'<meta name="viewport"[^>]*>', s)
    at = m.end() if m else s.index('<head>') + 6
    s = s[:at] + HEAD + s[at:]
    n += write(f, s)
print('self-hosted fonts wired into %d pages' % n)
