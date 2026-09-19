<?php
/**
 * U-CAN static-mirror theme.
 *
 * Every front-end URL that matches a page in pages/ is answered with that
 * page's HTML exactly as built for Vercel - no header.php/footer.php, no
 * wp_head(), nothing WordPress could restyle. The only change is swapping
 * the URL placeholders written by _scripts/wp/build_static_theme.py.
 *
 * URL shape mirrors vercel.json (cleanUrls + trailingSlash:false): /about,
 * not /about/ or /about.html. That matters beyond looks - a few inline
 * scripts navigate with relative slugs, which only resolve correctly from
 * a slash-less URL.
 *
 * Also here, so the old urban.org.in can be switched off:
 * - every old URL (/about-us/, /member/<slug>/, /etn/<slug>/, ...) 301s to
 *   its new page (redirects.php, generated from each page's old canonical)
 * - /sitemap.xml lists every page; WP core's own sitemap is off
 * - the same security headers Vercel sends
 * - newsletter signups are saved to this database (inc/subscribers.php)
 *
 * wp-admin, wp-login and the REST API are untouched.
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/inc/subscribers.php';
require_once __DIR__ . '/inc/canonical.php';

/**
 * The theme outputs raw HTML directly (see ucan_serve_static_page below) and
 * never calls wp_head()/wp_footer(), which is exactly the hook plugins like
 * WPCode use to inject anything site-wide. Run those hooks anyway and splice
 * their output into the page, so any such plugin keeps working without
 * needing per-page edits. Strip the usual default-WP head clutter first
 * (RSD/pingback/shortlink/emoji script/generator tag) - it was never on
 * these pages and shouldn't appear now just because the hook fires.
 */
add_action(
	'init',
	function () {
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	}
);

function ucan_run_wp_head_footer() {
	ob_start();
	do_action( 'wp_head' );
	$head = ob_get_clean();
	ob_start();
	do_action( 'wp_footer' );
	$foot = ob_get_clean();
	return array( $head, $foot );
}

add_filter( 'wp_sitemaps_enabled', '__return_false' );
add_filter(
	'robots_txt',
	function ( $output, $public ) {
		if ( $public ) {
			$output .= "\nSitemap: " . home_url( '/sitemap.xml' ) . "\n";
		}
		return $output;
	},
	10,
	2
);

add_action( 'template_redirect', 'ucan_serve_static_page', 0 );

function ucan_serve_static_page() {
	$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
	$path    = (string) wp_parse_url( $request, PHP_URL_PATH );
	$query   = (string) wp_parse_url( $request, PHP_URL_QUERY );
	$base    = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	$home    = ucan_public_origin( untrailingslashit( home_url() ) );

	$rel = rawurldecode( $path );
	if ( '' !== $base && 0 === strpos( $rel, $base ) ) {
		$rel = substr( $rel, strlen( $base ) );
	}
	$slug = trim( $rel, '/' );

	if ( 'sitemap.xml' === $slug ) {
		ucan_sitemap( UCAN_CANON_ORIGIN );
	}

	if ( '' === $slug ) {
		// Leave real WP queries on the root (?s=, ?p=, previews) to WordPress.
		if ( '' !== $query ) {
			return;
		}
		$slug = 'index';
	}

	$file = get_template_directory() . '/pages/' . $slug . '.html';
	if ( ! preg_match( '/^[a-z0-9-]+$/', $slug ) || ! is_file( $file ) ) {
		ucan_redirect_old_url( $slug, $home, $query );
		return;
	}

	if ( 'index' === $slug && '' !== trim( $rel, '/' ) ) {
		wp_safe_redirect( $home . '/', 301 );
		exit;
	}
	if ( 'index' !== $slug && '/' === substr( $path, -1 ) ) {
		wp_safe_redirect( $home . '/' . $slug . ( '' !== $query ? '?' . $query : '' ), 301 );
		exit;
	}

	$html = file_get_contents( $file );
	$html = str_replace(
		array( '%%UCAN_THEME%%', '%%UCAN_HOME%%', '%%UCAN_API%%' ),
		array( ucan_on_origin( get_template_directory_uri(), $home ), $home, ucan_on_origin( esc_url_raw( rest_url( 'ucan/v1/subscribe' ) ), $home ) ),
		$html
	);

	$html = ucan_canonicalise_head( $html, $home );

	list( $head_extra, $foot_extra ) = ucan_run_wp_head_footer();
	if ( $head_extra && false !== strpos( $html, '</head>' ) ) {
		$html = str_replace( '</head>', $head_extra . '</head>', $html );
	}
	if ( $foot_extra && false !== strpos( $html, '</body>' ) ) {
		$html = str_replace( '</body>', $foot_extra . '</body>', $html );
	}

	status_header( 200 );
	header( 'Content-Type: text/html; charset=UTF-8' );
	ucan_security_headers();
	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput -- trusted, theme-bundled page.
	exit;
}

/** Old urban.org.in URL -> its page on this site (301), if there is one. */
function ucan_redirect_old_url( $old, $home, $query ) {
	static $map = null;
	if ( null === $map ) {
		$map = include __DIR__ . '/redirects.php';
	}
	$key = strtolower( trim( $old, '/' ) );
	if ( isset( $map[ $key ] ) ) {
		$to = 'index' === $map[ $key ] ? '/' : '/' . $map[ $key ];
		wp_safe_redirect( $home . $to . ( '' !== $query ? '?' . $query : '' ), 301 );
		exit;
	}
}

function ucan_sitemap( $home ) {
	status_header( 200 );
	header( 'Content-Type: application/xml; charset=UTF-8' );
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
	foreach ( glob( get_template_directory() . '/pages/*.html' ) as $f ) {
		$slug = basename( $f, '.html' );
		$loc  = 'index' === $slug ? $home . '/' : $home . '/' . $slug;
		printf( "<url><loc>%s</loc><lastmod>%s</lastmod></url>\n", esc_url( $loc ), esc_html( gmdate( 'Y-m-d', filemtime( $f ) ) ) );
	}
	echo '</urlset>';
	exit;
}

/** Same policy as standalone/vercel.json, with the theme's own origin allowed. */
function ucan_security_headers() {
	$origin = wp_parse_url( get_template_directory_uri() );
	$assets = "'self'";
	if ( ! empty( $origin['host'] ) ) {
		$assets .= ' ' . $origin['scheme'] . '://' . $origin['host'] . ( ! empty( $origin['port'] ) ? ':' . $origin['port'] : '' );
	}
	$csp = "default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; "
		. "script-src $assets 'unsafe-inline'; style-src $assets 'unsafe-inline' data:; font-src $assets data:; "
		. "img-src $assets data:; media-src $assets; frame-src https://www.youtube-nocookie.com https://www.youtube.com; "
		. "connect-src 'self'";
	// https-only headers: over plain http (e.g. a LocalWP test site) browsers
	// ignore COOP and log a console error, and upgrading requests breaks assets
	if ( is_ssl() ) {
		$csp .= '; upgrade-insecure-requests';
		header( 'Strict-Transport-Security: max-age=63072000; includeSubDomains' );
		header( 'Cross-Origin-Opener-Policy: same-origin' );
	}
	header( 'Content-Security-Policy: ' . $csp );
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: DENY' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=(), interest-cohort=()' );
}
