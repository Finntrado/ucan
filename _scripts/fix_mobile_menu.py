# -*- coding: utf-8 -*-
"""Fixes the mobile hamburger menu (user report: "stops scrolling ability
sometimes and other times just closes").

Root cause, confirmed live on urban.org.in at 375x812: <div class="msheet">
is a plain in-flow child of <header class="bar"> (position:sticky), not a
fixed overlay. .bar-in (the visible logo/nav row) is only 70-80px tall, but
the full nav list is ~1050px - so opening the menu balloons the STICKY
HEADER ITSELF to ~1050-1120px tall. The JS then locks scroll with
`document.documentElement.style.overflow = "hidden"`, so nothing past the
first screenful of that ballooned header (Events, Our Members, Media, the
bottom links) can ever be reached: no page scroll (locked) and no internal
scroll on the panel either (`.msheet{overflow-y:visible}`). That is the
"stops scrolling" symptom. `overflow:hidden` on <html> is also well known to
not reliably block scroll on iOS Safari, so the page behind can bleed
through and visually "close" the menu as it scrolls out from under a
sticky ancestor - the second symptom, same root cause.

Two independent, minimal changes - both are pure additions/replacements of
byte-identical blocks confirmed present on all 176 pages, nothing else is
touched:

1. CSS: '.msheet.open{display:block}' gets two rules appended right after
   it, taking the open panel out of the sticky header's flow entirely
   (`position:fixed`) and giving it its own scroll, bounded to the
   viewport below the header - `top` mirrors the site's own .bar-in
   height rules (80px <=1100px, 70px <=560px - burger is hidden above
   1100px so nothing else matters), z-index above the cookie banner (120).
2. JS: the `setOpen` function's scroll-lock swaps `html{overflow:hidden}`
   for the standard cross-browser-safe pattern (pin <body> at its current
   scroll offset with position:fixed, restore precisely on close) - guarded
   by a `locked` flag so the harmless `setOpen(false)` call made once on
   page load (to set initial ARIA state) never fires the unlock branch and
   force-scrolls a fresh page load to (0,0).

Idempotent (both anchors are unique per file, exit early if already patched).
Re-run _scripts/wp/build_static_theme.py afterwards.
"""
import glob
import io
import os
import time

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')

CSS_OLD = '.msheet.open{display:block}'
CSS_NEW = (
    '.msheet.open{display:block}'
    # `top` + `bottom` alone leaves height unresolved (tested live: renders
    # 1px tall) because .msheet's parent <header class="bar"> is
    # position:sticky - a fixed child's auto-height-from-insets breaks in
    # that combination. Give it an explicit height instead of relying on
    # bottom:0 to imply it.
    '.msheet.open{position:fixed;left:0;right:0;top:80px;height:calc(100vh - 80px);'
    'overflow-y:auto;-webkit-overflow-scrolling:touch;z-index:150}'
    '@media(max-width:560px){.msheet.open{top:70px;height:calc(100vh - 70px)}}'
)

JS_VARIANTS = [
    ('var setOpen = function (open) {\r\r\n'
     '        panel.classList.toggle("open", open);\r\r\n'
     '        btn.classList.toggle("open", open);\r\r\n'
     '        btn.setAttribute("aria-expanded", open ? "true" : "false");\r\r\n'
     '        panel.setAttribute("aria-hidden", open ? "false" : "true");\r\r\n'
     '        document.documentElement.style.overflow = open ? "hidden" : "";\r\r\n'
     '      };'),
    ('var setOpen = function (open) {\r\n'
     '        panel.classList.toggle("open", open);\r\n'
     '        btn.classList.toggle("open", open);\r\n'
     '        btn.setAttribute("aria-expanded", open ? "true" : "false");\r\n'
     '        panel.setAttribute("aria-hidden", open ? "false" : "true");\r\n'
     '        document.documentElement.style.overflow = open ? "hidden" : "";\r\n'
     '      };'),
    ('var setOpen = function (open) {\n'
     '        panel.classList.toggle("open", open);\n'
     '        btn.classList.toggle("open", open);\n'
     '        btn.setAttribute("aria-expanded", open ? "true" : "false");\n'
     '        panel.setAttribute("aria-hidden", open ? "false" : "true");\n'
     '        document.documentElement.style.overflow = open ? "hidden" : "";\n'
     '      };'),
    ('var setOpen = function (open) {\r\n\r\n'
     '        panel.classList.toggle("open", open);\r\n\r\n'
     '        btn.classList.toggle("open", open);\r\n\r\n'
     '        btn.setAttribute("aria-expanded", open ? "true" : "false");\r\n\r\n'
     '        panel.setAttribute("aria-hidden", open ? "false" : "true");\r\n\r\n'
     '        document.documentElement.style.overflow = open ? "hidden" : "";\r\n\r\n'
     '      };'),
]

JS_NEW = (
    'var scrollY = 0, locked = false;\n'
    '      var setOpen = function (open) {\n'
    '        if (open) {\n'
    '          panel.classList.add("open");\n'
    '          btn.classList.add("open");\n'
    '          btn.setAttribute("aria-expanded", "true");\n'
    '          panel.setAttribute("aria-hidden", "false");\n'
    '          if (!locked) {\n'
    '            scrollY = window.scrollY || document.documentElement.scrollTop || 0;\n'
    '            document.body.style.position = "fixed";\n'
    '            document.body.style.top = (-scrollY) + "px";\n'
    '            document.body.style.left = "0";\n'
    '            document.body.style.right = "0";\n'
    '            locked = true;\n'
    '          }\n'
    '        } else {\n'
    '          // Restore scroll BEFORE the panel collapses back into the\n'
    '          // sticky header\'s normal flow - collapsing it first (tested:\n'
    '          // reproducible) leaves a same-tick window.scrollTo() with no\n'
    '          // effect, snapping the page to 0 instead of where it was.\n'
    '          if (locked) {\n'
    '            document.body.style.position = "";\n'
    '            document.body.style.top = "";\n'
    '            document.body.style.left = "";\n'
    '            document.body.style.right = "";\n'
    '            // Also needed: without forcing a layout flush here, the\n'
    '            // scrollTo below reads a stale (still fixed-body-sized)\n'
    '            // scroll range and silently clamps short (tested,\n'
    '            // reproducible; landed anywhere from ~80px to ~450px off\n'
    '            // depending on timing).\n'
    '            void document.body.offsetHeight;\n'
    '            locked = false;\n'
    '            window.scrollTo(0, scrollY);\n'
    '          }\n'
    '          panel.classList.remove("open");\n'
    '          btn.classList.remove("open");\n'
    '          btn.setAttribute("aria-expanded", "false");\n'
    '          panel.setAttribute("aria-hidden", "true");\n'
    '        }\n'
    '      };'
)


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


# Earlier versions of this script, migrated forward rather than left stacked
# in the cascade (each superseded by testing - see the comments above
# CSS_NEW / JS_NEW for why):
#   v1 CSS - a bottom:0-based rule that rendered 1px tall in practice.
#   v1 JS  - collapsed the panel before restoring scroll, which silently
#            dropped the pending window.scrollTo (tested, reproducible).
CSS_V1 = (
    '.msheet.open{display:block}'
    '.msheet.open{position:fixed;left:0;right:0;bottom:0;top:80px;overflow-y:auto;'
    '-webkit-overflow-scrolling:touch;z-index:150}'
    '@media(max-width:560px){.msheet.open{top:70px}}'
)
JS_V1 = (
    'var scrollY = 0, locked = false;\n'
    '      var setOpen = function (open) {\n'
    '        panel.classList.toggle("open", open);\n'
    '        btn.classList.toggle("open", open);\n'
    '        btn.setAttribute("aria-expanded", open ? "true" : "false");\n'
    '        panel.setAttribute("aria-hidden", open ? "false" : "true");\n'
    '        if (open && !locked) {\n'
    '          scrollY = window.scrollY || document.documentElement.scrollTop || 0;\n'
    '          document.body.style.position = "fixed";\n'
    '          document.body.style.top = (-scrollY) + "px";\n'
    '          document.body.style.left = "0";\n'
    '          document.body.style.right = "0";\n'
    '          locked = true;\n'
    '        } else if (!open && locked) {\n'
    '          document.body.style.position = "";\n'
    '          document.body.style.top = "";\n'
    '          document.body.style.left = "";\n'
    '          document.body.style.right = "";\n'
    '          locked = false;\n'
    '          window.scrollTo(0, scrollY);\n'
    '        }\n'
    '      };'
)
JS_V2 = (
    'var scrollY = 0, locked = false;\n'
    '      var setOpen = function (open) {\n'
    '        if (open) {\n'
    '          panel.classList.add("open");\n'
    '          btn.classList.add("open");\n'
    '          btn.setAttribute("aria-expanded", "true");\n'
    '          panel.setAttribute("aria-hidden", "false");\n'
    '          if (!locked) {\n'
    '            scrollY = window.scrollY || document.documentElement.scrollTop || 0;\n'
    '            document.body.style.position = "fixed";\n'
    '            document.body.style.top = (-scrollY) + "px";\n'
    '            document.body.style.left = "0";\n'
    '            document.body.style.right = "0";\n'
    '            locked = true;\n'
    '          }\n'
    '        } else {\n'
    '          // Restore scroll BEFORE the panel collapses back into the\n'
    '          // sticky header\'s normal flow - collapsing it first (tested:\n'
    '          // reproducible) leaves a same-tick window.scrollTo() with no\n'
    '          // effect, snapping the page to 0 instead of where it was.\n'
    '          if (locked) {\n'
    '            document.body.style.position = "";\n'
    '            document.body.style.top = "";\n'
    '            document.body.style.left = "";\n'
    '            document.body.style.right = "";\n'
    '            locked = false;\n'
    '            window.scrollTo(0, scrollY);\n'
    '          }\n'
    '          panel.classList.remove("open");\n'
    '          btn.classList.remove("open");\n'
    '          btn.setAttribute("aria-expanded", "false");\n'
    '          panel.setAttribute("aria-hidden", "true");\n'
    '        }\n'
    '      };'
)

css_n = js_n = touched = 0
for f in sorted(glob.glob(os.path.join(ROOT, '*.html'))):
    s = io.open(f, encoding='utf-8', newline='').read()
    s0 = s

    if CSS_NEW not in s:
        if CSS_V1 in s:
            s = s.replace(CSS_V1, CSS_NEW, 1)
        elif CSS_OLD in s:
            if s.count(CSS_OLD) != 1:
                raise SystemExit('%s: CSS anchor not unique' % os.path.basename(f))
            s = s.replace(CSS_OLD, CSS_NEW, 1)
        else:
            raise SystemExit('%s: CSS anchor not found' % os.path.basename(f))
        css_n += 1

    if JS_NEW not in s:
        if JS_V2 in s:
            s = s.replace(JS_V2, JS_NEW, 1)
            js_n += 1
        elif JS_V1 in s:
            s = s.replace(JS_V1, JS_NEW, 1)
            js_n += 1
        else:
            for variant in JS_VARIANTS:
                if variant in s:
                    if s.count(variant) != 1:
                        raise SystemExit('%s: JS anchor not unique' % os.path.basename(f))
                    s = s.replace(variant, JS_NEW, 1)
                    js_n += 1
                    break
            else:
                raise SystemExit('%s: no known setOpen variant matched' % os.path.basename(f))

    if s != s0:
        write(f, s)
        touched += 1

print('touched: %d pages (css %d, js %d)  already up to date: %d'
      % (touched, css_n, js_n, len(glob.glob(os.path.join(ROOT, '*.html'))) - touched))
