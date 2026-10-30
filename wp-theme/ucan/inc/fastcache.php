<?php
/**
 * Fast pages: Apache answers page requests from prebuilt files, without PHP.
 *
 * Why: field TTFB was 2 s. A static file here answers ~100 ms after the
 * connection is up, a page through WordPress 450-880 ms, and every visit booted
 * WordPress (WP_CACHE off, no cache plugin). Redirects from urban.org.in and
 * http:// booted it too, before the real page even started.
 *
 * What: the first visit to a page saves the exact bytes PHP is about to send to
 * wp-content/ucan-cache/<slug>.html. A block at the top of the root .htaccess
 * then serves that file directly, and does the http/apex -> https://www redirect
 * for page-shaped URLs. Everything else still reaches WordPress unchanged:
 * logged-in visitors, POST, query strings, trailing slashes, old URLs, 404s.
 *
 * Not a cache plugin because those skip PHP and so lose the security headers.
 * Here wp-content/ucan-cache/.htaccess sends the very headers
 * ucan_security_headers() just sent, read back from headers_list().
 *
 * Safety:
 *  - The root .htaccess is only touched after the cache directory's own rules
 *    work over a real request and Apache has accepted the exact block in a
 *    throwaway folder. After writing it, real requests check a page, the login
 *    form, the redirects and the headers; any failure restores the previous
 *    file byte for byte and is not retried.
 *  - Files are dropped when the theme changes, on content or plugin changes,
 *    and daily.
 *  - Off switch: `wp option update ucan_fc_off 1` removes the block and the
 *    files on the next PHP request. Switching theme does the same.
 */

defined( 'ABSPATH' ) || exit;

const UCAN_FC_BEGIN = '# BEGIN U-CAN fast pages';
const UCAN_FC_END   = '# END U-CAN fast pages';
const UCAN_FC_HOME  = '__home'; // not a valid slug, so /index still reaches PHP and 301s

function ucan_fc_dir() {
	return WP_CONTENT_DIR . '/ucan-cache';
}

function ucan_fc_state() {
	$s = get_option( 'ucan_fc_state' );
	return is_array( $s ) ? $s : array();
}

/** Changes whenever a new theme version is published. */
function ucan_fc_build() {
	$t = get_template_directory();
	return md5( @filemtime( $t ) . '|' . @filemtime( $t . '/functions.php' ) . '|' . @filemtime( $t . '/inc/fastcache.php' ) );
}

/** The block for the root .htaccess. Must come before WordPress's own rules. */
function ucan_fc_root_rules() {
	$dir = str_replace( '\\', '/', ucan_fc_dir() );
	$url = '/' . ltrim( (string) wp_parse_url( content_url( 'ucan-cache' ), PHP_URL_PATH ), '/' );

	$when = "RewriteCond %{REQUEST_METHOD} ^(GET|HEAD)$\n"
		. "RewriteCond %{HTTP_HOST} =" . UCAN_CANON_HOST . "\n"
		. "RewriteCond %{HTTPS} =on\n"
		. "RewriteCond %{QUERY_STRING} ^$\n"
		. "RewriteCond %{HTTP_COOKIE} !(wordpress_logged_in_|wp-postpass_|comment_author_)\n";

	return UCAN_FC_BEGIN . "\n"
		. "<IfModule mod_rewrite.c>\n"
		. "RewriteEngine On\n"
		// one hop to the canonical address, same result as ucan_enforce_https_and_host()
		. "RewriteCond %{REQUEST_METHOD} ^(GET|HEAD)$\n"
		. "RewriteCond %{HTTP_HOST} ^(www\\.)?urban\\.org\\.in$ [NC]\n"
		. "RewriteCond %{REQUEST_URI} !^/wp-\n"
		. "RewriteCond %{QUERY_STRING} !rest_route=\n"
		. "RewriteCond %{HTTPS} !=on [OR]\n"
		. "RewriteCond %{HTTP_HOST} !=" . UCAN_CANON_HOST . "\n"
		. "RewriteRule ^([a-z0-9-]*)$ " . UCAN_CANON_ORIGIN . "/$1 [R=301,L,E=UCAN_R:1]\n"
		. $when
		. "RewriteCond \"$dir/" . UCAN_FC_HOME . ".html\" -f\n"
		. "RewriteRule ^$ $url/" . UCAN_FC_HOME . ".html [L]\n"
		. $when
		. "RewriteCond \"$dir/$1.html\" -f\n"
		. "RewriteRule ^([a-z0-9-]+)$ $url/$1.html [L]\n"
		. "</IfModule>\n"
		. "<IfModule mod_headers.c>\n"
		. "Header always set Cache-Control \"public, max-age=3600\" env=UCAN_R\n"
		. "Header always set Strict-Transport-Security \"max-age=63072000; includeSubDomains\" \"expr=%{HTTPS} == 'on' && -n reqenv('UCAN_R')\"\n"
		. "</IfModule>\n"
		. UCAN_FC_END . "\n";
}

/** The cache directory's rules: never directly fetchable, and the same headers PHP sends. */
function ucan_fc_dir_rules( $headers ) {
	$out = "<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteCond %{ENV:REDIRECT_STATUS} ^$\nRewriteRule ^ - [F]\n</IfModule>\n"
		. "AddType \"text/html; charset=UTF-8\" .html\n"
		. "FileETag None\n"
		. "<IfModule mod_headers.c>\n"
		. "Header unset ETag\n"
		. "Header set Cache-Control \"no-cache\"\n"
		. "Header set X-UCAN-Cache \"HIT\"\n";
	foreach ( $headers as $name => $value ) {
		$out .= 'Header set ' . $name . ' "' . str_replace( array( '\\', '"' ), array( '\\\\', '\\"' ), $value ) . "\"\n";
	}
	return $out . "</IfModule>\n";
}

/** The security headers this response is about to send, or null if any is missing. */
function ucan_fc_sent_security_headers() {
	$want = array( 'Strict-Transport-Security', 'Cross-Origin-Opener-Policy', 'Content-Security-Policy', 'X-Content-Type-Options', 'X-Frame-Options', 'Referrer-Policy', 'Permissions-Policy' );
	$got  = array();
	foreach ( headers_list() as $line ) {
		$parts = explode( ':', $line, 2 );
		foreach ( $want as $name ) {
			if ( 2 === count( $parts ) && 0 === strcasecmp( trim( $parts[0] ), $name ) ) {
				$got[ $name ] = trim( $parts[1] );
			}
		}
	}
	return count( $got ) === count( $want ) ? $got : null;
}

/** Only an anonymous, plain GET of the canonical address may be saved. */
function ucan_fc_can_save() {
	if ( get_option( 'ucan_fc_off' ) || UCAN_CANON_HOST !== ucan_request_host() || ! is_ssl() ) {
		return false;
	}
	if ( 'GET' !== ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) || ! empty( $_SERVER['QUERY_STRING'] ) ) {
		return false;
	}
	if ( false === stripos( isset( $_SERVER['SERVER_SOFTWARE'] ) ? (string) $_SERVER['SERVER_SOFTWARE'] : '', 'apache' ) ) {
		return false;
	}
	if ( '/' !== (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ) ) {
		return false;
	}
	foreach ( array_keys( $_COOKIE ) as $c ) {
		if ( preg_match( '/^(wordpress_logged_in_|wp-postpass_|comment_author_)/', (string) $c ) ) {
			return false;
		}
	}
	return true;
}

/** Called by ucan_serve_static_page() after ucan_security_headers(), before echo. */
function ucan_fc_save( $slug, $html ) {
	if ( ! ucan_fc_can_save() ) {
		return;
	}
	$state = ucan_fc_state();
	if ( isset( $state['failed'] ) && md5( ucan_fc_root_rules() ) === $state['failed'] ) {
		return;
	}
	$headers = ucan_fc_sent_security_headers();
	$dir     = ucan_fc_dir();
	if ( null === $headers || ! wp_mkdir_p( $dir ) ) {
		return;
	}
	// rules first: a page file must never exist without its headers
	$rules = ucan_fc_dir_rules( $headers );
	$file  = $dir . '/.htaccess';
	if ( ! is_file( $file ) || (string) file_get_contents( $file ) !== $rules ) {
		if ( false === file_put_contents( $file, $rules, LOCK_EX ) ) {
			return;
		}
	}
	$name = 'index' === $slug ? UCAN_FC_HOME : $slug;
	$tmp  = $dir . '/' . $name . '.' . getmypid() . '.tmp';
	if ( false === file_put_contents( $tmp, $html ) || ! @rename( $tmp, $dir . '/' . $name . '.html' ) ) {
		@unlink( $tmp );
		return;
	}
	if ( ! isset( $state['rules'] ) || md5( ucan_fc_root_rules() ) !== $state['rules'] ) {
		add_action( 'shutdown', 'ucan_fc_install', 99 );
		$GLOBALS['ucan_fc_slug'] = $slug;
	}
}

function ucan_fc_purge() {
	$dir = ucan_fc_dir();
	foreach ( array_merge( (array) glob( $dir . '/*.html' ), (array) glob( $dir . '/*.tmp' ) ) as $f ) {
		@unlink( $f );
	}
}

function ucan_fc_root_file() {
	return ABSPATH . '.htaccess';
}

function ucan_fc_without_block( $text ) {
	return (string) preg_replace( '/' . preg_quote( UCAN_FC_BEGIN, '/' ) . '.*?' . preg_quote( UCAN_FC_END, '/' ) . '\R?/s', '', $text );
}

function ucan_fc_get( $url ) {
	return wp_remote_get(
		$url,
		array(
			'redirection' => 0,
			'timeout'     => 10,
			'user-agent'  => 'ucan-fastcache-selftest',
		)
	);
}

/** What a request answered: array( status, HIT?, Location, has CSP?, body ). */
function ucan_fc_probe( $url ) {
	$r = ucan_fc_get( $url );
	if ( is_wp_error( $r ) ) {
		return array( 0, false, '', false, '' );
	}
	return array(
		(int) wp_remote_retrieve_response_code( $r ),
		'HIT' === wp_remote_retrieve_header( $r, 'x-ucan-cache' ),
		(string) wp_remote_retrieve_header( $r, 'location' ),
		'' !== (string) wp_remote_retrieve_header( $r, 'content-security-policy' ),
		(string) wp_remote_retrieve_body( $r ),
	);
}

/** Every behaviour that must hold once the block is live. Returns '' or what failed. */
function ucan_fc_check_live( $slug ) {
	$o    = UCAN_CANON_ORIGIN;
	$page = 'index' === $slug ? '/' : '/' . $slug;

	$p = ucan_fc_probe( $o . $page );
	if ( 200 !== $p[0] || ! $p[1] || ! $p[3] || false === strpos( $p[4], '</html>' ) ) {
		return 'page not served from file';
	}
	$p = ucan_fc_probe( $o . '/about?ucanselftest=1' );
	if ( 200 !== $p[0] || $p[1] || false === strpos( $p[4], '</html>' ) ) {
		return 'query string did not reach PHP';
	}
	$p = ucan_fc_probe( $o . '/wp-login.php' );
	if ( 200 !== $p[0] || $p[1] ) {
		return 'login page';
	}
	$p = ucan_fc_probe( 'https://' . UCAN_APEX_HOST . '/about' );
	if ( 301 !== $p[0] || $o . '/about' !== $p[2] ) {
		return 'apex redirect';
	}
	$p = ucan_fc_probe( 'http://' . UCAN_CANON_HOST . '/about' );
	if ( 301 !== $p[0] || $o . '/about' !== $p[2] ) {
		return 'http redirect';
	}
	return '';
}

/** Puts the block in the root .htaccess, or leaves the file exactly as it was. */
function ucan_fc_install() {
	$rules = ucan_fc_root_rules();
	$hash  = md5( $rules );
	$state = ucan_fc_state();
	$file  = ucan_fc_root_file();
	$slug  = isset( $GLOBALS['ucan_fc_slug'] ) ? $GLOBALS['ucan_fc_slug'] : 'index';

	if ( ! add_option( 'ucan_fc_lock', time(), '', false ) ) {
		if ( (int) get_option( 'ucan_fc_lock' ) < time() - 300 ) {
			delete_option( 'ucan_fc_lock' );
		}
		return;
	}
	$fail = function ( $why ) use ( $state, $hash ) {
		ucan_fc_purge();
		$state['failed'] = $hash;
		$state['why']    = $why;
		unset( $state['rules'] );
		update_option( 'ucan_fc_state', $state );
		delete_option( 'ucan_fc_lock' );
	};

	if ( ! is_file( $file ) || ! is_writable( $file ) ) {
		$fail( 'root .htaccess not writable' );
		return;
	}
	$before = (string) file_get_contents( $file );

	// the cache directory's own rules must work before the root file is touched
	$name = 'index' === $slug ? UCAN_FC_HOME : $slug;
	$p    = ucan_fc_probe( UCAN_CANON_ORIGIN . wp_parse_url( content_url( 'ucan-cache/' . $name . '.html' ), PHP_URL_PATH ) );
	if ( 403 !== $p[0] ) {
		$fail( 'cache directory rules: direct request answered ' . $p[0] );
		return;
	}
	// a rule Apache cannot parse is a 500 for everything below it, so try the exact
	// block in a throwaway folder first: if it is wrong, only that folder breaks
	$try = ucan_fc_dir() . '/_preflight';
	wp_mkdir_p( $try );
	file_put_contents( $try . '/.htaccess', $rules );
	file_put_contents( $try . '/ok.txt', 'ok' );
	$p = ucan_fc_probe( UCAN_CANON_ORIGIN . wp_parse_url( content_url( 'ucan-cache/_preflight/ok.txt' ), PHP_URL_PATH ) );
	@unlink( $try . '/.htaccess' );
	@unlink( $try . '/ok.txt' );
	@rmdir( $try );
	if ( 200 !== $p[0] ) {
		$fail( 'rules rejected by Apache: test folder answered ' . $p[0] );
		return;
	}

	if ( false === file_put_contents( $file, $rules . ucan_fc_without_block( $before ), LOCK_EX ) ) {
		$fail( 'could not write root .htaccess' );
		return;
	}
	$why = ucan_fc_check_live( $slug );
	if ( '' !== $why ) {
		file_put_contents( $file, $before, LOCK_EX );
		$fail( $why );
		return;
	}
	unset( $state['failed'], $state['why'] );
	$state['rules'] = $hash;
	update_option( 'ucan_fc_state', $state );
	delete_option( 'ucan_fc_lock' );
}

function ucan_fc_uninstall() {
	ucan_fc_purge();
	$file = ucan_fc_root_file();
	if ( is_file( $file ) && is_writable( $file ) ) {
		$before = (string) file_get_contents( $file );
		$after  = ucan_fc_without_block( $before );
		if ( $after !== $before ) {
			file_put_contents( $file, $after, LOCK_EX );
		}
	}
	delete_option( 'ucan_fc_state' );
}

/** Runs on every request that reaches PHP. Cheap unless something changed. */
function ucan_fc_maintain() {
	$state = ucan_fc_state();
	if ( get_option( 'ucan_fc_off' ) ) {
		if ( $state ) {
			ucan_fc_uninstall();
		}
		return;
	}
	$build = ucan_fc_build();
	if ( ! isset( $state['build'] ) || $build !== $state['build'] ) {
		ucan_fc_purge();
		$state['build'] = $build;
		update_option( 'ucan_fc_state', $state );
	}
	if ( ! wp_next_scheduled( 'ucan_fc_daily' ) ) {
		wp_schedule_event( time() + DAY_IN_SECONDS, 'daily', 'ucan_fc_daily' );
	}
}
add_action( 'init', 'ucan_fc_maintain', 1 );

/** Daily: fresh files, and notice if something rewrote the root .htaccess without the block. */
function ucan_fc_daily() {
	ucan_fc_purge();
	$state = ucan_fc_state();
	if ( isset( $state['rules'] ) && false === strpos( (string) @file_get_contents( ucan_fc_root_file() ), UCAN_FC_BEGIN ) ) {
		unset( $state['rules'] );
		update_option( 'ucan_fc_state', $state );
	}
}
add_action( 'ucan_fc_daily', 'ucan_fc_daily' );

// anything that can change what wp_head()/wp_footer() add to a page
foreach ( array( 'save_post', 'activated_plugin', 'deactivated_plugin', 'upgrader_process_complete', 'update_option_wpcode_snippets' ) as $ucan_fc_hook ) {
	add_action( $ucan_fc_hook, 'ucan_fc_purge', 10, 0 );
}
add_action( 'switch_theme', 'ucan_fc_uninstall', 10, 0 );
