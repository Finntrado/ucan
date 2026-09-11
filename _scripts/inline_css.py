# -*- coding: utf-8 -*-
"""Turn the base64 data: stylesheet link at the top of every page into a plain
<style> block in the same position. Same rules, same order - base64 only added
a third to its size and a decode step before first paint. Re-runnable."""
import base64, glob, io, os, re, time

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')
LINK = re.compile(r'<link rel="stylesheet" type="text/css" href="data:text/css;base64,([A-Za-z0-9+/=]+)"\s*/?>')


def write(f, s):
    for _ in range(6):
        try:
            io.open(f, 'w', encoding='utf-8', newline='').write(s)
            return True
        except OSError:
            time.sleep(0.35)
    return False


n = saved = 0
for f in sorted(glob.glob(os.path.join(ROOT, '*.html'))):
    s = io.open(f, encoding='utf-8', newline='').read()
    m = LINK.search(s)
    if not m:
        continue
    css = base64.b64decode(m.group(1)).decode('utf-8')
    css = re.sub(r'^\s*@charset[^;]*;\s*', '', css)          # meaningless inside <style>
    assert '</style' not in css.lower()
    block = '<style data-ucan="base">' + css + '</style>'
    saved += len(m.group(0)) - len(block)
    s = s[:m.start()] + block + s[m.end():]
    n += write(f, s)
print('stylesheet inlined on %d pages, %d KB smaller in total' % (n, saved // 1024))
