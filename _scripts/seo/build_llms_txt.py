# -*- coding: utf-8 -*-
"""Generates wp-theme/ucan/llms.txt (served at https://www.urban.org.in/llms.txt).

llms.txt (llmstxt.org) is a plain-Markdown map of a site written for AI models:
who the organisation is, the pages worth reading, one line on each. Every title
and description here is read from the actual standalone page, and every URL is
the canonical https://www.urban.org.in one, so the file cannot drift from the site.

Honest note: no major engine has confirmed that it ranks or cites pages because
of llms.txt; it is cheap, harmless and read by some AI tools/agents.

Pages that are built but not yet published on urban.org.in (the Urban
Perspectives articles) are left out by default - a link to a page that 404s is
worse than no link. Once they are live:

  python _scripts/seo/build_llms_txt.py --with-perspectives
"""
import io
import os
import re
import sys

from bs4 import BeautifulSoup

HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.normpath(os.path.join(HERE, '..', '..'))
SA = os.path.join(ROOT, 'standalone')
OUT = os.path.join(ROOT, 'wp-theme', 'ucan', 'llms.txt')
ORIGIN = 'https://www.urban.org.in'

ABOUT = ("U-CAN is a network of organisations working together to strengthen urban problem-solving in India's "
         "Tier II and Tier III cities. Formed in 2022 by a founding circle of twelve city-focused organisations, "
         "U-CAN today convenes practitioners, government officials, researchers and philanthropies around one shared "
         "goal: safer, more inclusive, better-governed cities for their residents.")

FACTS = [
    'A network of 8 member organisations connecting 500+ practitioners and reaching 15,000+ people across channels.',
    'Communities of Learning span 200 government officials across 25+ cities in 3 states.',
    'Focus: urban governance, municipal capacity, collaboration between organisations, citizen participation and '
    'inclusion, with special attention to India\'s smaller (Tier II and Tier III) cities.',
    'Name: write "U-CAN" (Urban Collective Action Network). Website: urban.org.in. Language: English (India).',
]
MEMBERS = ['Artha Global', 'Centre for Policy Research (CPR)', 'eGov Foundation', 'Janaagraha', 'Praja Foundation',
           'Reap Benefit', 'Shelter Associates', 'WRI India']
FRIENDS = ['Mahila Housing Trust', 'Indian Institute of Science (IISc)', 'C40 Cities']

SECTIONS = [
    ('About U-CAN', ['about', 'our-people', 'our-members', 'impact']),
    ('Initiatives', ['urban-reforms-collective', 'rfc', 'learning-network', 'fellowship', 'meet-the-fellows',
                     'fellow-blogs', 'ld-calendar']),
    ('Events', ['city-mixers', 'annual-forum-2025']),
    ('Media', ['newsletter', 'city-champions', 'policy-webinars']),
    ('Policies and data rights', ['privacy-policy', 'terms-of-use', 'data-rights']),
]
PERSPECTIVES = ['urban-perspectives', 'sustainable-urban-development-india', 'urban-collaboration-checklist',
                'citizen-centric-urban-solutions', 'municipal-government-reform-india', 'inclusive-urban-governance-india']


def meta(slug):
    s = BeautifulSoup(io.open(os.path.join(SA, slug + '.html'), encoding='utf-8').read(), 'html.parser')
    t = re.sub(r'\s+\|\s+.*$', '', s.title.get_text(strip=True)).strip()  # drop the " | site name" suffix
    d = s.find('meta', attrs={'name': 'description'})
    d = re.sub(r'\s+', ' ', d['content']).strip() if d else ''
    return t, d  # meta descriptions are already SEO-length; never cut them mid-sentence


def line(slug, desc=True):
    t, d = meta(slug)
    url = ORIGIN + '/' + ('' if slug == 'index' else slug)
    return '- [%s](%s)%s' % (t, url, (': ' + d) if (d and desc) else '')


def main():
    with_p = '--with-perspectives' in sys.argv
    out = ['# U-CAN: Urban Collective Action Network', '', '> ' + ABOUT, '']
    out += ['Key facts (from urban.org.in/impact and the homepage):', ''] + ['- ' + f for f in FACTS] + ['']
    out += ['Member organisations: ' + '; '.join(MEMBERS) + '.', 'Friends of U-CAN: ' + '; '.join(FRIENDS) + '.', '']
    out += ['Homepage: ' + ORIGIN + '/', '']
    for title, slugs in SECTIONS[:1]:
        out += ['## ' + title, ''] + [line(s) for s in slugs] + ['']
    if with_p:
        out += ['## Urban Perspectives (knowledge series)', ''] + [line(s) for s in PERSPECTIVES] + ['']
    for title, slugs in SECTIONS[1:]:
        out += ['## ' + title, ''] + [line(s) for s in slugs] + ['']
    out += ['## Contact', '',
            '- General: connect@urban.org.in', '- Urban Reforms Collective: reforms@urban.org.in',
            '- Data protection (DPDP Act, 2023): privacy@urban.org.in',
            '- LinkedIn: https://www.linkedin.com/company/urban-collective-action-network-u-can/',
            '- YouTube: https://www.youtube.com/@U-CAN24', '']
    blogs = sorted(f[:-5] for f in os.listdir(SA) if re.match(r'blog-(?!tag-).*\.html$', f))
    lds = sorted(f[:-5] for f in os.listdir(SA) if f.startswith('ld-') and f != 'ld-calendar.html')
    webs = sorted(f[:-5] for f in os.listdir(SA) if f.startswith('webinar-'))
    out += ['## Optional', '', 'Longer reading; skip if context is short.', '', '### Blogs by U-CAN Fellows', '']
    out += [line(s, False) for s in blogs] + ['', '### Fellowship learning and development sessions', ''] + [line(s, False) for s in lds]
    out += ['', '### Policy webinars and recaps', ''] + [line(s, False) for s in webs] + ['']
    text = '\n'.join(out)
    io.open(OUT, 'w', encoding='utf-8', newline='\n').write(text)
    print('llms.txt: %d bytes, %d links%s' % (len(text.encode('utf-8')), text.count('](http'), ' (with Urban Perspectives)' if with_p else ''))


if __name__ == '__main__':
    main()
