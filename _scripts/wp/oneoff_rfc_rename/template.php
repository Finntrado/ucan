/*
 * ONE-TIME server-side change, run by an administrator opening any wp-admin page.
 * Why: the connector cannot publish the 325 MB theme in one request, and these changes
 * are only a few text replacements, so they are made directly in the live theme folder.
 *
 *  1. The RFC page moves to /enabling-collaboration-indias-urban-ecosystem
 *     - pages/rfc.html becomes pages/enabling-collaboration-indias-urban-ecosystem.html
 *     - every link / canonical / og:url / JSON-LD that said /rfc now says the new address
 *     - /rfc, /requests-for-collaboration and the old "from parallel to together" URL
 *       send a permanent 301 straight to it
 *  2. Our Members: the Roots and Horizons cover becomes a 3D book (CSS + 3 spans)
 *
 * Safety: nothing outside the theme's pages/ folder and three small data files is touched.
 * Every file is backed up first (wp-content/ucan-backup-rfc-<date>/), each write is atomic,
 * and if ANY step fails everything is put back exactly as it was. Runs once.
 * Result is shown as a notice in wp-admin and stored in the option ucan_rfc_rename_log.
 */
add_action( 'admin_init', function () {
	if ( get_option( 'ucan_rfc_rename_done' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( get_transient( 'ucan_rfc_rename_lock' ) ) { // another request is already running it
		return;
	}
	set_transient( 'ucan_rfc_rename_lock', 1, 300 );

	$run = function ( &$log ) {
		$new   = 'enabling-collaboration-indias-urban-ecosystem';
		$theme = untrailingslashit( get_template_directory() );
		if ( 'ucan' !== basename( $theme ) ) {
			throw new Exception( 'active theme folder is not "ucan"' );
		}
		$pages = $theme . '/pages';
		$newf  = $pages . '/' . $new . '.html';
		$oldf  = $pages . '/rfc.html';

		if ( is_file( $newf ) && ! is_file( $oldf ) ) {
			$log[] = 'Already renamed: nothing to do.';
			return true;
		}
		if ( ! is_file( $oldf ) ) {
			throw new Exception( 'pages/rfc.html not found' );
		}
		if ( is_file( $newf ) ) {
			throw new Exception( 'the new page file already exists' );
		}

		$old_css = <<<'UCAN_NOWDOC_END'
@@OLD_CSS_TAIL@@
UCAN_NOWDOC_END;
		$new_css = <<<'UCAN_NOWDOC_END'
@@NEW_CSS_TAIL@@
UCAN_NOWDOC_END;
		$old_mk = <<<'UCAN_NOWDOC_END'
@@OLD_MARKUP@@
UCAN_NOWDOC_END;
		$new_mk = <<<'UCAN_NOWDOC_END'
@@NEW_MARKUP@@
UCAN_NOWDOC_END;

		$orig = array(); // path => original contents (only files that change)
		$plan = array(); // path => new contents

		// 1. every page: links, canonical, og:url, JSON-LD
		$re = '~%%UCAN_HOME%%/rfc(?![a-z0-9_-])~';
		foreach ( (array) glob( $pages . '/*.html' ) as $f ) {
			$s = file_get_contents( $f );
			if ( false === $s ) {
				throw new Exception( 'cannot read ' . basename( $f ) );
			}
			$t = preg_replace( $re, '%%UCAN_HOME%%/' . $new, $s, -1, $n );
			if ( null === $t ) {
				throw new Exception( 'regex failed on ' . basename( $f ) );
			}
			if ( $n > 0 ) {
				$orig[ $f ] = $s;
				$plan[ $f ] = $t;
			}
		}
		if ( empty( $plan[ $oldf ] ) ) {
			throw new Exception( 'rfc.html has no self-links to rewrite (unexpected)' );
		}

		// 2. Our Members: 3D book (each piece must match exactly once, or already be applied)
		$om = $pages . '/our-members.html';
		if ( is_file( $om ) ) {
			$s = isset( $plan[ $om ] ) ? $plan[ $om ] : file_get_contents( $om );
			if ( false === $s ) {
				throw new Exception( 'cannot read our-members.html' );
			}
			$base = isset( $orig[ $om ] ) ? $orig[ $om ] : $s;
			foreach ( array( array( $old_css, $new_css, 'cover CSS' ), array( $old_mk, $new_mk, 'cover markup' ) ) as $p ) {
				$c = substr_count( $s, $p[0] );
				if ( 1 === $c ) {
					$s = str_replace( $p[0], $p[1], $s );
				} elseif ( 0 === $c && 1 === substr_count( $s, $p[1] ) ) {
					$log[] = 'Our Members ' . $p[2] . ': already applied.';
				} else {
					throw new Exception( 'Our Members ' . $p[2] . ' did not match exactly once (' . $c . ')' );
				}
			}
			if ( $s !== $base ) {
				$orig[ $om ] = $base;
				$plan[ $om ] = $s;
			}
		} else {
			$log[] = 'our-members.html not found: 3D book skipped.';
		}

		// 3. small data files: permanent redirects + 404 suggestion title
		$data = array(
			$theme . '/redirects.php'       => array( "'requests-for-collaboration' => 'rfc',", "'requests-for-collaboration' => '" . $new . "',\n\t'rfc' => '" . $new . "'," ),
			$theme . '/redirects-extra.php' => array( "'from-parellel-to-together-building-a-collaboration-practice-for-indias-urban-ecosystem' => '/rfc',", "'from-parellel-to-together-building-a-collaboration-practice-for-indias-urban-ecosystem' => '/" . $new . "'," ),
			$theme . '/inc/page-titles.php' => array( "'rfc' => 'Request for Collaboration (RFC)',", "'" . $new . "' => 'Request for Collaboration (RFC)'," ),
		);
		foreach ( $data as $f => $p ) {
			$s = is_file( $f ) ? file_get_contents( $f ) : false;
			if ( false === $s ) {
				throw new Exception( 'cannot read ' . basename( $f ) );
			}
			if ( 1 !== substr_count( $s, $p[0] ) ) {
				throw new Exception( basename( $f ) . ': expected line found ' . substr_count( $s, $p[0] ) . ' times' );
			}
			$orig[ $f ] = $s;
			$plan[ $f ]  = str_replace( $p[0], $p[1], $s );
		}

		// 4. backup of every file that will change
		$bdir = WP_CONTENT_DIR . '/ucan-backup-rfc-' . gmdate( 'Ymd-His' );
		if ( ! wp_mkdir_p( $bdir . '/pages' ) || ! wp_mkdir_p( $bdir . '/inc' ) ) {
			throw new Exception( 'cannot create backup folder' );
		}
		file_put_contents( $bdir . '/.htaccess', "<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\nDeny from all\n</IfModule>\n" );
		foreach ( $orig as $f => $s ) {
			$rel = ltrim( substr( $f, strlen( $theme ) ), '/' );
			if ( false === file_put_contents( $bdir . '/' . $rel, $s ) ) {
				throw new Exception( 'backup failed for ' . $rel );
			}
		}
		$log[] = 'Backup of ' . count( $orig ) . ' files: ' . $bdir;

		// 5. apply (atomic writes; rename of the page file last)
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
		$created = false;
		try {
			if ( ! $put( $newf, $plan[ $oldf ] ) ) {
				throw new Exception( 'cannot write the new page file' );
			}
			$created = true;
			foreach ( $plan as $f => $contents ) {
				if ( $f === $oldf ) {
					continue;
				}
				if ( ! $put( $f, $contents ) ) {
					throw new Exception( 'cannot write ' . basename( $f ) );
				}
				$written[] = $f;
			}
			if ( ! unlink( $oldf ) ) {
				throw new Exception( 'cannot remove pages/rfc.html' );
			}
		} catch ( Throwable $e ) {
			foreach ( $written as $f ) {
				file_put_contents( $f, $orig[ $f ] );
			}
			if ( $created ) {
				@unlink( $newf );
			}
			throw $e;
		}

		// 6. checks, then drop the fast-page cache (Apache serves those files without PHP)
		if ( ! is_file( $newf ) || is_file( $oldf ) || false === strpos( (string) file_get_contents( $newf ), '%%UCAN_HOME%%/' . $new ) ) {
			throw new Exception( 'post-change check failed' );
		}
		foreach ( array( '/redirects.php', '/redirects-extra.php', '/inc/page-titles.php' ) as $rel ) {
			if ( function_exists( 'opcache_invalidate' ) ) {
				@opcache_invalidate( $theme . $rel, true );
			}
		}
		if ( function_exists( 'ucan_fc_purge' ) ) {
			ucan_fc_purge();
		} else {
			foreach ( (array) glob( WP_CONTENT_DIR . '/ucan-cache/*.html' ) as $f ) {
				@unlink( $f );
			}
		}
		$log[] = 'Changed ' . count( $plan ) . ' files, created pages/' . $new . '.html, removed pages/rfc.html, cleared the page cache.';
		return true;
	};

	$log = array();
	$ok  = false;
	try {
		$ok = $run( $log );
	} catch ( Throwable $e ) {
		$log[] = 'FAILED, nothing was changed (or everything was restored): ' . $e->getMessage();
	}
	delete_transient( 'ucan_rfc_rename_lock' );
	update_option( 'ucan_rfc_rename_log', ( $ok ? 'OK' : 'FAILED' ) . ' ' . gmdate( 'c' ) . "\n" . implode( "\n", $log ), false );
	if ( $ok ) {
		update_option( 'ucan_rfc_rename_done', gmdate( 'c' ), false );
	}
	add_action( 'admin_notices', function () use ( $ok, $log ) {
		printf(
			'<div class="notice notice-%s"><p><strong>U-CAN RFC rename:</strong> %s</p></div>',
			$ok ? 'success' : 'error',
			esc_html( implode( ' | ', $log ) )
		);
	} );
}, 20 );
