# -*- coding: utf-8 -*-
"""Consistent spacing inside section heads.

.sec-head is a CSS grid whose 51px gap dates from when it had two columns.
Where the kicker, heading and lead are wrapped in an inner <div>, the gap has
nothing to separate and the three sit tight, as designed. Where they are direct
children (21 heads on 14 pages) the gap lands between each of them, doubling
the space above and below the heading. This zeroes the row gap for those heads
only and restates the spacing the wrapped heads already have. Re-runnable.
"""
import glob, io, os, re, time

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')

CSS = ('<style data-ucan="heads">'
       '.sec-head:has(>.kicker:first-child){row-gap:0!important}'
       '.sec-head>.kicker:first-child+:is(h2,h3){margin-top:2px!important}'
       '.sec-head>:is(h2,h3)+.lead{margin-top:16px!important}'
       # a sub-section heading that sits among h2s reads as undersized at h3 scale
       'main h3.fx-h3-lg{font-size:clamp(27px,3.6vw,40px)!important;line-height:1.18!important;'
       'letter-spacing:-.02em!important}'
       '</style>')


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
    s = re.sub(r'<style data-ucan="heads">.*?</style>', '', s, flags=re.S)
    s = s.replace('</head>', CSS + '</head>', 1)
    n += write(f, s)
print('section-head spacing applied on %d pages' % n)
