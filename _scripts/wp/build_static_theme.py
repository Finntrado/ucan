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
import collections
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

# --- the old site's URLs -> this site's pages --------------------------------
# Every page's <link rel=canonical> is its old urban.org.in URL
# (/about-us/, /member/<slug>/, /etn/<slug>/ ...). Once the old site is
# switched off those must point at the new pages, and anyone arriving on an
# old URL must be redirected. Both come from this one map.
OLD_SITE = re.compile(r'https?://(?:www\.)?urban\.org\.in(/[^"\'<>\s#?,)\]]*)?')


def old_key(path):
    return (path or '').strip('/').lower()


OLD_TO_SLUG = {}
for _slug in page_slugs:
    _s = io.open(os.path.join(SRC, _slug + '.html'), encoding='utf-8').read()
    _m = re.search(r'<link rel="canonical" href="([^"]+)"', _s)
    _o = OLD_SITE.match(_m.group(1)) if _m else None
    if not _o:
        raise SystemExit('%s: no urban.org.in canonical to map' % _slug)
    _k = old_key(_o.group(1))
    if _k in OLD_TO_SLUG:
        raise SystemExit('two pages claim the old URL /%s/: %s, %s' % (_k, OLD_TO_SLUG[_k], _slug))
    OLD_TO_SLUG[_k] = _slug
dead_old_links = collections.Counter()


def rewrite_old_site(m):
    """An absolute urban.org.in URL -> the same thing on this site."""
    path = m.group(1) or ''
    if re.match(r'/(assets|newsletters)/', path):
        return '%%UCAN_THEME%%' + path
    slug = OLD_TO_SLUG.get(old_key(path))
    if slug is None:
        dead_old_links[m.group(0)] += 1
        return m.group(0)
    return '%%UCAN_HOME%%/' + ('' if slug == 'index' else slug)


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
    # canonical / hreflang / og:url / og:image / JSON-LD / in-page links to the
    # old site - everything absolute - now names this site
    html = OLD_SITE.sub(rewrite_old_site, html)
    # newsletter forms save to this WordPress install (inc/subscribers.php)
    html, n = re.subn(r'<form\b(?![^>]*data-endpoint)([^>]*\bmethod="post")',
                      r'<form action="%%UCAN_API%%" data-endpoint="%%UCAN_API%%"\1', html)
    transform.forms += n
    return html


transform.forms = 0


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
# old URL -> new page, for functions.php's 301s (skip the ones that already
# are the new URL, e.g. /impact/ -> /impact, which the trailing-slash rule covers)
with io.open(os.path.join(THEME, 'redirects.php'), 'w', encoding='utf-8', newline='\n') as f:
    f.write('<?php\n// Generated by _scripts/wp/build_static_theme.py - old urban.org.in path => page slug.\n'
            "defined( 'ABSPATH' ) || exit;\nreturn array(\n")
    for k in sorted(OLD_TO_SLUG):
        if k and k != OLD_TO_SLUG[k]:
            f.write("\t'%s' => '%s',\n" % (k.replace('\\', '\\\\').replace("'", "\\'"), OLD_TO_SLUG[k]))
    f.write(');\n')

print('pages: %d  newsletter forms wired: %d  old URLs mapped: %d'
      % (len(page_slugs), transform.forms, len(OLD_TO_SLUG)))
if dead_old_links:
    print('links to old-site pages that have NO page here (dead once the old site is off):')
    for u, n in dead_old_links.most_common():
        print('   %3d  %s' % (n, u))
print('leftover relative asset refs: %d' % leftover)
if unresolved:
    print('page-like hrefs with no matching page (left as-is):', sorted(unresolved))
if leftover:
    raise SystemExit(1)
