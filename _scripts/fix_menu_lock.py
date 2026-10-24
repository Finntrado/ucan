# -*- coding: utf-8 -*-
"""Mobile menu: lock the page without moving it.

Bug (Oct 2026, live on every page, worst on blog pages): opening the mobile menu
made the page "freeze", the menu vanish (drawn ~1,400px above the screen) or not
reach the bottom, and closing it jumped the page. Two causes:

  1. body{overflow-x:hidden} makes <body> its own scroll container once <html>
     gets overflow:hidden, so position:fixed children are placed against the
     wrong box and scrolling dies.
  2. Pinning the body (position:fixed; top:-scrollY) re-lays out the
     content-visibility:auto sections, so scrollTo() on close lands 80-450px off.

Fix: never move the page. Open = html.ucan-menu-open {overflow:hidden}; body uses
overflow-x:clip (clips like hidden but is NOT a scroll container); the sheet is
pinned top..bottom so it always ends at the visible bottom (toolbars included)
and scrolls by itself with overscroll contained.

Writes:
  - the new mobileMenu() into every standalone page's <script data-ucan="behaviour">
  - <style data-ucan="menulock"> before </head> on every page
  - wp-theme/ucan/inc/behaviour.js + inc/menulock.css (functions.php serves these
    over each live page's own copies, so the live site is fixed without
    re-uploading 176 page files)
Idempotent.
"""
import io
import os
import re
import time

ROOT = os.path.normpath(os.path.join(os.path.dirname(os.path.abspath(__file__)), '..'))
SA = os.path.join(ROOT, 'standalone')
INC = os.path.join(ROOT, 'wp-theme', 'ucan', 'inc')

MENU_JS = r'''(function mobileMenu() {
    var pairs = [
      { btn: "#burger", panel: "#msheet" },
      { btn: "#burger", panel: "#mobile-menu" },
      { btn: "#menuBtn", panel: "#mob-nav" }
    ];
    var root = document.documentElement;
    pairs.forEach(function (p) {
      var btn = $(p.btn), panel = $(p.panel);
      if (!btn || !panel || btn.dataset.ucanBound) return;
      btn.dataset.ucanBound = "1";

      // Lock = overflow:hidden on <html> (see fix_menu_lock.py). The page is
      // never moved, so there is no scroll position to restore on close.
      var setOpen = function (open) {
        panel.classList.toggle("open", open);
        btn.classList.toggle("open", open);
        btn.setAttribute("aria-expanded", open ? "true" : "false");
        panel.setAttribute("aria-hidden", open ? "false" : "true");
        root.classList.toggle("ucan-menu-open", open);
        if (open) panel.scrollTop = 0;
      };
      setOpen(false);

      btn.addEventListener("click", function (e) {
        e.preventDefault();
        setOpen(!panel.classList.contains("open"));
      });

      var close = $("#closeBtn");
      if (close) close.addEventListener("click", function () { setOpen(false); });

      // close on link click, on Escape, and if the screen grows to desktop width
      $$("a", panel).forEach(function (a) {
        a.addEventListener("click", function () { setOpen(false); });
      });
      document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && panel.classList.contains("open")) {
          setOpen(false);
          btn.focus();
        }
      });
      window.addEventListener("resize", function () {
        if (panel.classList.contains("open") && getComputedStyle(btn).display === "none") setOpen(false);
      });
    });
  })();

  '''

# Self-contained on purpose: some live page copies (blog posts, newsletter issues)
# never got the "menu is a fixed overlay" rule, so the open menu sat in the sticky
# header's flow and stretched it ~1,100px tall. Header is 80px, 70px <=560px.
MENU_CSS = ('body{overflow-x:hidden}@supports (overflow:clip){body{overflow-x:clip}}'
            'html.ucan-menu-open{overflow:hidden}'
            '.msheet.open{position:fixed;left:0;right:0;top:80px;bottom:auto;'
            'height:calc(100vh - 80px);height:calc(100dvh - 80px);max-height:none;z-index:150;'
            'overflow-y:auto;overscroll-behavior:contain;-webkit-overflow-scrolling:touch;'
            'padding-bottom:env(safe-area-inset-bottom)}'
            '@media(max-width:560px){.msheet.open{top:70px;height:calc(100vh - 70px);height:calc(100dvh - 70px)}}')
STYLE = '<style data-ucan="menulock">%s</style>' % MENU_CSS

MENU_RE = re.compile(r'\(function mobileMenu\(\) \{.*?\}\)\(\);\s*(?=/\* -+ 2b\.)', re.S)
BEH_RE = re.compile(r'(<script data-ucan="behaviour">)(.*?)(</script>)', re.S)
STYLE_RE = re.compile(r'<style data-ucan="menulock">.*?</style>', re.S)


def patch(s):
    m = BEH_RE.search(s)
    if not m:
        return s, False
    body, n = MENU_RE.subn(lambda _: MENU_JS, m.group(2), count=1)
    if n != 1:
        raise SystemExit('mobileMenu() block not found')
    s = s[:m.start(2)] + body + s[m.end(2):]
    s = STYLE_RE.sub('', s)
    i = s.find('</head>')
    return s[:i] + STYLE + s[i:], True


def write(path, text):
    for attempt in range(20):  # Windows OSError 22 retry
        try:
            with io.open(path, 'w', encoding='utf-8', newline='') as fh:
                fh.write(text)
            return
        except OSError:
            if attempt == 19:
                raise
            time.sleep(0.5)


done = 0
for f in sorted(os.listdir(SA)):
    if not f.endswith('.html'):
        continue
    p = os.path.join(SA, f)
    s = io.open(p, encoding='utf-8', newline='').read()
    t, ok = patch(s)
    if ok and t != s:
        write(p, t)
        done += 1

# canonical copies for the live theme (superset script: has every optional block)
ref = io.open(os.path.join(SA, 'blog-tag-air-quality.html'), encoding='utf-8', newline='').read()
js = BEH_RE.search(ref).group(2)
assert 'ucan-menu-open' in js and 'tagLinks' in js
write(os.path.join(INC, 'behaviour.js'), js.replace('\r\n', '\n').replace('\r', '\n'))
write(os.path.join(INC, 'menulock.css'), MENU_CSS + '\n')
print('pages patched: %d' % done)
