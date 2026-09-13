<?php
/**
 * Template Name: Learning Network
 * Auto-applies to a WP Page whose slug is "learning-network-for-urban-managers" (file-name
 * convention - page-learning-network-for-urban-managers.php). Content lifted verbatim from
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

.hero-lede b { color: rgb(255, 255, 255); font-weight: 600; }

.hero-meta { margin-top: 26px; display: flex; flex-wrap: wrap; gap: 12px; }

.hero-chip { display: inline-flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 600; letter-spacing: 0.02em; color: rgb(228, 239, 235); background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.18); padding: 8px 15px; border-radius: 100px; }

.hero-chip::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: var(--lime); }

.lead-split { display: grid; grid-template-columns: 1fr 1fr; gap: clamp(32px, 5vw, 72px); align-items: start; }

.lead-split-left .lead-hero { font-family: var(--display); font-weight: 500; font-size: clamp(21px, 2.4vw, 30px); line-height: 1.38; letter-spacing: -0.015em; color: var(--ink); }

.lead-hero .em { color: var(--teal-text); }

.lead-split-left .lead-sub { margin-top: clamp(20px, 2.8vw, 30px); font-size: clamp(15.5px, 1.4vw, 16.5px); line-height: 1.7; color: var(--ink-soft); padding-top: 22px; border-top: 2px solid var(--line); }

.lead-right { display: flex; flex-direction: column; gap: 0px; border: 1px solid var(--line); }

.lr-row { display: flex; align-items: center; gap: 20px; padding: clamp(20px, 2.4vw, 26px) clamp(20px, 2.4vw, 28px); border-bottom: 1px solid var(--line); transition: background .25s var(--e); }

.lr-row:last-child { border-bottom: 0px; }

.lr-row:hover { background: var(--paper-alt); }

.lr-icon { flex: 0 0 44px; width: 44px; height: 44px; border-radius: 50%; background: var(--paper-alt); border: 1px solid var(--line); display: flex; align-items: center; justify-content: center; }

.lr-icon svg { width: 22px; height: 22px; fill: none; stroke: var(--teal); stroke-width: 1.7; stroke-linecap: round; stroke-linejoin: round; }

.lr-text b { display: block; font-family: var(--display); font-weight: 800; font-size: clamp(22px, 2.2vw, 28px); color: var(--teal-deep); line-height: 1; letter-spacing: -0.02em; }

.lr-text span { display: block; margin-top: 5px; font-size: 13px; font-weight: 600; letter-spacing: 0.02em; color: var(--ink-soft); }

@media (max-width: 900px) {
  .lead-split { grid-template-columns: 1fr; gap: 32px; }
  .lead-right { max-width: 560px; }
}

.shape { display: grid; grid-template-columns: 1.15fr 0.85fr; gap: clamp(32px, 5vw, 64px); align-items: start; }

.shape-text p { font-size: clamp(16px, 1.5vw, 17.5px); color: var(--ink-soft); line-height: 1.68; }

.shape-text p + p { margin-top: 18px; }

.shape-text .after { margin-top: 22px; }

.shape-card { background: var(--teal-deep); color: rgb(255, 255, 255); padding: clamp(28px, 3.4vw, 38px); position: relative; overflow: hidden; }

.shape-card::after { content: ""; position: absolute; right: -60px; bottom: -60px; width: 190px; height: 190px; border-radius: 50%; background: radial-gradient(circle, rgba(205, 222, 113, 0.18), transparent 65%); }

.shape-card h3 { font-family: var(--sans); font-weight: 700; font-size: 12.5px; letter-spacing: 0.12em; text-transform: uppercase; color: var(--lime); margin-bottom: 20px; }

.shape-card ol { counter-reset: sc 0; position: relative; z-index: 1; list-style: none; margin: 0px; padding: 0px; }

.shape-card li { list-style: none; counter-increment: sc 1; position: relative; padding: 16px 0px 16px 44px; border-top: 1px solid rgba(255, 255, 255, 0.14); font-size: 15.5px; line-height: 1.5; color: rgb(234, 243, 240); }

.shape-card li:first-child { border-top: 0px; }

.shape-card li::before { content: counter(sc, decimal-leading-zero); position: absolute; left: 0px; top: 15px; font-family: var(--display); font-weight: 800; font-size: 16px; color: var(--lime); }

.ws-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }

.ws { position: relative; background: var(--paper); border: 1px solid var(--line); padding: clamp(26px, 2.8vw, 32px); display: flex; flex-direction: column; overflow: hidden; transition: transform .32s var(--e),box-shadow .32s var(--e),border-color .32s var(--e); }

.sec.alt .ws { background: rgb(255, 255, 255); }

.ws::before { content: ""; position: absolute; left: 0px; top: 0px; right: 0px; height: 3px; background: var(--teal); transform: scaleX(0); transform-origin: left center; transition: transform .4s var(--e); }

.ws:hover, .ws:focus-within { transform: translateY(-6px); box-shadow: var(--shadow); border-color: var(--teal-light); }

.ws:hover::before, .ws:focus-within::before { transform: scaleX(1); }

.ws-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 18px; }

.ws-num { font-family: var(--display); font-weight: 800; font-size: clamp(28px, 3vw, 38px); line-height: 0.8; color: var(--line); letter-spacing: -0.03em; transition: color .32s var(--e); }

.ws:hover .ws-num, .ws:focus-within .ws-num { color: var(--teal-light); }

.ws-tags { display: flex; flex-direction: column; gap: 6px; align-items: flex-end; text-align: right; }

.ws-region { font-family: var(--sans); font-weight: 700; font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase; color: var(--teal-deep); background: var(--lime); padding: 4px 11px; border-radius: 100px; }

.ws-stat { font-size: 12px; font-weight: 600; color: var(--ink-soft); }

.ws h3 { font-size: 18.5px; line-height: 1.28; letter-spacing: -0.01em; margin-bottom: 14px; color: var(--ink); }

.ws-body p { font-size: 14.5px; color: var(--ink-soft); line-height: 1.62; }

.ws-body p + p { margin-top: 12px; }

.link-inline { display: inline-flex; align-items: center; gap: 9px; margin-top: clamp(30px, 4vw, 42px); font-family: var(--display); font-weight: 700; font-size: 15px; color: var(--teal-deep); text-decoration: none; padding-bottom: 4px; border-bottom: 2px solid var(--lime); transition: gap .22s var(--e),border-color .22s var(--e); }

.link-inline:hover { gap: 14px; border-color: var(--teal); }

.revealed { list-style: none; padding: 0px; margin: 0px; display: flex; flex-direction: column; gap: 18px; max-width: 900px; }

.revealed li { display: grid; grid-template-columns: auto 1fr; gap: 20px; align-items: start; padding: clamp(20px, 2.4vw, 26px); background: var(--paper); border: 1px solid var(--line); transition: transform .3s var(--e),box-shadow .3s var(--e),border-color .3s var(--e); }

.sec.alt .revealed li { background: rgb(255, 255, 255); }

.revealed li:hover { transform: translateX(4px); box-shadow: var(--shadow); border-color: var(--teal-light); }

.rl-num { font-family: var(--display); font-weight: 800; font-size: clamp(26px, 3vw, 36px); line-height: 0.9; color: var(--teal-light); letter-spacing: -0.02em; flex: 0 0 auto; }

.revealed li p { font-size: clamp(15px, 1.5vw, 16.5px); color: var(--ink); line-height: 1.62; }

.platform { display: grid; grid-template-columns: 0.85fr 1.15fr; gap: clamp(32px, 5vw, 64px); align-items: start; }

.platform-lead { font-family: var(--display); font-weight: 600; font-size: clamp(19px, 2.2vw, 26px); line-height: 1.36; letter-spacing: -0.015em; color: var(--ink); }

.platform-list { list-style: none; padding: 0px; margin: 0px; display: flex; flex-direction: column; }

.platform-list li { position: relative; padding: 18px 0px 18px 40px; border-top: 1px solid var(--line); font-size: clamp(16px, 1.5vw, 17.5px); color: var(--ink); line-height: 1.5; }

.platform-list li:first-child { border-top: 0px; }

.platform-list li::before { content: ""; position: absolute; left: 0px; top: 24px; width: 20px; height: 20px; border-radius: 50%; background: var(--paper-alt); border: 1.5px solid var(--teal); }

.platform-list li::after { content: ""; position: absolute; left: 7px; top: 31px; width: 6px; height: 6px; border-radius: 50%; background: var(--teal); }

.why-band { background: var(--teal-deep); color: rgb(255, 255, 255); }

.why-inner { padding: clamp(52px, 6.5vw, 88px) 0px; display: grid; grid-template-columns: 1.05fr 0.95fr; gap: clamp(36px, 6vw, 80px); align-items: center; }

.why-copy .kicker.on-dark { margin-bottom: 22px; }

.why-copy .why-statement { font-family: var(--display); font-weight: 500; font-size: clamp(20px, 2.4vw, 29px); line-height: 1.42; letter-spacing: -0.015em; color: rgb(255, 255, 255); max-width: 26ch; }

.why-copy .why-links { margin-top: 32px; display: flex; flex-wrap: wrap; gap: 14px; }

.why-viz { position: relative; min-height: 280px; }

.why-viz .motif { position: absolute; inset: 0px; width: 100%; height: 100%; overflow: visible; opacity: 0.55; }

.why-stats { position: relative; z-index: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

.why-stat { background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.16); border-radius: 2px; padding: 22px; backdrop-filter: blur(2px); transition: transform .3s var(--e),background .3s var(--e),border-color .3s var(--e); }

.why-stat:hover { transform: translateY(-4px); background: rgba(255, 255, 255, 0.1); border-color: rgba(205, 222, 113, 0.5); }

.why-stat b { display: block; font-family: var(--display); font-weight: 800; font-size: clamp(28px, 3.4vw, 42px); line-height: 1; color: var(--lime); letter-spacing: -0.02em; font-variant-numeric: tabular-nums; }

.why-stat span { display: block; margin-top: 9px; font-size: 12.5px; font-weight: 600; letter-spacing: 0.03em; color: rgb(207, 224, 218); line-height: 1.4; }

@media (max-width: 960px) {
  .why-inner { grid-template-columns: 1fr; gap: 40px; }
  .why-viz { min-height: 0px; }
}

@media (max-width: 440px) {
  .why-stats { grid-template-columns: 1fr 1fr; gap: 12px; }
  .why-stat { padding: 18px 16px; }
}

@media (max-width: 960px) {
  .shape, .platform { grid-template-columns: 1fr; }
  .ws-grid { grid-template-columns: 1fr; }
}

@media (max-width: 560px) {
  .revealed li { grid-template-columns: 1fr; gap: 8px; }
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
