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
	$description = '';
	if ( is_singular() ) {
		global $post;
		$custom = get_post_meta( $post->ID, 'meta_description', true );
		$description = $custom ? $custom : wp_strip_all_tags( get_the_excerpt( $post ) );
	} elseif ( is_front_page() ) {
		$description = get_bloginfo( 'description' );
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

		if ( $has_children ) {
			$output .= '<div class="ucnav-i">';
			$output .= sprintf(
				'<button type="button" class="ucnav-t%s" aria-expanded="false" aria-haspopup="true">%s%s</button>',
				$is_on ? ' on' : '',
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

	$about = $add( 'About Us', home_url( '/about/' ) );
	$add( 'About U-CAN', home_url( '/about/' ), $about );
	$add( 'Our People', home_url( '/our-people/' ), $about );
	$add( 'Impact', home_url( '/impact/' ), $about );

	$init = $add( 'Initiatives', '#' );
	$add( 'Urban Reforms Collective (URC)', home_url( '/urban-reforms-collective/' ), $init );
	$add( 'Request for Collaboration (RFC)', home_url( '/rfc/' ), $init );
	$add( 'Learning Network for Urban Managers', home_url( '/learning-network/' ), $init );
	$add( 'U-CAN Fellowship', home_url( '/fellowship/' ), $init );
	$add( 'Fellowship', '#', $init, array( 'menu-group-heading' ) );
	$add( 'Meet the 2024-25 Fellows', home_url( '/meet-the-fellows/' ), $init );
	$add( 'Blogs by Our Fellows', home_url( '/fellow-blogs/' ), $init );
	$add( 'L&D Calendar', home_url( '/ld-calendar/' ), $init );

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
