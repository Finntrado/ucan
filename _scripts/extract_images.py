# -*- coding: utf-8 -*-
"""Move base64-embedded images out of the pages into cacheable files.

Every page carried its images as base64 text: the homepage was 631 KB of which
516 KB was images, and the logo was embedded twice in every one of the 175
pages, so it could never be cached. On PageSpeed's slow-4G profile the document
alone took ~3 s to arrive before anything could paint.

Each unique image is written once to standalone/assets/img/<slug>-<hash>.<ext>
(identical images across pages share one file), and the attribute is pointed at
it. <img> tags without dimensions get their intrinsic width/height so the
browser reserves the space (no layout shift now that they load separately).
Images wider than 1000px also get an 800px WebP variant in srcset.

  python extract_images.py --dry-run     # survey only
  python extract_images.py               # rewrite pages
Re-runnable: pages with nothing embedded are left untouched.
"""
import glob, hashlib, io, os, re, sys, time, base64
from PIL import Image

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')
OUT = os.path.join(ROOT, 'assets', 'img')
DRY = '--dry-run' in sys.argv
MIN_BYTES = 1500              # tiny images (icons, dots) stay inline: a request costs more
EXT = {'image/webp': 'webp', 'image/png': 'png', 'image/jpeg': 'jpg', 'image/jpg': 'jpg',
       'image/gif': 'gif', 'image/svg+xml': 'svg', 'image/avif': 'avif', 'image/x-icon': 'ico'}
MARK = ';base64,'


def slugify(t):
    t = re.sub(r'[^a-z0-9]+', '-', (t or '').lower()).strip('-')
    return (t[:48].rstrip('-') or 'image')


def scan(s):
    """Yield (start, end, mime, b64) for every data:image base64 URI in s."""
    i = 0
    while True:
        i = s.find('data:image/', i)
        if i < 0:
            return
        j = s.find(MARK, i, i + 60)
        if j < 0:
            i += 11
            continue
        mime = s[i + 5:j]
        k = j + len(MARK)
        e = k
        n = len(s)
        while e < n and (s[e].isalnum() or s[e] in '+/='):
            e += 1
        yield i, e, mime, s[k:e]
        i = e


def write(f, s):
    for _ in range(6):
        try:
            io.open(f, 'w', encoding='utf-8', newline='').write(s)
            return True
        except OSError:
            time.sleep(0.35)
    return False


files = {}            # sha -> (relpath, w, h, variant_rel)
stats = dict(pages=0, occ=0, kept_inline=0, before=0, after=0)
os.makedirs(OUT, exist_ok=True)

for f in sorted(glob.glob(os.path.join(ROOT, '*.html'))):
    s = io.open(f, encoding='utf-8', newline='').read()
    if 'data:image/' not in s:
        continue
    stats['before'] += len(s)
    out, last, changed = [], 0, False
    for a, b, mime, b64 in list(scan(s)):
        raw_len = len(b64) * 3 // 4
        ext = EXT.get(mime)
        if not ext or raw_len < MIN_BYTES:
            stats['kept_inline'] += 1
            continue
        sha = hashlib.sha1(b64.encode()).hexdigest()[:10]
        if sha not in files:
            raw = base64.b64decode(b64 + '=' * (-len(b64) % 4))
            # name from the nearest alt text, else the attribute context
            tail = s[b:b + 600]
            alt = re.search(r'alt="([^"]*)"', tail)
            name = slugify(alt.group(1) if alt else '')
            if name == 'image':
                head = s[max(0, a - 200):a]
                name = 'favicon' if 'rel="icon"' in head or "rel='icon'" in head else 'image'
            rel = 'assets/img/%s-%s.%s' % (name, sha, ext)
            w = h = None
            variant = None
            if ext not in ('svg', 'ico'):
                try:
                    im = Image.open(io.BytesIO(raw))
                    w, h = im.size
                    if w > 1000 and not DRY:
                        small = im.convert('RGBA' if im.mode in ('RGBA', 'LA', 'P') else 'RGB')
                        small = small.resize((800, round(h * 800 / w)), Image.LANCZOS)
                        variant = 'assets/img/%s-%s-800.webp' % (name, sha)
                        small.save(os.path.join(ROOT, variant), 'WEBP', quality=78, method=6)
                except Exception:
                    pass
            if not DRY:
                with open(os.path.join(ROOT, rel), 'wb') as fh:
                    fh.write(raw)
            files[sha] = (rel, w, h, variant)
        rel = files[sha][0]
        out.append(s[last:a]); out.append(rel); last = b
        stats['occ'] += 1; changed = True
    if not changed:
        continue
    out.append(s[last:])
    s2 = ''.join(out)

    # dimensions + responsive variant on <img> tags that now point at a file
    def fix(m):
        tag = m.group(0)
        src = re.search(r'\ssrc="(assets/img/[^"]+)"', tag)
        if not src:
            return tag
        info = next((v for v in files.values() if v[0] == src.group(1)), None)
        if not info:
            return tag
        _, w, h, variant = info
        if w and h and not re.search(r'\swidth=', tag) and not re.search(r'\sheight=', tag):
            tag = tag.replace('<img', '<img width="%d" height="%d"' % (w, h), 1)
        if variant and 'srcset=' not in tag:
            tag = tag.replace('<img', '<img srcset="%s 800w, %s %dw" sizes="100vw"'
                              % (variant, info[0], w), 1)
        return tag
    s2 = re.sub(r'<img\b[^>]*>', fix, s2)
    stats['after'] += len(s2); stats['pages'] += 1
    if not DRY:
        if not write(f, s2):
            print('  ! could not write', f)

print('%s pages rewritten: %d | occurrences moved: %d | kept inline (tiny/unknown): %d'
      % ('[dry run]' if DRY else '', stats['pages'], stats['occ'], stats['kept_inline']))
print('unique image files: %d | page bytes %d MB -> %d MB'
      % (len(files), stats['before'] // 1048576, stats['after'] // 1048576))
