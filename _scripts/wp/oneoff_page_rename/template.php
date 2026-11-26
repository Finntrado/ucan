/*
 * ONE-TIME server-side page rename, run by an administrator opening any wp-admin page.
 * Why: the connector cannot publish the 325 MB theme in one request, and a rename is only
 * a few text replacements, so it is made directly in the live theme folder.
 *
 *   /@@OLD@@  ->  /@@NEW@@     (permanent 301 from the old address)
 *
 *  - pages/@@OLD@@.html becomes pages/@@NEW@@.html
 *  - every link / canonical / og:url / JSON-LD that said the old address now says the new one
 *  - redirects-extra.php: the old address and every older URL that pointed at it now 301
 *    straight to the new address (one hop, no chains); page-titles.php and the 404 page follow
 *
 * Safety: only the theme's pages/ folder plus three small data files are touched. Every file is
 * backed up first (wp-content/ucan-backup-@@NEW@@-<date>/), each write is atomic, and if ANY
 * step fails everything is put back exactly as it was. Runs once. The result is shown as a
 * notice in wp-admin and stored in the option ucan_rename_@@KEY@@_log.
 */
add_action( 'admin_init', function () {
	if ( get_option( 'ucan_rename_@@KEY@@_done' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( get_transient( 'ucan_rename_@@KEY@@_lock' ) ) { // another request is already running it
		return;
	}
	set_transient( 'ucan_rename_@@KEY@@_lock', 1, 300 );

	$run = function ( &$log ) {
		$old   = '@@OLD@@';
		$new   = '@@NEW@@';
		$theme = untrailingslashit( get_template_directory() );
		if ( 'ucan' !== basename( $theme ) ) {
			throw new Exception( 'active theme folder is not "ucan"' );
		}
		$pages = $theme . '/pages';
		$newf  = $pages . '/' . $new . '.html';
		$oldf  = $pages . '/' . $old . '.html';

		if ( is_file( $newf ) && ! is_file( $oldf ) ) {
			$log[] = 'Already renamed: nothing to do.';
			return true;
		}
		if ( ! is_file( $oldf ) ) {
			throw new Exception( 'pages/' . $old . '.html not found' );
		}
		if ( is_file( $newf ) ) {
			throw new Exception( 'the new page file already exists' );
		}

		$orig = array(); // path => original contents (only files that change)
		$plan = array(); // path => new contents
		$q    = preg_quote( $old, '~' );

		// 1. every page (and the 404 page): links, canonical, og:url, JSON-LD
		$forms = array(
			'~%%UCAN_HOME%%/' . $q . '(?![a-z0-9_-])~'                                   => '%%UCAN_HOME%%/' . $new,
			'~(https?://(?:www\.)?urban\.org\.in)/' . $q . '(?![a-z0-9_-])~'             => '${1}/' . $new,
			'~(href=")/' . $q . '(?=["#?])~'                                             => '${1}/' . $new,
		);
		foreach ( (array) glob( $pages . '/*.html' ) as $f ) {
			$s = file_get_contents( $f );
			if ( false === $s ) {
				throw new Exception( 'cannot read ' . basename( $f ) );
			}
			$t = $s;
			foreach ( $forms as $re => $rep ) {
				$t = preg_replace( $re, $rep, $t );
				if ( null === $t ) {
					throw new Exception( 'regex failed on ' . basename( $f ) );
				}
			}
			if ( $t !== $s ) {
				$orig[ $f ] = $s;
				$plan[ $f ] = $t;
			}
		}
		if ( empty( $plan[ $oldf ] ) ) {
			throw new Exception( 'the page has no self-links to rewrite (unexpected)' );
		}

		// 2. small data files
		$read = function ( $f ) {
			$s = is_file( $f ) ? file_get_contents( $f ) : false;
			if ( false === $s ) {
				throw new Exception( 'cannot read ' . basename( $f ) );
			}
			return $s;
		};
		// redirects-extra.php: every rule that pointed at the old address now points at the new one,
		// and the old address itself gets a rule (placed in front of the first such rule)
		$f = $theme . '/redirects-extra.php';
		$s = $read( $f );
		if ( false !== strpos( $s, "'" . $old . "' =>" ) ) {
			throw new Exception( 'redirects-extra.php already has a rule for the old address' );
		}
		$t = str_replace( "=> '/" . $old . "',", "=> '/" . $new . "',", $s, $cnt );
		if ( $cnt < 1 ) {
			throw new Exception( 'redirects-extra.php: no rule pointing at the old address was found' );
		}
		$t = preg_replace( '~^(\s*)(\'[^\']*\' => \'/' . preg_quote( $new, '~' ) . '\',)~m', "\${1}'" . $old . "' => '/" . $new . "',\n\${1}\$2", $t, 1, $ins );
		if ( 1 !== $ins ) {
			throw new Exception( 'redirects-extra.php: could not place the new rule' );
		}
		$orig[ $f ] = $s;
		$plan[ $f ] = $t;
		// redirects.php: a value that points at the old slug (optional)
		$f = $theme . '/redirects.php';
		$s = $read( $f );
		$t = str_replace( "=> '" . $old . "',", "=> '" . $new . "',", $s, $cnt );
		if ( $cnt > 0 ) {
			$orig[ $f ] = $s;
			$plan[ $f ]  = $t;
		}
		// page-titles.php: the 404 "did you mean" key
		$f = $theme . '/inc/page-titles.php';
		$s = $read( $f );
		if ( 1 !== substr_count( $s, "'" . $old . "' =>" ) ) {
			throw new Exception( 'page-titles.php: expected exactly one entry for the old address' );
		}
		$orig[ $f ] = $s;
		$plan[ $f ]  = str_replace( "'" . $old . "' =>", "'" . $new . "' =>", $s );
		// 404 page fragment (optional)
		$f = $theme . '/inc/404-fragment.html';
		if ( is_file( $f ) ) {
			$s = $read( $f );
			$t = str_replace( 'href="/' . $old . '"', 'href="/' . $new . '"', $s, $cnt );
			if ( $cnt > 0 ) {
				$orig[ $f ] = $s;
				$plan[ $f ]  = $t;
			}
		}

		// 3. backup of every file that will change
		$bdir = WP_CONTENT_DIR . '/ucan-backup-' . $new . '-' . gmdate( 'Ymd-His' );
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

		// 4. apply (atomic writes; removal of the old page file last)
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
				throw new Exception( 'cannot remove pages/' . $old . '.html' );
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

		// 5. checks, then drop the fast-page cache (Apache serves those files without PHP)
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
		$log[] = 'Changed ' . count( $plan ) . ' files, created pages/' . $new . '.html, removed pages/' . $old . '.html, cleared the page cache.';
		return true;
	};

	$log = array();
	$ok  = false;
	try {
		$ok = $run( $log );
	} catch ( Throwable $e ) {
		$log[] = 'FAILED, nothing was changed (or everything was restored): ' . $e->getMessage();
	}
	delete_transient( 'ucan_rename_@@KEY@@_lock' );
	update_option( 'ucan_rename_@@KEY@@_log', ( $ok ? 'OK' : 'FAILED' ) . ' ' . gmdate( 'c' ) . "\n" . implode( "\n", $log ), false );
	if ( $ok ) {
		update_option( 'ucan_rename_@@KEY@@_done', gmdate( 'c' ), false );
	}
	add_action( 'admin_notices', function () use ( $ok, $log ) {
		printf(
			'<div class="notice notice-%s"><p><strong>U-CAN page rename (@@OLD@@ to @@NEW@@):</strong> %s</p></div>',
			$ok ? 'success' : 'error',
			esc_html( implode( ' | ', $log ) )
		);
	} );
}, 20 );
