# -*- coding: utf-8 -*-
"""Phase 0 of the WordPress conversion (see plan: convert the static U-CAN
site into a real WP theme so the client gets the CMS they originally asked
for). Extracts the shared chrome that is already byte-identical across all
176 standalone/*.html pages (per CLAUDE.md §3c/§23/§26) and turns it into a
real WordPress theme's header.php / footer.php / style.css, plus a real
front-page.php built from index.html's <main> so there is one full page to
prove the chrome renders identically inside WP.

Everything text-heavy is extracted by byte offset from the source HTML,
never retyped by hand, for the same reason every other build script in this
repo works this way: retyping risks silent transcription errors in dense
CSS/markup (see CLAUDE.md's repeated heredoc-mangling traps).

Not re-runnable against a live WP install (no PHP/MySQL in this sandbox -
see the plan file) - this only ever writes into wp-theme/ucan/.
"""
import io
import os
import re
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from slugs import wp_slug  # noqa: E402

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', '..')
STANDALONE = os.path.join(ROOT, 'standalone')
THEME = os.path.join(ROOT, 'wp-theme', 'ucan')

ABOUT = io.open(os.path.join(STANDALONE, 'about.html'), encoding='utf-8').read()
INDEX = io.open(os.path.join(STANDALONE, 'index.html'), encoding='utf-8').read()


def style_block(s, marker):
    """Return the full <style data-ucan="marker">...</style> block."""
    start = s.index('<style data-ucan="%s">' % marker)
    end = s.index('</style>', start) + len('</style>')
    return s[start:end]


def unwrap_style(block):
    """Strip the <style ...> / </style> tags, keep the CSS body."""
    return re.sub(r'^<style[^>]*>', '', block)[:-len('</style>')]


def php_asset_url(css):
    """Rewrite bare relative asset paths to the theme's asset URI."""
    css = css.replace('url(assets/', "url(<?php echo esc_url( get_template_directory_uri() ); ?>/assets/")
    return css


# ---------------------------------------------------------------- CSS -----
# Shared, byte-identical across every standalone page (verified by reading
# the same six markers out of a second page - city-mixers.html - and diffing;
# see the phase-0 commit notes).
base = unwrap_style(style_block(ABOUT, 'base'))
fonts = php_asset_url(unwrap_style(style_block(ABOUT, 'fonts')))
chrome = unwrap_style(style_block(ABOUT, 'chrome'))
nav = unwrap_style(style_block(ABOUT, 'nav'))
rhythm = unwrap_style(style_block(ABOUT, 'rhythm'))
fallback = unwrap_style(style_block(ABOUT, 'fallback'))
type_fix = unwrap_style(style_block(ABOUT, 'type'))
heads_fix = unwrap_style(style_block(ABOUT, 'heads'))

# The 7-item-nav breakpoint override (CLAUDE.md §13) - shared by every
# .bar-chrome page, unlike the very next <style> in about.html (a
# .sec-head single-column override) which is About-only and stays out.
m = re.search(r'<style>\s*/\* 7-item nav[^<]*</style>', ABOUT, re.S)
nav_breakpoint = unwrap_style(m.group(0))

SHARED_CSS = '\n'.join([base, fonts, chrome, nav, rhythm, fallback, type_fix, heads_fix, nav_breakpoint])

# ------------------------------------------------------- head furniture ---
def tag(pattern):
    m = re.search(pattern, ABOUT)
    return m.group(0) if m else ''

viewport = tag(r'<meta name="viewport"[^>]*>')
preloads = re.findall(r'<link rel="preload"[^>]*>', ABOUT)
preloads = [php_asset_url(p.replace('href="assets/', 'href="ASSETPATH')).replace('ASSETPATH', '') for p in preloads]
# simpler + safer: rebuild preload/favicon hrefs explicitly rather than regex-patching
preloads = [
    '<link rel="preload" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/fonts/archivo-latin.woff2" as="font" type="font/woff2" crossorigin>',
    '<link rel="preload" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/fonts/public-sans-latin.woff2" as="font" type="font/woff2" crossorigin>',
]
favicon = '<link rel="icon" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/favicon-78b77e29b1.png">'
preconnect = '<link rel="preconnect" href="https://urban.org.in/">'

# ------------------------------------------------------------- header -----
header_block = ABOUT[ABOUT.index('<header class="bar"'):ABOUT.index('</header>') + len('</header>')]

# brand logo src -> theme asset URI
header_block = header_block.replace(
    'src="assets/img/u-can-urban-collective-action-network-08e2484273.svg"',
    'src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/u-can-urban-collective-action-network-08e2484273.svg"',
)
# brand link -> home_url()
header_block = header_block.replace('href="/"', "href=\"<?php echo esc_url( home_url( '/' ) ); ?>\"")

# Replace the two hand-generated nav blocks (desktop .ucnav-bar, mobile
# .msheet) with calls into the walkers in functions.php, so the client can
# actually edit these from wp-admin (Appearance > Menus) instead of
# re-running rebuild_nav.py. The exact markup those walkers must reproduce
# is preserved as a comment for whoever reviews functions.php.
header_block = re.sub(
    r'<nav class="ucnav-bar"[^>]*>.*?</nav>',
    "<?php ucan_nav_menu( 'desktop' ); ?>",
    header_block, count=1, flags=re.S,
)
header_block = re.sub(
    r'<div class="msheet" id="msheet">.*?</div>\s*</div>\s*(?=</header>)',
    "<?php ucan_nav_menu( 'mobile' ); ?>\n",
    header_block, count=1, flags=re.S,
)
# subscribe CTA -> use a real WP page link once the newsletter page exists;
# for now keep the same relative slug convention as the rest of chrome
header_block = header_block.replace('href="newsletter#subscribe"', "href=\"<?php echo esc_url( home_url( '/newsletter/#subscribe' ) ); ?>\"")

HEADER_PHP = '''<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<style data-ucan="base">%(base)s</style>

%(viewport)s
%(preloads)s
<style data-ucan="fonts">%(fonts)s</style>

%(favicon)s
%(preconnect)s

<style data-ucan="chrome">%(chrome)s</style>
<style data-ucan="nav">%(nav)s</style>
<style data-ucan="rhythm">%(rhythm)s</style>
<style data-ucan="fallback">%(fallback)s</style>
<style data-ucan="type">%(type_fix)s</style>
<style data-ucan="heads">%(heads_fix)s</style>
<style>%(nav_breakpoint)s</style>

<?php
/**
 * Per-page <title>/meta description/canonical/OG/JSON-LD are added on
 * wp_head by ucan_document_head() in functions.php (title-tag support +
 * canonical are WP core; description/OG/JSON-LD are this theme's own hook).
 * Phase 0 covers the shared Organization graph node only - Person/Article/
 * ProfilePage/FAQPage nodes are added per template in later phases.
 */
wp_head();
?>
</head>
<body <?php body_class(); ?>>
<a class="skip" href="#main">Skip to main content</a>

%(header)s

<main id="main">
''' % dict(
    base=base, viewport=viewport, preloads='\n'.join(preloads), fonts=fonts,
    favicon=favicon, preconnect=preconnect, chrome=chrome, nav=nav,
    rhythm=rhythm, fallback=fallback, type_fix=type_fix, heads_fix=heads_fix,
    nav_breakpoint=nav_breakpoint, header=header_block,
)

# ------------------------------------------------------------- footer -----
footer_block = ABOUT[ABOUT.index('<footer'):ABOUT.index('</footer>') + len('</footer>')]

FOOTER_LINK_SLUGS = [
    'about', 'our-people', 'impact', 'our-members', 'city-mixers',
    'annual-forum-2025', 'urban-reforms-collective', 'rfc', 'learning-network',
    'fellowship', 'newsletter', 'policy-webinars', 'city-champions',
    'terms-of-use', 'privacy-policy', 'data-rights',
]
for slug in FOOTER_LINK_SLUGS:
    footer_block = footer_block.replace(
        'href="%s"' % slug,
        "href=\"<?php echo esc_url( home_url( '/%s/' ) ); ?>\"" % wp_slug(slug),
    )
footer_block = footer_block.replace(
    'src="assets/img/u-can-urban-collective-action-network-08e2484273.svg"',
    'src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/u-can-urban-collective-action-network-08e2484273.svg"',
)
footer_block = footer_block.replace('href="/"', "href=\"<?php echo esc_url( home_url( '/' ) ); ?>\"")
# copyright year: was hardcoded "2026" - make it live
footer_block = re.sub(r'>\s*\S\s*U-CAN \d{4}\. All Rights Reserved<',
                       "><?php echo esc_html( '© U-CAN ' . date( 'Y' ) . '. All Rights Reserved' ); ?><",
                       footer_block)

# the shared behaviour script (DPDP gate, cookie banner, count-ups, reveals,
# burger, FAQ accordion) - identical logic, just enqueued as a real theme
# asset instead of an inlined <script> block, so browsers cache it once
# across every page instead of re-downloading it inline on every request.
behaviour_start = ABOUT.index('<script data-ucan="behaviour"')
behaviour_open_end = ABOUT.index('>', behaviour_start) + 1
behaviour_close = ABOUT.index('</script>', behaviour_start)
ucan_js = ABOUT[behaviour_open_end:behaviour_close]

FOOTER_PHP = '''</main>

%(footer)s

<?php wp_footer(); ?>
</body>
</html>
''' % dict(footer=footer_block)

# ------------------------------------------------------------- home --------
m = re.search(r'<meta name="description" content="([^"]*)">', INDEX)
home_description = m.group(1) if m else ''
home_ld = INDEX[INDEX.index('<script type="application/ld+json"'):INDEX.index('</script>', INDEX.index('<script type="application/ld+json"')) + len('</script>')]
home_ld_json = re.sub(r'^<script[^>]*>', '', home_ld)[:-len('</script>')].strip()


def php_string(s):
    return "'" + s.replace('\\', '\\\\').replace("'", "\\'") + "'"


home_main = INDEX[INDEX.index('<main'):INDEX.index('</main>') + len('</main>')]
# same slug rewrite as the footer (remapped through wp_slug for SEO-aligned
# canonical paths - CLAUDE.md §28), plus any #fragment is preserved; mailto/
# external links are left as-is
HOME_LINK_RE = re.compile(
    r'href="(%s)((?:#[a-zA-Z0-9_-]+)?)"' % '|'.join(re.escape(s) for s in FOOTER_LINK_SLUGS)
)


def _home_link_sub(m):
    slug, frag = m.group(1), m.group(2)
    return 'href="<?php echo esc_url( home_url( \'/%s/%s\' ) ); ?>"' % (wp_slug(slug), frag)


home_main = HOME_LINK_RE.sub(_home_link_sub, home_main)
ASSET_URI = "<?php echo esc_url( get_template_directory_uri() ); ?>/assets/"
home_main = (home_main
             .replace('src="assets/', 'src="%s' % ASSET_URI)
             .replace('poster="assets/', 'poster="%s' % ASSET_URI)
             .replace('href="assets/', 'href="%s' % ASSET_URI))


def srcset_sub(m):
    parts = [p.strip() for p in m.group(1).split(',')]
    parts = [('%s%s' % (ASSET_URI, p[len('assets/'):]) if p.startswith('assets/') else p) for p in parts]
    return 'srcset="%s"' % ', '.join(parts)


home_main = re.sub(r'srcset="([^"]*)"', srcset_sub, home_main)

FRONT_PAGE_PHP = '''<?php
/**
 * Phase 0: Home. Content lifted verbatim from standalone/index.html's
 * <main> (see CLAUDE.md - content stays verbatim unless a named fix is
 * requested); only asset paths and internal links were rewritten to WP
 * functions. The page's own JSON-LD (Organization + WebSite + WebPage +
 * BreadcrumbList, already carrying correct absolute urban.org.in canonical
 * URLs) is re-emitted unchanged via functions.php's wp_head hook. get_header()
 * / get_footer() pull in header.php / footer.php, which is where the actual
 * <main id="main"> open/close tags live, so this file supplies only what
 * goes inside them.
 */

$ucan_page_meta = array(
	'description' => %(description_php)s,
);
$ucan_page_jsonld = %(jsonld_php)s;

get_header();
?>
%(home_main_inner)s
<?php
get_footer();
''' % dict(
    description_php=php_string(home_description),
    jsonld_php=php_string(home_ld_json),
    home_main_inner=re.sub(r'^<main[^>]*>', '', home_main)[:-len('</main>')],
)


def write(path, content):
    full = os.path.join(THEME, path)
    os.makedirs(os.path.dirname(full), exist_ok=True)
    io.open(full, 'w', encoding='utf-8', newline='\n').write(content)
    print('wrote', path, len(content), 'chars')


write('header.php', HEADER_PHP)
write('footer.php', FOOTER_PHP)
write('front-page.php', FRONT_PAGE_PHP)
write('assets/js/ucan.js', ucan_js)

STYLE_CSS = '''/*
Theme Name: U-CAN
Theme URI: https://urban.org.in/
Author: Urban Collective Action Network
Description: Custom theme for U-CAN (Urban Collective Action Network) - editorial-civic design system. Page styles are inlined via header.php for parity with the static build this theme replaces; this file exists only so WordPress recognises the theme.
Version: 0.1.0
Text Domain: ucan
*/
'''
write('style.css', STYLE_CSS)

INDEX_PHP = '''<?php
/**
 * Fallback template WordPress requires every theme to have. Page-specific
 * templates (page-about.php, single-ucan_member.php, etc.) are added phase
 * by phase - see the plan file. Until a template exists for a given
 * page/post type, WP falls back to this.
 */
get_header();
?>
<div class="wrap sec">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <article <?php post_class(); ?>>
    <h1><?php the_title(); ?></h1>
    <div><?php the_content(); ?></div>
  </article>
<?php endwhile; endif; ?>
</div>
<?php
get_footer();
'''
write('index.php', INDEX_PHP)

print('\nucan.js length:', len(ucan_js), 'chars (enqueued, not inlined - see functions.php)')
