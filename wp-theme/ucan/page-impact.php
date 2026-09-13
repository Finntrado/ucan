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

.imp-hero-sub { margin-top: 26px; display: flex; flex-wrap: wrap; gap: 26px; }

.imp-hero-sub .hs { display: flex; flex-direction: column; gap: 2px; padding-left: 16px; border-left: 2px solid rgba(205, 222, 113, 0.5); }

.imp-hero-sub .hs b { font-family: var(--display); font-weight: 800; font-size: clamp(22px, 2.6vw, 30px); color: var(--lime); line-height: 1; font-variant-numeric: tabular-nums; }

.imp-hero-sub .hs span { font-size: 12px; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; color: rgb(207, 224, 218); }

.impact-band { background: var(--teal-deep); color: var(--paper); }

.impact-figs { display: grid; grid-template-columns: repeat(4, 1fr); }

.fig { padding: clamp(40px, 5vw, 64px) clamp(20px, 2.4vw, 32px); text-align: center; border-right: 1px solid rgba(255, 255, 255, 0.14); position: relative; }

.fig:last-child { border-right: 0px; }

.fig-ico { width: 60px; height: 60px; margin: 0px auto 20px; border-radius: 50%; background: rgba(205, 222, 113, 0.12); border: 1px solid rgba(205, 222, 113, 0.3); display: flex; align-items: center; justify-content: center; transition: background .35s var(--e),transform .35s var(--e); }

.fig-ico svg { width: 32px; height: 32px; fill: var(--lime); stroke: var(--lime); stroke-linecap: round; stroke-linejoin: round; }

.fig-ico svg [fill="none"] { fill: none; }

.fig-ico svg [fill="var(--teal-deep)"] { fill: var(--teal-deep); stroke: none; }

.fig:hover .fig-ico { background: var(--lime); transform: translateY(-4px); }

.fig:hover .fig-ico svg { fill: var(--teal-deep); stroke: var(--teal-deep); }

.fig b { font-family: var(--display); font-weight: 800; font-size: clamp(30px, 4vw, 50px); color: var(--lime); line-height: 1; display: block; font-variant-numeric: tabular-nums; letter-spacing: -0.02em; }

.fig.pop b { animation: pop .5s var(--e); }

@keyframes pop { 
  0% { transform: scale(0.82); opacity: 0.4; }
  60% { transform: scale(1.05); }
  100% { transform: scale(1); opacity: 1; }
}

.fig .kick { display: block; font-size: 11px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--lime); opacity: 0.8; margin-bottom: 9px; }

.fig .cap { display: block; margin-top: 10px; font-size: 13px; color: rgb(207, 224, 218); max-width: 180px; margin-inline: auto; min-height: 34px; }

.proof-list { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }

.pp { position: relative; background: var(--paper); border: 1px solid var(--line); padding: clamp(28px, 3vw, 38px); display: flex; flex-direction: column; overflow: hidden; transition: transform .32s var(--e),box-shadow .32s var(--e),border-color .32s var(--e); }

.sec.alt .pp { background: rgb(255, 255, 255); }

.pp::before { content: ""; position: absolute; left: 0px; top: 0px; bottom: 0px; width: 4px; background: var(--acc); transform: scaleY(0); transform-origin: center top; transition: transform .4s var(--e); }

.pp::after { content: ""; position: absolute; inset: 0px; background: var(--wash); opacity: 0; transition: opacity .4s var(--e); z-index: -1; }

.pp:hover, .pp:focus-within { transform: translateY(-6px); box-shadow: var(--shadow); border-color: var(--acc); }

.pp:hover::before, .pp:focus-within::before { transform: scaleY(1); }

.pp:hover::after, .pp:focus-within::after { opacity: 1; }

.pp-top { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; }

.pp-idx { font-family: var(--display); font-weight: 800; font-size: clamp(30px, 3.4vw, 42px); line-height: 0.8; color: var(--acc); letter-spacing: -0.03em; flex: 0 0 auto; font-variant-numeric: tabular-nums; }

.pp-line { height: 2px; flex: 1 1 0%; background: linear-gradient(90deg,var(--acc),transparent); opacity: 0.4; border-radius: 2px; }

.pp-body { display: flex; flex-direction: column; flex: 1 1 0%; }

.pp-body h3 { font-size: clamp(18px, 1.9vw, 21px); line-height: 1.3; letter-spacing: -0.01em; margin-bottom: 12px; color: var(--ink); }

.pp-body > p { font-size: 15px; color: var(--ink-soft); line-height: 1.62; }

.proof-quote { margin: 20px 0px 0px; padding: 18px 22px; background: var(--wash); border-left: 3px solid var(--acc); border-radius: 0px 2px 2px 0px; }

.proof-quote blockquote { margin: 0px; font-family: var(--display); font-weight: 500; font-style: italic; font-size: 14.5px; line-height: 1.55; color: var(--ink); }

.proof-quote blockquote::before { content: "“"; color: var(--acc); font-size: 1.5em; line-height: 0; vertical-align: -0.3em; margin-right: 0.05em; }

.proof-quote figcaption { margin-top: 12px; display: flex; flex-direction: column; gap: 2px; }

.proof-quote .pq-name { font-family: var(--display); font-weight: 700; font-size: 13.5px; color: var(--teal-deep); }

.proof-quote .pq-role { font-size: 12px; color: var(--ink-soft); }

.voices { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }

.voice { margin: 0px; background: var(--paper); border: 1px solid var(--line); padding: clamp(26px, 3vw, 34px); display: flex; flex-direction: column; transition: transform .3s var(--e),box-shadow .3s var(--e),border-color .3s var(--e); }

.sec.alt .voice { background: rgb(255, 255, 255); }

.voice:hover { transform: translateY(-5px); box-shadow: var(--shadow); border-color: var(--teal-light); }

.voice blockquote { margin: 0px 0px 20px; font-family: var(--display); font-weight: 500; font-size: clamp(15.5px, 1.5vw, 17px); line-height: 1.55; color: var(--ink); letter-spacing: -0.005em; flex: 1 1 0%; }

.voice blockquote::before { content: "“"; display: block; font-family: var(--display); font-weight: 800; font-size: 44px; line-height: 0.4; color: var(--teal-light); margin-bottom: 12px; }

.voice figcaption { border-top: 1px solid var(--line); padding-top: 18px; display: flex; align-items: center; gap: 14px; }

.voice .v-photo { width: 56px; height: 56px; flex: 0 0 56px; border-radius: 50%; object-fit: cover; object-position: center 22%; box-shadow: 0 0 0 1px var(--line); }

.voice .v-photo[data-ref="placeholder"] { filter: saturate(0.5); }

.voice .v-meta { display: flex; flex-direction: column; gap: 3px; min-width: 0px; }

.voice .v-name { font-family: var(--display); font-weight: 700; font-size: 14.5px; color: var(--teal-deep); }

.voice .v-role { font-size: 12.5px; color: var(--ink-soft); line-height: 1.4; }

@media (max-width: 1000px) {
  .impact-figs { grid-template-columns: repeat(2, 1fr); }
  .fig:nth-child(2) { border-right: 0px; }
  .fig { border-bottom: 1px solid rgba(255, 255, 255, 0.14); }
  .fig:nth-last-child(-n+2) { border-bottom: 0px; }
  .voices { grid-template-columns: 1fr; }
}

@media (max-width: 820px) {
  .proof-list { grid-template-columns: 1fr; }
}

@media (max-width: 560px) {
  .impact-figs { grid-template-columns: 1fr; }
  .fig { border-right: 0px; border-bottom: 1px solid rgba(255, 255, 255, 0.14); }
  .fig:last-child { border-bottom: 0px; }
  .imp-hero-sub { gap: 18px; }
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
