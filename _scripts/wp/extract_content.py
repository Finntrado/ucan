# -*- coding: utf-8 -*-
"""Phase 7 (part 1): extracts every piece of real content out of the
finished standalone/*.html pages into plain JSON, one file per CPT, under
_scripts/wp/data/. The PHP importer (import.php, run via WP-CLI on a real
WordPress install - none exists in this sandbox) reads these and creates
the actual posts/meta/taxonomy terms/media.

Same discipline as every extraction in this repo: parse the already-
verified finished HTML by structure, never invent or paraphrase content.
Byte offsets/regexes are matched against markup this session already
built the corresponding CPT/template against (phases 3-6), not guessed.

Two fields need a former-HTML -> plain-text decision:
  - bio/body "content" fields keep inner HTML (paragraphs, links, bold)
    verbatim, since post_content is rendered through the same wpautop-safe
    path as any other WP post - only the outer wrapper is stripped.
  - single-line fields (names, dates, one-liners) are stripped to plain
    text (tags removed, entities decoded) since they map to plain-string
    post meta.
"""
import glob
import html
import io
import json
import os
import re
from datetime import datetime

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', '..')
STANDALONE = os.path.join(ROOT, 'standalone')
DATA = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'data')
os.makedirs(DATA, exist_ok=True)


def read(fname):
    return io.open(os.path.join(STANDALONE, fname), encoding='utf-8').read()


def strip_tags(s):
    return html.unescape(re.sub(r'<[^>]+>', '', s)).strip()


def between(s, start_marker, end_marker, start_from=0):
    i = s.index(start_marker, start_from)
    j = s.index(end_marker, i)
    return s[i:j], i, j


def inner(s, start_marker, end_marker, start_from=0):
    """Like between(), but drops the start_marker itself - use this
    whenever the matching single-*.php template already renders that
    wrapper element and calls the_content() only for what's inside it
    (checked against every template this feeds, phases 3-6)."""
    block, i, j = between(s, start_marker, end_marker, start_from)
    return block[len(start_marker):].strip()


def write_json(name, data):
    path = os.path.join(DATA, name)
    io.open(path, 'w', encoding='utf-8').write(json.dumps(data, indent=1, ensure_ascii=False))
    print('wrote %s: %d items' % (name, len(data)))


# ============================================================ members ===
GROUP_ID_TO_SLUG = {
    'founding': 'founding-circle',
    'steering': 'steering-committee',
    'stewardship': 'stewardship-team',
    'team': 'our-team',
}


def extract_members():
    people = read('our-people.html')
    # roster: slug -> set of group slugs, in our-people.html's own order
    roster = {}
    order = []
    for grp_id, grp_slug in GROUP_ID_TO_SLUG.items():
        block, _, _ = between(people, '<div class="grp" id="%s">' % grp_id, '\n    <div class="grp"' if grp_id != 'team' else '<!-- CTA', 0)
        for m in re.finditer(r'href="profile-([a-z0-9-]+)"', block):
            slug = m.group(1)
            if slug not in roster:
                roster[slug] = []
                order.append(slug)
            if grp_slug not in roster[slug]:
                roster[slug].append(grp_slug)

    members = []
    for slug in order:
        fname = 'profile-%s.html' % slug
        path = os.path.join(STANDALONE, fname)
        if not os.path.exists(path):
            print('  ! missing profile file for', slug)
            continue
        s = read(fname)
        name_m = re.search(r'<h1 id="h1">([^<]+)</h1>', s)
        name = html.unescape(name_m.group(1)) if name_m else slug

        photo_m = re.search(r'<img src="(assets/img/[^"]+)"[^>]*width="220"', s)
        photo = photo_m.group(1) if photo_m else None

        bio_block, _, _ = between(s, '<div class="bio-body rv in">', '</div>')
        bio_paras = re.findall(r'<p>(.*?)</p>', bio_block, re.S)
        bio_html = '\n'.join('<p>%s</p>' % p.strip() for p in bio_paras)

        aside, _, _ = between(s, '<aside class="bio-aside', '</aside>')
        role_m = re.search(r'<dt>Role</dt><dd>([^<]*)</dd>', aside)
        org_m = re.search(r'<dt>Organisation</dt><dd>([^<]*)</dd>', aside)
        role = html.unescape(role_m.group(1)) if role_m else ''
        organisation = html.unescape(org_m.group(1)) if org_m else ''

        li_m = re.search(r'<dt>Connect</dt><dd><a href="(https://www\.linkedin\.com/in/[^"]+)"', aside)
        linkedin = li_m.group(1) if li_m else ''

        is_team = 'our-team' in roster[slug]
        job_title = role if is_team else ''
        if not is_team:
            organisation = role  # same value either way, kept explicit

        members.append({
            'slug': slug,
            'name': name,
            'groups': roster[slug],
            'photo': photo,
            'bio_html': bio_html,
            'job_title': job_title,
            'organisation': organisation,
            'linkedin': linkedin,
        })
    write_json('members.json', members)


# ============================================================ fellows ===
def extract_fellows():
    hub = read('meet-the-fellows.html')
    fcards = re.findall(
        r'<a class="flink" href="profile-fellow-([a-z0-9-]+)">.*?<img src="(assets/img/[^"]+)"[^>]*>.*?'
        r'<span class="fn">([^<]+)</span>\s*<span class="fo">([^<]*)</span>.*?</a>'
        r'(?:\s*<div class="soc">(.*?)</div>)?',
        hub, re.S,
    )
    fellows = []
    for i, (slug, photo, name, org, soc) in enumerate(fcards, 1):
        social_urls = re.findall(r'href="(https://[^"]+)"', soc or '')
        fname = 'profile-fellow-%s.html' % slug
        path = os.path.join(STANDALONE, fname)
        bio_html = ''
        joining_location = ''
        education = ''
        cohort_label = 'U-CAN Fellow · 2024-25'
        if os.path.exists(path):
            s = read(fname)
            body_block, _, _ = between(s, '<div class="body rv">', '</div>\n      <aside class="glance')
            paras = re.findall(r'<p>(.*?)</p>', body_block, re.S)
            bio_html = '\n'.join('<p>%s</p>' % p.strip() for p in paras)
            glance, _, _ = between(s, '<aside class="glance', '</aside>')
            jl_m = re.search(r'<dt>Joining location</dt><dd>([^<]*)</dd>', glance)
            ed_m = re.search(r'<dt>Education</dt><dd>([^<]*)</dd>', glance)
            cl_m = re.search(r'<span class="pbadge">([^<]*)</span>', s)
            if jl_m:
                joining_location = html.unescape(jl_m.group(1))
            if ed_m:
                education = html.unescape(ed_m.group(1))
            if cl_m:
                cohort_label = html.unescape(cl_m.group(1))
        fellows.append({
            'slug': slug,
            'name': html.unescape(name),
            'menu_order': i,
            'photo': photo,
            'host_organisation': html.unescape(org),
            'bio_html': bio_html,
            'joining_location': joining_location,
            'education': education,
            'cohort_label': cohort_label,
            'social_urls': social_urls,
        })
    write_json('fellows.json', fellows)


# ========================================================== etn events ==
MONTHS = ('January', 'February', 'March', 'April', 'May', 'June', 'July',
          'August', 'September', 'October', 'November', 'December')


def parse_date(s):
    """'December 20, 2024' -> '2024-12-20'. Returns None if unparseable."""
    m = re.search(r'(%s)\s+(\d{1,2}),\s*(\d{4})' % '|'.join(MONTHS), s)
    if not m:
        return None
    dt = datetime.strptime('%s %s %s' % m.groups(), '%B %d %Y')
    return dt.strftime('%Y-%m-%d')


def extract_ld_sessions():
    events = []
    for fname in sorted(glob.glob(os.path.join(STANDALONE, 'ld-*.html'))):
        base = os.path.basename(fname)
        if base == 'ld-calendar.html':
            continue
        slug = base[:-5]
        s = read(base)
        title = html.unescape(re.search(r'<h1 id="pt">([^<]+)</h1>', s).group(1))
        chips = re.findall(r'<div class="hero-chips">\s*<span>([^<]*)</span>(?:<span>([^<]*)</span>)?', s)
        date_str, time_range = (chips[0] if chips else ('', ''))
        post_date = parse_date(date_str) or '2024-01-01'

        lead_m = re.search(r'<h2>Session lead <span[^>]*>([^<]+)</span></h2>\s*((?:<p>.*?</p>\s*)+)', s, re.S)
        lead_name = html.unescape(lead_m.group(1)).strip() if lead_m else ''
        lead_bio = ' '.join(strip_tags(p) for p in re.findall(r'<p>(.*?)</p>', lead_m.group(2), re.S)) if lead_m else ''

        # Not every session has a separate "About the session" block - two
        # of the 12 (checked: storytelling-masterclass-4,
        # urban-planning-in-india) only have the session-lead bio, nothing
        # more. content_html stays empty for those; the lead bio alone is
        # the session's substance, already carried in lead_bio.
        if '<h2>About the session</h2>' in s:
            content_html = inner(s, '<div class="grp"><h2>About the session</h2>', '</div>\n      </div>\n      <aside')
        else:
            content_html = ''

        facts, _, _ = between(s, '<aside class="sfacts', '</aside>')
        type_m = re.search(r'<dt>Type</dt><dd>([^<]*)</dd>', facts)
        tz_m = re.search(r'\(([^)]+)\)', facts)
        session_type = html.unescape(type_m.group(1)) if type_m else ''
        timezone = tz_m.group(1) if tz_m else 'Asia/Calcutta'

        events.append({
            'slug': slug, 'kind': 'ld_session', 'title': title,
            'post_date': post_date, 'time_range': time_range or '',
            'lead_name': lead_name, 'lead_bio': lead_bio,
            'session_type': session_type, 'format': '', 'timezone': timezone,
            'content_html': content_html,
        })
    return events


def extract_policy_webinars():
    files = [
        'webinar-beyond-silos-the-case-for-collective-action-copy.html',
        'webinar-beyond-silos-the-case-for-collective-action.html',
        'webinar-policy-webinar-affordable-urban-housing.html',
        'webinar-policy-webinar-beyond-town-halls-2.html',
        'webinar-policy-webinar-beyond-town-halls.html',
        'webinar-policy-webinar-community-engagement.html',
        'webinar-reimagining-problem-solving-in-urban-governance-throug.html',
        'webinar-systems-governance.html',
        'webinar-understanding-urban-climate-action-in-india.html',
    ]
    events = []
    for base in files:
        slug = base[:-5]
        s = read(base)
        title = html.unescape(re.search(r'<h1 id="pt">([^<]+)</h1>', s).group(1))
        chips = re.findall(r'<span>([^<]*)</span>', re.search(r'<div class="hero-chips">(.*?)</div>', s, re.S).group(1))
        date_str = chips[0] if len(chips) > 0 else ''
        time_range = chips[1] if len(chips) > 1 else ''
        post_date = parse_date(date_str) or '2024-01-01'

        content_html = inner(s, '<div class="post-body">', '</div>\n      </div>\n      <aside')

        facts, _, _ = between(s, '<aside class="sfacts', '</aside>')
        fmt_m = re.search(r'<dt>Format</dt><dd>([^<]*)</dd>', facts)
        tz_m = re.search(r'\(([^)]+)\)', facts)
        events.append({
            'slug': slug, 'kind': 'policy_webinar', 'title': title,
            'post_date': post_date, 'time_range': time_range,
            'lead_name': '', 'lead_bio': '',
            'session_type': '', 'format': html.unescape(fmt_m.group(1)) if fmt_m else '',
            'timezone': tz_m.group(1) if tz_m else 'Asia/Calcutta',
            'content_html': content_html,
        })
    return events


def extract_mixer_note():
    s = read('event-hosted-by-artha-global.html')
    title = html.unescape(re.search(r'<h1 id="pt">([^<]+)</h1>', s).group(1))
    time_range = re.search(r'<span>([^<]*)</span>', s).group(1)
    content_html = inner(s, '<div class="post-body">', '</div>\n      </div>\n      <aside')
    return [{
        'slug': 'event-hosted-by-artha-global', 'kind': 'mixer_note', 'title': title,
        'post_date': '2026-04-24', 'time_range': time_range,
        'lead_name': '', 'lead_bio': '', 'session_type': '', 'format': '',
        'timezone': 'Asia/Calcutta', 'content_html': content_html,
    }]


def extract_etn_events():
    events = extract_ld_sessions() + extract_policy_webinars() + extract_mixer_note()
    write_json('etn_events.json', events)


# =============================================================== blogs ==
def extract_blogs():
    hub = read('fellow-blogs.html')
    cards = re.findall(
        r'<a class="bcard[^"]*" href="(blog-[a-z0-9-]+)"\s*\n?\s*data-author="([a-z0-9-]+)" data-date="([\d-]+)">'
        r'\s*<span class="bi"><img src="([^"]*)"[^>]*>.*?</span>\s*<span class="bb">\s*<h3>(.*?)</h3>'
        r'\s*<span class="bx">(.*?)</span>\s*<span class="btags">(.*?)<span class="bm">',
        hub, re.S,
    )
    blogs = []
    seen = set()
    for slug, author, date_str, image, title, excerpt, tags_block in cards:
        if slug in seen:
            continue
        seen.add(slug)
        # tags_block is captured non-greedily up to <span class="bm">, not
        # .btags' own closing tag - each individual .tag pill has its own
        # nested </span>, so a naive (.*?)</span> capture (tried first)
        # stopped at the FIRST tag's closing span and silently dropped
        # every tag on every one of the 58 posts. .bm doesn't contain any
        # data-tag= itself, so over-capturing into it is harmless here.
        tags = re.findall(r'data-tag="blog-tag-([a-z0-9-]+)"[^>]*>([^<]+)<', tags_block)
        fname = slug + '.html'
        path = os.path.join(STANDALONE, fname)
        content_html = ''
        if os.path.exists(path):
            s = read(fname)
            content_html = inner(s, '<article class="post-body rv">', '</article>')
        blogs.append({
            'slug': slug,
            'title': html.unescape(title),
            'excerpt': html.unescape(excerpt),
            'fellow_slug': author,
            'post_date': date_str,
            'image': image,
            'tags': [{'slug': ts, 'name': html.unescape(tn)} for ts, tn in tags],
            'content_html': content_html,
        })
    write_json('blogs.json', blogs)


# ============================================================== mixers ==
def extract_mixers():
    s = read('city-mixers.html')
    block, _, _ = between(s, '<div class="mxlist">', '\n    </div>\n  </div>\n</section>')
    articles = re.findall(r'<article class="mx rv in">(.*?)</article>', block, re.S)
    mixers = []
    n = len(articles)
    for art in articles:
        img_m = re.search(r'data-full="([^"]*)"', art) or re.search(r'<img src="([^"]*)"', art)
        meta_m = re.search(r'<span class="mx-n">(\d+)</span><span>([^<]*) · ([^<]*)</span>', art)
        title_m = re.search(r'<h3>(.*?)</h3>', art)
        body_m = re.search(r'<p>(.*?)</p>\s*</div>\s*$', art, re.S)
        num, date_str, city = meta_m.groups()
        title = html.unescape(title_m.group(1))
        host = title.replace('Hosted by ', '')
        mixers.append({
            'slug': 'mixer-%s' % num,
            'title': title,
            'menu_order': int(num),
            'post_date': parse_date(date_str) or '2025-01-01',
            'city': city,
            'host_organisation': host,
            'image': img_m.group(1) if img_m else None,
            'content_html': '<p>%s</p>' % body_m.group(1).strip() if body_m else '',
        })
    mixers.sort(key=lambda m: m['menu_order'])
    write_json('mixers.json', mixers)


# ==================================================== webinar recaps ====
def extract_webinar_recaps():
    files = [
        'webinar-recap-affordable-urban-housing.html',
        'webinar-recap-beyond-town-halls-exploring-new-avenues-for-ci.html',
        'webinar-recap-digital-citizen-engagement-opportunities-and-c.html',
        'webinar-recap-urban-systems-and-governance.html',
    ]
    recaps = []
    for base in files:
        slug = base[:-5]
        s = read(base)
        title = html.unescape(re.search(r'<h1 id="pt">([^<]+)</h1>', s).group(1))
        content_html = inner(s, '<div class="post-body rv" style="max-width:80ch">', '</div>\n  </div>\n</section>')
        recaps.append({'slug': slug, 'title': title, 'content_html': content_html})
    write_json('webinar_recaps.json', recaps)


# ====================================================== newsletters =====
def extract_newsletters():
    files = sorted(glob.glob(os.path.join(STANDALONE, 'newsletter-*.html')))
    issues = []
    for fname in files:
        base = os.path.basename(fname)
        slug = base[:-5]
        s = read(base)
        designed = 'class="masthead"' in s
        title = html.unescape(re.search(r'<h1 id="(?:mast-h|pt)"[^>]*>(?:<span>)?(.*?)(?:</span>)?</h1>', s).group(1))
        title = strip_tags(title)

        if designed:
            edition_m = re.search(r'<h1[^>]*class="mast-title"><span>([^<]*)</span></h1>', s)
            sub_m = re.search(r'<p class="mast-sub">(.*?)</p>', s, re.S)
            date_m = re.search(r'<p class="mast-date">([^<]*)</p>', s)
            edition_name = html.unescape(edition_m.group(1)) if edition_m else 'U-CAN Newsletter'
            masthead_sub = strip_tags(sub_m.group(1)) if sub_m else ''
            date_str = date_m.group(1) if date_m else ''
            # Unlike the other content types, single-ucan_newsletter.php
            # calls the_content() with no wrapper of its own - it expects
            # the whole TOC-through-sections block verbatim, so the TOC
            # nav is kept here, not stripped.
            body, _, _ = between(s, '<nav class="toc"', '<section class="sec" id="download">')
            content_html = body.strip()
            pdf_m = re.search(r'href="newsletters/([a-z0-9-]+\.pdf)"', s)
            pdf_path = 'newsletters/%s' % pdf_m.group(1) if pdf_m else ''
            excerpt = ''
        else:
            edition_name = 'U-CAN Newsletter'
            masthead_sub = ''
            m = re.search(r'in ([A-Za-z]+ \d{4})\.</p>', s)
            date_str = m.group(1) if m else ''
            content_html = ''
            pdf_path = ''
            excerpt = ''

        cover_m = re.search(r'<img src="(assets/img/cover-of[^"]*)"', s)
        month_year_dt = None
        if date_str:
            try:
                month_year_dt = datetime.strptime(date_str.strip(), '%B %Y')
            except ValueError:
                pass
        post_date = month_year_dt.strftime('%Y-%m-01') if month_year_dt else '2024-01-01'

        issues.append({
            'slug': slug,
            'title': title,
            'kind': 'designed' if designed else 'cover_only',
            'edition_name': edition_name,
            'masthead_sub': masthead_sub,
            'pdf_path': pdf_path,
            'cover_image': cover_m.group(1) if cover_m else None,
            'post_date': post_date,
            'content_html': content_html,
        })
    issues.sort(key=lambda i: i['post_date'])
    write_json('newsletters.json', issues)


if __name__ == '__main__':
    extract_members()
    extract_fellows()
    extract_etn_events()
    extract_blogs()
    extract_mixers()
    extract_webinar_recaps()
    extract_newsletters()
