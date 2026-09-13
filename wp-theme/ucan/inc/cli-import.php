<?php
/**
 * Phase 7: the one-time content importer. Reads the JSON files
 * _scripts/wp/extract_content.py produces (out of the finished
 * standalone/*.html pages - never re-scrapes the live site, same as
 * every build_*.py in this repo) and creates the real WordPress content:
 * every CPT post, its meta/taxonomy/featured image, and the Pages that
 * page-<slug>.php's file-name convention needs to exist before it can
 * apply automatically.
 *
 * WP-CLI only - this file is require'd from functions.php guarded by
 * `defined('WP_CLI') && WP_CLI`, so it never loads on a normal page
 * request. Not runnable in this sandbox (no PHP/MySQL/WP-CLI here - see
 * CLAUDE.md §28) - hand-reviewed against the JSON shape
 * extract_content.py actually produces, not executed.
 *
 * Usage, from the WordPress root once the theme is active and the JSON
 * files are on the server (e.g. uploaded alongside the theme):
 *   wp ucan import all --dir=/path/to/_scripts/wp/data
 * or one content type at a time:
 *   wp ucan import members --dir=/path/to/_scripts/wp/data
 *   wp ucan import fellows --dir=/path/to/_scripts/wp/data
 *   wp ucan import etn-events --dir=/path/to/_scripts/wp/data
 *   wp ucan import blogs --dir=/path/to/_scripts/wp/data   (needs fellows first)
 *   wp ucan import mixers --dir=/path/to/_scripts/wp/data
 *   wp ucan import webinar-recaps --dir=/path/to/_scripts/wp/data
 *   wp ucan import newsletters --dir=/path/to/_scripts/wp/data
 *   wp ucan import pages
 *
 * Idempotent: every method looks up an existing post by post_name first
 * and skips creating a duplicate, so re-running after a partial/failed
 * pass is safe - it only fills in what's missing, matching this repo's
 * long-standing "a partial build is worse than a failed one" rule (§24).
 */

defined( 'ABSPATH' ) || exit;

/**
 * Reads and JSON-decodes a data file, or WP_CLI::error()s with a clear
 * message naming the missing/malformed file rather than a raw PHP notice.
 */
function ucan_import_read_json( $dir, $filename ) {
	$path = trailingslashit( $dir ) . $filename;
	if ( ! file_exists( $path ) ) {
		WP_CLI::error( "Data file not found: $path" );
	}
	$data = json_decode( file_get_contents( $path ), true );
	if ( null === $data ) {
		WP_CLI::error( "Could not parse JSON: $path" );
	}
	return $data;
}

/**
 * Finds an existing post by post_type + post_name (slug), or 0.
 * get_page_by_path() is built for hierarchical path lookups and is
 * unreliable for the flat, non-hierarchical CPTs this importer creates -
 * a direct get_posts() name= query is the documented, reliable way to
 * look up any post type by its exact slug.
 */
function ucan_import_find( $post_type, $slug ) {
	$posts = get_posts( array(
		'name'             => $slug,
		'post_type'        => $post_type,
		'post_status'      => 'any',
		'posts_per_page'   => 1,
		'fields'           => 'ids',
		'suppress_filters' => false,
	) );
	return $posts ? $posts[0] : 0;
}

/**
 * Downloads/copies one theme-relative asset path (e.g.
 * "assets/img/x.webp" or "newsletters/img/y.webp") into the Media Library
 * and sets it as $post_id's featured image. No-ops quietly if the file
 * doesn't exist or $rel_path is empty - a missing photo shouldn't abort
 * an otherwise-good import.
 */
function ucan_import_set_featured_image( $post_id, $rel_path, $alt = '' ) {
	if ( ! $rel_path ) {
		return;
	}
	$src = get_template_directory() . '/' . ltrim( $rel_path, '/' );
	if ( ! file_exists( $src ) ) {
		WP_CLI::warning( "Asset not found, skipping image: $src" );
		return;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$upload_dir = wp_upload_dir();
	$filename   = wp_unique_filename( $upload_dir['path'], basename( $src ) );
	$dest       = $upload_dir['path'] . '/' . $filename;
	copy( $src, $dest );

	$filetype   = wp_check_filetype( $filename, null );
	$attachment = array(
		'post_mime_type' => $filetype['type'],
		'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
		'post_status'    => 'inherit',
	);
	$attach_id = wp_insert_attachment( $attachment, $dest, $post_id );
	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
		WP_CLI::warning( "Failed to create attachment for: $src" );
		return;
	}
	if ( $alt ) {
		update_post_meta( $attach_id, '_wp_attachment_image_alt', $alt );
	}
	$attach_data = wp_generate_attachment_metadata( $attach_id, $dest );
	wp_update_attachment_metadata( $attach_id, $attach_data );
	set_post_thumbnail( $post_id, $attach_id );
}

/** Gets-or-creates a fellow_blog_tag term, returns its term_id. */
function ucan_import_get_tag_term_id( $slug, $name ) {
	$term = get_term_by( 'slug', $slug, 'fellow_blog_tag' );
	if ( $term ) {
		return $term->term_id;
	}
	$result = wp_insert_term( $name, 'fellow_blog_tag', array( 'slug' => $slug ) );
	if ( is_wp_error( $result ) ) {
		WP_CLI::warning( 'Could not create tag term ' . $slug . ': ' . $result->get_error_message() );
		return 0;
	}
	return $result['term_id'];
}

class UCAN_Import_Command {

	/**
	 * Imports every content type, in dependency order (fellows before
	 * blogs, since a blog post looks up its fellow's post ID by slug).
	 *
	 * Corrected from an earlier version of this file that tried to make
	 * one `import( <type> )` method dispatch by its first positional
	 * argument - that's not how WP-CLI's class-based registration works:
	 * `WP_CLI::add_command( 'ucan import', 'UCAN_Import_Command' )`
	 * exposes every public method of the class as its own subcommand
	 * named after the method (so `pages`, `members`, `fellows` etc. below
	 * were already reachable as `wp ucan import members` and so on - only
	 * `wp ucan import all` was ever actually broken, since no method was
	 * named `all`). Found on the first real run against an actual
	 * WordPress install, not caught by static review alone.
	 *
	 * ## OPTIONS
	 *
	 * --dir=<dir>
	 * : Directory holding the JSON files from extract_content.py.
	 *
	 * ## EXAMPLES
	 *
	 *     wp ucan import all --dir=wp-content/ucan-import-data
	 *
	 * @when after_wp_load
	 */
	public function all( $args, $assoc_args ) {
		$dir = isset( $assoc_args['dir'] ) ? $assoc_args['dir'] : '';
		if ( ! $dir ) {
			WP_CLI::error( 'Missing --dir=<path to the JSON data files>' );
		}
		$this->pages( array(), array() );
		$this->members( array(), array( 'dir' => $dir ) );
		$this->fellows( array(), array( 'dir' => $dir ) );
		$this->etn_events( array(), array( 'dir' => $dir ) );
		$this->blogs( array(), array( 'dir' => $dir ) );
		$this->mixers( array(), array( 'dir' => $dir ) );
		$this->webinar_recaps( array(), array( 'dir' => $dir ) );
		$this->newsletters( array(), array( 'dir' => $dir ) );
		WP_CLI::success( 'All content imported.' );
	}

	/**
	 * The 19 WP Pages every page-<slug>.php template needs to exist for
	 * (front-page.php applies to the site root automatically, needs no
	 * Page row - WP's template hierarchy uses it unconditionally when
	 * present, regardless of the Reading-settings front-page choice).
	 */
	public function pages( $args, $assoc_args ) {
		$slugs = array(
			'about-us' => 'About Us', 'impact' => 'Impact', 'our-people' => 'Our People',
			'our-members' => 'Our Members', 'learning-network-for-urban-managers' => 'Learning Network',
			'urban-reforms-collective' => 'Urban Reforms Collective', 'requests-for-collaboration' => 'Request for Collaboration',
			'u-can-fellowship' => 'U-CAN Fellowship', 'meet-our-fellows' => 'Meet the Fellows',
			'fellowship-ld' => 'L&D Calendar', 'blogs-by-our-fellows' => 'Blogs by Our Fellows',
			'u-can-city-mixers' => 'City Mixers', 'policy-webinars' => 'Policy Webinars',
			'city-champions' => 'City Champions', 'the-u-can-annual-forum-2025' => 'Annual Forum 2025',
			'newsletter' => 'Newsletter', 'privacy-policy' => 'Privacy Policy',
			'terms-of-use' => 'Terms of Use', 'data-rights' => 'Data Rights',
		);
		$n = 0;
		foreach ( $slugs as $slug => $title ) {
			if ( ucan_import_find( 'page', $slug ) ) {
				continue;
			}
			wp_insert_post( array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => $title,
				'post_name'   => $slug,
			) );
			$n++;
		}
		WP_CLI::log( "Pages created: $n (of " . count( $slugs ) . ')' );
	}

	/**
	 * Imports the 22 U-CAN member profiles (ucan_member CPT).
	 *
	 * ## OPTIONS
	 *
	 * --dir=<dir>
	 * : Directory holding the JSON files from extract_content.py.
	 */
	public function members( $args, $assoc_args ) {
		$rows = ucan_import_read_json( $assoc_args['dir'], 'members.json' );
		$group_term_ids = array();
		foreach ( array( 'founding-circle', 'steering-committee', 'stewardship-team', 'our-team' ) as $g ) {
			$term = get_term_by( 'slug', $g, 'ucan_member_group' );
			if ( $term ) {
				$group_term_ids[ $g ] = $term->term_id;
			}
		}
		$n = 0;
		foreach ( $rows as $r ) {
			if ( ucan_import_find( 'ucan_member', $r['slug'] ) ) {
				continue;
			}
			$post_id = wp_insert_post( array(
				'post_type'    => 'ucan_member',
				'post_status'  => 'publish',
				'post_title'   => $r['name'],
				'post_name'    => $r['slug'],
				'post_content' => $r['bio_html'],
			) );
			if ( is_wp_error( $post_id ) ) {
				WP_CLI::warning( 'member ' . $r['slug'] . ': ' . $post_id->get_error_message() );
				continue;
			}
			update_post_meta( $post_id, 'job_title', $r['job_title'] );
			update_post_meta( $post_id, 'organisation', $r['organisation'] );
			update_post_meta( $post_id, 'linkedin', $r['linkedin'] );
			$tids = array();
			foreach ( $r['groups'] as $g ) {
				if ( isset( $group_term_ids[ $g ] ) ) {
					$tids[] = $group_term_ids[ $g ];
				}
			}
			wp_set_object_terms( $post_id, $tids, 'ucan_member_group' );
			ucan_import_set_featured_image( $post_id, $r['photo'], $r['name'] );
			$n++;
		}
		WP_CLI::log( "Members created: $n (of " . count( $rows ) . ')' );
	}

	/**
	 * Imports the 8 U-CAN Fellows (ucan_fellow CPT).
	 *
	 * ## OPTIONS
	 *
	 * --dir=<dir>
	 * : Directory holding the JSON files from extract_content.py.
	 */
	public function fellows( $args, $assoc_args ) {
		$rows = ucan_import_read_json( $assoc_args['dir'], 'fellows.json' );
		$n = 0;
		foreach ( $rows as $r ) {
			if ( ucan_import_find( 'ucan_fellow', $r['slug'] ) ) {
				continue;
			}
			$post_id = wp_insert_post( array(
				'post_type'    => 'ucan_fellow',
				'post_status'  => 'publish',
				'post_title'   => $r['name'],
				'post_name'    => $r['slug'],
				'post_content' => $r['bio_html'],
				'menu_order'   => $r['menu_order'],
			) );
			if ( is_wp_error( $post_id ) ) {
				WP_CLI::warning( 'fellow ' . $r['slug'] . ': ' . $post_id->get_error_message() );
				continue;
			}
			update_post_meta( $post_id, 'host_organisation', $r['host_organisation'] );
			update_post_meta( $post_id, 'joining_location', $r['joining_location'] );
			update_post_meta( $post_id, 'education', $r['education'] );
			update_post_meta( $post_id, 'cohort_label', $r['cohort_label'] );
			update_post_meta( $post_id, 'social_links', implode( "\n", $r['social_urls'] ) );
			ucan_import_set_featured_image( $post_id, $r['photo'], $r['name'] );
			$n++;
		}
		WP_CLI::log( "Fellows created: $n (of " . count( $rows ) . ')' );
	}

	/**
	 * Imports L&D sessions, Policy Webinars and the one City Mixer note (ucan_etn_event CPT).
	 *
	 * ## OPTIONS
	 *
	 * --dir=<dir>
	 * : Directory holding the JSON files from extract_content.py.
	 */
	public function etn_events( $args, $assoc_args ) {
		$rows = ucan_import_read_json( $assoc_args['dir'], 'etn_events.json' );
		$n = 0;
		foreach ( $rows as $r ) {
			if ( ucan_import_find( 'ucan_etn_event', $r['slug'] ) ) {
				continue;
			}
			$post_id = wp_insert_post( array(
				'post_type'    => 'ucan_etn_event',
				'post_status'  => 'publish',
				'post_title'   => $r['title'],
				'post_name'    => $r['slug'],
				'post_content' => $r['content_html'],
				'post_date'    => $r['post_date'] . ' 00:00:00',
			) );
			if ( is_wp_error( $post_id ) ) {
				WP_CLI::warning( 'etn_event ' . $r['slug'] . ': ' . $post_id->get_error_message() );
				continue;
			}
			update_post_meta( $post_id, 'event_kind', $r['kind'] );
			update_post_meta( $post_id, 'lead_name', $r['lead_name'] );
			update_post_meta( $post_id, 'lead_bio', $r['lead_bio'] );
			update_post_meta( $post_id, 'time_range', $r['time_range'] );
			update_post_meta( $post_id, 'timezone', $r['timezone'] );
			update_post_meta( $post_id, 'session_type', $r['session_type'] );
			update_post_meta( $post_id, 'format', $r['format'] );
			$n++;
		}
		WP_CLI::log( "Etn events created: $n (of " . count( $rows ) . ')' );
	}

	/**
	 * Imports the 58 fellow blog posts (ucan_blog CPT). Run after fellows.
	 *
	 * ## OPTIONS
	 *
	 * --dir=<dir>
	 * : Directory holding the JSON files from extract_content.py.
	 */
	public function blogs( $args, $assoc_args ) {
		$rows = ucan_import_read_json( $assoc_args['dir'], 'blogs.json' );
		$n = 0;
		foreach ( $rows as $r ) {
			if ( ucan_import_find( 'ucan_blog', $r['slug'] ) ) {
				continue;
			}
			$post_id = wp_insert_post( array(
				'post_type'    => 'ucan_blog',
				'post_status'  => 'publish',
				'post_title'   => $r['title'],
				'post_name'    => $r['slug'],
				'post_content' => $r['content_html'],
				'post_excerpt' => $r['excerpt'],
				'post_date'    => $r['post_date'] . ' 00:00:00',
			) );
			if ( is_wp_error( $post_id ) ) {
				WP_CLI::warning( 'blog ' . $r['slug'] . ': ' . $post_id->get_error_message() );
				continue;
			}
			$fellow_id = ucan_import_find( 'ucan_fellow', $r['fellow_slug'] );
			if ( $fellow_id ) {
				update_post_meta( $post_id, 'fellow_id', $fellow_id );
			} else {
				WP_CLI::warning( 'blog ' . $r['slug'] . ': fellow "' . $r['fellow_slug'] . '" not found - import fellows first' );
			}
			$tag_ids = array();
			foreach ( $r['tags'] as $t ) {
				$tid = ucan_import_get_tag_term_id( $t['slug'], $t['name'] );
				if ( $tid ) {
					$tag_ids[] = $tid;
				}
			}
			wp_set_object_terms( $post_id, $tag_ids, 'fellow_blog_tag' );
			ucan_import_set_featured_image( $post_id, $r['image'], $r['title'] );
			$n++;
		}
		WP_CLI::log( "Blog posts created: $n (of " . count( $rows ) . ')' );
	}

	/**
	 * Imports the 11 City Mixer write-ups (ucan_mixer CPT).
	 *
	 * ## OPTIONS
	 *
	 * --dir=<dir>
	 * : Directory holding the JSON files from extract_content.py.
	 */
	public function mixers( $args, $assoc_args ) {
		$rows = ucan_import_read_json( $assoc_args['dir'], 'mixers.json' );
		$n = 0;
		foreach ( $rows as $r ) {
			if ( ucan_import_find( 'ucan_mixer', $r['slug'] ) ) {
				continue;
			}
			$post_id = wp_insert_post( array(
				'post_type'    => 'ucan_mixer',
				'post_status'  => 'publish',
				'post_title'   => $r['title'],
				'post_name'    => $r['slug'],
				'post_content' => $r['content_html'],
				'post_date'    => $r['post_date'] . ' 00:00:00',
				'menu_order'   => $r['menu_order'],
			) );
			if ( is_wp_error( $post_id ) ) {
				WP_CLI::warning( 'mixer ' . $r['slug'] . ': ' . $post_id->get_error_message() );
				continue;
			}
			update_post_meta( $post_id, 'host_organisation', $r['host_organisation'] );
			update_post_meta( $post_id, 'city', $r['city'] );
			ucan_import_set_featured_image( $post_id, $r['image'], $r['title'] );
			$n++;
		}
		WP_CLI::log( "Mixers created: $n (of " . count( $rows ) . ')' );
	}

	/**
	 * Imports the 4 webinar recap articles (ucan_webinar_recap CPT).
	 *
	 * ## OPTIONS
	 *
	 * --dir=<dir>
	 * : Directory holding the JSON files from extract_content.py.
	 */
	public function webinar_recaps( $args, $assoc_args ) {
		$rows = ucan_import_read_json( $assoc_args['dir'], 'webinar_recaps.json' );
		$n = 0;
		foreach ( $rows as $r ) {
			if ( ucan_import_find( 'ucan_webinar_recap', $r['slug'] ) ) {
				continue;
			}
			$post_id = wp_insert_post( array(
				'post_type'    => 'ucan_webinar_recap',
				'post_status'  => 'publish',
				'post_title'   => $r['title'],
				'post_name'    => $r['slug'],
				'post_content' => $r['content_html'],
			) );
			if ( is_wp_error( $post_id ) ) {
				WP_CLI::warning( 'webinar_recap ' . $r['slug'] . ': ' . $post_id->get_error_message() );
				continue;
			}
			$n++;
		}
		WP_CLI::log( "Webinar recaps created: $n (of " . count( $rows ) . ')' );
	}

	/**
	 * Imports the 29 newsletter issues (ucan_newsletter CPT).
	 *
	 * ## OPTIONS
	 *
	 * --dir=<dir>
	 * : Directory holding the JSON files from extract_content.py.
	 */
	public function newsletters( $args, $assoc_args ) {
		$rows = ucan_import_read_json( $assoc_args['dir'], 'newsletters.json' );
		$n = 0;
		foreach ( $rows as $r ) {
			if ( ucan_import_find( 'ucan_newsletter', $r['slug'] ) ) {
				continue;
			}
			$post_id = wp_insert_post( array(
				'post_type'    => 'ucan_newsletter',
				'post_status'  => 'publish',
				'post_title'   => $r['title'],
				'post_name'    => $r['slug'],
				'post_content' => $r['content_html'],
				'post_date'    => $r['post_date'] . ' 00:00:00',
			) );
			if ( is_wp_error( $post_id ) ) {
				WP_CLI::warning( 'newsletter ' . $r['slug'] . ': ' . $post_id->get_error_message() );
				continue;
			}
			update_post_meta( $post_id, 'issue_kind', $r['kind'] );
			update_post_meta( $post_id, 'edition_name', $r['edition_name'] );
			update_post_meta( $post_id, 'masthead_sub', $r['masthead_sub'] );
			update_post_meta( $post_id, 'pdf_path', $r['pdf_path'] );
			$image = 'designed' === $r['kind'] ? null : $r['cover_image'];
			if ( $image ) {
				ucan_import_set_featured_image( $post_id, $image, $r['title'] );
			}
			$n++;
		}
		WP_CLI::log( "Newsletter issues created: $n (of " . count( $rows ) . ')' );
	}
}

WP_CLI::add_command( 'ucan import', 'UCAN_Import_Command' );
