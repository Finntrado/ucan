# -*- coding: utf-8 -*-
"""Builds the downloadable Urban Collaboration Checklist PDF (the link magnet)
from the same Google Doc the article is built from: the one-page checklist, the
10-minute test, the scorecard, and "when collaboration may not be the answer".

Content is the doc's own text. Layout is A4, U-CAN colours, checkbox lists.
Renders with the repo's Playwright (Chromium print-to-PDF), then writes a
content-hashed file into standalone/assets/docs/ (assets are cached immutable,
so a changed PDF must get a new name - CLAUDE.md §26) and prints the name.

  python _scripts/seo/build_checklist_pdf.py
"""
import glob
import hashlib
import html as H
import io
import os
import re
import subprocess
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
import build_articles as ba  # noqa: E402

ROOT = ba.ROOT
DOCS = os.path.join(ROOT, 'standalone', 'assets', 'docs')
FONTS = 'file:///' + os.path.join(ROOT, 'standalone', 'assets', 'fonts').replace('\\', '/')
LOGO = 'file:///' + os.path.join(ROOT, 'standalone', 'assets', 'img', 'u-can-urban-collective-action-network-08e2484273.svg').replace('\\', '/')
TMP = os.path.join(HERE, 'out')
NODE_PDF = os.path.join(HERE, 'html_to_pdf.js')
URL = 'urban.org.in/urban-collaboration-checklist'


def between(blocks, start, end):
    i = next(i for i, b in enumerate(blocks) if b['t'] == 'h' and b['text'].startswith(start))
    j = next(j for j, b in enumerate(blocks) if j > i and b['t'] == 'h' and b['text'].startswith(end))
    return blocks[i + 1:j]


def render(blocks):
    ids = set()
    return ''.join(ba.render_block(dict(b), ids) for b in blocks)


CSS = """
@font-face{font-family:Archivo;src:url(%(f)s/archivo-latin.woff2) format('woff2');font-weight:100 900}
@font-face{font-family:'Public Sans';src:url(%(f)s/public-sans-latin.woff2) format('woff2');font-weight:100 900}
:root{--ink:#222120;--soft:#57564F;--teal:#1F8F7B;--deep:#0E5348;--mint:#E9F5F2;--lime:#CDDE71;--line:#DCEAE6}
*{box-sizing:border-box}body{margin:0;font:10.5pt/1.5 'Public Sans',Arial,sans-serif;color:var(--ink)}
h1,h2,h3{font-family:Archivo,Arial,sans-serif;margin:0;color:var(--ink)}
.cover{background:var(--deep);color:#fff;padding:14mm 12mm 12mm;margin:0 0 7mm}
.cover img{height:11mm;display:block;margin-bottom:8mm;filter:brightness(0) invert(1)}
.cover .k{font:700 8.5pt/1 'Public Sans';letter-spacing:.16em;text-transform:uppercase;color:var(--lime);margin:0 0 3mm}
.cover h1{color:#fff;font-size:25pt;line-height:1.1;font-weight:800}.cover p{margin:4mm 0 0;color:#DCEAE6;font-size:11pt}
h2{font-size:14pt;font-weight:800;margin:6mm 0 3mm;padding-bottom:2mm;border-bottom:2px solid var(--teal)}
h3{font-size:10.5pt;font-weight:800;margin:4.5mm 0 1.5mm;color:var(--deep);text-transform:none}
p{margin:0 0 2.2mm}ul,ol{margin:0 0 3mm;padding-left:5mm}li{margin:0 0 1.4mm}
.ck{list-style:none;padding-left:0}.ck li{position:relative;padding-left:7mm;margin-bottom:1.6mm}
.ck li::before{content:"";position:absolute;left:0;top:.2em;width:3.6mm;height:3.6mm;border:.4mm solid var(--teal);border-radius:.6mm}
.groups{columns:2;column-gap:9mm}.groups h3{break-after:avoid;margin-top:0}.groups .g{break-inside:avoid;margin:0 0 4mm;padding:3mm 4mm;background:var(--mint);border-left:1.2mm solid var(--teal)}
table{border-collapse:collapse;width:100%%;font-size:9pt}th,td{border:.3mm solid var(--line);padding:1.6mm 2.2mm;text-align:left;vertical-align:top}th{background:var(--mint)}
.pb{break-before:page}.note{font-size:9pt;color:var(--soft)}a{color:var(--deep)}
.foot{margin-top:8mm;padding-top:3mm;border-top:.3mm solid var(--line);font-size:8.5pt;color:var(--soft)}
""" % dict(f=FONTS)


def build_html():
    title, blocks = ba.normalise(ba.Doc(os.path.join(ba.SRC, 'a2.html')).blocks())
    one = between(blocks, 'The Urban Collaboration Checklist: One-page', 'From partnership')
    # group the one-page version by its five phase headings so each becomes a shaded card
    groups, cur = [], None
    for b in one:
        if b['t'] == 'h':
            cur = [b['text'].capitalize(), []]
            groups.append(cur)
        elif b['t'] == 'ck' and cur is not None:
            cur[1] += b['items']
    intro = next((b['text'] for b in one if b['t'] == 'p'), '')
    cards = ''.join('<div class="g"><h3>%s</h3><ul class="ck">%s</ul></div>' % (
        H.escape(t), ''.join('<li>%s</li>' % i for i in items)) for t, items in groups)
    test = render(between(blocks, 'The 10-minute Urban Collaboration Test', 'An Urban Collaboration Scorecard'))
    score = render(between(blocks, 'An Urban Collaboration Scorecard', 'The government question'))
    notans = render(between(blocks, 'When collaboration may not be the answer', 'What cities can learn'))
    return ('<!doctype html><html lang="en-IN"><head><meta charset="utf-8"><title>Urban Collaboration Checklist | U-CAN</title>'
            '<style>%s</style></head><body>'
            '<div class="cover"><img src="%s" alt="U-CAN"><p class="k">Urban Collective Action Network</p>'
            '<h1>Urban Collaboration Checklist</h1><p>A practical framework for cities: should we collaborate, and how?</p></div>'
            '<h2>The one-page checklist</h2><p>%s</p><div class="groups">%s</div>'
            '<div class="pb"></div><h2>The 10-minute Urban Collaboration Test</h2>%s'
            '<div class="pb"></div><h2>An Urban Collaboration Scorecard</h2>%s'
            '<h2>When collaboration may not be the answer</h2>%s'
            '<p class="foot">Read the full framework: <a href="https://%s">%s</a> &middot; U-CAN, Urban Collective Action Network &middot; connect@urban.org.in</p>'
            '</body></html>') % (CSS, LOGO, H.escape(intro), cards, test, score, notans, URL, URL)


def main():
    os.makedirs(TMP, exist_ok=True)
    src = os.path.join(TMP, 'checklist.html')
    io.open(src, 'w', encoding='utf-8', newline='\n').write(build_html())
    tmp_pdf = os.path.join(TMP, 'checklist.pdf')
    subprocess.run(['node', NODE_PDF, src, tmp_pdf], check=True, cwd=os.path.join(ROOT, '_scripts', 'qa'))
    data = open(tmp_pdf, 'rb').read()
    name = 'urban-collaboration-checklist-%s.pdf' % hashlib.sha1(data).hexdigest()[:8]
    for old in glob.glob(os.path.join(DOCS, 'urban-collaboration-checklist-*.pdf')):
        os.remove(old)
    open(os.path.join(DOCS, name), 'wb').write(data)
    print(name, len(data) // 1024, 'KB')


if __name__ == '__main__':
    main()
