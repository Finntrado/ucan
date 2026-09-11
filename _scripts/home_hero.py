# -*- coding: utf-8 -*-
"""Homepage hero: headline centred full-width, network diagram replaced by an
interactive field of linking dots behind it. Re-runnable."""
import io, os, re

F = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'standalone', 'index.html')
s = io.open(F, encoding='utf-8', newline='').read()
eol = '\r\r\n' if '\r\r\n' in s[:4000] else ('\r\n' if '\r\n' in s[:4000] else '\n')
N = lambda t: t.replace('\n', eol)

# ---- 1. the diagram goes ----------------------------------------------------
m = re.search(r'\s*<!-- Signature element: the connective tissue -->\s*<figure class="net".*?</figure>', s, re.S)
if m:
    s = s[:m.start()] + s[m.end():]
assert '<figure class="net"' not in s

# ---- 2. headline words, each able to travel in from its own direction --------
H1_OLD = ('<h1 id="h1">Turning individual effort into <span class="accent">collective action</span>'
          ' across India\'s cities and towns</h1>')
if H1_OLD in s:
    # (dx, dy, rotation) per word: scattered, as individual efforts are
    plan = [('Turning', -60, -26, -6), ('individual', 44, -40, 5), ('effort', 70, 18, 7),
            ('into', -30, 36, -4)]
    accent = [('collective', -46, 30, 0), ('action', 52, -28, 0)]
    tail = [('across', -64, 22, -5), ("India's", 38, 40, 4), ('cities', -24, -34, -3),
            ('and', 60, -16, 6), ('towns', -40, 30, -5)]
    i = 0
    def w(word, dx, dy, r):
        global i
        out = ('<span class="hw" style="--i:%d;--dx:%dpx;--dy:%dpx;--r:%ddeg">%s</span>'
               % (i, dx, dy, r, word))
        i += 1
        return out
    parts = [w(*p) for p in plan]
    acc = '<span class="accent">' + ' '.join(w(*p) for p in accent) + '</span>'
    rest = [w(*p) for p in tail]
    BR = '<br class="hbr">'
    H1_NEW = ('<h1 id="h1">' + ' '.join(parts) + BR + ' ' + acc + ' ' + rest[0] + BR + ' ' +
              ' '.join(rest[1:]) + '</h1>')
    s = s.replace(H1_OLD, H1_NEW, 1)
assert 'class="hw"' in s
if '<br class="hbr">' not in s:
    s = s.replace('>into</span>', '>into</span><br class="hbr">', 1)
    s = s.replace('>across</span>', '>across</span><br class="hbr">', 1)
assert s.count('<br class="hbr">') == 2

# ---- 3. the field -------------------------------------------------------------
if '<canvas class="hero-field"' not in s:
    s = s.replace('<section class="hero" aria-labelledby="h1">',
                  '<section class="hero" aria-labelledby="h1">' + eol +
                  '  <canvas class="hero-field" aria-hidden="true"></canvas>', 1)

CSS = N('''<style data-ucan="hero">
/* Centred hero. The headline now owns the width the diagram used to share. */
.hero{position:relative;isolation:isolate;padding:clamp(64px,8vw,112px) 0 clamp(56px,6vw,84px)!important}
.hero-grid{display:block!important;position:relative;z-index:1;text-align:center}
.hero-grid>div{max-width:1040px;margin:0 auto}
.hero .eyebrow{justify-content:center}
.hero .eyebrow::after{content:"";width:24px;height:1.5px;background:currentColor;flex:0 0 24px}
.hero h1{max-width:13.1em!important;margin:24px auto 26px!important;text-align:center;text-wrap:balance}
/* Pinned breaks: balanced wrapping moved "into" between lines 1 and 2 when the
   webfont replaced its fallback, shifting the whole hero. */
.hero h1 .hbr{display:none}
@media(min-width:768px){.hero h1 .hbr{display:inline}.hero h1{max-width:14.2em!important;text-wrap:wrap}}
.hero .lede{max-width:39em!important;margin:0 auto!important}
.hero-actions{justify-content:center}
.hero-proof{justify-content:center;max-width:560px;margin-left:auto!important;margin-right:auto!important}
.hero-proof div{text-align:center}

/* words arrive from where they were, and settle into one line of thought */
/* Transform only: the words are legible from the very first paint (so the
   headline counts as painted at once - it is the page's LCP), they just
   travel the last few pixels into place. Composited, no main-thread work. */
.hero h1 .hw{display:inline-block;animation:hwin .8s cubic-bezier(.2,.75,.25,1) both;
  animation-delay:calc(var(--i) * 45ms)}
@keyframes hwin{from{transform:translate(calc(var(--dx) * .45),calc(var(--dy) * .45)) rotate(calc(var(--r) * .6))}
  to{transform:none}}
/* the orange underline draws with scaleX rather than background-size, which
   the browser can't hand to the compositor */
.hero h1 .accent{position:relative;background:none!important;animation:none!important}
.hero h1 .accent::after{content:"";position:absolute;left:0;right:0;bottom:.02em;height:.16em;
  background:var(--orange,#FEAE00);z-index:-1;transform:scaleX(0);transform-origin:left center;
  animation:swashx .9s .75s cubic-bezier(.22,.61,.36,1) forwards}
@keyframes swashx{to{transform:scaleX(1)}}

/* the field: individuals drifting, linking up when they come close */
.hero-field{position:absolute;inset:0 0 auto 0;z-index:0;width:100%;display:block;
  pointer-events:none;
  -webkit-mask-image:radial-gradient(ellipse 46% 52% at 50% 46%,rgba(0,0,0,.18) 0%,rgba(0,0,0,.55) 55%,#000 100%);
          mask-image:radial-gradient(ellipse 46% 52% at 50% 46%,rgba(0,0,0,.18) 0%,rgba(0,0,0,.55) 55%,#000 100%)}
@media(prefers-reduced-motion:reduce){.hero h1 .hw{animation:none}.hero h1 .accent::after{animation:none;transform:none}}
</style>
''')
s = re.sub(r'<style data-ucan="hero">.*?</style>\s*', '', s, flags=re.S)
s = s.replace('</head>', CSS + '</head>', 1)

JS = N('''<script data-ucan="hero">
/* Individual effort into collective action, drawn: dots drift on their own and
   link when they come within reach of each other; the visitor's cursor pulls
   nearby dots in and links them too. Canvas only, no layout, so no CLS; it
   sleeps when the hero is off screen and draws a single still frame for
   reduced-motion. */
(function () {
  var hero = document.querySelector('.hero');
  var cv = hero && hero.querySelector('.hero-field');
  if (!cv || !cv.getContext) return;
  var ctx = cv.getContext('2d');
  var still = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
  var W = 0, H = 0, dpr = 1, dots = [], mouse = null, on = true, raf = 0;
  var LINK = 128, PULL = 170;

  function size() {
    var r = hero.getBoundingClientRect();
    var g = hero.querySelector('.hero-grid');
    var gb = g ? g.getBoundingClientRect().bottom - r.top : r.height;
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    W = r.width; H = Math.min(r.height, Math.round(gb + 56));
    cv.style.height = H + 'px';
    cv.width = Math.round(W * dpr); cv.height = Math.round(H * dpr);
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    var want = Math.max(28, Math.min(90, Math.round(W * H / 15000)));
    while (dots.length < want) dots.push(dot());
    dots.length = want;
  }
  function dot() {
    var a = Math.random() * Math.PI * 2, v = 0.12 + Math.random() * 0.22;
    return { x: Math.random() * (W || 1200), y: Math.random() * (H || 700),
             vx: Math.cos(a) * v, vy: Math.sin(a) * v, r: 1.4 + Math.random() * 1.8 };
  }
  function frame() {
    ctx.clearRect(0, 0, W, H);
    var i, j, a, b, dx, dy, d;
    for (i = 0; i < dots.length; i++) {
      a = dots[i];
      if (mouse) {
        dx = mouse.x - a.x; dy = mouse.y - a.y; d = Math.sqrt(dx * dx + dy * dy);
        if (d < PULL && d > 1) { a.vx += dx / d * 0.018; a.vy += dy / d * 0.018; }
      }
      a.vx *= 0.992; a.vy *= 0.992;
      var sp = Math.sqrt(a.vx * a.vx + a.vy * a.vy);
      if (sp < 0.1) { a.vx *= 1.04; a.vy *= 1.04; }
      if (sp > 1.1) { a.vx *= 0.9; a.vy *= 0.9; }
      a.x += a.vx; a.y += a.vy;
      if (a.x < -10) a.x = W + 10; else if (a.x > W + 10) a.x = -10;
      if (a.y < -10) a.y = H + 10; else if (a.y > H + 10) a.y = -10;
    }
    ctx.lineWidth = 1;
    for (i = 0; i < dots.length; i++) {
      a = dots[i];
      for (j = i + 1; j < dots.length; j++) {
        b = dots[j]; dx = a.x - b.x; dy = a.y - b.y;
        if (Math.abs(dx) > LINK || Math.abs(dy) > LINK) continue;
        d = Math.sqrt(dx * dx + dy * dy);
        if (d < LINK) {
          ctx.strokeStyle = 'rgba(31,143,123,' + (0.26 * (1 - d / LINK)).toFixed(3) + ')';
          ctx.beginPath(); ctx.moveTo(a.x, a.y); ctx.lineTo(b.x, b.y); ctx.stroke();
        }
      }
      if (mouse) {
        dx = a.x - mouse.x; dy = a.y - mouse.y; d = Math.sqrt(dx * dx + dy * dy);
        if (d < PULL) {
          ctx.strokeStyle = 'rgba(14,83,72,' + (0.45 * (1 - d / PULL)).toFixed(3) + ')';
          ctx.beginPath(); ctx.moveTo(a.x, a.y); ctx.lineTo(mouse.x, mouse.y); ctx.stroke();
        }
      }
    }
    for (i = 0; i < dots.length; i++) {
      a = dots[i];
      ctx.fillStyle = 'rgba(31,143,123,.55)';
      ctx.beginPath(); ctx.arc(a.x, a.y, a.r, 0, 6.2832); ctx.fill();
    }
    if (mouse) {
      ctx.fillStyle = '#0E5348';
      ctx.beginPath(); ctx.arc(mouse.x, mouse.y, 4.5, 0, 6.2832); ctx.fill();
    }
  }
  function loop() { frame(); raf = on && !document.hidden ? requestAnimationFrame(loop) : 0; }
  function start() { if (!raf && !still) raf = requestAnimationFrame(loop); }

  function boot() {
    size();
    if (still) { frame(); return; }
    start();
    hero.addEventListener('pointermove', function (e) {
      if (e.pointerType === 'touch') return;
      var r = hero.getBoundingClientRect();
      mouse = { x: e.clientX - r.left, y: e.clientY - r.top };
    });
    hero.addEventListener('pointerleave', function () { mouse = null; });
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (es) {
        on = es[0].isIntersecting; if (on) start();
      }).observe(hero);
    }
    document.addEventListener('visibilitychange', function () { if (!document.hidden) start(); });
  }
  var t;
  window.addEventListener('resize', function () {
    clearTimeout(t); t = setTimeout(function () { size(); if (still) frame(); }, 150);
  });
  // after first paint, so the canvas never competes with the headline for LCP
  if ('requestIdleCallback' in window) requestIdleCallback(boot, { timeout: 1200 });
  else setTimeout(boot, 300);
})();
</script>''')
s = re.sub(r'<script data-ucan="hero">.*?</script>\s*', '', s, flags=re.S)
s = s.replace('</body>', JS + eol + '</body>', 1)

io.open(F, 'w', encoding='utf-8', newline='').write(s)
print('homepage hero rebuilt')
