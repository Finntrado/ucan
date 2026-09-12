<?php
/**
 * Template Name: City Champions
 * Auto-applies to a WP Page whose slug is "city-champions" (file-name
 * convention - page-city-champions.php). Content lifted verbatim from
 * standalone/city-champions.html's <main> (CLAUDE.md: content stays verbatim unless a
 * named fix is requested); only asset paths and internal links were
 * rewritten to WP functions. The page's own JSON-LD (already carrying
 * correct absolute urban.org.in canonical URLs) is re-emitted unchanged,
 * so functions.php's generic wp_head hook skips its own Organization node
 * for this page.
 */

$ucan_page_meta = array(
	'description' => 'City Champions is U-CAN\'s podcast, in partnership with Josh Talks, bringing urban development conversations to students, young professionals, and everyday citizens.',
);
$ucan_page_jsonld = '{
 "@context": "https://schema.org",
 "@graph": [
  {"@type": "Organization", "@id": "https://urban.org.in/#org",
   "name": "Urban Collective Action Network (U-CAN)", "alternateName": "U-CAN",
   "url": "https://urban.org.in/", "email": "connect@urban.org.in",
   "areaServed": {"@type": "Country", "name": "India"}},
  {"@type": "WebPage", "@id": "https://urban.org.in/city-champions/#webpage", "url": "https://urban.org.in/city-champions/",
   "name": "City Champions Podcast | U-CAN", "description": "City Champions is U-CAN&#x27;s podcast, in partnership with Josh Talks.", "inLanguage": "en-IN",
   "isPartOf": {"@id": "https://urban.org.in/#website"},
   "about": {"@id": "https://urban.org.in/#org"},
   "speakable": {"@type": "SpeakableSpecification", "cssSelector": ["h1", ".hero-lede"]}},
  {"@type": "BreadcrumbList", "@id": "https://urban.org.in/city-champions/#breadcrumb", "itemListElement": [
   {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://urban.org.in/"},
   {"@type": "ListItem", "position": 2, "name": "City Champions", "item": "https://urban.org.in/city-champions/"}]},
  {"@type": "PodcastSeries", "@id": "https://urban.org.in/city-champions/#series", "name": "City Champions", "url": "https://urban.org.in/city-champions/", "publisher": {"@id": "https://urban.org.in/#org"}, "numberOfEpisodes": 4}
 ]
}';

get_header();
?>


<section class="hero" aria-labelledby="pt">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <span>City Champions</span></nav>
    <p class="hero-tag">Media · Podcast</p>
    <h1 id="pt">City Champions Podcast</h1>
    <p class="hero-lede">In mid-2024, we began exploring ways to share U-CAN's and our member organizations' perspectives on cities, including how we see urban challenges and the solutions we are collectively pursuing. One key challenge was reaching audiences outside the usual urban ecosystem, such as students, young professionals, social innovators, and everyday citizens, while speaking in a language that resonates with them. This led to our partnership with Josh Talks, a leading media platform that connects with younger audiences across India in multiple languages.</p>
    <p class="hero-lede">The City Champions podcast brings together voices from all corners of urban development. Our guests share how they see and understand our cities, and the creative ways they're tackling today's urban challenges.</p>
    </div>
</section>

<section class="sec" aria-labelledby="ep1">
  <div class="wrap">
    <div class="split split-mid">
      <div class="body rv">
        <p class="kicker" data-num="—" style="margin-bottom:14px">Episode 1</p>
        <h2 id="ep1" style="font-size:clamp(21px,2.5vw,29px);margin-bottom:16px">Building Better Cities, with Real Solutions</h2>
        <p>Our cities are a reflection of the choices we make. In the first episode of the City Champions Podcast series, our guests break down what's already happening in our cities and how young changemakers can take action, from holding leaders accountable to driving local change.</p>
        <p style="margin-top:18px;font-weight:600;color:var(--ink)">Featured in this episode:</p>
        <ul class="blist">
          <li><span>Supriya Paul (host), Founder, Josh Talks</span></li>
          <li><span><a href="<?php echo esc_url( home_url( '/profile-milind-mhaske/' ) ); ?>" style="color:var(--teal-text);font-weight:600;text-decoration:none">Milind Mhaske</a>, CEO, Praja Foundation</span></li>
          <li><span>Shruti Narayan, Managing Director of Regions and Mayoral Engagement, C40 Cities</span></li>
        </ul>
      </div>
      <div class="rv d1">
        <div class="vid"><a class="ytlite" href="https://www.youtube.com/watch?v=hGHrr2mD6O8" data-yt="hGHrr2mD6O8" data-title="Episode 1: Building Better Cities, with Real Solutions" aria-label="Play video: Episode 1: Building Better Cities, with Real Solutions" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/yt/hGHrr2mD6O8.webp" width="960" height="540" alt="" loading="lazy" decoding="async"><span class="ytplay" aria-hidden="true"><svg viewBox="0 0 68 48"><path class="ytp-bg" d="M66.52 7.74c-.78-2.93-2.49-5.41-5.42-6.19C55.79.13 34 0 34 0S12.21.13 6.9 1.55C3.97 2.33 2.27 4.81 1.48 7.74.06 13.05 0 24 0 24s.06 10.95 1.48 16.26c.78 2.93 2.49 5.41 5.42 6.19C12.21 47.87 34 48 34 48s21.79-.13 27.1-1.55c2.93-.78 4.64-3.26 5.42-6.19C67.94 34.95 68 24 68 24s-.06-10.95-1.48-16.26z"/><path d="M45 24 27 14v20" fill="#fff"/></svg></span></a></div>
      </div>
    </div>
  </div>
</section>
<section class="sec alt" aria-labelledby="ep2">
  <div class="wrap">
    <div class="split split-mid">
      <div class="body rv">
        <p class="kicker" data-num="—" style="margin-bottom:14px">Episode 2</p>
        <h2 id="ep2" style="font-size:clamp(21px,2.5vw,29px);margin-bottom:16px">Designing Smarter Cities for a Sustainable Future</h2>
        <p>In the second episode of the City Champions Podcast series, our guests explore what it takes to design cities built to last, from the systems and policies that shape sustainable growth to the leadership needed to put them into practice.</p>
        <p style="margin-top:18px;font-weight:600;color:var(--ink)">Featured in this episode:</p>
        <ul class="blist">
          <li><span>Supriya Paul (host), Founder, Josh Talks</span></li>
          <li><span><a href="<?php echo esc_url( home_url( '/profile-siddharth-pandit/' ) ); ?>" style="color:var(--teal-text);font-weight:600;text-decoration:none">Siddharth Pandit</a>, CEO, Urban Collective Action Network</span></li>
          <li><span><a href="<?php echo esc_url( home_url( '/profile-gautham-ravichander/' ) ); ?>" style="color:var(--teal-text);font-weight:600;text-decoration:none">Gautham Ravichander</a>, Director &ndash; Policy &amp; Advocacy, eGov Foundation</span></li>
        </ul>
      </div>
      <div class="rv d1">
        <div class="vid"><a class="ytlite" href="https://www.youtube.com/watch?v=scZQvFPqNA4" data-yt="scZQvFPqNA4" data-title="Episode 2: Designing Smarter Cities for a Sustainable Future" aria-label="Play video: Episode 2: Designing Smarter Cities for a Sustainable Future" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/yt/scZQvFPqNA4.webp" width="960" height="540" alt="" loading="lazy" decoding="async"><span class="ytplay" aria-hidden="true"><svg viewBox="0 0 68 48"><path class="ytp-bg" d="M66.52 7.74c-.78-2.93-2.49-5.41-5.42-6.19C55.79.13 34 0 34 0S12.21.13 6.9 1.55C3.97 2.33 2.27 4.81 1.48 7.74.06 13.05 0 24 0 24s.06 10.95 1.48 16.26c.78 2.93 2.49 5.41 5.42 6.19C12.21 47.87 34 48 34 48s21.79-.13 27.1-1.55c2.93-.78 4.64-3.26 5.42-6.19C67.94 34.95 68 24 68 24s-.06-10.95-1.48-16.26z"/><path d="M45 24 27 14v20" fill="#fff"/></svg></span></a></div>
      </div>
    </div>
  </div>
</section>
<section class="sec" aria-labelledby="ep3">
  <div class="wrap">
    <div class="split split-mid">
      <div class="body rv">
        <p class="kicker" data-num="—" style="margin-bottom:14px">Episode 3</p>
        <h2 id="ep3" style="font-size:clamp(21px,2.5vw,29px);margin-bottom:16px">How Much Does It Really Cost to Build the Cities We Dream Of?</h2>
        <p>In Episode 3 of the City Champions Podcast, our guests explore how urban spaces can be reshaped to ensure that development benefits everyone. They discuss the role of urban planning and design in enabling equitable growth, the power of participatory governance in giving citizens a voice, and the importance of proper budgeting to create long-term, people-first infrastructure.</p>
        <p style="margin-top:18px;font-weight:600;color:var(--ink)">Featured in this episode:</p>
        <ul class="blist">
          <li><span>Supriya Paul (host), Founder, Josh Talks</span></li>
          <li><span><a href="<?php echo esc_url( home_url( '/profile-shilpa-kumar/' ) ); ?>" style="color:var(--teal-text);font-weight:600;text-decoration:none">Shilpa Kumar</a>, Partner, Omidyar Network India</span></li>
          <li><span><a href="<?php echo esc_url( home_url( '/profile-krishnan-subbaraman/' ) ); ?>" style="color:var(--teal-text);font-weight:600;text-decoration:none">Krishnan Subbaraman</a>, Head, Strategy, Janaagraha</span></li>
        </ul>
      </div>
      <div class="rv d1">
        <div class="vid"><a class="ytlite" href="https://www.youtube.com/watch?v=LLa14DgZz4c" data-yt="LLa14DgZz4c" data-title="Episode 3: How Much Does It Really Cost to Build the Cities We Dream Of?" aria-label="Play video: Episode 3: How Much Does It Really Cost to Build the Cities We Dream Of?" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/yt/LLa14DgZz4c.webp" width="960" height="540" alt="" loading="lazy" decoding="async"><span class="ytplay" aria-hidden="true"><svg viewBox="0 0 68 48"><path class="ytp-bg" d="M66.52 7.74c-.78-2.93-2.49-5.41-5.42-6.19C55.79.13 34 0 34 0S12.21.13 6.9 1.55C3.97 2.33 2.27 4.81 1.48 7.74.06 13.05 0 24 0 24s.06 10.95 1.48 16.26c.78 2.93 2.49 5.41 5.42 6.19C12.21 47.87 34 48 34 48s21.79-.13 27.1-1.55c2.93-.78 4.64-3.26 5.42-6.19C67.94 34.95 68 24 68 24s-.06-10.95-1.48-16.26z"/><path d="M45 24 27 14v20" fill="#fff"/></svg></span></a></div>
      </div>
    </div>
  </div>
</section>
<section class="sec alt" aria-labelledby="ep4">
  <div class="wrap">
    <div class="split split-mid">
      <div class="body rv">
        <p class="kicker" data-num="—" style="margin-bottom:14px">Episode 4</p>
        <h2 id="ep4" style="font-size:clamp(21px,2.5vw,29px);margin-bottom:16px">Who Really Shapes the Cities We Live In?</h2>
        <p>In the finale episode of the City Champions Podcast series, our guests break down what it takes to build urban spaces that work for everyone&mdash;and why young changemakers must step up as key players in city development. From housing to air quality to transport, every urban challenge is a chance to create meaningful change. It's time to turn hope into action.</p>
        <p style="margin-top:18px;font-weight:600;color:var(--ink)">Featured in this episode:</p>
        <ul class="blist">
          <li><span>Varun Khera (host), Head of Partnerships, Josh Talks</span></li>
          <li><span><a href="<?php echo esc_url( home_url( '/profile-jagan-shah/' ) ); ?>" style="color:var(--teal-text);font-weight:600;text-decoration:none">Jagan Shah</a>, CEO, Infravision Foundation</span></li>
          <li><span>Anuj Bhagwati, Director, A.T.E. Group</span></li>
        </ul>
      </div>
      <div class="rv d1">
        <div class="vid"><a class="ytlite" href="https://www.youtube.com/watch?v=j6qcGe_aLjk" data-yt="j6qcGe_aLjk" data-title="Episode 4: Who Really Shapes the Cities We Live In?" aria-label="Play video: Episode 4: Who Really Shapes the Cities We Live In?" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/yt/j6qcGe_aLjk.webp" width="960" height="540" alt="" loading="lazy" decoding="async"><span class="ytplay" aria-hidden="true"><svg viewBox="0 0 68 48"><path class="ytp-bg" d="M66.52 7.74c-.78-2.93-2.49-5.41-5.42-6.19C55.79.13 34 0 34 0S12.21.13 6.9 1.55C3.97 2.33 2.27 4.81 1.48 7.74.06 13.05 0 24 0 24s.06 10.95 1.48 16.26c.78 2.93 2.49 5.41 5.42 6.19C12.21 47.87 34 48 34 48s21.79-.13 27.1-1.55c2.93-.78 4.64-3.26 5.42-6.19C67.94 34.95 68 24 68 24s-.06-10.95-1.48-16.26z"/><path d="M45 24 27 14v20" fill="#fff"/></svg></span></a></div>
      </div>
    </div>
  </div>
</section>

<section class="ctaband" aria-label="Watch City Champions">
  <div class="wrap">
    <p>Want to hear the full conversations?</p>
    <a class="btn" href="https://www.youtube.com/@U-CAN24/playlists" target="_blank" rel="noopener noreferrer">Watch City Champions on YouTube <span class="ar" aria-hidden="true">→</span></a>
  </div>
</section>


<?php
get_footer();
