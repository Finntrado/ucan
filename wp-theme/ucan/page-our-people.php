<?php
/**
 * Template Name: Our People
 * Auto-applies to a WP Page whose slug is "our-people" (file-name
 * convention - page-our-people.php). Content lifted verbatim from
 * standalone/our-people.html's <main> (CLAUDE.md: content stays verbatim unless a
 * named fix is requested); only asset paths and internal links were
 * rewritten to WP functions. The page's own JSON-LD (already carrying
 * correct absolute urban.org.in canonical URLs) is re-emitted unchanged,
 * so functions.php's generic wp_head hook skips its own Organization node
 * for this page.
 */

$ucan_page_meta = array(
	'description' => 'Meet the founders, steering committee and team driving U-CAN\'s work to strengthen urban problem-solving in India\'s cities.',
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

      "@id": "https://urban.org.in/our-people/#page",

      "url": "https://urban.org.in/our-people/",

      "name": "Our People | U-CAN, Urban Collective Action Network",

      "description": "Meet the founders, steering committee and team driving U-CAN\'s work to strengthen urban problem-solving in India\'s cities.",

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

          "name": "Our People",

          "item": "https://urban.org.in/our-people/"

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

      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">/</span><span>Our People</span>

    </nav>

    <p class="hero-tag">The people behind the network</p>

    <h1 id="h1">Our People</h1>

    <p class="hero-lede">U-CAN is run by a small backbone team and guided by a wider circle of founders and member representatives who steer the network's strategy and hold it accountable to its mission.</p>

  </div>

</section>



<!-- PEOPLE -->

<section class="sec" id="people" aria-label="Our People">

  <div class="wrap">

    



    <div class="grp" id="founding">

      <div class="grp-head"><h2>Founding Circle</h2></div>

      <ul class="people" aria-label="Founding Circle">

      <li class="pcard rv in">

        <a href="<?php echo esc_url( home_url( '/profile-gautham-ravichander/' ) ); ?>" aria-label="Gautham Ravichander, eGov Foundation — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/gautham-ravichander-c23c86573e.webp" width="50" height="50" loading="lazy" decoding="async" alt="Gautham Ravichander">

          <span class="p-info">

            <span class="p-name">Gautham Ravichander</span>

            <span class="p-role">eGov Foundation</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d1 in">

        <a href="<?php echo esc_url( home_url( '/profile-jagan-shah/' ) ); ?>" aria-label="Jagan Shah, The Infravision Foundation — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/jagan-shah-ad53cd9ce1.webp" width="50" height="50" loading="lazy" decoding="async" alt="Jagan Shah">

          <span class="p-info">

            <span class="p-name">Jagan Shah</span>

            <span class="p-role">The Infravision Foundation</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d2 in">

        <a href="<?php echo esc_url( home_url( '/profile-jaya-dhindaw/' ) ); ?>" aria-label="Jaya Dhindaw, WRI India — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/jaya-dhindaw-0c4987dc1b.webp" width="50" height="50" loading="lazy" decoding="async" alt="Jaya Dhindaw">

          <span class="p-info">

            <span class="p-name">Jaya Dhindaw</span>

            <span class="p-role">WRI India</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d3 in">

        <a href="<?php echo esc_url( home_url( '/profile-kuldeep-dantewadia/' ) ); ?>" aria-label="Kuldeep Dantewadia, Reap Benefit — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/kuldeep-dantewadia-27b36ac106.webp" width="50" height="50" loading="lazy" decoding="async" alt="Kuldeep Dantewadia">

          <span class="p-info">

            <span class="p-name">Kuldeep Dantewadia</span>

            <span class="p-role">Reap Benefit</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv in">

        <a href="<?php echo esc_url( home_url( '/profile-madhav-pai/' ) ); ?>" aria-label="Madhav Pai, WRI India — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/madhav-pai-469cda42dc.webp" width="50" height="50" loading="lazy" decoding="async" alt="Madhav Pai">

          <span class="p-info">

            <span class="p-name">Madhav Pai</span>

            <span class="p-role">WRI India</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d1 in">

        <a href="<?php echo esc_url( home_url( '/profile-milind-mhaske/' ) ); ?>" aria-label="Milind Mhaske, Praja Foundation — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/milind-mhaske-21d23a5672.webp" width="50" height="50" loading="lazy" decoding="async" alt="Milind Mhaske">

          <span class="p-info">

            <span class="p-name">Milind Mhaske</span>

            <span class="p-role">Praja Foundation</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d2 in">

        <a href="<?php echo esc_url( home_url( '/profile-pratima-joshi/' ) ); ?>" aria-label="Pratima Joshi, Shelter Associates — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/pratima-joshi-83661514ee.webp" width="50" height="50" loading="lazy" decoding="async" alt="Pratima Joshi">

          <span class="p-info">

            <span class="p-name">Pratima Joshi</span>

            <span class="p-role">Shelter Associates</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d3 in">

        <a href="<?php echo esc_url( home_url( '/profile-pritika-hingorani/' ) ); ?>" aria-label="Pritika Hingorani, Artha Global (India) — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/pritika-hingorani-20f3bf622a.webp" width="50" height="50" loading="lazy" decoding="async" alt="Pritika Hingorani">

          <span class="p-info">

            <span class="p-name">Pritika Hingorani</span>

            <span class="p-role">Artha Global (India)</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv in">

        <a href="<?php echo esc_url( home_url( '/profile-shilpa-kumar/' ) ); ?>" aria-label="Shilpa Kumar, British International Investment — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/shilpa-kumar-b7ca333c99.webp" width="50" height="50" loading="lazy" decoding="async" alt="Shilpa Kumar">

          <span class="p-info">

            <span class="p-name">Shilpa Kumar</span>

            <span class="p-role">British International Investment</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d1 in">

        <a href="<?php echo esc_url( home_url( '/profile-shubhagato-dasgupta/' ) ); ?>" aria-label="Shubhagato Dasgupta, Centre for Policy Research — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/shubhagato-dasgupta-1e05e861cb.webp" width="50" height="50" loading="lazy" decoding="async" alt="Shubhagato Dasgupta">

          <span class="p-info">

            <span class="p-name">Shubhagato Dasgupta</span>

            <span class="p-role">Centre for Policy Research</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d2 in">

        <a href="<?php echo esc_url( home_url( '/profile-srikanth-viswanathan/' ) ); ?>" aria-label="Srikanth Viswanathan, Janaagraha — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/srikanth-viswanathan-d20af6bc57.webp" width="50" height="50" loading="lazy" decoding="async" alt="Srikanth Viswanathan">

          <span class="p-info">

            <span class="p-name">Srikanth Viswanathan</span>

            <span class="p-role">Janaagraha</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d3 in">

        <a href="<?php echo esc_url( home_url( '/profile-viraj-tyagi/' ) ); ?>" aria-label="Viraj Tyagi, eGov Foundation — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/viraj-tyagi-57d2a2923a.webp" width="50" height="50" loading="lazy" decoding="async" alt="Viraj Tyagi">

          <span class="p-info">

            <span class="p-name">Viraj Tyagi</span>

            <span class="p-role">eGov Foundation</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      </ul>

    </div>



    <div class="grp" id="steering">

      <div class="grp-head"><h2>Steering Committee</h2></div>

      <ul class="people" aria-label="Steering Committee">

      <li class="pcard rv in">

        <a href="<?php echo esc_url( home_url( '/profile-champaka-rajagopal/' ) ); ?>" aria-label="Champaka Rajagopal, Centre for Policy Research — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/champaka-rajagopal-401d5e6a72.webp" width="50" height="50" loading="lazy" decoding="async" alt="Champaka Rajagopal">

          <span class="p-info">

            <span class="p-name">Champaka Rajagopal</span>

            <span class="p-role">Centre for Policy Research</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d1 in">

        <a href="<?php echo esc_url( home_url( '/profile-dhanashree-gurav/' ) ); ?>" aria-label="Dhanashree Gurav, Shelter Associates — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/dhanashree-gurav-a9bf39ff3d.webp" width="50" height="50" loading="lazy" decoding="async" alt="Dhanashree Gurav">

          <span class="p-info">

            <span class="p-name">Dhanashree Gurav</span>

            <span class="p-role">Shelter Associates</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d2 in">

        <a href="<?php echo esc_url( home_url( '/profile-gautham-ravichander/' ) ); ?>" aria-label="Gautham Ravichander, eGov Foundation — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/gautham-ravichander-c23c86573e.webp" width="50" height="50" loading="lazy" decoding="async" alt="Gautham Ravichander">

          <span class="p-info">

            <span class="p-name">Gautham Ravichander</span>

            <span class="p-role">eGov Foundation</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d3 in">

        <a href="<?php echo esc_url( home_url( '/profile-krishnan-subbaraman/' ) ); ?>" aria-label="Krishnan Subbaraman, Janaagraha — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/krishnan-subbaraman-f6d1962753.webp" width="50" height="50" loading="lazy" decoding="async" alt="Krishnan Subbaraman">

          <span class="p-info">

            <span class="p-name">Krishnan Subbaraman</span>

            <span class="p-role">Janaagraha</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv in">

        <a href="<?php echo esc_url( home_url( '/profile-mayura-gadkari/' ) ); ?>" aria-label="Mayura Gadkari, Artha Global (India) — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/mayura-gadkari-f05974079d.webp" width="50" height="50" loading="lazy" decoding="async" alt="Mayura Gadkari">

          <span class="p-info">

            <span class="p-name">Mayura Gadkari</span>

            <span class="p-role">Artha Global (India)</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d1 in">

        <a href="<?php echo esc_url( home_url( '/profile-meghna-bandelwar-indurkar/' ) ); ?>" aria-label="Meghna Bandelwar Indurkar, Praja Foundation — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/meghna-bandelwar-indurkar-6f4b7c9d83.webp" width="72" height="72" loading="lazy" decoding="async" alt="Meghna Bandelwar Indurkar">

          <span class="p-info">

            <span class="p-name">Meghna Bandelwar Indurkar</span>

            <span class="p-role">Praja Foundation</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d2 in">

        <a href="<?php echo esc_url( home_url( '/profile-neha-lal/' ) ); ?>" aria-label="Neha Lal, WRI India — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/neha-lal-80fd3ce895.webp" width="50" height="50" loading="lazy" decoding="async" alt="Neha Lal">

          <span class="p-info">

            <span class="p-name">Neha Lal</span>

            <span class="p-role">WRI India</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d3 in">

        <a href="<?php echo esc_url( home_url( '/profile-sarah-misra/' ) ); ?>" aria-label="Sarah Misra, Reap Benefit — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/sarah-misra-694eaf22af.webp" width="50" height="50" loading="lazy" decoding="async" alt="Sarah Misra">

          <span class="p-info">

            <span class="p-name">Sarah Misra</span>

            <span class="p-role">Reap Benefit</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      </ul>

    </div>



    <div class="grp" id="stewardship">

      <div class="grp-head"><h2>Stewardship Team</h2></div>

      <ul class="people" aria-label="Stewardship Team">

      <li class="pcard rv in">

        <a href="<?php echo esc_url( home_url( '/profile-jaya-dhindaw/' ) ); ?>" aria-label="Jaya Dhindaw, WRI India — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/jaya-dhindaw-0c4987dc1b.webp" width="50" height="50" loading="lazy" decoding="async" alt="Jaya Dhindaw">

          <span class="p-info">

            <span class="p-name">Jaya Dhindaw</span>

            <span class="p-role">WRI India</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d1 in">

        <a href="<?php echo esc_url( home_url( '/profile-milind-mhaske/' ) ); ?>" aria-label="Milind Mhaske, Praja Foundation — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/milind-mhaske-21d23a5672.webp" width="50" height="50" loading="lazy" decoding="async" alt="Milind Mhaske">

          <span class="p-info">

            <span class="p-name">Milind Mhaske</span>

            <span class="p-role">Praja Foundation</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d2 in">

        <a href="<?php echo esc_url( home_url( '/profile-pritika-hingorani/' ) ); ?>" aria-label="Pritika Hingorani, Artha Global (India) — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/pritika-hingorani-20f3bf622a.webp" width="50" height="50" loading="lazy" decoding="async" alt="Pritika Hingorani">

          <span class="p-info">

            <span class="p-name">Pritika Hingorani</span>

            <span class="p-role">Artha Global (India)</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d3 in">

        <a href="<?php echo esc_url( home_url( '/profile-srikanth-viswanathan/' ) ); ?>" aria-label="Srikanth Viswanathan, Janaagraha — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/srikanth-viswanathan-d20af6bc57.webp" width="50" height="50" loading="lazy" decoding="async" alt="Srikanth Viswanathan">

          <span class="p-info">

            <span class="p-name">Srikanth Viswanathan</span>

            <span class="p-role">Janaagraha</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      </ul>

    </div>



    <div class="grp" id="team">

      <div class="grp-head"><h2>Our Team</h2></div>

      <ul class="people team" aria-label="Our Team">

      <li class="pcard rv in">

        <a href="<?php echo esc_url( home_url( '/profile-siddharth-pandit/' ) ); ?>" aria-label="Siddharth Pandit, Chief Executive Officer — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/siddharth-pandit-c60ab2ec36.webp" width="50" height="50" loading="lazy" decoding="async" alt="Siddharth Pandit">

          <span class="p-info">

            <span class="p-name">Siddharth Pandit</span>

            <span class="p-role team">Chief Executive Officer</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d1 in">

        <a href="<?php echo esc_url( home_url( '/profile-akshay-agarwal/' ) ); ?>" aria-label="Akshay Agarwal, Lead – Programs — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/akshay-agarwal-8fd86b10d0.webp" width="50" height="50" loading="lazy" decoding="async" alt="Akshay Agarwal">

          <span class="p-info">

            <span class="p-name">Akshay Agarwal</span>

            <span class="p-role team">Lead – Programs</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      <li class="pcard rv d3 in">

        <a href="<?php echo esc_url( home_url( '/profile-manali-shah/' ) ); ?>" aria-label="Manali Shah, Lead – Facilitation — view profile">

          <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/manali-shah-1d82336c25.webp" width="50" height="50" loading="lazy" decoding="async" alt="Manali Shah">

          <span class="p-info">

            <span class="p-name">Manali Shah</span>

            <span class="p-role team">Lead – Facilitation</span>

          </span>

          <span class="p-go" aria-hidden="true">↗</span>

        </a>

      </li>

      </ul>

    </div>



    </div>

</section>



<!-- CTA PAIR -->





<!-- NEWSLETTER -->






<?php
get_footer();
