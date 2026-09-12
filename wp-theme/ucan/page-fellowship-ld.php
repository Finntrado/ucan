<?php
/**
 * Template Name: L&D Calendar
 * Auto-applies to a WP Page whose slug is "fellowship-ld" (file-name
 * convention - page-fellowship-ld.php).
 *
 * Hero extracted verbatim from standalone/ld-calendar.html; the 12-row
 * session list is now a live ucan_etn_event CPT query (event_kind=
 * ld_session - phase 5 generalised the CPT phase 4 built as
 * ucan_ld_session, see functions.php), newest first (CLAUDE.md §28) -
 * renders nothing until those posts exist (phase 7's importer). The page's
 * own JSON-LD (already carrying correct absolute
 * urban.org.in canonical URLs) is re-emitted unchanged, so functions.php's
 * generic wp_head hook skips its own Organization node for this page.
 */

$ucan_page_meta = array(
	'description' => 'Browse the U-CAN Fellowship\'s full Learning & Development calendar, past sessions on urban governance, storytelling, climate resilience, and housing.',
);
$ucan_page_jsonld = '{
 "@context": "https://schema.org",
 "@graph": [
  {"@type": "Organization", "@id": "https://urban.org.in/#org",
   "name": "Urban Collective Action Network (U-CAN)", "alternateName": "U-CAN",
   "url": "https://urban.org.in/", "email": "connect@urban.org.in",
   "areaServed": {"@type": "Country", "name": "India"}},
  {"@type": "WebPage", "@id": "https://urban.org.in/fellowship-ld/#webpage", "url": "https://urban.org.in/fellowship-ld/",
   "name": "Fellowship L&amp;D Calendar | U-CAN", "description": "The U-CAN Fellowship&#x27;s full Learning &amp; Development calendar.", "inLanguage": "en-IN",
   "isPartOf": {"@id": "https://urban.org.in/#website"},
   "about": {"@id": "https://urban.org.in/#org"},
   "speakable": {"@type": "SpeakableSpecification", "cssSelector": ["h1", ".hero-lede"]}},
  {"@type": "BreadcrumbList", "@id": "https://urban.org.in/fellowship-ld/#breadcrumb", "itemListElement": [
   {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://urban.org.in/"},
   {"@type": "ListItem", "position": 2, "name": "U-CAN Fellowship", "item": "https://urban.org.in/u-can-fellowship/"},
   {"@type": "ListItem", "position": 3, "name": "L&amp;D Calendar", "item": "https://urban.org.in/fellowship-ld/"}]},
  {"@type": "ItemList", "@id": "https://urban.org.in/fellowship-ld/#sessions", "name": "U-CAN Fellowship L&D sessions", "itemListElement": [
   {"@type": "ListItem", "position": 1, "name": "Roof Over Our Heads (ROOH): Reframing Urban Poor Habitats", "item": "https://urban.org.in/etn/roof-over-our-heads-rooh-reframing-urban-poor-habitats/"},
   {"@type": "ListItem", "position": 2, "name": "Leveraging Digital Public Infrastructure for Urban Transformation", "item": "https://urban.org.in/etn/leveraging-digital-public-infrastructure-for-urban-transformation/"},
   {"@type": "ListItem", "position": 3, "name": "Exploring informality: Informal Settlements, Development and Climate Change", "item": "https://urban.org.in/etn/exploring-informality-informal-settlements-development-and-climate-change/"},
   {"@type": "ListItem", "position": 4, "name": "Living with climate stressors: Urban resilience and climate adaptation", "item": "https://urban.org.in/etn/living-with-climate-stressors-urban-resilience-and-climate-adaptation/"},
   {"@type": "ListItem", "position": 5, "name": "Storytelling Masterclass 4: Using Oral Stories at Work by Storywallahs", "item": "https://urban.org.in/etn/storytelling-masterclass-4-using-oral-stories-at-work-by-storywallahs/"},
   {"@type": "ListItem", "position": 6, "name": "Urban Planning in India – Experiences and Lessons for the Future by CPR India", "item": "https://urban.org.in/etn/urban-planning-in-india-experiences-and-lessons-for-the-future-by-cpr-india/"},
   {"@type": "ListItem", "position": 7, "name": "Storytelling Masterclass 3: Storyboarding and Visual Communication for Impact by Storywallahs", "item": "https://urban.org.in/etn/storytelling-masterclass-3-storyboarding-and-visual-communication-for-impact-by-storywallahs/"},
   {"@type": "ListItem", "position": 8, "name": "Storytelling Masterclass 2: Using Data to Create Impactful Narratives by Storywallahs", "item": "https://urban.org.in/etn/storytelling-masterclass-2-using-data-to-create-impactful-narratives-by-storywallahs/"},
   {"@type": "ListItem", "position": 9, "name": "Urban Governance 2: Right to Information (RTI) &amp; Citizens by Praja Foundation", "item": "https://urban.org.in/etn/urban-governance-2-right-to-information-rti-citizens-by-praja-foundation/"},
   {"@type": "ListItem", "position": 10, "name": "Urban Governance 1: Structure &amp; Status by Praja Foundation", "item": "https://urban.org.in/etn/urban-governance-1-structure-status-by-praja-foundation/"},
   {"@type": "ListItem", "position": 11, "name": "Thinking Better, Alone Together by Manali Shah", "item": "https://urban.org.in/etn/thinking-better-alone-together-by-manali-shah/"},
   {"@type": "ListItem", "position": 12, "name": "Storytelling Masterclass 1: Introduction to Storytelling at Work by Storywallahs", "item": "https://urban.org.in/etn/storytelling-masterclass-1-introduction-to-storytelling-at-work-by-storywallahs/"}]}
 ]
}';

get_header();
?>


<section class="hero" aria-labelledby="pt">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <a href="<?php echo esc_url( home_url( '/u-can-fellowship/' ) ); ?>">U-CAN Fellowship</a> <span aria-hidden="true">/</span> <span>L&amp;D Calendar</span></nav>
    <p class="hero-tag">L&amp;D Calendar</p>
    <h1 id="pt">L&amp;D Calendar</h1>
    <p class="hero-lede">The L&amp;D Calendar was where fellows accessed and reviewed training sessions throughout the program, a clear schedule of masterclasses, workshops, and expert sessions covering everything from data storytelling to urban governance to housing policy. Browse the calendar below to see the full range of learning fellows engaged with over the year.</p>
  </div>
</section>

<?php
/**
 * Phase 4/5: dynamic replacement for the static 12-row session list -
 * queries the ucan_etn_event CPT (event_kind=ld_session; see functions.php's
 * ucan_register_etn_event_cpt() for why L&D sessions/Policy Webinars/one
 * City Mixer note all share this one post type) ordered by post_date,
 * newest first (matches the source's own ordering, June 2025 down to
 * December 2024).
 */
$sessions = new WP_Query(
	array(
		'post_type'      => 'ucan_etn_event',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_key'       => 'event_kind',
		'meta_value'     => 'ld_session',
	)
);
?>
<section class="sec alt" aria-labelledby="ps">
  <div class="wrap">
    <div class="sec-head rv">
      <p class="kicker" data-num="—">The calendar</p>
      <h2 id="ps">Past sessions</h2>
    </div>
    <ul class="slist" aria-label="Fellowship L&amp;D sessions">
	<?php while ( $sessions->have_posts() ) : $sessions->the_post(); $f = ucan_etn_event_display_fields( get_post() ); ?>
      <li class="rv"><a class="srow" href="<?php the_permalink(); ?>">
        <span class="sdate"><?php echo esc_html( $f['date_display'] ); ?></span>
        <span><h3><?php the_title(); ?></h3><?php if ( $f['lead_name'] ) : ?><span class="sw">Session lead: <?php echo esc_html( $f['lead_name'] ); ?></span><?php endif; ?></span>
        <span class="sgo" aria-hidden="true">↗</span>
      </a></li>
	<?php endwhile; wp_reset_postdata(); ?>
    </ul>
  </div>
</section>

<section class="ctaband" aria-label="More from the Fellowship">
  <div class="wrap">
    <p>See who took part</p>
    <a class="btn" href="<?php echo esc_url( home_url( '/meet-our-fellows/' ) ); ?>">Meet the 2024-25 Fellows <span class="ar" aria-hidden="true">→</span></a>
    <a class="btn line" href="<?php echo esc_url( home_url( '/u-can-fellowship/' ) ); ?>">About the Fellowship <span class="ar" aria-hidden="true">→</span></a>
  </div>
</section>


<?php
get_footer();
