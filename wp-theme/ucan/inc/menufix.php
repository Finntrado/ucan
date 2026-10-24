<?php
/**
 * One behaviour script and one menu-lock stylesheet for every page.
 *
 * The page files in pages/ each carry their own inlined copy of the shared
 * behaviour script, and the live copies had drifted (two different, broken
 * mobile-menu scroll locks: the menu vanished off-screen on blog pages, froze
 * the page, and closing jumped the scroll). At serve time this swaps each
 * page's copy for inc/behaviour.js and adds inc/menulock.css, so every page
 * runs the same, tested code. Both files are written by
 * _scripts/fix_menu_lock.py; see that file for the bug and the fix.
 */

defined( 'ABSPATH' ) || exit;

function ucan_canonical_behaviour( $html ) {
	static $js = null, $css = null;
	if ( null === $js ) {
		$js  = is_file( __DIR__ . '/behaviour.js' ) ? (string) file_get_contents( __DIR__ . '/behaviour.js' ) : '';
		$css = is_file( __DIR__ . '/menulock.css' ) ? trim( (string) file_get_contents( __DIR__ . '/menulock.css' ) ) : '';
	}
	if ( '' !== $js ) {
		$html = (string) preg_replace_callback(
			'#(<script data-ucan="behaviour">).*?(</script>)#s',
			function ( $m ) use ( $js ) {
				return $m[1] . $js . $m[2];
			},
			$html,
			1
		);
	}
	if ( '' !== $css ) {
		$html = (string) preg_replace( '#<style data-ucan="menulock">.*?</style>#s', '', $html );
		$i    = strpos( $html, '</head>' );
		if ( false !== $i ) {
			$html = substr( $html, 0, $i ) . '<style data-ucan="menulock">' . $css . '</style>' . substr( $html, $i );
		}
	}
	return $html;
}
