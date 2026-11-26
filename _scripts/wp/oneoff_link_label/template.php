/*
 * ONE-TIME server-side change, run by an administrator opening any wp-admin page.
 * Renames the LABEL of the menu / footer links that point at /@@SLUG@@
 * (desktop dropdown, mobile menu, footer) to "@@NEW@@". Only that link text changes:
 * the page itself, its title, headings and the address are not touched.
 *
 * Safety: only the theme's pages/*.html files that contain such a link are changed. Every
 * changed file is backed up first (wp-content/ucan-backup-label-<date>/), each write is atomic,
 * and if ANY step fails everything is put back exactly as it was. Runs once.
 * The result is shown as a notice in wp-admin and stored in the option ucan_label_@@KEY@@_log.
 */
add_action( 'admin_init', function () {
	if ( get_option( 'ucan_label_@@KEY@@_done' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( get_transient( 'ucan_label_@@KEY@@_lock' ) ) {
		return;
	}
	set_transient( 'ucan_label_@@KEY@@_lock', 1, 300 );

	$run = function ( &$log ) {
		$theme = untrailingslashit( get_template_directory() );
		if ( 'ucan' !== basename( $theme ) ) {
			throw new Exception( 'active theme folder is not "ucan"' );
		}
		$pages = $theme . '/pages';
		$new   = '@@NEW@@';
		// <a ... href="%%UCAN_HOME%%/slug" ...>OLD LABEL</a>  ->  same tag, new label
		$re = '~(<a\b[^>]*href="%%UCAN_HOME%%/' . preg_quote( '@@SLUG@@', '~' ) . '"[^>]*>)@@OLD_RE@@(</a>)~';

		$orig  = array();
		$plan  = array();
		$links = 0;
		foreach ( (array) glob( $pages . '/*.html' ) as $f ) {
			$s = file_get_contents( $f );
			if ( false === $s ) {
				throw new Exception( 'cannot read ' . basename( $f ) );
			}
			$t = preg_replace( $re, '${1}' . $new . '${2}', $s, -1, $n );
			if ( null === $t ) {
				throw new Exception( 'regex failed on ' . basename( $f ) );
			}
			if ( $n > 0 ) {
				$orig[ $f ] = $s;
				$plan[ $f ] = $t;
				$links     += $n;
			}
		}
		if ( 0 === count( $plan ) ) {
			$log[] = 'No link with the old label found: nothing to do (already renamed?).';
			return true;
		}

		$bdir = WP_CONTENT_DIR . '/ucan-backup-label-' . gmdate( 'Ymd-His' );
		if ( ! wp_mkdir_p( $bdir . '/pages' ) ) {
			throw new Exception( 'cannot create backup folder' );
		}
		file_put_contents( $bdir . '/.htaccess', "<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\nDeny from all\n</IfModule>\n" );
		foreach ( $orig as $f => $s ) {
			if ( false === file_put_contents( $bdir . '/pages/' . basename( $f ), $s ) ) {
				throw new Exception( 'backup failed for ' . basename( $f ) );
			}
		}
		$log[] = 'Backup of ' . count( $orig ) . ' files: ' . $bdir;

		$put = function ( $path, $contents ) {
			$tmp = $path . '.ucan-tmp';
			if ( false === file_put_contents( $tmp, $contents ) ) {
				@unlink( $tmp );
				return false;
			}
			if ( ! rename( $tmp, $path ) ) {
				@unlink( $tmp );
				return false;
			}
			return true;
		};
		$written = array();
		try {
			foreach ( $plan as $f => $contents ) {
				if ( ! $put( $f, $contents ) ) {
					throw new Exception( 'cannot write ' . basename( $f ) );
				}
				$written[] = $f;
			}
		} catch ( Throwable $e ) {
			foreach ( $written as $f ) {
				file_put_contents( $f, $orig[ $f ] );
			}
			throw $e;
		}

		if ( function_exists( 'ucan_fc_purge' ) ) {
			ucan_fc_purge();
		} else {
			foreach ( (array) glob( WP_CONTENT_DIR . '/ucan-cache/*.html' ) as $f ) {
				@unlink( $f );
			}
		}
		$log[] = 'Relabelled ' . $links . ' links in ' . count( $plan ) . ' pages, cleared the page cache.';
		return true;
	};

	$log = array();
	$ok  = false;
	try {
		$ok = $run( $log );
	} catch ( Throwable $e ) {
		$log[] = 'FAILED, nothing was changed (or everything was restored): ' . $e->getMessage();
	}
	delete_transient( 'ucan_label_@@KEY@@_lock' );
	update_option( 'ucan_label_@@KEY@@_log', ( $ok ? 'OK' : 'FAILED' ) . ' ' . gmdate( 'c' ) . "\n" . implode( "\n", $log ), false );
	if ( $ok ) {
		update_option( 'ucan_label_@@KEY@@_done', gmdate( 'c' ), false );
	}
	add_action( 'admin_notices', function () use ( $ok, $log ) {
		printf(
			'<div class="notice notice-%s"><p><strong>U-CAN menu label (@@NEW@@):</strong> %s</p></div>',
			$ok ? 'success' : 'error',
			esc_html( implode( ' | ', $log ) )
		);
	} );
}, 20 );
