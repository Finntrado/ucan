<?php
/**
 * One canonical address, HTTPS only, and a crawler policy.
 *
 * Before this, every page answered on four URLs (http/https x apex/www) with no
 * redirects, canonicals said https://urban.org.in, WordPress core 301'd
 * www/robots.txt back to the apex, plain-http wp-login.php served the login
 * form, and /?author=1 revealed the "admin" username.
 *
 * Rules (production hosts urban.org.in / www.urban.org.in only - dev hosts such
 * as LocalWP are left alone, except that canonical URLs are ALWAYS the www ones):
 *  1. Public pages: anything on http or on the apex 301s (308 for non-GET) in a
 *     single hop to https://www.urban.org.in<same path>.
 *  2. Admin, login, REST, cron, xmlrpc: HTTPS is forced but the HOST is never
 *     changed - login cookies and API clients (incl. the WPVibe connector) are
 *     tied to the host WordPress is configured for, and a redirected POST loses
 *     its body.
 *  3. <link rel=canonical>, hreflang, og:url, og:image, twitter:image and the
 *     JSON-LD graph always output https://www.urban.org.in, whatever host served
 *     the request; the sitemap and robots.txt do too.
 *  4. robots.txt: every crawler, AI and answer-engine bots named explicitly, all
 *     public pages allowed; same protections as before (admin, login, REST,
 *     search). If "Discourage search engines" is ticked in wp-admin, WordPress's
 *     own disallow-all is left untouched.
 *  5. /?author=N returns 404 and /author/* returns 410 (no username disclosure).
 *
 * robots.txt is a courtesy for crawlers, not a security control: nothing
 * sensitive is listed in it, and real protection is authentication + HTTPS.
 */

defined( 'ABSPATH' ) || exit;

const UCAN_CANON_HOST   = 'www.urban.org.in';
const UCAN_CANON_ORIGIN = 'https://www.urban.org.in';
const UCAN_APEX_HOST    = 'urban.org.in';

function ucan_request_host() {
	$h = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( (string) $_SERVER['HTTP_HOST'] ) : '';
	$h = preg_replace( '/:\d+$/', '', $h );
	return preg_match( '/^[a-z0-9.-]+$/', $h ) ? $h : '';
}

function ucan_is_prod_host() {
	$h = ucan_request_host();
	return UCAN_CANON_HOST === $h || UCAN_APEX_HOST === $h;
}

function ucan_request_is_https() {
	if ( is_ssl() ) {
		return true;
	}
	if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) {
		$p = explode( ',', (string) $_SERVER['HTTP_X_FORWARDED_PROTO'] );
		if ( 'https' === strtolower( trim( $p[0] ) ) ) {
			return true;
		}
	}
	return isset( $_SERVER['HTTP_CF_VISITOR'] ) && false !== strpos( (string) $_SERVER['HTTP_CF_VISITOR'], '"https"' );
}

/** Origin the page's own links/assets should use: www on production, else the dev host. */
function ucan_public_origin( $home ) {
	return ucan_is_prod_host() ? UCAN_CANON_ORIGIN : $home;
}

/** Same URL, on $origin (used for theme assets and the REST endpoint). */
function ucan_on_origin( $url, $origin ) {
	return ucan_is_prod_host() ? preg_replace( '#^https?://[^/]+#i', $origin, $url ) : $url;
}

function ucan_is_backend_uri( $uri ) {
	$path = (string) wp_parse_url( $uri, PHP_URL_PATH );
	return (bool) preg_match( '#^/(wp-admin|wp-login\.php|wp-json|wp-cron\.php|xmlrpc\.php|wp-comments-post\.php)(/|$)#i', $path )
		|| false !== strpos( (string) wp_parse_url( $uri, PHP_URL_QUERY ), 'rest_route=' );
}

function ucan_redirect( $url, $code ) {
	if ( ucan_request_is_https() ) {
		header( 'Strict-Transport-Security: max-age=63072000; includeSubDomains' );
	}
	header( 'Cache-Control: public, max-age=3600' );
	header( 'Location: ' . $url, true, $code );
	exit;
}

/** 1-3: HTTPS everywhere, www for public pages, author enumeration closed. */
function ucan_enforce_https_and_host() {
	if ( ( defined( 'WP_CLI' ) && WP_CLI ) || 'cli' === PHP_SAPI || wp_doing_cron() || ! ucan_is_prod_host() ) {
		return;
	}
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '/';
	if ( '' === $uri || '/' !== $uri[0] || preg_match( '/[\r\n]/', $uri ) ) {
		$uri = '/';
	}
	$method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( (string) $_SERVER['REQUEST_METHOD'] ) : 'GET';
	$code   = in_array( $method, array( 'GET', 'HEAD' ), true ) ? 301 : 308; // 308 keeps a POST a POST
	$host   = ucan_request_host();
	$https  = ucan_request_is_https();

	if ( ucan_is_backend_uri( $uri ) ) {
		if ( ! $https ) {
			ucan_redirect( 'https://' . $host . $uri, $code );
		}
		return;
	}
	if ( ! $https || UCAN_CANON_HOST !== $host ) {
		ucan_redirect( UCAN_CANON_ORIGIN . $uri, $code );
	}
	// no username disclosure: /?author=1 and /author/<login>/
	$path = (string) wp_parse_url( $uri, PHP_URL_PATH );
	if ( isset( $_GET['author'] ) ) {
		status_header( 404 );
		nocache_headers();
		exit;
	}
	if ( 0 === stripos( $path, '/author/' ) ) { // author archives were removed for good
		if ( function_exists( 'ucan_serve_error_page' ) ) {
			ucan_serve_error_page( 410 );
		}
		status_header( 410 );
		nocache_headers();
		exit;
	}
}
add_action( 'init', 'ucan_enforce_https_and_host', 0 );

/** wp_safe_redirect() (trailing-slash and old-URL 301s) refuses hosts it does not know - allow ours. */
add_filter(
	'allowed_redirect_hosts',
	function ( $hosts ) {
		return array_merge( (array) $hosts, array( UCAN_CANON_HOST, UCAN_APEX_HOST ) );
	}
);

/** WordPress's own canonical redirects would send www/robots.txt back to the apex. */
add_filter(
	'redirect_canonical',
	function ( $redirect ) {
		return ucan_is_prod_host() ? false : $redirect;
	},
	1
);

/** HSTS on every HTTPS response, not just the static pages (login, REST, ...). */
add_action(
	'send_headers',
	function () {
		if ( ucan_is_prod_host() && ucan_request_is_https() ) {
			header( 'Strict-Transport-Security: max-age=63072000; includeSubDomains' );
		}
	}
);

/**
 * 3: force the www origin in every "identity" URL of a served page: canonical,
 * hreflang, og:url, og:image, twitter:image and the JSON-LD graph. Also strips
 * any query string / fragment from the canonical.
 */
function ucan_canonicalise_head( $html, $from_origin ) {
	$fix = function ( $url ) use ( $from_origin ) {
		$url = html_entity_decode( $url, ENT_QUOTES );
		if ( '' !== $from_origin && 0 === strpos( $url, $from_origin ) ) {
			$url = UCAN_CANON_ORIGIN . substr( $url, strlen( $from_origin ) );
		}
		return preg_replace( '#^https?://(?:www\.)?urban\.org\.in#i', UCAN_CANON_ORIGIN, $url );
	};
	$html = preg_replace_callback(
		'#(<link\s+rel="canonical"\s+href=")([^"]*)(")#i',
		function ( $m ) use ( $fix ) {
			$u = preg_replace( '/[?#].*$/', '', $fix( $m[2] ) );
			return $m[1] . esc_attr( $u ) . $m[3];
		},
		$html
	);
	$html = preg_replace_callback(
		'#(<link\s+rel="alternate"\s+hreflang="[^"]*"\s+href=")([^"]*)(")#i',
		function ( $m ) use ( $fix ) {
			return $m[1] . esc_attr( $fix( $m[2] ) ) . $m[3];
		},
		$html
	);
	$html = preg_replace_callback(
		'#(<meta\s+(?:property="og:(?:url|image)"|name="twitter:image")\s+content=")([^"]*)(")#i',
		function ( $m ) use ( $fix ) {
			return $m[1] . esc_attr( $fix( $m[2] ) ) . $m[3];
		},
		$html
	);
	return preg_replace_callback(
		'#(<script\s+type="application/ld\+json"[^>]*>)(.*?)(</script>)#is',
		function ( $m ) use ( $from_origin ) {
			$b = $m[2]; // our pages never escape slashes in JSON-LD, so plain URLs
			if ( '' !== $from_origin ) {
				$b = str_replace( $from_origin, UCAN_CANON_ORIGIN, $b );
			}
			$b = preg_replace( '#https?://(?:www\.)?urban\.org\.in#i', UCAN_CANON_ORIGIN, $b );
			return $m[1] . $b . $m[3];
		},
		$html
	);
}

/** 4: robots.txt */
add_filter( 'robots_txt', 'ucan_robots_txt', 99, 2 );
function ucan_robots_txt( $output, $public ) {
	if ( ! $public ) {
		return $output; // never override an explicit "discourage search engines"
	}
	$rules = "Allow: /\n"
		. "Disallow: /wp-admin/\n"
		. "Allow: /wp-admin/admin-ajax.php\n"
		. "Disallow: /wp-login.php\n"
		. "Disallow: /wp-json/\n"
		. "Disallow: /*?s=\n"
		. "Disallow: /*&s=\n";
	$ai = array(
		'GPTBot', 'OAI-SearchBot', 'ChatGPT-User', 'ClaudeBot', 'Claude-SearchBot', 'Claude-User',
		'PerplexityBot', 'Perplexity-User', 'Google-Extended', 'Applebot', 'Applebot-Extended',
		'Amazonbot', 'CCBot', 'Meta-ExternalAgent', 'DuckAssistBot', 'cohere-ai',
	);
	$out  = "User-agent: *\n" . $rules . "\n";
	$out .= "# Search, answer-engine and AI crawlers are welcome on every public page.\n";
	foreach ( $ai as $ua ) {
		$out .= 'User-agent: ' . $ua . "\n";
	}
	$out .= $rules . "\n";
	$out .= 'Sitemap: ' . UCAN_CANON_ORIGIN . "/sitemap.xml\n";
	return $out;
}


/**
 * 6: /llms.txt (and /llms-full.txt when present) - a plain-Markdown map of the site for AI
 * models, generated by _scripts/seo/build_llms_txt.py. The theme's router only serves
 * extensionless page URLs, so a .txt file needs an explicit route.
 */
add_action( 'template_redirect', 'ucan_serve_llms_txt', -1 );
function ucan_serve_llms_txt() {
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '/';
	$path = trim( (string) wp_parse_url( $uri, PHP_URL_PATH ), '/' );
	if ( 'llms.txt' !== $path && 'llms-full.txt' !== $path ) {
		return;
	}
	$file = get_template_directory() . '/' . $path;
	if ( ! is_file( $file ) ) {
		return;
	}
	status_header( 200 );
	header( 'Content-Type: text/plain; charset=UTF-8' );
	header( 'Cache-Control: public, max-age=3600' );
	header( 'X-Content-Type-Options: nosniff' );
	readfile( $file );
	exit;
}
