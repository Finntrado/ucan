# -*- coding: utf-8 -*-
"""One size for every logo tile on the site.

Three grids had grown their own specs: the homepage (.logo-grid, 116px tall,
Friends squeezed into a 3-column 640px-wide strip), Our Members (.mgrid, 150px)
and the Annual Forum partners (.ptrow, its own padding and a narrower anchor).
This injects the Our Members "Member Organisations" tile as the single spec.
Re-runnable: it replaces its own previous block.
"""
import io, os, re

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')
PAGES = ['index.html', 'our-members.html', 'annual-forum-2025.html']

CSS = '''<style data-ucan="logos">
/* One tile size for Member Organisations, Friends of U-CAN and Forum partners */
.logo-grid,.logo-grid.friends,.mgrid,.ptrow{display:grid!important;
  grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:14px!important;max-width:none!important}
.logo-grid>li,.mgrid>li,.ptrow>li{min-width:0}
.chip,.ptrow .pt{padding:0!important;min-height:150px!important}
.chip a,.mgrid a,.ptrow .pt a{display:flex!important;align-items:center;justify-content:center;
  width:100%;height:100%;min-height:150px!important;padding:22px!important;box-sizing:border-box}
.chip img,.mgrid img,.ptrow .pt img{max-height:88px!important;max-width:82%!important;
  width:auto!important;height:auto!important;object-fit:contain}
@media(max-width:980px){.logo-grid,.logo-grid.friends,.mgrid,.ptrow{
  grid-template-columns:repeat(3,minmax(0,1fr))!important}}
@media(max-width:680px){.logo-grid,.logo-grid.friends,.mgrid,.ptrow{
  grid-template-columns:repeat(2,minmax(0,1fr))!important;gap:10px!important}
  .chip,.ptrow .pt,.chip a,.mgrid a,.ptrow .pt a{min-height:120px!important}
  .chip img,.mgrid img,.ptrow .pt img{max-height:68px!important}}
</style>
'''

for name in PAGES:
    f = os.path.join(ROOT, name)
    s = io.open(f, encoding='utf-8', newline='').read()
    eol = '\r\r\n' if '\r\r\n' in s[:4000] else ('\r\n' if '\r\n' in s[:4000] else '\n')
    s = re.sub(r'<style data-ucan="logos">.*?</style>\s*', '', s, flags=re.S)
    s = s.replace('</head>', CSS.replace('\n', eol) + '</head>', 1)
    io.open(f, 'w', encoding='utf-8', newline='').write(s)
    print('logo tiles unified on', name)
