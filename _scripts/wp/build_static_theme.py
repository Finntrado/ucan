# -*- coding: utf-8 -*-
"""Builds wp-theme/ucan as a *static mirror* of standalone/.

Replaces the earlier approach (tag `wp-php-templates`) of re-deriving every
page as PHP templates + CPTs, which kept drifting from the finished design
(page-specific CSS silently lost, etc.). Here nothing is re-derived: every
standalone/*.html page is copied byte-for-byte into wp-theme/ucan/pages/,
with exactly one change - relative URLs become placeholders that
functions.php swaps for the real theme/home URL at request time:

  assets/...  newsletters/...   ->  %%UCAN_THEME%%/assets/...
  about  /  newsletter#subscribe ->  %%UCAN_HOME%%/about  (only if about.html exists)

assets/ and newsletters/ are mirrored alongside. Re-run after any change to
standalone/ - it is the only way the theme gets updated.
"""
import io
import os
import re
import shutil

ROOT = os.path.normpath(os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', '..'))
SRC = os.path.join(ROOT, 'standalone')
THEME = os.path.join(ROOT, 'wp-theme', 'ucan')
PAGES = os.path.join(THEME, 'pages')

page_slugs = {f[:-5] for f in os.listdir(SRC) if f.endswith('.html')}
unresolved = set()


def rewrite_url(v):
    if v.startswith(('assets/', 'newsletters/')):
        return '%%UCAN_THEME%%/' + v
    if v == '/':
        return '%%UCAN_HOME%%/'
    if re.match(r'^[a-z0-9-]+(#[^"]*)?$', v):
        if v.split('#')[0] in page_slugs:
            return '%%UCAN_HOME%%/' + v
        unresolved.add(v)
    return v


def rewrite_attr(m):
    name, v = m.group(1), m.group(2)
    if name not in PAGE_LINK_ATTRS and not v.startswith(('assets/', 'newsletters/')):
        return m.group(0)
    if name == 'srcset':
        parts = [p.strip().split(' ', 1) for p in v.split(',')]
        v = ', '.join(' '.join([rewrite_url(p[0])] + p[1:]) for p in parts)
    else:
        v = rewrite_url(v)
    return '%s="%s"' % (name, v)


def rewrite_css_url(m):
    q, v = m.group(1), m.group(2)
    return 'url(%s%s%s' % (q, rewrite_url(v), q)


# Attributes that can hold a page slug. Asset paths are rewritten in ANY
# attribute (JS reads some from data-poster/data-full/etc.).
PAGE_LINK_ATTRS = ('href', 'data-tag')


def transform(html):
    html = re.sub(r'(?<![\w-])([a-z][\w-]*)="([^"]*)"', rewrite_attr, html)
    html = re.sub(r'url\(\s*([\'"]?)((?:assets|newsletters)/[^\'")]+)\1', rewrite_css_url, html)
    return html


def mirror(name):
    dst = os.path.join(THEME, name)
    if os.path.exists(dst):
        shutil.rmtree(dst)
    shutil.copytree(os.path.join(SRC, name), dst)


# empty rather than rmtree: on Windows a shell sitting in pages/ locks the
# directory itself, and rmtree dies halfway through
os.makedirs(PAGES, exist_ok=True)
for f in os.listdir(PAGES):
    os.remove(os.path.join(PAGES, f))

for slug in sorted(page_slugs):
    # newline='' keeps each page's own line endings (\r\r\n / \r\n / \n) intact
    with io.open(os.path.join(SRC, slug + '.html'), encoding='utf-8', newline='') as f:
        out = transform(f.read())
    with io.open(os.path.join(PAGES, slug + '.html'), 'w', encoding='utf-8', newline='') as f:
        f.write(out)

mirror('assets')
mirror('newsletters')

# Nothing relative may survive: any leftover would resolve against the WP
# URL instead of the theme folder and silently break.
leftover = 0
for slug in page_slugs:
    s = io.open(os.path.join(PAGES, slug + '.html'), encoding='utf-8').read()
    leftover += len(re.findall(r'(?:src|href|poster|srcset|data-full|url\()\s*=?\s*["\']?(?:assets|newsletters)/', s))
print('pages: %d  leftover relative asset refs: %d' % (len(page_slugs), leftover))
if unresolved:
    print('page-like hrefs with no matching page (left as-is):', sorted(unresolved))
if leftover:
    raise SystemExit(1)
