<?php
/**
 * U-CAN theme setup.
 *
 * Phase 0 of the WordPress conversion (see the project plan at
 * .claude/plans/greedy-moseying-magpie.md, or CLAUDE.md's WordPress-theme
 * section once this lands there): theme bootstrap, the shared header/
 * footer chrome (header.php / footer.php), and a real Primary Navigation
 * menu that reproduces the site's existing dropdown nav (see CLAUDE.md
 * §3a) as an editable wp-admin menu instead of the static build's
 * hand-generated markup (rebuild_nav.py).
 *
 * NOT yet in this file (later phases, see the plan):
 *   - Custom post types for members / fellows / L&D sessions / city
 *     mixers / webinars / newsletters (phases 3-6).
 *   - The Data Rights request form's wp_mail() handler (phase 7).
 *   - Full per-type JSON-LD (Person, Article, ProfilePage, FAQPage) -
 *     only the site-wide Organization node is emitted for now.
 */

defined( 'ABSPATH' ) || exit;

// --------------------------------------------------------------- setup ---
function ucan_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array( 'search-form', 'gallery', 'caption', 'script', 'style' )
	);
	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'ucan' ),
		)
	);
}
add_action( 'after_setup_theme', 'ucan_setup' );

function ucan_enqueue_assets() {
	// Page CSS stays inlined in header.php (byte-identical to the static
	// build this replaces - see the plan's rationale). style.css only
	// carries the theme header comment WP requires.
	wp_enqueue_style( 'ucan-style', get_stylesheet_uri(), array(), '0.1.0' );

	wp_enqueue_script(
		'ucan-behaviour',
		get_template_directory_uri() . '/assets/js/ucan.js',
		array(),
		filemtime( get_template_directory() . '/assets/js/ucan.js' ),
		array( 'in_footer' => true, 'strategy' => 'defer' )
	);
}
add_action( 'wp_enqueue_scripts', 'ucan_enqueue_assets' );

// ---------------------------------------------------------- <head> tags --
/**
 * Adds the meta description, OG/Twitter tags and the site-wide Organization
 * JSON-LD node that every standalone/*.html page already carries (CLAUDE.md
 * §13). Canonical + <title> are WP core's own job (rel_canonical() and
 * title-tag support, both already firing on wp_head()) - not duplicated
 * here. Per-page/per-type JSON-LD nodes (Person, Article, ProfilePage,
 * FAQPage, WebPage, BreadcrumbList) are added by their own templates in
 * later phases and should be additive to, not a replacement for, this hook.
 */
function ucan_document_head() {
	// Page templates (page-about.php etc., phase 2 onward) set these two
	// globals before calling get_header(), so their extracted-verbatim
	// meta description and full JSON-LD graph (already carrying correct
	// absolute urban.org.in canonical URLs) take priority over anything
	// generated generically below.
	$page_meta   = isset( $GLOBALS['ucan_page_meta'] ) ? $GLOBALS['ucan_page_meta'] : array();
	$page_jsonld = isset( $GLOBALS['ucan_page_jsonld'] ) ? $GLOBALS['ucan_page_jsonld'] : '';

	$description = isset( $page_meta['description'] ) ? $page_meta['description'] : '';
	if ( ! $description ) {
		if ( is_singular() ) {
			global $post;
			$custom = get_post_meta( $post->ID, 'meta_description', true );
			$description = $custom ? $custom : wp_strip_all_tags( get_the_excerpt( $post ) );
		} elseif ( is_front_page() ) {
			$description = get_bloginfo( 'description' );
		}
	}
	$title = wp_get_document_title();
	$url   = is_singular() ? get_permalink() : home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
	$image = 'https://urban.org.in/assets/img/og-default.jpg';
	if ( is_singular() && has_post_thumbnail() ) {
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		if ( $thumb ) {
			$image = $thumb[0];
		}
	}

	if ( $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	}
	echo '<meta name="theme-color" content="#0E5348">' . "\n";
	echo '<meta name="color-scheme" content="light">' . "\n";
	printf( '<meta property="og:type" content="%s">' . "\n", is_singular( 'post' ) ? 'article' : 'website' );
	echo '<meta property="og:site_name" content="U-CAN — Urban Collective Action Network">' . "\n";
	echo '<meta property="og:locale" content="en_IN">' . "\n";
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	if ( $description ) {
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	}
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

	if ( $page_jsonld ) {
		// Already a complete, verified @graph (Organization + WebPage +
		// BreadcrumbList, +FAQPage on URC) - re-emit exactly as extracted.
		printf( '<script type="application/ld+json">%s</script>' . "\n", $page_jsonld );
		return;
	}

	// Fallback for any page/post type that doesn't supply its own graph yet
	// (CPT archives/singles land in phases 3-6) - at minimum, say who the
	// site is.
	$graph = array(
		'@type'       => 'Organization',
		'@id'         => 'https://urban.org.in/#org',
		'name'        => 'Urban Collective Action Network (U-CAN)',
		'alternateName' => 'U-CAN',
		'url'         => 'https://urban.org.in/',
		'email'       => 'connect@urban.org.in',
		'description' => "U-CAN is a network of organisations working together to strengthen urban problem-solving in India's Tier II and Tier III cities.",
		'foundingDate' => '2022',
		'areaServed'  => 'IN',
		'sameAs'      => array(
			'https://www.linkedin.com/company/urban-collective-action-network-u-can/',
			'https://www.youtube.com/@U-CAN24',
		),
	);
	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => array( $graph ) ) )
	);
}
add_action( 'wp_head', 'ucan_document_head' );

// -------------------------------------------------------- primary nav ----
/**
 * The site's real nav (CLAUDE.md §3a): 5 top-level items, most with a
 * dropdown; "Fellowship" is a group divider inside Initiatives' dropdown,
 * not a link. To mark a menu item as a group divider in wp-admin, give it
 * the CSS class `menu-group-heading` (Appearance > Menus > the item's
 * "CSS Classes" field - enable that field from Screen Options if hidden).
 * It renders as a plain heading, never a link, and never has its own
 * dropdown.
 */
function ucan_caret_svg() {
	return '<svg class="ucnav-cv" viewBox="0 0 10 6" aria-hidden="true"><path d="M1 1l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>';
}

function ucan_item_is_current( $item ) {
	$classes = (array) $item->classes;
	foreach ( array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' ) as $c ) {
		if ( in_array( $c, $classes, true ) ) {
			return true;
		}
	}
	return false;
}

function ucan_item_is_group_heading( $item ) {
	return in_array( 'menu-group-heading', (array) $item->classes, true );
}

class UCAN_Nav_Walker_Desktop extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<div class="ucnav-dd">';
	}
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</div>';
	}
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		if ( $depth > 0 ) {
			if ( ucan_item_is_group_heading( $item ) ) {
				$output .= sprintf( '<p class="ucnav-gh">%s</p>', esc_html( $title ) );
			} else {
				$output .= sprintf( '<a href="%s">%s</a>', esc_url( $item->url ), esc_html( $title ) );
			}
			return;
		}

		$has_children = ! empty( $item->has_children );
		$is_on        = ucan_item_is_current( $item );
		// A top-level item can both have a dropdown AND be its own
		// destination (e.g. "About Us" links to /about-us/ as well as
		// opening its submenu) - only render the plain toggle <button>
		// when the item has no real URL of its own ("#", the convention
		// ucan_seed_primary_menu() uses for pure category headers like
		// "Initiatives"/"Events"/"Media").
		$has_own_url = $has_children && '#' !== $item->url && '' !== trim( (string) $item->url );

		if ( $has_children && ! $has_own_url ) {
			$output .= '<div class="ucnav-i">';
			$output .= sprintf(
				'<button type="button" class="ucnav-t%s" aria-expanded="false" aria-haspopup="true">%s%s</button>',
				$is_on ? ' on' : '',
				esc_html( $title ),
				ucan_caret_svg()
			);
		} elseif ( $has_children && $has_own_url ) {
			$output .= '<div class="ucnav-i">';
			$output .= sprintf(
				'<a class="ucnav-t%s" href="%s"%s>%s%s</a>',
				$is_on ? ' on' : '',
				esc_url( $item->url ),
				$is_on ? ' aria-current="page"' : '',
				esc_html( $title ),
				ucan_caret_svg()
			);
		} else {
			$output .= sprintf(
				'<a class="ucnav-t%s" href="%s"%s>%s</a>',
				$is_on ? ' on' : '',
				esc_url( $item->url ),
				$is_on ? ' aria-current="page"' : '',
				esc_html( $title )
			);
		}
	}
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( $depth === 0 && ! empty( $item->has_children ) ) {
			$output .= '</div>'; // .ucnav-i
		}
	}
}

class UCAN_Nav_Walker_Mobile extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<div class="ucmob-l">';
	}
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</div>';
	}
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		if ( $depth > 0 ) {
			if ( ucan_item_is_group_heading( $item ) ) {
				$output .= sprintf( '<p class="ucmob-gh">%s</p>', esc_html( $title ) );
			} else {
				$output .= sprintf( '<a href="%s">%s</a>', esc_url( $item->url ), esc_html( $title ) );
			}
			return;
		}

		$has_children = ! empty( $item->has_children );
		if ( $has_children ) {
			$output .= sprintf( '<div class="ucmob-g"><p class="ucmob-h">%s</p>', esc_html( $title ) );
		} else {
			$output .= sprintf( '<a class="ucmob-t" href="%s">%s</a>', esc_url( $item->url ), esc_html( $title ) );
		}
	}
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( $depth === 0 && ! empty( $item->has_children ) ) {
			$output .= '</div>'; // .ucmob-g
		}
	}
}

/**
 * Prints the desktop (.ucnav-bar) or mobile (.msheet) nav from the same
 * "primary" wp-admin menu. If no menu is assigned yet, falls back to a
 * plain page list so the header never renders empty.
 */
function ucan_nav_menu( $variant ) {
	if ( 'desktop' === $variant ) {
		if ( ! has_nav_menu( 'primary' ) ) {
			echo '<nav class="ucnav-bar" aria-label="Primary"><div class="ucnav">';
			wp_list_pages( array( 'title_li' => '', 'depth' => 1 ) );
			echo '</div></nav>';
			return;
		}
		echo '<nav class="ucnav-bar" aria-label="Primary"><div class="ucnav">';
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'depth'          => 2,
				'walker'         => new UCAN_Nav_Walker_Desktop(),
			)
		);
		echo '</div></nav>';
		return;
	}

	// mobile
	echo '<div class="msheet" id="msheet"><div class="wrap"><div class="ucmob">';
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'depth'          => 2,
				'walker'         => new UCAN_Nav_Walker_Mobile(),
			)
		);
	}
	printf(
		'<a class="ucmob-t ucmob-cta" href="%s">Subscribe</a>',
		esc_url( home_url( '/newsletter/#subscribe' ) )
	);
	echo '</div></div></div>';
}

// --------------------------------------------------- default menu seed ---
/**
 * Creates the real nav once, on first activation, so the site isn't blank
 * until someone hand-builds 15 menu items in wp-admin. Safe to re-run -
 * bails if a "Primary Navigation" menu already exists. Editing afterwards
 * happens entirely in Appearance > Menus; this never runs again once the
 * menu exists.
 */
function ucan_seed_primary_menu() {
	if ( wp_get_nav_menu_object( 'Primary Navigation' ) ) {
		return;
	}
	$menu_id = wp_create_nav_menu( 'Primary Navigation' );

	$add = function ( $title, $url, $parent = 0, $classes = array() ) use ( $menu_id ) {
		return wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $title,
				'menu-item-url'       => $url,
				'menu-item-parent-id' => $parent,
				'menu-item-status'    => 'publish',
				'menu-item-classes'   => implode( ' ', $classes ),
			)
		);
	};

	$about = $add( 'About Us', home_url( '/about-us/' ) );
	$add( 'About U-CAN', home_url( '/about-us/' ), $about );
	$add( 'Our People', home_url( '/our-people/' ), $about );
	$add( 'Impact', home_url( '/impact/' ), $about );

	$init = $add( 'Initiatives', '#' );
	$add( 'Urban Reforms Collective (URC)', home_url( '/urban-reforms-collective/' ), $init );
	// URLs match the old site's real canonical paths, not this build's
	// shorter internal file-slugs (SEO decision - CLAUDE.md §28) - keep
	// these in sync with _scripts/wp/slugs.py's CANONICAL_SLUG map.
	$add( 'Request for Collaboration (RFC)', home_url( '/requests-for-collaboration/' ), $init );
	$add( 'Learning Network for Urban Managers', home_url( '/learning-network-for-urban-managers/' ), $init );
	$add( 'U-CAN Fellowship', home_url( '/u-can-fellowship/' ), $init );
	$add( 'Fellowship', '#', $init, array( 'menu-group-heading' ) );
	$add( 'Meet the 2024-25 Fellows', home_url( '/meet-our-fellows/' ), $init );
	$add( 'Blogs by Our Fellows', home_url( '/blogs-by-our-fellows/' ), $init );
	$add( 'L&D Calendar', home_url( '/fellowship-ld/' ), $init );

	$events = $add( 'Events', '#' );
	$add( 'U-CAN City Mixers', home_url( '/city-mixers/' ), $events );
	$add( 'The U-CAN Annual Forum 2025', home_url( '/annual-forum-2025/' ), $events );

	$add( 'Our Members', home_url( '/our-members/' ) );

	$media = $add( 'Media', '#' );
	$add( 'Newsletter', home_url( '/newsletter/' ), $media );
	$add( 'City Champions', home_url( '/city-champions/' ), $media );
	$add( 'Policy Webinars', home_url( '/policy-webinars/' ), $media );

	$locations = get_theme_mod( 'nav_menu_locations' );
	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}
add_action( 'after_switch_theme', 'ucan_seed_primary_menu' );

// -------------------------------------------------------- ucan_member ----
/**
 * Phase 3: the 23 individual profiles (CLAUDE.md §14), previously
 * profile-<slug>.html. Real canonical is /member/<slug>/ (checked each
 * profile's own <link rel="canonical"> - all 23 agree), hence rewrite
 * slug 'member' below.
 *
 * Field design note: the static build overloaded one "role" string to mean
 * either a job title (Our Team members) or an organisation name (everyone
 * else) - correct on the page, confusing to store. This CPT keeps them as
 * two explicit fields instead:
 *   - job_title    (post meta) - only meaningful for Our Team members
 *   - organisation (post meta) - always the org name ("U-CAN" for the team)
 *   - linkedin     (post meta) - optional personal profile URL
 *   - ucan_member_group (taxonomy) - one or more of the 4 groups a person
 *     can belong to; a person in two groups gets one post with two terms,
 *     reproducing the original's "same person, two <li> cards" behaviour
 *     via one query per group instead of duplicate posts.
 * single-ucan_member.php derives the original's "Our Team" display branch
 * from whether the "Our Team" term is present, and picks the person's
 * "primary" group (the hero badge) as the first match in GROUP_ORDER below
 * - the same order the groups appear on Our People, which is how the
 * static build effectively chose one when a person had two.
 */
define( 'UCAN_MEMBER_GROUP_ORDER', array(
	'founding-circle'    => 'Founding Circle',
	'steering-committee' => 'Steering Committee',
	'stewardship-team'   => 'Stewardship Team',
	'our-team'           => 'Our Team',
) );

function ucan_register_member_cpt() {
	register_post_type(
		'ucan_member',
		array(
			'labels'       => array(
				'name'          => 'Members',
				'singular_name' => 'Member',
				'add_new_item'  => 'Add New Member',
				'edit_item'     => 'Edit Member',
			),
			'public'       => true,
			'has_archive'  => false, // page-our-people.php is the listing
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-groups',
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'      => array( 'slug' => 'member', 'with_front' => false ),
		)
	);

	register_taxonomy(
		'ucan_member_group',
		'ucan_member',
		array(
			'labels'       => array( 'name' => 'Groups', 'singular_name' => 'Group' ),
			'public'       => true,
			'hierarchical' => false, // a person can hold more than one
			'show_in_rest' => true,
			'rewrite'      => false, // groups aren't their own archive page
		)
	);
}
add_action( 'init', 'ucan_register_member_cpt' );

/**
 * Creates the 4 fixed group terms once, in display order, on theme
 * activation - same "seed it, then it's just wp-admin from here" pattern
 * as ucan_seed_primary_menu(). Safe to re-run: wp_insert_term() is a
 * no-op (WP_Error, caught) if the term already exists.
 */
function ucan_seed_member_groups() {
	foreach ( UCAN_MEMBER_GROUP_ORDER as $slug => $name ) {
		if ( ! term_exists( $slug, 'ucan_member_group' ) ) {
			wp_insert_term( $name, 'ucan_member_group', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'after_switch_theme', 'ucan_seed_member_groups' );

function ucan_member_meta_box() {
	add_meta_box(
		'ucan_member_details',
		'Member Details',
		'ucan_render_member_meta_box',
		'ucan_member',
		'side'
	);
}
add_action( 'add_meta_boxes', 'ucan_member_meta_box' );

function ucan_render_member_meta_box( $post ) {
	wp_nonce_field( 'ucan_member_save', 'ucan_member_nonce' );
	$job_title    = get_post_meta( $post->ID, 'job_title', true );
	$organisation = get_post_meta( $post->ID, 'organisation', true );
	$linkedin     = get_post_meta( $post->ID, 'linkedin', true );
	?>
	<p>
		<label for="ucan_organisation"><strong>Organisation</strong> (required - the org name for everyone, "U-CAN" for Our Team)</label><br>
		<input type="text" id="ucan_organisation" name="ucan_organisation" class="widefat" value="<?php echo esc_attr( $organisation ); ?>">
	</p>
	<p>
		<label for="ucan_job_title"><strong>Job title</strong> (Our Team members only - leave blank otherwise)</label><br>
		<input type="text" id="ucan_job_title" name="ucan_job_title" class="widefat" value="<?php echo esc_attr( $job_title ); ?>">
	</p>
	<p>
		<label for="ucan_linkedin"><strong>LinkedIn URL</strong> (optional)</label><br>
		<input type="url" id="ucan_linkedin" name="ucan_linkedin" class="widefat" value="<?php echo esc_attr( $linkedin ); ?>" placeholder="https://www.linkedin.com/in/…">
	</p>
	<p class="description">Set the person's group(s) in the "Groups" box elsewhere on this screen.</p>
	<?php
}

function ucan_save_member_meta( $post_id ) {
	if ( ! isset( $_POST['ucan_member_nonce'] ) || ! wp_verify_nonce( $_POST['ucan_member_nonce'], 'ucan_member_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array( 'organisation' => 'ucan_organisation', 'job_title' => 'ucan_job_title' ) as $meta_key => $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}
	if ( isset( $_POST['ucan_linkedin'] ) ) {
		update_post_meta( $post_id, 'linkedin', esc_url_raw( wp_unslash( $_POST['ucan_linkedin'] ) ) );
	}
}
add_action( 'save_post_ucan_member', 'ucan_save_member_meta' );

/**
 * Shared by single-ucan_member.php and page-our-people.php's card loop:
 * given a WP_Post for a ucan_member, returns the display fields the
 * original static build computed per person (see the field-design note
 * above) - $r['is_team'], $r['primary_group'], $r['all_groups'] (display
 * string, "A · B"), $r['role'] and $r['affiliation'] (already branched the
 * same way profile-siddharth-pandit.html vs profile-gautham-ravichander.html
 * differ).
 */
function ucan_member_display_fields( $post ) {
	$terms = wp_get_post_terms( $post->ID, 'ucan_member_group' );
	$slugs = wp_list_pluck( $terms, 'slug' );
	$names = array();
	$primary_group = '';
	foreach ( UCAN_MEMBER_GROUP_ORDER as $slug => $name ) {
		if ( in_array( $slug, $slugs, true ) ) {
			$names[] = $name;
			if ( ! $primary_group ) {
				$primary_group = $name;
			}
		}
	}
	$is_team      = in_array( 'our-team', $slugs, true );
	$job_title    = get_post_meta( $post->ID, 'job_title', true );
	$organisation = get_post_meta( $post->ID, 'organisation', true );
	$linkedin     = get_post_meta( $post->ID, 'linkedin', true );

	return array(
		'is_team'       => $is_team,
		'primary_group' => $primary_group ? $primary_group : 'Our People',
		'all_groups'    => implode( ' · ', $names ),
		'role'          => $is_team && $job_title ? $job_title : $organisation,
		'affiliation'   => $is_team ? 'U-CAN · Urban Collective Action Network' : implode( ' · ', $names ),
		'organisation'  => $organisation,
		'linkedin'      => $linkedin,
	);
}

// --------------------------------------------------------- ucan_fellow ---
/**
 * Phase 4: the 8 U-CAN Fellows (was profile-fellow-<slug>.html). Real
 * canonical is a bare root-level slug on all 8 pages (e.g.
 * /aanchal-aggarwal/, not /fellow/aanchal-aggarwal/ or /fellow-blogs/…/)
 * - checked each profile's own <link rel="canonical"> - hence the empty
 * rewrite slug below. Post title = the fellow's name; post_content = bio.
 */
function ucan_register_fellow_cpt() {
	register_post_type(
		'ucan_fellow',
		array(
			'labels'       => array(
				'name'          => 'Fellows',
				'singular_name' => 'Fellow',
				'add_new_item'  => 'Add New Fellow',
				'edit_item'     => 'Edit Fellow',
			),
			'public'       => true,
			'has_archive'  => false, // page-meet-our-fellows.php is the listing
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-businessperson',
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'      => array( 'slug' => '', 'with_front' => false ),
		)
	);
}
add_action( 'init', 'ucan_register_fellow_cpt' );

function ucan_fellow_meta_box() {
	add_meta_box( 'ucan_fellow_details', 'Fellow Details', 'ucan_render_fellow_meta_box', 'ucan_fellow', 'side' );
}
add_action( 'add_meta_boxes', 'ucan_fellow_meta_box' );

function ucan_render_fellow_meta_box( $post ) {
	wp_nonce_field( 'ucan_fellow_save', 'ucan_fellow_nonce' );
	$fields = array(
		'ucan_host_organisation' => array( 'host_organisation', 'Host organisation', 'text' ),
		'ucan_joining_location'  => array( 'joining_location', 'Joining location', 'text' ),
		'ucan_education'         => array( 'education', 'Education', 'text' ),
		'ucan_cohort_label'      => array( 'cohort_label', 'Cohort label (defaults to "U-CAN Fellow · 2024-25")', 'text' ),
	);
	foreach ( $fields as $field => $spec ) {
		list( $meta_key, $label, $type ) = $spec;
		$value = get_post_meta( $post->ID, $meta_key, true );
		printf(
			'<p><label for="%1$s"><strong>%2$s</strong></label><br><input type="%3$s" id="%1$s" name="%1$s" class="widefat" value="%4$s"></p>',
			esc_attr( $field ), esc_html( $label ), esc_attr( $type ), esc_attr( $value )
		);
	}
	$social = get_post_meta( $post->ID, 'social_links', true );
	printf(
		'<p><label for="ucan_social_links"><strong>Social links</strong> (one per line - not everyone has one, some have several: seen LinkedIn, Facebook and X across the 8 fellows)</label><br><textarea id="ucan_social_links" name="ucan_social_links" class="widefat" rows="3" placeholder="https://www.linkedin.com/in/…">%s</textarea></p>',
		esc_textarea( $social )
	);
}

function ucan_save_fellow_meta( $post_id ) {
	if ( ! isset( $_POST['ucan_fellow_nonce'] ) || ! wp_verify_nonce( $_POST['ucan_fellow_nonce'], 'ucan_fellow_save' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$map = array(
		'ucan_host_organisation' => 'host_organisation',
		'ucan_joining_location'  => 'joining_location',
		'ucan_education'         => 'education',
		'ucan_cohort_label'      => 'cohort_label',
	);
	foreach ( $map as $field => $meta_key ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}
	if ( isset( $_POST['ucan_social_links'] ) ) {
		$lines = preg_split( '/[\r\n]+/', wp_unslash( $_POST['ucan_social_links'] ) );
		$urls  = array_filter( array_map( 'esc_url_raw', array_map( 'trim', $lines ) ) );
		update_post_meta( $post_id, 'social_links', implode( "\n", $urls ) );
	}
}
add_action( 'save_post_ucan_fellow', 'ucan_save_fellow_meta' );

/** Shared by single-ucan_fellow.php, page-meet-our-fellows.php and page-u-can-fellowship.php's cohort list. */
function ucan_fellow_display_fields( $post ) {
	$cohort = get_post_meta( $post->ID, 'cohort_label', true );
	$social = get_post_meta( $post->ID, 'social_links', true );
	return array(
		'host_organisation' => get_post_meta( $post->ID, 'host_organisation', true ),
		'joining_location'  => get_post_meta( $post->ID, 'joining_location', true ),
		'education'         => get_post_meta( $post->ID, 'education', true ),
		'social_urls'       => $social ? array_filter( array_map( 'trim', explode( "\n", $social ) ) ) : array(),
		'cohort_label'      => $cohort ? $cohort : 'U-CAN Fellow · 2024-25',
	);
}

/**
 * A fellow can have zero, one, or several social links, on different
 * platforms (seen across the 8 fellows: LinkedIn, Facebook, X) - not the
 * single-LinkedIn-button the member profiles use. Detects platform from
 * the URL's host and renders the same `.soc on-dark` stroke-icon row
 * every fellow/blog-author-aside template needs, icons lifted verbatim
 * from the source markup (never redrawn by guesswork).
 */
function ucan_social_icon_svg( $platform ) {
	$paths = array(
		'linkedin'  => '<rect x="3.2" y="3.2" width="17.6" height="17.6" rx="2.4"/><path d="M7.6 10.4v6.4M7.6 7.6v.01M11.4 16.8v-6.4M11.4 13.2a2.6 2.6 0 0 1 5.2 0v3.6"/>',
		'facebook'  => '<path d="M14.6 8.2h2.2V5.1h-2.4c-2.3 0-3.7 1.5-3.7 3.9v1.8H8.4v3.1h2.3v7h3.2v-7h2.4l.5-3.1h-2.9V9.4c0-.8.3-1.2.7-1.2z"/>',
		'x'         => '<path d="M4 3.6h4.2l4.1 5.6 4.8-5.6h2.6l-6.2 7.2 6.7 9.6h-4.2l-4.5-6.2-5.3 6.2H3.6l6.8-7.9z"/>',
		'instagram' => '<rect x="3.2" y="3.2" width="17.6" height="17.6" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="0.6"/>',
		'generic'   => '<path d="M9.5 14.5 14.5 9.5M11 6.5h5.5c1.1 0 2 .9 2 2V14M13 17.5H7.5c-1.1 0-2-.9-2-2V9.5"/>',
	);
	return isset( $paths[ $platform ] ) ? $paths[ $platform ] : $paths['generic'];
}

function ucan_social_platform_from_url( $url ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( ! $host ) {
		return array( 'generic', 'website' );
	}
	$host = preg_replace( '/^www\./', '', strtolower( $host ) );
	$map  = array(
		'linkedin.com'  => array( 'linkedin', 'LinkedIn' ),
		'facebook.com'  => array( 'facebook', 'Facebook' ),
		'x.com'         => array( 'x', 'X' ),
		'twitter.com'   => array( 'x', 'X' ),
		'instagram.com' => array( 'instagram', 'Instagram' ),
	);
	return isset( $map[ $host ] ) ? $map[ $host ] : array( 'generic', 'website' );
}

/** $urls: array of social URLs. $name: the person's name, for aria-label. Empty string if $urls is empty. */
function ucan_social_row( $urls, $name ) {
	if ( ! $urls ) {
		return '';
	}
	$links = '';
	foreach ( $urls as $url ) {
		list( $platform, $label ) = ucan_social_platform_from_url( $url );
		$links .= sprintf(
			'<a href="%s" target="_blank" rel="noopener noreferrer" aria-label="%s on %s"><svg class="ic" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%s</svg></a>',
			esc_url( $url ), esc_attr( $name ), esc_attr( $label ), ucan_social_icon_svg( $platform )
		);
	}
	return $links;
}

// ----------------------------------------------------- ucan_ld_session ---
/**
 * Phase 4: the 12 Fellowship L&D sessions (was ld-<slug>.html). Real
 * canonical is /etn/<slug>/ on all 12 pages ("etn" = the old site's own
 * Events/Training custom post type slug) - checked each session's own
 * <link rel="canonical">, hence rewrite slug 'etn' below. post_title is
 * the full session title exactly as shown (e.g. "Thinking Better, Alone
 * Together by Manali Shah") - kept as one string rather than reconstructed
 * from a separate lead-name field, matching the source verbatim. Ordering
 * (for the prev/next "Later session"/"Earlier session" nav and the
 * calendar listing) uses the post's own post_date - set it to the actual
 * session date on import so WP's native adjacent-post functions and
 * date ordering just work, no extra meta needed for that part.
 */
function ucan_register_ld_session_cpt() {
	register_post_type(
		'ucan_ld_session',
		array(
			'labels'       => array(
				'name'          => 'L&D Sessions',
				'singular_name' => 'L&D Session',
				'add_new_item'  => 'Add New L&D Session',
				'edit_item'     => 'Edit L&D Session',
			),
			'public'       => true,
			'has_archive'  => false, // page-fellowship-ld.php is the listing
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-welcome-learn-more',
			'supports'     => array( 'title', 'editor' ),
			'rewrite'      => array( 'slug' => 'etn', 'with_front' => false ),
		)
	);
}
add_action( 'init', 'ucan_register_ld_session_cpt' );

function ucan_ld_session_meta_box() {
	add_meta_box( 'ucan_ld_session_details', 'Session Details', 'ucan_render_ld_session_meta_box', 'ucan_ld_session', 'side' );
}
add_action( 'add_meta_boxes', 'ucan_ld_session_meta_box' );

function ucan_render_ld_session_meta_box( $post ) {
	wp_nonce_field( 'ucan_ld_session_save', 'ucan_ld_session_nonce' );
	$fields = array(
		'ucan_lead_name'  => array( 'lead_name', 'Session lead name' ),
		'ucan_lead_bio'   => array( 'lead_bio', 'Session lead bio (one paragraph)' ),
		'ucan_time_range' => array( 'time_range', 'Time (e.g. "10:00 am - 12:00 pm")' ),
		'ucan_timezone'   => array( 'timezone', 'Timezone label (defaults to "Asia/Calcutta")' ),
		'ucan_session_type' => array( 'session_type', 'Session type (e.g. "Masterclass Session")' ),
	);
	foreach ( $fields as $field => $spec ) {
		list( $meta_key, $label ) = $spec;
		$value = get_post_meta( $post->ID, $meta_key, true );
		if ( 'lead_bio' === $meta_key ) {
			printf(
				'<p><label for="%1$s"><strong>%2$s</strong></label><br><textarea id="%1$s" name="%1$s" class="widefat" rows="4">%3$s</textarea></p>',
				esc_attr( $field ), esc_html( $label ), esc_textarea( $value )
			);
		} else {
			printf(
				'<p><label for="%1$s"><strong>%2$s</strong></label><br><input type="text" id="%1$s" name="%1$s" class="widefat" value="%3$s"></p>',
				esc_attr( $field ), esc_html( $label ), esc_attr( $value )
			);
		}
	}
}

function ucan_save_ld_session_meta( $post_id ) {
	if ( ! isset( $_POST['ucan_ld_session_nonce'] ) || ! wp_verify_nonce( $_POST['ucan_ld_session_nonce'], 'ucan_ld_session_save' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$map = array(
		'ucan_lead_name'    => 'lead_name',
		'ucan_lead_bio'     => 'lead_bio',
		'ucan_time_range'   => 'time_range',
		'ucan_timezone'     => 'timezone',
		'ucan_session_type' => 'session_type',
	);
	foreach ( $map as $field => $meta_key ) {
		if ( isset( $_POST[ $field ] ) ) {
			$sanitizer = 'lead_bio' === $meta_key ? 'sanitize_textarea_field' : 'sanitize_text_field';
			update_post_meta( $post_id, $meta_key, $sanitizer( wp_unslash( $_POST[ $field ] ) ) );
		}
	}
}
add_action( 'save_post_ucan_ld_session', 'ucan_save_ld_session_meta' );

function ucan_ld_session_display_fields( $post ) {
	$tz = get_post_meta( $post->ID, 'timezone', true );
	return array(
		'lead_name'    => get_post_meta( $post->ID, 'lead_name', true ),
		'lead_bio'     => get_post_meta( $post->ID, 'lead_bio', true ),
		'time_range'   => get_post_meta( $post->ID, 'time_range', true ),
		'timezone'     => $tz ? $tz : 'Asia/Calcutta',
		'session_type' => get_post_meta( $post->ID, 'session_type', true ),
		'date_display' => get_the_date( 'F j, Y', $post ),
	);
}

// --------------------------------------------------------- ucan_blog -----
/**
 * Phase 4: the fellow blog posts (was blog-<slug>.html), and their topic
 * tags (was blog-tag-<slug>.html). A dedicated CPT rather than native
 * `post` on purpose - real canonical is /fellow-blogs/<slug>/, and forcing
 * that onto native posts would mean changing the site's global permalink
 * structure (a wp-admin setting, not something a theme should silently
 * override) for every post on the site, not just these. The
 * fellow_blog_tag taxonomy shares the same rewrite base, matching each
 * topic page's real canonical (/fellow-blogs/<tag-slug>/, e.g.
 * /fellow-blogs/air-quality/) - a generic taxonomy-fellow_blog_tag.php
 * template (WP's own template-hierarchy convention) serves all of them,
 * replacing the 13 separate blog-tag-*.html files with one query.
 *
 * A post's author is one of the 8 ucan_fellow posts (fellow_id meta,
 * an ID reference) rather than a WP user account - fellows don't log in,
 * they're subjects, so a real WP User per fellow would be the wrong tool.
 */
function ucan_register_blog_cpt() {
	register_post_type(
		'ucan_blog',
		array(
			'labels'       => array(
				'name'          => 'Fellow Blog Posts',
				'singular_name' => 'Fellow Blog Post',
				'add_new_item'  => 'Add New Blog Post',
				'edit_item'     => 'Edit Blog Post',
			),
			'public'       => true,
			'has_archive'  => false, // page-blogs-by-our-fellows.php is the listing
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-edit-page',
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'      => array( 'slug' => 'fellow-blogs', 'with_front' => false ),
		)
	);

	register_taxonomy(
		'fellow_blog_tag',
		'ucan_blog',
		array(
			'labels'       => array( 'name' => 'Topics', 'singular_name' => 'Topic' ),
			'public'       => true,
			'hierarchical' => false,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'fellow-blogs', 'with_front' => false ),
		)
	);
}
add_action( 'init', 'ucan_register_blog_cpt' );

function ucan_blog_meta_box() {
	add_meta_box( 'ucan_blog_details', 'Blog Post Details', 'ucan_render_blog_meta_box', 'ucan_blog', 'side' );
}
add_action( 'add_meta_boxes', 'ucan_blog_meta_box' );

function ucan_render_blog_meta_box( $post ) {
	wp_nonce_field( 'ucan_blog_save', 'ucan_blog_nonce' );
	$current = get_post_meta( $post->ID, 'fellow_id', true );
	$fellows = get_posts( array( 'post_type' => 'ucan_fellow', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
	echo '<p><label for="ucan_fellow_id"><strong>Fellow (author)</strong></label><br>';
	echo '<select id="ucan_fellow_id" name="ucan_fellow_id" class="widefat"><option value="">— Select —</option>';
	foreach ( $fellows as $fellow ) {
		printf( '<option value="%1$d"%2$s>%3$s</option>', $fellow->ID, selected( $current, $fellow->ID, false ), esc_html( $fellow->post_title ) );
	}
	echo '</select></p>';
}

function ucan_save_blog_meta( $post_id ) {
	if ( ! isset( $_POST['ucan_blog_nonce'] ) || ! wp_verify_nonce( $_POST['ucan_blog_nonce'], 'ucan_blog_save' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['ucan_fellow_id'] ) ) {
		update_post_meta( $post_id, 'fellow_id', absint( $_POST['ucan_fellow_id'] ) );
	}
}
add_action( 'save_post_ucan_blog', 'ucan_save_blog_meta' );

/**
 * Resolves a blog post's fellow_id meta to the fellow's WP_Post, or null
 * if unset/not found (a post can exist briefly without an author assigned
 * - templates must handle that, not assume it's always there).
 */
function ucan_blog_author( $post ) {
	$fellow_id = get_post_meta( $post->ID, 'fellow_id', true );
	return $fellow_id ? get_post( $fellow_id ) : null;
}

/** get_the_terms() returns false (none) or WP_Error (bad taxonomy) as well as an array - always returns a plain array so templates can foreach it safely. */
function ucan_post_tags( $post_id ) {
	$terms = get_the_terms( $post_id, 'fellow_blog_tag' );
	return is_array( $terms ) ? $terms : array();
}
