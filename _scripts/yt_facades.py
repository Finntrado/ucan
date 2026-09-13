# -*- coding: utf-8 -*-
"""Replace embedded YouTube players with click-to-play facades.

Each embedded player loads ~1 MB of YouTube JavaScript on page load. City
Champions has four of them: 2.3 s of blocked main thread and a PageSpeed
performance score of 71. A facade is the video's own thumbnail (served from
this site) with a play button; the real player is created only when someone
presses play, and without JS it is simply a link to the video.
Re-runnable.
"""
import glob, io, os, re, time, urllib.request
from PIL import Image

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone')
OUT = os.path.join(ROOT, 'assets', 'img', 'yt')
UA = {'User-Agent': 'Mozilla/5.0'}
os.makedirs(OUT, exist_ok=True)
IFRAME = re.compile(r'<iframe\s+src="https://www\.youtube(?:-nocookie)?\.com/embed/([\w-]{11})[^"]*"([^>]*)>\s*</iframe>')

PLAY = ('<span class="ytplay" aria-hidden="true"><svg viewBox="0 0 68 48"><path class="ytp-bg" '
        'd="M66.52 7.74c-.78-2.93-2.49-5.41-5.42-6.19C55.79.13 34 0 34 0S12.21.13 6.9 1.55C3.97 2.33 2.27 '
        '4.81 1.48 7.74.06 13.05 0 24 0 24s.06 10.95 1.48 16.26c.78 2.93 2.49 5.41 5.42 6.19C12.21 47.87 '
        '34 48 34 48s21.79-.13 27.1-1.55c2.93-.78 4.64-3.26 5.42-6.19C67.94 34.95 68 24 68 24s-.06-10.95'
        '-1.48-16.26z"/><path d="M45 24 27 14v20" fill="#fff"/></svg></span>')

CSS = ('<style data-ucan="ytf">'
       '.ytlite{position:relative;display:block;width:100%;height:100%;cursor:pointer;background:#000;'
       'text-decoration:none;overflow:hidden}'
       '.vid .ytlite{position:absolute;inset:0}'
       '.ytlite img{display:block;width:100%;height:100%;object-fit:cover;transition:opacity .25s ease}'
       '.ytlite:hover img{opacity:.86}'
       '.ytplay{position:absolute;top:50%;left:50%;width:68px;height:48px;transform:translate(-50%,-50%);'
       'pointer-events:none}'
       '.ytplay svg{display:block;width:100%;height:100%;filter:drop-shadow(0 2px 10px rgba(0,0,0,.35))}'
       '.ytplay .ytp-bg{fill:#212121;fill-opacity:.85;transition:fill .2s ease,fill-opacity .2s ease}'
       '.ytlite:hover .ytp-bg,.ytlite:focus-visible .ytp-bg{fill:#f00;fill-opacity:1}'
       '.ytlite:focus-visible{outline:2px solid var(--teal,#1F8F7B);outline-offset:3px}'
       '@media(prefers-reduced-motion:reduce){.ytlite img,.ytplay .ytp-bg{transition:none}}'
       '</style>')

JS = ('<script data-ucan="ytf">'
      '(function(){var a=document.querySelectorAll(".ytlite[data-yt]");'
      'Array.prototype.forEach.call(a,function(l){l.addEventListener("click",function(e){'
      'if(e.metaKey||e.ctrlKey||e.shiftKey||e.button)return;e.preventDefault();'
      'var f=document.createElement("iframe");'
      'f.src="https://www.youtube-nocookie.com/embed/"+l.getAttribute("data-yt")+"?autoplay=1&rel=0";'
      'f.title=l.getAttribute("data-title")||"Video";'
      'f.allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share";'
      'f.allowFullscreen=true;f.style.cssText="position:absolute;inset:0;width:100%;height:100%;border:0";'
      'l.parentNode.replaceChild(f,l);f.focus();});});})();'
      '</script>')


def thumb(vid):
    rel = 'assets/img/yt/%s.webp' % vid
    path = os.path.join(ROOT, rel)
    if not os.path.exists(path):
        raw = None
        for name in ('maxresdefault.jpg', 'sddefault.jpg', 'hqdefault.jpg'):
            try:
                raw = urllib.request.urlopen(urllib.request.Request(
                    'https://i.ytimg.com/vi/%s/%s' % (vid, name), headers=UA), timeout=30).read()
                im = Image.open(io.BytesIO(raw))
                if im.size[0] >= 480:
                    break
            except Exception:
                raw = None
        im = Image.open(io.BytesIO(raw)).convert('RGB')
        if im.width > 960:
            im = im.resize((960, round(im.height * 960 / im.width)), Image.LANCZOS)
        im.save(path, 'WEBP', quality=72, method=6)
    w, h = Image.open(path).size
    return rel, w, h


n_pages = n_vids = 0
for f in sorted(glob.glob(os.path.join(ROOT, '*.html'))):
    s = io.open(f, encoding='utf-8', newline='').read()
    if not IFRAME.search(s):
        continue

    def repl(m):
        vid, rest = m.group(1), m.group(2)
        title = re.search(r'\stitle="([^"]*)"', rest)
        title = title.group(1) if title else 'Video'
        rel, w, h = thumb(vid)
        return ('<a class="ytlite" href="https://www.youtube.com/watch?v=%s" data-yt="%s" data-title="%s" '
                'aria-label="Play video: %s" target="_blank" rel="noopener noreferrer">'
                '<img src="%s" width="%d" height="%d" alt="" loading="lazy" decoding="async">%s</a>'
                % (vid, vid, title, title, rel, w, h, PLAY))
    s, k = IFRAME.subn(repl, s)
    s = re.sub(r'<style data-ucan="ytf">.*?</style>', '', s, flags=re.S)
    s = re.sub(r'<script data-ucan="ytf">.*?</script>', '', s, flags=re.S)
    s = s.replace('</head>', CSS + '</head>', 1).replace('</body>', JS + '</body>', 1)
    io.open(f, 'w', encoding='utf-8', newline='').write(s)
    n_pages += 1; n_vids += k
    print('  %-60s %d video(s)' % (os.path.basename(f), k))
print('facades: %d videos on %d pages' % (n_vids, n_pages))
