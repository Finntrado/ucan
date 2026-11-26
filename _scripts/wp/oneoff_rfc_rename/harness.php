<?php
// usage: php harness.php <snippet.php> <fixture_root> <mode: ok|fail> <expected_our_members_file>
error_reporting( E_ALL );
list( , $snippet, $root, $mode, $expected_om ) = $argv;

define( 'ABSPATH', $root . '/' );
define( 'WP_CONTENT_DIR', $root . '/wp-content' );
$GLOBALS['opts'] = array();
$GLOBALS['trans'] = array();
$GLOBALS['hooks'] = array();
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
$new   = 'enabling-collaboration-indias-urban-ecosystem';

// snapshot of every file before
$before = array();
$it = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $theme, FilesystemIterator::SKIP_DOTS ) );
foreach ( $it as $f ) { if ( $f->isFile() ) { $before[ $f->getPathname() ] = file_get_contents( $f->getPathname() ); } }

eval( file_get_contents( $snippet ) );
foreach ( $GLOBALS['hooks']['admin_init'] as $cb ) { $cb(); }
ob_start();
foreach ( isset( $GLOBALS['hooks']['admin_notices'] ) ? $GLOBALS['hooks']['admin_notices'] : array() as $cb ) { $cb(); }
$notice = trim( strip_tags( ob_get_clean() ) );

$fails = 0;
function chk( $name, $ok ) { global $fails; printf( "  %-62s %s\n", $name, $ok ? 'ok' : 'FAIL' ); if ( ! $ok ) { $fails++; } }
echo "notice: $notice\n";

if ( 'ok' === $mode ) {
	chk( 'success + done flag set', 0 === strpos( get_option( 'ucan_rfc_rename_log' ), 'OK' ) && get_option( 'ucan_rfc_rename_done' ) );
	chk( 'new page exists, rfc.html removed', is_file( "$theme/pages/$new.html" ) && ! is_file( "$theme/pages/rfc.html" ) );
	$left = 0; $changed = 0;
	foreach ( glob( "$theme/pages/*.html" ) as $f ) {
		if ( preg_match( '~%%UCAN_HOME%%/rfc(?![a-z0-9_-])~', file_get_contents( $f ) ) ) { $left++; }
	}
	chk( 'no page still links to /rfc', 0 === $left );
	chk( 'every other page byte-identical except the rfc link', ( function () use ( $before, $theme, $new ) {
		$bad = 0;
		foreach ( $before as $p => $s ) {
			if ( false === strpos( $p, '/pages/' ) || ! is_file( $p ) ) { continue; }
			$exp = preg_replace( '~%%UCAN_HOME%%/rfc(?![a-z0-9_-])~', '%%UCAN_HOME%%/' . $new, $s );
			if ( basename( $p ) === 'our-members.html' ) { continue; }
			if ( file_get_contents( $p ) !== $exp ) { $bad++; }
		}
		return 0 === $bad;
	} )() );
	global $expected_om;
	chk( 'our-members == the repo page (3D book applied exactly)', file_get_contents( "$theme/pages/our-members.html" ) === file_get_contents( $expected_om ) );
	$r = include "$theme/redirects.php";
	chk( "redirects.php: 'rfc' and 'requests-for-collaboration' -> new", isset( $r['rfc'], $r['requests-for-collaboration'] ) && $r['rfc'] === $new && $r['requests-for-collaboration'] === $new );
	$x = include "$theme/redirects-extra.php";
	$xx = $x['exact']['from-parellel-to-together-building-a-collaboration-practice-for-indias-urban-ecosystem'];
	chk( 'redirects-extra.php: old report URL -> /new', '/' . $new === $xx );
	$t = include "$theme/inc/page-titles.php";
	chk( 'page-titles.php: new key present, old gone', isset( $t[ $new ] ) && ! isset( $t['rfc'] ) );
	$bk = glob( WP_CONTENT_DIR . '/ucan-backup-rfc-*' );
	chk( 'backup folder created', 1 === count( $bk ) );
	$okb = true; $nb = 0;
	foreach ( $bk[0] ? new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $bk[0], FilesystemIterator::SKIP_DOTS ) ) : array() as $f ) {
		if ( basename( $f ) === '.htaccess' ) { continue; }
		$rel = ltrim( substr( $f->getPathname(), strlen( $bk[0] ) ), '/' );
		$nb++;
		if ( ! isset( $before[ "$theme/$rel" ] ) || $before[ "$theme/$rel" ] !== file_get_contents( $f->getPathname() ) ) { $okb = false; }
	}
	chk( "backup holds the untouched originals ($nb files)", $okb && $nb > 150 );
	chk( 'no leftover temp files', 0 === count( glob( "$theme/pages/*.ucan-tmp" ) ) );
	// second run is a no-op
	$GLOBALS['hooks'] = array();
	eval( file_get_contents( $snippet ) );
	foreach ( $GLOBALS['hooks']['admin_init'] as $cb ) { $cb(); }
	chk( 'second run does nothing', 1 === count( glob( WP_CONTENT_DIR . '/ucan-backup-rfc-*' ) ) );
} else {
	chk( 'failure reported', 0 === strpos( get_option( 'ucan_rfc_rename_log' ), 'FAILED' ) && ! get_option( 'ucan_rfc_rename_done' ) );
	$same = true; $diff = array();
	foreach ( $before as $p => $s ) { if ( ! is_file( $p ) || file_get_contents( $p ) !== $s ) { $same = false; $diff[] = $p; } }
	chk( 'EVERY original file restored byte for byte', $same );
	chk( 'no new page left behind', ! is_file( "$theme/pages/$new.html" ) && is_file( "$theme/pages/rfc.html" ) );
	if ( $diff ) { echo '  differing: ' . implode( ', ', array_slice( $diff, 0, 5 ) ) . "\n"; }
}
echo $fails ? "RESULT: $fails FAILED\n" : "RESULT: all passed\n";
exit( $fails ? 1 : 0 );
