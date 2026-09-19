# -*- coding: utf-8 -*-
"""Adds the "Urban Perspectives" menu (5 articles + a hub page) to the shared
header, mobile sheet and footer of every standalone/*.html page.

The nav is shared chrome (CLAUDE.md §3c), so this is a byte-exact insertion at
three anchors that exist once per page. Idempotent: pages that already carry
the marker are skipped. Import `patch()` to apply it to one page's text.

  python _scripts/seo/add_menu.py          # all pages
"""
import glob
import io
import os
import re
import time

ROOT = os.path.normpath(os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', '..'))
STANDALONE = os.path.join(ROOT, 'standalone')

HUB = 'urban-perspectives'
ITEMS = [
    ('sustainable-urban-development-india', 'Sustainable Urban Development'),
    ('urban-collaboration-checklist', 'Urban Collaboration Checklist'),
    ('citizen-centric-urban-solutions', 'Citizen-Centric Urban Solutions'),
    ('municipal-government-reform-india', 'Municipal Government Reform'),
    ('inclusive-urban-governance-india', 'Inclusive Urban Governance'),
]
MARK = 'data-menu="urban-perspectives"'

DESKTOP_ANCHOR = '<a href="policy-webinars">Policy Webinars</a></div></div></div></nav>'
MOBILE_ANCHOR = '<a class="ucmob-t ucmob-cta" href="newsletter#subscribe">Subscribe</a>'
FOOTER_ANCHOR = '<li><a href="impact">Impact</a></li>'


def _chevron(s):
    """Reuse the exact chevron svg the 'About Us' item already carries."""
    m = re.search(r'<a class="ucnav-t[^"]*" href="about"[^>]*>About Us(<svg.*?</svg>)</a>', s)
    return m.group(1) if m else ''


def patch(s, active=False):
    if MARK in s:
        return s
    for a in (DESKTOP_ANCHOR, MOBILE_ANCHOR, FOOTER_ANCHOR):
        if s.count(a) != 1:
            raise ValueError('anchor not unique/present: %s (x%d)' % (a[:50], s.count(a)))
    cur = ' aria-current="page"' if active else ''
    on = ' on' if active else ''
    links = ''.join('<a href="%s">%s</a>' % (slug, label) for slug, label in ITEMS)
    desktop = ('<div class="ucnav-i" %s><a class="ucnav-t%s" href="%s"%s>Urban Perspectives%s</a>'
               '<div class="ucnav-dd">%s</div></div>') % (MARK, on, HUB, cur, _chevron(s), links)
    mobile = ('<div class="ucmob-g"><p class="ucmob-h">Urban Perspectives</p><div class="ucmob-l">'
              '<a href="%s">All Urban Perspectives</a>%s</div></div>') % (HUB, links)
    # keep .ucnav open: ...</a></div></div> + new item + </div></nav>
    s = s.replace(DESKTOP_ANCHOR, DESKTOP_ANCHOR[:-len('</div></nav>')] + desktop + '</div></nav>', 1)
    s = s.replace(MOBILE_ANCHOR, mobile + MOBILE_ANCHOR, 1)
    s = s.replace(FOOTER_ANCHOR, FOOTER_ANCHOR + '<li><a href="%s">Urban Perspectives</a></li>' % HUB, 1)
    return s


BAD = ('Policy Webinars</a></div></div></div><div class="ucnav-i" ' + MARK + '>')
GOOD = ('Policy Webinars</a></div></div><div class="ucnav-i" ' + MARK + '>')


def repair(s):
    """v1 of patch() closed .ucnav one </div> early; undo that on pages it touched."""
    return s.replace(BAD, GOOD)


def unpatch(s):
    """Remove the Urban Perspectives menu again (for pages that must not link to unpublished pages)."""
    s = re.sub(r'<div class="ucnav-i" ' + re.escape(MARK) + r'>.*?</div></div>', '', s, flags=re.S)
    s = re.sub(r'<div class="ucmob-g"><p class="ucmob-h">Urban Perspectives</p>.*?</div></div>', '', s, flags=re.S)
    return s.replace('<li><a href="%s">Urban Perspectives</a></li>' % HUB, '')


def write(path, text):
    for attempt in range(20):  # Windows OSError 22 retry, see localonly.py
        try:
            with io.open(path, 'w', encoding='utf-8', newline='') as fh:
                fh.write(text)
            return
        except OSError:
            if attempt == 19:
                raise
            time.sleep(0.5)


if __name__ == '__main__':
    done = skipped = 0
    for f in sorted(glob.glob(os.path.join(STANDALONE, '*.html'))):
        s = io.open(f, encoding='utf-8', newline='').read()
        t = patch(repair(s))
        if t == s:
            skipped += 1
        else:
            write(f, t)
            done += 1
    print('menu added to %d pages (%d already had it)' % (done, skipped))
