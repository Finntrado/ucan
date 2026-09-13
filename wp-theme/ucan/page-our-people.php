<?php
/**
 * Template Name: Our People
 * Auto-applies to a WP Page whose slug is "our-people" (file-name
 * convention - page-our-people.php).
 *
 * Phase 2 built this from standalone/our-people.html's <main> verbatim;
 * phase 3 (CLAUDE.md §28) replaced the static 28-card PEOPLE section with
 * a live ucan_member CPT loop, grouped by the ucan_member_group taxonomy -
 * see functions.php's ucan_register_member_cpt()/ucan_member_display_fields().
 * The hero above it is still the phase-2 verbatim block; only the card
 * markup itself is now generated. The page's own JSON-LD (already carrying
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

$ucan_page_css = '.sec.alt .sec-head h2 { font-size: clamp(27px, 3.6vw, 40px); letter-spacing: -0.02em; max-width: 22ch; }

.sec-head .lead { margin-top: 16px; font-size: 17.5px; color: var(--ink-soft); max-width: 62ch; }

.rv { opacity: 0; transform: translateY(20px); transition: opacity .65s var(--e),transform .65s var(--e); }

.rv.in { opacity: 1; transform: none; }

.rv.d1 { transition-delay: 0.08s; }

.rv.d2 { transition-delay: 0.16s; }

.rv.d3 { transition-delay: 0.24s; }

.vm { display: grid; grid-template-columns: 1fr 1fr; gap: clamp(24px, 3vw, 32px); }

.vm-card { border: 1px solid var(--line); background: var(--paper); padding: clamp(30px, 3.4vw, 46px); position: relative; overflow: hidden; }

.sec.alt .vm-card { background: rgb(255, 255, 255); }

.vm-card.dark { background: var(--teal-deep); color: rgb(255, 255, 255); border-color: var(--teal-deep); }

.vm-card.dark::after { content: ""; position: absolute; right: -70px; bottom: -70px; width: 200px; height: 200px; border-radius: 50%; background: radial-gradient(circle, rgba(205, 222, 113, 0.18), transparent 65%); }

.vm-eyebrow { font-family: var(--sans); font-weight: 700; font-size: 12.5px; letter-spacing: 0.14em; text-transform: uppercase; color: var(--teal-text); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }

.vm-card.dark .vm-eyebrow { color: var(--lime); }

.vm-eyebrow i { width: 34px; height: 34px; flex: 0 0 34px; border-radius: 50%; background: var(--paper-alt); display: flex; align-items: center; justify-content: center; }

.vm-card.dark .vm-eyebrow i { background: rgba(205, 222, 113, 0.14); }

.vm-eyebrow svg { width: 19px; height: 19px; fill: none; stroke: var(--teal-deep); stroke-width: 1.7; stroke-linecap: round; stroke-linejoin: round; }

.vm-card.dark .vm-eyebrow svg { stroke: var(--lime); }

.vm-text { font-family: var(--display); font-weight: 500; font-size: clamp(19px, 2vw, 23px); line-height: 1.42; letter-spacing: -0.01em; position: relative; z-index: 1; }

.vm-card p.body { font-size: 16px; color: var(--ink-soft); line-height: 1.62; }

.vm-card.dark p.body { color: rgb(212, 226, 221); }

.vm-card p.body + p.body { margin-top: 15px; }

.brochure { margin-top: 26px; display: inline-flex; align-items: center; gap: 11px; font-family: var(--display); font-weight: 700; font-size: 15px; color: var(--lime); text-decoration: none; padding-bottom: 4px; border-bottom: 2px solid rgba(205, 222, 113, 0.4); transition: border-color .22s var(--e),gap .22s var(--e); position: relative; z-index: 1; }

.brochure:hover { border-color: var(--lime); gap: 15px; }

.pr-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 20px; }

.pr { grid-column: span 2; border: 1px solid var(--line); background: var(--paper); padding: clamp(26px, 2.8vw, 34px); position: relative; overflow: hidden; transition: transform .32s var(--e),box-shadow .32s var(--e),border-color .32s var(--e); }

.pr::before { content: ""; position: absolute; left: 0px; top: 0px; bottom: 0px; width: 3px; background: var(--teal); transform: scaleY(0); transform-origin: center top; transition: transform .4s var(--e); }

.pr:hover, .pr:focus-within { transform: translateY(-6px); box-shadow: var(--shadow); border-color: var(--teal-light); }

.pr:hover::before, .pr:focus-within::before { transform: scaleY(1); }

.pr:nth-child(4) { grid-column: 2 / span 2; }

.pr:nth-child(5) { grid-column: 4 / span 2; }

.pr-num { font-family: var(--display); font-weight: 800; font-size: 34px; line-height: 1; color: var(--line); letter-spacing: -0.03em; display: block; margin-bottom: 16px; transition: color .32s var(--e); }

.pr:hover .pr-num, .pr:focus-within .pr-num { color: var(--teal-light); }

.pr h3 { font-size: 18px; line-height: 1.3; margin-bottom: 11px; color: var(--ink); }

.pr p { font-size: 14.5px; color: var(--ink-soft); }

.tl { position: relative; list-style: none; padding: 0px; margin: 0px; }

.tl::before { content: ""; position: absolute; left: 50%; top: 6px; bottom: 6px; width: 2px; background: var(--line); transform: translateX(-50%); }

.tl-year { text-align: center; margin: 6px 0px 30px; position: relative; z-index: 2; }

.tl-year span { display: inline-block; font-family: var(--display); font-weight: 800; font-size: 14px; letter-spacing: 0.06em; color: var(--paper); background: var(--teal-deep); padding: 7px 18px; border-radius: 100px; }

.tl-item { position: relative; width: calc(50% - 38px); margin-bottom: 26px; }

.tl-item.l { margin-right: auto; text-align: right; }

.tl-item.r { margin-left: auto; text-align: left; }

.tl-marker { position: absolute; top: 24px; width: 15px; height: 15px; border-radius: 50%; background: var(--paper); border: 3px solid var(--teal); z-index: 2; transition: transform .3s var(--e),background .3s var(--e),border-color .3s var(--e); }

.tl-item.l .tl-marker { right: -45px; }

.tl-item.r .tl-marker { left: -45px; }

.tl-card { border: 1px solid var(--line); background: var(--paper); padding: 22px 24px; transition: transform .3s var(--e),box-shadow .3s var(--e),border-color .3s var(--e); position: relative; }

.sec.alt .tl-card { background: rgb(255, 255, 255); }

.tl-item:hover .tl-card { transform: translateY(-4px); box-shadow: var(--shadow); border-color: var(--teal-light); }

.tl-item:hover .tl-marker { transform: scale(1.25); background: var(--teal); border-color: var(--teal); }

.tl-date { font-family: var(--display); font-weight: 700; font-size: 13px; letter-spacing: 0.04em; color: var(--teal-text); margin-bottom: 9px; text-transform: uppercase; }

.tl-card h3 { font-size: 17px; line-height: 1.32; margin-bottom: 9px; color: var(--ink); }

.tl-body { font-size: 14px; color: var(--ink-soft); line-height: 1.56; }

.tl-item.is-latest .tl-marker { background: var(--orange); border-color: var(--orange); box-shadow: rgba(254, 174, 0, 0.18) 0px 0px 0px 5px; }

.tl-item.is-latest .tl-card { border-top-color: ; border-right-color: ; border-bottom-color: ; border-left: 3px solid var(--teal-deep); }

.tl-flag { display: inline-flex; align-items: center; gap: 6px; font-family: var(--sans); font-weight: 700; font-size: 10.5px; letter-spacing: 0.1em; text-transform: uppercase; color: var(--teal-deep); background: var(--lime); padding: 3px 10px; border-radius: 100px; margin-bottom: 10px; }

.ctaband { background: var(--paper-alt); }

.ctaband .wrap { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: center; padding: clamp(40px,5vw,60px) var(--gutter); }

.ctaband p { font-family: var(--display); font-weight: 600; font-size: clamp(18px, 2vw, 22px); color: var(--ink); margin-right: 8px; }

.news { background: var(--ink); color: rgb(255, 255, 255); }

.news-in { display: grid; grid-template-columns: 1fr 1fr; gap: clamp(32px, 5vw, 72px); align-items: start; padding: clamp(60px, 7vw, 92px) 0px; }

.news h2 { color: rgb(255, 255, 255); font-size: clamp(26px, 3.2vw, 38px); max-width: 16ch; letter-spacing: -0.02em; }

.news .sub { margin-top: 18px; font-size: 16px; color: rgb(185, 196, 191); max-width: 44ch; }

.nform { max-width: 520px; }

.nrow { display: flex; gap: 10px; }

.nrow input { flex: 1 1 0%; padding: 16px 18px; border: 1.5px solid rgba(255, 255, 255, 0.28); background: rgba(255, 255, 255, 0.06); color: rgb(255, 255, 255); font-family: var(--sans); font-size: 15px; border-radius: 2px; transition: border-color 0.2s, background 0.2s; }

.nrow input::placeholder { color: rgb(159, 180, 174); }

.nrow input:focus { outline: none; border-color: var(--lime); background: rgba(255, 255, 255, 0.1); }

.nrow button { padding: 16px 28px; background: var(--lime); border: 1.5px solid var(--lime); color: var(--ink); font-family: var(--sans); font-weight: 700; font-size: 15px; border-radius: 2px; cursor: pointer; transition: transform .2s var(--e),filter .2s var(--e); }

.nrow button:hover { transform: translateY(-2px); filter: brightness(1.06); }

.consent { display: flex; gap: 11px; align-items: flex-start; margin-top: 18px; }

.consent input { appearance: none; flex: 0 0 18px; width: 18px; height: 18px; margin-top: 2px; border: 1.5px solid rgba(255, 255, 255, 0.5); border-radius: 2px; background: rgba(255, 255, 255, 0.06); cursor: pointer; display: grid; place-content: center; transition: background 0.18s, border-color 0.18s; }

.consent input::before { content: ""; width: 10px; height: 10px; transform: scale(0); transition: transform .15s var(--e); box-shadow: inset 1em 1em var(--teal-deep); clip-path: polygon(14% 44%, 0px 65%, 50% 100%, 100% 16%, 80% 0px, 43% 62%); }

.consent input:checked { background: var(--lime); border-color: var(--lime); }

.consent input:checked::before { transform: scale(1); }

.consent label { font-size: 13px; line-height: 1.55; color: rgb(199, 210, 205); cursor: pointer; }

.consent a { color: var(--lime); text-underline-offset: 2px; }

.nlegal { margin-top: 14px; font-size: 12px; line-height: 1.55; color: rgb(147, 164, 158); }

.nlegal a { color: var(--lime); }

.nerr { display: none; margin-top: 12px; font-size: 13px; font-weight: 600; color: var(--orange); }

.nok { display: none; margin-top: 14px; font-size: 14px; font-weight: 600; color: var(--lime); }

footer { background: var(--ink); color: rgb(185, 196, 191); padding: clamp(52px, 6vw, 72px) 0px 26px; border-top: 1px solid rgba(255, 255, 255, 0.08); }

.f-grid { display: grid; grid-template-columns: 1.6fr 1fr 1fr; gap: clamp(28px, 4vw, 52px); padding-bottom: 42px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }

footer .brand b { color: rgb(255, 255, 255); }

footer .brand small { color: rgb(140, 160, 152); }

footer .blurb { max-width: 300px; margin-top: 20px; font-size: 14.5px; color: rgb(140, 160, 152); }

footer h4 { font-family: var(--sans); font-weight: 700; font-size: 12.5px; letter-spacing: 0.1em; text-transform: uppercase; color: rgb(255, 255, 255); margin-bottom: 18px; }

footer li { margin-bottom: 11px; }

footer a { text-decoration: none; font-size: 14.5px; color: rgb(185, 196, 191); transition: color .18s var(--e); }

footer a:hover { color: var(--lime); }

.f-bottom { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding-top: 24px; font-size: 13px; color: rgb(124, 139, 133); }

.f-bottom a { color: rgb(124, 139, 133); margin-left: 18px; font-size: 13px; }

.cookie-link { background: none; border: 0px; padding: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-size-adjust: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-language-override: inherit; font-size: 13px; color: rgb(124, 139, 133); cursor: pointer; margin-left: 18px; }

.cookie-link:hover { color: var(--lime); }

.cc { position: fixed; left: 0px; right: 0px; bottom: 0px; z-index: 120; background: var(--paper); border-top: 3px solid var(--teal-deep); box-shadow: rgba(14, 83, 72, 0.5) 0px -8px 40px -18px; transform: translateY(110%); transition: transform .5s var(--e); }

.cc.show { transform: none; }

.cc-in { max-width: var(--wrap); margin: 0px auto; padding: 22px var(--gutter); display: grid; grid-template-columns: 1fr auto; gap: 26px; align-items: center; }

.cc-badge { display: inline-flex; align-items: center; gap: 7px; font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--teal-text); margin-bottom: 9px; }

.cc-badge::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: var(--teal); }

.cc h2 { font-size: 17px; margin-bottom: 7px; }

.cc p { font-size: 13.5px; line-height: 1.6; color: var(--ink-soft); max-width: 76ch; }

.cc p a { color: var(--teal-text); font-weight: 600; }

.cc-act { display: flex; gap: 10px; flex-wrap: wrap; }

.cc-btn { padding: 12px 20px; font-family: var(--sans); font-size: 14px; font-weight: 700; border-radius: 2px; cursor: pointer; border: 1.5px solid var(--teal-deep); background: var(--teal-deep); color: var(--paper); white-space: nowrap; transition: transform .2s var(--e); }

.cc-btn:hover { transform: translateY(-2px); }

.cc-btn.alt { background: transparent; color: var(--teal-deep); border-color: var(--line); }

.cc-btn.alt:hover { border-color: var(--teal); }

@media (max-width: 1000px) {
  .vm { grid-template-columns: 1fr; }
  .pr-grid { grid-template-columns: repeat(2, 1fr); }
  .pr, .pr:nth-child(4), .pr:nth-child(5) { grid-column: auto; }
  .news-in { grid-template-columns: 1fr; gap: 32px; }
}

@media (max-width: 860px) {
  .nav, .bar-cta { display: none; }
  .burger { display: flex; }
  .sec-head { grid-template-columns: 1fr; gap: 12px; align-items: start; }
  .tl::before { left: 7px; transform: none; }
  .tl-year { text-align: left; padding-left: 0px; }
  .tl-year span { margin-left: -4px; }
  .tl-item, .tl-item.l, .tl-item.r { width: 100%; margin: 0px 0px 20px; text-align: left; padding-left: 36px; }
  .tl-item.l .tl-marker, .tl-item.r .tl-marker { left: 0px; right: auto; }
  .f-grid { grid-template-columns: 1fr; }
  .cc-in { grid-template-columns: 1fr; gap: 16px; }
}

@media (max-width: 560px) {
  body { font-size: 16px; }
  .pr-grid { grid-template-columns: 1fr; }
  .nrow { flex-direction: column; }
  .nrow button { width: 100%; }
  .cc-act { flex-direction: column; }
  .cc-btn { width: 100%; }
  .ctaband .wrap { flex-direction: column; align-items: stretch; text-align: center; }
  .ctaband .btn { justify-content: center; }
}

@media (prefers-reduced-motion: reduce) {
  html { scroll-behavior: auto; }
  *, ::before, ::after { animation: auto ease 0s 1 normal none running none !important; transition: none !important; }
  .rv { opacity: 1; transform: none; }
}

@media print {
  .bar, .news, .cc, .ctaband { display: none; }
  body { font-size: 12pt; }
  .hero { background: none; color: rgb(0, 0, 0); }
  .hero::before, .hero::after { display: none; }
}

.grp { margin-bottom: clamp(48px, 6vw, 80px); }

.grp:last-of-type { margin-bottom: 0px; }

.grp-head { display: flex; align-items: baseline; gap: 16px; flex-wrap: wrap; margin-bottom: clamp(24px, 3vw, 34px); padding-bottom: 16px; border-bottom: 1px solid var(--line); }

.grp-head h2 { font-size: clamp(22px, 2.8vw, 30px); letter-spacing: -0.02em; }

.grp-count { font-family: var(--display); font-weight: 700; font-size: 13px; color: var(--teal-text); background: var(--paper-alt); padding: 5px 13px; border-radius: 100px; }

.grp-desc { font-size: 15.5px; color: var(--ink-soft); max-width: 70ch; margin-top: -6px; margin-bottom: 26px; }

.people { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }

.people.team { grid-template-columns: repeat(4, 1fr); }

.pcard { position: relative; }

.pcard a { display: flex; align-items: center; gap: 15px; padding: 18px; border: 1px solid var(--line); background: var(--paper); text-decoration: none; height: 100%; transition: transform .3s var(--e),box-shadow .3s var(--e),border-color .3s var(--e); }

.pcard a:hover { transform: translateY(-4px); box-shadow: var(--shadow); border-color: var(--teal-light); }

.p-ava { flex: 0 0 50px; width: 50px; height: 50px; border-radius: 50%; object-fit: cover; object-position: center 22%; display: flex; align-items: center; justify-content: center; background: var(--paper-alt); transition: transform .3s var(--e); box-shadow: 0 0 0 1px var(--line); }

.p-mono { font-family: var(--display); font-weight: 700; font-size: 16px; letter-spacing: 0.01em; color: rgb(255, 255, 255); background: var(--ava); box-shadow: none; }

.pcard a:hover .p-ava { transform: scale(1.06); }

.p-info { display: flex; flex-direction: column; gap: 3px; min-width: 0px; flex: 1 1 0%; }

.p-name { font-family: var(--display); font-weight: 700; font-size: 15.5px; color: var(--ink); line-height: 1.22; letter-spacing: -0.01em; }

.p-role { font-size: 12.5px; color: var(--ink-soft); line-height: 1.35; }

.p-role.team { color: var(--teal-text); font-weight: 600; }

.p-go { position: absolute; top: 12px; right: 13px; font-size: 13px; color: var(--teal); opacity: 0; transform: translate(-3px, 3px); transition: opacity .25s var(--e),transform .25s var(--e); }

.pcard a:hover .p-go, .pcard a:focus-visible .p-go { opacity: 1; transform: none; }

.note { margin-top: clamp(40px, 5vw, 56px); font-size: 13.5px; color: var(--ink-soft); text-align: center; font-style: italic; }

@media (max-width: 1000px) {
  .people, .people.team { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 760px) {
  .people, .people.team { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 480px) {
  .people, .people.team { grid-template-columns: 1fr; }
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
<?php
/**
 * Phase 3: dynamic replacement for the static 28-card markup phase 2
 * extracted verbatim. One WP_Query per group, in the same fixed order the
 * static page used (UCAN_MEMBER_GROUP_ORDER, functions.php) - a person in
 * two groups shows up once per group they belong to, exactly reproducing
 * the original's "same person, two <li> cards" result, just driven by the
 * ucan_member_group taxonomy instead of duplicate HTML.
 *
 * Renders nothing per group until real ucan_member posts exist (the
 * phase-7 importer) - expected, see CLAUDE.md §28.
 */
$anchor_id = array(
	'founding-circle'    => 'founding',
	'steering-committee' => 'steering',
	'stewardship-team'   => 'stewardship',
	'our-team'           => 'team',
);
foreach ( UCAN_MEMBER_GROUP_ORDER as $group_slug => $group_name ) :
	$members = new WP_Query(
		array(
			'post_type'      => 'ucan_member',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'tax_query'      => array(
				array(
					'taxonomy' => 'ucan_member_group',
					'field'    => 'slug',
					'terms'    => $group_slug,
				),
			),
		)
	);
	if ( ! $members->have_posts() ) {
		continue;
	}
	?>
    <div class="grp" id="<?php echo esc_attr( $anchor_id[ $group_slug ] ); ?>">
      <div class="grp-head"><h2><?php echo esc_html( $group_name ); ?></h2></div>
      <ul class="people rv" aria-label="<?php echo esc_attr( $group_name ); ?>">
	<?php
	while ( $members->have_posts() ) :
		$members->the_post();
		$f = ucan_member_display_fields( get_post() );
		?>
        <li class="pcard rv in">
          <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() . ', ' . $f['role'] . ' — view profile' ); ?>">
	    <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'p-ava', 'width' => 50, 'height' => 50, 'loading' => 'lazy', 'decoding' => 'async', 'alt' => get_the_title() ) ); ?>
	    <?php else : ?>
            <img class="p-ava" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/monogram-placeholder.svg" width="50" height="50" loading="lazy" decoding="async" alt="<?php echo esc_attr( get_the_title() ); ?>">
	    <?php endif; ?>
            <span class="p-info">
              <span class="p-name"><?php the_title(); ?></span>
              <span class="p-role"><?php echo esc_html( $f['role'] ); ?></span>
            </span>
            <span class="p-go" aria-hidden="true">↗</span>
          </a>
        </li>
	<?php
	endwhile;
	wp_reset_postdata();
	?>
      </ul>
    </div>
<?php endforeach; ?>
  </div>
</section>
<!-- CTA PAIR -->





<!-- NEWSLETTER -->






<?php
get_footer();
