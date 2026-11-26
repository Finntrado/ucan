<?php
// usage: php harness.php <snippet.php> <fixture_root> <ok|fail> <key> <repo_pages_dir>
error_reporting( E_ALL );
list( , $snippet, $root, $mode, $key, $repo_pages ) = $argv;
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
foreach ( glob( "$theme/pages/*.html" ) as $f ) { $before[ $f ] = file_get_contents( $f ); }

eval( file_get_contents( $snippet ) );
foreach ( $GLOBALS['hooks']['admin_init'] as $cb ) { $cb(); }
ob_start();
foreach ( isset( $GLOBALS['hooks']['admin_notices'] ) ? $GLOBALS['hooks']['admin_notices'] : array() as $cb ) { $cb(); }
$notice = trim( strip_tags( ob_get_clean() ) );
$fails = 0;
function chk( $name, $ok ) { global $fails; printf( "  %-66s %s\n", $name, $ok ? 'ok' : 'FAIL' ); if ( ! $ok ) { $fails++; } }
echo 'notice: ' . substr( $notice, 0, 200 ) . "\n";

if ( 'ok' === $mode ) {
	chk( 'success + done flag', 0 === strpos( get_option( "ucan_label_{$key}_log" ), 'OK' ) && get_option( "ucan_label_{$key}_done" ) );
	$mism = array();
	foreach ( glob( "$theme/pages/*.html" ) as $f ) {
		$rp = $repo_pages . '/' . basename( $f );
		if ( ! is_file( $rp ) || file_get_contents( $rp ) !== file_get_contents( $f ) ) { $mism[] = basename( $f ); }
	}
	chk( 'EVERY page == the repo\'s rebuilt page, byte for byte (' . count( $before ) . ' pages)', 0 === count( $mism ) );
	if ( $mism ) { echo '    differing: ' . implode( ', ', array_slice( $mism, 0, 6 ) ) . "\n"; }
	$bk = glob( WP_CONTENT_DIR . '/ucan-backup-label-*' );
	$okb = 1 === count( $bk ); $nb = 0;
	if ( $okb ) {
		foreach ( glob( $bk[0] . '/pages/*.html' ) as $f ) { $nb++; if ( $before[ "$theme/pages/" . basename( $f ) ] !== file_get_contents( $f ) ) { $okb = false; } }
	}
	chk( "backup holds the untouched originals ($nb files)", $okb && $nb > 150 );
	chk( 'no leftover temp files', 0 === count( glob( "$theme/pages/*.ucan-tmp" ) ) );
	$GLOBALS['hooks'] = array();
	eval( file_get_contents( $snippet ) );
	foreach ( $GLOBALS['hooks']['admin_init'] as $cb ) { $cb(); }
	chk( 'second run does nothing', 1 === count( glob( WP_CONTENT_DIR . '/ucan-backup-label-*' ) ) );
} else {
	chk( 'failure reported, not marked done', 0 === strpos( get_option( "ucan_label_{$key}_log" ), 'FAILED' ) && ! get_option( "ucan_label_{$key}_done" ) );
	$same = true;
	foreach ( $before as $p => $s ) { if ( ! is_file( $p ) || file_get_contents( $p ) !== $s ) { $same = false; } }
	chk( 'EVERY original page restored byte for byte', $same );
}
echo $fails ? "RESULT: $fails FAILED\n" : "RESULT: all passed\n";
exit( $fails ? 1 : 0 );
