# -*- coding: utf-8 -*-
"""The brochure, the RFC Phase I report and the Fellowship report were only
reachable through urban.org.in pages wrapping a flipbooklets.com viewer, so
those links die with the old site. The viewers expose the real PDFs; they are
downloaded into assets/docs/ (content-hashed names, like every other asset -
§26: never change a file's content without changing its name) and the links
repointed at them. Idempotent: skips downloads already present.
"""
import glob
import hashlib
import io
import os
import re
import time
import urllib.request

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')
DOCS = os.path.join(ROOT, 'assets', 'docs')
UA = {'User-Agent': 'Mozilla/5.0'}

BOOKS = [
    # (old urban.org.in link, flipbooklets slug, local name)
    ('https://urban.org.in/ucan-brochure-final/', 'ucan-brochure-final', 'ucan-brochure'),
    ('https://urban.org.in/from-parellel-to-together-building-a-collaboration-practice-for-indias-urban-ecosystem',
     'from-parellel-to-together-building-a-collaboration-practice-for-india-s-urban-ecosystem',
     'from-parallel-to-together-rfc-phase-1-report'),
    ('https://urban.org.in/u-can-fellowship-report-2025', 'u-can-fellowship-report', 'u-can-fellowship-report-2025'),
]


def fetch(url):
    return urllib.request.urlopen(urllib.request.Request(url, headers=UA), timeout=120).read()


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


os.makedirs(DOCS, exist_ok=True)
replace = {}
for old, slug, name in BOOKS:
    existing = glob.glob(os.path.join(DOCS, name + '-*.pdf'))
    if existing:
        rel = 'assets/docs/' + os.path.basename(existing[0])
    else:
        viewer = fetch('https://flipbooklets.com/pdfflipbooklets/' + slug).decode('utf-8', 'ignore')
        m = re.search(r'https://cdn\.flipbooklets\.com/pdfs/[\w-]+\.pdf', viewer)
        if not m:
            raise SystemExit('no PDF found behind flipbook ' + slug)
        pdf = fetch(m.group(0))
        if not pdf.startswith(b'%PDF'):
            raise SystemExit(slug + ': download is not a PDF')
        fname = '%s-%s.pdf' % (name, hashlib.sha1(pdf).hexdigest()[:8])
        with open(os.path.join(DOCS, fname), 'wb') as fh:
            fh.write(pdf)
        rel = 'assets/docs/' + fname
        print('downloaded %s (%d KB)' % (rel, len(pdf) // 1024))
    for variant in {old, old.rstrip('/'), old.rstrip('/') + '/'}:
        replace['href="%s"' % variant] = 'href="%s"' % rel

n = 0
for f in sorted(glob.glob(os.path.join(ROOT, '*.html'))):
    s0 = s = io.open(f, encoding='utf-8', newline='').read()
    for a, b in replace.items():
        n += s.count(a)
        s = s.replace(a, b)
    if s != s0:
        write(f, s)
        print('relinked', os.path.basename(f))
print('links repointed: %d' % n)
