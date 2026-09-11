# -*- coding: utf-8 -*-
"""Photo lightbox: a real <button> over each tile instead of role=button on
the tile itself. The tile holds a "photo loads on urban.org.in" placeholder
that is visible until its lazy image arrives, and a role=button element's
label has to include its visible text (WCAG 2.5.3) - so the label and the
placeholder clashed. A native button carries only its own label, and gets
keyboard behaviour for free. Also gives City Mixers' list of events a section
heading so the event titles (h3) no longer skip a level. Re-runnable."""
import io, os, re

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')

OLD_JS = """    t.setAttribute('role', 'button');
    t.setAttribute('tabindex', '0');
    t.setAttribute('aria-label', 'View photograph' + (im.alt ? ': ' + im.alt : ''));
    if (!t.querySelector('.zoomdot')) {
      var dot = document.createElement('span');
      dot.className = 'zoomdot';
      dot.setAttribute('aria-hidden', 'true');
      t.appendChild(dot);
    }
    t.addEventListener('click', function () { open(t); });
    t.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(t); }
    });"""

NEW_JS = """    if (!t.querySelector('.zoomhit')) {
      var hit = document.createElement('button');
      hit.type = 'button';
      hit.className = 'zoomhit';
      hit.setAttribute('aria-label', 'View photograph' + (im.alt ? ': ' + im.alt : ''));
      var dot = document.createElement('span');
      dot.className = 'zoomdot';
      dot.setAttribute('aria-hidden', 'true');
      t.appendChild(dot);
      t.appendChild(hit);
      hit.addEventListener('click', function () { open(t); });
    }"""

CSS = ('<style data-ucan="lbx2">'
       '.zoomhit{position:absolute;inset:0;z-index:4;margin:0;padding:0;border:0;background:none;'
       'cursor:zoom-in;-webkit-appearance:none;appearance:none}'
       '.zoomhit:focus-visible{outline:2px solid var(--teal,#1F8F7B);outline-offset:-3px}'
       '.galx figure:focus-within::after,.mx-img:focus-within::after{opacity:1}'
       '.galx figure:focus-within .zoomdot,.mx-img:focus-within .zoomdot{opacity:1;transform:scale(1) translateY(0)}'
       '</style>')

for name in ('city-mixers.html', 'annual-forum-2025.html'):
    f = os.path.join(ROOT, name)
    s = io.open(f, encoding='utf-8', newline='').read()
    # match regardless of line endings (pages mix \n, \r\n and \r\r\n), and
    # write the replacement with whatever ending the matched block used
    pat = r'\r*\n'.join(re.escape(line) for line in OLD_JS.split('\n'))
    m = re.search(pat, s)
    if m:
        eol = re.search(r'\r*\n', m.group(0)).group(0)
        s = s[:m.start()] + NEW_JS.replace('\n', eol) + s[m.end():]
    assert "t.setAttribute('role', 'button')" not in s, name
    s = re.sub(r'<style data-ucan="lbx2">.*?</style>', '', s, flags=re.S)
    s = s.replace('</head>', CSS + '</head>', 1)
    if name == 'city-mixers.html' and 'id="series-h"' not in s:
        s = s.replace('<section class="sec" id="series" aria-label="City Mixers">',
                      '<section class="sec" id="series" aria-labelledby="series-h">', 1)
        s = s.replace('<div class="mxlist">', '<h2 class="vh" id="series-h">City Mixers</h2><div class="mxlist">', 1)
        assert 'id="series-h"' in s
    io.open(f, 'w', encoding='utf-8', newline='').write(s)
    print('lightbox buttons on', name)
