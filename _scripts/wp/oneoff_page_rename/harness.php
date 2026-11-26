<?php
// usage: php harness.php <snippet.php> <fixture_root> <mode: ok|fail> <old> <new> <repo_pages_dir>
// Runs the real snippet against a fixture of the live theme and checks the result.
error_reporting( E_ALL );
list( , $snippet, $root, $mode, $old, $new, $repo_pages ) = $argv;

define( 'ABSPATH', $root . '/' );
define( 'WP_CONTENT_DIR', $root . '/wp-content' );
$GLOBALS['opts'] = array(); $GLOBALS['trans'] = array(); $GLOBALS['hooks'] = array();
function add_action( $h, $cb, $p = 10 ) { $GLOBALS['hooks'][ $h ][] = $cb; }
function current_user_can( $c ) { return true; }
function get_option( $k, $d = false ) { return isset( $GLOBALS['opts'][ $k ] ) ? $GLOBALS['opts'][ $k ] : $d; }
function update_option( $k, $v, $a = null ) { $GLOBALS['opts'][ $k ] = $v; return true; }
function get_transient( $k ) { return isset( $GLOBALS['trans'][ $k ] ) ? $GLOBALS['trans'][ $k ] : false; }
function set_transient( $k, $v, $t = 0 ) { $GLOBALS['trans'][ $k ] = $v; return true; }
function delete_transient( $k ) { unset( $GLOBALS['trans'][ $k ] ); return true; }
function untrailingslashit( $s ) { return rtrim( $s, '/\\' ); }
function get_template_directory() { return WP_CONTENT_DIR . '/themes/ucan'; }
function wp_mkdir_p( $d ) { return is_dir( $d ) || mkdir( $d, 0755, true ); }
function esc_html( $s ) { return htmlspecialchars( $s, ENT_QUOTES ); }

$theme = WP_CONTENT_DIR . '/themes/ucan';
$before = array();
$it = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $theme, FilesystemIterator::SKIP_DOTS ) );
foreach ( $it as $f ) { if ( $f->isFile() ) { $before[ $f->getPathname() ] = file_get_contents( $f->getPathname() ); } }

eval( file_get_contents( $snippet ) );
foreach ( $GLOBALS['hooks']['admin_init'] as $cb ) { $cb(); }
ob_start();
foreach ( isset( $GLOBALS['hooks']['admin_notices'] ) ? $GLOBALS['hooks']['admin_notices'] : array() as $cb ) { $cb(); }
$notice = trim( strip_tags( ob_get_clean() ) );

$fails = 0;
function chk( $name, $ok ) { global $fails; printf( "  %-66s %s\n", $name, $ok ? 'ok' : 'FAIL' ); if ( ! $ok ) { $fails++; } }
echo "notice: " . substr( $notice, 0, 220 ) . "\n";
$key = str_replace( '-', '_', $new );

if ( 'ok' === $mode ) {
	chk( 'success + done flag', 0 === strpos( get_option( "ucan_rename_{$key}_log" ), 'OK' ) && get_option( "ucan_rename_{$key}_done" ) );
	chk( 'new page exists, old page removed', is_file( "$theme/pages/$new.html" ) && ! is_file( "$theme/pages/$old.html" ) );
	$q = preg_quote( $old, '~' ); $left = 0;
	foreach ( glob( "$theme/pages/*.html" ) as $f ) {
		$c = file_get_contents( $f );
		if ( preg_match( '~%%UCAN_HOME%%/' . $q . '(?![a-z0-9_-])~', $c ) || preg_match( '~href="/' . $q . '(?=["#?])~', $c ) || preg_match( '~urban\.org\.in/' . $q . '(?![a-z0-9_-])~', $c ) ) { $left++; }
	}
	chk( 'no page still links to the old address (3 link forms)', 0 === $left );
	$mism = array();
	foreach ( glob( "$theme/pages/*.html" ) as $f ) {
		$rp = $repo_pages . '/' . basename( $f );
		if ( ! is_file( $rp ) || file_get_contents( $rp ) !== file_get_contents( $f ) ) { $mism[] = basename( $f ); }
	}
	chk( 'EVERY page == the repo\'s rebuilt page, byte for byte (' . count( glob( "$theme/pages/*.html" ) ) . ' pages)', 0 === count( $mism ) );
	if ( $mism ) { echo '    differing: ' . implode( ', ', array_slice( $mism, 0, 6 ) ) . "\n"; }
	$x = include "$theme/redirects-extra.php";
	chk( "redirects-extra: '$old' => '/$new'", isset( $x['exact'][ $old ] ) && '/' . $new === $x['exact'][ $old ] );
	$stale = 0; foreach ( $x['exact'] as $k => $v ) { if ( '/' . $old === $v ) { $stale++; } }
	chk( 'redirects-extra: no rule still points at the old address (no chains)', 0 === $stale );
	$t = include "$theme/inc/page-titles.php";
	chk( 'page-titles: new key present, old key gone', isset( $t[ $new ] ) && ! isset( $t[ $old ] ) );
	chk( '404 fragment link updated', false === strpos( file_get_contents( "$theme/inc/404-fragment.html" ), 'href="/' . $old . '"' ) );
	$bk = glob( WP_CONTENT_DIR . '/ucan-backup-*' );
	$okb = 1 === count( $bk ); $nb = 0;
	if ( $okb ) {
		foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $bk[0], FilesystemIterator::SKIP_DOTS ) ) as $f ) {
			if ( basename( $f ) === '.htaccess' ) { continue; }
			$rel = ltrim( substr( $f->getPathname(), strlen( $bk[0] ) ), '/' ); $nb++;
			if ( ! isset( $before[ "$theme/$rel" ] ) || $before[ "$theme/$rel" ] !== file_get_contents( $f->getPathname() ) ) { $okb = false; }
		}
	}
	chk( "backup holds the untouched originals ($nb files)", $okb && $nb > 150 );
	chk( 'no leftover temp files', 0 === count( glob( "$theme/pages/*.ucan-tmp" ) ) );
	$GLOBALS['hooks'] = array();
	eval( file_get_contents( $snippet ) );
	foreach ( $GLOBALS['hooks']['admin_init'] as $cb ) { $cb(); }
	chk( 'second run does nothing', 1 === count( glob( WP_CONTENT_DIR . '/ucan-backup-*' ) ) );
} else {
	chk( 'failure reported, not marked done', 0 === strpos( get_option( "ucan_rename_{$key}_log" ), 'FAILED' ) && ! get_option( "ucan_rename_{$key}_done" ) );
	$same = true;
	foreach ( $before as $p => $s ) { if ( ! is_file( $p ) || file_get_contents( $p ) !== $s ) { $same = false; } }
	chk( 'EVERY original file restored byte for byte', $same );
	chk( 'no new page left behind', ! is_file( "$theme/pages/$new.html" ) && is_file( "$theme/pages/$old.html" ) );
}
echo $fails ? "RESULT: $fails FAILED\n" : "RESULT: all passed\n";
exit( $fails ? 1 : 0 );
