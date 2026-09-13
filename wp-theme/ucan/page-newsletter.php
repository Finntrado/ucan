<?php
/**
 * Template Name: Newsletter
 * Auto-applies to a WP Page whose slug is "newsletter" (file-name
 * convention - page-newsletter.php) - coexists fine with the
 * ucan_newsletter CPT's own "newsletter" rewrite slug (see functions.php's
 * ucan_register_newsletter_cpt()): this Page owns the one-segment
 * /newsletter/ path, individual issues sit at the two-segment
 * /newsletter/<slug>/.
 *
 * Hero/breadcrumb extracted verbatim from standalone/newsletter.html; the
 * featured-issue card and the archive grid are now live ucan_newsletter
 * queries (CLAUDE.md §28) - "featured" is just the newest issue by date,
 * not a hardcoded slug, so next month's issue becomes featured
 * automatically once it's the newest post. Renders empty until those
 * posts exist (phase 7's importer). The page's own JSON-LD (already
 * carrying correct absolute urban.org.in canonical URLs) is re-emitted
 * unchanged, so functions.php's generic wp_head hook skips its own
 * Organization node for this page.
 */

$ucan_page_meta = array(
	'description' => 'Join U-CAN\'s community for monthly updates on urban governance, fellowship insights, city reform stories, and collaboration opportunities across India\'s Tier II and Tier III cities.',
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

      "@id": "https://urban.org.in/newsletter/#page",

      "url": "https://urban.org.in/newsletter/",

      "name": "Newsletter | U-CAN, Urban Collective Action Network",

      "description": "Urban governance insights from U-CAN, delivered monthly. Browse past editions of the newsletter.",

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

          "name": "Newsletter",

          "item": "https://urban.org.in/newsletter/"

        }

      ]

    }

  ]

}';

get_header();
?>




<!-- ————— HERO BANNER ————— -->

<section class="nl-hero">

  <div class="nl-hero-bg" aria-hidden="true">

    <svg viewBox="0 0 1200 500" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

      <circle cx="150" cy="100" r="300" fill="#1F8F7B" opacity=".3"></circle>

      <circle cx="1000" cy="400" r="250" fill="#CDDE71" opacity=".15"></circle>

      <circle cx="600" cy="250" r="160" fill="#4EC6B2" opacity=".1"></circle>

      <line x1="0" y1="200" x2="1200" y2="200" stroke="#4EC6B2" stroke-width="1" stroke-dasharray="6 12" opacity=".25"></line>

      <line x1="0" y1="350" x2="1200" y2="350" stroke="#4EC6B2" stroke-width="1" stroke-dasharray="6 12" opacity=".15"></line>

    </svg>

  </div>



  <div class="wrap">

    <div class="nl-hero-grid" style="grid-template-columns:1fr;max-width:70ch">

      <div>

        <div class="eyebrow">Media · Monthly Updates</div>

        <h1>Newsletter</h1>

        <p class="nl-hero-lead">Join our community to receive regular updates on how people are driving change in our cities. We share insights on fostering collaboration, highlight the innovative ideas our fellows are working on, and let you know about opportunities to get involved.</p>

      </div>

    </div>

  </div>

</section>



<!-- breadcrumb -->

<div class="wrap">

  <nav class="crumbs" aria-label="Breadcrumb" style="padding-bottom:0">

    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span class="sep">/</span>

    <span aria-current="page">Newsletter</span>

  </nav>

</div>



<!-- ————— FEATURED LATEST ISSUE ————— -->
<?php
/**
 * Phase 6: dynamic replacement for the hardcoded "featured" issue and the
 * static 29-row archive grid - both are live ucan_newsletter queries.
 * "Featured" is simply the newest issue by post_date, not a hand-picked
 * slug - set each issue's post_date to its real edition date on import
 * and this always shows the actual latest one, no re-editing needed each
 * month. Year filter buttons/hidden-row reveal are unchanged - ucan.js's
 * existing archive-filter handler already keys on `data-year || data-author`
 * (§25) and needs no changes here.
 */
$featured_q = new WP_Query( array( 'post_type' => 'ucan_newsletter', 'posts_per_page' => 1, 'orderby' => 'date', 'order' => 'DESC' ) );
$years = array();
$all_issues = new WP_Query( array( 'post_type' => 'ucan_newsletter', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC' ) );
foreach ( $all_issues->posts as $p ) {
	$years[ get_the_date( 'Y', $p ) ] = true;
}
krsort( $years );
?>
<?php if ( $featured_q->have_posts() ) : $featured_q->the_post(); $f = ucan_newsletter_display_fields( get_post() ); ?>
<section class="featured">
  <div class="wrap">
    <div class="eyebrow" style="margin-bottom:1.75rem">Latest edition</div>
    <a href="<?php the_permalink(); ?>" class="featured-card" style="text-decoration:none;color:var(--ink)">
      <div class="featured-cover">
        <div class="featured-cover-bg" aria-hidden="true">
          <svg viewBox="0 0 420 400" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
            <rect x="280" y="-20" width="200" height="200" rx="100" fill="#1F8F7B" opacity=".4"></rect>
            <rect x="-40" y="220" width="180" height="180" rx="90" fill="#CDDE71" opacity=".2"></rect>
            <line x1="40" y1="0" x2="40" y2="400" stroke="#4EC6B2" stroke-width="1" stroke-dasharray="4 8" opacity=".3"></line>
            <line x1="140" y1="0" x2="140" y2="400" stroke="#4EC6B2" stroke-width="1" stroke-dasharray="4 8" opacity=".2"></line>
            <line x1="240" y1="0" x2="240" y2="400" stroke="#4EC6B2" stroke-width="1" stroke-dasharray="4 8" opacity=".15"></line>
            <line x1="340" y1="0" x2="340" y2="400" stroke="#4EC6B2" stroke-width="1" stroke-dasharray="4 8" opacity=".1"></line>
          </svg>
        </div>
        <div class="cover-badge">Latest Issue</div>
        <div class="cover-title">
          <div class="edition"><?php echo esc_html( $f['edition_name'] ); ?></div>
          <div class="month"><?php echo esc_html( $f['month'] ); ?></div>
          <div class="year"><?php echo esc_html( $f['year'] ); ?></div>
        </div>
      </div>
      <div class="featured-body">
        <div class="featured-tag"><span class="dot"></span><?php echo esc_html( $f['month_year'] ); ?> edition</div>
        <h2 class="featured-title"><?php the_title(); ?></h2>
        <p><?php echo esc_html( get_the_excerpt() ); ?></p>
        <span class="btn btn-primary" style="width:fit-content">
          Read the <?php echo esc_html( $f['month'] ); ?> edition
          <svg viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 6h8m0 0L6.5 2.5M10 6L6.5 9.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        </span>
      </div>
    </a>
  </div>
</section>
<?php wp_reset_postdata(); endif; ?>

<!-- ————— PAST ISSUES ARCHIVE ————— -->
<section class="archive">
  <div class="wrap">
    <div class="archive-head">
      <h2>Past Newsletters</h2>
      <div class="archive-filter">
        <button class="filter-btn active" data-filter="all">All</button>
        <?php foreach ( array_keys( $years ) as $y ) : ?><button class="filter-btn" data-filter="<?php echo esc_attr( $y ); ?>"><?php echo esc_html( $y ); ?></button><?php endforeach; ?>
      </div>
    </div>
    <div class="archive-grid" id="archiveGrid">
      <?php
      // First 12 shown, the rest behind "Show all editions" - matches
      // the static archive's own cutoff (checked: it showed all of 2026
      // plus Dec/Nov/Oct/Sep 2025 before hiding the remainder), not tied
      // to a year boundary since that visible set spans two years.
      $visible = 12;
      $i = 0;
      while ( $all_issues->have_posts() ) : $all_issues->the_post(); $f = ucan_newsletter_display_fields( get_post() ); $i++;
      ?>
      <a class="issue<?php echo $i > $visible ? ' hidden' : ''; ?>" data-year="<?php echo esc_attr( $f['year'] ); ?>" href="<?php the_permalink(); ?>"><div class="issue-content"><span class="issue-year"><?php echo esc_html( $f['year'] ); ?></span><span class="issue-month"><?php echo esc_html( $f['archive_label'] ); ?></span></div><span class="issue-arrow"><svg viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 6h8m0 0L6.5 2.5M10 6L6.5 9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></span></a>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <div class="load-more" id="loadMore">
      <button id="loadBtn">
        Show all editions
        <svg viewBox="0 0 10 10" fill="none"><path d="M5 1v8m0 0L1.5 5.5M5 9l3.5-3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
      </button>
    </div>
  </div>
</section>



<section class="sec alt" id="subscribe-wrap" aria-label="Subscribe">

  <div class="wrap" style="max-width:640px">

    <div class="subscribe-box" id="subscribe">

        <h2>Subscribe</h2>

        <p>Urban governance insights, delivered monthly. No spam.</p>

        <form action="https://urban.org.in/newsletter/" method="post" aria-label="Newsletter signup" novalidate="">

          <label>

            <span style="position:absolute;left:-9999px">Email address</span>

            <div class="sub-field">

              <input type="email" name="email" placeholder="your@work-email.in" required="" autocomplete="email">

              <button type="submit">Subscribe<svg viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2 6h8m0 0L6.5 2.5M10 6L6.5 9.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>

            </div>

          </label>

          <div class="consent" style="display:flex;gap:10px;align-items:flex-start;margin-top:14px">

            <input type="checkbox" id="nl-consent" name="consent" required="" style="margin-top:4px;flex:0 0 auto">

            <label for="nl-consent" style="font-size:12.5px;line-height:1.55">I agree that U-CAN may use my email address to send its newsletter, event invitations and programme details. My email will be kept until I unsubscribe or ask for it to be deleted. I can withdraw this consent at any time by writing to <a href="mailto:privacy@urban.org.in">privacy@urban.org.in</a>.</label>

          </div>

          <p class="nl-err" id="nl-err" role="alert" style="display:none">Please enter a valid email address and tick the consent box to continue.</p>

          <p class="nl-ok" id="nl-ok" role="status" style="display:none">Thanks — you're on the list. We've recorded your consent and the time it was given.</p>

          <p style="margin-top:12px;font-size:12.5px;line-height:1.6;color:rgba(251,250,246,.72);text-transform:none;letter-spacing:normal;font-weight:400">U-CAN is the Data Fiduciary for this data and processes it under the Digital Personal Data Protection Act, 2023. We do not sell your data or share it for advertising. You may request access, correction, erasure or nomination, or raise a grievance, at <a href="mailto:privacy@urban.org.in">privacy@urban.org.in</a>. See our <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a>.</p>

          <div class="sub-hint">Fellowship stories · policy briefs · city reform updates · unsubscribe anytime</div>

        </form>

      </div>

  </div>

</section>




<?php
get_footer();
