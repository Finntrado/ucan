<?php
/**
 * U-CAN static-mirror theme.
 *
 * Every front-end URL that matches a page in pages/ is answered with that
 * page's HTML exactly as built for Vercel - no header.php/footer.php, no
 * wp_head(), nothing WordPress could restyle. The only change is swapping
 * the two URL placeholders written by _scripts/wp/build_static_theme.py.
 *
 * URL shape mirrors vercel.json (cleanUrls + trailingSlash:false): /about,
 * not /about/ or /about.html. That matters beyond looks - a few inline
 * scripts navigate with relative slugs, which only resolve correctly from
 * a slash-less URL.
 *
 * wp-admin, wp-login, REST, feeds and sitemaps are untouched.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'template_redirect', 'ucan_serve_static_page', 0 );

function ucan_serve_static_page() {
	$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
	$path    = (string) wp_parse_url( $request, PHP_URL_PATH );
	$query   = (string) wp_parse_url( $request, PHP_URL_QUERY );
	$base    = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );

	$rel = $path;
	if ( '' !== $base && 0 === strpos( $rel, $base ) ) {
		$rel = substr( $rel, strlen( $base ) );
	}
	$slug = trim( $rel, '/' );

	if ( '' === $slug ) {
		// Leave real WP queries on the root (?s=, ?p=, previews) to WordPress.
		if ( '' !== $query ) {
			return;
		}
		$slug = 'index';
	} elseif ( ! preg_match( '/^[a-z0-9-]+$/', $slug ) ) {
		return;
	}

	$file = get_template_directory() . '/pages/' . $slug . '.html';
	if ( ! is_file( $file ) ) {
		return;
	}

	$home = untrailingslashit( home_url() );

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
		array( '%%UCAN_THEME%%', '%%UCAN_HOME%%' ),
		array( get_template_directory_uri(), $home ),
		$html
	);

	status_header( 200 );
	header( 'Content-Type: text/html; charset=UTF-8' );
	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput -- trusted, theme-bundled page.
	exit;
}
