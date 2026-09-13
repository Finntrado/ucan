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

$ucan_page_css = '*, ::before, ::after { box-sizing: border-box; }

html { text-size-adjust: 100%; scroll-behavior: smooth; }

@media (prefers-reduced-motion: reduce) {
  html { scroll-behavior: auto; }
  *, ::before, ::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
}

:root { --paper: #FBFAF6; --paper-alt: #E9F5F2; --paper-3: #F4F0E6; --ink: #222120; --ink-soft: #57564F; --teal: #1F8F7B; --teal-text: #177A69; --teal-deep: #0E5348; --teal-light: #4EC6B2; --lime: #CDDE71; --lime-strong: #B8CC55; --brand-orange: #FEAE00; --line: #DCEAE6; --line-strong: #B4CFC8; --line-dark: rgba(251,250,246,.16); --sans: \'Public Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Arial,sans-serif; --display: \'Archivo\',\'Public Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,sans-serif; --fs-display: clamp(2.25rem,5.5vw,4rem); --fs-h1: clamp(1.875rem,4vw,3rem); --fs-h2: clamp(1.5rem,3vw,2.125rem); --fs-body: 1.0625rem; --w: 1180px; --gutter: clamp(1.25rem,4vw,2rem); --section: clamp(4rem,9vw,6.5rem); --radius: 2px; --ease: cubic-bezier(.4,0,.2,1); }

body { margin: 0px; background: var(--paper); color: var(--ink); font-family: var(--sans); font-size: var(--fs-body); line-height: 1.6; -webkit-font-smoothing: antialiased; text-rendering: optimizelegibility; }

img, svg { display: block; max-width: 100%; height: auto; }

a { color: inherit; text-decoration-thickness: 1px; text-underline-offset: 0.18em; }

button { font: inherit; cursor: pointer; background: none; border: 0px; color: inherit; }

:focus-visible { outline: 2px solid var(--teal-text); outline-offset: 3px; border-radius: 2px; }

.skip { position: absolute; left: -9999px; top: 0px; background: var(--teal-deep); color: var(--paper); padding: 0.75rem 1rem; z-index: 100; text-decoration: none; font-weight: 600; }

.skip:focus { left: 0.5rem; top: 0.5rem; }

.wrap { max-width: var(--w); margin: 0px auto; padding: 0 var(--gutter); }

.eyebrow { font-family: var(--sans); font-size: 13px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--teal-text); display: inline-flex; align-items: center; gap: 12px; }

.eyebrow::before { content: ""; width: 22px; height: 1px; background: var(--teal-text); }

.header { position: sticky; top: 0px; z-index: 50; background: rgba(251, 250, 246, 0.94); backdrop-filter: saturate(140%) blur(10px); border-bottom: 1px solid var(--line); }

.header-inner { display: flex; align-items: center; justify-content: space-between; padding: 0 var(--gutter); max-width: var(--w); margin: 0px auto; gap: 1.5rem; height: 76px; }

.logo { display: inline-flex; align-items: center; gap: 0.6rem; text-decoration: none; color: var(--ink); font-family: var(--display); font-weight: 700; letter-spacing: -0.01em; }

.logo-mark { width: 34px; height: 34px; background: var(--teal-deep); color: var(--paper); border-radius: 6px; display: grid; place-items: center; font-weight: 800; font-size: 1rem; line-height: 1; position: relative; flex-shrink: 0; }

.logo-mark::after { content: ""; position: absolute; top: -3px; right: -3px; width: 8px; height: 8px; background: var(--brand-orange); border-radius: 50%; }

.logo-word { font-size: 1.125rem; line-height: 1.1; }

.logo-word small { display: block; font-family: var(--sans); font-size: 8.5px; font-weight: 600; letter-spacing: 0.09em; color: var(--ink-soft); text-transform: uppercase; margin-top: 2px; }

.nav { display: none; gap: 2rem; align-items: center; font-size: 0.875rem; position: relative; }

.nav-item { position: relative; }

.nav-link { text-decoration: none; color: var(--ink-soft); letter-spacing: 0.02em; padding: 0.5rem 0px; display: inline-flex; align-items: center; gap: 0.35em; transition: color 0.15s; cursor: pointer; background: none; border: 0px; font: inherit; }

.nav-link:hover, .nav-link[aria-current="page"] { color: var(--teal-text); }

.nav-link[aria-current="page"] { border-bottom: 2px solid var(--lime); padding-bottom: 0.35rem; }

.nav-link svg { width: 9px; height: 9px; opacity: 0.6; transition: transform .18s var(--ease); }

.nav-item:hover .nav-link svg, .nav-item:focus-within .nav-link svg { transform: rotate(180deg); }

.dropdown { position: absolute; top: calc(100% + 8px); left: -14px; min-width: 220px; background: var(--paper); border: 1px solid var(--line); border-radius: var(--radius); padding: 0.5rem 0px; box-shadow: rgba(14, 83, 72, 0.16) 0px 12px 32px -12px; opacity: 0; visibility: hidden; transform: translateY(-4px); transition: opacity .18s var(--ease),transform .18s var(--ease),visibility .18s var(--ease); z-index: 60; }

.nav-item:hover .dropdown, .nav-item:focus-within .dropdown { opacity: 1; visibility: visible; transform: translateY(0px); }

.dropdown a { display: block; padding: 0.6rem 1.15rem; text-decoration: none; color: var(--ink-soft); font-size: 0.875rem; font-weight: 600; letter-spacing: 0.01em; border-left: 2px solid transparent; transition: background 0.12s, color 0.12s, border-color 0.12s; }

.dropdown a:hover { color: var(--teal-text); background: var(--paper-alt); border-left-color: var(--lime); }

.dropdown-sub { padding-left: 2rem; font-size: 0.8125rem; opacity: 0.85; }

.header-cta { display: flex; align-items: center; gap: 0.75rem; }

.btn { display: inline-flex; align-items: center; gap: 0.5em; padding: 14px 24px; font-family: var(--sans); font-size: 0.9375rem; font-weight: 600; border-radius: var(--radius); text-decoration: none; transition: background 0.18s, color 0.18s, border-color 0.18s, transform 0.18s; line-height: 1; white-space: nowrap; border: 1px solid transparent; cursor: pointer; }

.btn-primary { background: var(--teal-deep); color: var(--paper); border-color: var(--teal-deep); }

.btn-primary:hover { background: var(--ink); border-color: var(--ink); transform: translateY(-1px); }

.btn-ghost { color: var(--ink); border-color: var(--line-strong); }

.btn-ghost:hover { border-color: var(--teal-deep); color: var(--teal-deep); }

.btn-lime { background: var(--lime); color: var(--ink); border-color: var(--lime); }

.btn-lime:hover { background: var(--lime-strong); border-color: var(--lime-strong); }

.btn svg { width: 12px; height: 12px; flex-shrink: 0; transition: transform 0.18s; }

.btn:hover svg { transform: translateX(3px); }

.menu-btn { display: grid; place-items: center; width: 42px; height: 42px; border: 1px solid var(--line-strong); border-radius: var(--radius); color: var(--ink); }

.menu-btn svg { width: 18px; height: 18px; }

@media (min-width: 1024px) {
  .nav { display: flex; }
  .menu-btn { display: none; }
  .header-cta .btn-ghost { display: inline-flex; }
}

@media (max-width: 1023px) {
  .header-cta .btn-ghost { display: none; }
}

.sheet { position: fixed; inset: 0px; background: var(--paper); padding: 5.5rem var(--gutter) 2rem; z-index: 60; transform: translateY(-100%); transition: transform 0.3s; visibility: hidden; overflow-y: auto; }

.sheet.open { transform: translateY(0px); visibility: visible; }

.sheet-close { position: absolute; top: 1.15rem; right: var(--gutter); }

.sheet-group { border-bottom: 1px solid var(--line); padding: 1rem 0px; }

.sheet-group > a, .sheet-group > span { display: block; font-family: var(--display); font-size: 1.25rem; font-weight: 700; text-decoration: none; color: var(--ink); margin-bottom: 0.35rem; }

.sheet-group ul { list-style: none; padding: 0px; margin: 0.35rem 0px 0px; }

.sheet-group li a { display: block; padding: 0.4rem 0px 0.4rem 1rem; font-size: 0.9375rem; color: var(--ink-soft); text-decoration: none; font-weight: 500; border-left: 2px solid var(--line); }

.sheet-group li a:hover { color: var(--teal-text); border-left-color: var(--lime); }

.crumbs { padding: 1.5rem 0px 0px; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--ink-soft); }

.crumbs a { text-decoration: none; color: var(--ink-soft); }

.crumbs a:hover { color: var(--teal-text); }

.crumbs span[aria-current] { color: var(--teal-text); }

.crumbs .sep { margin: 0px 0.6em; opacity: 0.5; }

.nl-hero { background: var(--teal-deep); color: var(--paper); padding: clamp(3rem, 6vw, 5rem) 0px; position: relative; overflow: hidden; }

.nl-hero-bg { position: absolute; inset: 0px; pointer-events: none; opacity: 0.12; }

.nl-hero-bg svg { width: 100%; height: 100%; }

.nl-hero-grid { display: grid; grid-template-columns: 1fr; gap: 2.5rem; align-items: center; position: relative; z-index: 1; }

@media (min-width: 900px) {
  .nl-hero-grid { grid-template-columns: 1.3fr 1fr; gap: 3.5rem; }
}

.nl-hero .eyebrow { color: var(--lime); }

.nl-hero .eyebrow::before { background: var(--lime); }

.nl-hero h1 { font-family: var(--display); font-size: var(--fs-display); line-height: 1.05; letter-spacing: -0.028em; font-weight: 700; color: var(--paper); margin: 0.75rem 0px 1.25rem; max-width: 16ch; }

.nl-hero-lead { font-size: clamp(1.0625rem, 1.4vw, 1.1875rem); line-height: 1.55; color: rgba(251, 250, 246, 0.8); max-width: 48ch; margin: 0px; }

.subscribe-box { background: rgba(251, 250, 246, 0.06); border: 1px solid rgba(251, 250, 246, 0.14); border-radius: var(--radius); padding: clamp(1.5rem, 3vw, 2.25rem); }

.subscribe-box h2 { font-family: var(--display); font-size: 1.375rem; font-weight: 700; color: var(--paper); margin: 0px 0px 0.25rem; }

.subscribe-box p { font-size: 0.9375rem; color: rgba(251, 250, 246, 0.7); margin: 0px 0px 1.5rem; line-height: 1.5; }

.sub-field { display: flex; gap: 0.5rem; }

.sub-field input { flex: 1 1 0%; min-width: 0px; background: rgba(251, 250, 246, 0.08); border: 1px solid rgba(251, 250, 246, 0.25); border-radius: var(--radius); color: var(--paper); font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-size-adjust: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-language-override: inherit; font-size: 0.9375rem; padding: 0.85rem 1rem; outline: none; transition: border-color 0.18s, background 0.18s; }

.sub-field input:focus { border-color: var(--lime); background: rgba(251, 250, 246, 0.12); }

.sub-field input::placeholder { color: rgba(251, 250, 246, 0.45); }

.sub-field button { padding: 0.85rem 1.5rem; background: var(--lime); color: var(--ink); font-weight: 700; font-size: 0.9375rem; border: 0px; border-radius: var(--radius); display: inline-flex; align-items: center; gap: 0.5em; white-space: nowrap; transition: background 0.18s; }

.sub-field button:hover { background: var(--lime-strong); }

.sub-hint { font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: rgba(251, 250, 246, 0.5); margin-top: 0.85rem; }

.featured { padding: var(--section) 0; }

.featured-card { display: grid; grid-template-columns: 1fr; gap: 0px; background: var(--paper); border: 1px solid var(--line); border-radius: var(--radius); overflow: hidden; transition: box-shadow .28s var(--ease),transform .28s var(--ease); }

@media (min-width: 800px) {
  .featured-card { grid-template-columns: 420px 1fr; }
}

.featured-card:hover { box-shadow: rgba(14, 83, 72, 0.2) 0px 20px 56px -24px; transform: translateY(-2px); }

.featured-cover { background: var(--teal-deep); padding: 2.5rem; display: flex; flex-direction: column; justify-content: space-between; min-height: 320px; position: relative; overflow: hidden; }

.featured-cover-bg { position: absolute; inset: 0px; pointer-events: none; opacity: 0.18; }

.featured-cover-bg svg { width: 100%; height: 100%; }

.cover-badge { font-family: var(--sans); font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; background: var(--lime); color: var(--ink); padding: 6px 12px; border-radius: var(--radius); display: inline-block; width: fit-content; position: relative; z-index: 1; }

.cover-title { position: relative; z-index: 1; }

.cover-title .edition { font-family: var(--display); font-size: 0.875rem; font-weight: 600; color: var(--lime); letter-spacing: 0.05em; text-transform: uppercase; margin: 0px 0px 0.5rem; }

.cover-title .month { font-family: var(--display); font-size: clamp(2.5rem, 6vw, 4rem); font-weight: 800; line-height: 1; letter-spacing: -0.03em; color: var(--paper); margin: 0px; }

.cover-title .year { font-family: var(--display); font-size: 1.375rem; font-weight: 700; color: rgba(251, 250, 246, 0.6); margin: 0.25rem 0px 0px; }

.featured-body { padding: clamp(1.75rem, 3vw, 2.5rem); display: flex; flex-direction: column; justify-content: center; }

.featured-tag { font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--teal-text); margin: 0px 0px 1.25rem; display: inline-flex; align-items: center; gap: 0.5em; }

.featured-tag .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--lime); }

.featured-body h3 { font-family: var(--display); font-size: clamp(1.5rem, 2.5vw, 2rem); font-weight: 700; line-height: 1.15; letter-spacing: -0.015em; color: var(--ink); margin: 0px 0px 1rem; max-width: 24ch; }

.featured-body p { font-size: 1.0625rem; line-height: 1.65; color: var(--ink-soft); margin: 0px 0px 2rem; max-width: 48ch; }

.featured-body .btn { width: fit-content; }

.archive { padding: 0 0 var(--section); content-visibility: auto; contain-intrinsic-size: auto 700px; }

.archive-head { display: flex; flex-wrap: wrap; align-items: end; justify-content: space-between; gap: 1rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--line); }

.archive-head h2 { font-family: var(--display); font-size: var(--fs-h2); font-weight: 700; letter-spacing: -0.015em; color: var(--ink); margin: 0px; display: inline-block; padding-bottom: 0.5rem; border-bottom: 4px solid var(--lime); }

.archive-filter { display: flex; gap: 0.5rem; flex-wrap: wrap; }

.filter-btn { padding: 8px 16px; font-size: 0.8125rem; font-weight: 700; letter-spacing: 0.04em; border: 1px solid var(--line-strong); border-radius: var(--radius); color: var(--ink-soft); transition: 0.18s; }

.filter-btn:hover, .filter-btn.active { background: var(--teal-deep); color: var(--paper); border-color: var(--teal-deep); }

.archive-grid { display: grid; grid-template-columns: 1fr; gap: 0px; border-top: 1px solid var(--line); }

@media (min-width: 700px) {
  .archive-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (min-width: 1000px) {
  .archive-grid { grid-template-columns: repeat(4, 1fr); }
}

.issue { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1.25rem; border-bottom: 1px solid var(--line); text-decoration: none; color: var(--ink); transition: background 0.18s, padding-left 0.18s; position: relative; }

@media (min-width: 700px) {
  .issue { border-right: 1px solid var(--line); }
}

@media (min-width: 1000px) {
  .issue:nth-child(4n) { border-right: 0px; }
}

@media (min-width: 700px) and (max-width: 999px) {
  .issue:nth-child(2n) { border-right: 0px; }
}

.issue::before { content: ""; position: absolute; left: 0px; top: 0px; bottom: 0px; width: 0px; background: var(--lime); transition: width .22s var(--ease); }

.issue:hover { background: var(--paper-alt); padding-left: 1.65rem; }

.issue:hover::before { width: 4px; }

.issue-content { display: flex; flex-direction: column; gap: 2px; min-width: 0px; }

.issue-year { font-family: var(--display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; color: var(--ink-soft); }

.issue-month { font-family: var(--display); font-size: 1.0625rem; font-weight: 700; letter-spacing: -0.005em; color: var(--teal-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; transition: color 0.18s; }

.issue:hover .issue-month { color: var(--teal-deep); }

.issue-arrow { width: 28px; height: 28px; border: 1px solid var(--line-strong); border-radius: 50%; display: grid; place-items: center; flex-shrink: 0; transition: background 0.18s, border-color 0.18s, transform 0.18s; }

.issue-arrow svg { width: 10px; height: 10px; color: var(--ink-soft); transition: color 0.18s; }

.issue:hover .issue-arrow { background: var(--teal-deep); border-color: var(--teal-deep); transform: translateX(2px); }

.issue:hover .issue-arrow svg { color: var(--paper); }

.load-more { text-align: center; padding: 2.5rem 0px 0px; }

.load-more button { padding: 14px 32px; font-weight: 700; font-size: 0.9375rem; border: 1px solid var(--line-strong); border-radius: var(--radius); color: var(--ink); display: inline-flex; align-items: center; gap: 0.65em; transition: 0.18s; }

.load-more button:hover { background: var(--teal-deep); color: var(--paper); border-color: var(--teal-deep); }

.load-more button svg { width: 10px; height: 10px; transition: transform 0.18s; }

.load-more button:hover svg { transform: translateY(2px); }

.hidden { display: none; }

.explore { padding: var(--section) 0; background: var(--paper-alt); content-visibility: auto; contain-intrinsic-size: auto 300px; }

.explore h2 { font-family: var(--display); font-size: var(--fs-h2); font-weight: 700; letter-spacing: -0.015em; color: var(--ink); margin: 0px 0px 2rem; text-align: center; }

.explore-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }

@media (min-width: 700px) {
  .explore-grid { grid-template-columns: repeat(3, 1fr); }
}

.explore-link { display: flex; align-items: center; justify-content: space-between; padding: 1.15rem 1.25rem; background: var(--paper); border: 1px solid var(--line); border-radius: var(--radius); text-decoration: none; color: var(--teal-text); font-family: var(--display); font-weight: 700; font-size: 0.9375rem; transition: background 0.18s, border-color 0.18s, transform 0.18s, color 0.18s; }

.explore-link:hover { background: var(--teal-deep); color: var(--paper); border-color: var(--teal-deep); transform: translateY(-1px); }

.explore-link svg { width: 12px; height: 12px; flex-shrink: 0; transition: transform 0.18s; }

.explore-link:hover svg { transform: translateX(3px); }

.footer { padding: 3.5rem 0px 1.75rem; background: var(--ink); color: rgb(185, 196, 191); font-size: 0.9375rem; }

.footer-grid { display: grid; grid-template-columns: 1fr; gap: 2.5rem; padding-bottom: 2.5rem; border-bottom: 1px solid var(--line-dark); }

@media (min-width: 700px) {
  .footer-grid { grid-template-columns: 1.5fr repeat(3, 1fr); }
}

.footer h4 { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--paper); margin: 0px 0px 1rem; }

.footer ul { list-style: none; padding: 0px; margin: 0px; display: grid; gap: 0.65rem; }

.footer a { text-decoration: none; color: rgb(185, 196, 191); transition: color 0.15s; }

.footer a:hover { color: var(--lime); }

.footer-brand p { margin: 1rem 0px; line-height: 1.55; color: rgb(140, 160, 152); max-width: 32ch; }

.footer-brand .logo { color: var(--paper); }

.footer-brand .logo small { color: rgb(140, 160, 152); }

.footer-brand .logo-mark { background: var(--teal); }

.footer-legal { display: flex; flex-wrap: wrap; gap: 1.5rem; padding-top: 1.75rem; font-size: 0.8125rem; color: rgb(140, 160, 152); }

.footer-legal a { color: rgb(140, 160, 152); }

@media (min-width: 700px) {
  .footer-legal { justify-content: space-between; }
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
