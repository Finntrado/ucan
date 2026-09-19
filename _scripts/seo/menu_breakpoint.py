# -*- coding: utf-8 -*-
"""Moves the burger/desktop-nav breakpoint from 1100px to 1160px on every page.

With the sixth top-level item ("Urban Perspectives") the desktop nav needs
~1140px; at 1101-1139px it overflowed by up to 28px (CLAUDE.md §13: these
thresholds are tuned to the item count, so adding an item means re-tuning).
Only the three rules that define the burger switch are touched - base
(.bar .nav / .burger), chrome (.ucnav-bar / .burger) and rhythm (bar height,
brand size, scroll padding) - so header height and the mobile-menu offset stay
in step. Idempotent.
"""
import glob, io, os, re, sys
HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
import add_menu

NEW = '1160px'
PATTERNS = [
    r'(@media\s*\(\s*max-width:\s*)1100px(\s*\)\s*\{\s*\.bar \.nav,\.bar \.bar-cta\{display:none\})',
    r'(@media\s*\(\s*max-width:\s*)1100px(\s*\)\s*\{\s*\.bar \.ucnav-bar\{display:none\})',
    r'(@media\s*\(\s*max-width:\s*)1100px(\s*\)\s*\{\s*\.bar \.brand img\{width:148px)',
]
if __name__ == '__main__':
    changed, no_match = 0, []
    for f in sorted(glob.glob(os.path.join(add_menu.STANDALONE, '*.html'))):
        s = t = io.open(f, encoding='utf-8', newline='').read()
        hits = 0
        for p in PATTERNS:
            t, n = re.subn(p, r'\g<1>' + NEW + r'\g<2>', t)
            hits += n
        if not re.search(r'max-width:\s*%s\s*\)\s*\{\s*\.bar \.ucnav-bar' % NEW, t):
            no_match.append(os.path.basename(f))
        if t != s:
            add_menu.write(f, t)
            changed += 1
    print('breakpoint moved on %d pages; pages WITHOUT the chrome rule at %s: %s' % (changed, NEW, no_match[:8] or 'none'))
