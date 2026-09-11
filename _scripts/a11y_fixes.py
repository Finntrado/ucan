# -*- coding: utf-8 -*-
"""Accessibility fixes found by Lighthouse.

1. Footer column titles were <h4> directly after the page's <h2>s, skipping a
   level (Lighthouse 'heading-order'). They become <h2 class="f-h"> with the
   same small uppercase look.
2. Homepage only: footer had content-visibility:auto, so while off screen it
   was a 720px placeholder and audits measured the newsletter column as
   hanging below it on the page background. The footer is small; render it.
3. Homepage only: the 3Cs card accent word was --teal-light (#4EC6B2) on
   paper - 2:1 contrast. --teal keeps it in the family at 4:1, which passes
   for 24px bold text.
Re-runnable.
"""
import glob, io, os, re, time

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')
FOOT_CSS = ('<style data-ucan="a11y">'
            "footer .f-h{font-family:var(--sans,'Public Sans',sans-serif)!important;font-weight:700;"
            'font-size:12.5px!important;line-height:1.4;letter-spacing:.1em;text-transform:uppercase;'
            'color:var(--paper,#FBFAF6);margin:0 0 18px}'
            # footer legal line: #75857E on ink is 4.1:1; the consent label's colour is 5.8:1
            '.f-legal,.f-legal a{color:#8CA098!important}'
            # links inside running text need more than colour to stand out
            '#fnform a{text-decoration:underline!important;text-underline-offset:2px}'
            # About's principle numbers were --line on paper (1.2:1); brand teal is 3.8:1
            '.pr-num{color:var(--teal,#1F8F7B)!important}'
            '.pr:hover .pr-num,.pr:focus-within .pr-num{color:var(--teal-deep,#0E5348)!important}'
            '</style>')
HOME_CSS = ('<style data-ucan="a11y-home">'
            'footer{content-visibility:visible!important;contain-intrinsic-size:none!important}'
            '.c-card .c-word{color:var(--teal,#1F8F7B)!important}'
            '</style>')


def write(f, s):
    for _ in range(6):
        try:
            io.open(f, 'w', encoding='utf-8', newline='').write(s)
            return True
        except OSError:
            time.sleep(0.35)
    return False


n = heads = 0
for f in sorted(glob.glob(os.path.join(ROOT, '*.html'))):
    s = io.open(f, encoding='utf-8', newline='').read()
    a = s.find('<footer'); b = s.find('</footer>', a)
    if a >= 0 and b > a:
        foot = s[a:b]
        k = foot.count('<h4')
        foot = re.sub(r'<h4(\s[^>]*)?>', lambda m: '<h2 class="f-h"%s>' % (m.group(1) or ''), foot)
        foot = foot.replace('</h4>', '</h2>')
        s = s[:a] + foot + s[b:]
        heads += k
    s = re.sub(r'<style data-ucan="a11y(?:-home)?">.*?</style>', '', s, flags=re.S)
    extra = FOOT_CSS + (HOME_CSS if os.path.basename(f) == 'index.html' else '')
    s = s.replace('</head>', extra + '</head>', 1)
    n += write(f, s)
# newsletter.html: the featured card title came straight after the h1 as an h3
F2 = os.path.join(ROOT, 'newsletter.html')
t = io.open(F2, encoding='utf-8', newline='').read()
i = t.find('class="featured-body"', t.find('<main'))
j = t.find('<h3>', i)
if i >= 0 and j >= 0 and j - i < 400:
    k = t.find('</h3>', j)
    t = t[:j] + '<h2 class="featured-title">' + t[j + 4:k] + '</h2>' + t[k + 5:]
if 'data-ucan="nlhub-a11y"' not in t:
    t = t.replace('</head>', '<style data-ucan="nlhub-a11y">main .featured-body h2.featured-title{font-family:var(--display)!important;'
                  'font-size:clamp(1.5rem,2.5vw,2rem)!important;font-weight:700!important;line-height:1.15!important;'
                  'letter-spacing:-.015em;color:var(--ink);margin:0 0 1rem;max-width:24ch}</style></head>', 1)
write(F2, t)
print('a11y fixes on %d pages; footer headings retagged: %d' % (n, heads))
