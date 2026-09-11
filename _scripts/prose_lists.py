# -*- coding: utf-8 -*-
"""Prose list items as blocks with a positioned bullet, not two-column grids.

The site-wide rule made every unclassed <li> a grid (bullet | content). Any
item mixing an element with loose text - <li><strong>Label:</strong> text</li>,
used throughout the Privacy Policy - then became three grid items, so the
label sat in the content column and the text wrapped to a new row under the
bullet. A block with an absolutely positioned bullet renders any content in
normal flow and looks the same: 7px teal dot on the first line, dashed rules
between items. Re-runnable.
"""
import glob, io, os, time

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')

OLD_LI = ('main ul:not([class])>li{list-style:none!important;display:grid!important;'
          'grid-template-columns:auto 1fr;gap:13px;padding:11px 0;')
NEW_LI = ('main ul:not([class])>li{list-style:none!important;display:block!important;'
          'position:relative;padding:11px 0 11px 20px;')
OLD_DOT = ('main ul:not([class])>li::before{content:"";width:7px;height:7px;'
           'background:var(--teal,#1F8F7B);border-radius:50%;margin-top:9px}')
# centred on the first line: top padding + half a line (1.62em) - half the dot
NEW_DOT = ('main ul:not([class])>li::before{content:"";position:absolute;left:0;'
           'top:calc(11px + .81em - 3.5px);width:7px;height:7px;'
           'background:var(--teal,#1F8F7B);border-radius:50%}')


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
    if OLD_LI not in s and OLD_DOT not in s:
        continue
    s = s.replace(OLD_LI, NEW_LI).replace(OLD_DOT, NEW_DOT)
    n += write(f, s)
print('prose list rule updated on %d pages' % n)
