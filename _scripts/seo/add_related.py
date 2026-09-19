# -*- coding: utf-8 -*-
"""Adds a crawlable "Go deeper: Urban Perspectives" block to the pages that
should pass authority into the cluster: Urban Reforms Collective, RFC, Learning
Network for Urban Managers, and the 58 fellow blog posts (not tag pages).

Inserted immediately before </main>. Idempotent (marker data-ucan="upl").
Also adds the Organization enrichment (logo, contactPoint, knowsAbout) to every
page's JSON-LD Organization node - see enrich_org() below.

  python _scripts/seo/add_related.py
"""
import glob
import io
import json
import os
import re
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
import add_menu  # noqa: E402
from articles_config import ARTICLES, RELATED_BLURB, SITE  # noqa: E402

ROOT = os.path.normpath(os.path.join(HERE, '..', '..'))
STANDALONE = os.path.join(ROOT, 'standalone')
TARGETS = ['urban-reforms-collective', 'rfc', 'learning-network']

CSS = ('<style data-ucan="upl">.upl{background:var(--paper-alt,#E9F5F2);border-top:1px solid var(--line,#DCEAE6)}'
       '.upl .wrap{padding-top:clamp(36px,5vw,64px);padding-bottom:clamp(36px,5vw,64px)}'
       '.upl h2{margin:8px 0 14px;font:800 clamp(22px,2.6vw,30px)/1.2 var(--display,Archivo,sans-serif);color:var(--ink,#222120)}'
       '.upl ul{list-style:none;margin:0;padding:0;display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px}'
       '.upl li{border:1px solid var(--line,#DCEAE6);background:var(--paper,#FBFAF6);padding:16px 18px}'
       '.upl li a{font-weight:700;color:var(--teal-text,#177A69);text-decoration:none}.upl li a:hover{text-decoration:underline}'
       '.upl li span{display:block;margin-top:6px;font-size:14.5px;line-height:1.5;color:var(--ink-soft,#57564F)}'
       '.upl p.more{margin:16px 0 0}.upl p.more a{font-weight:700;color:var(--teal-text,#177A69)}</style>')


def block():
    lis = ''.join('<li><a href="%s">%s</a><span>%s</span></li>' % (a['slug'], a['keyword'], RELATED_BLURB[a['n']]) for a in ARTICLES)
    return (CSS + '<section class="upl" data-ucan="upl" aria-labelledby="upl-h"><div class="wrap">'
            '<p class="kicker" data-num="—">Urban Perspectives</p><h2 id="upl-h">Go deeper on urban governance in India</h2>'
            '<ul>%s</ul><p class="more"><a href="urban-perspectives">See all Urban Perspectives →</a></p></div></section>' % lis)


def targets():
    out = []
    for f in sorted(glob.glob(os.path.join(STANDALONE, '*.html'))):
        n = os.path.basename(f)[:-5]
        if n in TARGETS or (n.startswith('blog-') and not n.startswith('blog-tag-')):
            out.append(f)
    return out


ORG_EXTRA = {
    'logo': {'@type': 'ImageObject', 'url': SITE + '/assets/img/u-can-urban-collective-action-network-08e2484273.svg'},
    'contactPoint': {'@type': 'ContactPoint', 'contactType': 'general enquiries', 'email': 'connect@urban.org.in',
                     'areaServed': 'IN', 'availableLanguage': ['English']},
    'knowsAbout': ['Urban governance in India', 'Sustainable urban development', 'Municipal government reform',
                   'Citizen-centric urban solutions', 'Inclusive urban governance', 'Urban collaboration', 'Tier II and Tier III cities'],
}


def enrich_org(s):
    """Add logo/contactPoint/knowsAbout to the Organization node, in place."""
    m = re.search(r'(<script type="application/ld\+json"[^>]*>)(.*?)(</script>)', s, re.S)
    if not m:
        return s
    try:
        d = json.loads(m.group(2))
    except ValueError:
        return s
    nodes = d.get('@graph', [d]) if isinstance(d, dict) else []
    changed = False
    for n in nodes:
        if isinstance(n, dict) and str(n.get('@id', '')).endswith('#org') and 'knowsAbout' not in n:
            n.update(ORG_EXTRA)
            changed = True
    if not changed:
        return s
    body = json.dumps(d, ensure_ascii=False, separators=(',', ':')).replace('</', '<\\/')
    return s[:m.start(2)] + body + s[m.end(2):]


def main():
    added = org = 0
    tset = set(targets())
    for f in sorted(glob.glob(os.path.join(STANDALONE, '*.html'))):
        s = t = io.open(f, encoding='utf-8', newline='').read()
        if f in tset and 'data-ucan="upl"' not in t:
            i = t.rfind('</main>')
            if i < 0:
                raise SystemExit('no </main> in ' + f)
            t = t[:i] + block() + t[i:]
            added += 1
        t2 = enrich_org(t)
        if t2 != t:
            org += 1
        t = t2
        if t != s:
            add_menu.write(f, t)
    print('related block added to %d pages; Organization record enriched on %d pages' % (added, org))


if __name__ == '__main__':
    main()
