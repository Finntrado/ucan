# -*- coding: utf-8 -*-
"""Make every standalone/ page fetch nothing from anywhere but its own origin.

Found by _scripts/qa/externals.py + offline.js; each fix is idempotent:

1. <link rel="preconnect" href="https://urban.org.in/"> - opened a connection
   to the old site on every page load, for nothing (nothing loads from it).
2. Click-to-play YouTube (<script data-ucan="ytf"> / "yt") - swapped the
   local thumbnail for a youtube-nocookie iframe on click. Removed: each
   facade is already an <a href="https://www.youtube.com/watch?v=..."
   target="_blank">, so a click now opens the video on YouTube in a new tab
   and the page itself never loads third-party code.
3. Newsletter forms' action="https://urban.org.in/newsletter/" - the JS gate
   always preventDefault()s, so it only mattered with JS off, when it posted
   the visitor's email to the old site. Dropped; the form posts to itself.
4. Byte-identical duplicate <script|style data-ucan> blocks - a re-run build
   step had injected some twice (e.g. the newsletter share script, which
   then bound its copy-link handler twice). Keeps the first copy.

Run after any build step, then _scripts/wp/build_static_theme.py.
"""
import glob
import io
import os
import re

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')

totals = dict(preconnect=0, yt_scripts=0, form_actions=0, duplicates=0)
changed = 0

for f in sorted(glob.glob(os.path.join(ROOT, '*.html'))):
    s0 = s = io.open(f, encoding='utf-8', newline='').read()

    s, n = re.subn(r'<link rel="preconnect" href="https?://(?:www\.)?urban\.org\.in/?"\s*/?>\r*\n?', '', s)
    totals['preconnect'] += n

    s, n = re.subn(r'<script data-ucan="(?:ytf|yt)">.*?</script>\r*\n?', '', s, flags=re.S)
    totals['yt_scripts'] += n

    s, n = re.subn(r'(<form\b[^>]*?)\s+action="https?://(?:www\.)?urban\.org\.in/[^"]*"', r'\1', s)
    totals['form_actions'] += n

    seen = set()

    def dedupe(m):
        if m.group(0) in seen:
            totals['duplicates'] += 1
            return ''
        seen.add(m.group(0))
        return m.group(0)
    s = re.sub(r'<(script|style) data-ucan="[^"]+"[^>]*>.*?</\1>', dedupe, s, flags=re.S)

    if s != s0:
        io.open(f, 'w', encoding='utf-8', newline='').write(s)
        changed += 1

print('pages changed: %d  %s' % (changed, totals))
