<?php
/**
 * Template Name: Impact
 * Auto-applies to a WP Page whose slug is "impact" (file-name
 * convention - page-impact.php). Content lifted verbatim from
 * standalone/impact.html's <main> (CLAUDE.md: content stays verbatim unless a
 * named fix is requested); only asset paths and internal links were
 * rewritten to WP functions. The page's own JSON-LD (already carrying
 * correct absolute urban.org.in canonical URLs) is re-emitted unchanged,
 * so functions.php's generic wp_head hook skips its own Organization node
 * for this page.
 */

$ucan_page_meta = array(
	'description' => 'See U-CAN\'s impact in numbers and stories, from peer learning networks and the Request for Collaboration to the Fellowship, City Mixers and Roots and Horizons.',
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

      "@id": "https://urban.org.in/impact/#page",

      "url": "https://urban.org.in/impact/",

      "name": "Our Impact | U-CAN, Urban Collective Action Network",

      "description": "See U-CAN\'s impact in numbers and stories, from peer learning networks and the Request for Collaboration to the Fellowship, City Mixers and Roots and Horizons.",

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

          "name": "Impact",

          "item": "https://urban.org.in/impact/"

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

      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">/</span><span>Impact</span>

    </nav>

    <h1 id="h1">What We've Built Together</h1>

    <p class="hero-lede">Since 2022, U-CAN has turned individual effort into collective action across India's cities. Here's what that collaboration has produced so far, in numbers, and in the stories behind them.</p>

    

  </div>

</section>



<section class="sec" id="figures" aria-labelledby="fig-h" style="padding-bottom:0">

  <div class="wrap">

    <div class="sec-head rv in">

      <div>

        <p class="kicker" data-num="—">By the numbers</p>

        <h2 id="fig-h">The network at a glance</h2>

      </div>

    </div>

  </div>

  <div class="impact-band" id="figs">

    <div class="wrap">

      <div class="impact-figs">

        <div class="fig">

          <span class="fig-ico" aria-hidden="true"><svg viewBox="0 0 40 40"><path d="M20 8.5 8 15v10l12 6.5L32 25V15z" opacity=".28"></path><path d="M20 8.5 8 15v10l12 6.5L32 25V15z" fill="none" stroke-width="1.8"></path><path d="M13 12.5h5.4M13 16h5.4M13 19.5h5.4M22 12.5h5M22 16h5M22 19.5h5" stroke-width="1.6"></path></svg></span>

          <span class="kick">A network of</span>

          <b data-to="8" data-suffix="" data-comma="0">8</b>

          <span class="cap">Member organisations</span>

        </div>

        <div class="fig">

          <span class="fig-ico" aria-hidden="true"><svg viewBox="0 0 40 40"><circle cx="20" cy="12" r="4.4"></circle><circle cx="9.5" cy="27" r="4"></circle><circle cx="30.5" cy="27" r="4"></circle><path d="M17 15.5 12 23.5M23 15.5l5 8M13.5 27h13" fill="none" stroke-width="1.8"></path></svg></span>

          <span class="kick">Connecting</span>

          <b data-to="500" data-suffix="+" data-comma="0">500+</b>

          <span class="cap">Practitioners</span>

        </div>

        <div class="fig">

          <span class="fig-ico" aria-hidden="true"><svg viewBox="0 0 40 40"><path d="M8 16v8h5l8 6V10l-8 6z"></path><path d="M26 15a7 7 0 0 1 0 10M30 11a12 12 0 0 1 0 18" fill="none" stroke-width="1.8"></path></svg></span>

          <span class="kick">Reaching</span>

          <b data-to="15000" data-suffix="+" data-comma="1">15,000+</b>

          <span class="cap">People across channels</span>

        </div>

        <div class="fig">

          <span class="fig-ico" aria-hidden="true"><svg viewBox="0 0 40 40"><path d="M20 5c-5.8 0-10.5 4.6-10.5 10.4C9.5 23 20 35 20 35s10.5-12 10.5-19.6C30.5 9.6 25.8 5 20 5z"></path><circle cx="20" cy="15.2" r="3.6" fill="var(--teal-deep)"></circle></svg></span>

          <span class="kick">Across</span>

          <b data-to="25" data-suffix="+" data-comma="0">25+</b>

          <span class="cap">Cities in 3 states</span>

        </div>

      </div>

    </div>

  </div>

</section>



<section class="sec" id="proof" aria-labelledby="pp-h">

  <div class="wrap">

    <div class="sec-head rv in">

      <div>

        <p class="kicker" data-num="—">The proof points</p>

        <h2 id="pp-h">What collective action has produced</h2>

        <p class="lead">Eight ways the network has changed what's possible for India's cities — in Communities of Learning, fellowships, forums and platforms that carry from one city to the next.</p>

      </div>

    </div>

    <div class="proof-list">

      <article class="pp rv in" style="--acc:var(--teal);--wash:rgba(31,143,123,.06)" tabindex="0">

        <div class="pp-top">

          <span class="pp-idx" aria-hidden="true">01</span>

          <span class="pp-line" aria-hidden="true"></span>

        </div>

        <div class="pp-body">

          <h3>Increased learning opportunities and knowledge exchange across mid-level administration</h3>

          <p>Through Communities of Learning, spanning 200 officials across 25+ cities in 3 states.</p>

        </div>

      </article>

      <article class="pp rv in" style="--acc:var(--teal);--wash:rgba(31,143,123,.06)" tabindex="0">

        <div class="pp-top">

          <span class="pp-idx" aria-hidden="true">02</span>

          <span class="pp-line" aria-hidden="true"></span>

        </div>

        <div class="pp-body">

          <h3>Developed a governance innovation platform</h3>

          <p>Through structured cross-organisational collaboration through the Request for Collaboration (RFC) initiative. The platform is now being implemented in 3 Indian cities and Nairobi, Kenya.</p>

        </div>

      </article>

      <article class="pp rv in" style="--acc:var(--teal);--wash:rgba(31,143,123,.06)" tabindex="0">

        <div class="pp-top">

          <span class="pp-idx" aria-hidden="true">03</span>

          <span class="pp-line" aria-hidden="true"></span>

        </div>

        <div class="pp-body">

          <h3>Activated place-based ecosystems</h3>

          <p>Through 10+ City Mixers, connecting 500+ practitioners, researchers, funders, communicators, and community leaders across sectors to increase understanding of urban systems.</p>

          <figure class="proof-quote">

            <blockquote>I truly enjoyed the U-CAN mixer at the WRI Delhi office, the format felt refreshing, focusing less on project presentations and more on personal journeys and what inspires our work.</blockquote>

            <figcaption><span class="pq-name">Nidhi Batra</span><span class="pq-role">Founder, Sehreeti Development Practices</span></figcaption>

          </figure>

        </div>

      </article>

      <article class="pp rv in" style="--acc:var(--teal);--wash:rgba(31,143,123,.06)" tabindex="0">

        <div class="pp-top">

          <span class="pp-idx" aria-hidden="true">04</span>

          <span class="pp-line" aria-hidden="true"></span>

        </div>

        <div class="pp-body">

          <h3>Mainstreaming urban discourse using various media channels</h3>

          <p>4 podcast episodes, 10+ webinars and 30+ newsletters reaching an audience of 15,000+.</p>

        </div>

      </article>

      <article class="pp rv in" style="--acc:var(--teal);--wash:rgba(31,143,123,.06)" tabindex="0">

        <div class="pp-top">

          <span class="pp-idx" aria-hidden="true">05</span>

          <span class="pp-line" aria-hidden="true"></span>

        </div>

        <div class="pp-body">

          <h3>Advanced voices of women in the urban sector</h3>

          <p>With the U-CAN Women's Fellowship, supporting 6 professionals and 2 entrepreneurs through embedded practice and mentorship. Projects spanned a rainwater harvesting calculator piloted in Gurugram, ward-level climate action plans in Bengaluru, and redesigning public spaces in Jaipur and Chennai.</p>

          <figure class="proof-quote">

            <blockquote>The U-CAN Fellowship became a transformative space to deepen this pursuit, through hands-on work with host organisations, writing blogs, and co-developing solutions that strengthened my understanding of data-driven and participatory approaches to urban resilience.</blockquote>

            <figcaption><span class="pq-name">Shubhi Kesarwani</span></figcaption>

          </figure>

        </div>

      </article>

      <article class="pp rv in" style="--acc:var(--teal);--wash:rgba(31,143,123,.06)" tabindex="0">

        <div class="pp-top">

          <span class="pp-idx" aria-hidden="true">06</span>

          <span class="pp-line" aria-hidden="true"></span>

        </div>

        <div class="pp-body">

          <h3>Organised participatory and non-hierarchical space</h3>

          <p>Bringing together practitioners, community leaders, and government on a common platform, The U-CAN Annual Forum. Strengthened trust and deepened cross-sector relationships amongst 100+ participants across 20+ organisations.</p>

          <figure class="proof-quote">

            <blockquote>The Annual Forum was a valuable space for bringing together diverse voices from across the urban ecosystem. It helped us reflect on how national policy priorities connect with the lived realities of cities.</blockquote>

            <figcaption><span class="pq-name">Gurjit Singh Dhillon</span><span class="pq-role">Director, Ministry of Housing and Urban Affairs</span></figcaption>

          </figure>

        </div>

      </article>

      <article class="pp rv in" style="--acc:var(--teal);--wash:rgba(31,143,123,.06)" tabindex="0">

        <div class="pp-top">

          <span class="pp-idx" aria-hidden="true">07</span>

          <span class="pp-line" aria-hidden="true"></span>

        </div>

        <div class="pp-body">

          <h3>Initiated a shift from a programmatic approach to a strategic approach</h3>

          <p>For state-level engagement. Convened teams across 5 organisations in a series of discussions for Uttar Pradesh to align infrastructure-focused planning with community-grounded action.</p>

        </div>

      </article>

      <article class="pp rv in" style="--acc:var(--teal);--wash:rgba(31,143,123,.06)" tabindex="0">

        <div class="pp-top">

          <span class="pp-idx" aria-hidden="true">08</span>

          <span class="pp-line" aria-hidden="true"></span>

        </div>

        <div class="pp-body">

          <h3>Developed a knowledge commons, 'Roots and Horizons'</h3>

          <p>To increase visibility of organisational work, journeys, practices and futures of 9 organisations to peers and donors.</p>

        </div>

      </article>

    </div>

  

</div></section>



<section class="sec alt" id="voices" aria-labelledby="v-h">

  <div class="wrap">

    <div class="sec-head rv in">

      <div>

        <p class="kicker" data-num="—">In their words</p>

        <h2 id="v-h">What the network means to its members</h2>

      </div>

    </div>

    <div class="voices">

        <figure class="voice rv in">

          <blockquote>Emerging cities today tell us that growth is not something you can design on paper, it's something you build through trust, collaboration, and constant learning. Real change happens when intent, leadership, and citizen energy start to move in the same direction.</blockquote>

          <figcaption>

            <img class="v-photo" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/mayura-gadkari-0f2cf67e7b.webp" width="56" height="56" loading="lazy" decoding="async" alt="Mayura Gadkari">

            <span class="v-meta"><span class="v-name">Mayura Gadkari</span><span class="v-role">Principal, Artha Centre for Emerging Cities, Artha Global</span></span>

          </figcaption>

        </figure>

        <figure class="voice rv in">

          <blockquote>Each organisation in U-CAN has its own story, shaped by years of working in different cities, with different communities and challenges. Yet, through this collective, those stories connect, revealing patterns, shared struggles, and possibilities that none of us could see alone.</blockquote>

          <figcaption>

            <img class="v-photo" src="data:image/webp;base64,UklGRlwEAABXRUJQVlA4IFAEAADQEwCdASo4ADgAPj0aikOiIaETDVbEIAPEoAnTOOixyIrD3mI98KTbl+YDwZvc/vKHoMdK7PuOO/zP7IaAH0p/AZyDe5+844PHN57n/B5bvqD/n+4H+sHRANUtLqB0RpXvR6l0/gdpfv2IjQd1dw3b200HZhPk/ITP/6OELQxG70uTkOELTvDn6RMJsXH9Sx/BX5ZqDEmA/f1cwzsqfgre6t+kj7DKgAD+/ULh2pQ80hLmbrbW/K5CQsB+8183d/2FReZRFlsyvphsDyLcYb6Iymtdund+Lrh8LvH7TS6Kql83XudQ/fG6uyfDg7DHx3mV/nHqa8ZMYf0RGRyOujp9QfueYGeT7RZnu1p2btXp/uubQNJBprce6iw9n+1U9SxxgV/R/GLUstnibVT0Qq5g/qmlyOV6fb/2AYSt4uVpYKfKi3S+jWE/upV0d5BNjr/HYY061/KN3elYB2xLPIm9V5GGyj+cWtP/Vd++aDXp1U3e5t+TDPLnqn3wgCqI1wLuIO7c9cI3Hv3UZ5zevkY6XFhs5/gA7u00WuEWMdLjWWnMuNe7w6mzuNwNUNZ80PH9DcrCeTjDParrXcjv26U0woWks1NK/6CP9tv+PNv5LZI4jdL46QK6RY9rZwldta4X3Apnes1E7wEr5yyNJolXGuN2oxaQBGH+vHwzj8t+W3zk3ga2Fi/Yh5UxumvvK9wQeAIgFfoM6T1/9JudJmYIE7LYewO7xmjYV+24c+TZpSuRe7gfsm/YPcVNaGXNVPadQLnfR/cxBekrCzLpqp0TwGiNDzRpri4unntWj9FUUPDA3jMGwdBwda6DXJMFofHtPqa7pyZBoUvxZV8AOtvSlJF/feG1tJOkChWhmYTGsElqI86BXqZvTgWLi8Mrf/ysRd1y7Da+FYhp3NSO+Q5Dt2v4k93LztYMz/+En/pD1nScUg78rvT2p3fuTrdNfwSjOXFVvS1bG7gsDSd1QWjojKE7ItVC70U8B3RIPtn1h/H3wlj+y3BOx585nEalVSLJ5XJzdZ9d2YSLF/OQU5vprGbdeeE2kdNIc9f7G5jHylIvchCEbqqC+F/1el9uiGdgnywia11C687ywlu3aUMnrHS6UnkBKPP/mrtjlQK/StFz6n3bFaZInMjrnCuHtvrHUhd48lB7Hs2ukRQRjfpqiMm96aj6J//d35Y/Yy+b/Ph+dVP/m1nVLkGcntKyrc7UIN3zWQpoFtjTHJEmheruGmtTf0zdSGLDSrPaD7aeum66KiabxDn79RRo92ZLgrf8Tz+we9NSGjr+oFmTU1zgfUkvVS7t5ztH0HOM2Gr5ykGInjqp+C8mwdptMsdhr3kOKE5Vc64LkXH6qd3d5fJLuqkU74K7CBJ+zOaP995HiOj8e/NKyKwiCqMpJ7nEoGxNbAdYjvH9UEZ67o8K4PRRt9G3SXS5wfuv9o//ILbGB254jcMsxwOGwxF3FwwAAAA=" width="56" height="56" loading="lazy" decoding="async" alt="Meghna Indurkar">

            <span class="v-meta"><span class="v-name">Meghna Indurkar</span><span class="v-role">Head of Strategic Communication, Praja Foundation</span></span>

          </figcaption>

        </figure>

        <figure class="voice rv in">

          <blockquote>It is extremely useful to hear from the other members and their state teams who have been working in Uttar Pradesh for years. As a new entrant in the state, we learnt how the state machinery functions, how officials perceive us and what we need to do for them to value our work, thereby shortening our learning curve.</blockquote>

          <figcaption>

            <img class="v-photo" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/pratima-joshi-7e64221802.webp" width="56" height="56" loading="lazy" decoding="async" alt="Pratima Joshi">

            <span class="v-meta"><span class="v-name">Pratima Joshi</span><span class="v-role">Founder and Executive Director, Shelter Associates</span></span>

          </figcaption>

        </figure>

    </div>

  </div>

</section>












<?php
get_footer();
