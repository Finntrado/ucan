<?php
/**
 * Template Name: Annual Forum 2025
 * Auto-applies to a WP Page whose slug is "the-u-can-annual-forum-2025" (file-name
 * convention - page-the-u-can-annual-forum-2025.php). Content lifted verbatim from
 * standalone/annual-forum-2025.html's <main> (CLAUDE.md: content stays verbatim unless a
 * named fix is requested); only asset paths and internal links were
 * rewritten to WP functions. The page's own JSON-LD (already carrying
 * correct absolute urban.org.in canonical URLs) is re-emitted unchanged,
 * so functions.php's generic wp_head hook skips its own Organization node
 * for this page.
 */

$ucan_page_meta = array(
	'description' => '70+ practitioners, policymakers and community leaders gathered in Panjim, Goa for U-CAN\'s first Annual Forum. See the sessions, reflections and photos.',
);
$ucan_page_jsonld = '{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "https://urban.org.in/#org",
      "name": "Urban Collective Action Network (U-CAN)",
      "url": "https://urban.org.in/",
      "email": "connect@urban.org.in"
    },
    {
      "@type": "Event",
      "@id": "https://urban.org.in/the-u-can-annual-forum-2025/#event",
      "name": "The U-CAN Annual Forum 2025",
      "description": "U-CAN\'s flagship gathering — Cities. People. Practice. Together. Held 21–23 August 2025 in Panjim, Goa.",
      "startDate": "2025-08-21",
      "endDate": "2025-08-23",
      "eventStatus": "https://schema.org/EventScheduled",
      "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
      "location": {
        "@type": "Place",
        "name": "Panjim, Goa",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "Panjim",
          "addressRegion": "Goa",
          "addressCountry": "IN"
        }
      },
      "organizer": {
        "@id": "https://urban.org.in/#org"
      }
    },
    {
      "@type": "WebPage",
      "@id": "https://urban.org.in/the-u-can-annual-forum-2025/#page",
      "url": "https://urban.org.in/the-u-can-annual-forum-2025/",
      "name": "U-CAN Annual Forum 2025: Panjim, Goa | U-CAN",
      "description": "70+ practitioners, policymakers and community leaders gathered in Panjim, Goa for U-CAN\'s first Annual Forum. See the sessions, reflections and photos.",
      "mainEntity": {
        "@id": "https://urban.org.in/the-u-can-annual-forum-2025/#event"
      },
      "isPartOf": {
        "@id": "https://urban.org.in/#org"
      },
      "inLanguage": "en-IN"
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
          "name": "The U-CAN Annual Forum 2025",
          "item": "https://urban.org.in/the-u-can-annual-forum-2025/"
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
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">/</span><span>The U-CAN Annual Forum 2025</span>
    </nav>
    <p class="hero-tag">Events</p>
    <h1 id="h1">The U-CAN Annual Forum 2025</h1>
    <p class="hero-lede">The U-CAN Annual Forum is U-CAN's flagship gathering, <b>“Cities. People. Practice. Together.”</b> Held for the first time from <b>21–23 August 2025 in Panjim, Goa</b>, the Forum marked a milestone three years into U-CAN's collective journey, what began as a collaboration of twelve organisations has grown into a space of trust, shared purpose, and experimentation in advancing urban transformation in India's smaller cities.</p>
    <div class="hero-actions">
      <a class="btn on-photo" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/docs/u-can-annual-forum-report-1-7622b5ef.pdf" target="_blank" rel="noopener noreferrer">Download the Forum report <span class="ar" aria-hidden="true">→</span></a>
      <a class="btn ghost-photo" href="#voices">Read the reflections</a>
    </div>
  </div>
</section>

<!-- QUICK OVERVIEW -->
<section class="sec" id="overview" aria-labelledby="ov-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <h2 id="ov-h">Quick Overview</h2>
      </div>
    </div>
    <div class="split">
      <div class="body rv in">
        <p>The first U-CAN Annual Forum, held from 21–23 August 2025 in Panjim, Goa, marked a milestone—three years into our collective journey. What began as a collaboration of twelve organisations has grown into a space of trust, shared purpose, and experimentation in advancing urban transformation in India's smaller cities.</p>
        <p>Over 70 practitioners, elected representatives, policymakers, researchers, and community leaders came together to co-create solutions for India's urban future. Designed to break away from the conventional conference model, the Forum placed collaboration and co-creation at its core. Instead of presentations, sessions created space for participants from diverse roles and backgrounds to reflect, contribute, and connect meaningfully.</p>
        <p class="callout">The Forum underscored a central truth: India's urban challenges cannot be solved by any single actor. By convening this diverse ecosystem, we deepened our collective commitment to act for impact.</p>
      </div>
      <aside class="numcard rv d1 in" aria-labelledby="ov-stats">
        <h3 id="ov-stats">The Forum in numbers</h3>
        <ol class="plainnum">
          <li><span><b style="color:#fff;font-size:19px">70+</b><br>practitioners, elected representatives, policymakers, researchers and community leaders</span></li>
          <li><span><b style="color:#fff;font-size:19px">3 days</b><br>of gallery walks, human libraries, film screenings, collaborative problem-solving and field visits</span></li>
          <li><span><b style="color:#fff;font-size:19px">12 → many</b><br>from a founding collaboration of twelve organisations to a wider ecosystem</span></li>
        </ol>
      </aside>
    </div>
  </div>
</section>

<!-- REPORT -->
<section class="repband" id="report" aria-labelledby="rp-h">
  <div class="wrap repband-in">
    <div class="repband-copy rv in">
      <p class="kicker on-dark" data-num="—">The report</p>
      <h2 id="rp-h">Collective Action in Learning: Cities. People. Practice. Together.</h2>
      <p>Inside the Forum report, you'll find session highlights, participant reflections, and stories of how alternative formats – gallery walks, human libraries, film screenings, collaborative problem-solving, and field visits – reshaped the way we learn and connect.</p>
      <p>The document captures takeaways from across the urban ecosystem: what organisations discovered about themselves, how partnerships are forged in practice, and what seeds were planted for future collaborations.</p>
      <p>It's more than a record; it's a resource for anyone interested in how collective action can be designed and sustained in India's emerging cities.</p>
      <div class="hero-actions" style="margin-top:28px">
        <a class="btn repdl" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/docs/u-can-annual-forum-report-1-7622b5ef.pdf" target="_blank" rel="noopener noreferrer">Download the report <span class="ar" aria-hidden="true">→</span></a>
      </div>
    </div>
    <a class="repcover rv d1 in" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/docs/u-can-annual-forum-report-1-7622b5ef.pdf" target="_blank" rel="noopener noreferrer"
       aria-label="Download Collective Action in Learning: Cities. People. Practice. Together., the U-CAN Annual Forum 2025 report">
      <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/annual-forum-2025-report-cover.webp" width="388" height="592" loading="lazy" decoding="async"
           alt="Cover of the U-CAN Annual Forum 2025 report, Collective Learning in Action: Cities. People. Practice. Together.">
    </a>
  </div>
</section>

<!-- HIGHLIGHTS VIDEO -->
<section class="sec alt" id="highlights" aria-labelledby="hl-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <p class="kicker" data-num="—">Watch</p>
        <h2 id="hl-h">U-CAN Annual Forum 2025 highlights video</h2>
      </div>
    </div>
    <div class="vidrow">
      <figure class="forumvid rv in">
        <video controls preload="none" playsinline
               data-poster="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7924-enhanced-nr-scaled-5d5aaf99-1024.webp"
               width="1280" height="720">
          <source src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/video/u-can-annual-forum-e4ad3c72.mp4" type="video/mp4">
          <p>Your browser cannot play this video.
            <a href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/video/u-can-annual-forum-e4ad3c72.mp4">Open the highlights video</a>.</p>
        </video>
      </figure>
    </div>
  </div>
</section>

<!-- IN THEIR WORDS -->
<section class="sec" id="voices" aria-labelledby="vo-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <p class="kicker" data-num="—">Reflections</p>
        <h2 id="vo-h">In Their Words</h2>
      </div>
    </div>
    <div class="qgrid">
      <figure class="qc rv in">
        <span class="qm" aria-hidden="true">“</span>
        <blockquote>What I found most valuable is the opportunity to convene and collaborate with other organisations, as well as the chance to step back and rethink what our work means, while experiencing the sometimes painful, but ultimately rewarding, process of co-creation.</blockquote>
        <figcaption><span class="av mono" aria-hidden="true">KK</span><span class="qn"><b>Kanupriya Kaikeya</b><span>Senior Manager Communications, C40 Cities</span></span></figcaption>
      </figure>
      <figure class="qc rv in">
        <span class="qm" aria-hidden="true">“</span>
        <blockquote>It is possibly the only one of its kind initiative, to bring together various voices from different urban geographies across India. The willingness to embrace new approaches and ideas, and making it a collaborative effort, is quite impressive.</blockquote>
        <figcaption><span class="av mono" aria-hidden="true">SS</span><span class="qn"><b>Shivani Singh</b><span>Editor–Urban Affairs, Hindustan Times</span></span></figcaption>
      </figure>
      <figure class="qc rv in">
        <span class="qm" aria-hidden="true">“</span>
        <blockquote>The immersive site visits in Panjim and the direct interactions with local actors stood out as particularly meaningful, since they grounded the discussions in real challenges and opportunities.</blockquote>
        <figcaption><span class="av mono" aria-hidden="true">PK</span><span class="qn"><b>Pradeep Kumar CM</b><span>Senior Technical Manager, eGov Foundation</span></span></figcaption>
      </figure>
      <figure class="qc rv in">
        <span class="qm" aria-hidden="true">“</span>
        <blockquote>The Annual Forum was a valuable space for bringing together diverse voices from across the urban ecosystem. It helped us reflect on how national policy priorities connect with the lived realities of cities. The discussions will support our efforts at the Ministry by enhancing the understanding of the work being done by civil society and research institutions.</blockquote>
        <figcaption><span class="av mono" aria-hidden="true">GS</span><span class="qn"><b>Gurjit Singh Dhillon</b><span>Director, Ministry of Housing and Urban Affairs</span></span></figcaption>
      </figure>
      <figure class="qc rv in">
        <span class="qm" aria-hidden="true">“</span>
        <blockquote>The U-CAN meeting in Goa was truly enriching. Connecting with organizations from different states gave us valuable insights, and the films and presentations - especially on community initiatives and employment programs - were particularly inspiring.</blockquote>
        <figcaption><span class="av mono" aria-hidden="true">BV</span><span class="qn"><b>Bhikaji Vaydande</b><span>Slum Dwellers Community Leader, Kohlapur</span></span></figcaption>
      </figure>
      <figure class="qc rv in">
        <span class="qm" aria-hidden="true">“</span>
        <blockquote>I thought that it was valuable to bring the 2nd and 3rd tier of participants from the organisations to interact with each other. This gives a better understanding of their work.</blockquote>
        <figcaption><span class="av"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/pratima-joshi-38ddc17d32.webp" width="56" height="56" alt="Pratima Joshi" loading="lazy" decoding="async"></span><span class="qn"><b>Pratima Joshi</b><span>Founder and Executive Director, Shelter Associates</span></span></figcaption>
      </figure>
      <figure class="qc rv in">
        <span class="qm" aria-hidden="true">“</span>
        <blockquote>Each session was thoughtfully structured, run and curated. The respectful and attentive listening principle and inclusive ethos that was built is an example to follow.</blockquote>
        <figcaption><span class="av"><img src="data:image/webp;base64,UklGRoQEAABXRUJQVlA4IHgEAAAwFACdASo4ADgAPj0YikMiIaEYCwZ8IAPEoAxnQ0yESVLF/g96HM3DH22O5A3pLehp990E+7vajKYTwbJHElpy5oPkY+lfYI/W1FUChHiOzzN9uosBXEXGodf+9hrv1tph1sn+xQJifXWRiVpEOzXZ1I79qNSPOZuGuSiQocIpe2BxQU8qnyvIc0tCYKh259gUSAA25uY32HsU+vwVDE9CaweKffNvhb7RQAD+/zM6fbCa48+dL0q42iybSK5CfZRQK5P+DW/qfXsFdo4XxuYX2Wi+M3Gf/KGf7Fh5mDdoB4aPg3m78ts/dB8SxA9iZeDGugcK6yBOb1yq5r9tDosCjx9vyFt81iwKbS9trCpHZmgn8jXTcrFr0pXhkmTLeqnOmkdEXnf9aLDswl/tocV3QslJR6TJyFsn5m+8tJUYFxhJ5B/e6flWnaQ02N3/1kGw89zcYyv0HrX6/5EBVCrqjR9X8fTkuXJFFLvVBZkBuzs4cjzm4P6rGsKYSp7k7PeZVrJjGEpd+WH6/YTcJ7GYvP/5s1ymDPYBCjLL3cs7P6ewf6u7p3Yj/8RUQl2fju0Tsas+a4zwckO7tCd3+lgbWsGXd36Z5rTcko+vzX7wr5qjBZBQ4lkDlw3QtMhA0Ankf1AqmFTuBkAUNtWC+INRvUfDO8HP+996p3z/jPzX9JVNnmgvETi2G1NQuSy+jCFv/qM8+PbRmY0wuaEbeBNDaOBS/ILqiFWlSpYoP6PQtI83hGEvqHHaq8sfmCEAE5Zjbw7DcR5CW4hwfZCgXveKzqfo/VFj6kYYgfJmsfMkci59ktngclt0oxu2UajWa//cOCw2ue57pymHMkiMENrP/UvsiXELQH9tNIMYBhAkaueVfRawBa4tIC+/5FX6xFntrvUI6sGVKepZLmXmF+77uRsRmAHY7EMEuAlfqQ740O9zfvNYN5NyuQJhcxo0zbDR7MB/7Bmo33zgdkdLXck4LSyrea4YbIQOZJc5E1Si8mOXxz0/pNIu0w94cdfQvhn/nXDKhLDmpJ/0MWIXukC88wsGynWuN++A3csP4Wrz7xrQHvqYslf6PPLzAYS9Wr8UoZ13Y9cDGl9f47ycV4QsuNJh+1bfngXvczcH9cF+F539NU6NYhDLqxkRWCquqj/eJZIALzOgNJHVYA97yBykTse1TyHZhiVwZ/T7wHpeOeedvr6Y0q9Z3gW5UEuYRQeWPNhyA1b7JC3C3W1PuA/3Xumj8G7591p47JZAI42YSrxhmNGQrjxUuZ/c2RIIOUJ5gbk3j1YrV+swdzGLIcXeEJrpHVId9QipXsxYkl4I8Y42z+YFy6a7G0A77/VwEr4vJdabbYPOEIsElk/iLl2vFJ3887ZsS80a8W/BjbQhz4wP9yvF0pkUjn6007Pgy/13UlQd7h3wVIFGEdfV8VX/YsIihorHiqlR7Qn46Hyrt/f6Oz2vN6AIBc81/A2VuNxfXJkysQpIqpLCCxmC5QpXz3rmTYOHBn9H5xue08GRfCJvfBZHSAAA" width="56" height="56" alt="Neha Lal" loading="lazy" decoding="async"></span><span class="qn"><b>Neha Lal</b><span>Senior Program Manager, WRI</span></span></figcaption>
      </figure>
      <figure class="qc rv in">
        <span class="qm" aria-hidden="true">“</span>
        <blockquote>The participatory nature of the Forum was remarkable. Everyone was able to contribute honestly and spontaneously, sharing their perspectives without fear or bias. It created a fair space where diverse voices, from civil society, NGOs, and local leaders, could come together, exchange ideas, and converge on common understanding. This instant, open dialogue was a valuable learning experience for all of us.</blockquote>
        <figcaption><span class="av mono" aria-hidden="true">SN</span><span class="qn"><b>Sangramjit Nayak</b><span>Former Director of Municipal Administration, Housing and Urban Development Department, Government of Odisha</span></span></figcaption>
      </figure>
    </div>
  </div>
</section>

<!-- PHOTOS -->
<!-- PHOTOS -->
<section class="sec alt" id="photos" aria-labelledby="ph-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <h2 id="ph-h" style="font-size:clamp(27px,3.6vw,40px);letter-spacing:-.02em;max-width:22ch">Participants at the U-CAN Annual Forum 2025, Panjim, Goa</h2>
      </div>
    </div>
    <div class="tabs daytabs rv" data-tabs>
      <div class="tablist" role="tablist" aria-label="Photographs by day">
          <button type="button" class="tab" role="tab" id="tab-day1" aria-controls="pane-day1" aria-selected="true">Day 1</button>
          <button type="button" class="tab" role="tab" id="tab-day2" aria-controls="pane-day2" aria-selected="false">Day 2</button>
          <button type="button" class="tab" role="tab" id="tab-day3" aria-controls="pane-day3" aria-selected="false">Day 3</button>
      </div>
        <div class="tabpane galpane" role="tabpanel" id="pane-day1" aria-labelledby="tab-day1" tabindex="0">
<div class="gal galx">
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7924-enhanced-nr-scaled-5d5aaf99-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7924-enhanced-nr-scaled-5d5aaf99-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7924-enhanced-nr-scaled-5d5aaf99-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7924-enhanced-nr-scaled-5d5aaf99-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 100vw, 760px" width="640" height="427" data-full="assets/img/forum/0b7a7924-enhanced-nr-scaled-5d5aaf99-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7802-enhanced-nr-scaled-a029906f-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7802-enhanced-nr-scaled-a029906f-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7802-enhanced-nr-scaled-a029906f-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7802-enhanced-nr-scaled-a029906f-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a7802-enhanced-nr-scaled-a029906f-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7798-enhanced-nr-scaled-70422c1e-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7798-enhanced-nr-scaled-70422c1e-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7798-enhanced-nr-scaled-70422c1e-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7798-enhanced-nr-scaled-70422c1e-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a7798-enhanced-nr-scaled-70422c1e-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8248-enhanced-nr-scaled-735605c1-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8248-enhanced-nr-scaled-735605c1-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8248-enhanced-nr-scaled-735605c1-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8248-enhanced-nr-scaled-735605c1-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8248-enhanced-nr-scaled-735605c1-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8228-enhanced-nr-scaled-6b4ddaa1-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8228-enhanced-nr-scaled-6b4ddaa1-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8228-enhanced-nr-scaled-6b4ddaa1-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8228-enhanced-nr-scaled-6b4ddaa1-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8228-enhanced-nr-scaled-6b4ddaa1-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8157-enhanced-nr-scaled-247a9831-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8157-enhanced-nr-scaled-247a9831-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8157-enhanced-nr-scaled-247a9831-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8157-enhanced-nr-scaled-247a9831-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8157-enhanced-nr-scaled-247a9831-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8117-enhanced-nr-scaled-17e24d5b-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8117-enhanced-nr-scaled-17e24d5b-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8117-enhanced-nr-scaled-17e24d5b-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8117-enhanced-nr-scaled-17e24d5b-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8117-enhanced-nr-scaled-17e24d5b-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8065-enhanced-nr-1-scaled-907710c1-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8065-enhanced-nr-1-scaled-907710c1-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8065-enhanced-nr-1-scaled-907710c1-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8065-enhanced-nr-1-scaled-907710c1-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 100vw, 760px" width="640" height="427" data-full="assets/img/forum/0b7a8065-enhanced-nr-1-scaled-907710c1-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8052-enhanced-nr-scaled-29e7f7ed-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8052-enhanced-nr-scaled-29e7f7ed-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8052-enhanced-nr-scaled-29e7f7ed-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8052-enhanced-nr-scaled-29e7f7ed-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8052-enhanced-nr-scaled-29e7f7ed-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8045-enhanced-nr-scaled-4b29f149-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8045-enhanced-nr-scaled-4b29f149-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8045-enhanced-nr-scaled-4b29f149-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8045-enhanced-nr-scaled-4b29f149-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8045-enhanced-nr-scaled-4b29f149-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8040-enhanced-nr-scaled-200a5e89-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8040-enhanced-nr-scaled-200a5e89-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8040-enhanced-nr-scaled-200a5e89-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8040-enhanced-nr-scaled-200a5e89-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8040-enhanced-nr-scaled-200a5e89-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8015-enhanced-nr-1-scaled-b2ee39fd-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8015-enhanced-nr-1-scaled-b2ee39fd-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8015-enhanced-nr-1-scaled-b2ee39fd-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8015-enhanced-nr-1-scaled-b2ee39fd-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8015-enhanced-nr-1-scaled-b2ee39fd-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8007-enhanced-nr-scaled-531d4d22-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8007-enhanced-nr-scaled-531d4d22-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8007-enhanced-nr-scaled-531d4d22-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8007-enhanced-nr-scaled-531d4d22-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8007-enhanced-nr-scaled-531d4d22-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7975-enhanced-nr-scaled-397d79ed-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7975-enhanced-nr-scaled-397d79ed-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7975-enhanced-nr-scaled-397d79ed-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7975-enhanced-nr-scaled-397d79ed-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a7975-enhanced-nr-scaled-397d79ed-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7970-enhanced-nr-scaled-1a4a6806-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7970-enhanced-nr-scaled-1a4a6806-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7970-enhanced-nr-scaled-1a4a6806-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7970-enhanced-nr-scaled-1a4a6806-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 100vw, 760px" width="640" height="427" data-full="assets/img/forum/0b7a7970-enhanced-nr-scaled-1a4a6806-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7965-enhanced-nr-scaled-e36f006a-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7965-enhanced-nr-scaled-e36f006a-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7965-enhanced-nr-scaled-e36f006a-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7965-enhanced-nr-scaled-e36f006a-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a7965-enhanced-nr-scaled-e36f006a-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7947-enhanced-nr-scaled-7e0b1a3d-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7947-enhanced-nr-scaled-7e0b1a3d-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7947-enhanced-nr-scaled-7e0b1a3d-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7947-enhanced-nr-scaled-7e0b1a3d-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a7947-enhanced-nr-scaled-7e0b1a3d-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7941-enhanced-nr-scaled-4850dfe6-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7941-enhanced-nr-scaled-4850dfe6-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7941-enhanced-nr-scaled-4850dfe6-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7941-enhanced-nr-scaled-4850dfe6-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a7941-enhanced-nr-scaled-4850dfe6-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7936-enhanced-nr-scaled-b015f539-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7936-enhanced-nr-scaled-b015f539-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7936-enhanced-nr-scaled-b015f539-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7936-enhanced-nr-scaled-b015f539-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a7936-enhanced-nr-scaled-b015f539-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7825-enhanced-nr-scaled-9764cd23-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7825-enhanced-nr-scaled-9764cd23-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7825-enhanced-nr-scaled-9764cd23-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7825-enhanced-nr-scaled-9764cd23-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a7825-enhanced-nr-scaled-9764cd23-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7818-enhanced-nr-scaled-31328171-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7818-enhanced-nr-scaled-31328171-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7818-enhanced-nr-scaled-31328171-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7818-enhanced-nr-scaled-31328171-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a7818-enhanced-nr-scaled-31328171-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7813-enhanced-nr-scaled-2eb5d735-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7813-enhanced-nr-scaled-2eb5d735-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7813-enhanced-nr-scaled-2eb5d735-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7813-enhanced-nr-scaled-2eb5d735-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 100vw, 760px" width="640" height="427" data-full="assets/img/forum/0b7a7813-enhanced-nr-scaled-2eb5d735-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7808-enhanced-nr-scaled-3e21c059-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7808-enhanced-nr-scaled-3e21c059-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7808-enhanced-nr-scaled-3e21c059-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7808-enhanced-nr-scaled-3e21c059-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a7808-enhanced-nr-scaled-3e21c059-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7800-enhanced-nr-scaled-2d55780c-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7800-enhanced-nr-scaled-2d55780c-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7800-enhanced-nr-scaled-2d55780c-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7800-enhanced-nr-scaled-2d55780c-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a7800-enhanced-nr-scaled-2d55780c-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7792-enhanced-nr-scaled-07bb4ac7-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7792-enhanced-nr-scaled-07bb4ac7-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7792-enhanced-nr-scaled-07bb4ac7-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7792-enhanced-nr-scaled-07bb4ac7-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a7792-enhanced-nr-scaled-07bb4ac7-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7791-enhanced-nr-scaled-0d200acf-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7791-enhanced-nr-scaled-0d200acf-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7791-enhanced-nr-scaled-0d200acf-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a7791-enhanced-nr-scaled-0d200acf-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a7791-enhanced-nr-scaled-0d200acf-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
    </div>
        </div>
        <div class="tabpane galpane" role="tabpanel" id="pane-day2" aria-labelledby="tab-day2" tabindex="0">
<div class="gal galx">
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8252-enhanced-nr-scaled-a678b3c7-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8252-enhanced-nr-scaled-a678b3c7-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8252-enhanced-nr-scaled-a678b3c7-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8252-enhanced-nr-scaled-a678b3c7-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 100vw, 760px" width="640" height="427" data-full="assets/img/forum/0b7a8252-enhanced-nr-scaled-a678b3c7-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8379-enhanced-nr-scaled-1baa41d7-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8379-enhanced-nr-scaled-1baa41d7-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8379-enhanced-nr-scaled-1baa41d7-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8379-enhanced-nr-scaled-1baa41d7-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8379-enhanced-nr-scaled-1baa41d7-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8365-enhanced-nr-scaled-711774cb-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8365-enhanced-nr-scaled-711774cb-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8365-enhanced-nr-scaled-711774cb-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8365-enhanced-nr-scaled-711774cb-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8365-enhanced-nr-scaled-711774cb-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8354-enhanced-nr-scaled-3dddee65-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8354-enhanced-nr-scaled-3dddee65-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8354-enhanced-nr-scaled-3dddee65-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8354-enhanced-nr-scaled-3dddee65-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8354-enhanced-nr-scaled-3dddee65-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8308-enhanced-nr-scaled-fbd8799f-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8308-enhanced-nr-scaled-fbd8799f-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8308-enhanced-nr-scaled-fbd8799f-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8308-enhanced-nr-scaled-fbd8799f-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8308-enhanced-nr-scaled-fbd8799f-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8300-enhanced-nr-scaled-09c4fc88-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8300-enhanced-nr-scaled-09c4fc88-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8300-enhanced-nr-scaled-09c4fc88-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8300-enhanced-nr-scaled-09c4fc88-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8300-enhanced-nr-scaled-09c4fc88-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8292-enhanced-nr-scaled-3b2ee848-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8292-enhanced-nr-scaled-3b2ee848-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8292-enhanced-nr-scaled-3b2ee848-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8292-enhanced-nr-scaled-3b2ee848-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8292-enhanced-nr-scaled-3b2ee848-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8275-enhanced-nr-scaled-ad4ac56a-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8275-enhanced-nr-scaled-ad4ac56a-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8275-enhanced-nr-scaled-ad4ac56a-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8275-enhanced-nr-scaled-ad4ac56a-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 100vw, 760px" width="640" height="427" data-full="assets/img/forum/0b7a8275-enhanced-nr-scaled-ad4ac56a-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8263-enhanced-nr-scaled-4c2ee76f-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8263-enhanced-nr-scaled-4c2ee76f-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8263-enhanced-nr-scaled-4c2ee76f-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a8263-enhanced-nr-scaled-4c2ee76f-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a8263-enhanced-nr-scaled-4c2ee76f-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
    </div>
        </div>
        <div class="tabpane galpane" role="tabpanel" id="pane-day3" aria-labelledby="tab-day3" tabindex="0">
<div class="gal galx">
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9963-enhanced-nr-scaled-1f713656-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9963-enhanced-nr-scaled-1f713656-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9963-enhanced-nr-scaled-1f713656-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9963-enhanced-nr-scaled-1f713656-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 100vw, 760px" width="640" height="427" data-full="assets/img/forum/0b7a9963-enhanced-nr-scaled-1f713656-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9953-enhanced-nr-scaled-4e05c030-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9953-enhanced-nr-scaled-4e05c030-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9953-enhanced-nr-scaled-4e05c030-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9953-enhanced-nr-scaled-4e05c030-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9953-enhanced-nr-scaled-4e05c030-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9951-enhanced-nr-scaled-9e4c7b89-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9951-enhanced-nr-scaled-9e4c7b89-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9951-enhanced-nr-scaled-9e4c7b89-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9951-enhanced-nr-scaled-9e4c7b89-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9951-enhanced-nr-scaled-9e4c7b89-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9949-enhanced-nr-scaled-daeb3459-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9949-enhanced-nr-scaled-daeb3459-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9949-enhanced-nr-scaled-daeb3459-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9949-enhanced-nr-scaled-daeb3459-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9949-enhanced-nr-scaled-daeb3459-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9944-enhanced-nr-scaled-f19930a6-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9944-enhanced-nr-scaled-f19930a6-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9944-enhanced-nr-scaled-f19930a6-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9944-enhanced-nr-scaled-f19930a6-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9944-enhanced-nr-scaled-f19930a6-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9943-enhanced-nr-scaled-9fee8321-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9943-enhanced-nr-scaled-9fee8321-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9943-enhanced-nr-scaled-9fee8321-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9943-enhanced-nr-scaled-9fee8321-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9943-enhanced-nr-scaled-9fee8321-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9939-enhanced-nr-scaled-ef208135-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9939-enhanced-nr-scaled-ef208135-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9939-enhanced-nr-scaled-ef208135-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9939-enhanced-nr-scaled-ef208135-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9939-enhanced-nr-scaled-ef208135-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9938-enhanced-nr-scaled-bac5c6eb-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9938-enhanced-nr-scaled-bac5c6eb-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9938-enhanced-nr-scaled-bac5c6eb-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9938-enhanced-nr-scaled-bac5c6eb-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 100vw, 760px" width="640" height="427" data-full="assets/img/forum/0b7a9938-enhanced-nr-scaled-bac5c6eb-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9936-enhanced-nr-scaled-b6dd2411-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9936-enhanced-nr-scaled-b6dd2411-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9936-enhanced-nr-scaled-b6dd2411-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9936-enhanced-nr-scaled-b6dd2411-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9936-enhanced-nr-scaled-b6dd2411-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9929-enhanced-nr-scaled-275e1f94-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9929-enhanced-nr-scaled-275e1f94-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9929-enhanced-nr-scaled-275e1f94-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9929-enhanced-nr-scaled-275e1f94-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9929-enhanced-nr-scaled-275e1f94-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9898-enhanced-nr-scaled-85c58329-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9898-enhanced-nr-scaled-85c58329-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9898-enhanced-nr-scaled-85c58329-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9898-enhanced-nr-scaled-85c58329-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9898-enhanced-nr-scaled-85c58329-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9901-enhanced-nr-scaled-3a5da829-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9901-enhanced-nr-scaled-3a5da829-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9901-enhanced-nr-scaled-3a5da829-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9901-enhanced-nr-scaled-3a5da829-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9901-enhanced-nr-scaled-3a5da829-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9865-enhanced-nr-scaled-09762e9a-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9865-enhanced-nr-scaled-09762e9a-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9865-enhanced-nr-scaled-09762e9a-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9865-enhanced-nr-scaled-09762e9a-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9865-enhanced-nr-scaled-09762e9a-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9848-enhanced-nr-scaled-722ff6ce-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9848-enhanced-nr-scaled-722ff6ce-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9848-enhanced-nr-scaled-722ff6ce-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9848-enhanced-nr-scaled-722ff6ce-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9848-enhanced-nr-scaled-722ff6ce-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9831-enhanced-nr-scaled-c5d19462-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9831-enhanced-nr-scaled-c5d19462-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9831-enhanced-nr-scaled-c5d19462-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9831-enhanced-nr-scaled-c5d19462-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 100vw, 760px" width="640" height="427" data-full="assets/img/forum/0b7a9831-enhanced-nr-scaled-c5d19462-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9796-enhanced-nr-scaled-9274f9c5-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9796-enhanced-nr-scaled-9274f9c5-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9796-enhanced-nr-scaled-9274f9c5-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9796-enhanced-nr-scaled-9274f9c5-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9796-enhanced-nr-scaled-9274f9c5-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9791-enhanced-nr-scaled-6c8b7648-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9791-enhanced-nr-scaled-6c8b7648-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9791-enhanced-nr-scaled-6c8b7648-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9791-enhanced-nr-scaled-6c8b7648-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9791-enhanced-nr-scaled-6c8b7648-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9854-enhanced-nr-scaled-2fbd84ca-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9854-enhanced-nr-scaled-2fbd84ca-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9854-enhanced-nr-scaled-2fbd84ca-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9854-enhanced-nr-scaled-2fbd84ca-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9854-enhanced-nr-scaled-2fbd84ca-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9860-enhanced-nr-scaled-2b7358d1-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9860-enhanced-nr-scaled-2b7358d1-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9860-enhanced-nr-scaled-2b7358d1-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9860-enhanced-nr-scaled-2b7358d1-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9860-enhanced-nr-scaled-2b7358d1-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9766-enhanced-nr-scaled-66a51cc5-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9766-enhanced-nr-scaled-66a51cc5-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9766-enhanced-nr-scaled-66a51cc5-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9766-enhanced-nr-scaled-66a51cc5-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9766-enhanced-nr-scaled-66a51cc5-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9763-enhanced-nr-scaled-c356ba3b-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9763-enhanced-nr-scaled-c356ba3b-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9763-enhanced-nr-scaled-c356ba3b-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9763-enhanced-nr-scaled-c356ba3b-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9763-enhanced-nr-scaled-c356ba3b-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9710-enhanced-nr-scaled-07988248-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9710-enhanced-nr-scaled-07988248-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9710-enhanced-nr-scaled-07988248-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9710-enhanced-nr-scaled-07988248-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 100vw, 760px" width="640" height="427" data-full="assets/img/forum/0b7a9710-enhanced-nr-scaled-07988248-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9736-enhanced-nr-scaled-d0d1ded2-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9736-enhanced-nr-scaled-d0d1ded2-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9736-enhanced-nr-scaled-d0d1ded2-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9736-enhanced-nr-scaled-d0d1ded2-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9736-enhanced-nr-scaled-d0d1ded2-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9706-enhanced-nr-scaled-b7d6b343-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9706-enhanced-nr-scaled-b7d6b343-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9706-enhanced-nr-scaled-b7d6b343-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9706-enhanced-nr-scaled-b7d6b343-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="354" data-full="assets/img/forum/0b7a9706-enhanced-nr-scaled-b7d6b343-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9692-enhanced-nr-scaled-879e8423-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9692-enhanced-nr-scaled-879e8423-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9692-enhanced-nr-scaled-879e8423-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9692-enhanced-nr-scaled-879e8423-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9692-enhanced-nr-scaled-879e8423-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
        <figure class="rv in">
          <div class="ph" aria-hidden="true"><b>Forum 2025</b><span>Photo loads on urban.org.in</span></div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9678-enhanced-nr-scaled-79198ee1-640.webp" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9678-enhanced-nr-scaled-79198ee1-640.webp 640w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9678-enhanced-nr-scaled-79198ee1-1024.webp 1024w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/forum/0b7a9678-enhanced-nr-scaled-79198ee1-1600.webp 1600w" sizes="(max-width:520px) 100vw, (max-width:820px) 50vw, 380px" width="640" height="427" data-full="assets/img/forum/0b7a9678-enhanced-nr-scaled-79198ee1-1600.webp" alt="Participants at the U-CAN Annual Forum 2025, Panjim, Goa" loading="lazy" decoding="async"
            onload="this.parentNode.classList.add('ok')" onerror="this.style.display='none'">
        </figure>
    </div>
        </div>
    </div>
  </div>
</section>

<!-- PARTNERS -->
<section class="sec" id="partners" aria-labelledby="pa-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <h2 id="pa-h">The U-CAN Annual Forum was organised in partnership with:</h2>
      </div>
    </div>
    <ul class="ptrow">
        <li class="pt rv in"><a href="https://museumofgoa.com/" target="_blank" rel="noopener noreferrer" aria-label="Museum of Goa — visit website" style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;text-decoration:none">
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/partner-museum-of-goa.webp" alt="Museum of Goa logo" loading="lazy" decoding="async"></a></li>
        <li class="pt rv in"><a href="https://transitionsresearch.org/" target="_blank" rel="noopener noreferrer" aria-label="Transitions Research — visit website" style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;text-decoration:none">
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/partner-transitions-research.webp" alt="Transitions Research logo" loading="lazy" decoding="async"></a></li>
        <li class="pt rv in"><a href="https://charlescorreafoundation.org/" target="_blank" rel="noopener noreferrer" aria-label="Charles Correa Foundation — visit website" style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;text-decoration:none">
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/partner-charles-correa-foundation.webp" alt="Charles Correa Foundation logo" loading="lazy" decoding="async"></a></li>
        <li class="pt rv in"><a href="https://www.youtube.com/@nagaritv_ccf" target="_blank" rel="noopener noreferrer" aria-label="Nagari Short Films — visit website" style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;text-decoration:none">
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/partner-nagari-short-films.webp" alt="Nagari Short Films logo" loading="lazy" decoding="async"></a></li>
    </ul>
  </div>
</section>


<?php
get_footer();
