# -*- coding: utf-8 -*-
"""Single source of truth mapping this build's internal file-slugs to the
real canonical WP slugs, wherever the two differ, so every internal link
site-wide (nav, footer, page bodies, the default-menu seeder) resolves to
the same final URL.

SEO decision (CLAUDE.md §28): preserve the old site's real canonical paths
rather than this build's own file-naming shorthand, so inbound links and
search rankings carry over once urban.org.in is replaced. Only two of the
hub pages actually differ from their standalone/ filename - the rest
already match, verified against each page's own <link rel="canonical"> in
standalone/*.html.
"""
CANONICAL_SLUG = {
    'about': 'about-us',
    'rfc': 'requests-for-collaboration',
    'learning-network': 'learning-network-for-urban-managers',
}


def wp_slug(file_slug):
    return CANONICAL_SLUG.get(file_slug, file_slug)
