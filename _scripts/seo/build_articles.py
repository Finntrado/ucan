# -*- coding: utf-8 -*-
"""Builds the 5 "Urban Perspectives" articles + the hub page into standalone/.

Source: the client's Google Docs (HTML export, _scripts/seo/src/). The doc text
is used as written; this script only (1) converts it to the site's markup with
every source hyperlinked (utm_* tracking stripped), (2) adds the AEO layer:
question heading + 40-60 word direct answer, key takeaways, sourced statistics,
diagram, FAQ, sources, author box, related reading, and (3) writes the SEO layer:
title/description/canonical/hreflang/OG/Twitter and one JSON-LD graph per page.

The page shell is an existing blog page (same chrome, fonts, CSS): its head tags
that we own are stripped, our own head block is inserted before </head>, and its
<main> is replaced. Idempotent: rewrites the same 6 files each run.

  python _scripts/seo/build_articles.py [--pdf-name FILE.pdf]
"""
import html as H
import io
import json
import os
import re
import sys
from urllib.parse import parse_qsl, unquote, urlencode, urlparse, urlunparse

from bs4 import BeautifulSoup, NavigableString, Tag

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
import add_menu  # noqa: E402
from articles_config import (ARTICLES, AUTHOR, MODIFIED, PUBLISHED, RELATED_BLURB, SITE)  # noqa: E402

ROOT = os.path.normpath(os.path.join(HERE, '..', '..'))
SRC = os.path.join(HERE, 'src')
OUT = os.path.join(ROOT, 'standalone')
SHELL = os.path.join(OUT, 'blog-the-urban-haze.html')
OG_IMG = SITE + '/assets/img/og-default.jpg'
BY_N = {a['n']: a for a in ARTICLES}

# ---------------------------------------------------------------- parsing

def esc(t):
    return H.escape(t.replace('\xa0', ' '), quote=False)


def clean_url(u):
    m = re.search(r'google\.com/url\?q=([^&]+)', u)
    if m:
        u = unquote(m.group(1))
    p = urlparse(u)
    q = [(k, v) for k, v in parse_qsl(p.query, keep_blank_values=True) if not k.lower().startswith('utm_')]
    return urlunparse(p._replace(query=urlencode(q)))


class Doc:
    def __init__(self, path):
        self.soup = BeautifulSoup(io.open(path, encoding='utf-8').read(), 'html.parser')
        css = ' '.join(t.get_text() for t in self.soup.find_all('style'))
        self.bold, self.ital = set(), set()
        for m in re.finditer(r'\.(c\d+)\{([^}]*)\}', css):
            cls, body = m.groups()
            if re.search(r'font-weight:\s*(700|bold)', body):
                self.bold.add(cls)
            if re.search(r'font-style:\s*italic', body):
                self.ital.add(cls)

    def inline(self, el):
        out = []
        for ch in el.children:
            if isinstance(ch, NavigableString):
                out.append(esc(str(ch)))
            elif ch.name == 'a':
                href = clean_url(ch.get('href', ''))
                inner = self.inline(ch)
                if href.startswith('http') and inner.strip():
                    out.append('<a href="%s" target="_blank" rel="noopener">%s</a>' % (H.escape(href), inner))
                else:
                    out.append(inner)
            elif ch.name == 'span':
                inner = self.inline(ch)
                cls = set(ch.get('class', []))
                if inner.strip():
                    if cls & self.bold:
                        inner = '<strong>%s</strong>' % inner
                    if cls & self.ital:
                        inner = '<em>%s</em>' % inner
                out.append(inner)
            elif ch.name == 'br':
                out.append(' ')
            elif isinstance(ch, Tag) and ch.name not in ('ul', 'ol'):
                out.append(self.inline(ch))
        return ''.join(out)

    def blocks(self):
        raw = []
        for el in self.soup.body.children:
            if not isinstance(el, Tag):
                continue
            n = el.name
            if n in ('h1', 'h2', 'h3', 'h4', 'h5', 'h6'):
                t = el.get_text(' ', strip=True).replace('\xa0', ' ')
                if t:
                    raw.append(dict(t='h', lvl=int(n[1]), text=t))
            elif n == 'p':
                txt = el.get_text(' ', strip=True).replace('\xa0', ' ')
                if not txt or set(txt) <= set('_-–— '):
                    continue
                if txt.startswith('☐'):
                    raw.append(dict(t='ck', items=[re.sub(r'^☐\s*', '', self.inline(el).strip())]))
                else:
                    raw.append(dict(t='p', html=self.inline(el).strip(), text=txt))
            elif n in ('ul', 'ol'):
                items = [self.inline(li).strip() for li in el.find_all('li', recursive=False)]
                items = [i for i in items if i]
                if items:
                    raw.append(dict(t=n, items=items))
            elif n == 'table':
                rows = []
                for tr in el.find_all('tr'):
                    rows.append([self.inline(td).strip() for td in tr.find_all(['td', 'th'])])
                rows = [r for r in rows if any(c for c in r)]
                if rows:
                    raw.append(dict(t='table', rows=rows))
        merged = []
        for b in raw:  # merge adjacent lists / checklist paragraphs
            if merged and b['t'] in ('ul', 'ol', 'ck') and merged[-1]['t'] == b['t']:
                merged[-1]['items'] += b['items']
            else:
                merged.append(b)
        return merged


def slugify(t, used):
    s = re.sub(r'[^a-z0-9]+', '-', t.lower()).strip('-')[:60] or 'section'
    base, i = s, 2
    while s in used:
        s = '%s-%d' % (base, i)
        i += 1
    used.add(s)
    return s


def normalise(blocks):
    """First h1 = title. h1 -> h2; ALL-CAPS h2 -> h3; other levels kept."""
    title = None
    out = []
    for b in blocks:
        if b['t'] == 'h':
            if title is None and b['lvl'] == 1:
                title = b['text']
                continue
            lvl = b['lvl']
            if lvl == 1:
                lvl = 2
            elif lvl == 2 and b['text'].isupper():
                lvl = 3
            b = dict(b, lvl=lvl)
        out.append(b)
    return title, out


def split_zones(blocks):
    """-> (intro_and_body, faq[(q, [answer blocks])], sources_blocks, notes)"""
    faq_i = next((i for i, b in enumerate(blocks) if b['t'] == 'h' and re.match(r'^frequently asked questions$', b['text'], re.I)), None)
    src_i = next((i for i, b in enumerate(blocks) if b['t'] == 'h' and re.search(r'^(key )?sources|further reading', b['text'], re.I)), None)
    if faq_i is None:
        raise SystemExit('no FAQ section found')
    body = blocks[:faq_i]
    j = faq_i + 1
    faq, cur = [], None
    while j < len(blocks) and j != src_i:
        b = blocks[j]
        is_q = (b['t'] == 'h' and b['text'].endswith('?')) or (b['t'] == 'p' and b['text'].endswith('?') and len(b['text']) < 130)
        if is_q:
            cur = (b['text'], [])
            faq.append(cur)
        elif b['t'] == 'h':
            break  # a non-question heading ends the FAQ (e.g. "Conclusion")
        elif cur is not None:
            cur[1].append(b)
        j += 1
    post = []
    while j < len(blocks) and (src_i is None or j < src_i):
        post.append(blocks[j])
        j += 1
    body += [b for b in post if not (b['t'] == 'h' and re.match(r"author.s note", b['text'], re.I))]
    notes = []
    k = next((i for i, b in enumerate(post) if b['t'] == 'h' and re.match(r"author.s note", b['text'], re.I)), None)
    if k is not None:
        notes = [b for b in post[k + 1:] if b['t'] == 'p']
        body = [b for b in body if b not in post[k:]]
    sources = blocks[src_i + 1:] if src_i is not None else []
    return body, faq, sources, notes


# ---------------------------------------------------------------- rendering

def L(slug):
    return slug  # relative, like every other standalone page


def render_block(b, ids):
    t = b['t']
    if t == 'h':
        tag = 'h%d' % min(b['lvl'], 4)
        hid = slugify(b['text'], ids)
        b['id'] = hid
        return '<%s id="%s">%s</%s>' % (tag, hid, esc(b['text']), tag)
    if t == 'p':
        return '<p>%s</p>' % b['html']
    if t in ('ul', 'ol'):
        return '<%s>%s</%s>' % (t, ''.join('<li>%s</li>' % i for i in b['items']), t)
    if t == 'ck':
        return '<ul class="ck">%s</ul>' % ''.join('<li>%s</li>' % i for i in b['items'])
    if t == 'table':
        rows = b['rows']
        head = '<thead><tr>%s</tr></thead>' % ''.join('<th>%s</th>' % c for c in rows[0])
        body = '<tbody>%s</tbody>' % ''.join('<tr>%s</tr>' % ''.join('<td>%s</td>' % c for c in r) for r in rows[1:])
        return '<div class="tbl"><table>%s%s</table></div>' % (head, body)
    return ''


def pick_items(spec, blocks):
    kind, arg, n = spec
    heads = [b for b in blocks if b['t'] == 'h']
    num = re.compile(r'^\d+\.\s*')
    if kind == 'h2num':
        got = [num.sub('', h['text']) for h in heads if h['lvl'] == 2 and num.match(h['text'])]
    elif kind in ('h3num', 'h3num_first'):
        i = next(i for i, b in enumerate(blocks) if b['t'] == 'h' and b['text'] == arg)
        got = []
        for b in blocks[i + 1:]:
            if b['t'] == 'h' and b['lvl'] == 2 and got:
                break
            if b['t'] == 'h' and b['lvl'] == 3 and num.match(b['text']):
                got.append(num.sub('', b['text']))
    elif kind == 'h3names':
        names = arg.split('|')
        got = [h['text'] for h in heads if h['lvl'] == 3 and h['text'] in names]
    else:
        raise SystemExit('unknown pick %s' % kind)
    if len(got) < n:
        raise SystemExit('diagram pick %s found %d of %d' % (spec, len(got), n))
    got = got[:n]
    if kind == 'h2num':  # long "define the problem before defining the partnership" -> use the doc's short stage list
        for b in blocks:
            if b['t'] == 'ol' and len(b['items']) == n:
                got = [re.sub(r'<[^>]+>', '', i).strip() for i in b['items']]
                break
    return got


def diagram_html(cfg, blocks):
    d = cfg['diagram']
    items = pick_items(d['pick'], blocks)
    alt = '%s. %s.' % (d['title'], '; '.join('%d %s' % (i + 1, x) for i, x in enumerate(items)))
    kind = d['kind']
    if kind == 'pillars':
        inner = '<div class="dg-roof">%s</div><ol class="dg dg-pillars">%s</ol>' % (
            esc(d['roof']), ''.join('<li><b>%d</b><span>%s</span></li>' % (i + 1, esc(x)) for i, x in enumerate(items)))
    elif kind == 'stairs':
        inner = '<ol class="dg dg-stairs" style="--n:%d">%s</ol>' % (
            len(items), ''.join('<li style="--i:%d"><b>%d</b><span>%s</span></li>' % (i, i + 1, esc(x)) for i, x in enumerate(items)))
    else:
        extra = '<li class="dg-back" aria-hidden="true"><span>↺ Then back to “%s”</span></li>' % esc(items[0]) if kind == 'loop' else ''
        inner = '<ol class="dg dg-steps">%s%s</ol>' % (
            ''.join('<li><b>%d</b><span>%s</span></li>' % (i + 1, esc(x)) for i, x in enumerate(items)), extra)
    return ('<figure class="dg-fig rv" role="group" aria-label="Diagram: %s">'
            '<figcaption class="dg-t">%s</figcaption>%s<p class="dg-cap">%s</p></figure>'
            % (H.escape(alt, quote=True), esc(d['title']), inner, esc(d['caption']))), items


def stats_html(cfg):
    cards = []
    for fig, label, src in cfg['stats']:
        if src is None:
            s = ''
        elif src[1].startswith('/'):
            s = '<small>Source: <a href="%s">%s</a></small>' % (L(src[1].lstrip('/')), esc(src[0]))
        else:
            s = '<small>Source: <a href="%s" target="_blank" rel="noopener">%s</a></small>' % (H.escape(src[1]), esc(src[0]))
        cards.append('<div class="stat"><b>%s</b><span>%s</span>%s</div>' % (esc(fig), esc(label), s))
    head = 'U-CAN in numbers' if cfg['n'] == 2 else 'By the numbers'
    return ('<section class="stats-wrap" aria-labelledby="st-h"><h2 id="st-h">%s</h2><div class="stats">%s</div></section>'
            % (head, ''.join(cards)))


def words(html):
    return len(re.findall(r"\S+", re.sub(r'<[^>]+>', ' ', html)))


def fmt_date(iso):
    y, m, d = iso.split('-')
    mon = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'][int(m) - 1]
    return '%d %s %s' % (int(d), mon, y)


def author_html():
    return ('<div class="authorcard"><p class="authorcard-k">About the author</p>'
            '<p class="authorcard-n"><a href="about">%s</a></p><p>%s</p></div>') % (esc(AUTHOR['name']), esc(AUTHOR['bio']))


def related_html(cfg):
    lis = ''.join('<li><a href="%s">%s</a><span>%s</span></li>' % (
        BY_N[n]['slug'], esc(BY_N[n]['keyword']), esc(RELATED_BLURB[n])) for n in cfg['related'])
    return ('<section class="art-related" aria-labelledby="rel-h"><h2 id="rel-h">Keep reading: Urban Perspectives</h2>'
            '<ul>%s</ul><p><a href="urban-perspectives">See all Urban Perspectives →</a></p></section>' % lis)


def main_html(cfg, title, lede, body, faq, sources, notes, blocks_all, pdf_href):
    ids = set()
    n_words = 0
    body_html, h2s = [], []
    first_h2_done = seen_h2 = 0
    diagram, items = diagram_html(cfg, blocks_all)
    stats = stats_html(cfg)
    for b in body:
        if b['t'] == 'h' and b['lvl'] == 2:
            seen_h2 += 1
            if seen_h2 == 1:
                body_html.append(diagram)
            if seen_h2 == 2:
                body_html.append(stats)
        h = render_block(b, ids)
        if b['t'] == 'h' and b['lvl'] == 2:
            h2s.append((b['id'], b['text']))
        body_html.append(h)
    if seen_h2 < 2:
        body_html.append(stats)
    body_txt = ' '.join(re.sub(r'<[^>]+>', ' ', x) for x in body_html)
    n_words = words(body_txt)

    faq_html = ''.join('<h3 id="%s">%s</h3>%s' % (slugify(q, ids), esc(q), ''.join(render_block(a, ids) for a in ans)) for q, ans in faq)
    src_html = ''
    for b in sources:
        if b['t'] in ('ul', 'ol'):
            src_html += '<ol class="src">%s</ol>' % ''.join('<li>%s</li>' % i for i in b['items'])
    notes_html = ''.join('<p>%s</p>' % n['html'] for n in notes)
    toc = ''.join('<li><a href="#%s">%s</a></li>' % (i, esc(t)) for i, t in h2s[:40])
    toc += '<li><a href="#faq">Frequently asked questions</a></li>'
    if src_html:
        toc += '<li><a href="#sources">Sources</a></li>'

    dl = ''
    if cfg.get('pdf'):
        dl = ('<div class="dl"><p class="dl-k">Free download</p><p class="dl-t">Urban Collaboration Checklist (PDF)</p>'
              '<p>The one-page checklist, the 10-minute test and the scorecard, ready to print for your next partnership meeting.</p>'
              '<a class="btn" href="%s" download>Download the PDF</a></div>') % pdf_href
    read_min = max(1, round((n_words + words(faq_html)) / 200.0))

    crumb = ('<nav class="crumb" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">/</span>'
             '<a href="urban-perspectives">Urban Perspectives</a><span aria-hidden="true">/</span><span>%s</span></nav>') % esc(cfg['crumb'])
    hero = ('<section class="hero" aria-labelledby="pt"><div class="hero-in">%s<p class="hero-tag">%s</p>'
            '<h1 id="pt">%s</h1><p class="hero-lede">%s</p>'
            '<p class="post-byline">By <a href="about" style="color:var(--lime);font-weight:600;text-decoration:none">%s</a> '
            '<span aria-hidden="true">·</span> Published <time datetime="%s">%s</time> '
            '<span aria-hidden="true">·</span> Updated <time datetime="%s">%s</time> '
            '<span aria-hidden="true">·</span> %d min read</p></div></section>') % (
        crumb, esc(cfg['kicker']), esc(title), esc(lede), esc(AUTHOR['byline']),
        PUBLISHED, fmt_date(PUBLISHED), MODIFIED, fmt_date(MODIFIED), read_min)

    answer = ('<section class="art-answer" aria-labelledby="ans-h"><h2 id="ans-h">%s</h2><p>%s</p></section>'
              % (esc(cfg['q']), esc(cfg['answer'])))
    answer = share_html(cfg, title, 'Share this guide') + answer
    take = ('<section class="art-take" aria-labelledby="tk-h"><h2 id="tk-h">Key takeaways</h2><ul>%s</ul></section>'
            % ''.join('<li>%s</li>' % esc(t) for t in cfg['takeaways']))
    main = ('<main id="main">%s<section class="sec art" aria-label="Article"><div class="wrap art-wrap">'
            '<article class="art-main">%s%s<div class="post-body art-body">%s'
            '<section id="faq" class="faq" aria-labelledby="faq-h"><h2 id="faq-h">Frequently asked questions</h2>%s</section>'
            '%s%s</div>%s%s%s</article>'
            '<aside class="art-side"><nav class="art-toc" aria-label="On this page"><p class="art-toc-h">On this page</p><ol>%s</ol></nav>%s</aside>'
            '</div></section></main>') % (
        hero, answer, take, ''.join(body_html), faq_html,
        ('<section id="sources" class="srcs" aria-labelledby="src-h"><h2 id="src-h">Sources and further reading</h2>%s</section>' % src_html) if src_html else '',
        ('<div class="art-note"><p class="art-note-k">About this article</p>%s</div>' % notes_html) if notes_html else '',
        author_html() + share_html(cfg, title, 'Found this useful? Share it').replace('class="art-share"', 'class="art-share bottom"'), related_html(cfg), dl, toc, dl)
    main = main.replace('</main>', SHARE_JS + '</main>')
    return main, dict(h2=h2s, items=items, words=n_words + words(faq_html), read=read_min)


# ---------------------------------------------------------------- share

ICON = {
    'linkedin': '<path d="M4.98 3.5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM.5 8h4V21h-4zM8 8h3.8v1.8h.05c.53-.95 1.83-1.95 3.76-1.95C19.7 7.85 21 10.1 21 13.3V21h-4v-6.9c0-1.65-.03-3.77-2.3-3.77-2.3 0-2.65 1.8-2.65 3.65V21H8z" fill="currentColor"/>',
    'x': '<path d="M17.5 2h3.3l-7.2 8.24L22 22h-6.6l-5.18-6.78L4.3 22H1l7.7-8.8L1.4 2H8.2l4.68 6.19L17.5 2zm-1.16 18h1.83L7.75 3.9H5.79z" fill="currentColor"/>',
    'whatsapp': '<path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2zm5.8 14.18c-.24.68-1.42 1.31-1.95 1.35-.5.04-.98.22-3.3-.69-2.79-1.1-4.55-3.96-4.69-4.15-.14-.19-1.12-1.49-1.12-2.85s.71-2.02.96-2.3c.25-.27.55-.34.73-.34h.52c.17 0 .4-.06.62.48.24.57.8 1.98.87 2.12.07.14.12.31.02.5-.09.19-.14.31-.28.47l-.42.49c-.14.14-.28.29-.12.57.16.27.72 1.18 1.54 1.91 1.06.94 1.95 1.24 2.22 1.38.27.14.43.12.59-.07.16-.19.68-.79.86-1.06.18-.27.36-.22.61-.13.24.09 1.55.73 1.82.86.27.14.44.2.51.32.06.11.06.67-.18 1.35z" fill="currentColor"/>',
    'email': '<path d="M3 5h18a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zm9 8.2L4.4 7h15.2z" fill="currentColor"/>',
    'link': '<path d="M10.6 13.4a4 4 0 0 0 5.66 0l3-3a4 4 0 1 0-5.66-5.66l-1.5 1.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M13.4 10.6a4 4 0 0 0-5.66 0l-3 3a4 4 0 1 0 5.66 5.66l1.5-1.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
    'share': '<path d="M12 3v12M8 7l4-4 4 4M5 12v7h14v-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
}


def share_html(cfg, title, label):
    from urllib.parse import quote
    url = '%s/%s' % (SITE, cfg['slug'])
    u, t = quote(url, safe=''), quote(title, safe='')
    a = lambda href, name, key, txt: ('<a href="%s" target="_blank" rel="noopener noreferrer" aria-label="Share on %s"><svg viewBox="0 0 24 24" aria-hidden="true">%s</svg>%s</a>'
                                      % (href.replace('&', '&amp;'), name, ICON[key], txt))
    return ('<div class="art-share" data-title="%s" data-url="%s"><span class="lbl">%s</span>%s%s%s%s'
            '<button type="button" class="art-copy" hidden><svg viewBox="0 0 24 24" aria-hidden="true">%s</svg><span>Copy link</span></button>'
            '<button type="button" class="art-native" hidden><svg viewBox="0 0 24 24" aria-hidden="true">%s</svg><span>Share…</span></button></div>') % (
        H.escape(title, quote=True), url, esc(label),
        a('https://www.linkedin.com/sharing/share-offsite/?url=' + u, 'LinkedIn', 'linkedin', 'LinkedIn'),
        a('https://twitter.com/intent/tweet?url=%s&text=%s' % (u, t), 'X', 'x', 'X'),
        a('https://api.whatsapp.com/send?text=%s%%20%s' % (t, u), 'WhatsApp', 'whatsapp', 'WhatsApp'),
        a('mailto:?subject=%s&body=%s' % (t, u), 'Email', 'email', 'Email'),
        ICON['link'], ICON['share'])


SHARE_JS = ('<script data-ucan="artshare">(function(){var bars=document.querySelectorAll(".art-share");'
            'Array.prototype.forEach.call(bars,function(bar){var url=bar.getAttribute("data-url"),title=bar.getAttribute("data-title");'
            'var copy=bar.querySelector(".art-copy"),nat=bar.querySelector(".art-native");'
            'if(copy&&navigator.clipboard){copy.hidden=false;var lb=copy.querySelector("span");copy.addEventListener("click",function(){'
            'navigator.clipboard.writeText(url).then(function(){var old=lb.textContent;lb.textContent="Link copied";setTimeout(function(){lb.textContent=old;},1800);});});}'
            'if(nat&&navigator.share){nat.hidden=false;nat.addEventListener("click",function(){navigator.share({title:title,url:url}).catch(function(){});});}'
            '});})();</script>')

# ---------------------------------------------------------------- schema

def org_node():
    return {
        '@type': 'Organization', '@id': SITE + '/#org', 'name': 'Urban Collective Action Network (U-CAN)',
        'alternateName': 'U-CAN', 'url': SITE + '/', 'email': 'connect@urban.org.in',
        'description': "U-CAN is a network of organisations working together to strengthen urban problem-solving in India's Tier II and Tier III cities.",
        'foundingDate': '2022', 'areaServed': 'IN',
        'sameAs': ['https://www.linkedin.com/company/urban-collective-action-network-u-can/', 'https://www.youtube.com/@U-CAN24'],
    }


def ld_graph(cfg, title, meta, faq, pdf_href):
    url = '%s/%s' % (SITE, cfg['slug'])
    org = {'@id': SITE + '/#org'}
    g = [
        org_node(),
        {'@type': 'WebPage', '@id': url + '#webpage', 'url': url, 'name': cfg['title'], 'description': cfg['description'],
         'inLanguage': 'en-IN', 'isPartOf': {'@id': SITE + '/#org'},
         'breadcrumb': {'@id': url + '#breadcrumb'}, 'primaryImageOfPage': {'@type': 'ImageObject', 'url': OG_IMG},
         'speakable': {'@type': 'SpeakableSpecification', 'cssSelector': ['.art-answer', '.art-take']}},
        {'@type': 'Article', '@id': url + '#article', 'headline': title, 'description': cfg['description'],
         'inLanguage': 'en-IN', 'datePublished': PUBLISHED, 'dateModified': MODIFIED, 'mainEntityOfPage': {'@id': url + '#webpage'},
         'author': ({'@type': 'Person', 'name': AUTHOR['name']} if AUTHOR['kind'] == 'Person' else org),
         'publisher': org, 'image': [OG_IMG], 'articleSection': 'Urban governance', 'wordCount': meta['words'],
         'keywords': [cfg['keyword'], 'urban governance India', 'Indian cities', 'Tier II and Tier III cities'],
         'about': [{'@type': 'Thing', 'name': cfg['keyword']}]},
        {'@type': 'BreadcrumbList', '@id': url + '#breadcrumb', 'itemListElement': [
            {'@type': 'ListItem', 'position': 1, 'name': 'Home', 'item': SITE + '/'},
            {'@type': 'ListItem', 'position': 2, 'name': 'Urban Perspectives', 'item': SITE + '/urban-perspectives'},
            {'@type': 'ListItem', 'position': 3, 'name': cfg['crumb'], 'item': url}]},
        {'@type': 'FAQPage', '@id': url + '#faq', 'mainEntity': [
            {'@type': 'Question', 'name': q, 'acceptedAnswer': {'@type': 'Answer', 'text': ' '.join(
                re.sub(r'<[^>]+>', ' ', ''.join(render_block(a, set()) for a in ans)).split())}} for q, ans in faq]},
    ]
    if cfg.get('pdf'):
        steps = meta['items']
        g.append({'@type': 'HowTo', '@id': url + '#howto', 'name': 'How to run the Urban Collaboration Checklist',
                  'description': 'Ten stages for deciding whether and how organisations should collaborate on an urban problem.',
                  'step': [{'@type': 'HowToStep', 'position': i + 1, 'name': s, 'url': '%s#%s' % (url, meta['h2'][i][0]) if i < len(meta['h2']) else url}
                           for i, s in enumerate(steps)]})
        g[2]['associatedMedia'] = {'@type': 'MediaObject', 'name': 'Urban Collaboration Checklist (PDF)',
                                   'encodingFormat': 'application/pdf', 'contentUrl': SITE + '/' + pdf_href}
    return {'@context': 'https://schema.org', '@graph': g}


ARTICLE_CSS = r"""
.art-wrap{display:grid;grid-template-columns:minmax(0,1fr) 290px;gap:clamp(28px,4vw,64px);align-items:start}
.art-main{min-width:0}.art-side{position:sticky;top:104px;align-self:start;display:grid;gap:22px}
@media(max-width:1000px){.art-wrap{grid-template-columns:1fr}.art-side{position:static;order:-1}.art-side .dl{display:none}}
.art-answer{border:1px solid var(--line,#DCEAE6);border-left:5px solid var(--teal,#1F8F7B);background:var(--paper-alt,#E9F5F2);padding:clamp(20px,2.4vw,28px);margin:0 0 26px}
.art-answer h2{margin:0 0 10px;font:800 clamp(19px,2vw,24px)/1.3 var(--display,Archivo,sans-serif);color:var(--ink,#222120)}
.art-answer p{margin:0;font-size:clamp(17px,1.5vw,19px);line-height:1.65;color:var(--ink,#222120)}
.art-take{background:var(--teal-deep,#0E5348);color:#fff;padding:clamp(20px,2.4vw,28px);margin:0 0 30px}
.art-take h2{margin:0 0 12px;font:700 12.5px/1 var(--sans,'Public Sans',sans-serif);letter-spacing:.16em;text-transform:uppercase;color:var(--lime,#CDDE71)}
.art-take ul{margin:0;padding:0;list-style:none;display:grid;gap:10px}
.art-take li{position:relative;padding-left:26px;font-size:16.5px;line-height:1.55;color:#E4EFEB}
.art-take li::before{content:"";position:absolute;left:0;top:.55em;width:12px;height:2px;background:var(--lime,#CDDE71)}
.art-toc{border:1px solid var(--line,#DCEAE6);padding:18px 20px;background:var(--paper,#FBFAF6)}
.art-toc-h{margin:0 0 10px;font:700 12px/1 var(--sans,'Public Sans',sans-serif);letter-spacing:.16em;text-transform:uppercase;color:var(--teal-text,#177A69)}
.art-toc ol{margin:0;padding:0;list-style:none;display:grid;gap:8px;max-height:60vh;overflow:auto}
.art-toc a{font-size:14.5px;line-height:1.4;color:var(--ink-soft,#57564F);text-decoration:none}.art-toc a:hover{color:var(--teal-text,#177A69);text-decoration:underline}
.dl{border:1px solid var(--teal,#1F8F7B);padding:20px;background:var(--paper-alt,#E9F5F2)}
.dl-k{margin:0 0 6px;font:700 12px/1 var(--sans,'Public Sans',sans-serif);letter-spacing:.16em;text-transform:uppercase;color:var(--teal-text,#177A69)}
.dl-t{margin:0 0 8px;font:800 19px/1.25 var(--display,Archivo,sans-serif);color:var(--ink,#222120)}
.dl p{font-size:14.5px;line-height:1.55;color:var(--ink-soft,#57564F)}
.art-main>.dl{display:none;margin:0 0 26px}@media(max-width:1000px){.art-main>.dl{display:block}}
.art-body>.stats-wrap,.art-body>.dg-fig,.art-body>.tbl,.art-body>.faq,.art-body>.srcs{max-width:none}
.art-body h2{scroll-margin-top:104px}.art-body h3{scroll-margin-top:104px}
.art-body ul,.art-body ol{max-width:72ch;padding-left:22px;margin:0 0 18px}.art-body li{margin:0 0 8px;line-height:1.65}
.art-body a{color:var(--teal-text,#177A69)}
.ck{list-style:none;padding-left:0!important}.ck li{position:relative;padding-left:34px}
.ck li::before{content:"";position:absolute;left:0;top:.32em;width:18px;height:18px;border:2px solid var(--teal,#1F8F7B);border-radius:3px}
.tbl{overflow-x:auto;margin:0 0 22px}.tbl table{border-collapse:collapse;width:100%;min-width:520px;font-size:15px}
.tbl th,.tbl td{border:1px solid var(--line,#DCEAE6);padding:10px 12px;text-align:left;vertical-align:top;line-height:1.5}
.tbl th{background:var(--paper-alt,#E9F5F2);color:var(--ink,#222120)}
.stats-wrap{margin:34px 0}.stats-wrap h2{margin:0 0 14px}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:14px}
.stat{border:1px solid var(--line,#DCEAE6);padding:18px;background:var(--paper,#FBFAF6)}
.stat b{display:block;font:800 clamp(24px,2.6vw,32px)/1.1 var(--display,Archivo,sans-serif);color:var(--teal-deep,#0E5348);margin-bottom:8px;overflow-wrap:anywhere}
.stat span{display:block;font-size:15px;line-height:1.5;color:var(--ink-soft,#57564F)}
.stat small{display:block;margin-top:10px;font-size:12.5px;line-height:1.4;color:var(--ink-soft,#57564F)}
.dg-fig{margin:30px 0;padding:clamp(18px,2.4vw,28px);background:var(--teal-deep,#0E5348);color:#fff}
.dg-t{font:800 clamp(18px,2vw,23px)/1.25 var(--display,Archivo,sans-serif);margin:0 0 16px}
.dg{list-style:none;margin:0!important;padding:0!important;max-width:none!important}
.dg li{margin:0}.dg b{font:800 15px/1 var(--display,Archivo,sans-serif);color:var(--lime,#CDDE71)}
.dg span{color:#fff;font-size:15px;line-height:1.35;font-weight:600}
.dg-steps,.dg-pillars{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:10px}
.dg-steps li,.dg-pillars li{display:flex;gap:10px;align-items:flex-start;border:1px solid rgba(255,255,255,.22);padding:12px 14px;background:rgba(255,255,255,.06)}
.dg-back{border-style:dashed!important;justify-content:center;align-items:center!important}.dg-back span{color:var(--lime,#CDDE71)}
.dg-roof{border:1px solid rgba(255,255,255,.3);padding:12px 16px;text-align:center;font:800 17px/1.2 var(--display,Archivo,sans-serif);margin-bottom:10px;background:rgba(255,255,255,.1)}
.dg-pillars li{flex-direction:column;min-height:110px}
.dg-stairs{display:grid;grid-template-columns:repeat(var(--n),1fr);gap:8px;align-items:end}
.dg-stairs li{display:flex;flex-direction:column;gap:8px;justify-content:flex-end;padding:12px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.22);min-height:calc(72px + var(--i)*34px)}
@media(max-width:700px){.dg-stairs{grid-template-columns:1fr}.dg-stairs li{min-height:0;flex-direction:row;align-items:center}}
.dg-cap{margin:14px 0 0;font-size:13.5px;line-height:1.5;color:#CFE3DC}
.faq{margin:38px 0 0}.faq h3{margin:24px 0 8px;font-size:clamp(18px,1.9vw,21px);color:var(--ink,#222120)}
.srcs{margin:38px 0 0}.src{padding-left:22px;font-size:15px}.src li{margin:0 0 9px}
.art-note{border-left:4px solid var(--line,#DCEAE6);padding:4px 0 4px 18px;margin:26px 0;color:var(--ink-soft,#57564F);font-size:15px;max-width:72ch}
.art-note-k{font:700 12px/1 var(--sans,'Public Sans',sans-serif);letter-spacing:.16em;text-transform:uppercase;color:var(--teal-text,#177A69)}
.authorcard{border:1px solid var(--line,#DCEAE6);padding:20px 22px;margin:34px 0 0;background:var(--paper-alt,#E9F5F2);max-width:72ch}
.authorcard p{margin:0 0 8px;font-size:15px;line-height:1.6;color:var(--ink-soft,#57564F)}.authorcard-k{font:700 12px/1 var(--sans,'Public Sans',sans-serif)!important;letter-spacing:.16em;text-transform:uppercase;color:var(--teal-text,#177A69)!important}
.authorcard-n{font:800 18px/1.3 var(--display,Archivo,sans-serif)!important;color:var(--ink,#222120)!important}.authorcard-n a{color:inherit;text-decoration:none}
.art-related{margin:34px 0 0;padding-top:26px;border-top:1px solid var(--line,#DCEAE6)}.art-related h2{margin:0 0 12px;font-size:clamp(20px,2.2vw,26px)}
.art-related ul{list-style:none;padding:0;margin:0 0 14px;display:grid;gap:12px}.art-related li a{font-weight:700;color:var(--teal-text,#177A69);text-decoration:none}.art-related li a:hover{text-decoration:underline}
.art-related li span{display:block;font-size:14.5px;color:var(--ink-soft,#57564F)}
"""


# The shared page CSS styles bare h2/li/figcaption (dark ink text, dashed li borders, big
# mobile h2 sizes). On the dark panels that made text unreadable, so these win explicitly.
ARTICLE_CSS += r"""
.art .art-take h2{font:700 12.5px/1 var(--sans,'Public Sans',sans-serif)!important;letter-spacing:.16em!important;text-transform:uppercase!important;color:var(--lime,#CDDE71)!important;margin:0 0 14px!important}
.art .art-take li{color:#E4EFEB!important;border:0!important;padding-top:0!important;padding-bottom:0!important;margin:0!important;font-size:16.5px!important;line-height:1.55!important}
.art .dg-fig .dg-t{color:#fff!important;font:800 clamp(18px,2vw,23px)/1.25 var(--display,Archivo,sans-serif)!important}
.art .dg-fig .dg-cap{color:#CFE3DC!important}
.art .dg li{border-top-width:1px!important}.art .dg span{color:#fff!important}.art .dg b{color:var(--lime,#CDDE71)!important}
.art .dg-back span{color:var(--lime,#CDDE71)!important}
.art .art-answer h2{font-size:clamp(19px,2vw,24px)!important}
.art .stat b{color:var(--teal-deep,#0E5348)!important}.art .stat span,.art .stat small{color:var(--ink-soft,#57564F)!important}
.art .art-related li,.art .authorcard p{border-top:0}
.art-share{display:flex;flex-wrap:wrap;align-items:center;gap:.55rem;margin:0 0 22px}
.art-share .lbl{font:700 11px/1 var(--sans,'Public Sans',sans-serif);letter-spacing:.14em;text-transform:uppercase;color:var(--ink-soft,#57564F);margin-right:.25rem}
.art .art-share a,.art .art-share button{display:inline-flex;align-items:center;justify-content:center;gap:.45rem;height:40px;padding:0 .95rem;border-radius:999px;border:1px solid var(--line,#DCEAE6);background:var(--paper,#FBFAF6);color:var(--ink,#222120)!important;font:600 13px/1 var(--sans,'Public Sans',sans-serif);text-decoration:none!important;cursor:pointer;transition:background .18s,border-color .18s,color .18s,transform .18s}
.art .art-share a:hover,.art .art-share button:hover,.art .art-share a:focus-visible,.art .art-share button:focus-visible{background:var(--teal-deep,#0E5348);border-color:var(--teal-deep,#0E5348);color:#fff!important;transform:translateY(-1px)}
.art-share svg{width:15px;height:15px;flex:none}.art-share button[hidden]{display:none}
.art-share.bottom{margin:26px 0 0;padding-top:20px;border-top:1px solid var(--line,#DCEAE6)}
@media(max-width:520px){.art .art-share a,.art .art-share button{height:38px;padding:0 .8rem}}
"""

HEAD_STRIP = [
    r'<title[^>]*>.*?</title>\s*', r'<meta\s+name="description"[^>]*>\s*', r'<meta\s+name="author"[^>]*>\s*',
    r'<link\s+rel="canonical"[^>]*>\s*', r'<link\s+rel="alternate"\s+hreflang[^>]*>\s*',
    r'<meta\s+property="(?:og|article):[^"]*"[^>]*>\s*', r'<meta\s+name="twitter:[^"]*"[^>]*>\s*',
    r'<script\s+type="application/ld\+json"[^>]*>.*?</script>\s*',
]


def head_block(title, desc, url, graph, og_type='article', extra_meta='', css=ARTICLE_CSS):
    q = lambda s: H.escape(s, quote=True)
    t = [
        '<title>%s</title>' % H.escape(title, quote=False),
        '<meta name="description" content="%s">' % q(desc),
        '<meta name="author" content="%s">' % q(AUTHOR['name']),
        '<link rel="canonical" href="%s">' % url,
        '<link rel="alternate" hreflang="en-in" href="%s">' % url,
        '<link rel="alternate" hreflang="x-default" href="%s">' % url,
        '<meta property="og:type" content="%s">' % og_type,
        '<meta property="og:site_name" content="U-CAN — Urban Collective Action Network">',
        '<meta property="og:locale" content="en_IN">',
        '<meta property="og:title" content="%s">' % q(title), '<meta property="og:description" content="%s">' % q(desc),
        '<meta property="og:url" content="%s">' % url, '<meta property="og:image" content="%s">' % OG_IMG,
        '<meta property="og:image:width" content="1200">', '<meta property="og:image:height" content="630">',
        '<meta property="og:image:alt" content="%s">' % q(title),
        extra_meta,
        '<meta name="twitter:card" content="summary_large_image">', '<meta name="twitter:title" content="%s">' % q(title),
        '<meta name="twitter:description" content="%s">' % q(desc), '<meta name="twitter:image" content="%s">' % OG_IMG,
        '<script type="application/ld+json" data-ucan="schema">%s</script>' % json.dumps(graph, ensure_ascii=False, separators=(',', ':')).replace('</', '<\\/'),
        '<style data-ucan="article">%s</style>' % re.sub(r'\s*\n\s*', '', css.strip()),
    ]
    return '\n'.join(x for x in t if x) + '\n'


def compose(shell, head, main, active=True):
    s = shell
    for pat in HEAD_STRIP:
        s = re.sub(pat, '', s, flags=re.S | re.I)
    s = re.sub(r'(class="ucnav-t)\s+on"', r'\1"', s)          # clear the shell's active nav item
    s = re.sub(r'(<a class="ucnav-t[^"]*"[^>]*?)\s+aria-current="page"', r'\1', s)
    s = add_menu.patch(s, active=active)
    i = s.index('</head>')
    s = s[:i] + head + s[i:]
    m = re.search(r'<main\b.*?</main>', s, re.S | re.I)
    s = s[:m.start()] + main + s[m.end():]
    return s


def write(path, text):
    add_menu.write(path, text)


# ---------------------------------------------------------------- hub page

def hub_page(shell, metas):
    url = SITE + '/urban-perspectives'
    title = 'Urban Perspectives: Urban Governance in India | U-CAN'
    desc = ("Urban Perspectives: U-CAN's knowledge series on how India's cities are governed, from sustainable "
            "development and collaboration to municipal reform and inclusion.")
    cards = ''.join(
        '<li class="up-card"><p class="up-k">%s</p><h2><a href="%s">%s</a></h2><p>%s</p><p class="up-m">%d min read · <a href="%s">Read the guide →</a></p></li>'
        % (esc(a['intent']), a['slug'], esc(a['keyword']), esc(RELATED_BLURB[a['n']]), metas[a['n']]['read'], a['slug']) for a in ARTICLES)
    hero = ('<section class="hero" aria-labelledby="pt"><div class="hero-in"><nav class="crumb" aria-label="Breadcrumb"><a href="/">Home</a>'
            '<span aria-hidden="true">/</span><span>Urban Perspectives</span></nav><p class="hero-tag">Knowledge series</p>'
            '<h1 id="pt">Urban Perspectives</h1><p class="hero-lede">Practical thinking on how India\'s cities are governed: five guides '
            'on sustainable development, collaboration, citizen-centric design, municipal reform and inclusion.</p></div></section>')
    main = ('<main id="main">%s<section class="sec"><div class="wrap"><ul class="up-grid">%s</ul></div></section></main>' % (hero, cards))
    css = ARTICLE_CSS + ('.up-grid{list-style:none;margin:0;padding:0;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px}'
                         '.up-card{border:1px solid var(--line,#DCEAE6);padding:24px;background:var(--paper,#FBFAF6)}'
                         '.up-card h2{margin:6px 0 10px;font:800 clamp(20px,2vw,24px)/1.25 var(--display,Archivo,sans-serif)}.up-card h2 a{color:var(--ink,#222120);text-decoration:none}'
                         '.up-k{margin:0;font:700 12px/1 var(--sans,"Public Sans",sans-serif);letter-spacing:.16em;text-transform:uppercase;color:var(--teal-text,#177A69)}'
                         '.up-card p{color:var(--ink-soft,#57564F);font-size:15.5px;line-height:1.6}.up-m{margin-top:14px!important;font-size:14px!important}.up-m a{color:var(--teal-text,#177A69);font-weight:700}')
    g = {'@context': 'https://schema.org', '@graph': [
        org_node(),
        {'@type': 'CollectionPage', '@id': url + '#webpage', 'url': url, 'name': title, 'description': desc, 'inLanguage': 'en-IN',
         'isPartOf': {'@id': SITE + '/#org'}, 'breadcrumb': {'@id': url + '#breadcrumb'}},
        {'@type': 'ItemList', 'itemListElement': [{'@type': 'ListItem', 'position': a['n'], 'url': '%s/%s' % (SITE, a['slug']), 'name': a['keyword']} for a in ARTICLES]},
        {'@type': 'BreadcrumbList', '@id': url + '#breadcrumb', 'itemListElement': [
            {'@type': 'ListItem', 'position': 1, 'name': 'Home', 'item': SITE + '/'},
            {'@type': 'ListItem', 'position': 2, 'name': 'Urban Perspectives', 'item': url}]}]}
    return compose(shell, head_block(title, desc, url, g, og_type='website', css=css), main)


# ---------------------------------------------------------------- run

def main():
    pdf_href = 'assets/docs/urban-collaboration-checklist.pdf'
    if '--pdf-name' in sys.argv:
        pdf_href = 'assets/docs/' + sys.argv[sys.argv.index('--pdf-name') + 1]
    shell = io.open(SHELL, encoding='utf-8', newline='').read()
    metas = {}
    for cfg in ARTICLES:
        title, blocks = normalise(Doc(os.path.join(SRC, cfg['doc'])).blocks())
        body, faq, sources, notes = split_zones(blocks)
        if 'lede' in cfg:
            lede = cfg['lede']
        else:  # the doc's own standfirst is its first paragraph
            k = next(i for i, b in enumerate(body) if b['t'] == 'p')
            lede = body[k]['text']
            body = body[:k] + body[k + 1:]
        aw = words(cfg['answer'])
        assert 40 <= aw <= 60, '%s: direct answer is %d words' % (cfg['slug'], aw)
        for what, val, lim in (('title', cfg['title'], 65), ('description', cfg['description'], 160)):
            if len(val) > lim:
                print('  WARN %s: %s is %d chars (>%d)' % (cfg['slug'], what, len(val), lim))
        main_h, meta = main_html(cfg, title, lede, body, faq, sources, notes, blocks, pdf_href)
        metas[cfg['n']] = meta
        url = '%s/%s' % (SITE, cfg['slug'])
        extra = ('<meta property="article:published_time" content="%s">\n<meta property="article:modified_time" content="%s">\n'
                 '<meta property="article:section" content="Urban governance">') % (PUBLISHED, MODIFIED)
        head = head_block(cfg['title'], cfg['description'], url, ld_graph(cfg, title, meta, faq, pdf_href), extra_meta=extra)
        write(os.path.join(OUT, cfg['slug'] + '.html'), compose(shell, head, main_h))
        print('%-40s h1=%d words=%d read=%dm faq=%d src=%d diagram=%d' % (
            cfg['slug'], 1, meta['words'], meta['read'], len(faq), sum(len(b['items']) for b in sources if 'items' in b), len(meta['items'])))
    write(os.path.join(OUT, 'urban-perspectives.html'), hub_page(shell, metas))
    print('hub written')


if __name__ == '__main__':
    main()
