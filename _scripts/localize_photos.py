# -*- coding: utf-8 -*-
"""Serve a page's urban.org.in photos from the site, sized for where they sit.

City Mixers' 11 event photos and the Annual Forum's 61 gallery photos were
hot-linked from urban.org.in at full size - the Forum ones at 2560px, several
hundred KB each, for tiles a few hundred pixels wide. Each photo is fetched
once and written as WebP at a few widths; the <img> gets srcset/sizes, its
real dimensions, and data-full (the largest copy) for the lightbox.

  python localize_photos.py city-mixers
  python localize_photos.py annual-forum-2025
Re-runnable: photos already local are skipped.
"""
import hashlib, io, os, re, sys, time, urllib.request
from concurrent.futures import ThreadPoolExecutor
from PIL import Image

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')
UA = {'User-Agent': 'Mozilla/5.0'}

CONFIG = {
    # tile ~40% of the content width on desktop, full width on phones
    'city-mixers': dict(scope='class="mxlist"', widths=(640, 1024, 1600),
                        sizes=lambda i: '(max-width:760px) 100vw, 460px', folder='mixers'),
    # 3-up grid; every 7th tile spans two columns (see the .galx rules)
    'annual-forum-2025': dict(scope='data-tabs', widths=(640, 1024, 1600),
                              sizes=lambda i: ('(max-width:520px) 100vw, (max-width:820px) 100vw, 760px'
                                               if i % 7 == 0 else
                                               '(max-width:520px) 100vw, (max-width:820px) 50vw, 380px'),
                              folder='forum'),
}


def fetch(url):
    for _ in range(3):
        try:
            return urllib.request.urlopen(urllib.request.Request(url, headers=UA), timeout=90).read()
        except Exception as e:
            err = e
            time.sleep(1.5)
    raise err


def main(page):
    cfg = CONFIG[page]
    f = os.path.join(ROOT, page + '.html')
    s = io.open(f, encoding='utf-8', newline='').read()
    a = s.index(cfg['scope'])
    outdir = os.path.join(ROOT, 'assets', 'img', cfg['folder'])
    os.makedirs(outdir, exist_ok=True)

    tags = [(m.start(), m.end(), m.group(0)) for m in re.finditer(r'<img\b[^>]*>', s) if m.start() > a]
    remote = [(st, en, t, re.search(r'\ssrc="(https://urban\.org\.in/[^"]+)"', t))
              for st, en, t in tags]
    remote = [(st, en, t, m.group(1)) for st, en, t, m in remote if m]
    urls = sorted(set(r[3] for r in remote))
    print('%s: %d remote photos (%d unique)' % (page, len(remote), len(urls)))

    with ThreadPoolExecutor(6) as ex:
        blobs = dict(zip(urls, ex.map(fetch, urls)))

    made, before, after = {}, 0, 0
    for url, raw in blobs.items():
        im = Image.open(io.BytesIO(raw))
        im = im.convert('RGB')
        w0, h0 = im.size
        base = re.sub(r'[^a-z0-9]+', '-', os.path.splitext(os.path.basename(url))[0].lower()).strip('-')[:40]
        h8 = hashlib.sha1(raw).hexdigest()[:8]
        variants = []
        for w in cfg['widths']:
            if w > w0:
                continue
            rel = 'assets/img/%s/%s-%s-%d.webp' % (cfg['folder'], base, h8, w)
            (im.resize((w, round(h0 * w / w0)), Image.LANCZOS) if w != w0 else im).save(
                os.path.join(ROOT, rel), 'WEBP', quality=74, method=6)
            variants.append((w, rel))
        if not variants:                       # smaller than every target width
            rel = 'assets/img/%s/%s-%s-%d.webp' % (cfg['folder'], base, h8, w0)
            im.save(os.path.join(ROOT, rel), 'WEBP', quality=74, method=6)
            variants.append((w0, rel))
        made[url] = (variants, w0, h0)
        before += len(raw)
        after += os.path.getsize(os.path.join(ROOT, variants[0][1]))

    out, last = [], 0
    for idx, (st, en, tag, url) in enumerate(remote):
        variants, w0, h0 = made[url]
        small_w, small = variants[0]
        big = variants[-1][1]
        t = re.sub(r'\s(?:src|srcset|sizes|width|height|data-full)="[^"]*"', '', tag)
        t = t.replace('<img', '<img src="%s" srcset="%s" sizes="%s" width="%d" height="%d" data-full="%s"' % (
            small, ', '.join('%s %dw' % (r, w) for w, r in variants), cfg['sizes'](idx % 61 if page != 'annual-forum-2025' else pane_index(s, st)),
            small_w, round(h0 * small_w / w0), big), 1)
        out.append(s[last:st]); out.append(t); last = en
    out.append(s[last:])
    s = ''.join(out)

    # lightbox: open the largest copy, not whatever the tile happened to pick
    s = s.replace('pic.src = src.currentSrc || src.src;',
                  "pic.src = src.getAttribute('data-full') || src.currentSrc || src.src;")
    io.open(f, 'w', encoding='utf-8', newline='').write(s)
    print('  written: %d photos, %d MB remote -> %d KB at the size a phone loads'
          % (len(made), before // 1048576, after // 1024))


def pane_index(s, pos):
    """Position of an image within its day pane (tiles are counted per pane)."""
    pane = s.rfind('<div class="gal galx">', 0, pos)
    return s.count('<img', pane, pos)


if __name__ == '__main__':
    for p in sys.argv[1:]:
        main(p)
