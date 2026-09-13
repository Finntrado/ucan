# -*- coding: utf-8 -*-
"""Static audit: every absolute URL in every page, classified by whether the
browser would actually FETCH it (a load) or it is only a reference (an <a>
link, canonical/hreflang/og meta, JSON-LD). Loads must be zero for the site
to be fully standalone.

  python externals.py [dir ...]     default: standalone/ and wp-theme/ucan/pages/
"""
import collections
import glob
import io
import os
import re
import sys

ROOT = os.path.normpath(os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', '..'))
DIRS = sys.argv[1:] or [os.path.join(ROOT, 'standalone'), os.path.join(ROOT, 'wp-theme', 'ucan', 'pages')]

URL = r'(?:https?:)?//[a-z0-9.-]+\.[a-z]{2,}[^\s"\'<>)]*'
REFERENCE_ONLY = {
    ('a', 'href'),
    ('link', 'href:canonical'), ('link', 'href:alternate'),
    ('meta', 'content'),
    ('button', 'data-url'),   # newsletter "copy link": written to the clipboard, never fetched
    ('svg', 'xmlns'), ('svg', 'xmlns:xlink'),  # XML namespace names, not URLs anything requests
}

loads = collections.defaultdict(set)      # (context, host) -> pages
refs = collections.Counter()

for d in DIRS:
    for f in sorted(glob.glob(os.path.join(d, '*.html'))):
        name = os.path.relpath(f, ROOT)
        s = io.open(f, encoding='utf-8').read()
        # JSON-LD is metadata for crawlers, never fetched by the page
        s = re.sub(r'<script[^>]*application/ld\+json[^>]*>.*?</script>', '', s, flags=re.S)

        for m in re.finditer(r'<(script|style)\b[^>]*>(.*?)</\1>', s, re.S):
            for u in re.findall(URL, m.group(2)):
                if m.group(1) == 'style' and not re.search(r'(url\(|@import)\s*[\'"]?' + re.escape(u), m.group(2)):
                    continue
                host = re.match(r'(?:https?:)?//([^/]+)', u).group(1)
                loads[('<%s> body' % m.group(1), host)].add(name)
        body = re.sub(r'<(script|style)\b[^>]*>.*?</\1>', '', s, flags=re.S)

        for t in re.finditer(r'<([a-z][a-z0-9]*)\b([^>]*)>', body, re.I):
            tag, attrs = t.group(1).lower(), t.group(2)
            rel = re.search(r'\brel="([^"]*)"', attrs)
            for a in re.finditer(r'([a-z][\w:-]*)\s*=\s*"([^"]*)"', attrs, re.I):
                attr, val = a.group(1).lower(), a.group(2)
                for u in re.findall(URL, val):
                    host = re.match(r'(?:https?:)?//([^/]+)', u).group(1)
                    key = attr + (':' + rel.group(1) if tag == 'link' and attr == 'href' and rel else '')
                    if (tag, key) in REFERENCE_ONLY or (tag == 'form' and attr == 'action'):
                        refs[(tag, key, host)] += 1
                        if tag == 'form':
                            loads[('<form action> (on submit)', host)].add(name)
                    elif attr == 'style' and 'url(' not in val:
                        continue
                    else:
                        loads[('<%s %s>' % (tag, key), host)].add(name)

print('== WOULD BE FETCHED (must be empty) ==')
for (ctx, host), pages in sorted(loads.items()):
    print('  %-40s %-32s %3d pages  e.g. %s' % (ctx, host, len(pages), sorted(pages)[0]))
print('\n== references only (links/meta, never fetched) ==')
for (tag, key, host), n in refs.most_common():
    print('  %-6s %-22s %-32s %d' % (tag, key, host, n))
