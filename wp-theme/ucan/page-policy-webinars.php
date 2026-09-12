<?php
/**
 * Template Name: Policy Webinars
 * Auto-applies to a WP Page whose slug is "policy-webinars" (file-name
 * convention - page-policy-webinars.php).
 *
 * Hero extracted verbatim from standalone/policy-webinars.html; the 9-row
 * list is now a live ucan_etn_event CPT query (event_kind=policy_webinar -
 * CLAUDE.md §28), newest first - renders empty until those posts exist
 * (phase 7's importer). The page's own JSON-LD (already carrying correct
 * absolute urban.org.in canonical URLs) is re-emitted unchanged, so
 * functions.php's generic wp_head hook skips its own Organization node
 * for this page.
 */

$ucan_page_meta = array(
	'description' => 'Explore U-CAN\'s Policy Webinars, conversations with sector experts, donor representatives, and member organizations on urban housing, governance, and community engagement.',
);
$ucan_page_jsonld = '{
 "@context": "https://schema.org",
 "@graph": [
  {"@type": "Organization", "@id": "https://urban.org.in/#org",
   "name": "Urban Collective Action Network (U-CAN)", "alternateName": "U-CAN",
   "url": "https://urban.org.in/", "email": "connect@urban.org.in",
   "areaServed": {"@type": "Country", "name": "India"}},
  {"@type": "WebPage", "@id": "https://urban.org.in/policy-webinars/#webpage", "url": "https://urban.org.in/policy-webinars/",
   "name": "Policy Webinars | U-CAN", "description": "Explore U-CAN&#x27;s Policy Webinars, conversations with sector experts, donor representatives, and member organizations.", "inLanguage": "en-IN",
   "isPartOf": {"@id": "https://urban.org.in/#website"},
   "about": {"@id": "https://urban.org.in/#org"},
   "speakable": {"@type": "SpeakableSpecification", "cssSelector": ["h1", ".hero-lede"]}},
  {"@type": "BreadcrumbList", "@id": "https://urban.org.in/policy-webinars/#breadcrumb", "itemListElement": [
   {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://urban.org.in/"},
   {"@type": "ListItem", "position": 2, "name": "Policy Webinars", "item": "https://urban.org.in/policy-webinars/"}]},
  {"@type": "ItemList", "@id": "https://urban.org.in/policy-webinars/#list", "name": "U-CAN Policy Webinars", "itemListElement": [
   {"@type": "ListItem", "position": 1, "name": "Working Across Organisations: What Does It Really Take?", "item": "https://urban.org.in/etn/beyond-silos-the-case-for-collective-action-copy/"},
   {"@type": "ListItem", "position": 2, "name": "Beyond Silos: The Case for Collective Action", "item": "https://urban.org.in/etn/beyond-silos-the-case-for-collective-action/"},
   {"@type": "ListItem", "position": 3, "name": "Reimagining Problem-Solving in Urban Governance Through Digital Public Infrastructure", "item": "https://urban.org.in/etn/reimagining-problem-solving-in-urban-governance-through-digital-public-infrastructure/"},
   {"@type": "ListItem", "position": 4, "name": "Understanding Urban Climate Action in India", "item": "https://urban.org.in/etn/understanding-urban-climate-action-in-india/"},
   {"@type": "ListItem", "position": 5, "name": "Policy Webinar: Community Engagement", "item": "https://urban.org.in/etn/policy-webinar-community-engagement/"},
   {"@type": "ListItem", "position": 6, "name": "Policy Webinar: Affordable Urban Housing", "item": "https://urban.org.in/etn/policy-webinar-affordable-urban-housing/"},
   {"@type": "ListItem", "position": 7, "name": "Policy Webinar: Systems &amp; Governance", "item": "https://urban.org.in/etn/systems-governance/"},
   {"@type": "ListItem", "position": 8, "name": "Policy Webinar: Beyond Town Halls", "item": "https://urban.org.in/etn/policy-webinar-beyond-town-halls/"},
   {"@type": "ListItem", "position": 9, "name": "Policy Webinar: Digital Citizen Engagement", "item": "https://urban.org.in/etn/policy-webinar-beyond-town-halls-2/"}]}
 ]
}';

get_header();
?>


<section class="hero" aria-labelledby="pt">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <span>Policy Webinars</span></nav>
    <p class="hero-tag">Media</p>
    <h1 id="pt">Policy Webinars</h1>
    <p class="hero-lede">U-CAN's Policy Webinars are curated to bring together sector experts, donor
      representatives, and member organizations for focused conversations on the urban challenges
      shaping India's cities, from housing and governance to community engagement, climate action,
      and digital infrastructure.</p>
  </div>
</section>

<section class="sec" aria-labelledby="pe">
  <div class="wrap">
    <div class="sec-head rv">
      <p class="kicker" data-num="—">The archive</p>
      <h2 id="pe">Past webinars</h2>
    </div>
    <ul class="slist" aria-label="U-CAN policy webinars">
<?php
/**
 * Phase 5: dynamic replacement for the static 9-row webinar list -
 * queries the ucan_etn_event CPT (event_kind=policy_webinar; see
 * functions.php's ucan_register_etn_event_cpt()), newest first.
 */
$webinars = new WP_Query(
	array(
		'post_type'      => 'ucan_etn_event',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_key'       => 'event_kind',
		'meta_value'     => 'policy_webinar',
	)
);
while ( $webinars->have_posts() ) : $webinars->the_post(); $f = ucan_etn_event_display_fields( get_post() );
?>
      <li class="rv"><a class="srow" href="<?php the_permalink(); ?>">
        <span class="sdate"><?php echo esc_html( $f['date_display'] ); ?></span>
        <span><h3><?php the_title(); ?></h3></span>
        <span class="sgo" aria-hidden="true">↗</span>
      </a></li>
<?php endwhile; wp_reset_postdata(); ?>
    </ul>
  </div>
</section>

<section class="ctaband" aria-label="More media">
  <div class="wrap">
    <p>More from U-CAN</p>
    <a class="btn" href="<?php echo esc_url( home_url( '/city-champions/' ) ); ?>">City Champions podcast <span class="ar" aria-hidden="true">→</span></a>
    <a class="btn line" href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>">Newsletter <span class="ar" aria-hidden="true">→</span></a>
  </div>
</section>


<?php
get_footer();
