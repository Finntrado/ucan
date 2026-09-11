# -*- coding: utf-8 -*-
"""Re-tune the metric-matched fallback fonts so text occupies the same width
before and after the webfonts load (the source of font-swap layout shift).

Measured in Chromium with _scripts/qa/fontmetrics.js against local Arial:
  Archivo 700      vs Arch Fallback (Arial Bold x1.04)  -> fallback 5.0% too wide
  Public Sans 400  vs PS Fallback   (Arial x0.99)       -> fallback 6.3% too narrow
  Public Sans 600  vs PS Fallback                       -> fallback 8.1% too narrow
Arial has no 600, so semibold Public Sans gets its own face on Arial Bold.

The originals live inside three different base64 stylesheets, so rather than
edit those, these declarations are injected later in the cascade: for an
identical family/weight match the last-declared face wins, and the explicit
weight ranges make these the closer match for every weight in use.
Re-runnable: it replaces its own previous block.
"""
import glob, io, os, re, time

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')

# one face per weight in use; measured with _scripts/qa/fm2.js (width of a
# sample line, real font vs Arial / Arial Bold)
FACES = ('@font-face{font-family:"Arch Fallback";src:local("Arial Bold"),local("Arial-BoldMT"),local("Helvetica Neue Bold");font-weight:500;size-adjust:93.4%;ascent-override:90%;descent-override:22%;line-gap-override:0%}'
         '@font-face{font-family:"Arch Fallback";src:local("Arial Bold"),local("Arial-BoldMT"),local("Helvetica Neue Bold");font-weight:600;size-adjust:95.5%;ascent-override:90%;descent-override:22%;line-gap-override:0%}'
         '@font-face{font-family:"Arch Fallback";src:local("Arial Bold"),local("Arial-BoldMT"),local("Helvetica Neue Bold");font-weight:700;size-adjust:99%;ascent-override:90%;descent-override:22%;line-gap-override:0%}'
         '@font-face{font-family:"Arch Fallback";src:local("Arial Bold"),local("Arial-BoldMT"),local("Helvetica Neue Bold");font-weight:800;size-adjust:104.3%;ascent-override:90%;descent-override:22%;line-gap-override:0%}'
         '@font-face{font-family:"PS Fallback";src:local("Arial"),local("ArialMT"),local("Helvetica Neue");font-weight:400;size-adjust:105.3%;ascent-override:92%;descent-override:24%;line-gap-override:0%}'
         '@font-face{font-family:"PS Fallback";src:local("Arial"),local("ArialMT"),local("Helvetica Neue");font-weight:500;size-adjust:106.1%;ascent-override:92%;descent-override:24%;line-gap-override:0%}'
         '@font-face{font-family:"PS Fallback";src:local("Arial Bold"),local("Arial-BoldMT"),local("Helvetica Neue Bold");font-weight:600;size-adjust:98.9%;ascent-override:92%;descent-override:24%;line-gap-override:0%}'
         '@font-face{font-family:"PS Fallback";src:local("Arial Bold"),local("Arial-BoldMT"),local("Helvetica Neue Bold");font-weight:700;size-adjust:100.3%;ascent-override:92%;descent-override:24%;line-gap-override:0%}')
# `ch` is the width of "0", which differs between each webfont and its
# fallback even when whole lines match - so a ch-based max-width changes size
# on swap and re-wraps the text. The same limits in em are font-independent.
# (Public Sans "0" = 0.612em, Archivo 700 "0" = 0.595em.)
WIDTHS = ('.hero-lede{max-width:39.2em}'          # was 64ch
          '.sec-head h2{max-width:13.1em}'        # was 22ch
          '.section-head{max-width:45.3em}'       # was 74ch
          # blog hero images carried no dimensions, so the article jumped down
          # when each one arrived; reserve the box up front
          '.post-hero img{aspect-ratio:16/9;height:auto}')
BLOCK = '<style data-ucan="fallback">' + FACES + WIDTHS + '</style>'


def write(f, s):
    for _ in range(6):
        try:
            io.open(f, 'w', encoding='utf-8', newline='').write(s)
            return True
        except OSError:
            time.sleep(0.35)
    return False


n = fail = 0
for f in sorted(glob.glob(os.path.join(ROOT, '*.html'))):
    s = io.open(f, encoding='utf-8', newline='').read()
    s = re.sub(r'<style data-ucan="fallback">.*?</style>', '', s, flags=re.S)
    # directly before the type block, so it follows every page stylesheet
    i = s.find('<style data-ucan="type">')
    if i < 0:
        i = s.find('</head>')
    s = s[:i] + BLOCK + s[i:]
    if write(f, s):
        n += 1
    else:
        fail += 1
        print('  ! could not write', f)
print('fallback faces re-tuned on %d pages (%d failed)' % (n, fail))
