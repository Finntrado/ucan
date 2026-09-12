<?php
/**
 * Template Name: Fellowship
 * Auto-applies to a WP Page whose slug is "u-can-fellowship" (file-name
 * convention - page-u-can-fellowship.php). Content lifted verbatim from
 * standalone/fellowship.html's <main> (CLAUDE.md: content stays verbatim unless a
 * named fix is requested); only the "01-08 cohort" list (further down) is
 * now a live ucan_fellow CPT query instead of 8 static rows (CLAUDE.md
 * §28) - everything else, including asset paths and internal links
 * rewritten to WP functions, is unchanged. The page's own JSON-LD (already
 * carrying correct absolute urban.org.in canonical URLs) is re-emitted
 * unchanged, so functions.php's generic wp_head hook skips its own
 * Organization node for this page.
 */

$ucan_page_meta = array(
	'description' => 'The U-CAN Fellowship is a year-long program placing women leaders inside urban institutions across India. See what the 2024-25 cohort built.',
);
$ucan_page_jsonld = '{
 "@context": "https://schema.org",
 "@graph": [
  {"@type": "Organization", "@id": "https://urban.org.in/#org",
   "name": "Urban Collective Action Network (U-CAN)", "alternateName": "U-CAN",
   "url": "https://urban.org.in/", "email": "connect@urban.org.in",
   "areaServed": {"@type": "Country", "name": "India"}},
  {"@type": "WebPage", "@id": "https://urban.org.in/u-can-fellowship/#webpage", "url": "https://urban.org.in/u-can-fellowship/",
   "name": "The U-CAN Fellowship", "description": "The U-CAN Fellowship is a year-long program placing women leaders inside urban institutions across India.", "inLanguage": "en-IN",
   "isPartOf": {"@id": "https://urban.org.in/#website"},
   "about": {"@id": "https://urban.org.in/#org"},
   "speakable": {"@type": "SpeakableSpecification", "cssSelector": ["h1", ".hero-lede"]}},
  {"@type": "BreadcrumbList", "@id": "https://urban.org.in/u-can-fellowship/#breadcrumb", "itemListElement": [
   {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://urban.org.in/"},
   {"@type": "ListItem", "position": 2, "name": "U-CAN Fellowship", "item": "https://urban.org.in/u-can-fellowship/"}]}
 ]
}';

get_header();
?>


<section class="hero" aria-labelledby="pt">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <span>U-CAN Fellowship</span></nav>
    <p class="hero-tag">A U-CAN Initiative</p>
    <h1 id="pt">Empowering Women Change-Makers to Transform Urban Communities</h1>
    <p class="hero-lede">The U-CAN Fellowship was a program designed to empower passionate leaders
      dedicated to driving sustainable urban development and fostering community engagement across
      India. Its inaugural cohort ran in 2024-25.</p>
    <div class="hero-actions">
      <a class="btn on-photo" href="<?php echo esc_url( home_url( '/meet-our-fellows/' ) ); ?>">Meet Our Fellows <span class="ar" aria-hidden="true">→</span></a>
      <a class="btn ghost-photo" href="<?php echo esc_url( home_url( '/blogs-by-our-fellows/' ) ); ?>">Blogs by Our Fellows <span class="ar" aria-hidden="true">→</span></a>
    </div>
  </div>
</section>

<section class="sec" aria-labelledby="ab">
  <div class="wrap">
    <div class="intro">
      <div class="body rv">
        <p class="kicker" data-num="—" style="margin-bottom:16px;color:var(--teal-text)">The programme</p>
        <h2 id="ab" style="margin-bottom:18px">About the Fellowship</h2>
        <p>Over a 12-month period, U-CAN Fellows embarked on an immersive learning journey,
          collaborating with civic leaders, urban planners, and social innovators to tackle some of
          the most pressing challenges in urban development. The fellowship integrated hands-on
          fieldwork, professional mentorship, and skill-building workshops, empowering a new
          generation of change-makers to drive impactful solutions in urban spaces.</p>
        <p class="callout">Fellows worked on housing affordability, transportation, environmental
          sustainability, and inclusion, each embedded for a year with a single host organisation.</p>
      </div>
      <div class="panel rv d1">
        <h3>The 2024-25 cohort</h3>
        <?php
        /**
         * Phase 4: dynamic replacement for the static 8-row list -
         * queries the ucan_fellow CPT ordered by menu_order (the real
         * 01-08 cohort sequence, CLAUDE.md §28).
         */
        $cohort = new WP_Query( array( 'post_type' => 'ucan_fellow', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
        $n = 0;
        while ( $cohort->have_posts() ) : $cohort->the_post(); $n++;
        $ff = ucan_fellow_display_fields( get_post() );
        ?>
        <div class="prow"><b><?php echo esc_html( str_pad( $n, 2, '0', STR_PAD_LEFT ) ); ?></b><span><a href="<?php the_permalink(); ?>" style="color:var(--ink);font-weight:600;text-decoration:none"><?php the_title(); ?></a><br><?php echo esc_html( $ff['host_organisation'] ); ?></span></div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
  </div>
</section>

<section class="sec alt" aria-labelledby="ph">
  <div class="wrap">
    <div class="sec-head rv">
      <p class="kicker" data-num="—">What Fellows gained</p>
      <h2 id="ph">Program Highlights</h2>
      <p class="lead">The U-CAN Fellowship was not just an academic experience, it was a chance to
        work directly on the problems shaping India's cities. Fellows gained:</p>
    </div>
    <div class="hl">
      <div class="hlc icard rv"><div class="ihead"><span class="icb"><svg class="ic" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3 20.4h18"/><path d="M4.6 20.4V10l5-3.2V20.4"/><path d="M9.6 12.4h5.6v8"/><path d="M15.2 15.2h4.2v5.2"/><path d="M6.8 13h1M6.8 16.2h1M11.8 15.2h1.4M11.8 18h1.4"/></svg></span><h3>On-the-Ground Experience</h3></div><p>Live urban projects alongside local communities, municipal bodies, and policy experts, solving real-world urban challenges.</p></div>
      <div class="hlc icard rv d1"><div class="ihead"><span class="icb"><svg class="ic" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3.2" y="4" width="17.6" height="12" rx="1.6"/><path d="M12 16v4.4M8.4 20.4h7.2"/><path d="M7.4 12.6V9.4M11 12.6V7.2M14.6 12.6v-2"/></svg></span><h3>Workshops and Training</h3></div><p>Workshops, training sessions, and expert-led discussions to develop practical skills and knowledge critical to urban development.</p></div>
      <div class="hlc icard rv d2"><div class="ihead"><span class="icb"><svg class="ic" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="4.6" r="2.3"/><circle cx="4.6" cy="18" r="2.3"/><circle cx="19.4" cy="18" r="2.3"/><path d="M10.4 6.5 6.2 15.9M13.6 6.5l4.2 9.4M6.9 18h10.2"/></svg></span><h3>Networking Opportunities</h3></div><p>Lasting relationships within a vibrant community of organizations and urban experts, fostering collaboration and ongoing learning in the urban sector.</p></div>
      <div class="hlc icard rv"><div class="ihead"><span class="icb"><svg class="ic" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="8.6"/><circle cx="12" cy="12" r="4.6"/><circle cx="12" cy="12" r="1"/></svg></span><h3>Project Focus</h3></div><p>Each fellow tackled a key urban issue through a project, with mentorship and support from their assigned organization.</p></div>
      <div class="hlc icard rv d1"><div class="ihead"><span class="icb"><svg class="ic" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M8 12.5 5 9.6a2 2 0 0 1 0-2.8l1.4-1.4a2 2 0 0 1 2.8 0L12 8.2l2.8-2.8a2 2 0 0 1 2.8 0L19 6.8a2 2 0 0 1 0 2.8l-3 2.9"/><path d="M12 8.2 8.6 11.6a1.8 1.8 0 0 0 0 2.6l3 3a1.8 1.8 0 0 0 2.6 0l3.4-3.4"/></svg></span><h3>Expert Mentorship</h3></div><p>Peer-to-peer mentorship within the cohort, plus guidance from U-CAN member organizations, the U-CAN CEO, and the extensive alumni network, with access to potential funding, grants, or investment networks through the U-CAN platform.</p></div>
      <div class="hlc icard rv d2"><div class="ihead"><span class="icb"><svg class="ic" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"/><path d="M9 7.6h6M9 10.6h6M13.6 7.6a3 3 0 0 1 0 6H9l5 5"/></svg></span><h3>Funding</h3></div><p>A one-year commitment in the form of a competitive stipend, supporting fellows&#x27; work and learning throughout the fellowship.</p></div>
    </div>
  </div>
</section>

<section class="sec" aria-labelledby="su">
  <div class="wrap">
    <div class="sec-head rv">
      <p class="kicker" data-num="—" style="color:var(--teal-text)">Inside the Fellowship</p>
      <h2 id="su">Shaping Urban Futures: Stories from the U-CAN Fellowship</h2>
    </div>
    <div class="split fx-intro">
      <div class="body rv">
        <p>India's cities are full of possibility, but women are largely missing from the leadership
        spaces shaping them, despite being among those most affected by decisions on mobility,
        safety, public space, and climate. The U-CAN Fellowship was created to bridge this gap and
        ask a crucial question: what becomes possible when women lead urban transformation?</p>
        <p>Over one year, eight women Fellows embedded themselves within leading urban institutions
        across India, working alongside governments, community networks, researchers, and
        non-profits to solve real problems on the ground. From clean air zones and climate action
        planning to participatory governance and public-space redesign, their work demonstrates the
        power of placing diverse leadership at the centre of city-making.</p>
      </div>
      <div class="body rv d1">
        <h3 class="fx-h3">How the Fellowship Is Designed</h3>
        <p>The programme was intentionally built as a platform that:</p>
        <ul class="blist">
          <li>Promotes women&#x27;s leadership in the urban development ecosystem, ensuring diverse perspectives and more equitable representation in decision-making spaces.</li>
          <li>Attracts talented mid-career professionals and social entrepreneurs to nurture cross-sector learning and engage meaningfully in shaping India&#x27;s urban development landscape.</li>
          <li>Builds structured leadership pathways, combining on-ground problem-solving with mentorship, peer learning, and exposure to global best practices.</li>
        </ul>
      </div>
    </div>

    <div class="cultivate" style="margin-top:clamp(34px,4.5vw,52px)">
      <div class="sec-head rv">
        <p class="kicker" data-num="—" style="color:var(--teal-text)">Three core enablers</p>
        <h3 class="fx-h3 fx-h3-lg">What It Aims to Cultivate</h3>
        <p class="lead">Fellows are supported through three core enablers that shape their leadership journey.</p>
      </div>
      <div class="pillars">
        <article class="pillar rv">
          <span class="pillar-n" aria-hidden="true">01</span>
          <span class="icb"><svg class="ic" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4 4.6h5.2A2.8 2.8 0 0 1 12 7.4v12a2.4 2.4 0 0 0-2.4-2.4H4z"/><path d="M20 4.6h-5.2A2.8 2.8 0 0 0 12 7.4v12a2.4 2.4 0 0 1 2.4-2.4H20z"/></svg></span>
          <h4>Knowledge</h4>
          <p class="pillar-lede">Learning that compounds</p>
          <p>Immersive learning experiences that blend mentorship, masterclasses, field insights, and peer exchange, helping Fellows develop both strategic and practical skills for urban problem-solving.</p>
        </article>
        <article class="pillar rv d1">
          <span class="pillar-n" aria-hidden="true">02</span>
          <span class="icb"><svg class="ic" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="4.6" r="2.3"/><circle cx="4.6" cy="18" r="2.3"/><circle cx="19.4" cy="18" r="2.3"/><path d="M10.4 6.5 6.2 15.9M13.6 6.5l4.2 9.4M6.9 18h10.2"/></svg></span>
          <h4>Network</h4>
          <p class="pillar-lede">Doors that stay open</p>
          <p>Access to a connected ecosystem of government institutions, host organisations, practitioners, and technical experts, opening doors to collaboration and long-term pathways in the urban sector.</p>
        </article>
        <article class="pillar rv d2">
          <span class="pillar-n" aria-hidden="true">03</span>
          <span class="icb"><svg class="ic" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"/><path d="M9 7.6h6M9 10.6h6M13.6 7.6a3 3 0 0 1 0 6H9l5 5"/></svg></span>
          <h4>Funding</h4>
          <p class="pillar-lede">Room to experiment</p>
          <p>A dedicated one-year grant that provides the stability and flexibility to experiment, co-create solutions with stakeholders, and demonstrate credible impact on the ground.</p>
        </article>
      </div>
      <p class="cultivate-close rv d3">Together, these pillars create the conditions for Fellows to build
        confidence, capability, and influence, enabling them to shape more equitable,
        resilient cities across India.</p>
    </div>

    <div class="pubrow" style="margin-top:clamp(34px,4.5vw,52px)">
      <div class="numcard rv d1">
      <h3>Inside this publication, you will find:</h3>
      <ol>
        <li><span>The journeys and lived experiences of the inaugural cohort</span></li>
        <li><span>The solutions they built with institutions and communities</span></li>
        <li><span>And the Fellowship model that supported them</span></li>
      </ol>
      <a class="btn pubdl" href="https://urban.org.in/u-can-fellowship-report-2025" target="_blank" rel="noopener noreferrer">Download full report <span class="ar" aria-hidden="true">&#8594;</span></a>
      </div>
      <a class="pubcover rv d2" href="https://urban.org.in/u-can-fellowship-report-2025" target="_blank" rel="noopener noreferrer"
         aria-label="Download Shaping Urban Futures: Stories from the U-CAN Fellowship, the 2025 Fellowship report">
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/fellowship-report-2025-cover.webp" width="391" height="597" loading="lazy" decoding="async"
             alt="Cover of Shaping Urban Futures: Stories from the U-CAN Fellowship, the U-CAN Fellowship report 2025">
      </a>
    </div>
  </div>
</section>

<section class="ctaband" aria-label="Explore the Fellowship">
  <div class="wrap">
    <p>See what the inaugural cohort built</p>
    <a class="btn" href="<?php echo esc_url( home_url( '/meet-our-fellows/' ) ); ?>">Meet the 2024-25 Fellows <span class="ar" aria-hidden="true">→</span></a>
    <a class="btn line" href="<?php echo esc_url( home_url( '/fellowship-ld/' ) ); ?>">L&amp;D Calendar <span class="ar" aria-hidden="true">→</span></a>
  </div>
</section>


<?php
get_footer();
