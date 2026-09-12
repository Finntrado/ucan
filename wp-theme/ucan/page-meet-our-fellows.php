<?php
/**
 * Template Name: Meet the Fellows
 * Auto-applies to a WP Page whose slug is "meet-our-fellows" (file-name
 * convention - page-meet-our-fellows.php).
 *
 * Hero extracted verbatim from standalone/meet-the-fellows.html; the
 * 8-card grid is now a live ucan_fellow CPT query (CLAUDE.md §28), ordered
 * by menu_order to match the cohort's real 01-08 numbering - renders
 * nothing until those posts exist (phase 7's importer). The page's own
 * JSON-LD (already carrying correct absolute urban.org.in canonical URLs)
 * is re-emitted unchanged, so functions.php's generic wp_head hook skips
 * its own Organization node for this page.
 */

$ucan_page_meta = array(
	'description' => 'Meet the eight women leaders of U-CAN\'s inaugural Fellowship cohort, hosted across India\'s leading urban institutions in 2024-25.',
);
$ucan_page_jsonld = '{
 "@context": "https://schema.org",
 "@graph": [
  {"@type": "Organization", "@id": "https://urban.org.in/#org",
   "name": "Urban Collective Action Network (U-CAN)", "alternateName": "U-CAN",
   "url": "https://urban.org.in/", "email": "connect@urban.org.in",
   "areaServed": {"@type": "Country", "name": "India"}},
  {"@type": "WebPage", "@id": "https://urban.org.in/meet-our-fellows/#webpage", "url": "https://urban.org.in/meet-our-fellows/",
   "name": "Meet the 2024-25 Fellows | U-CAN Fellowship", "description": "Meet the eight women leaders of U-CAN&#x27;s inaugural Fellowship cohort.", "inLanguage": "en-IN",
   "isPartOf": {"@id": "https://urban.org.in/#website"},
   "about": {"@id": "https://urban.org.in/#org"},
   "speakable": {"@type": "SpeakableSpecification", "cssSelector": ["h1", ".hero-lede"]}},
  {"@type": "BreadcrumbList", "@id": "https://urban.org.in/meet-our-fellows/#breadcrumb", "itemListElement": [
   {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://urban.org.in/"},
   {"@type": "ListItem", "position": 2, "name": "U-CAN Fellowship", "item": "https://urban.org.in/u-can-fellowship/"},
   {"@type": "ListItem", "position": 3, "name": "Meet the Fellows", "item": "https://urban.org.in/meet-our-fellows/"}]},
  {"@type": "ItemList", "@id": "https://urban.org.in/meet-our-fellows/#fellows", "name": "The 2024-25 U-CAN Fellows", "itemListElement": [
   {"@type": "ListItem", "position": 1, "item": {"@type": "Person", "name": "Aanchal Aggarwal", "affiliation": {"@type": "Organization", "name": "Janaagraha"}, "url": "https://urban.org.in/aanchal-aggarwal/"}},
   {"@type": "ListItem", "position": 2, "item": {"@type": "Person", "name": "Aashima Arora", "affiliation": {"@type": "Organization", "name": "WRI India &amp; U-CAN"}, "url": "https://urban.org.in/aashima-arora/"}},
   {"@type": "ListItem", "position": 3, "item": {"@type": "Person", "name": "Anwesha Bhattacharya", "affiliation": {"@type": "Organization", "name": "Safetipin"}, "url": "https://urban.org.in/anwesha-bhattacharya/"}},
   {"@type": "ListItem", "position": 4, "item": {"@type": "Person", "name": "Manisha Bisht", "affiliation": {"@type": "Organization", "name": "Shelter Associates"}, "url": "https://urban.org.in/manisha-bisht/"}},
   {"@type": "ListItem", "position": 5, "item": {"@type": "Person", "name": "Ramya M A", "affiliation": {"@type": "Organization", "name": "Artha Global"}, "url": "https://urban.org.in/ramya-ma/"}},
   {"@type": "ListItem", "position": 6, "item": {"@type": "Person", "name": "Sharathppriyaa Venkatesan", "affiliation": {"@type": "Organization", "name": "Reap Benefit"}, "url": "https://urban.org.in/sharathappriyaa-venkatesan/"}},
   {"@type": "ListItem", "position": 7, "item": {"@type": "Person", "name": "Shubhi Kesarwani", "affiliation": {"@type": "Organization", "name": "GuruJal"}, "url": "https://urban.org.in/shubhi-kesarwani/"}},
   {"@type": "ListItem", "position": 8, "item": {"@type": "Person", "name": "Shreya Krishnan", "affiliation": {"@type": "Organization", "name": "SKDO"}, "url": "https://urban.org.in/shreya-krishnan/"}}]}
 ]
}';

get_header();
?>


<section class="hero" aria-labelledby="pt">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <a href="<?php echo esc_url( home_url( '/u-can-fellowship/' ) ); ?>">U-CAN Fellowship</a> <span aria-hidden="true">/</span> <span>Meet the Fellows</span></nav>
    <p class="hero-tag">U-CAN Fellows</p>
    <h1 id="pt">Meet the 2024-25 Fellows</h1>
    <p class="hero-lede">Eight women leaders, embedded for a year within urban institutions across
      India, working on housing, transportation, sustainability, and inclusion.</p>
  </div>
</section>

<?php
/**
 * Phase 4: dynamic replacement for the static 8-card grid phase-2-style
 * extraction produced - queries the ucan_fellow CPT directly, ordered by
 * menu_order (set on import to match the cohort's real 01-08 sequence,
 * not alphabetical - checked against the source's own "prow" numbering
 * on the Fellowship page, which follows the same order).
 */
$fellows = new WP_Query(
	array(
		'post_type'      => 'ucan_fellow',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	)
);
?>
<section class="sec" aria-label="The 2024-25 Fellows">
  <div class="wrap">
    <ul class="fgrid" aria-label="The 2024-25 U-CAN Fellows">
	<?php while ( $fellows->have_posts() ) : $fellows->the_post(); $f = ucan_fellow_display_fields( get_post() ); ?>
      <li class="rv">
        <div class="fcard">
          <a class="flink" href="<?php the_permalink(); ?>">
	    <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'medium', array( 'width' => 260, 'height' => 260, 'loading' => 'lazy', 'decoding' => 'async', 'alt' => get_the_title() . ', U-CAN Fellow at ' . $f['host_organisation'] . ', portrait photo' ) ); ?>
	    <?php else : ?>
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/monogram-placeholder.svg" width="260" height="260" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" decoding="async">
	    <?php endif; ?>
            <span class="fb">
              <span class="fn"><?php the_title(); ?></span>
              <span class="fo"><?php echo esc_html( $f['host_organisation'] ); ?></span>
              <span class="fg">View Details ↗</span>
            </span>
          </a>
	  <?php if ( $f['social_urls'] ) : ?>
          <div class="soc"><?php echo ucan_social_row( $f['social_urls'], get_the_title() ); ?></div>
	  <?php endif; ?>
        </div>
      </li>
	<?php endwhile; wp_reset_postdata(); ?>
    </ul>
  </div>
</section>

<section class="ctaband" aria-label="More from the Fellowship">
  <div class="wrap">
    <p>Follow their work through the year</p>
    <a class="btn" href="<?php echo esc_url( home_url( '/blogs-by-our-fellows/' ) ); ?>">Blogs by Our Fellows <span class="ar" aria-hidden="true">→</span></a>
    <a class="btn line" href="<?php echo esc_url( home_url( '/u-can-fellowship/' ) ); ?>">About the Fellowship <span class="ar" aria-hidden="true">→</span></a>
  </div>
</section>


<?php
get_footer();
