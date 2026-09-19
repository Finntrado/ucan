# -*- coding: utf-8 -*-
"""Per-article SEO/AEO configuration for the five cluster pages.

Everything a search or answer engine reads that is NOT lifted straight out of
the source Google Doc lives here, so it can be reviewed and edited in one place:
title tag, meta description, the 40-60 word direct answer, key takeaways, the
sourced statistics, and which headings of the doc feed the diagram.

Statistics rule: every figure below was checked against the page it links to
(see CLAUDE.md §29). Nothing here is an estimate. U-CAN's own numbers come from
urban.org.in/impact and the homepage.
"""

SITE = 'https://urban.org.in'
PUBLISHED = '2026-09-19'
MODIFIED = '2026-09-19'

# Named person if U-CAN supplies one; until then the organisation is the author.
# (kind 'Person' needs name/jobTitle/url; see build_articles.author_html.)
AUTHOR = {
    'kind': 'Organization',
    'name': 'U-CAN editorial team',
    'byline': 'U-CAN editorial team',
    'bio': ("U-CAN is a network of organisations working together to strengthen urban "
            "problem-solving in India's Tier II and Tier III cities. Formed in 2022 by a "
            "founding circle of twelve city-focused organisations, U-CAN today convenes "
            "practitioners, government officials, researchers and philanthropies around "
            "one shared goal: safer, more inclusive, better-governed cities for their residents."),
}

WB = ('World Bank, Towards Resilient and Prosperous Cities in India (2025), via Drishti IAS summary',
      'https://www.drishtiias.com/daily-updates/daily-news-analysis/towards-resilient-and-prosperous-cities-in-india')
NITI_SDG = ('NITI Aayog, SDG Goal 11 dashboard',
            'https://www.niti.gov.in/competitive-federalism/sdg/goal-11-make-cities-and-human-settlements-inclusive-safe-resilient-and-sustainable')
WB_GENDER = ('World Bank, Handbook for Gender-Inclusive Urban Planning and Design (2020)',
             'https://www.worldbank.org/en/news/press-release/2020/02/12/designing-gender-inclusive-cities-that-work-for-all')
RBI = ('Reserve Bank of India, Report on Municipal Finances (13 Nov 2024)',
       'https://www.rbi.org.in/Scripts/BS_PressReleaseDisplay.aspx?prid=59093')
NITI_CITY = ('NITI Aayog, Moving Towards Effective City Government (2026)',
             'https://www.niti.gov.in/node/2278')
TWELFTH = ('12th Schedule provisions overview, 74th Constitutional Amendment Act, 1992',
           'https://byjus.com/ias-questions/what-is-12th-schedule-of-indian-constitution/')

ARTICLES = [
    dict(
        n=1, doc='a1.html', slug='sustainable-urban-development-india',
        keyword='Sustainable Urban Development in India',
        intent='What it is',
        title='Sustainable Urban Development in India: Explained | U-CAN',
        description=('Sustainable urban development in India is about governance, not just infrastructure: '
                     'strong institutions, climate resilience, participation and collaboration.'),
        crumb='Sustainable urban development',
        kicker='The pillar guide',
        q='What is sustainable urban development in India?',
        answer=('Sustainable urban development in India means planning and managing cities to balance economic '
                'opportunity, social inclusion, environmental sustainability, climate resilience and effective '
                'governance. It takes more than infrastructure: cities need institutions that can plan for growth, '
                'coordinate across agencies, involve citizens and measure outcomes.'),
        takeaways=[
            'Sustainable urban development is a governance challenge as much as an infrastructure one.',
            'Urban growth is spreading well beyond the megacities into Tier II and Tier III cities and towns.',
            'Climate resilience, digital systems and citizen participation all depend on institutions that can coordinate.',
            'Collaboration and learning across cities work as missing urban infrastructure.',
            'Measure outcomes, not announcements.',
        ],
        stats=[
            ('951 million', "India's urban population is projected to nearly double to this figure by 2050.", WB),
            ('70%', 'of all new jobs in India are expected to be generated in cities by 2030.', WB),
            ('USD 2.4 trillion', 'is what Indian cities will need by 2050 to build climate-resilient infrastructure and services.', WB),
            ('USD 5 bn to 30 bn', 'annual flood losses could rise from about USD 5 billion by 2030 to USD 30 billion by 2070 without adaptation.', WB),
            ('3 lakh', 'heat-related deaths a year are projected by 2050.', WB),
        ],
        diagram=dict(kind='steps', title='Seven building blocks of a sustainable Indian city',
                     caption='The seven building blocks from the article, in order.',
                     pick=('h3num', 'What would a genuinely sustainable Indian city look like?', 7)),
        related=[2, 3, 4, 5],
    ),
    dict(
        n=2, doc='a2.html', slug='urban-collaboration-checklist',
        keyword='Urban Collaboration Checklist',
        intent='How to do it',
        title='Urban Collaboration Checklist: A Free 10-Step Framework | U-CAN',
        description=('A practical 10-step Urban Collaboration Checklist for cities: define the problem, test readiness, '
                     'agree roles, measure outcomes. Free one-page PDF.'),
        crumb='Urban Collaboration Checklist',
        kicker='Practical framework',
        lede=('A practical framework for deciding whether, and how, government, community organisations, '
              'researchers and funders should work together on an urban problem.'),
        q='What is the Urban Collaboration Checklist?',
        answer=('The Urban Collaboration Checklist is a practical framework for assessing whether organisations or '
                'institutions should collaborate on an urban problem and, if so, how to structure the partnership. '
                'It runs from defining the problem and testing readiness to agreeing roles, checking resources '
                'and measuring outcomes rather than meetings.'),
        takeaways=[
            'Ask first whether collaboration is needed: some problems are better solved by one capable organisation.',
            'The checklist has 10 stages, from defining the problem to measuring outcomes rather than meetings.',
            'Test readiness and agree roles, decisions and resources before announcing a partnership.',
            'A one-page version and a 10-minute test make the framework usable in a real meeting.',
        ],
        stats=[
            ('10', 'stages in the Urban Collaboration Checklist, from defining the problem to measuring outcomes.', None),
            ('8 · 500+ · 25+', "U-CAN's network today: member organisations, practitioners connected and cities reached.",
             ('U-CAN, Impact', '/impact')),
            ('200', 'government officials engaged through U-CAN Communities of Learning, across 25+ cities in 3 states.',
             ('U-CAN, Impact', '/impact')),
        ],
        diagram=dict(kind='steps', title='The 10 stages of the Urban Collaboration Checklist',
                     caption='The ten stages of the U-CAN Urban Collaboration Checklist, in order.',
                     pick=('h2num', None, 10)),
        related=[1, 3, 4, 5],
        pdf=True,
    ),
    dict(
        n=3, doc='a3.html', slug='citizen-centric-urban-solutions',
        keyword='Citizen-Centric Urban Solutions',
        intent='How to design it',
        title='Citizen-Centric Urban Solutions for Indian Cities | U-CAN',
        description=('How to design Indian cities around the people who live in them: participation, feedback loops, '
                     'digital access and measuring what residents actually experience.'),
        crumb='Citizen-centric urban solutions',
        kicker='Design approach',
        q='What are citizen-centric urban solutions?',
        answer=('Citizen-centric urban solutions are policies, services, infrastructure and governance approaches '
                'designed around the actual needs and experiences of people living in cities. They combine service '
                'quality, accessibility, participation, accountability, technology and a feedback loop, so residents '
                'can use services easily and see what happens after they raise a problem.'),
        takeaways=[
            'A citizen-centric city designs services around how people actually experience them.',
            'Participation must move beyond the public meeting towards citizen partnership.',
            'Feedback matters only when the city acts and reports back: listen, analyse, decide, act, measure, report back.',
            'Design deliberately for the people easiest to overlook, and offer more than one access channel.',
        ],
        stats=[
            ('97%', 'of wards have 100% door-to-door waste collection, an outcome cities can report to residents.', NITI_SDG),
            ('68% to 78.46%', 'municipal solid waste processed rose from 2020 to 2024.', NITI_SDG),
            ('90%', 'of wards have 100% source segregation under Swachh Bharat Mission (Urban).', NITI_SDG),
            ('10–20%', 'road flooding can disrupt over 50% of a city transport system: the street-level experience of a governance gap.', WB),
        ],
        diagram=dict(kind='loop', title='The citizen feedback loop',
                     caption='Feedback only counts when the loop closes: the six steps from the article.',
                     pick=('h3names', 'Listen|Analyse|Decide|Act|Measure|Report back', 6)),
        related=[1, 2, 4, 5],
    ),
    dict(
        n=4, doc='a4.html', slug='municipal-government-reform-india',
        keyword='Municipal Government Reform in India',
        intent='Institutions and finance',
        title='Municipal Government Reform in India: Powers & Finance | U-CAN',
        description=('Municipal government reform in India: why city governments need the authority, money, people and '
                     'accountability to match their duties. With a checklist.'),
        crumb='Municipal government reform',
        kicker='Institutions and finance',
        q='What is municipal government reform in India?',
        answer=('Municipal government reform in India means changing the structure, powers, finances, staffing and '
                'accountability of urban local governments so they can deliver services. Rooted in the 74th '
                'Constitutional Amendment, it asks whether city governments have the authority, money and '
                'capacity that match the responsibilities citizens expect them to carry.'),
        takeaways=[
            'The 74th Constitutional Amendment created the framework, but responsibility often outruns authority.',
            'Reform has five parts: political leadership, administrative capacity, functional authority, financial capacity and public accountability.',
            'Municipal finance, State Finance Commissions and professional staffing are the least visible constraints.',
            'Smaller cities cannot simply copy metropolitan models.',
        ],
        stats=[
            ('18', 'functions listed in the Twelfth Schedule, added by the 74th Constitutional Amendment Act, 1992, that may be devolved to municipalities.', TWELFTH),
            ('232', 'municipal corporations analysed in the RBI report, covering more than 90% of all municipal corporations in India.', RBI),
            ('Fixed tenure', 'NITI Aayog’s 2026 framework recommends a directly elected Mayor with a fixed tenure, alongside an empowered Mayor-in-Council.', NITI_CITY),
            ('Report to the city', 'NITI Aayog recommends that parastatals and SPVs delivering essential urban services should report directly to the city government.', NITI_CITY),
        ],
        diagram=dict(kind='pillars', title='Five parts of municipal reform',
                     caption='Effective city government rests on five parts, as set out in the article.',
                     pick=('h3num_first', 'Municipal reform is not simply administrative reform', 5),
                     roof='Effective city government'),
        related=[1, 2, 3, 5],
    ),
    dict(
        n=5, doc='a5.html', slug='inclusive-urban-governance-india',
        keyword='Inclusive Urban Governance in India',
        intent='Equity',
        title='Inclusive Urban Governance in India: Who Gets a Voice? | U-CAN',
        description=('Inclusive urban governance in India: who is missing from city decisions, and how representation, '
                     'access, voice and accountability make cities work for all.'),
        crumb='Inclusive urban governance',
        kicker='Equity',
        q='What is inclusive urban governance?',
        answer=('Inclusive urban governance is an approach to running cities in which different groups can access '
                'services, take part in decisions and share in the benefits of urban development. It considers '
                'political, social, economic, spatial, digital and institutional inclusion, and asks who is '
                'missing from planning, budgets and everyday city services.'),
        takeaways=[
            'Inclusion is about who is missing from decisions, not only who is allowed to live in the city.',
            'It spans political, social, economic, spatial, digital and institutional inclusion.',
            'Consultation is not inclusion: participation needs information, institutions and a path to influence.',
            'Measure inclusion through participation, representation, accessibility, service equity, affordability, digital inclusion, responsiveness and outcome equity.',
        ],
        stats=[
            ('10%', 'of the highest-ranking jobs at the world’s leading architecture firms are held by women, one reason cities have been planned around traditional gender roles.', WB_GENDER),
            ('97%', 'of wards report 100% door-to-door waste collection. Headline coverage like this can still hide which neighbourhoods and households are missed.', NITI_SDG),
            ('951 million', 'India’s projected urban population by 2050, so inclusion has to be built into institutions, not left to goodwill.', WB),
        ],
        diagram=dict(kind='stairs', title='A framework for inclusive urban governance',
                     caption='Six steps from being counted to shaping outcomes, as set out in the article.',
                     pick=('h3num', 'A practical framework for inclusive urban governance', 6)),
        related=[1, 2, 3, 4],
    ),
]

RELATED_BLURB = {
    1: 'What sustainable urban development in India really requires',
    2: 'A free 10-step framework for cities that want to partner well',
    3: 'Designing Indian cities around the people who live in them',
    4: 'Why city governments need authority, money and capacity',
    5: 'Who gets a voice in the city, and who is missing',
}
