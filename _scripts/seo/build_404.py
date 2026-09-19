# -*- coding: utf-8 -*-
"""Builds the custom 404/410 page and its supporting data.

  standalone/404.html            Vercel serves this automatically (with a real 404
                                 status) for any unknown URL; the WordPress theme
                                 serves the same file through ucan_serve_error_page().
  wp-theme/ucan/inc/page-titles.php   slug => title, used for "Did you mean" suggestions.

The page is a real page in the site's design (header, menu, footer), tells the
visitor what happened, and links to the important pages. It is deliberately
NOT a redirect to the homepage: a redirected or 200-status "not found" page is a
soft 404, which search engines treat as a quality problem.

Design/SEO rules baked in: noindex + follow, no canonical, no structured data,
root-absolute links (the page can be served from any URL depth), and comment
markers (<!--ERR-TITLE-->…) so the server can swap the wording for a 410 "removed" page.

  python _scripts/seo/build_404.py [--with-perspectives]   (once the Urban Perspectives pages are live)
"""
import html as H
import io
import os
import re
import sys

from bs4 import BeautifulSoup

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
import add_menu  # noqa: E402
import build_articles as ba  # noqa: E402
from articles_config import ARTICLES  # noqa: E402

ROOT = ba.ROOT
SA = ba.OUT
TITLES_PHP = os.path.join(ROOT, 'wp-theme', 'ucan', 'inc', 'page-titles.php')
# built but not yet published on urban.org.in: neither linked in the menu nor suggested
PENDING = {a['slug'] for a in ARTICLES} | {'urban-perspectives'}
WITH_P = '--with-perspectives' in sys.argv

POPULAR = [
    ('about', 'About U-CAN', 'Who we are and what we do'),
    ('impact', 'Our impact', 'The network in numbers and stories'),
    ('urban-reforms-collective', 'Urban Reforms Collective', 'A pan-India platform for systemic urban reform'),
    ('rfc', 'Request for Collaboration', 'From intent to practice, together'),
    ('fellowship', 'The U-CAN Fellowship', 'Women leaders inside urban institutions'),
    ('learning-network', 'Learning Network', 'Peer learning for municipal officials'),
    ('newsletter', 'Newsletter', 'Monthly urban governance updates'),
    ('fellow-blogs', 'Blogs by our Fellows', 'Field notes from Indian cities'),
]

CSS = r"""
.e404-wrap h2{margin:0 0 14px;font:800 clamp(20px,2.2vw,26px)/1.25 var(--display,Archivo,sans-serif);color:var(--ink,#222120)}
.e404-grid{list-style:none;margin:0 0 30px;padding:0;display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px}
.e404-grid li{margin:0;border:1px solid var(--line,#DCEAE6);background:var(--paper,#FBFAF6)}
.e404-grid a{display:block;padding:16px 18px;text-decoration:none;color:var(--ink,#222120)}
.e404-grid a:hover,.e404-grid a:focus-visible{background:var(--paper-alt,#E9F5F2)}
.e404-grid b{display:block;font:800 17px/1.3 var(--display,Archivo,sans-serif);color:var(--teal-deep,#0E5348)}
.e404-grid span{display:block;margin-top:4px;font-size:14.5px;line-height:1.45;color:var(--ink-soft,#57564F)}
.e404-sugg{margin:0 0 30px;padding:18px 20px;border-left:4px solid var(--teal,#1F8F7B);background:var(--paper-alt,#E9F5F2)}
.e404-sugg p{margin:0 0 8px;font-weight:700;color:var(--ink,#222120)}.e404-sugg ul{margin:0;padding-left:20px}.e404-sugg a{color:var(--teal-text,#177A69);font-weight:600}
.e404-help{margin:0;font-size:16px;line-height:1.6;color:var(--ink-soft,#57564F);max-width:64ch}.e404-help a{color:var(--teal-text,#177A69);font-weight:600}
"""


def main_html():
    cards = ''.join('<li><a href="/%s"><b>%s</b><span>%s</span></a></li>' % (s, H.escape(t), H.escape(d)) for s, t, d in POPULAR)
    return ('<main id="main"><section class="hero" aria-labelledby="pt"><div class="hero-in">'
            '<p class="hero-tag"><!--ERR-CODE-->Error 404<!--/ERR-CODE--></p>'
            '<h1 id="pt"><!--ERR-TITLE-->This page isn\'t here<!--/ERR-TITLE--></h1>'
            '<p class="hero-lede"><!--ERR-MSG-->The page may have moved, been renamed or been removed. '
            'Try one of the pages below.<!--/ERR-MSG--></p></div></section>'
            '<section class="sec"><div class="wrap e404-wrap"><!--SUGGEST--><!--/SUGGEST-->'
            '<h2>Popular pages</h2><ul class="e404-grid">%s</ul>'
            '<p class="e404-help">Followed a link from another site and it brought you here? Tell us at '
            '<a href="mailto:connect@urban.org.in">connect@urban.org.in</a> and we will fix it.</p>'
            '</div></section></main>') % cards


def page_titles():
    out = {}
    for f in sorted(os.listdir(SA)):
        if not f.endswith('.html') or f == '404.html' or (f[:-5] in PENDING and not WITH_P):
            continue
        s = BeautifulSoup(io.open(os.path.join(SA, f), encoding='utf-8').read(), 'html.parser')
        if s.title:
            out[f[:-5]] = re.sub(r'\s+\|\s+.*$', '', s.title.get_text(strip=True)).strip()
    return out


def main():
    shell = io.open(ba.SHELL, encoding='utf-8', newline='').read()
    head = ('<base href="/">\n<title>Page not found | U-CAN</title>\n'
            '<meta name="description" content="The page you were looking for could not be found. Try the popular pages on U-CAN, the Urban Collective Action Network.">\n'
            '<style data-ucan="e404">%s</style>\n') % re.sub(r'\s*\n\s*', '', CSS.strip())
    page = ba.compose(shell, head, main_html(), active=False)
    # noindex: the status code already says "not found"; this keeps it out of any index even if served with a 200 by mistake
    page, n = re.subn(r'<meta\s+name="robots"[^>]*>', '<meta name="robots" content="noindex, follow">', page, count=1)
    assert n == 1, 'shell has no robots meta to replace'
    if not WITH_P:
        page = add_menu.unpatch(page)
    assert 'rel="canonical"' not in page and 'application/ld+json' not in page
    ba.write(os.path.join(SA, '404.html'), page)

    # the WordPress theme composes the error page at request time from an existing page's chrome + this
    # fragment (3 KB instead of another 100 KB page). Same head extras/main as the file above, minus <base>.
    frag = '<!--HEAD-->%s<!--/HEAD-->\n<!--MAIN-->%s<!--/MAIN-->\n' % (head.replace('<base href="/">\n', ''), main_html())
    io.open(os.path.join(ROOT, 'wp-theme', 'ucan', 'inc', '404-fragment.html'), 'w', encoding='utf-8', newline='\n').write(frag)

    t = page_titles()
    q = lambda s: "'" + s.replace('\\', '\\\\').replace("'", "\\'") + "'"
    php = ['<?php', '// Generated by _scripts/seo/build_404.py - slug => title, for the 404 page\'s "did you mean" suggestions.',
           "defined( 'ABSPATH' ) || exit;", 'return array(']
    php += ['\t%s => %s,' % (q(k), q(v)) for k, v in t.items()]
    php += [');', '']
    io.open(TITLES_PHP, 'w', encoding='utf-8', newline='\n').write('\n'.join(php))
    print('404.html %d KB | %d page titles' % (os.path.getsize(os.path.join(SA, '404.html')) // 1024, len(t)))


if __name__ == '__main__':
    main()
