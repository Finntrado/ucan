# -*- coding: utf-8 -*-
"""Homepage "Why U-CAN?" block: switch between the new design and the original.

    python why_ucan.py new        # the redesign (default)
    python why_ucan.py classic    # put the original back exactly as it was

The first run saves the original block, byte for byte, to
_scripts/snippets/why-ucan-classic.html; 'classic' restores from that file.
Only the Why U-CAN block is touched - the "What we do" content that shares
its <section> is left alone. All copy is the page's existing text.
"""
import io, os, re, sys

HERE = os.path.dirname(os.path.abspath(__file__))
PAGE = os.path.join(HERE, '..', 'standalone', 'index.html')
SNIP = os.path.join(HERE, 'snippets', 'why-ucan-classic.html')
START, END = '<!-- WHY:start -->', '<!-- WHY:end -->'

s = io.open(PAGE, encoding='utf-8', newline='').read()

# ---- locate the block (marked, or the original unmarked form) ---------------
if START in s:
    a = s.index(START); b = s.index(END) + len(END)
else:
    a = s.index('<div class="wrap two-col">', s.index('id="about"'))
    b = s.index('<!-- ===================== WHAT WE DO', a)
    b = s.rfind('</div>', a, b) + len('</div>')          # close of .wrap.two-col
    os.makedirs(os.path.dirname(SNIP), exist_ok=True)
    if not os.path.exists(SNIP):
        io.open(SNIP, 'w', encoding='utf-8', newline='').write(s[a:b])
        print('original saved to', os.path.relpath(SNIP, HERE))

classic = io.open(SNIP, encoding='utf-8', newline='').read()


def dots():
    """A handful of large dots (metros) beside a dense field of small ones."""
    big = [(46, 62, 17), (98, 38, 13), (92, 100, 15), (148, 72, 12), (40, 128, 11)]
    out = ['<g class="w2-metro">']
    for i, (x, y, r) in enumerate(big):
        out.append('<circle cx="%d" cy="%d" r="%d" style="--d:%dms"/>' % (x, y, r, i * 90))
    out.append('</g><g class="w2-towns">')
    k = 0
    for row in range(9):
        for col in range(22):
            x = 236 + col * 14.6 + (7 if row % 2 else 0)
            y = 22 + row * 14.4
            out.append('<circle cx="%.1f" cy="%.1f" r="2.6" style="--d:%dms"/>' % (x, y, 300 + (k * 7) % 900))
            k += 1
    out.append('</g>')
    return ''.join(out)


NEW = ('<div class="wrap why2">'
       '<div class="why2-head rv">'
       '<p class="eyebrow">Why U-CAN?</p>'
       '<h2 id="why-h">India\'s urban challenges are too complex for any one organisation to solve alone, '
       'so we create the conditions to enable collaboration between organisations.</h2>'
       '</div>'
       '<div class="why2-grid">'
       '<div class="why2-story">'
       '<figure class="why2-scale rv">'
       '<svg viewBox="0 0 560 160" aria-hidden="true" focusable="false">' + dots() + '</svg>'
       '<figcaption><span><b>A handful</b> of metropolitan cities</span>'
       '<span><b>Nearly 10,000</b> smaller towns and cities</span></figcaption>'
       '</figure>'
       '<div class="body-text">'
       '<p class="rv d1">Most of what shapes urban policy in India comes from a handful of metropolitan cities. '
       'But the real story, and much of the country\'s urban growth, is playing out in nearly 10,000 smaller '
       'towns and cities, often with little data and even less coordination between the people working to '
       'improve them.</p>'
       '<p class="rv d2">U-CAN exists to be the connective tissue that\'s missing: a trusted space for '
       'practitioners, government, researchers and philanthropies to connect, learn from each other, and act '
       'together.</p>'
       '</div></div>'
       '<figure class="why2-quote rv d1">'
       '<span class="why2-mark" aria-hidden="true">&ldquo;</span>'
       '<blockquote><p>The spark for U-CAN emerged from two intertwined realisations. First, we may be running '
       'out of time for slow, linear change; our cities need bold, accelerated action built on collective '
       'momentum. Second, when the right people and ideas meet in a shared conversation, they can catalyse '
       'transformative change at scale. U-CAN was born from that belief: a space for collaboration beyond '
       'boundaries.</p></blockquote>'
       '<figcaption class="why2-by">'
       '<img src="assets/img/shilpa-kumar-founding-member-of-u-can-and-managi-c9c0ed061c.webp" width="132" '
       'height="132" loading="lazy" decoding="async" alt="Shilpa Kumar, Founding Member of U-CAN and Managing '
       'Director &amp; Head of India at British International Investment">'
       '<span><b>Shilpa Kumar</b><em>Founding Member, U-CAN and Managing Director &amp; Head of India, '
       'British International Investment</em></span>'
       '</figcaption>'
       '</figure>'
       '</div></div>')

CSS = ('<style data-ucan="why2">'
       '.why2-head{max-width:100%}'
       '.why2-head h2{max-width:24em;margin:18px 0 0}'
       '.why2-grid{display:grid;grid-template-columns:minmax(0,1.08fr) minmax(0,.92fr);'
       'gap:clamp(28px,4.4vw,64px);align-items:stretch;margin-top:clamp(34px,4vw,52px)}'
       '.why2-story{display:flex;flex-direction:column;gap:clamp(22px,2.6vw,30px);min-width:0}'
       '.why2-story .body-text p{margin:0 0 16px}'
       '.why2-story .body-text p:last-child{margin-bottom:0}'
       # the scale figure
       '.why2-scale{margin:0;padding:clamp(18px,2.2vw,26px);background:var(--paper-alt,#E9F5F2);'
       'border:1px solid var(--line,#DCEAE6)}'
       '.why2-scale svg{display:block;width:100%;height:auto}'
       '.w2-metro circle{fill:var(--teal-deep,#0E5348)}'
       '.w2-towns circle{fill:var(--teal,#1F8F7B);opacity:.55}'
       '.why2-scale circle{transform-box:fill-box;transform-origin:center;transform:scale(0);'
       'transition:transform .5s cubic-bezier(.3,1.4,.5,1) var(--d)}'
       '.why2-scale.in circle{transform:scale(1)}'
       '.why2-scale figcaption{display:grid;grid-template-columns:.36fr .64fr;gap:12px;margin-top:12px;'
       "font-family:var(--sans,'Public Sans',sans-serif);font-size:13px;line-height:1.4;color:var(--ink-soft,#57564F)}"
       ".why2-scale figcaption b{display:block;font-family:var(--display,Archivo,sans-serif);font-weight:800;"
       'font-size:clamp(17px,1.8vw,21px);color:var(--teal-deep,#0E5348);letter-spacing:-.01em}'
       # the quote card
       '.why2-quote{position:relative;margin:0;display:flex;flex-direction:column;justify-content:space-between;'
       'gap:26px;padding:clamp(28px,3.4vw,44px);background:var(--teal-deep,#0E5348);color:#FBFAF6;'
       'overflow:hidden}'
       '.why2-quote::after{content:"";position:absolute;right:-90px;top:-90px;width:260px;height:260px;'
       'border-radius:50%;background:radial-gradient(circle,rgba(78,198,178,.28),rgba(78,198,178,0) 70%);'
       'pointer-events:none}'
       '.why2-mark{position:absolute;left:clamp(22px,2.8vw,36px);top:clamp(4px,1vw,12px);'
       'font-family:var(--display,Archivo,serif);font-weight:800;font-size:96px;line-height:1;'
       'color:var(--lime,#CDDE71);opacity:.9}'
       '.why2-quote blockquote{margin:36px 0 0;position:relative}'
       '.why2-quote blockquote p{margin:0!important;font-family:var(--display,Archivo,sans-serif)!important;'
       'font-weight:500;font-size:clamp(17px,1.55vw,19px)!important;line-height:1.6!important;color:#FBFAF6}'
       '.why2-by{display:flex;align-items:center;gap:16px;padding-top:22px;'
       'border-top:1px solid rgba(251,250,246,.16)}'
       '.why2-by img{width:64px;height:64px;border-radius:50%;object-fit:cover;flex:0 0 64px;'
       'box-shadow:0 0 0 2px var(--teal-deep,#0E5348),0 0 0 4px var(--lime,#CDDE71)}'
       '.why2-by b{display:block;font-family:var(--display,Archivo,sans-serif);font-size:16px;color:#FBFAF6}'
       '.why2-by em{display:block;margin-top:3px;font-style:normal;font-size:13px;line-height:1.45;'
       'color:#B9C4BF}'
       '@media(max-width:900px){.why2-grid{grid-template-columns:1fr}}'
       '@media(max-width:520px){.why2-scale figcaption{grid-template-columns:1fr 1fr}}'
       '@media(prefers-reduced-motion:reduce){.why2-scale circle{transition:none;transform:none}}'
       '</style>')

variant = (sys.argv[1] if len(sys.argv) > 1 else 'new').lower()
block = START + (NEW if variant == 'new' else classic) + END
s = s[:a] + block + s[b:]
s = re.sub(r'<style data-ucan="why2">.*?</style>', '', s, flags=re.S)
if variant == 'new':
    s = s.replace('</head>', CSS + '</head>', 1)
io.open(PAGE, 'w', encoding='utf-8', newline='').write(s)
print('Why U-CAN block set to:', variant)
