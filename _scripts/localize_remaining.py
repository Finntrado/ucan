# -*- coding: utf-8 -*-
"""Bring every remaining urban.org.in-hosted file into the repo.

An audit found 120 files still loaded from the live site: blog card
thumbnails and in-article images that sat outside the <figure class="post-hero">
localize_blog_heroes.py handled, the two generic og:image/twitter:image
photos, JSON-LD "image" fields, RFC's framework diagram, URC's six gallery
photos, the Forum highlights video, and eight PDFs (webinar decks, the Forum
report, the two Learning Network PDFs). All of it 404s the day the old site
comes down, whether or not anyone notices first.

Each unique URL is fetched once and written to assets/<kind>/, then every
occurrence across every page is rewritten: src=/poster=/href= to a
page-relative path (the convention already used for assets/img/blog etc.),
but og:image, twitter:image and JSON-LD "image" (read by crawlers
independently of the page) to an absolute https://urban.org.in/... URL -
the same convention canonical/hreflang/JSON-LD @id already use, since that
domain is where this build is meant to replace the old site.

Re-runnable: a URL already in the cache is not re-fetched; a page already
pointing locally is left alone.
"""
import glob, hashlib, io, json, os, re, time, urllib.parse, urllib.request

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')
CACHE = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'localize_remaining_cache.json')
UA = {'User-Agent': 'Mozilla/5.0'}

DIRS = {'img': 'assets/img/wp', 'doc': 'assets/docs', 'video': 'assets/video'}
KIND = {'png': 'img', 'jpg': 'img', 'jpeg': 'img', 'gif': 'img', 'webp': 'img',
        'pdf': 'doc', 'mp4': 'video'}

ASSET_RE = re.compile(
    r'(?:src|poster|href|content)="(https://(?:www\.)?urban\.org\.in/[^"]+?'
    r'\.(?:png|jpe?g|gif|webp|mp4|pdf))"'
    r'|"image":\s*"(https://(?:www\.)?urban\.org\.in/[^"]+?\.(?:png|jpe?g|gif|webp))"')


def write(f, s):
    for _ in range(6):
        try:
            io.open(f, 'w', encoding='utf-8', newline='').write(s)
            return True
        except OSError:
            time.sleep(0.35)
    return False


def fetch(url):
    # a URL copied verbatim out of scraped HTML can carry a literal non-ASCII
    # character (an en dash, here); urllib needs it percent-encoded first
    safe = urllib.parse.quote(url, safe=":/?#[]@!$&'()*+,;=%")
    return urllib.request.urlopen(urllib.request.Request(safe, headers=UA), timeout=120).read()


def local_name(url, data):
    base = os.path.splitext(os.path.basename(url.split('?')[0]))[0]
    slug = re.sub(r'[^a-z0-9]+', '-', base.lower()).strip('-')[:56] or 'file'
    ext = url.rsplit('.', 1)[1].lower()
    h8 = hashlib.sha1(data).hexdigest()[:8]
    return '%s-%s.%s' % (slug, h8, ext)


# ---------------------------------------------------------------- collect ---
pages = sorted(glob.glob(os.path.join(ROOT, '*.html')))
urls = set()
for f in pages:
    s = io.open(f, encoding='utf-8', errors='replace').read()
    for m in ASSET_RE.finditer(s):
        u = m.group(1) or m.group(2)
        # already our own local file, just written with the urban.org.in
        # prefix (deliberately, for og:image/JSON-LD) - not a fetch target
        if u and '/assets/' not in u:
            urls.add(u)
print('remote files referenced: %d' % len(urls))

cache = json.load(open(CACHE)) if os.path.exists(CACHE) else {}
mapping = {}          # url -> relative path from standalone/, forward slashes
fail = []
for i, u in enumerate(sorted(urls), 1):
    if u in cache and os.path.exists(os.path.join(ROOT, cache[u])):
        mapping[u] = cache[u]
        continue
    ext = u.rsplit('.', 1)[1].lower()
    kind = KIND.get(ext)
    if not kind:
        fail.append(u)
        continue
    try:
        data = fetch(u)
    except Exception as e:
        print('  ! failed:', u, e)
        fail.append(u)
        continue
    outdir = os.path.join(ROOT, DIRS[kind])
    os.makedirs(outdir, exist_ok=True)
    name = local_name(u, data)
    rel = DIRS[kind] + '/' + name
    with open(os.path.join(ROOT, rel), 'wb') as fh:
        fh.write(data)
    mapping[u] = cache[u] = rel
    print('  [%3d/%d] %6d KB  %s -> %s' % (i, len(urls), len(data) // 1024, u[-60:], rel))

json.dump(cache, open(CACHE, 'w'), indent=1)
if fail:
    print('unresolved (no local copy made):')
    for u in fail:
        print('   ', u)

# ------------------------------------------------------------- rewrite -----
CRAWLER_ATTRS = ('og:image', 'twitter:image')
n_pages = n_rep = 0
for f in pages:
    s = io.open(f, encoding='utf-8', newline='').read()
    orig = s

    # 1) og:image / twitter:image meta content -> absolute urban.org.in URL
    def meta_sub(m):
        global n_rep
        full, attr_kind, url = m.group(0), m.group(1), m.group(2)
        if url not in mapping:
            return full
        n_rep += 1
        return full.replace(url, 'https://urban.org.in/' + mapping[url])
    s = re.sub(r'<meta (?:property|name)="(og:image|twitter:image)" content="([^"]+)">', meta_sub, s)

    # 2) JSON-LD "image": "URL" -> absolute urban.org.in URL
    def ld_sub(m):
        global n_rep
        url = m.group(1)
        if url not in mapping:
            return m.group(0)
        n_rep += 1
        return '"image": "https://urban.org.in/%s"' % mapping[url]
    s = re.sub(r'"image":\s*"(https://(?:www\.)?urban\.org\.in/[^"]+?\.(?:png|jpe?g|gif|webp))"', ld_sub, s)

    # 3) remaining src=/poster=/href= -> page-relative local path
    def attr_sub(m):
        global n_rep
        attr, url = m.group(1), m.group(2)
        if url not in mapping:
            return m.group(0)
        n_rep += 1
        return '%s="%s"' % (attr, mapping[url])
    s = re.sub(r'(src|poster|href)="(https://(?:www\.)?urban\.org\.in/[^"]+?'
              r'\.(?:png|jpe?g|gif|webp|mp4|pdf))"', attr_sub, s)

    if s != orig:
        n_pages += 1
        write(f, s)

print('\nreplaced %d references across %d pages' % (n_rep, n_pages))

remaining = 0
for f in pages:
    s = io.open(f, encoding='utf-8', errors='replace').read()
    for m in ASSET_RE.finditer(s):
        u = m.group(1) or m.group(2)
        if u and '/assets/' not in u:
            remaining += 1
print('remaining genuinely-remote references after rewrite: %d' % remaining)
