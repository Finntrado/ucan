<?php
/**
 * Template Name: About Us
 * Auto-applies to a WP Page whose slug is "about" (file-name
 * convention - page-about.php). Content lifted verbatim from
 * standalone/about.html's <main> (CLAUDE.md: content stays verbatim unless a
 * named fix is requested); only asset paths and internal links were
 * rewritten to WP functions. The page's own JSON-LD (already carrying
 * correct absolute urban.org.in canonical URLs) is re-emitted unchanged,
 * so functions.php's generic wp_head hook skips its own Organization node
 * for this page.
 */

$ucan_page_meta = array(
	'description' => 'Learn about U-CAN\'s vision, mission and guiding principles for building more livable, inclusive Indian cities, and how the network has grown since 2022.',
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

      "@id": "https://urban.org.in/about-us/#page",

      "url": "https://urban.org.in/about-us/",

      "name": "About U-CAN | Urban Collective Action Network",

      "description": "Learn about U-CAN\'s vision, mission and guiding principles for building more livable, inclusive Indian cities, and how the network has grown since 2022.",

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

          "name": "About",

          "item": "https://urban.org.in/about-us/"

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

      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">/</span><span>About</span>

    </nav>

    <p class="hero-tag">About the Network</p>

    <h1 id="h1">About U-CAN</h1>

    <p class="hero-lede">U-CAN is a network of organisations working together to strengthen urban problem-solving in India's <b>Tier II and Tier III cities</b>. Formed in 2022 by a founding circle of twelve city-focused organisations, U-CAN today convenes practitioners, government officials, researchers and philanthropies around one shared goal: <b>safer, more inclusive, better-governed cities</b> for their residents.</p>

  </div>

</section>



<!-- VISION + MISSION -->

<section class="sec" id="vision" aria-labelledby="vm-h">

  <div class="wrap">

    <div class="sec-head rv in">

      <div>

        <h2 id="vm-h">Our Vision &amp; Mission</h2>

      </div>

    </div>

    <div class="vm">

      <article class="vm-card dark rv in" id="vision-card">

        <p class="vm-eyebrow"><i aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle></svg></i> Vision</p>

        <p class="vm-text">Indian cities should be livable, equitable, and inclusive—places where all citizens are empowered to partner with government agencies to improve last-mile service delivery.</p>

        <a class="btn on-photo brochure-btn" href="https://urban.org.in/ucan-brochure-final/" target="_blank" rel="noopener noreferrer">Download our Brochure <span aria-hidden="true">→</span></a>

      </article>

      <article class="vm-card rv d1 in" id="mission">

        <p class="vm-eyebrow"><i aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 2v4M12 18v4M2 12h4M18 12h4M5 5l2.5 2.5M16.5 16.5 19 19M19 5l-2.5 2.5M7.5 16.5 5 19"></path><circle cx="12" cy="12" r="3.4"></circle></svg></i> Mission</p>

        <p class="vm-text">We are a network of organisations working to strengthen urban problem-solving in emerging Indian cities.</p>

        <p class="body" style="margin-top:16px">By creating safe spaces for people, communities, and policymakers to come together, we foster collaborative solutions that lead to more inclusive governance and a richer, more livable urban experience.</p>

        <p class="body">Our approach is grounded in collective action, a multisectoral systems lens, and a strong commitment to citizen participation in policymaking.</p>

      </article>

    </div>

  </div>

</section>



<!-- GOVERNING PRINCIPLES -->

<section class="sec alt" id="principles" aria-labelledby="pr-h">

  <div class="wrap">

    <div class="sec-head rv in">

      <div>

        <p class="kicker" data-num="—">How we work</p>

        <h2 id="pr-h">Governing Principles</h2>

        </div>

    </div>

    <div class="pr-grid">

      <article class="pr rv in" tabindex="0">

        <span class="pr-num" aria-hidden="true">01</span>

        <h3>Citizen-centric approaches</h3>

        <p>We prioritise amplifying citizens' voices by championing solutions that centre their needs, making participation and civic responsibilities more accessible, meaningful, and impactful.</p>

      </article>

      <article class="pr rv d1 in" tabindex="0">

        <span class="pr-num" aria-hidden="true">02</span>

        <h3>Inclusivity and Equity</h3>

        <p>We commit to inclusive development rooted in the socio-economic realities of vulnerable and marginalised communities—those often left out of traditional planning processes.</p>

      </article>

      <article class="pr rv d2 in" tabindex="0">

        <span class="pr-num" aria-hidden="true">03</span>

        <h3>Openness and transparency</h3>

        <p>We advocate for transparent governance practices at the local level to foster trust, accountability, and informed citizen engagement.</p>

      </article>

      <article class="pr rv in" tabindex="0">

        <span class="pr-num" aria-hidden="true">04</span>

        <h3>Systems thinking with a multi-sectoral and long-term perspective</h3>

        <p>We promote coordinated city planning by encouraging interdepartmental collaboration and nurturing institutional memory, ensuring sustainable, holistic urban development.</p>

      </article>

      <article class="pr rv d1 in" tabindex="0">

        <span class="pr-num" aria-hidden="true">05</span>

        <h3>Collaborative partnerships</h3>

        <p>We actively partner with diverse stakeholders to identify synergies and work collectively towards the shared vision of Indian Cities@2047.</p>

      </article>

    </div>

  </div>

</section>



<!-- TIMELINE -->

<section class="sec" id="timeline" aria-labelledby="tl-h">

  <div class="wrap">

    <div class="sec-head rv in">

      <div>

        <p class="kicker" data-num="—">Our journey</p>

        <h2 id="tl-h">Timeline</h2>

        <p class="lead">From the first possibility circle in 2022 to the launch of the Urban Reforms Collective—here's how the network has grown.</p>

      </div>

    </div>

    <ol class="tl">

<li class="tl-year" aria-hidden="true"><span>2022</span></li><li class="tl-item l rv in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">March 2022</p>

        <h3>Urban Governance Collective: 1st Possibility Circle</h3>

        <p class="tl-body">The first U-CAN meeting aimed to discover common grounds for creating an urban collective, articulating the purpose and gaining clarity on the next steps.</p>

      </div>

    </li>

<li class="tl-item r rv d1 in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">March 2022</p>

        <h3>Urban Governance Collective: 2nd Possibility Circle</h3>

        <p class="tl-body">The 2nd U-CAN possibility circle focused on aligning the working definitions of U-CAN, identifying preferred areas of engagement and agreeing on the next steps such as the frequency of subsequent meetings.</p>

      </div>

    </li>

<li class="tl-item l rv d2 in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">May 2022</p>

        <h3>Urban Governance Collective: 3rd Possibility Circle</h3>

        <p class="tl-body">The 3rd U-CAN Possibility Circle brought together members to refine the collective's principles, discuss effective ways of working, and align on ambitious goals, setting the foundation for meaningful collaboration across the urban ecosystem.</p>

      </div>

    </li>

<li class="tl-item r rv in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">October 2022</p>

        <h3>Meeting Zero</h3>

        <p class="tl-body">This meeting focused on shaping the collective's emerging charter, outlining a concrete action plan, and establishing a robust governance framework to guide its efforts and ensure accountability within the collective.</p>

      </div>

    </li>

<li class="tl-item l rv d1 in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">December 2022</p>

        <h3>U-CAN Charter</h3>

        <p class="tl-body">The charter defines U-CAN's purpose, principles, goals, themes, and approach. The collective envisions building a community of diverse yet complementary experts from industry, civil society, academia, and government to foster a unified ecosystem for impactful, scalable collective action.</p>

      </div>

    </li>

<li class="tl-year" aria-hidden="true"><span>2024</span></li><li class="tl-item r rv d2 in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">May 2024</p>

        <h3>In-person Steering Committee Meeting</h3>

        <p class="tl-body">U-CAN members gathered in person for the Steering Committee Meeting to reaffirm the collective's purpose, goals, and working modalities. The session offered a platform to reflect on past progress and shape U-CAN's program priorities for 2024 through collaborative input.</p>

      </div>

    </li>

<li class="tl-item l rv in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">May 2024</p>

        <h3>Network Dialogue and Kick-off</h3>

        <p class="tl-body">The Network Dialogue brought together diverse voices from the urban ecosystem, including donors, multilaterals, think tanks, and philanthropies. The session featured a Q&amp;A, inviting participants to explore the collective's vision and approach, followed by a collaborative exercise highlighting the value of joint problem-solving.</p>

      </div>

    </li>

<li class="tl-item r rv d1 in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">July 2024</p>

        <h3>Launch of the U-CAN Fellowship Program</h3>

        <p class="tl-body">The launch of the U-CAN Fellowship marked the first program under the U-CAN umbrella, setting the stage for empowering individuals passionate about sustainable urban development. This milestone reflected U-CAN's commitment to nurturing talent and driving systemic change in urban spaces across India.</p>

      </div>

    </li>

<li class="tl-item l rv d2 in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">October 2024</p>

        <h3>In-person Steering Committee Meeting</h3>

        <p class="tl-body">At the second in-person Steering Committee Meeting, U-CAN members review progress over the preceding six months, ideated and discussed program and operational priorities for 2025.</p>

      </div>

    </li>

<li class="tl-item r rv in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">November 2024</p>

        <h3>U-CAN Fellowship Begins for Mid-Career Professionals and Social Entrepreneurs</h3>

        <p class="tl-body">The U-CAN Fellowship officially commenced, welcoming six mid-career professionals working with U-CAN member organisations, and two social entrepreneur fellows.</p>

      </div>

    </li>

<li class="tl-year" aria-hidden="true"><span>2025</span></li><li class="tl-item l rv d1 in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">February 2025</p>

        <h3>Launch of the Request for Collaboration</h3>

        <p class="tl-body">The Request for Collaboration was launched, providing member organizations with the opportunity to collaborate on innovative projects aimed at addressing urban challenges. This initiative encourages experimentation and learning through collective action, paving the way for sustainable, scalable solutions within the urban ecosystem.</p>

      </div>

    </li>

<li class="tl-item r rv d2 in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">April 2025</p>

        <h3>Launch of the first City Mixers in Mumbai and Bangalore for our members</h3>

        <p class="tl-body">These get-togethers, hosted in partnership with our member organisations, are an opportunity for teams to meet in-person, exchange updates, and spark new ideas together.</p>

      </div>

    </li>

<li class="tl-item l rv in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">May 2025</p>

        <h3>In-person SteerCo Meeting</h3>

        <p class="tl-body">At the third in-person meeting, members get together to review updates since the last meeting and collectively shape the Annual Forum, due to be hosted in August 2025.</p>

      </div>

    </li>

<li class="tl-item r rv d1 in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">August 2025</p>

        <h3>First U-CAN Annual Forum</h3>

        <p class="tl-body">Our first-ever Annual Forum brought together 70+ urban changemakers from across U-CAN's network. Over three days in Panjim, Goa, participants shared insights, explored bold ideas, and built connections that continue to shape our collective work in urban governance.</p>

      </div>

    </li>

<li class="tl-item l rv d2 in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">October 2025</p>

        <h3>End of the first cohort of the U-CAN Fellowship</h3>

        <p class="tl-body">The first U-CAN Fellowship came to a close, marking the culmination of a transformative year in which fellows worked alongside urban institutions to co-create solutions and reimagine women's leadership in cities.</p>

      </div>

    </li>

<li class="tl-item r rv in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">December 2025</p>

        <h3>U-CAN's first public City Mixers in Delhi and Mumbai</h3>

        <p class="tl-body">Hosted by WRI India and Praja Foundation, the City Mixers were opened to the public for the first time, bringing together students, researchers, academics, and practitioners beyond the U-CAN network.</p>

      </div>

    </li>

<li class="tl-year" aria-hidden="true"><span>2026</span></li><li class="tl-item l rv d1 in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">January 2026</p>

        <h3>City Mixer in Bengaluru</h3>

        <p class="tl-body">Hosted by Reap Benefit, we began the year with an open “Adda” format that encouraged candid reflections and unstructured dialogue across the network.</p>

      </div>

    </li>

<li class="tl-item r rv d2 in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">February 2026</p>

        <h3>City Mixer in New Delhi</h3>

        <p class="tl-body">Hosted at the Centre for Policy Research, this mixer explored the state of collective action in our cities and the challenge of sustaining meaningful civic engagement.</p>

      </div>

    </li>

<li class="tl-item l rv in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <p class="tl-date">February 2026</p>

        <h3>In-person SteerCo Meeting in Lucknow</h3>

        <p class="tl-body">At our fourth in-person SteerCo meeting, members evaluated the impact of existing programs and new initiatives introduced this year, and joined a field visit to Banthra hosted by Shelter Associates.</p>

      </div>

    </li>

<li class="tl-item r is-latest rv d1 in">

      <div class="tl-marker" aria-hidden="true"></div>

      <div class="tl-card">

        <span class="tl-flag">Latest</span><p class="tl-date">June 2026</p>

        <h3>Launch of the Urban Reforms Collective in Mumbai</h3>

        <p class="tl-body">12 organisations signed the Mumbai Declaration, marking the formal launch of the Urban Reforms Collective.</p>

      </div>

    </li>

    </ol>

  </div>

</section>



<!-- CTA PAIR -->





<!-- NEWSLETTER -->






<?php
get_footer();
