<?php
/**
 * Template Name: Learning Network
 * Auto-applies to a WP Page whose slug is "learning-network" (file-name
 * convention - page-learning-network.php). Content lifted verbatim from
 * standalone/learning-network.html's <main> (CLAUDE.md: content stays verbatim unless a
 * named fix is requested); only asset paths and internal links were
 * rewritten to WP functions. The page's own JSON-LD (already carrying
 * correct absolute urban.org.in canonical URLs) is re-emitted unchanged,
 * so functions.php's generic wp_head hook skips its own Organization node
 * for this page.
 */

$ucan_page_meta = array(
	'description' => 'The Learning Network for Urban Managers is a peer-driven platform run by Artha Global, with the support of U-CAN, enabling municipal officials to learn from each other\'s real city challenges.',
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

      "@id": "https://urban.org.in/learning-network-for-urban-managers/#page",

      "url": "https://urban.org.in/learning-network-for-urban-managers/",

      "name": "Learning Network for Urban Managers | U-CAN",

      "description": "The Learning Network for Urban Managers is a peer-driven platform run by Artha Global, with the support of U-CAN, enabling municipal officials to learn from each other\'s real city challenges.",

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

          "name": "Learning Network",

          "item": "https://urban.org.in/learning-network-for-urban-managers/"

        }

      ]

    }

  ]

}';

get_header();
?>




<section class="hero" aria-labelledby="h1">

  <div class="hero-in">

    <nav class="crumb" aria-label="Breadcrumb">

      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">/</span><span>Learning Network</span>

    </nav>

    <p class="hero-tag">A U-CAN Initiative · Implemented by Artha Global</p>

    <h1 id="h1">Learning Network for Urban Managers</h1>

    <p class="hero-lede">The Learning Network for Urban Managers is a peer-driven platform implemented by Artha Global with support from U-CAN, that enables municipal officials to learn directly from one another's real-world experiences of city challenges. The initiative focused on continuous, meaningful peer-to-peer knowledge sharing and co-developing solutions rooted in practical municipal experience.</p>

    <div class="hero-meta">

      <span class="hero-chip">8-month pilot · 2025</span>

      <span class="hero-chip">Maharashtra &amp; Andhra Pradesh</span>

      <span class="hero-chip">Peer-to-peer learning</span>

    </div>

  </div>

</section>



<section class="sec" id="overview" aria-labelledby="ov-h">

  <div class="wrap">

    <div class="sec-head rv in">

      <p class="kicker" data-num="—">Overview</p>

      <h2 id="ov-h">Learning from those who do the work</h2>

    </div>

    <div class="lead-split rv in">

      <!-- LEFT: statement + supporting body -->

      <div class="lead-split-left">

        <p class="lead-hero">Indian cities are solving complex problems every day, often with limited resources, overlapping mandates, and little opportunity to learn from peers facing the same constraints. <span class="em">The Learning Network for Urban Managers was created to change that.</span></p>

        <p class="lead-sub">Anchored within the Urban Collective Action Network (U-CAN) and implemented by Artha Global, the Learning Network brings municipal officials together to share lived experience, exchange practical solutions, and learn directly from one another. Rather than focusing on one-off trainings or expert-led lectures, the Network is grounded in peer exchange and practice-based learning. Over an eight-month pilot phase in 2025, the Learning Network demonstrated that when given the right space and structure, city officials are eager to engage, reflect, and collaborate across state boundaries.</p>

      </div>

      <!-- RIGHT: purposeful stat panel — fills the space meaningfully -->

      <aside class="lead-right rv d1 in">

        <div class="lr-row">

          <span class="lr-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3"></circle><path d="M5 20c0-4 3-7 7-7s7 3 7 7"></path></svg></span>

          <div class="lr-text"><b>75+</b><span>Municipal officials engaged across pilot workshops</span></div>

        </div>

        <div class="lr-row">

          <span class="lr-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><polygon points="3,11 12,2 21,11 21,22 15,22 15,15 9,15 9,22 3,22"></polygon></svg></span>

          <div class="lr-text"><b>13</b><span>Cities in the Andhra Pradesh cohort alone</span></div>

        </div>

        <div class="lr-row">

          <span class="lr-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg></span>

          <div class="lr-text"><b>8 months</b><span>Pilot phase, 2025 — peer learning at scale</span></div>

        </div>

        <div class="lr-row">

          <span class="lr-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5.6 5.6l2.1 2.1M16.3 16.3l2.1 2.1M5.6 18.4l2.1-2.1M16.3 7.7l2.1-2.1"></path></svg></span>

          <div class="lr-text"><b>2 themes</b><span>Air quality &amp; affordable housing — more planned</span></div>

        </div>

        <div class="lr-row">

          <span class="lr-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 4 5.5 4 9.5 4 15 12 22 12 22s8-7 8-12.5C20 5.5 16 2 12 2z"></path><circle cx="12" cy="9.5" r="2.5"></circle></svg></span>

          <div class="lr-text"><b>2 states</b><span>Maharashtra &amp; Andhra Pradesh — expanding nationally</span></div>

        </div>

      </aside>

    </div>

  </div>

</section>



<section class="sec alt" id="shape" aria-labelledby="sh-h">

  <div class="wrap">

    <div class="sec-head rv in">

      <p class="kicker" data-num="—">How it works</p>

      <h2 id="sh-h">How the Learning Network took shape</h2>

    </div>

    <div class="shape">

      <div class="shape-text rv in">

        <p>The Learning Network was intentionally designed as a practice-oriented platform, not a conference saeries. Its objective was clear from the outset: create sustained opportunities for peer-to-peer learning that are grounded in real implementation challenges.</p>

        <p>During the pilot phase, the Network:</p>

        <ol class="stepper" style="margin:14px 0 0">
          <li><p>Established partnerships with state governments</p></li>
          <li><p>Engaged diverse municipal stakeholders</p></li>
          <li><p>Piloted thematic workshops that generated measurable impact, beginning with air quality and affordable housing</p></li>
        </ol>

        <p class="after" style="margin-top:18px">What made the Network effective was a champion-led approach – working with senior state officials who could convene the right city-level participants and create a sense of shared ownership. In Maharashtra and Andhra Pradesh, support from the respective Directorates of Municipal Administration played a catalytic role, enabling strong participation from municipalities across both states.</p>

      </div>

      <aside class="champ rv d1" aria-labelledby="champ-h">
        <h3 id="champ-h">What made it work</h3>
        <ol class="champ-steps">
          <li>A champion-led approach</li>
          <li>Senior state officials who could convene the right city-level participants</li>
          <li>A catalytic role for the Directorates of Municipal Administration</li>
        </ol>
        <p class="champ-k">Pilot states</p>
        <ul class="champ-states">
          <li>Maharashtra</li><li>Andhra Pradesh</li>
        </ul>
      </aside>

    </div>

  </div>

</section>



<section class="sec" id="workshops" aria-labelledby="pw-h">

  <div class="wrap">

    <div class="sec-head rv in">

      <p class="kicker" data-num="—">The pilot</p>

      <h2 id="pw-h">The Pilot Workshop</h2>

    </div>

    <div class="ws-grid">

      <article class="ws rv in" tabindex="0">

        <div class="ws-head">

          <span class="ws-num" aria-hidden="true">01</span>

          <div class="ws-tags">

            <span class="ws-region">Maharashtra</span>

          </div>

        </div>

        <h3>Air quality management in Maharashtra</h3>

        <div class="ws-body">

          <p>The first pilot workshop brought together over 35 municipal leaders from across Maharashtra. Instead of technical lectures, the emphasis was on city-led case sharing and dialogue.</p>

          <p>Officials from Thane and Pimpri Chinchwad shared on-ground experiences; from networked air quality monitoring and mechanised road washing to EV adoption and enforcement challenges. Cross-state learning was introduced through presentations by officials from Visakhapatnam and Vijayawada on piloting Clean Air Zones.</p>

          <p>What stood out was not just the diversity of interventions, but the quality of discussion. Participants engaged deeply on questions of implementation, sensor placement, compliance, community engagement, prompting a clear call for recurring forums and systematic documentation of best practices.</p>

        </div>

      </article>

      <article class="ws rv in" tabindex="0">

        <div class="ws-head">

          <span class="ws-num" aria-hidden="true">02</span>

          <div class="ws-tags">

            <span class="ws-region">Andhra Pradesh</span>

          </div>

        </div>

        <h3>Air quality management in Andhra Pradesh</h3>

        <div class="ws-body">

          <p>Building on this momentum, a second workshop was convened with nearly 40 officials from 13 non-attainment cities in Andhra Pradesh. Here, the format shifted even further towards collaborative problem-solving. Presentations by officials from Visakhapatnam and Bhubaneswar on Clean Air Zones and Low Emission Zones served as starting points for reflection, rather than models to be copied. City officials discussed how solutions could be adapted to their own contexts, moving the conversation from theory to action. By the end of the session, there was strong consensus on the value of sustained, peer-driven engagement, and broad support for scaling the Learning Network beyond a single theme or state.</p>

        </div>

      </article>

      <article class="ws rv in" tabindex="0">

        <div class="ws-head">

          <span class="ws-num" aria-hidden="true">03</span>

          <div class="ws-tags">

            <span class="ws-region">Maharashtra</span>

            <span class="ws-stat">Virtual workshop</span>

          </div>

        </div>

        <h3>Affordable housing in Maharashtra</h3>

        <div class="ws-body">

          <p>The Learning Network also expanded beyond environmental issues to address affordable housing, a priority concern for many cities. A virtual workshop with municipal representatives from Maharashtra explored housing challenges through a combination of expert framing and city case studies. Officials from Kolhapur and Nanded shared experiences implementing housing under PMAY's Beneficiary-Led Construction vertical, while officials from Odisha's award-winning Jaga Mission offered insights into in-situ slum upgrading and community-led approaches. The discussion underscored the value of the Network as a space where cities could openly discuss constraints, financial, institutional, and political, while learning from peers who have navigated similar challenges.</p>

        </div>

      </article>

    </div>

    <a class="link-inline" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/docs/learning-network-one-pagers-a6ef60b6.pdf" target="_blank" rel="noopener noreferrer">To know more about these pilots, click here! <span aria-hidden="true">→</span></a>

  </div>

</section>



<section class="sec alt" id="revealed" aria-labelledby="re-h">

  <div class="wrap">

    <div class="sec-head rv in">

      <p class="kicker" data-num="—">What we learned</p>

      <h2 id="re-h">What the pilot phase revealed</h2>

    </div>

    <ol class="revealed">

        <li class="rv in"><span class="rl-num" aria-hidden="true">1</span><p>Peer learning consistently emerged as the most valued element. City officials were more receptive to lessons grounded in lived experience than to external prescriptions.</p></li>

        <li class="rv in"><span class="rl-num" aria-hidden="true">2</span><p>Action-oriented design of the sessions – structured dialogue, problem-solving exercises, and reflection – helped sustain engagement and generate practical takeaways.</p></li>

        <li class="rv in"><span class="rl-num" aria-hidden="true">3</span><p>While air quality served as an effective entry point, cities repeatedly expressed interest in engaging on a much wider set of themes, including clean and liveable neighbourhoods, municipal finance, governance, and climate adaptation.</p></li>

    </ol>

  </div>

</section>



<section class="sec" id="platform" aria-labelledby="pl-h">

  <div class="wrap">

    <div class="sec-head rv in">

      <p class="kicker" data-num="—">The road ahead</p>

      <h2 id="pl-h">From pilot to platform</h2>

    </div>

    <div class="platform">

      <p class="platform-lead rv in">Looking ahead, the Learning Network aims to:</p>

      <figure class="ladder rv d1" aria-labelledby="ladder-h">
        <figcaption id="ladder-h">From pilot to platform</figcaption>
        <svg viewBox="0 0 260 120" role="img"
             aria-label="A pilot in two states growing into a platform across states">
          <rect x="8"   y="74" width="58" height="38" fill="#1F8F7B" fill-opacity=".22"/>
          <rect x="82"  y="50" width="58" height="62" fill="#1F8F7B" fill-opacity=".42"/>
          <rect x="156" y="20" width="58" height="92" fill="#0E5348"/>
          <path d="M8 112h222" stroke="#DCEAE6" stroke-width="1.5"/>
        </svg>
        <div class="ladder-x"><span>Pilot</span><span>Growing</span><span>Platform</span></div>
      </figure>

      <ul class="platform-list rv d1 in">

          <li>Expand participation across states</li>

          <li>Systematically document and share best practices</li>

          <li>Broaden its thematic focus beyond air quality</li>

          <li>Strengthen digital infrastructure for continuous engagement</li>

          <li>Support 'champion cities' to mentor and guide others</li>

      </ul>

    </div>

  </div>

</section>



<section class="why-band" aria-labelledby="why-h">

  <div class="wrap why-inner">

    <div class="why-copy">

      <p class="kicker on-dark" data-num="—">Why this matters</p>

      <p class="why-statement">The Learning Network demonstrates that collaboration, not just capacity building, is key to improving urban outcomes. With targeted investment and institutional support, the Network has the potential to evolve into a national mechanism for strengthening urban governance and delivering better outcomes for millions of citizens.</p>
      </div>

    <div class="why-side">
      <div class="wpath" aria-hidden="true">
      <p class="wpath-k">What it needs</p>
      <ul class="wp-ins">
        <li><b>01</b> Collaboration, not just capacity building</li>
        <li><b>02</b> Targeted investment</li>
        <li><b>03</b> Institutional support</li>
      </ul>
      <svg class="wp-arrow" viewBox="0 0 300 26" preserveAspectRatio="none">
        <path d="M40 0 L150 20 M150 0 L150 20 M260 0 L150 20"
              fill="none" stroke="#CDDE71" stroke-width="1" opacity=".55"/>
        <path d="M144 14 L150 22 L156 14" fill="none" stroke="#CDDE71" stroke-width="1.4"/>
      </svg>
      <div class="wp-node">
        <strong>A national mechanism for strengthening urban governance</strong>
        <span>What the Network can become</span>
      </div>
      <p class="wp-out">Better outcomes for&nbsp;<em>millions of citizens</em></p>
    </div>
      <div class="why-links">

      <a class="btn on-photo" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/docs/02-12-25-knowledge-report-urban-managers-learning-networ-993d8b8b.pdf" target="_blank" rel="noopener noreferrer">Read the full report <span class="ar" aria-hidden="true">→</span></a>

      <a class="btn ghost-photo" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/docs/learning-network-one-pagers-a6ef60b6.pdf" target="_blank" rel="noopener noreferrer">Pilot one-pagers</a>

    </div>
    </div>

  </div>

</section>












<?php
get_footer();
