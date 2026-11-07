<?php
/**
 * U-CAN Collaboration Toolkit - the online version of the PDF toolkit.
 *
 * Page: /collaboration-toolkit (pages/collaboration-toolkit.html, unlisted:
 * noindex, kept out of the sitemap). Two organisations fill it in
 * independently:
 *   1. Organisation A submits and gets a private partner link (?pair=CODE).
 *   2. Organisation B opens that link, answers the same parts without seeing
 *      A's answers, and submits.
 *   3. The joint result is computed here, shown to B at once, emailed to both
 *      organisations and copied to connect@urban.org.in. Either side can
 *      reopen it at ?results=KEY.
 *
 * Storage: table {prefix}ucan_toolkit, one row per organisation, the two rows
 * of a pair sharing pair_code and result_key. wp-admin > Form submissions >
 * Collaboration Toolkit lists every pair (search, filter, sort, CSV, resend,
 * delete for DPDP erasure).
 *
 * Content (statements, questions, labels) lives in inc/toolkit-data.json,
 * the same file the page is built from, so scoring and emails can never
 * disagree with what people were shown.
 */

defined( 'ABSPATH' ) || exit;

const UCAN_TK_DB_VERSION = '1';
const UCAN_TK_NOTICE     = 'ucan-toolkit-notice-2026-11';
const UCAN_TK_COPY_TO    = 'connect@urban.org.in';
const UCAN_TK_SLUG       = 'collaboration-toolkit';

function ucan_tk_table() {
	global $wpdb;
	return $wpdb->prefix . 'ucan_toolkit';
}

function ucan_tk_data() {
	static $data = null;
	if ( null === $data ) {
		$data = json_decode( (string) file_get_contents( __DIR__ . '/toolkit-data.json' ), true );
	}
	return $data;
}

/** section id => array( part, index-in-part, section ) */
function ucan_tk_sections() {
	static $map = null;
	if ( null === $map ) {
		$map = array();
		foreach ( ucan_tk_data()['parts'] as $part ) {
			foreach ( $part['sections'] as $i => $s ) {
				$map[ $s['id'] ] = array( $part, $i, $s );
			}
		}
	}
	return $map;
}

/* ------------------------------------------------------------- install */

function ucan_tk_install() {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	$table   = ucan_tk_table();
	$charset = $wpdb->get_charset_collate();
	dbDelta(
		"CREATE TABLE $table (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			pair_code varchar(16) NOT NULL,
			result_key varchar(64) NOT NULL,
			role char(1) NOT NULL,
			org varchar(200) NOT NULL,
			person varchar(200) NOT NULL,
			email varchar(191) NOT NULL,
			partner_org varchar(200) NOT NULL DEFAULT '',
			sections varchar(255) NOT NULL,
			answers longtext NOT NULL,
			data_version varchar(32) NOT NULL,
			notice_version varchar(64) NOT NULL,
			created_at datetime NOT NULL,
			emailed_at datetime NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY pair_role (pair_code,role),
			KEY result_key (result_key),
			KEY created_at (created_at)
		) $charset;"
	);
	update_option( 'ucan_tk_db_version', UCAN_TK_DB_VERSION );
}
add_action( 'after_switch_theme', 'ucan_tk_install' );
add_action(
	'init',
	function () {
		if ( get_option( 'ucan_tk_db_version' ) !== UCAN_TK_DB_VERSION ) {
			ucan_tk_install();
		}
	}
);

/* ---------------------------------------------------------------- page */

/*
 * The page is composed per request, like the 404 page: the live header and
 * footer come from pages/about.html, the toolkit's own parts from inc/. So it
 * can never drift from the rest of the site, and it is not in pages/, which
 * keeps it out of the sitemap and the fast-page cache (the ?pair= and
 * ?results= keys are private).
 */
add_action(
	'template_redirect',
	function () {
		$path = (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH );
		if ( '/' . UCAN_TK_SLUG !== untrailingslashit( $path ) ) {
			return;
		}
		if ( '/' === substr( $path, -1 ) ) {
			$q = (string) wp_parse_url( (string) $_SERVER['REQUEST_URI'], PHP_URL_QUERY );
			wp_safe_redirect( ucan_tk_url( array() ) . ( '' !== $q ? '?' . $q : '' ), 301 );
			exit;
		}
		$html = ucan_tk_page();
		if ( '' === $html ) {
			return; // template files missing: fall through to the normal 404
		}
		status_header( 200 );
		header( 'Content-Type: text/html; charset=UTF-8' );
		if ( function_exists( 'ucan_security_headers' ) ) {
			ucan_security_headers();
		}
		// after the site-wide headers, so these win
		header( 'X-Robots-Tag: noindex, nofollow, noarchive' );
		header( 'Referrer-Policy: no-referrer' ); // the private keys in the URL must not leak to other sites
		header( 'Cache-Control: no-store' );
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput -- theme-bundled page; data is JSON-encoded below.
		exit;
	},
	-1
);

/** Where the cover art and the PDF are: the theme copy if present, else the media library. */
function ucan_tk_asset( $theme_rel, $uploads_path ) {
	if ( is_file( get_template_directory() . '/' . $theme_rel ) ) {
		$uri = get_template_directory_uri();
		if ( function_exists( 'ucan_on_origin' ) && function_exists( 'ucan_public_origin' ) ) {
			$uri = ucan_on_origin( $uri, ucan_public_origin( untrailingslashit( home_url() ) ) );
		}
		return $uri . '/' . $theme_rel;
	}
	return $uploads_path;
}

function ucan_tk_page() {
	$dir   = __DIR__;
	$shell = get_template_directory() . '/pages/about.html';
	foreach ( array( 'toolkit-fragment.html', 'toolkit.css', 'toolkit.js', 'toolkit-data.json' ) as $f ) {
		if ( ! is_file( "$dir/$f" ) ) {
			return '';
		}
	}
	if ( ! is_file( $shell ) ) {
		return '';
	}
	$f = (string) file_get_contents( "$dir/toolkit-fragment.html" );
	$s = (string) file_get_contents( $shell );
	if ( ! preg_match( '#<!--HEAD-->(.*?)<!--/HEAD-->#s', $f, $h ) || ! preg_match( '#<!--MAIN-->(.*?)<!--/MAIN-->#s', $f, $m ) ) {
		return '';
	}
	// the shell page's own title, description, canonical, social cards and schema
	foreach ( array(
		'#<title[^>]*>.*?</title>\s*#is', '#<meta\s+name="description"[^>]*>\s*#i', '#<meta\s+name="author"[^>]*>\s*#i',
		'#<link\s+rel="canonical"[^>]*>\s*#i', '#<link\s+rel="alternate"\s+hreflang[^>]*>\s*#i',
		'#<meta\s+property="(?:og|article):[^"]*"[^>]*>\s*#i', '#<meta\s+name="twitter:[^"]*"[^>]*>\s*#i',
		'#<script\s+type="application/ld\+json"[^>]*>.*?</script>\s*#is', '#<meta\s+name="robots"[^>]*>\s*#i',
		'#<link\s+rel="preload"\s+as="image"[^>]*>\s*#i',
	) as $rx ) {
		$s = preg_replace( $rx, '', $s );
	}
	$s = preg_replace( '#(class="ucnav-t)\s+on"#', '$1"', $s );
	$s = preg_replace( '#(<a class="ucnav-t[^"]*"[^>]*?)\s+aria-current="page"#', '$1', $s );

	$origin = function_exists( 'ucan_public_origin' ) ? ucan_public_origin( untrailingslashit( home_url() ) ) : untrailingslashit( home_url() );
	$api    = function_exists( 'ucan_on_origin' ) ? ucan_on_origin( esc_url_raw( rest_url( 'ucan/v1/toolkit' ) ), $origin ) : esc_url_raw( rest_url( 'ucan/v1/toolkit' ) );
	$art    = ucan_tk_asset( 'assets/img/toolkit-cover-art-4e1ee696.webp', '/wp-content/uploads/2026/11/Collaboration-Toolkit-cover-art.webp' );
	$pdf    = ucan_tk_asset( 'assets/docs/u-can-collaboration-toolkit-cf30d7b8.pdf', '/wp-content/uploads/2026/11/U-CAN-Collaboration-Toolkit.pdf' );
	$data   = wp_json_encode( ucan_tk_data(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE );
	$repl   = array(
		'%%TK_CSS%%'  => (string) file_get_contents( "$dir/toolkit.css" ),
		'%%TK_JS%%'   => (string) file_get_contents( "$dir/toolkit.js" ),
		'%%TK_DATA%%' => $data,
		'%%TK_API%%'  => esc_url( $api ),
		'%%TK_ART%%'  => esc_url( $art ),
		'%%TK_PDF%%'  => esc_url( $pdf ),
	);
	$head = '<meta name="robots" content="noindex, nofollow, noarchive">' . strtr( $h[1], $repl );
	$main = strtr( $m[1], $repl );
	$i    = strpos( $s, '</head>' );
	if ( false === $i ) {
		return '';
	}
	$s = substr( $s, 0, $i ) . $head . substr( $s, $i );
	$s = (string) preg_replace_callback(
		'#<main\b.*?</main>#is',
		function () use ( $main ) {
			return $main;
		},
		$s,
		1
	);
	$s = function_exists( 'ucan_fill_placeholders' ) ? ucan_fill_placeholders( $s ) : str_replace( array( '%%UCAN_THEME%%', '%%UCAN_HOME%%' ), array( get_template_directory_uri(), $origin ), $s );
	if ( function_exists( 'ucan_canonical_behaviour' ) ) {
		$s = ucan_canonical_behaviour( $s );
	}
	if ( function_exists( 'ucan_inject_analytics' ) ) {
		$s = ucan_inject_analytics( $s );
	}
	return $s;
}

/* ---------------------------------------------------------------- REST */

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'ucan/v1',
			'/toolkit/submit',
			array(
				'methods'             => 'POST',
				'callback'            => 'ucan_tk_submit',
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'ucan/v1',
			'/toolkit/pair/(?P<code>[A-Za-z0-9]{6,16})',
			array(
				'methods'             => 'GET',
				'callback'            => 'ucan_tk_pair_info',
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'ucan/v1',
			'/toolkit/result/(?P<key>[a-f0-9]{32,64})',
			array(
				'methods'             => 'GET',
				'callback'            => 'ucan_tk_result_view',
				'permission_callback' => '__return_true',
			)
		);
	}
);

/** Per-IP rate limit (only a hash of the IP is kept, for an hour). */
function ucan_tk_limited( $bucket, $max ) {
	$ip   = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '';
	$key  = 'ucan_tk_' . $bucket . '_' . md5( $ip . wp_salt( 'nonce' ) );
	$hits = (int) get_transient( $key );
	if ( $hits >= $max ) {
		return true;
	}
	set_transient( $key, $hits + 1, HOUR_IN_SECONDS );
	return false;
}

function ucan_tk_error( $message, $status ) {
	$r = new WP_REST_Response( array( 'ok' => false, 'error' => $message ), $status );
	$r->header( 'Cache-Control', 'no-store' );
	return $r;
}

function ucan_tk_ok( $body ) {
	$r = new WP_REST_Response( array_merge( array( 'ok' => true ), $body ), 200 );
	$r->header( 'Cache-Control', 'no-store' );
	return $r;
}

function ucan_tk_clean( $v, $max ) {
	$v = sanitize_text_field( (string) $v );
	return function_exists( 'mb_substr' ) ? mb_substr( $v, 0, $max ) : substr( $v, 0, $max );
}

function ucan_tk_clean_long( $v, $max ) {
	$v = sanitize_textarea_field( (string) $v );
	return function_exists( 'mb_substr' ) ? mb_substr( $v, 0, $max ) : substr( $v, 0, $max );
}

function ucan_tk_code() {
	$alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789'; // no 0/O/1/I/L: read aloud or typed safely
	$out      = '';
	for ( $i = 0; $i < 10; $i++ ) {
		$out .= $alphabet[ random_int( 0, strlen( $alphabet ) - 1 ) ];
	}
	return $out;
}

/**
 * Validate the answers for the given sections.
 * Returns array( $clean, $error ).
 */
function ucan_tk_clean_answers( $raw, $sections ) {
	$map   = ucan_tk_sections();
	$clean = array();
	$raw   = is_array( $raw ) ? $raw : array();
	foreach ( $sections as $sid ) {
		$s = $map[ $sid ][2];
		$r = isset( $raw[ $sid ]['r'] ) && is_array( $raw[ $sid ]['r'] ) ? array_values( $raw[ $sid ]['r'] ) : array();
		if ( count( $r ) !== count( $s['statements'] ) ) {
			return array( null, 'Please rate every statement in "' . $s['name'] . '".' );
		}
		$scores = array();
		foreach ( $r as $v ) {
			$v = (int) $v;
			if ( $v < 1 || $v > 5 ) {
				return array( null, 'Please rate every statement in "' . $s['name'] . '".' );
			}
			$scores[] = $v;
		}
		$clean[ $sid ] = array(
			'r'      => $scores,
			'action' => ucan_tk_clean_long( isset( $raw[ $sid ]['action'] ) ? $raw[ $sid ]['action'] : '', 2000 ),
		);
		if ( 0 === strpos( $sid, 'p3' ) ) {
			$clean[ $sid ]['right'] = ucan_tk_clean_long( isset( $raw[ $sid ]['right'] ) ? $raw[ $sid ]['right'] : '', 2000 );
			$clean[ $sid ]['diff']  = ucan_tk_clean_long( isset( $raw[ $sid ]['diff'] ) ? $raw[ $sid ]['diff'] : '', 2000 );
		}
	}
	return array( $clean, null );
}

function ucan_tk_submit( WP_REST_Request $req ) {
	if ( '' !== (string) $req->get_param( 'website' ) ) { // honeypot
		return ucan_tk_error( 'Could not save.', 400 );
	}
	if ( ucan_tk_limited( 'submit', 12 ) ) {
		return ucan_tk_error( 'Too many submissions from this connection. Please try again in an hour.', 429 );
	}

	$org     = ucan_tk_clean( $req->get_param( 'org' ), 200 );
	$person  = ucan_tk_clean( $req->get_param( 'person' ), 200 );
	$email   = strtolower( sanitize_email( (string) $req->get_param( 'email' ) ) );
	$consent = strtolower( (string) $req->get_param( 'consent' ) );
	if ( '' === $org || '' === $person ) {
		return ucan_tk_error( 'Please give your organisation and your name.', 400 );
	}
	if ( ! is_email( $email ) ) {
		return ucan_tk_error( 'Please give a valid email address so we can send you the results.', 400 );
	}
	if ( ! in_array( $consent, array( 'yes', 'on', '1', 'true' ), true ) ) {
		return ucan_tk_error( 'Please tick the consent box to continue.', 400 );
	}

	global $wpdb;
	$table = ucan_tk_table();
	$code  = strtoupper( preg_replace( '/[^A-Za-z0-9]/', '', (string) $req->get_param( 'pair' ) ) );

	if ( '' === $code ) {
		// organisation A: chooses the parts
		$valid    = array_keys( ucan_tk_sections() );
		$sections = array_values( array_unique( array_intersect( (array) $req->get_param( 'sections' ), $valid ) ) );
		$sections = array_values( array_intersect( $valid, $sections ) ); // keep toolkit order
		if ( ! $sections ) {
			return ucan_tk_error( 'Please choose at least one part of the toolkit.', 400 );
		}
		list( $answers, $err ) = ucan_tk_clean_answers( $req->get_param( 'answers' ), $sections );
		if ( $err ) {
			return ucan_tk_error( $err, 400 );
		}
		$key = bin2hex( random_bytes( 16 ) );
		do {
			$code = ucan_tk_code();
		} while ( $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table WHERE pair_code = %s", $code ) ) );

		$ok = $wpdb->insert(
			$table,
			array(
				'pair_code'      => $code,
				'result_key'     => $key,
				'role'           => 'A',
				'org'            => $org,
				'person'         => $person,
				'email'          => $email,
				'partner_org'    => ucan_tk_clean( $req->get_param( 'partner_org' ), 200 ),
				'sections'       => implode( ',', $sections ),
				'answers'        => wp_json_encode( $answers ),
				'data_version'   => ucan_tk_data()['version'],
				'notice_version' => UCAN_TK_NOTICE,
				'created_at'     => current_time( 'mysql', true ),
			)
		);
		if ( ! $ok ) {
			return ucan_tk_error( 'Could not save your answers. Please try again.', 500 );
		}
		$a      = ucan_tk_row( $code, 'A' );
		$result = ucan_tk_result( $a, null );
		ucan_tk_mail_invite( $a );
		return ucan_tk_ok(
			array(
				'role'       => 'A',
				'pair'       => $code,
				'invite_url' => ucan_tk_url( array( 'pair' => $code ) ),
				'result_url' => ucan_tk_url( array( 'results' => $key ) ),
				'result'     => $result,
			)
		);
	}

	// organisation B: answers the parts A chose
	$a = ucan_tk_row( $code, 'A' );
	if ( ! $a ) {
		return ucan_tk_error( 'This partner link is not valid. Please check it with the organisation that sent it.', 404 );
	}
	if ( ucan_tk_row( $code, 'B' ) ) {
		return ucan_tk_error( 'Your partner organisation has already completed this toolkit. Please ask them to start a new one.', 409 );
	}
	$sections = explode( ',', $a->sections );
	list( $answers, $err ) = ucan_tk_clean_answers( $req->get_param( 'answers' ), $sections );
	if ( $err ) {
		return ucan_tk_error( $err, 400 );
	}
	$ok = $wpdb->insert(
		$table,
		array(
			'pair_code'      => $code,
			'result_key'     => $a->result_key,
			'role'           => 'B',
			'org'            => $org,
			'person'         => $person,
			'email'          => $email,
			'partner_org'    => $a->org,
			'sections'       => $a->sections,
			'answers'        => wp_json_encode( $answers ),
			'data_version'   => ucan_tk_data()['version'],
			'notice_version' => UCAN_TK_NOTICE,
			'created_at'     => current_time( 'mysql', true ),
		)
	);
	if ( ! $ok ) { // includes the race where B was saved twice at once
		return ucan_tk_error( 'Could not save your answers. Please try again.', 500 );
	}
	$b      = ucan_tk_row( $code, 'B' );
	$result = ucan_tk_result( $a, $b );
	$sent   = ucan_tk_mail_results( $a, $b, $result );
	return ucan_tk_ok(
		array(
			'role'       => 'B',
			'pair'       => $code,
			'result_url' => ucan_tk_url( array( 'results' => $a->result_key ) ),
			'result'     => $result,
			'emailed'    => $sent,
		)
	);
}

function ucan_tk_pair_info( WP_REST_Request $req ) {
	if ( ucan_tk_limited( 'read', 120 ) ) {
		return ucan_tk_error( 'Too many requests. Please try again later.', 429 );
	}
	$code = strtoupper( (string) $req['code'] );
	$a    = ucan_tk_row( $code, 'A' );
	if ( ! $a ) {
		return ucan_tk_error( 'This partner link is not valid. Please check it with the organisation that sent it.', 404 );
	}
	return ucan_tk_ok(
		array(
			'org'         => $a->org,
			'partner_org' => $a->partner_org,
			'sections'    => explode( ',', $a->sections ),
			'complete'    => (bool) ucan_tk_row( $code, 'B' ),
		)
	);
}

function ucan_tk_result_view( WP_REST_Request $req ) {
	if ( ucan_tk_limited( 'read', 120 ) ) {
		return ucan_tk_error( 'Too many requests. Please try again later.', 429 );
	}
	global $wpdb;
	$rows = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . ucan_tk_table() . ' WHERE result_key = %s ORDER BY role', (string) $req['key'] ) );
	$a    = null;
	$b    = null;
	foreach ( $rows as $r ) {
		if ( 'A' === $r->role ) {
			$a = $r;
		} elseif ( 'B' === $r->role ) {
			$b = $r;
		}
	}
	if ( ! $a ) {
		return ucan_tk_error( 'These results could not be found. They may have been deleted.', 404 );
	}
	return ucan_tk_ok(
		array(
			'pair'       => $a->pair_code,
			'invite_url' => $b ? '' : ucan_tk_url( array( 'pair' => $a->pair_code ) ),
			'result'     => ucan_tk_result( $a, $b ),
		)
	);
}

function ucan_tk_row( $code, $role ) {
	global $wpdb;
	return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . ucan_tk_table() . ' WHERE pair_code = %s AND role = %s', $code, $role ) );
}

function ucan_tk_url( $args ) {
	$origin = function_exists( 'ucan_public_origin' ) ? ucan_public_origin( untrailingslashit( home_url() ) ) : untrailingslashit( home_url() );
	return add_query_arg( $args, $origin . '/' . UCAN_TK_SLUG );
}

/* ------------------------------------------------------------- scoring */

/** 4-5 strong / 3 emerging / 1-2 needs attention, on the average rounded half up. */
function ucan_tk_band( $avg ) {
	if ( null === $avg ) {
		return null;
	}
	return $avg >= 3.5 ? 'g' : ( $avg >= 2.5 ? 'a' : 'r' );
}

function ucan_tk_avg( $nums ) {
	return $nums ? round( array_sum( $nums ) / count( $nums ), 2 ) : null;
}

/** The "What does this tell you?" row that fits a part's pattern of bands. */
function ucan_tk_pattern( $part_id, $bands ) {
	$c = array_count_values( array_filter( $bands ) ) + array( 'g' => 0, 'a' => 0, 'r' => 0 );
	if ( ! $bands || 'p3' === $part_id ) {
		return null;
	}
	if ( 'p2' === $part_id ) {
		if ( $c['r'] > 0 || $c['a'] > $c['g'] ) {
			return 'r';
		}
		return 0 === $c['a'] ? 'g' : 'mix';
	}
	if ( $c['r'] > 0 ) {
		return 'r';
	}
	return 0 === $c['a'] ? 'g' : 'mix';
}

/**
 * The full result for a pair (or for A alone while B has not answered).
 * Numbers and ids only - the page and the emails look the text up in
 * toolkit-data.json. No email addresses: the result is reachable by its key.
 */
function ucan_tk_result( $a, $b ) {
	$data = ucan_tk_data();
	$ansA = json_decode( $a->answers, true );
	$ansB = $b ? json_decode( $b->answers, true ) : null;
	$want = explode( ',', $a->sections );

	$parts   = array();
	$gaps    = array();
	$within1 = 0;
	$pairs   = 0;
	$ranked  = array();
	foreach ( $data['parts'] as $part ) {
		$secs = array();
		foreach ( $part['sections'] as $i => $s ) {
			if ( ! in_array( $s['id'], $want, true ) ) {
				continue;
			}
			$ra    = $ansA[ $s['id'] ]['r'];
			$rb    = $ansB ? $ansB[ $s['id'] ]['r'] : null;
			$stmts = array();
			foreach ( $s['statements'] as $j => $t ) {
				$va  = (int) $ra[ $j ];
				$vb  = $rb ? (int) $rb[ $j ] : null;
				$gap = null === $vb ? null : abs( $va - $vb );
				if ( null !== $gap ) {
					$pairs++;
					if ( $gap <= 1 ) {
						$within1++;
					}
					if ( $gap >= 2 ) {
						$gaps[] = array( 'section' => $s['id'], 'i' => $j, 'a' => $va, 'b' => $vb, 'gap' => $gap );
					}
				}
				$stmts[] = array( 'a' => $va, 'b' => $vb, 'gap' => $gap );
			}
			$avgA = ucan_tk_avg( $ra );
			$avgB = $rb ? ucan_tk_avg( $rb ) : null;
			$avg  = null === $avgB ? $avgA : round( ( $avgA + $avgB ) / 2, 2 );
			$sec  = array(
				'id'      => $s['id'],
				'n'       => $i + 1,
				'avgA'    => $avgA,
				'avgB'    => $avgB,
				'avg'     => $avg,
				'band'    => ucan_tk_band( $avg ),
				'bandA'   => ucan_tk_band( $avgA ),
				'bandB'   => ucan_tk_band( $avgB ),
				'stmts'   => $stmts,
				'actionA' => (string) $ansA[ $s['id'] ]['action'],
				'actionB' => $ansB ? (string) $ansB[ $s['id'] ]['action'] : '',
			);
			if ( 'p3' === $part['id'] ) {
				$sec['rightA'] = (string) $ansA[ $s['id'] ]['right'];
				$sec['diffA']  = (string) $ansA[ $s['id'] ]['diff'];
				$sec['rightB'] = $ansB ? (string) $ansB[ $s['id'] ]['right'] : '';
				$sec['diffB']  = $ansB ? (string) $ansB[ $s['id'] ]['diff'] : '';
			}
			$secs[]   = $sec;
			$ranked[] = array( 'id' => $s['id'], 'avg' => $avg, 'band' => $sec['band'] );
		}
		if ( $secs ) {
			$parts[] = array(
				'id'       => $part['id'],
				'sections' => $secs,
				'pattern'  => ucan_tk_pattern( $part['id'], wp_list_pluck( $secs, 'band' ) ),
			);
		}
	}

	usort(
		$gaps,
		function ( $x, $y ) {
			return $y['gap'] - $x['gap'];
		}
	);
	usort(
		$ranked,
		function ( $x, $y ) {
			return $y['avg'] <=> $x['avg'];
		}
	);
	$strengths = array_values( array_filter( $ranked, function ( $r ) { return 'g' === $r['band']; } ) );
	$attention = array_values( array_reverse( array_filter( $ranked, function ( $r ) { return 'g' !== $r['band']; } ) ) );

	return array(
		'version'  => $data['version'],
		'complete' => (bool) $b,
		'date'     => substr( $b ? $b->created_at : $a->created_at, 0, 10 ),
		'a'        => array( 'org' => $a->org, 'person' => $a->person ),
		'b'        => $b ? array( 'org' => $b->org, 'person' => $b->person ) : array( 'org' => $a->partner_org, 'person' => '' ),
		'parts'    => $parts,
		'summary'  => array(
			'alignment' => $pairs ? (int) round( 100 * $within1 / $pairs ) : null,
			'compared'  => $pairs,
			'gaps'      => array_slice( $gaps, 0, 8 ),
			'gapCount'  => count( $gaps ),
			'strengths' => wp_list_pluck( array_slice( $strengths, 0, 3 ), 'id' ),
			'attention' => wp_list_pluck( array_slice( $attention, 0, 3 ), 'id' ),
		),
	);
}

/* --------------------------------------------------------------- email */

function ucan_tk_band_label( $part_id, $band ) {
	foreach ( ucan_tk_data()['parts'] as $p ) {
		if ( $p['id'] === $part_id ) {
			return isset( $p['snapshot']['bands'][ $band ] ) ? ucwords( strtolower( $p['snapshot']['bands'][ $band ] ) ) : '';
		}
	}
	return '';
}

function ucan_tk_mail( $to, $subject, $html ) {
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'Reply-To: U-CAN <' . UCAN_TK_COPY_TO . '>',
	);
	return (bool) wp_mail( $to, $subject, $html, $headers );
}

function ucan_tk_mail_wrap( $title, $body ) {
	return '<!doctype html><html><body style="margin:0;background:#FBFAF6;font-family:Arial,Helvetica,sans-serif;color:#222120">'
		. '<div style="max-width:640px;margin:0 auto;background:#ffffff">'
		. '<div style="background:#0E5348;padding:28px 28px 24px;color:#ffffff">'
		. '<div style="font-size:12px;letter-spacing:.14em;font-weight:bold;color:#CDDE71">U-CAN COLLABORATION TOOLKIT</div>'
		. '<div style="font-size:24px;font-weight:bold;margin-top:8px;line-height:1.25">' . esc_html( $title ) . '</div></div>'
		. '<div style="padding:24px 28px 28px;font-size:15px;line-height:1.6">' . $body . '</div>'
		. '<div style="padding:18px 28px;background:#E9F5F2;font-size:12px;color:#57564F;line-height:1.5">Urban Collective Action Network (U-CAN) · <a href="https://www.urban.org.in" style="color:#177A69">www.urban.org.in</a><br>You are receiving this because you completed the U-CAN Collaboration Toolkit. To have your answers deleted, write to <a href="mailto:privacy@urban.org.in" style="color:#177A69">privacy@urban.org.in</a>.</div>'
		. '</div></body></html>';
}

function ucan_tk_button( $url, $label ) {
	return '<p style="margin:22px 0"><a href="' . esc_url( $url ) . '" style="display:inline-block;background:#0E5348;color:#ffffff;text-decoration:none;font-weight:bold;padding:13px 22px;border-radius:2px">' . esc_html( $label ) . '</a></p>';
}

/** Summary table: one row per section, both organisations' averages and the band. */
function ucan_tk_mail_summary( $result ) {
	$map  = ucan_tk_sections();
	$dot  = array( 'g' => '#2E9E5B', 'a' => '#F0A229', 'r' => '#D9534F' );
	$html = '';
	foreach ( $result['parts'] as $p ) {
		$part  = $map[ $p['sections'][0]['id'] ][0];
		$html .= '<h3 style="font-size:16px;margin:24px 0 8px;color:#0E5348">' . esc_html( $part['snapshot']['title'] ) . '</h3>';
		$html .= '<table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;font-size:14px">';
		$html .= '<tr><th align="left" style="padding:8px 6px;border-bottom:2px solid #0E5348;font-size:11px;letter-spacing:.08em;color:#0E5348">' . esc_html( $part['snapshot']['col'] ) . '</th>'
			. '<th style="padding:8px 6px;border-bottom:2px solid #0E5348;font-size:11px;color:#0E5348">' . esc_html( $result['a']['org'] ) . '</th>'
			. ( $result['complete'] ? '<th style="padding:8px 6px;border-bottom:2px solid #0E5348;font-size:11px;color:#0E5348">' . esc_html( $result['b']['org'] ) . '</th>' : '' )
			. '<th align="left" style="padding:8px 6px;border-bottom:2px solid #0E5348;font-size:11px;color:#0E5348">RESULT</th></tr>';
		foreach ( $p['sections'] as $s ) {
			$name  = $map[ $s['id'] ][2]['name'];
			$html .= '<tr><td style="padding:9px 6px;border-bottom:1px solid #DCEAE6">' . esc_html( $name ) . '</td>'
				. '<td align="center" style="padding:9px 6px;border-bottom:1px solid #DCEAE6">' . esc_html( number_format( $s['avgA'], 1 ) ) . '</td>'
				. ( $result['complete'] ? '<td align="center" style="padding:9px 6px;border-bottom:1px solid #DCEAE6">' . esc_html( number_format( $s['avgB'], 1 ) ) . '</td>' : '' )
				. '<td style="padding:9px 6px;border-bottom:1px solid #DCEAE6;white-space:nowrap"><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:' . $dot[ $s['band'] ] . ';margin-right:6px"></span>' . esc_html( ucan_tk_band_label( $p['id'], $s['band'] ) ) . '</td></tr>';
		}
		$html .= '</table>';
		if ( $p['pattern'] ) {
			foreach ( $part['snapshot']['tell']['rows'] as $row ) {
				if ( $row['key'] === $p['pattern'] ) {
					$html .= '<p style="margin:10px 0 0;padding:10px 12px;background:#E9F5F2;font-size:14px"><strong>What does this tell you?</strong> ' . esc_html( $row['text'] ) . '</p>';
				}
			}
		}
	}
	if ( $result['complete'] && $result['summary']['gaps'] ) {
		$html .= '<h3 style="font-size:16px;margin:24px 0 8px;color:#0E5348">Where your organisations differed most</h3><ul style="padding-left:18px;margin:0">';
		foreach ( array_slice( $result['summary']['gaps'], 0, 5 ) as $g ) {
			$sec   = $map[ $g['section'] ][2];
			$html .= '<li style="margin-bottom:8px">' . esc_html( $sec['statements'][ $g['i'] ] ) . ' <span style="color:#57564F">(' . esc_html( $sec['name'] ) . ': ' . (int) $g['a'] . ' vs ' . (int) $g['b'] . ')</span></li>';
		}
		$html .= '</ul>';
	}
	return $html;
}

/** Organisation A: thanks + the partner link to forward. */
function ucan_tk_mail_invite( $a ) {
	$invite = ucan_tk_url( array( 'pair' => $a->pair_code ) );
	$body   = '<p>Dear ' . esc_html( $a->person ) . ',</p>'
		. '<p>Thank you for completing the U-CAN Collaboration Toolkit for <strong>' . esc_html( $a->org ) . '</strong>. Your answers are saved.</p>'
		. '<p>The toolkit works best when both organisations rate the statements independently. Please send this link to your partner organisation' . ( $a->partner_org ? ' (<strong>' . esc_html( $a->partner_org ) . '</strong>)' : '' ) . ':</p>'
		. '<p style="padding:12px 14px;background:#E9F5F2;word-break:break-all"><a href="' . esc_url( $invite ) . '" style="color:#0E5348">' . esc_html( $invite ) . '</a></p>'
		. '<p>As soon as they finish, we will email the joint results to both of you. You can check progress at any time here:</p>'
		. ucan_tk_button( ucan_tk_url( array( 'results' => $a->result_key ) ), 'View your results' );
	return ucan_tk_mail( $a->email, 'Your U-CAN Collaboration Toolkit: share this link with your partner', ucan_tk_mail_wrap( 'Thank you, ' . $a->org, $body ) );
}

/** Both organisations get the joint result; U-CAN gets a copy with contacts. */
function ucan_tk_mail_results( $a, $b, $result ) {
	global $wpdb;
	$url     = ucan_tk_url( array( 'results' => $a->result_key ) );
	$summary = ucan_tk_mail_summary( $result );
	$align   = null === $result['summary']['alignment'] ? '' : '<p>Your organisations gave answers within one point of each other on <strong>' . (int) $result['summary']['alignment'] . '%</strong> of the statements.</p>';
	$title   = $a->org . ' & ' . $b->org;
	$sent    = array();
	foreach ( array( 'a' => $a, 'b' => $b ) as $k => $row ) {
		$body       = '<p>Dear ' . esc_html( $row->person ) . ',</p>'
			. '<p>Both <strong>' . esc_html( $a->org ) . '</strong> and <strong>' . esc_html( $b->org ) . '</strong> have now completed the U-CAN Collaboration Toolkit. Here is your joint result.</p>'
			. $align . $summary
			. '<p style="margin-top:22px">The full results page shows every statement side by side, the questions to discuss together, the actions each organisation suggested, and a PDF download.</p>'
			. ucan_tk_button( $url, 'Open your full results' );
		$sent[ $k ] = ucan_tk_mail( $row->email, 'Your U-CAN Collaboration Toolkit results: ' . $title, ucan_tk_mail_wrap( $title, $body ) );
	}
	$copy          = '<p>A new pair of organisations has completed the Collaboration Toolkit.</p>'
		. '<table role="presentation" style="font-size:14px;border-collapse:collapse">'
		. '<tr><td style="padding:4px 12px 4px 0;color:#57564F">Organisation A</td><td>' . esc_html( $a->org ) . ' · ' . esc_html( $a->person ) . ' · ' . esc_html( $a->email ) . '</td></tr>'
		. '<tr><td style="padding:4px 12px 4px 0;color:#57564F">Organisation B</td><td>' . esc_html( $b->org ) . ' · ' . esc_html( $b->person ) . ' · ' . esc_html( $b->email ) . '</td></tr>'
		. '</table>' . $align . $summary
		. ucan_tk_button( $url, 'Open the full results' )
		. '<p style="font-size:13px;color:#57564F">Every submission is also listed in wp-admin under Form submissions › Collaboration Toolkit.</p>';
	$sent['connect'] = ucan_tk_mail( UCAN_TK_COPY_TO, '[Toolkit] ' . $title . ' completed the Collaboration Toolkit', ucan_tk_mail_wrap( $title, $copy ) );
	if ( $sent['a'] || $sent['b'] ) {
		$wpdb->update( ucan_tk_table(), array( 'emailed_at' => current_time( 'mysql', true ) ), array( 'pair_code' => $a->pair_code ) );
	}
	return $sent;
}

/* --------------------------------------------------------------- admin */

add_action(
	'admin_menu',
	function () {
		add_submenu_page( 'ucan-subscribers', 'Collaboration Toolkit', 'Collaboration Toolkit', 'manage_options', 'ucan-toolkit', 'ucan_tk_admin' );
	},
	11
);

/** Every pair: A's row joined to B's (if any). */
function ucan_tk_admin_rows( $search, $status, $order, $limit, $offset ) {
	global $wpdb;
	$t     = ucan_tk_table();
	$where = array( "a.role = 'A'" );
	if ( '' !== $search ) {
		$like    = '%' . $wpdb->esc_like( $search ) . '%';
		$where[] = $wpdb->prepare( '(a.org LIKE %s OR a.person LIKE %s OR a.email LIKE %s OR b.org LIKE %s OR b.person LIKE %s OR b.email LIKE %s OR a.pair_code LIKE %s)', $like, $like, $like, $like, $like, $like, $like );
	}
	if ( 'complete' === $status ) {
		$where[] = 'b.id IS NOT NULL';
	} elseif ( 'waiting' === $status ) {
		$where[] = 'b.id IS NULL';
	}
	$orders = array(
		'new' => 'a.created_at DESC',
		'old' => 'a.created_at ASC',
		'org' => 'a.org ASC, a.created_at DESC',
	);
	$sql   = "FROM $t a LEFT JOIN $t b ON b.pair_code = a.pair_code AND b.role = 'B' WHERE " . implode( ' AND ', $where );
	$total = (int) $wpdb->get_var( "SELECT COUNT(*) $sql" );
	$rows  = $wpdb->get_results(
		"SELECT a.id AS a_id, b.id AS b_id $sql ORDER BY " . ( isset( $orders[ $order ] ) ? $orders[ $order ] : $orders['new'] )
		. ( $limit ? $wpdb->prepare( ' LIMIT %d OFFSET %d', $limit, $offset ) : '' )
	);
	$out = array();
	foreach ( $rows as $r ) {
		$a     = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $t WHERE id = %d", $r->a_id ) );
		$b     = $r->b_id ? $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $t WHERE id = %d", $r->b_id ) ) : null;
		$out[] = array( $a, $b, ucan_tk_result( $a, $b ) );
	}
	return array( $total, $out );
}

function ucan_tk_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
	$status = isset( $_GET['status'] ) ? sanitize_key( $_GET['status'] ) : '';
	$order  = isset( $_GET['order'] ) ? sanitize_key( $_GET['order'] ) : 'new';
	$paged  = max( 1, isset( $_GET['paged'] ) ? (int) $_GET['paged'] : 1 );
	$per    = 30;
	list( $total, $rows ) = ucan_tk_admin_rows( $search, $status, $order, $per, ( $paged - 1 ) * $per );
	$export = wp_nonce_url( admin_url( 'admin-post.php?action=ucan_tk_export' ), 'ucan_tk_export' );
	$map    = ucan_tk_sections();
	$dot    = array( 'g' => '#2E9E5B', 'a' => '#F0A229', 'r' => '#D9534F' );
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline">Collaboration Toolkit</h1>
		<a href="<?php echo esc_url( $export ); ?>" class="page-title-action">Export CSV</a>
		<a href="<?php echo esc_url( ucan_tk_url( array() ) ); ?>" class="page-title-action" target="_blank" rel="noopener">Open the toolkit</a>
		<?php if ( isset( $_GET['deleted'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>Submission deleted.</p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['resent'] ) ) : ?>
			<div class="notice notice-<?php echo '1' === $_GET['resent'] ? 'success' : 'error'; ?> is-dismissible"><p><?php echo '1' === $_GET['resent'] ? 'Results emailed again.' : 'The email could not be sent. Check the site\'s mail settings (an SMTP plugin is recommended).'; ?></p></div>
		<?php endif; ?>
		<p><?php echo esc_html( number_format_i18n( $total ) ); ?> organisation pair(s). Organisation A starts the toolkit and sends a link to organisation B; the joint result is emailed to both and to <?php echo esc_html( UCAN_TK_COPY_TO ); ?> when B finishes. Deleting a pair erases both organisations' answers and contact details (use this for DPDP erasure requests).</p>
		<form method="get" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin:12px 0">
			<input type="hidden" name="page" value="ucan-toolkit">
			<input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="Organisation, name, email or code">
			<select name="status">
				<option value="">All</option>
				<option value="complete" <?php selected( $status, 'complete' ); ?>>Both completed</option>
				<option value="waiting" <?php selected( $status, 'waiting' ); ?>>Waiting for partner</option>
			</select>
			<select name="order">
				<option value="new" <?php selected( $order, 'new' ); ?>>Newest first</option>
				<option value="old" <?php selected( $order, 'old' ); ?>>Oldest first</option>
				<option value="org" <?php selected( $order, 'org' ); ?>>Organisation A–Z</option>
			</select>
			<input type="submit" class="button" value="Filter">
		</form>
		<table class="widefat striped">
			<thead><tr><th>Started (UTC)</th><th>Status</th><th>Organisation A</th><th>Organisation B</th><th>Parts</th><th>Result by section</th><th>Agreement</th><th></th></tr></thead>
			<tbody>
			<?php if ( ! $rows ) : ?>
				<tr><td colspan="8">No submissions yet.</td></tr>
			<?php endif; ?>
			<?php foreach ( $rows as $row ) : ?>
				<?php
				list( $a, $b, $res ) = $row;
				$code  = $a->pair_code;
				$del   = wp_nonce_url( admin_url( 'admin-post.php?action=ucan_tk_delete&pair=' . rawurlencode( $code ) ), 'ucan_tk_delete_' . $code );
				$again = wp_nonce_url( admin_url( 'admin-post.php?action=ucan_tk_resend&pair=' . rawurlencode( $code ) ), 'ucan_tk_resend_' . $code );
				$parts = array();
				foreach ( $res['parts'] as $p ) {
					$parts[] = strtoupper( str_replace( 'p', 'Part ', $p['id'] ) );
				}
				?>
				<tr>
					<td><?php echo esc_html( $a->created_at ); ?><br><code><?php echo esc_html( $code ); ?></code></td>
					<td><?php echo $b ? '<strong>Both completed</strong><br>' . esc_html( $b->created_at ) . ( $b->emailed_at ? '<br>Emailed ' . esc_html( $b->emailed_at ) : '<br><span style="color:#b32d2e">Not emailed</span>' ) : 'Waiting for partner'; ?></td>
					<td><strong><?php echo esc_html( $a->org ); ?></strong><br><?php echo esc_html( $a->person ); ?><br><a href="mailto:<?php echo esc_attr( $a->email ); ?>"><?php echo esc_html( $a->email ); ?></a></td>
					<td><?php if ( $b ) : ?><strong><?php echo esc_html( $b->org ); ?></strong><br><?php echo esc_html( $b->person ); ?><br><a href="mailto:<?php echo esc_attr( $b->email ); ?>"><?php echo esc_html( $b->email ); ?></a><?php else : ?><em><?php echo esc_html( $a->partner_org ? $a->partner_org : '—' ); ?></em><?php endif; ?></td>
					<td><?php echo esc_html( implode( ', ', array_map( 'ucwords', array_map( 'strtolower', $parts ) ) ) ); ?></td>
					<td>
						<?php foreach ( $res['parts'] as $p ) : ?>
							<?php foreach ( $p['sections'] as $s ) : ?>
								<div style="white-space:nowrap"><span style="display:inline-block;width:9px;height:9px;border-radius:50%;background:<?php echo esc_attr( $dot[ $s['band'] ] ); ?>"></span> <?php echo esc_html( $map[ $s['id'] ][2]['name'] ); ?> <span style="color:#646970"><?php echo esc_html( number_format( $s['avgA'], 1 ) . ( null !== $s['avgB'] ? ' / ' . number_format( $s['avgB'], 1 ) : '' ) ); ?></span></div>
							<?php endforeach; ?>
						<?php endforeach; ?>
					</td>
					<td><?php echo null === $res['summary']['alignment'] ? '—' : esc_html( $res['summary']['alignment'] . '%' ); ?></td>
					<td style="white-space:nowrap">
						<a href="<?php echo esc_url( ucan_tk_url( array( 'results' => $a->result_key ) ) ); ?>" target="_blank" rel="noopener">View results</a><br>
						<?php if ( $b ) : ?><a href="<?php echo esc_url( $again ); ?>">Resend email</a><br><?php endif; ?>
						<a href="<?php echo esc_url( $del ); ?>" onclick="return confirm('Permanently delete both organisations\' answers and contact details?');" style="color:#b32d2e">Delete</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php
		$pages = (int) ceil( $total / $per );
		if ( $pages > 1 ) {
			echo '<p>' . paginate_links(
				array(
					'base'    => add_query_arg( 'paged', '%#%' ),
					'format'  => '',
					'current' => $paged,
					'total'   => $pages,
				)
			) . '</p>';
		}
		?>
		<p style="color:#646970">Scores are each organisation's average for that section (1–5). Agreement is the share of statements on which the two organisations' ratings were within one point.</p>
	</div>
	<?php
}

add_action(
	'admin_post_ucan_tk_export',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Not allowed.' );
		}
		check_admin_referer( 'ucan_tk_export' );
		list( , $rows ) = ucan_tk_admin_rows( '', '', 'new', 0, 0 );
		$map            = ucan_tk_sections();
		nocache_headers();
		header( 'Content-Type: text/csv; charset=UTF-8' );
		header( 'Content-Disposition: attachment; filename="ucan-collaboration-toolkit-' . gmdate( 'Y-m-d' ) . '.csv"' );
		$out  = fopen( 'php://output', 'w' );
		$head = array( 'pair_code', 'started_utc', 'completed_utc', 'emailed_utc', 'org_a', 'person_a', 'email_a', 'org_b', 'person_b', 'email_b', 'agreement_pct' );
		foreach ( $map as $sid => $m ) {
			$head[] = $m[2]['name'] . ' (A)';
			$head[] = $m[2]['name'] . ' (B)';
			$head[] = $m[2]['name'] . ' (result)';
		}
		fputcsv( $out, $head );
		foreach ( $rows as $row ) {
			list( $a, $b, $res ) = $row;
			$line                = array( $a->pair_code, $a->created_at, $b ? $b->created_at : '', $b ? (string) $b->emailed_at : '', $a->org, $a->person, $a->email, $b ? $b->org : $a->partner_org, $b ? $b->person : '', $b ? $b->email : '', null === $res['summary']['alignment'] ? '' : $res['summary']['alignment'] );
			$by                  = array();
			foreach ( $res['parts'] as $p ) {
				foreach ( $p['sections'] as $s ) {
					$by[ $s['id'] ] = array( $p['id'], $s );
				}
			}
			foreach ( $map as $sid => $m ) {
				if ( isset( $by[ $sid ] ) ) {
					list( $pid, $s ) = $by[ $sid ];
					array_push( $line, $s['avgA'], null === $s['avgB'] ? '' : $s['avgB'], ucan_tk_band_label( $pid, $s['band'] ) );
				} else {
					array_push( $line, '', '', '' );
				}
			}
			// stop spreadsheet apps treating a value as a formula
			$line = array_map(
				function ( $v ) {
					return preg_match( '/^[=+\-@]/', (string) $v ) ? "'" . $v : $v;
				},
				$line
			);
			fputcsv( $out, $line );
		}
		fclose( $out );
		exit;
	}
);

add_action(
	'admin_post_ucan_tk_delete',
	function () {
		$code = isset( $_GET['pair'] ) ? preg_replace( '/[^A-Z0-9]/', '', strtoupper( (string) $_GET['pair'] ) ) : '';
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Not allowed.' );
		}
		check_admin_referer( 'ucan_tk_delete_' . $code );
		global $wpdb;
		$wpdb->delete( ucan_tk_table(), array( 'pair_code' => $code ) );
		wp_safe_redirect( admin_url( 'admin.php?page=ucan-toolkit&deleted=1' ) );
		exit;
	}
);

add_action(
	'admin_post_ucan_tk_resend',
	function () {
		$code = isset( $_GET['pair'] ) ? preg_replace( '/[^A-Z0-9]/', '', strtoupper( (string) $_GET['pair'] ) ) : '';
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Not allowed.' );
		}
		check_admin_referer( 'ucan_tk_resend_' . $code );
		$a  = ucan_tk_row( $code, 'A' );
		$b  = ucan_tk_row( $code, 'B' );
		$ok = false;
		if ( $a && $b ) {
			$sent = ucan_tk_mail_results( $a, $b, ucan_tk_result( $a, $b ) );
			$ok   = $sent['a'] || $sent['b'];
		}
		wp_safe_redirect( admin_url( 'admin.php?page=ucan-toolkit&resent=' . ( $ok ? '1' : '0' ) ) );
		exit;
	}
);
