# -*- coding: utf-8 -*-
"""Builds wp-theme/ucan/redirects-extra.php: the 301 / 410 rules for old
urban.org.in URLs that the canonical-URL map (redirects.php) does not cover.

Source of the inventory: the Internet Archive's record of the old site (402
distinct public paths, 259 of which had no redirect and no page). Decisions:

  301  the content moved or has a genuine successor -> that page
  410  Gone: junk that must be dropped from search fast (lorem-ipsum test pages,
       expired job posts, plugin demo pages, author archives, hacked-in spam,
       removed uploads). 410 (not 404) tells crawlers it is permanent.

Rules: EXACT first, then pagination is stripped (…/page/N), then PATTERNS in
order. Every 301 target is validated to exist (a page, or a local asset), so
there are no redirects to 404s and no redirect chains. Re-run after adding
pages:  python _scripts/seo/build_redirects.py
"""
import io
import json
import os
import re
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.normpath(os.path.join(HERE, '..', '..'))
SA = os.path.join(ROOT, 'standalone')
OUT = os.path.join(ROOT, 'wp-theme', 'ucan', 'redirects-extra.php')
PAGES = {f[:-5] for f in os.listdir(SA) if f.endswith('.html')}
THEME_URL = '/wp-content/themes/ucan/'
GONE = None  # marker

E = {}  # exact: old key -> new slug ('' = home) | GONE


def to(target, *keys):
    for k in keys:
        E[k] = target


# ---- moved: root-level pages ----------------------------------------------------
to('about', 'about-us-2')
to('fellow-blogs', 'blogs-by-fellows')
to('meet-the-fellows', 'meet-the-fellows-2', 'shashi-meet-the-fellows')
to('ld-calendar', 'fellowship-ld-2', 'category/past-sessions', 'category/upcoming-sessions')
to('annual-forum-2025', 'the-u-can-annual-forum-2025-2', 'the-u-can-summit', 'category/u-can-summit')
to('urban-reforms-collective', 'urban-reforms-collective-championing-a-collective-reform-agenda-for-indias-cities')
to('rfc', 'from-parellel-to-together-building-a-collaboration-practice-for-indias-urban-ecosystem')
to('fellowship', 'u-can-fellowship-report-2025', 'u-can-fellowship-2024-2025', 'u-can-fellowship-2024-2025-first-draft',
   'citizen-engagement-fellowship', 'citizen-engagement-fellowship-2', 'civic-leader-fellowship-application',
   'mid-career-professionals-fellowship-application', 'social-entrepreneur-fellowship-application', 'etn/fellowship-start')
to('profile-fellow-sharathappriyaa-venkatesan', 'sharathppriyaa-venkatesan')
to('impact', 'roots-and-horizons')
to('learning-network', 'capacity-building', 'capacity-building-2', 'capacity-building-3', 'community-of-practice',
   'community-of-practice/online-repository', 'peer-learning-sessions')
to('policy-webinars', 'climate-change', 'climate-change-2', 'economic-development', 'knowledge-centre', 'policy-papers',
   'policy-paper/citizen-engagement-catalyst-for-inclusive-urban-governance', 'webinars', 'category/webinars',
   'category/policy-paper', 'etn/community-engagement-praja', 'etn/policy-webinar-climate-action',
   'etn/policy-webinar-mobility', 'etn/policy-roundtable-economic-development')
to('webinar-policy-webinar-community-engagement', 'etn/community-engagement', 'webinars/community-engagement')
to('webinar-recap-digital-citizen-engagement-opportunities-and-c', 'case-study/policy-webinar-digital-citizen-engagement')
to('newsletter', 'media', 'category/newsletter', 'newsletter/u-can-newsletter-november-2023',
   'newsletter/urban-collectiveaction-network-newsletter-december-2023')
to('city-mixers', 'etn', 'etn_category', 'etn-tags', 'event-calendar', 'past-events', 'upcoming-events',
   'category/featured-mixers', 'category/hosted-by-artha-global', 'category/hosted-by-janaagraha', 'category/hosted-by-wri-india')
to('our-people', 'new-team-page')
to('our-members', 'our-partners')
to('', 'social-media-feed')
to('fellow-blogs', 'category/fellow-blogs', 'fellow-blogs/a-city-in-transition-climate-governance-and-the-bengaluru-story',
   'fellow-blogs/participatory-governance-an-exploration')
to('profile-viraj-tyagi', 'member/viraj-tyagi')
# fellows' own category archives -> their profile page (which lists their posts)
for cat, slug in {'aanchal': 'profile-fellow-aanchal-aggarwal', 'aashima': 'profile-fellow-aashima-arora',
                  'anwesha': 'profile-fellow-anwesha-bhattacharya', 'manisha': 'profile-fellow-manisha-bisht',
                  'ramya': 'profile-fellow-ramya-ma', 'sharathppriyaa': 'profile-fellow-sharathappriyaa-venkatesan',
                  'shreya': 'profile-fellow-shreya-krishnan', 'shubhi': 'profile-fellow-shubhi-kesarwani'}.items():
    E['category/' + cat] = slug
# old past/upcoming session pages -> the L&D session page when one exists
LD = {'thinking-better-alone-together': 'ld-thinking-better-alone-together',
      'urban-governance-1-structure-status': 'ld-urban-governance-1-structure-status',
      'urban-governance-2-rti-constitution-citizen': 'ld-urban-governance-2-right-to-information-rti-citizens',
      'storytelling-1': 'ld-storytelling-masterclass-1-introduction-to-storytell',
      'storytelling-masterclass-4-using-oral-stories-at-work': 'ld-storytelling-masterclass-4-using-oral-stories-at-wor',
      'urban-planning-in-india-experiences-and-lessons-for-the-future': 'ld-urban-planning-in-india-experiences-and-lessons-for'}
for old, new in LD.items():
    E['past-sessions/' + old] = new
    E['upcoming-sessions/' + old] = new
# old per-author blog URLs (/shreya/<post>/) -> blog-<first 60 chars of the post slug>
OLD_BLOGS = """shreya/cohesion-and-culture-whats-urban-development-got-to-do-with-heritage
shreya/field-notes-in-building-a-community-around-a-shared-neighbourhood-institution
shreya/how-can-planners-empower-communities-to-create-more-inclusive-cities
shreya/introducing-the-pelathope-urban-living-lab
shreya/neighbourhoods-as-social-glue-neglected-and-overlooked-but-essential-for-connecting-planners-with-citizens-lived-experiences
shreya/pilots-as-proof-of-possibility
shreya/reclaiming-the-past-how-heritage-precincts-can-power-the-future-of-cities
shreya/unpacking-the-why-of-pull-my-personal-journey-to-social-entrepreneurship
shreya/urban-experiments-everyday-experts-lessons-from-pelathope
shubhi/water-security-and-the-role-of-a-rainwater-harvesting-calculator""".split('\n')
unmatched_blogs = []
for k in OLD_BLOGS:
    post = k.split('/', 1)[1]
    cand = 'blog-' + post[:60].rstrip('-')
    hit = next((p for p in (cand, cand + '-2') if p in PAGES), None) or next((p for p in PAGES if p.startswith('blog-' + post[:45])), None)
    if hit:
        E[k] = hit
    else:
        unmatched_blogs.append(k)
        E[k] = 'fellow-blogs'
# ---- gone: no successor, must leave search results quickly ----------------------
for k in ['careers', 'partnerships-and-communications-manager', 'coordinator-knowledge-and-fellowship', 'case-studies',
          'category/case-study', 'test', 'test-2', 'test-2-2', 'test-3', 'event-test', 'test-event-list', 'ticket-details',
          'ticket-receipt', 'events-pro', 'events-pro-2', 'events-pro-3', 'events-pro-4', 'events-pro-5', 'etn-speaker-category',
          'etn/event-oct', 'etn/event-circle-of-research', 'etn/event-circle-of-research-2', 'etn/events-circle-of-public-leadership',
          'etn/event1-virtual-steerco-meeting-2', 'feed', 'comments/feed']:
    E[k] = GONE

# ---- patterns (regex on the normalised key, first match wins) --------------------
TAGS = {'air-pollution': 'blog-tag-air-quality', 'air-quality': 'blog-tag-air-quality', 'clean-air-zones': 'blog-tag-air-quality',
        'citizen-engagement': 'blog-tag-citizen-participation', 'climate': 'blog-tag-climate-action',
        'climate-action': 'blog-tag-climate-action', 'climate-change': 'blog-tag-climate-action',
        'data': 'blog-tag-data-and-evidence', 'gender-equality': 'blog-tag-gender-and-safety'}
P = []  # (regex, target slug | None for gone)
for t, slug in TAGS.items():
    P.append(('^tag/%s$' % re.escape(t), slug))
P += [
    (r'^tag/.+$', 'fellow-blogs'),
    (r'^category/.+$', 'fellow-blogs'),
    (r'^etn_category/(policy-webinar.*|policy-round-table|online-event.*)$', 'policy-webinars'),
    (r'^etn_category/past-sessions$', 'ld-calendar'),
    (r'^etn_category/.+$', 'city-mixers'),
    (r'^etn-tags/policy-webinar$', 'policy-webinars'),
    (r'^etn-tags/masterclass-session$', 'ld-calendar'),
    (r'^etn-tags/.+$', 'city-mixers'),
    (r'^etn/.+$', None),
    (r'^featured-mixers/.+$', 'city-mixers'),
    (r'^(past|upcoming)-sessions/.+$', 'ld-calendar'),
    (r'^member/.+$', 'our-people'),
    (r'^timeline_slider_post/.+$', 'about'),
    (r'^policy-paper/.+$', 'policy-webinars'),
    (r'^community-of-practice/.+$', 'learning-network'),
    (r'^author/.+$', None),
    (r'^case-study/.+$', None),
    (r'^\d{4}/\d{2}(/\d{2})?$', None),
    (r'^(elementskit-content|etn-schedule|etn-speaker-category)/.+$', None),
    (r'^wp-content/(uploads|plugins|cache|themes/(?!ucan(/|$)))', None),
    (r'^(wp-sitemap|sitemap_index|post-sitemap|page-sitemap|category-sitemap)\.xml$', '/sitemap.xml'),
    (r'^(:80|woods|luvnest)(/|$)', None),
]

# ---- old uploads that we hosted locally -> the theme copy -----------------------
cache = json.load(io.open(os.path.join(ROOT, '_scripts', 'localize_remaining_cache.json'), encoding='utf-8'))
UP = {}
for old, local in cache.items():
    m = re.match(r'^https?://(?:www\.)?urban\.org\.in/(wp-content/uploads/.+)$', old)
    if m and os.path.exists(os.path.join(SA, local)):
        UP[m.group(1).lower()] = THEME_URL + local
# newsletters: old PDF names -> our copies
NL = {'wp-content/uploads/2024/05/u-can-newsletter-april-2024.pdf': 'newsletters/u-can-newsletter-april-2024.pdf',
      'wp-content/uploads/2024/09/u-can-newsletter_september-2024.pdf': 'newsletters/september-2024.pdf'}
for k, v in NL.items():
    if os.path.exists(os.path.join(SA, v)):
        UP[k] = THEME_URL + v


def check(target):
    if target is None or target == '':
        return
    if target.startswith('/'):
        ok = target == '/sitemap.xml' or os.path.exists(os.path.join(SA, target[len(THEME_URL):] if target.startswith(THEME_URL) else '\0'))
    else:
        ok = target in PAGES
    if not ok:
        raise SystemExit('redirect target does not exist: %r' % target)


def main():
    existing = set(re.findall(r"^\s*'([^']*)' =>", io.open(os.path.join(ROOT, 'wp-theme', 'ucan', 'redirects.php'), encoding='utf-8').read(), re.M))
    for k, v in E.items():
        check(v)
        if k in existing:
            raise SystemExit('rule shadows an existing canonical redirect: %s' % k)
        if k in PAGES:
            raise SystemExit('rule key is a live page: %s' % k)
    for rx, v in P:
        check(v)
        re.compile(rx)
    for v in UP.values():
        check(v)

    def q(s):
        return "'" + s.replace('\\', '\\\\').replace("'", "\\'") + "'"
    out = ['<?php', '// Generated by _scripts/seo/build_redirects.py - do not edit by hand.',
           "defined( 'ABSPATH' ) || exit;", 'return array(', "\t'exact'    => array("]
    for k in sorted(E):
        if E[k] is not GONE:
            out.append('\t\t%s => %s,' % (q(k), q('/' + E[k] if not E[k].startswith('/') else E[k])))
    for k in sorted(UP):
        out.append('\t\t%s => %s,' % (q(k), q(UP[k])))
    out += ["\t),", "\t'gone'     => array("]
    out += ['\t\t%s => true,' % q(k) for k in sorted(E) if E[k] is GONE]
    out += ["\t),", "\t'patterns' => array("]
    for rx, v in P:
        rxp = '#' + rx.replace('#', '\\#') + '#'
        out.append('\t\tarray( %s, %s ),' % (q(rxp), 'null' if v is None else q(v if v.startswith('/') else '/' + v)))
    out += ["\t),", ');', '']
    io.open(OUT, 'w', encoding='utf-8', newline='\n').write('\n'.join(out))
    print('exact 301: %d (+%d old uploads) | gone 410: %d | patterns: %d' % (
        sum(1 for v in E.values() if v is not GONE), len(UP), sum(1 for v in E.values() if v is GONE), len(P)))
    if unmatched_blogs:
        print('old blog URLs with no new post (sent to /fellow-blogs):', unmatched_blogs)


if __name__ == '__main__':
    main()
