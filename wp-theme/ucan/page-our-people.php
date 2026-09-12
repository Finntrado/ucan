<?php
/**
 * Template Name: Our People
 * Auto-applies to a WP Page whose slug is "our-people" (file-name
 * convention - page-our-people.php).
 *
 * Phase 2 built this from standalone/our-people.html's <main> verbatim;
 * phase 3 (CLAUDE.md §28) replaced the static 28-card PEOPLE section with
 * a live ucan_member CPT loop, grouped by the ucan_member_group taxonomy -
 * see functions.php's ucan_register_member_cpt()/ucan_member_display_fields().
 * The hero above it is still the phase-2 verbatim block; only the card
 * markup itself is now generated. The page's own JSON-LD (already carrying
 * correct absolute urban.org.in canonical URLs) is re-emitted unchanged,
 * so functions.php's generic wp_head hook skips its own Organization node
 * for this page.
 */

$ucan_page_meta = array(
	'description' => 'Meet the founders, steering committee and team driving U-CAN\'s work to strengthen urban problem-solving in India\'s cities.',
);
$ucan_page_jsonld = '{

  "@context": "https://schema.org",

  "@graph": [

    {

      "@type": "Organization",

      "@id": "https://urban.org.in/#org",

      "name": "Urban Collective Action Network (U-CAN)",

      "alternateName": "U-CAN",

      "url": "https://urban.org.in/",

      "email": "connect@urban.org.in",

      "description": "U-CAN is a network of organisations working together to strengthen urban problem-solving in India\'s Tier II and Tier III cities.",

      "foundingDate": "2022",

      "areaServed": "IN",

      "sameAs": [

        "https://www.linkedin.com/company/urban-collective-action-network-u-can/",

        "https://www.youtube.com/@U-CAN24"

      ]

    },

    {

      "@type": "WebPage",

      "@id": "https://urban.org.in/our-people/#page",

      "url": "https://urban.org.in/our-people/",

      "name": "Our People | U-CAN, Urban Collective Action Network",

      "description": "Meet the founders, steering committee and team driving U-CAN\'s work to strengthen urban problem-solving in India\'s cities.",

      "isPartOf": {

        "@id": "https://urban.org.in/#org"

      },

      "inLanguage": "en-IN",

      "speakable": {

        "@type": "SpeakableSpecification",

        "cssSelector": [

          "h1",

          ".hero-lede",

          ".lede"

        ]

      }

    },

    {

      "@type": "BreadcrumbList",

      "itemListElement": [

        {

          "@type": "ListItem",

          "position": 1,

          "name": "Home",

          "item": "https://urban.org.in/"

        },

        {

          "@type": "ListItem",

          "position": 2,

          "name": "Our People",

          "item": "https://urban.org.in/our-people/"

        }

      ]

    }

  ]

}';

get_header();
?>




<!-- HERO -->

<section class="hero" aria-labelledby="h1">

  <div class="hero-in">

    <nav class="crumb" aria-label="Breadcrumb">

      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">/</span><span>Our People</span>

    </nav>

    <p class="hero-tag">The people behind the network</p>

    <h1 id="h1">Our People</h1>

    <p class="hero-lede">U-CAN is run by a small backbone team and guided by a wider circle of founders and member representatives who steer the network's strategy and hold it accountable to its mission.</p>

  </div>

</section>
<!-- PEOPLE -->
<section class="sec" id="people" aria-label="Our People">
  <div class="wrap">
<?php
/**
 * Phase 3: dynamic replacement for the static 28-card markup phase 2
 * extracted verbatim. One WP_Query per group, in the same fixed order the
 * static page used (UCAN_MEMBER_GROUP_ORDER, functions.php) - a person in
 * two groups shows up once per group they belong to, exactly reproducing
 * the original's "same person, two <li> cards" result, just driven by the
 * ucan_member_group taxonomy instead of duplicate HTML.
 *
 * Renders nothing per group until real ucan_member posts exist (the
 * phase-7 importer) - expected, see CLAUDE.md §28.
 */
$anchor_id = array(
	'founding-circle'    => 'founding',
	'steering-committee' => 'steering',
	'stewardship-team'   => 'stewardship',
	'our-team'           => 'team',
);
foreach ( UCAN_MEMBER_GROUP_ORDER as $group_slug => $group_name ) :
	$members = new WP_Query(
		array(
			'post_type'      => 'ucan_member',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'tax_query'      => array(
				array(
					'taxonomy' => 'ucan_member_group',
					'field'    => 'slug',
					'terms'    => $group_slug,
				),
			),
		)
	);
	if ( ! $members->have_posts() ) {
		continue;
	}
	?>
    <div class="grp" id="<?php echo esc_attr( $anchor_id[ $group_slug ] ); ?>">
      <div class="grp-head"><h2><?php echo esc_html( $group_name ); ?></h2></div>
      <ul class="people rv" aria-label="<?php echo esc_attr( $group_name ); ?>">
	<?php
	while ( $members->have_posts() ) :
		$members->the_post();
		$f = ucan_member_display_fields( get_post() );
		?>
        <li class="pcard rv in">
          <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() . ', ' . $f['role'] . ' — view profile' ); ?>">
	    <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'p-ava', 'width' => 50, 'height' => 50, 'loading' => 'lazy', 'decoding' => 'async', 'alt' => get_the_title() ) ); ?>
	    <?php else : ?>
            <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/monogram-placeholder.svg" width="50" height="50" loading="lazy" decoding="async" alt="<?php echo esc_attr( get_the_title() ); ?>">
	    <?php endif; ?>
            <span class="p-info">
              <span class="p-name"><?php the_title(); ?></span>
              <span class="p-role"><?php echo esc_html( $f['role'] ); ?></span>
            </span>
            <span class="p-go" aria-hidden="true">↗</span>
          </a>
        </li>
	<?php
	endwhile;
	wp_reset_postdata();
	?>
      </ul>
    </div>
<?php endforeach; ?>
  </div>
</section>
<!-- CTA PAIR -->





<!-- NEWSLETTER -->






<?php
get_footer();
