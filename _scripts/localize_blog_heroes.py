# -*- coding: utf-8 -*-
"""Blog posts: serve each post's hero photo from the site as WebP.

The hero image is every post's LCP element, and it was hot-linked from
urban.org.in as a 1024px PNG/JPEG - a second origin to connect to before the
largest thing on the page could paint (LCP 3.1 s on PageSpeed's slow-4G run).
Each is downloaded once, re-encoded as WebP at 1024 and 640 px, and the
<img> gets both via srcset plus its real dimensions. The alt text is kept.
Re-runnable: already-local heroes are skipped.
"""
import glob, hashlib, io, os, re, time, urllib.request
from PIL import Image

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')
OUT = os.path.join(ROOT, 'assets', 'img', 'blog')
UA = {'User-Agent': 'Mozilla/5.0'}
os.makedirs(OUT, exist_ok=True)


def write(f, s):
    for _ in range(6):
        try:
            io.open(f, 'w', encoding='utf-8', newline='').write(s)
            return True
        except OSError:
            time.sleep(0.35)
    return False


done = skipped = failed = 0
before = after = 0
for f in sorted(glob.glob(os.path.join(ROOT, 'blog-*.html'))):
    if os.path.basename(f).startswith('blog-tag-'):
        continue
    s = io.open(f, encoding='utf-8', newline='').read()
    m = re.search(r'<figure class="post-hero"><img\b([^>]*)>', s)
    if not m:
        continue
    attrs = m.group(1)
    src = re.search(r'\ssrc="([^"]+)"', attrs)
    if not src or not src.group(1).startswith('http'):
        skipped += 1
        continue
    url = src.group(1)
    try:
        raw = urllib.request.urlopen(urllib.request.Request(url, headers=UA), timeout=60).read()
        im = Image.open(io.BytesIO(raw))
        im = im.convert('RGB') if im.mode not in ('RGB',) else im
    except Exception as e:
        failed += 1
        print('  ! could not fetch', os.path.basename(f), url, e)
        continue
    slug = os.path.basename(f)[5:-5][:56]
    h8 = hashlib.sha1(raw).hexdigest()[:8]
    w0, h0 = im.size
    names = {}
    for w in (1024, 640):
        if w > w0 and w != 640:
            w = w0
        img = im.resize((w, round(h0 * w / w0)), Image.LANCZOS) if w != w0 else im
        rel = 'assets/img/blog/%s-%s-%d.webp' % (slug, h8, w)
        img.save(os.path.join(ROOT, rel), 'WEBP', quality=76, method=6)
        names[w] = (rel, img.size)
    big_w = max(names)
    big, (bw, bh) = names[big_w]
    small = names[640][0]
    before += len(raw); after += os.path.getsize(os.path.join(ROOT, big))
    new_attrs = re.sub(r'\ssrc="[^"]+"', ' src="%s"' % big, attrs)
    new_attrs = re.sub(r'\s(?:width|height|srcset|sizes)="[^"]*"', '', new_attrs)
    new_attrs = (' width="%d" height="%d" srcset="%s 640w, %s %dw" sizes="(max-width:1180px) 100vw, 1136px"'
                 % (bw, bh, small, big, big_w)) + new_attrs
    s = s[:m.start(1)] + new_attrs + s[m.end(1):]
    if write(f, s):
        done += 1
print('blog heroes localised: %d | already local: %d | failed: %d' % (done, skipped, failed))
if done:
    print('hero bytes: %d KB remote -> %d KB local (1024w)' % (before // 1024, after // 1024))
