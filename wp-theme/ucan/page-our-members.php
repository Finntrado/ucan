<?php
/**
 * Template Name: Our Members
 * Auto-applies to a WP Page whose slug is "our-members" (file-name
 * convention - page-our-members.php). Content lifted verbatim from
 * standalone/our-members.html's <main> (CLAUDE.md: content stays verbatim unless a
 * named fix is requested); only asset paths and internal links were
 * rewritten to WP functions. The page's own JSON-LD (already carrying
 * correct absolute urban.org.in canonical URLs) is re-emitted unchanged,
 * so functions.php's generic wp_head hook skips its own Organization node
 * for this page.
 */

$ucan_page_meta = array(
	'description' => 'Meet the organisations powering U-CAN\'s work, from founding members to Friends of U-CAN.',
);
$ucan_page_jsonld = '{
 "@context": "https://schema.org",
 "@graph": [
  {"@type": "Organization", "@id": "https://urban.org.in/#org",
   "name": "Urban Collective Action Network (U-CAN)", "alternateName": "U-CAN",
   "url": "https://urban.org.in/", "email": "connect@urban.org.in",
   "areaServed": {"@type": "Country", "name": "India"}},
  {"@type": "WebPage", "@id": "https://urban.org.in/our-members/#webpage", "url": "https://urban.org.in/our-members/",
   "name": "Our Members | U-CAN",
   "description": "Meet the organisations powering U-CAN\'s work, from founding members to Friends of U-CAN.",
   "inLanguage": "en-IN", "isPartOf": {"@id": "https://urban.org.in/#website"},
   "about": {"@id": "https://urban.org.in/#org"},
   "speakable": {"@type": "SpeakableSpecification", "cssSelector": ["h1", ".hero-lede"]}},
  {"@type": "BreadcrumbList", "@id": "https://urban.org.in/our-members/#breadcrumb", "itemListElement": [
   {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://urban.org.in/"},
   {"@type": "ListItem", "position": 2, "name": "Our Members", "item": "https://urban.org.in/our-members/"}]}
 ]
}';

get_header();
?>


<section class="hero" aria-labelledby="pt">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <span>Our Members</span></nav>
    <p class="hero-tag">The network</p>
    <h1 id="pt">Our Members</h1>
    <p class="hero-lede">At the Urban Collective Action Network (U-CAN), we are proud to partner with a diverse group of organizations that share our vision and commitment to driving positive change in urban development. Together, we leverage our collective expertise, resources, and networks to tackle the most pressing challenges facing cities today.</p>
  </div>
</section>

<section class="sec" aria-labelledby="mo">
  <div class="wrap">
    <div class="sec-head rv">
      <p class="kicker" data-num="—">Who we are</p>
      <h2 id="mo">Member Organisations</h2>
      
    </div>
    <ul class="mgrid" aria-label="U-CAN member organisations">
      <li class="rv"><a href="https://artha.global/" target="_blank" rel="noopener noreferrer" aria-label="Artha Global — visit website">
        <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/artha-global-logo-cc6ae7093a.webp" alt="Artha Global logo" loading="lazy" decoding="async">
        <span class="go" aria-hidden="true">↗</span>
      </a></li>
      <li class="rv"><a href="https://cprindia.org/" target="_blank" rel="noopener noreferrer" aria-label="Centre for Policy Research — visit website">
        <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/centre-for-policy-research-logo-fc8445d7a7.webp" alt="Centre for Policy Research (CPR) logo" loading="lazy" decoding="async">
        <span class="go" aria-hidden="true">↗</span>
      </a></li>
      <li class="rv"><a href="https://egov.org.in/" target="_blank" rel="noopener noreferrer" aria-label="eGov Foundation — visit website">
        <img srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/egov-foundation-logo-f623aa609f-800.webp 800w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/egov-foundation-logo-f623aa609f.webp 1024w" sizes="100vw" width="1024" height="260" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/egov-foundation-logo-f623aa609f.webp" alt="eGov Foundation logo" loading="lazy" decoding="async">
        <span class="go" aria-hidden="true">↗</span>
      </a></li>
      <li class="rv"><a href="https://www.janaagraha.org/" target="_blank" rel="noopener noreferrer" aria-label="Janaagraha — visit website">
        <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/janaagraha-logo-985127bfc1.webp" alt="Janaagraha logo" loading="lazy" decoding="async">
        <span class="go" aria-hidden="true">↗</span>
      </a></li>
      <li class="rv"><a href="https://praja.org/" target="_blank" rel="noopener noreferrer" aria-label="Praja Foundation — visit website">
        <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/praja-foundation-logo-7ae1e89a9b.webp" alt="Praja Foundation logo" loading="lazy" decoding="async">
        <span class="go" aria-hidden="true">↗</span>
      </a></li>
      <li class="rv"><a href="https://www.reapbenefit.org/" target="_blank" rel="noopener noreferrer" aria-label="Reap Benefit — visit website">
        <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/reap-benefit-logo-e142694e13.webp" alt="Reap Benefit logo" loading="lazy" decoding="async">
        <span class="go" aria-hidden="true">↗</span>
      </a></li>
      <li class="rv"><a href="https://shelter-associates.org/" target="_blank" rel="noopener noreferrer" aria-label="Shelter Associates — visit website">
        <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/shelter-associates-logo-5ebfb355a7.webp" alt="Shelter Associates logo" loading="lazy" decoding="async">
        <span class="go" aria-hidden="true">↗</span>
      </a></li>
      <li class="rv"><a href="https://wri-india.org/" target="_blank" rel="noopener noreferrer" aria-label="WRI India — visit website">
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/wri-india-logo-3052bd5545.svg" alt="WRI India logo" loading="lazy" decoding="async">
        <span class="go" aria-hidden="true">↗</span>
      </a></li>
    </ul>
  </div>
</section>

<section class="sec alt" aria-labelledby="fo">
  <div class="wrap">
    <div class="sec-head rv">
      <p class="kicker" data-num="—">Wider circle</p>
      <h2 id="fo">Friends of U-CAN</h2>
      
    </div>
    <ul class="mgrid" aria-label="Friends of U-CAN">
      <li class="rv"><a href="https://www.mahilahousingtrust.org/" target="_blank" rel="noopener noreferrer" aria-label="Mahila Housing Trust — visit website">
        <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/mahila-housing-trust-logo-d50608d443.webp" alt="Mahila Housing Trust logo" loading="lazy" decoding="async">
        <span class="go" aria-hidden="true">↗</span>
      </a></li>
      <li class="rv"><a href="https://iisc.ac.in/" target="_blank" rel="noopener noreferrer" aria-label="Indian Institute of Science — visit website">
        <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/indian-institute-of-science-logo-13c84eb948.webp" alt="Indian Institute of Science (IISc) logo" loading="lazy" decoding="async">
        <span class="go" aria-hidden="true">↗</span>
      </a></li>
      <li class="rv"><a href="https://www.c40.org/" target="_blank" rel="noopener noreferrer" aria-label="C40 Cities — visit website">
        <img width="185" height="115" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/c40-cities-logo-8b3d4f418e.png" alt="C40 Cities logo" loading="lazy" decoding="async">
        <span class="go" aria-hidden="true">↗</span>
      </a></li>
    </ul>
  </div>
</section>


<?php
get_footer();
