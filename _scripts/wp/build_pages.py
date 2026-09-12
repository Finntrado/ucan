# -*- coding: utf-8 -*-
"""Phase 2 of the WordPress conversion: the 7 one-off "hub" pages (About,
Impact, Our People, Our Members, Learning Network, URC, RFC) as real WP
page templates, using the file-slug-matches-template convention
(`page-<slug>.php` auto-applies to a WP Page whose slug matches - no
manual template assignment needed once the phase-7 importer creates those
Pages).

Same discipline as build_scaffold.py and every other build script in this
repo: content is extracted by byte offset, never retyped, so there is no
chance of transcription drift from the already-verified static pages.

Per page this pulls, verbatim:
  - <main>...</main>            -> the template body (asset/link paths rewritten)
  - <meta name="description">   -> $ucan_page_meta['description']
  - the page's own JSON-LD <script> block (Organization + WebPage +
    BreadcrumbList, +FAQPage on URC) -> re-emitted completely unchanged,
    since it already carries correct absolute urban.org.in canonical URLs
    and needs no rewriting at all. functions.php's generic wp_head hook
    skips its own Organization node whenever a template supplies this.

Any internal href to a bare slug (no protocol, not mailto/anchor-only)
is rewritten to home_url('/slug/'), whether or not that target page
exists in WP yet - this is a *convention* choice (this build's own
slugs, e.g. "rfc"/"about"), not the old site's real canonical paths
(e.g. "/requests-for-collaboration/"), matching the internal-link
convention already used by front-page.php in phase 0. Un-built targets
will 404 until their own phase lands, which is expected.
"""
import io
import os
import re

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', '..')
STANDALONE = os.path.join(ROOT, 'standalone')
THEME = os.path.join(ROOT, 'wp-theme', 'ucan')

PAGES = [
    # (standalone file, wp slug, template comment label)
    ('about.html', 'about', 'About Us'),
    ('impact.html', 'impact', 'Impact'),
    ('our-people.html', 'our-people', 'Our People'),
    ('our-members.html', 'our-members', 'Our Members'),
    ('learning-network.html', 'learning-network', 'Learning Network'),
    ('urban-reforms-collective.html', 'urban-reforms-collective', 'Urban Reforms Collective'),
    ('rfc.html', 'rfc', 'Request for Collaboration'),
]

ASSET_URI = "<?php echo esc_url( get_template_directory_uri() ); ?>/assets/"

# bare internal slugs this build actually uses in nav/footer/body copy today
# (mailto:, https://, and pure #fragment links are left untouched by the
# regex below regardless of this list)
LINK_ATTR_RE = re.compile(r'(href|src|poster)="([a-z][a-z0-9-]*)((?:#[a-zA-Z0-9_-]+)?)"')


def rewrite_links_and_assets(html):
    def sub(m):
        attr, slug, frag = m.group(1), m.group(2), m.group(3)
        if frag:
            return '%s="<?php echo esc_url( home_url( \'/%s/%s\' ) ); ?>"' % (attr, slug, frag)
        return '%s="<?php echo esc_url( home_url( \'/%s/\' ) ); ?>"' % (attr, slug)
    html = LINK_ATTR_RE.sub(sub, html)
    html = html.replace('href="/"', "href=\"<?php echo esc_url( home_url( '/' ) ); ?>\"")
    html = (html
            .replace('src="assets/', 'src="%s' % ASSET_URI)
            .replace('poster="assets/', 'poster="%s' % ASSET_URI)
            .replace('href="assets/', 'href="%s' % ASSET_URI))

    def srcset_sub(m):
        parts = [p.strip() for p in m.group(1).split(',')]
        parts = [('%s%s' % (ASSET_URI, p[len('assets/'):]) if p.startswith('assets/') else p) for p in parts]
        return 'srcset="%s"' % ', '.join(parts)

    html = re.sub(r'srcset="([^"]*)"', srcset_sub, html)
    return html


def extract(fname):
    s = io.open(os.path.join(STANDALONE, fname), encoding='utf-8').read()
    main = s[s.index('<main'):s.index('</main>') + len('</main>')]
    main = re.sub(r'^<main[^>]*>', '', main)[:-len('</main>')]

    m = re.search(r'<meta name="description" content="([^"]*)">', s)
    description = m.group(1) if m else ''

    ld = s[s.index('<script type="application/ld+json"'):s.index('</script>', s.index('<script type="application/ld+json"')) + len('</script>')]
    # strip the data-ucan attribute + the outer <script> tags - the template
    # re-wraps it, so this is just the raw JSON text
    ld_json = re.sub(r'^<script[^>]*>', '', ld)[:-len('</script>')].strip()

    return rewrite_links_and_assets(main), description, ld_json


TEMPLATE = '''<?php
/**
 * Template Name: %(label)s
 * Auto-applies to a WP Page whose slug is "%(slug)s" (file-name
 * convention - page-%(slug)s.php). Content lifted verbatim from
 * standalone/%(file)s's <main> (CLAUDE.md: content stays verbatim unless a
 * named fix is requested); only asset paths and internal links were
 * rewritten to WP functions. The page's own JSON-LD (already carrying
 * correct absolute urban.org.in canonical URLs) is re-emitted unchanged,
 * so functions.php's generic wp_head hook skips its own Organization node
 * for this page.
 */

$ucan_page_meta = array(
	'description' => %(description_php)s,
);
$ucan_page_jsonld = %(jsonld_php)s;

get_header();
?>
%(main)s
<?php
get_footer();
'''


def php_string(s):
    return "'" + s.replace('\\', '\\\\').replace("'", "\\'") + "'"


def write(path, content):
    full = os.path.join(THEME, path)
    os.makedirs(os.path.dirname(full), exist_ok=True)
    io.open(full, 'w', encoding='utf-8', newline='\n').write(content)
    print('wrote', path, len(content), 'chars')


for fname, slug, label in PAGES:
    main, description, ld_json = extract(fname)
    out = TEMPLATE % dict(
        label=label, slug=slug, file=fname,
        description_php=php_string(description),
        jsonld_php=php_string(ld_json),
        main=main,
    )
    write('page-%s.php' % slug, out)
