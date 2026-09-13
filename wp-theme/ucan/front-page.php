<?php
/**
 * Phase 0: Home. Content lifted verbatim from standalone/index.html's
 * <main> (see CLAUDE.md - content stays verbatim unless a named fix is
 * requested); only asset paths and internal links were rewritten to WP
 * functions. The page's own JSON-LD (Organization + WebSite + WebPage +
 * BreadcrumbList, already carrying correct absolute urban.org.in canonical
 * URLs) is re-emitted unchanged via functions.php's wp_head hook. get_header()
 * / get_footer() pull in header.php / footer.php, which is where the actual
 * <main id="main"> open/close tags live, so this file supplies only what
 * goes inside them.
 */

$ucan_page_meta = array(
	'description' => 'U-CAN unites urban practitioners, government and philanthropies to build liveable Indian cities. See how we are driving change.',
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

      "@type": "WebSite",

      "@id": "https://urban.org.in/#website",

      "url": "https://urban.org.in/",

      "name": "Urban Collective Action Network (U-CAN)",

      "publisher": {

        "@id": "https://urban.org.in/#org"

      },

      "inLanguage": "en-IN"

    },

    {

      "@type": "WebPage",

      "@id": "https://urban.org.in/#page",

      "url": "https://urban.org.in/",

      "name": "Urban Collective Action Network for India\'s cities | U-CAN",

      "description": "U-CAN unites urban practitioners, government and philanthropies to build liveable Indian cities. See how we are driving change.",

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

        }

      ]

    }

  ]

}';

$ucan_page_css = '@font-face { font-family: "PS Fallback"; src: local("Helvetica Neue"), local("Arial"); size-adjust: 99%; ascent-override: 92%; descent-override: 24%; line-gap-override: 0%; }

@font-face { font-family: "Arch Fallback"; src: local("Arial Bold"), local("Helvetica Neue Bold"), local("Arial"); size-adjust: 104%; ascent-override: 90%; descent-override: 22%; line-gap-override: 0%; }

:root { --paper: #FBFAF6; --paper-alt: #E9F5F2; --ink: #222120; --ink-soft: #57564F; --teal: #1F8F7B; --teal-text: #177A69; --teal-deep: #0E5348; --teal-light: #4EC6B2; --lime: #CDDE71; --orange: #FEAE00; --line: #DCEAE6; --radius: 2px; --wrap: 1180px; --sans: \'Public Sans\',\'PS Fallback\',system-ui,-apple-system,Segoe UI,Roboto,sans-serif; --display: \'Archivo\',\'Arch Fallback\',system-ui,-apple-system,Segoe UI,Arial,sans-serif; --shadow: 0 1px 2px rgba(14,83,72,.04),0 8px 28px -14px rgba(14,83,72,.22); --ease: cubic-bezier(.22,.61,.36,1); }

*, ::before, ::after { box-sizing: border-box; }

html { scroll-behavior: smooth; text-size-adjust: 100%; scroll-padding-top: 88px; }

body { margin: 0px; background: var(--paper); color: var(--ink); font-family: var(--sans); font-size: 17px; line-height: 1.62; -webkit-font-smoothing: antialiased; text-rendering: optimizelegibility; overflow-x: hidden; }

h1, h2, h3, h4, .display { font-family: var(--display); font-weight: 700; letter-spacing: -0.012em; margin: 0px; line-height: 1.18; }

p { margin: 0px; }

a { color: inherit; }

img, svg { display: block; max-width: 100%; }

ul { margin: 0px; padding: 0px; list-style: none; }

:focus-visible { outline: 2.5px solid var(--teal-deep); outline-offset: 3px; border-radius: var(--radius); }

::selection { background: var(--lime); color: var(--ink); }

.wrap { max-width: var(--wrap); margin: 0px auto; padding: 0px clamp(20px, 4vw, 32px); }

.skip { position: absolute; left: -9999px; top: 0px; background: var(--teal-deep); color: var(--paper); padding: 12px 20px; z-index: 200; font-weight: 600; }

.skip:focus { left: 12px; top: 12px; }

.eyebrow { font-family: var(--sans); font-weight: 700; font-size: 12.5px; letter-spacing: 0.1em; text-transform: uppercase; color: var(--teal-text); display: inline-flex; align-items: center; gap: 11px; margin: 0px; }

.eyebrow::before { content: ""; width: 24px; height: 1.5px; background: currentcolor; flex: 0 0 24px; }

.eyebrow.on-dark { color: var(--lime); }

.btn { display: inline-flex; align-items: center; gap: 10px; padding: 15px 28px; background: var(--teal-deep); color: var(--paper); font-weight: 600; font-size: 15px; letter-spacing: 0.01em; text-decoration: none; border: 1.5px solid var(--teal-deep); border-radius: var(--radius); transition: background .22s var(--ease),border-color .22s var(--ease),transform .22s var(--ease),box-shadow .22s var(--ease); will-change: transform; }

.btn:hover { background: var(--ink); border-color: var(--ink); transform: translateY(-2px); box-shadow: var(--shadow); }

.btn .arrow { transition: transform .22s var(--ease); }

.btn:hover .arrow { transform: translateX(5px); }

.btn.ghost { background: transparent; color: var(--teal-deep); border-color: var(--line); }

.btn.ghost:hover { background: transparent; color: var(--teal-deep); border-color: var(--teal); transform: translateY(-2px); }

.section { padding: clamp(72px, 9vw, 116px) 0px; position: relative; }

.section#members, .newsletter, footer { content-visibility: auto; contain-intrinsic-size: auto 720px; }

.section.alt { background: var(--paper-alt); }

.section-head { max-width: 660px; }

.section-head h2 { font-size: clamp(27px, 3.5vw, 36px); margin-top: 18px; }

.section-head p.sub { font-size: 17.5px; color: var(--ink-soft); margin-top: 16px; }

.nav { position: sticky; top: 0px; z-index: 80; background: rgba(251, 250, 246, 0.88); backdrop-filter: saturate(180%) blur(10px); border-bottom: 1px solid var(--line); transition: box-shadow .25s var(--ease); }

.nav.scrolled { box-shadow: rgba(14, 83, 72, 0.28) 0px 1px 20px -8px; }

.nav-inner { max-width: var(--wrap); margin: 0px auto; padding: 0px clamp(20px, 4vw, 32px); display: flex; align-items: center; justify-content: space-between; gap: 24px; height: 76px; }

.brand { display: flex; align-items: center; gap: 11px; text-decoration: none; }

.brand img { width: 36px; height: 36px; object-fit: contain; flex: 0 0 36px; }

.brand b { font-family: var(--display); font-weight: 700; font-size: 20px; letter-spacing: -0.01em; display: block; line-height: 1.1; }

.brand small { display: block; font-family: var(--sans); font-weight: 600; font-size: 8.6px; letter-spacing: 0.1em; color: var(--ink-soft); text-transform: uppercase; margin-top: 2px; }

.links { display: flex; gap: 30px; align-items: center; }

.links a { font-size: 14.5px; font-weight: 600; letter-spacing: 0.01em; text-decoration: none; color: var(--ink-soft); position: relative; padding: 6px 0px; transition: color .18s var(--ease); }

.links a::after { content: ""; position: absolute; left: 0px; right: 100%; bottom: 0px; height: 2px; background: var(--teal); transition: right .28s var(--ease); }

.links a:hover, .links a.active { color: var(--ink); }

.links a:hover::after, .links a.active::after { right: 0px; }

.nav-cta { display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; text-decoration: none; color: var(--teal-deep); border: 1.5px solid var(--line); padding: 9px 17px; border-radius: var(--radius); transition: border-color .2s var(--ease),background .2s var(--ease); }

.nav-cta:hover { border-color: var(--teal); background: var(--paper-alt); }

.burger { display: none; width: 44px; height: 44px; border: 1px solid var(--line); background: transparent; border-radius: var(--radius); cursor: pointer; padding: 0px; align-items: center; justify-content: center; }

.burger span { display: block; width: 18px; height: 1.6px; background: var(--ink); position: relative; transition: background 0.2s; }

.burger span::before, .burger span::after { content: ""; position: absolute; left: 0px; width: 18px; height: 1.6px; background: var(--ink); transition: transform .25s var(--ease); }

.burger span::before { top: -6px; }

.burger span::after { top: 6px; }

.burger[aria-expanded="true"] span { background: transparent; }

.burger[aria-expanded="true"] span::before { transform: translateY(6px) rotate(45deg); }

.burger[aria-expanded="true"] span::after { transform: translateY(-6px) rotate(-45deg); }

.mobile-menu { display: none; border-top: 1px solid var(--line); background: var(--paper); }

.mobile-menu.open { display: block; }

.mobile-menu a { display: block; padding: 15px 0px; border-bottom: 1px solid var(--line); text-decoration: none; font-weight: 600; font-size: 16px; color: var(--ink); }

.mobile-menu a:last-child { border-bottom: 0px; }

.hero { position: relative; padding: clamp(56px, 7vw, 84px) 0px 0px; overflow: hidden; }

.hero-grid { display: grid; grid-template-columns: 1.06fr 0.94fr; gap: clamp(32px, 5vw, 68px); align-items: center; }

.hero h1 { font-size: clamp(36px, 5.1vw, 58px); line-height: 1.06; letter-spacing: -0.024em; margin: 22px 0px 24px; max-width: 15ch; }

.hero h1 .accent { white-space: nowrap; background-image: linear-gradient(var(--orange),var(--orange)); background-repeat: no-repeat; background-position: 0px 88%; background-size: 0% 0.16em; animation: swash .9s .4s var(--ease) forwards; padding-bottom: 0.02em; }

@keyframes swash { 
  100% { background-size: 100% 0.16em; }
}

.hero .lede { font-size: clamp(17px, 1.5vw, 19px); color: var(--ink-soft); max-width: 52ch; }

.hero-actions { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 34px; }

.hero-proof { display: flex; flex-wrap: wrap; gap: 0px; margin-top: 40px; border-top: 1px solid var(--line); padding-top: 22px; }

.hero-proof div { padding-right: 26px; margin-right: 26px; border-right: 1px solid var(--line); }

.hero-proof div:last-child { border-right: 0px; margin-right: 0px; padding-right: 0px; }

.hero-proof b { font-family: var(--display); font-size: 22px; font-weight: 700; color: var(--teal-deep); display: block; line-height: 1.1; font-variant-numeric: tabular-nums; }

.hero-proof div.pop b { animation: statpop .5s var(--ease); }

.hero-proof span { font-size: 12.5px; letter-spacing: 0.05em; text-transform: uppercase; font-weight: 600; color: var(--ink-soft); }

.net { position: relative; aspect-ratio: 520 / 440; width: 100%; max-width: 540px; margin-inline: auto; }

.net svg { width: 100%; height: 100%; overflow: visible; }

.net .ln { fill: none; stroke: var(--teal); stroke-width: 1; opacity: 0; stroke-dasharray: 400; stroke-dashoffset: 400; animation: strand 1.1s var(--ease) forwards; }

.net .ln.out { stroke: var(--teal-deep); stroke-width: 1.3; }

@keyframes strand { 
  0% { opacity: 0; stroke-dashoffset: 400; }
  20% { opacity: 0.5; }
  100% { opacity: 0.5; stroke-dashoffset: 0; }
}

.net .nd { fill: var(--paper); stroke: var(--teal); stroke-width: 1.5; opacity: 0; animation: appear .5s var(--ease) forwards; }

@keyframes appear { 
  100% { opacity: 1; }
}

.net .hub { fill: var(--teal-deep); }

.net .ring { fill: none; stroke: var(--teal-light); stroke-width: 1.2; opacity: 0; transform-origin: 268px 220px; animation: 3.6s ease-out 1.4s infinite normal none running ripple; }

.net .ring.r2 { animation-delay: 3.2s; }

@keyframes ripple { 
  0% { opacity: 0.6; transform: scale(0.78); }
  70%, 100% { opacity: 0; transform: scale(1.9); }
}

.net .town { fill: var(--teal-deep); opacity: 0; animation: appear .55s var(--ease) forwards; }

.net .town rect:nth-child(2) { fill: var(--teal); }

.photo-band { position: relative; margin-top: clamp(48px, 6vw, 76px); }

.photo-band .shot { position: relative; width: 100%; aspect-ratio: 1400 / 612; overflow: hidden; background: var(--paper-alt); }

.photo-band img { width: 100%; height: 100%; object-fit: cover; object-position: center 42%; }

.photo-band .cap { position: absolute; left: 50%; transform: translateX(-50%); top: clamp(16px, 3vw, 22px); z-index: 2; display: inline-flex; align-items: center; gap: 9px; text-align: center; white-space: nowrap; font-size: 12.5px; color: rgb(255, 255, 255); background: rgba(14, 83, 72, 0.62); backdrop-filter: blur(6px); padding: 9px 18px; border-radius: 100px; letter-spacing: 0.03em; font-weight: 600; max-width: min(92%, 560px); box-shadow: rgba(0, 0, 0, 0.5) 0px 2px 14px -6px; }

.photo-band .cap::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: var(--lime); flex: 0 0 6px; }

@media (max-width: 560px) {
  .photo-band .cap { white-space: normal; font-size: 11.5px; padding: 8px 14px; }
}

.capsule { max-width: 900px; margin: -56px auto 0px; position: relative; z-index: 3; background: var(--paper); border-right-color: ; border-right-style: ; border-right-width: ; border-bottom-color: ; border-bottom-style: ; border-bottom-width: ; border-left-color: ; border-left-style: ; border-left-width: ; border-image-source: ; border-image-slice: ; border-image-width: ; border-image-outset: ; border-image-repeat: ; border-top: 3px solid var(--teal-deep); padding: clamp(24px, 3.4vw, 38px); box-shadow: var(--shadow); }

.capsule p { font-size: clamp(15.5px, 1.4vw, 17.5px); color: var(--ink); line-height: 1.66; }

.capsule .eyebrow { margin-bottom: 14px; }

.two-col { display: grid; grid-template-columns: 0.72fr 1.28fr; gap: clamp(28px, 5vw, 72px); align-items: start; }

.two-col .rail { position: sticky; top: 110px; }

.two-col h2 { font-size: clamp(24px, 2.9vw, 33px); line-height: 1.25; letter-spacing: -0.018em; }

.two-col .body-text { font-size: 17.5px; color: var(--ink-soft); max-width: 62ch; margin-top: 26px; }

.two-col .body-text p + p { margin-top: 18px; }

.quote { margin-top: 32px; background: var(--paper-alt); border-left: 3px solid var(--teal); padding: clamp(22px, 2.6vw, 30px); border-radius: 0 var(--radius) var(--radius) 0; }

.quote p { font-family: var(--display); font-style: italic; font-weight: 500; font-size: clamp(16px, 1.5vw, 18px); color: var(--ink); line-height: 1.58; }

.quote footer { display: flex; align-items: center; gap: 13px; margin-top: 20px; }

.quote .avatar { width: 42px; height: 42px; flex: 0 0 42px; border-radius: 50%; background: var(--teal-deep); color: var(--lime); display: flex; align-items: center; justify-content: center; font-family: var(--display); font-weight: 700; font-size: 15px; letter-spacing: 0.02em; }

.quote cite { font-style: normal; font-size: 13.5px; font-weight: 600; color: var(--teal-deep); line-height: 1.45; }

.cs-head { margin: clamp(64px, 8vw, 92px) 0px 8px; }

.cs-head h2 { font-size: clamp(23px, 2.7vw, 30px); margin-top: 14px; }

.cards-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; position: relative; margin-top: 0px; }

.cards-3 { margin-top: 8px; }

.c-card { position: relative; z-index: 1; background: var(--paper); border: 1px solid var(--line); padding: clamp(30px, 3.2vw, 40px) clamp(24px, 2.8vw, 32px) clamp(28px, 3vw, 36px); transition: transform .35s var(--ease),box-shadow .35s var(--ease),border-color .35s var(--ease); overflow: hidden; display: flex; flex-direction: column; }

.c-card::before { content: ""; position: absolute; top: 0px; left: 0px; bottom: 0px; width: 3px; background: var(--accent); transform: scaleY(0); transform-origin: center top; transition: transform .42s var(--ease); }

.c-card::after { content: ""; position: absolute; left: 0px; right: 0px; bottom: 0px; height: 0px; background: var(--wash); transition: height .42s var(--ease); z-index: -1; }

.c-card:hover, .c-card:focus-within { transform: translateY(-8px); box-shadow: var(--shadow); border-color: var(--accent); }

.c-card:hover::before, .c-card:focus-within::before { transform: scaleY(1); }

.c-card:hover::after, .c-card:focus-within::after { height: 100%; }

.c-index { position: absolute; top: clamp(18px, 2.4vw, 26px); right: clamp(20px, 2.6vw, 30px); font-family: var(--display); font-weight: 700; font-size: clamp(40px, 5vw, 58px); line-height: 0.8; color: var(--line); letter-spacing: -0.03em; transition: color .35s var(--ease),transform .35s var(--ease); pointer-events: none; }

.c-card:hover .c-index, .c-card:focus-within .c-index { color: var(--accent); opacity: 0.22; transform: scale(1.06); }

.c-ico { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: var(--wash); margin-bottom: 22px; transition: background .35s var(--ease),transform .35s var(--ease); }

.c-ico svg { width: 34px; height: 34px; fill: none; stroke: var(--accent); stroke-width: 1.7; stroke-linecap: round; stroke-linejoin: round; transition: stroke .35s var(--ease); }

.c-card:hover .c-ico, .c-card:focus-within .c-ico { background: var(--accent); transform: translateY(-2px); }

.c-card:hover .c-ico svg, .c-card:focus-within .c-ico svg { stroke: var(--paper); }

.c-card h3 { font-size: 19px; line-height: 1.3; margin-bottom: 13px; color: var(--ink); }

.c-word { display: block; font-family: var(--display); font-weight: 700; font-size: clamp(24px, 2.4vw, 29px); letter-spacing: -0.01em; text-transform: none; color: var(--accent); margin-bottom: 6px; line-height: 1.08; transition: color .35s var(--ease); }

.c-word::after { content: ""; display: block; width: 34px; height: 3px; background: var(--accent); margin-top: 12px; border-radius: 2px; transform-origin: left center; transform: scaleX(1); transition: width .35s var(--ease); }

.c-card:hover .c-word::after, .c-card:focus-within .c-word::after { width: 52px; }

.c-card p { font-size: 15px; color: var(--ink-soft); margin-top: auto; }

.stats { background: var(--teal-deep); color: var(--paper); padding: clamp(48px, 6vw, 64px) 0px; margin-top: clamp(40px, 5vw, 60px); }

.stats .lab { text-align: center; font-size: 12.5px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--lime); margin-bottom: 40px; }

.stats-row { display: grid; grid-template-columns: repeat(4, 1fr); max-width: 980px; margin: 0px auto; }

.stat { padding: 0px 18px; text-align: center; display: flex; flex-direction: column; align-items: center; }

.stat + .stat { border-left: 1px solid rgba(255, 255, 255, 0.16); }

.stat b { font-family: var(--display); font-weight: 700; font-size: clamp(34px, 4.2vw, 52px); color: var(--lime); line-height: 1; display: block; font-variant-numeric: tabular-nums; letter-spacing: -0.01em; }

.stat.pop b { animation: statpop .5s var(--ease); }

@keyframes statpop { 
  0% { transform: scale(0.8); opacity: 0.4; }
  60% { transform: scale(1.06); }
  100% { transform: scale(1); opacity: 1; }
}

.impact-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-top: clamp(44px, 5vw, 60px); }

.i-card { background: var(--paper); border: 1px solid var(--line); padding: clamp(24px, 2.6vw, 30px) clamp(20px, 2.4vw, 26px); display: flex; flex-direction: column; gap: 11px; position: relative; transition: transform .3s var(--ease),box-shadow .3s var(--ease),border-color .3s var(--ease); }

.i-card:hover { transform: translateY(-6px); box-shadow: var(--shadow); border-color: var(--teal-light); }

.i-card .n { font-family: var(--display); font-weight: 700; font-size: 13px; letter-spacing: 0.08em; color: var(--teal-text); display: flex; align-items: center; gap: 8px; }

.i-card .n::after { content: ""; height: 1px; flex: 1 1 0%; background: var(--line); }

.i-card h3 { font-size: 18.5px; line-height: 1.32; }

.i-card p { font-size: 15px; color: var(--ink-soft); }

.members-cols { display: grid; grid-template-columns: 0.72fr 1.28fr; gap: clamp(28px, 5vw, 72px); align-items: start; }

.logo-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }

.logo-grid.friends { grid-template-columns: repeat(3, 1fr); max-width: 640px; }

.chip { position: relative; border: 1px solid var(--line); background: var(--paper); min-height: 112px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; text-align: center; padding: 14px 12px; overflow: hidden; transition: border-color .28s var(--ease),transform .28s var(--ease),box-shadow .28s var(--ease),background .28s var(--ease); }

.chip .mono { width: 34px; height: 34px; border-radius: 50%; flex: 0 0 34px; display: flex; align-items: center; justify-content: center; font-family: var(--display); font-weight: 700; font-size: 12.5px; letter-spacing: 0.02em; background: var(--paper-alt); color: var(--teal-deep); transition: background .28s var(--ease),color .28s var(--ease),transform .28s var(--ease); }

.chip strong { font-weight: 700; font-size: 14px; line-height: 1.25; color: var(--ink); max-width: 15ch; }

.chip em { font-style: normal; display: block; font-weight: 500; font-size: 10.5px; letter-spacing: 0.04em; color: var(--ink-soft); margin-top: 3px; }

.chip:hover { border-color: var(--teal); transform: translateY(-4px); box-shadow: var(--shadow); background: var(--paper); }

.chip:hover .mono { background: var(--teal-deep); color: var(--lime); transform: scale(1.08); }

.vh { width: 1px; height: 1px; padding: 0px; margin: -1px; overflow: hidden; clip: rect(0px, 0px, 0px, 0px); white-space: nowrap; border: 0px; position: absolute !important; }

.rail-note { margin-top: 18px; font-size: 15.5px; color: var(--ink-soft); max-width: 30ch; }

.tier { font-size: 12.5px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--ink-soft); margin: 44px 0px 18px; display: flex; align-items: center; gap: 12px; }

.tier::after { content: ""; height: 1px; flex: 1 1 0%; background: var(--line); }

.newsletter { background: var(--teal-deep); color: var(--paper); padding: clamp(60px, 7vw, 88px) 0px; }

.nl-inner { display: grid; grid-template-columns: 1fr auto; gap: clamp(28px, 4vw, 56px); align-items: center; }

.newsletter h2 { color: var(--paper); font-size: clamp(24px, 2.9vw, 33px); max-width: 20ch; margin-top: 18px; line-height: 1.25; }

.nl-form { display: flex; gap: 10px; align-items: stretch; }

#nl-form { max-width: 560px; }

.nl-form input { padding: 15px 17px; border: 1.5px solid rgba(255, 255, 255, 0.28); background: rgba(255, 255, 255, 0.07); color: var(--paper); font-family: var(--sans); font-size: 15px; border-radius: var(--radius); width: 258px; transition: border-color .2s var(--ease),background .2s var(--ease); }

.nl-form input::placeholder { color: rgb(174, 203, 195); }

.nl-form input:focus { border-color: var(--lime); background: rgba(255, 255, 255, 0.12); outline: none; }

.nl-form button { padding: 15px 26px; background: var(--lime); border: 1.5px solid var(--lime); color: var(--ink); font-family: var(--sans); font-weight: 700; font-size: 15px; border-radius: var(--radius); cursor: pointer; transition: transform .2s var(--ease),filter .2s var(--ease); }

.nl-form button:hover { transform: translateY(-2px); filter: brightness(1.06); }

.nl-note { margin-top: 14px; font-size: 13px; color: rgb(174, 203, 195); }

.nl-ok { margin-top: 14px; font-size: 14px; font-weight: 600; color: var(--lime); display: none; }

footer { background: var(--ink); color: rgb(185, 196, 191); padding: clamp(52px, 6vw, 68px) 0px 26px; }

.f-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: clamp(28px, 4vw, 48px); padding-bottom: 40px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }

footer .brand b { color: var(--paper); }

footer .brand small { color: rgb(140, 160, 152); }

footer .blurb { max-width: 290px; margin-top: 18px; font-size: 14.5px; color: rgb(140, 160, 152); }

footer h4 { font-family: var(--sans); font-weight: 700; font-size: 12.5px; letter-spacing: 0.1em; text-transform: uppercase; color: var(--paper); margin-bottom: 18px; }

footer li { margin-bottom: 11px; }

footer a { text-decoration: none; font-size: 14.5px; color: rgb(185, 196, 191); transition: color .18s var(--ease); }

footer a:hover { color: var(--lime); }

.f-bottom { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding-top: 24px; font-size: 13px; color: rgb(124, 139, 133); }

.f-bottom a { color: rgb(124, 139, 133); margin-left: 18px; font-size: 13px; }

.two-col > *, .members-cols > *, .hero-grid > *, .nl-inner > *, .capsule > *, .cc-inner > *, .f-grid > *, .quote > * { min-width: 0px; }

.towns svg { max-width: 100%; }

.capsule { padding: clamp(28px, 3.6vw, 44px) clamp(24px, 4vw, 56px); text-align: center; }

.capsule .eyebrow { justify-content: center; }

.capsule .eyebrow::after { content: ""; width: 24px; height: 1.5px; background: currentcolor; flex: 0 0 24px; }

.capsule p.txt { font-size: clamp(15.5px, 1.4vw, 17.5px); color: var(--ink); line-height: 1.68; margin: 16px auto 0px; max-width: 70ch; }

.towns { margin-top: 26px; border: 1px solid var(--line); background: var(--paper); padding: 22px 22px 18px; }

.towns svg { width: 100%; height: auto; display: block; }

.towns figcaption { margin-top: 16px; font-size: 12.5px; line-height: 1.5; color: var(--ink-soft); letter-spacing: 0.02em; }

.towns .key { display: flex; gap: 16px; margin-top: 12px; flex-wrap: wrap; }

.towns .key span { display: inline-flex; align-items: center; gap: 7px; font-size: 11.5px; font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; color: var(--ink-soft); }

.towns .key i { width: 9px; height: 9px; display: inline-block; border-radius: 1px; }

.quote { display: grid; grid-template-columns: 168px 1fr; gap: 30px; align-items: start; background: var(--paper-alt); }

.q-media { margin: 0px; text-align: center; }

.q-media img { width: 132px; height: 132px; object-fit: cover; object-position: center 18%; border-radius: 50%; background: var(--paper); border: 3px solid var(--paper); box-shadow: 0 0 0 1.5px var(--teal-light); margin: 0px auto; }

.q-media figcaption { margin-top: 16px; padding-top: 14px; border-top: 2px solid var(--teal); display: inline-block; }

.q-mark { font-family: var(--display); font-size: 44px; line-height: 0.6; color: var(--teal-light); display: block; margin-bottom: 6px; }

.quote .q-name { display: block; font-family: var(--display); font-weight: 700; font-size: 15px; color: var(--teal-deep); font-style: normal; }

.quote .q-role { display: block; font-size: 12.5px; font-weight: 500; color: var(--ink-soft); margin-top: 5px; line-height: 1.5; font-style: normal; }

.stat .s-ico { display: flex; align-items: center; justify-content: center; width: 66px; height: 66px; margin: 0px auto 20px; border-radius: 50%; background: rgba(205, 222, 113, 0.12); border: 1px solid rgba(205, 222, 113, 0.3); transition: background .35s var(--ease),transform .35s var(--ease),border-color .35s var(--ease); }

.stat .s-ico svg { width: 36px; height: 36px; fill: var(--lime); stroke: var(--lime); stroke-linecap: round; stroke-linejoin: round; }

.stat .s-ico svg [fill="none"] { fill: none; }

.stat .s-ico svg [fill="var(--teal-deep)"] { fill: var(--teal-deep); stroke: none; }

.stat:hover .s-ico { background: var(--lime); transform: translateY(-4px); border-color: var(--lime); }

.stat:hover .s-ico svg { fill: var(--teal-deep); stroke: var(--teal-deep); }

.stat:hover .s-ico svg [fill="var(--teal-deep)"] { fill: var(--lime); }

.s-kick { display: block; font-size: 11.5px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--lime); margin-bottom: 8px; }

.s-cap { display: block; margin-top: 10px; font-size: 13.5px; font-weight: 500; color: rgb(207, 224, 218); max-width: 180px; margin-inline: auto; min-height: 38px; }

.i-card { padding-top: clamp(26px, 2.8vw, 32px); }

.i-card.no-ico { padding-top: clamp(26px, 2.8vw, 32px); }

.i-card:nth-child(1) .i-card:nth-child(2) .i-card:nth-child(3) .i-card:nth-child(4) .i-card:hover .i-card:hover .i-card .n { margin-top: 2px; }

.logo-grid { gap: 16px; }

.chip { padding: 0px; }

.chip a { display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; min-height: 116px; padding: 18px 20px; text-decoration: none; position: relative; }

.chip img { max-height: 56px; max-width: 82%; width: auto; height: auto; object-fit: contain; filter: grayscale(1); opacity: 0.72; transition: filter .3s var(--ease),opacity .3s var(--ease),transform .3s var(--ease); }

.chip:hover img { filter: grayscale(0); opacity: 1; transform: scale(1.04); }

.chip .go { position: absolute; top: 9px; right: 11px; font-size: 13px; line-height: 1; color: var(--teal); opacity: 0; transform: translate(-3px, 3px); transition: opacity .25s var(--ease),transform .25s var(--ease); }

.chip:hover .go, .chip a:focus-visible .go { opacity: 1; transform: none; }

.consent { display: flex; gap: 11px; align-items: flex-start; margin-top: 16px; max-width: 520px; }

.consent input[type="checkbox"] { appearance: none; flex: 0 0 18px; width: 18px; height: 18px; margin-top: 2px; border: 1.5px solid rgba(255, 255, 255, 0.5); border-radius: 2px; background: rgba(255, 255, 255, 0.06); cursor: pointer; display: grid; place-content: center; transition: background 0.18s, border-color 0.18s; }

.consent input[type="checkbox"]::before { content: ""; width: 10px; height: 10px; transform: scale(0); transition: transform .15s var(--ease); box-shadow: inset 1em 1em var(--teal-deep); clip-path: polygon(14% 44%, 0px 65%, 50% 100%, 100% 16%, 80% 0px, 43% 62%); }

.consent input[type="checkbox"]:checked { background: var(--lime); border-color: var(--lime); }

.consent input[type="checkbox"]:checked::before { transform: scale(1); }

.consent label { font-size: 13px; line-height: 1.55; color: rgb(207, 224, 218); cursor: pointer; }

.consent a { color: var(--lime); text-underline-offset: 2px; }

.nl-legal { margin-top: 12px; font-size: 12px; line-height: 1.55; color: rgb(156, 189, 180); max-width: 520px; }

.nl-err { display: none; margin-top: 10px; font-size: 13px; font-weight: 600; color: var(--orange); }

.cc { position: fixed; left: 0px; right: 0px; bottom: 0px; z-index: 120; background: var(--paper); border-top: 3px solid var(--teal-deep); box-shadow: rgba(14, 83, 72, 0.5) 0px -8px 40px -18px; transform: translateY(110%); transition: transform .5s var(--ease); }

.cc.show { transform: none; }

.cc-inner { max-width: var(--wrap); margin: 0px auto; padding: 22px clamp(20px, 4vw, 32px); display: grid; grid-template-columns: 1fr auto; gap: 26px; align-items: center; }

.cc h2 { font-size: 17px; margin-bottom: 7px; }

.cc p { font-size: 13.5px; line-height: 1.6; color: var(--ink-soft); max-width: 74ch; }

.cc p a { color: var(--teal-text); font-weight: 600; }

.cc-actions { display: flex; gap: 10px; flex-wrap: wrap; }

.cc-btn { padding: 12px 20px; font-family: var(--sans); font-size: 14px; font-weight: 700; border-radius: var(--radius); cursor: pointer; border: 1.5px solid var(--teal-deep); background: var(--teal-deep); color: var(--paper); transition: transform .2s var(--ease),background .2s var(--ease),color .2s var(--ease); white-space: nowrap; }

.cc-btn:hover { transform: translateY(-2px); }

.cc-btn.alt { background: transparent; color: var(--teal-deep); border-color: var(--line); }

.cc-btn.alt:hover { border-color: var(--teal); }

.cc-badge { display: inline-flex; align-items: center; gap: 7px; font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--teal-text); margin-bottom: 9px; }

.cc-badge::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: var(--teal); }

.cookie-link { background: none; border: 0px; padding: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; font-optical-sizing: inherit; font-size-adjust: inherit; font-kerning: inherit; font-feature-settings: inherit; font-variation-settings: inherit; font-language-override: inherit; font-size: 13px; color: rgb(124, 139, 133); cursor: pointer; margin-left: 18px; }

.cookie-link:hover { color: var(--lime); }

@media (max-width: 880px) {
  .cc-inner { grid-template-columns: 1fr; gap: 16px; }
}

@media (max-width: 560px) {
  .quote { grid-template-columns: 1fr; gap: 22px; }
  .q-media { text-align: left; }
  .q-media img { width: 104px; height: 104px; margin: 0px; }
  .logo-grid, .logo-grid.friends { grid-template-columns: repeat(2, 1fr); }
  .chip a { min-height: 98px; padding: 14px; }
  .cc-actions { flex-direction: column; }
  .cc-btn { width: 100%; }
}

.rv { opacity: 0; transform: translateY(18px); transition: opacity .6s var(--ease),transform .6s var(--ease); }

.rv.in { opacity: 1; transform: none; }

.rv.d1 { transition-delay: 0.07s; }

.rv.d2 { transition-delay: 0.14s; }

.rv.d3 { transition-delay: 0.21s; }

@media (max-width: 1020px) {
  .hero-grid { grid-template-columns: 1fr; gap: 40px; }
  .net { max-width: 400px; }
  .hero h1 { max-width: 18ch; }
  .impact-grid { grid-template-columns: repeat(2, 1fr); }
  .two-col, .members-cols { grid-template-columns: 1fr; }
  .two-col .rail { position: static; }
}

@media (max-width: 880px) {
  .links, .nav-cta { display: none; }
  .burger { display: flex; }
  .cards-3 { grid-template-columns: 1fr; }
  .cards-3 { margin-top: 0px; }
  .stats-row { grid-template-columns: repeat(2, 1fr); gap: 32px 0px; }
  .stat + .stat { border-left-width: medium; border-left-style: none; border-left-color: currentcolor; }
  .stat:nth-child(2n) { border-left: 1px solid rgba(255, 255, 255, 0.16); }
  .nl-inner { grid-template-columns: 1fr; }
  .f-grid { grid-template-columns: 1fr; }
  .capsule { margin-top: -28px; }
}

@media (max-width: 640px) {
  body { font-size: 16px; }
  .net { display: none; }
  .hero-grid { gap: 0px; }
  .impact-grid { grid-template-columns: 1fr; }
  .logo-grid, .logo-grid.friends { grid-template-columns: repeat(2, 1fr); }
  .hero-proof div { padding-right: 16px; margin-right: 16px; }
  .hero-proof b { font-size: 19px; }
  .nl-form { flex-direction: column; }
  .nl-form input { width: 100%; }
  .capsule { margin-top: 0px; border-top-width: 3px; }
  .photo-band .shot { aspect-ratio: 16 / 11; }
}

@media (prefers-reduced-motion: reduce) {
  html { scroll-behavior: auto; }
  *, ::before, ::after { animation: auto ease 0s 1 normal none running none !important; transition: none !important; }
  .rv { opacity: 1; transform: none; }
  .hero h1 .accent { background-size: 100% 0.16em; }
  .net .edge { stroke-dashoffset: 0; }
  .net .node { opacity: 1; }
}

@media print {
  .nav, .newsletter, .net { display: none; }
  body { font-size: 12pt; }
}';

get_header();
?>




<!-- ===================== HERO ===================== -->

<section class="hero" aria-labelledby="h1">
  <canvas class="hero-field" aria-hidden="true"></canvas>

  <div class="wrap hero-grid">

    <div>

      <p class="eyebrow">Urban Collective Action Network</p>

      <h1 id="h1"><span class="hw" style="--i:0;--dx:-60px;--dy:-26px;--r:-6deg">Turning</span> <span class="hw" style="--i:1;--dx:44px;--dy:-40px;--r:5deg">individual</span> <span class="hw" style="--i:2;--dx:70px;--dy:18px;--r:7deg">effort</span> <span class="hw" style="--i:3;--dx:-30px;--dy:36px;--r:-4deg">into</span><br class="hbr"> <span class="accent"><span class="hw" style="--i:4;--dx:-46px;--dy:30px;--r:0deg">collective</span> <span class="hw" style="--i:5;--dx:52px;--dy:-28px;--r:0deg">action</span></span> <span class="hw" style="--i:6;--dx:-64px;--dy:22px;--r:-5deg">across</span><br class="hbr"> <span class="hw" style="--i:7;--dx:38px;--dy:40px;--r:4deg">India's</span> <span class="hw" style="--i:8;--dx:-24px;--dy:-34px;--r:-3deg">cities</span> <span class="hw" style="--i:9;--dx:60px;--dy:-16px;--r:6deg">and</span> <span class="hw" style="--i:10;--dx:-40px;--dy:30px;--r:-5deg">towns</span></h1>

      <p class="lede">U-CAN convenes urban practitioners, governments, researchers and philanthropies to champion liveable cities for their residents, and communicate the need to attract capital to the urban sector.</p>

      <div class="hero-actions">

        <a class="btn" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">Explore our work <span class="arrow" aria-hidden="true">→</span></a>

        <a class="btn ghost" href="<?php echo esc_url( home_url( '/our-members/' ) ); ?>">Meet our members</a>

      </div>

      <div class="hero-proof" id="hero-proof">

        <div class="pop"><b data-to="8">8</b><span>Members</span></div>

        <div class="pop"><b data-to="500" data-suffix="+">500+</b><span>Practitioners</span></div>

        <div class="pop"><b data-to="25" data-suffix="+">25+</b><span>Cities</span></div>

      </div>

    </div>

  </div>



  <!-- Photo band: real people, real forum -->

  <div class="photo-band">

    <div class="shot">

      <img loading="lazy" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/practitioners-researchers-media-and-government-o-d8f17bc4d5-760.webp 760w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/practitioners-researchers-media-and-government-o-d8f17bc4d5.webp 1400w" sizes="100vw" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/practitioners-researchers-media-and-government-o-d8f17bc4d5.webp" width="1400" height="612" decoding="async" fetchpriority="low" alt="Practitioners, researchers, media and government officials at U-CAN's first Annual Forum">

    </div>

    <p class="cap">Key attendees at U-CAN's first Annual Forum</p>

  </div>



  <div class="wrap">

    <div class="capsule rv in">

      <p class="eyebrow">In brief</p>

      <p class="txt">U-CAN is a national network connecting urban practitioners, government officials, researchers and philanthropies working to build more liveable cities across India. Since launching, the network has brought together 500+ practitioners, 200+ government officials and 25+ cities to share what works and act on it together. Here's how U-CAN turns individual effort into collective action, and what it's helped build so far.</p>

    </div>

  </div>

</section>



<!-- ===================== WHY U-CAN ===================== -->

<section class="section" id="about" aria-labelledby="why-h">

  <!-- WHY:start --><div class="wrap why2"><div class="why2-head rv"><p class="eyebrow">Why U-CAN?</p><h2 id="why-h">India's urban challenges are too complex for any one organisation to solve alone, so we create the conditions to enable collaboration between organisations.</h2></div><div class="why2-grid"><div class="why2-story"><figure class="why2-scale rv"><svg viewBox="0 0 560 160" aria-hidden="true" focusable="false"><g class="w2-metro"><circle cx="46" cy="62" r="17" style="--d:0ms"/><circle cx="98" cy="38" r="13" style="--d:90ms"/><circle cx="92" cy="100" r="15" style="--d:180ms"/><circle cx="148" cy="72" r="12" style="--d:270ms"/><circle cx="40" cy="128" r="11" style="--d:360ms"/></g><g class="w2-towns"><circle cx="236.0" cy="22.0" r="2.6" style="--d:300ms"/><circle cx="250.6" cy="22.0" r="2.6" style="--d:307ms"/><circle cx="265.2" cy="22.0" r="2.6" style="--d:314ms"/><circle cx="279.8" cy="22.0" r="2.6" style="--d:321ms"/><circle cx="294.4" cy="22.0" r="2.6" style="--d:328ms"/><circle cx="309.0" cy="22.0" r="2.6" style="--d:335ms"/><circle cx="323.6" cy="22.0" r="2.6" style="--d:342ms"/><circle cx="338.2" cy="22.0" r="2.6" style="--d:349ms"/><circle cx="352.8" cy="22.0" r="2.6" style="--d:356ms"/><circle cx="367.4" cy="22.0" r="2.6" style="--d:363ms"/><circle cx="382.0" cy="22.0" r="2.6" style="--d:370ms"/><circle cx="396.6" cy="22.0" r="2.6" style="--d:377ms"/><circle cx="411.2" cy="22.0" r="2.6" style="--d:384ms"/><circle cx="425.8" cy="22.0" r="2.6" style="--d:391ms"/><circle cx="440.4" cy="22.0" r="2.6" style="--d:398ms"/><circle cx="455.0" cy="22.0" r="2.6" style="--d:405ms"/><circle cx="469.6" cy="22.0" r="2.6" style="--d:412ms"/><circle cx="484.2" cy="22.0" r="2.6" style="--d:419ms"/><circle cx="498.8" cy="22.0" r="2.6" style="--d:426ms"/><circle cx="513.4" cy="22.0" r="2.6" style="--d:433ms"/><circle cx="528.0" cy="22.0" r="2.6" style="--d:440ms"/><circle cx="542.6" cy="22.0" r="2.6" style="--d:447ms"/><circle cx="243.0" cy="36.4" r="2.6" style="--d:454ms"/><circle cx="257.6" cy="36.4" r="2.6" style="--d:461ms"/><circle cx="272.2" cy="36.4" r="2.6" style="--d:468ms"/><circle cx="286.8" cy="36.4" r="2.6" style="--d:475ms"/><circle cx="301.4" cy="36.4" r="2.6" style="--d:482ms"/><circle cx="316.0" cy="36.4" r="2.6" style="--d:489ms"/><circle cx="330.6" cy="36.4" r="2.6" style="--d:496ms"/><circle cx="345.2" cy="36.4" r="2.6" style="--d:503ms"/><circle cx="359.8" cy="36.4" r="2.6" style="--d:510ms"/><circle cx="374.4" cy="36.4" r="2.6" style="--d:517ms"/><circle cx="389.0" cy="36.4" r="2.6" style="--d:524ms"/><circle cx="403.6" cy="36.4" r="2.6" style="--d:531ms"/><circle cx="418.2" cy="36.4" r="2.6" style="--d:538ms"/><circle cx="432.8" cy="36.4" r="2.6" style="--d:545ms"/><circle cx="447.4" cy="36.4" r="2.6" style="--d:552ms"/><circle cx="462.0" cy="36.4" r="2.6" style="--d:559ms"/><circle cx="476.6" cy="36.4" r="2.6" style="--d:566ms"/><circle cx="491.2" cy="36.4" r="2.6" style="--d:573ms"/><circle cx="505.8" cy="36.4" r="2.6" style="--d:580ms"/><circle cx="520.4" cy="36.4" r="2.6" style="--d:587ms"/><circle cx="535.0" cy="36.4" r="2.6" style="--d:594ms"/><circle cx="549.6" cy="36.4" r="2.6" style="--d:601ms"/><circle cx="236.0" cy="50.8" r="2.6" style="--d:608ms"/><circle cx="250.6" cy="50.8" r="2.6" style="--d:615ms"/><circle cx="265.2" cy="50.8" r="2.6" style="--d:622ms"/><circle cx="279.8" cy="50.8" r="2.6" style="--d:629ms"/><circle cx="294.4" cy="50.8" r="2.6" style="--d:636ms"/><circle cx="309.0" cy="50.8" r="2.6" style="--d:643ms"/><circle cx="323.6" cy="50.8" r="2.6" style="--d:650ms"/><circle cx="338.2" cy="50.8" r="2.6" style="--d:657ms"/><circle cx="352.8" cy="50.8" r="2.6" style="--d:664ms"/><circle cx="367.4" cy="50.8" r="2.6" style="--d:671ms"/><circle cx="382.0" cy="50.8" r="2.6" style="--d:678ms"/><circle cx="396.6" cy="50.8" r="2.6" style="--d:685ms"/><circle cx="411.2" cy="50.8" r="2.6" style="--d:692ms"/><circle cx="425.8" cy="50.8" r="2.6" style="--d:699ms"/><circle cx="440.4" cy="50.8" r="2.6" style="--d:706ms"/><circle cx="455.0" cy="50.8" r="2.6" style="--d:713ms"/><circle cx="469.6" cy="50.8" r="2.6" style="--d:720ms"/><circle cx="484.2" cy="50.8" r="2.6" style="--d:727ms"/><circle cx="498.8" cy="50.8" r="2.6" style="--d:734ms"/><circle cx="513.4" cy="50.8" r="2.6" style="--d:741ms"/><circle cx="528.0" cy="50.8" r="2.6" style="--d:748ms"/><circle cx="542.6" cy="50.8" r="2.6" style="--d:755ms"/><circle cx="243.0" cy="65.2" r="2.6" style="--d:762ms"/><circle cx="257.6" cy="65.2" r="2.6" style="--d:769ms"/><circle cx="272.2" cy="65.2" r="2.6" style="--d:776ms"/><circle cx="286.8" cy="65.2" r="2.6" style="--d:783ms"/><circle cx="301.4" cy="65.2" r="2.6" style="--d:790ms"/><circle cx="316.0" cy="65.2" r="2.6" style="--d:797ms"/><circle cx="330.6" cy="65.2" r="2.6" style="--d:804ms"/><circle cx="345.2" cy="65.2" r="2.6" style="--d:811ms"/><circle cx="359.8" cy="65.2" r="2.6" style="--d:818ms"/><circle cx="374.4" cy="65.2" r="2.6" style="--d:825ms"/><circle cx="389.0" cy="65.2" r="2.6" style="--d:832ms"/><circle cx="403.6" cy="65.2" r="2.6" style="--d:839ms"/><circle cx="418.2" cy="65.2" r="2.6" style="--d:846ms"/><circle cx="432.8" cy="65.2" r="2.6" style="--d:853ms"/><circle cx="447.4" cy="65.2" r="2.6" style="--d:860ms"/><circle cx="462.0" cy="65.2" r="2.6" style="--d:867ms"/><circle cx="476.6" cy="65.2" r="2.6" style="--d:874ms"/><circle cx="491.2" cy="65.2" r="2.6" style="--d:881ms"/><circle cx="505.8" cy="65.2" r="2.6" style="--d:888ms"/><circle cx="520.4" cy="65.2" r="2.6" style="--d:895ms"/><circle cx="535.0" cy="65.2" r="2.6" style="--d:902ms"/><circle cx="549.6" cy="65.2" r="2.6" style="--d:909ms"/><circle cx="236.0" cy="79.6" r="2.6" style="--d:916ms"/><circle cx="250.6" cy="79.6" r="2.6" style="--d:923ms"/><circle cx="265.2" cy="79.6" r="2.6" style="--d:930ms"/><circle cx="279.8" cy="79.6" r="2.6" style="--d:937ms"/><circle cx="294.4" cy="79.6" r="2.6" style="--d:944ms"/><circle cx="309.0" cy="79.6" r="2.6" style="--d:951ms"/><circle cx="323.6" cy="79.6" r="2.6" style="--d:958ms"/><circle cx="338.2" cy="79.6" r="2.6" style="--d:965ms"/><circle cx="352.8" cy="79.6" r="2.6" style="--d:972ms"/><circle cx="367.4" cy="79.6" r="2.6" style="--d:979ms"/><circle cx="382.0" cy="79.6" r="2.6" style="--d:986ms"/><circle cx="396.6" cy="79.6" r="2.6" style="--d:993ms"/><circle cx="411.2" cy="79.6" r="2.6" style="--d:1000ms"/><circle cx="425.8" cy="79.6" r="2.6" style="--d:1007ms"/><circle cx="440.4" cy="79.6" r="2.6" style="--d:1014ms"/><circle cx="455.0" cy="79.6" r="2.6" style="--d:1021ms"/><circle cx="469.6" cy="79.6" r="2.6" style="--d:1028ms"/><circle cx="484.2" cy="79.6" r="2.6" style="--d:1035ms"/><circle cx="498.8" cy="79.6" r="2.6" style="--d:1042ms"/><circle cx="513.4" cy="79.6" r="2.6" style="--d:1049ms"/><circle cx="528.0" cy="79.6" r="2.6" style="--d:1056ms"/><circle cx="542.6" cy="79.6" r="2.6" style="--d:1063ms"/><circle cx="243.0" cy="94.0" r="2.6" style="--d:1070ms"/><circle cx="257.6" cy="94.0" r="2.6" style="--d:1077ms"/><circle cx="272.2" cy="94.0" r="2.6" style="--d:1084ms"/><circle cx="286.8" cy="94.0" r="2.6" style="--d:1091ms"/><circle cx="301.4" cy="94.0" r="2.6" style="--d:1098ms"/><circle cx="316.0" cy="94.0" r="2.6" style="--d:1105ms"/><circle cx="330.6" cy="94.0" r="2.6" style="--d:1112ms"/><circle cx="345.2" cy="94.0" r="2.6" style="--d:1119ms"/><circle cx="359.8" cy="94.0" r="2.6" style="--d:1126ms"/><circle cx="374.4" cy="94.0" r="2.6" style="--d:1133ms"/><circle cx="389.0" cy="94.0" r="2.6" style="--d:1140ms"/><circle cx="403.6" cy="94.0" r="2.6" style="--d:1147ms"/><circle cx="418.2" cy="94.0" r="2.6" style="--d:1154ms"/><circle cx="432.8" cy="94.0" r="2.6" style="--d:1161ms"/><circle cx="447.4" cy="94.0" r="2.6" style="--d:1168ms"/><circle cx="462.0" cy="94.0" r="2.6" style="--d:1175ms"/><circle cx="476.6" cy="94.0" r="2.6" style="--d:1182ms"/><circle cx="491.2" cy="94.0" r="2.6" style="--d:1189ms"/><circle cx="505.8" cy="94.0" r="2.6" style="--d:1196ms"/><circle cx="520.4" cy="94.0" r="2.6" style="--d:303ms"/><circle cx="535.0" cy="94.0" r="2.6" style="--d:310ms"/><circle cx="549.6" cy="94.0" r="2.6" style="--d:317ms"/><circle cx="236.0" cy="108.4" r="2.6" style="--d:324ms"/><circle cx="250.6" cy="108.4" r="2.6" style="--d:331ms"/><circle cx="265.2" cy="108.4" r="2.6" style="--d:338ms"/><circle cx="279.8" cy="108.4" r="2.6" style="--d:345ms"/><circle cx="294.4" cy="108.4" r="2.6" style="--d:352ms"/><circle cx="309.0" cy="108.4" r="2.6" style="--d:359ms"/><circle cx="323.6" cy="108.4" r="2.6" style="--d:366ms"/><circle cx="338.2" cy="108.4" r="2.6" style="--d:373ms"/><circle cx="352.8" cy="108.4" r="2.6" style="--d:380ms"/><circle cx="367.4" cy="108.4" r="2.6" style="--d:387ms"/><circle cx="382.0" cy="108.4" r="2.6" style="--d:394ms"/><circle cx="396.6" cy="108.4" r="2.6" style="--d:401ms"/><circle cx="411.2" cy="108.4" r="2.6" style="--d:408ms"/><circle cx="425.8" cy="108.4" r="2.6" style="--d:415ms"/><circle cx="440.4" cy="108.4" r="2.6" style="--d:422ms"/><circle cx="455.0" cy="108.4" r="2.6" style="--d:429ms"/><circle cx="469.6" cy="108.4" r="2.6" style="--d:436ms"/><circle cx="484.2" cy="108.4" r="2.6" style="--d:443ms"/><circle cx="498.8" cy="108.4" r="2.6" style="--d:450ms"/><circle cx="513.4" cy="108.4" r="2.6" style="--d:457ms"/><circle cx="528.0" cy="108.4" r="2.6" style="--d:464ms"/><circle cx="542.6" cy="108.4" r="2.6" style="--d:471ms"/><circle cx="243.0" cy="122.8" r="2.6" style="--d:478ms"/><circle cx="257.6" cy="122.8" r="2.6" style="--d:485ms"/><circle cx="272.2" cy="122.8" r="2.6" style="--d:492ms"/><circle cx="286.8" cy="122.8" r="2.6" style="--d:499ms"/><circle cx="301.4" cy="122.8" r="2.6" style="--d:506ms"/><circle cx="316.0" cy="122.8" r="2.6" style="--d:513ms"/><circle cx="330.6" cy="122.8" r="2.6" style="--d:520ms"/><circle cx="345.2" cy="122.8" r="2.6" style="--d:527ms"/><circle cx="359.8" cy="122.8" r="2.6" style="--d:534ms"/><circle cx="374.4" cy="122.8" r="2.6" style="--d:541ms"/><circle cx="389.0" cy="122.8" r="2.6" style="--d:548ms"/><circle cx="403.6" cy="122.8" r="2.6" style="--d:555ms"/><circle cx="418.2" cy="122.8" r="2.6" style="--d:562ms"/><circle cx="432.8" cy="122.8" r="2.6" style="--d:569ms"/><circle cx="447.4" cy="122.8" r="2.6" style="--d:576ms"/><circle cx="462.0" cy="122.8" r="2.6" style="--d:583ms"/><circle cx="476.6" cy="122.8" r="2.6" style="--d:590ms"/><circle cx="491.2" cy="122.8" r="2.6" style="--d:597ms"/><circle cx="505.8" cy="122.8" r="2.6" style="--d:604ms"/><circle cx="520.4" cy="122.8" r="2.6" style="--d:611ms"/><circle cx="535.0" cy="122.8" r="2.6" style="--d:618ms"/><circle cx="549.6" cy="122.8" r="2.6" style="--d:625ms"/><circle cx="236.0" cy="137.2" r="2.6" style="--d:632ms"/><circle cx="250.6" cy="137.2" r="2.6" style="--d:639ms"/><circle cx="265.2" cy="137.2" r="2.6" style="--d:646ms"/><circle cx="279.8" cy="137.2" r="2.6" style="--d:653ms"/><circle cx="294.4" cy="137.2" r="2.6" style="--d:660ms"/><circle cx="309.0" cy="137.2" r="2.6" style="--d:667ms"/><circle cx="323.6" cy="137.2" r="2.6" style="--d:674ms"/><circle cx="338.2" cy="137.2" r="2.6" style="--d:681ms"/><circle cx="352.8" cy="137.2" r="2.6" style="--d:688ms"/><circle cx="367.4" cy="137.2" r="2.6" style="--d:695ms"/><circle cx="382.0" cy="137.2" r="2.6" style="--d:702ms"/><circle cx="396.6" cy="137.2" r="2.6" style="--d:709ms"/><circle cx="411.2" cy="137.2" r="2.6" style="--d:716ms"/><circle cx="425.8" cy="137.2" r="2.6" style="--d:723ms"/><circle cx="440.4" cy="137.2" r="2.6" style="--d:730ms"/><circle cx="455.0" cy="137.2" r="2.6" style="--d:737ms"/><circle cx="469.6" cy="137.2" r="2.6" style="--d:744ms"/><circle cx="484.2" cy="137.2" r="2.6" style="--d:751ms"/><circle cx="498.8" cy="137.2" r="2.6" style="--d:758ms"/><circle cx="513.4" cy="137.2" r="2.6" style="--d:765ms"/><circle cx="528.0" cy="137.2" r="2.6" style="--d:772ms"/><circle cx="542.6" cy="137.2" r="2.6" style="--d:779ms"/></g></svg><figcaption><span><b>A handful</b> of metropolitan cities</span><span><b>Nearly 10,000</b> smaller towns and cities</span></figcaption></figure><div class="body-text"><p class="rv d1">Most of what shapes urban policy in India comes from a handful of metropolitan cities. But the real story, and much of the country's urban growth, is playing out in nearly 10,000 smaller towns and cities, often with little data and even less coordination between the people working to improve them.</p><p class="rv d2">U-CAN exists to be the connective tissue that's missing: a trusted space for practitioners, government, researchers and philanthropies to connect, learn from each other, and act together.</p></div></div><figure class="why2-quote rv d1"><span class="why2-mark" aria-hidden="true">&ldquo;</span><blockquote><p>The spark for U-CAN emerged from two intertwined realisations. First, we may be running out of time for slow, linear change; our cities need bold, accelerated action built on collective momentum. Second, when the right people and ideas meet in a shared conversation, they can catalyse transformative change at scale. U-CAN was born from that belief: a space for collaboration beyond boundaries.</p></blockquote><figcaption class="why2-by"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/shilpa-kumar-founding-member-of-u-can-and-managi-c9c0ed061c.webp" width="132" height="132" loading="lazy" decoding="async" alt="Shilpa Kumar, Founding Member of U-CAN and Managing Director &amp; Head of India at British International Investment"><span><b>Shilpa Kumar</b><em>Founding Member, U-CAN and Managing Director &amp; Head of India, British International Investment</em></span></figcaption></figure></div></div><!-- WHY:end -->



  <!-- ===================== WHAT WE DO ===================== -->

  <div class="wrap" id="what-we-do">

    <div class="cs-head rv in">

      <p class="eyebrow">What we do</p>

      <h2>Our 3Cs of Collective Action</h2>

    </div>

    <div class="cards-3">

      <article class="c-card rv in" tabindex="0" style="--accent:var(--teal);--wash:rgba(31,143,123,.10)">

        <span class="c-index" aria-hidden="true">01</span>

        <span class="c-ico" aria-hidden="true">

          <svg viewBox="0 0 40 40" role="presentation"><circle cx="20" cy="20" r="6.5"></circle><circle cx="8" cy="12" r="3.4"></circle><circle cx="32" cy="12" r="3.4"></circle><circle cx="8" cy="28" r="3.4"></circle><circle cx="32" cy="28" r="3.4"></circle><path d="M11 13.4 14.6 16M29 13.4 25.4 16M11 26.6 14.6 24M29 26.6 25.4 24"></path></svg>

        </span>

        <h3><span class="c-word">Convene</span>Create spaces to connect</h3>

        <p>We create safe, structured spaces, both online and in-person, for members to share what's worked, what hasn't, and where new collaborations can begin.</p>

      </article>

      <article class="c-card rv d1 in" tabindex="0" style="--accent:var(--teal-light);--wash:rgba(78,198,178,.14)">

        <span class="c-index" aria-hidden="true">02</span>

        <span class="c-ico" aria-hidden="true">

          <svg viewBox="0 0 40 40" role="presentation"><path d="M20 30V15"></path><path d="m13 21 7-7 7 7"></path><path d="M9 32h22"></path><circle cx="20" cy="9" r="3"></circle></svg>

        </span>

        <h3><span class="c-word">Champion</span>Align action, drive reform</h3>

        <p>We help members align strategies, pool resources and coordinate action around shared goals, working through the Urban Reforms Collective's engagement with government and a shared narrative on the role of Tier II and III cities in India's growth.</p>

      </article>

      <article class="c-card rv d2 in" tabindex="0" style="--accent:var(--teal-deep);--wash:rgba(14,83,72,.10)">

        <span class="c-index" aria-hidden="true">03</span>

        <span class="c-ico" aria-hidden="true">

          <svg viewBox="0 0 40 40" role="presentation"><path d="M8 16v8h5l8 6V10l-8 6z"></path><path d="M26 15a7 7 0 0 1 0 10M30 11a12 12 0 0 1 0 18"></path></svg>

        </span>

        <h3><span class="c-word">Communicate</span>Build the case for cities</h3>

        <p>We communicate cities as complex, interconnected systems, making the case for the human and financial capital needed to address urban challenges collectively.</p>

      </article>

    </div>

  </div>

</section>



<!-- ===================== IMPACT ===================== -->

<section class="section alt" id="impact" aria-labelledby="impact-h" style="padding-bottom:clamp(72px,9vw,116px)">

  <div class="wrap">

    <div class="section-head rv in">

      <p class="eyebrow">Impact</p>

      <h2 id="impact-h">What we've built together</h2>

    </div>

  </div>



  <div class="stats" id="stats">

    <div class="wrap">

      <p class="lab">At a glance</p>

      <div class="stats-row">

        <div class="stat pop">

          <span class="s-ico" aria-hidden="true"><svg viewBox="0 0 40 40"><path d="M20 8.5 8 15v10l12 6.5L32 25V15z" opacity=".28"></path><path d="M20 8.5 8 15v10l12 6.5L32 25V15z" fill="none" stroke-width="1.8"></path><path d="M13 12.5h5.4M13 16h5.4M13 19.5h5.4M22 12.5h5M22 16h5M22 19.5h5" stroke-width="1.6"></path></svg></span>

          <span class="s-kick">A network of</span>

          <b data-to="8">8</b>

          <span class="s-cap">Member organisations</span>

        </div>

        <div class="stat pop">

          <span class="s-ico" aria-hidden="true"><svg viewBox="0 0 40 40"><circle cx="20" cy="12" r="4.4"></circle><circle cx="9.5" cy="27" r="4"></circle><circle cx="30.5" cy="27" r="4"></circle><path d="M17 15.5 12 23.5M23 15.5l5 8M13.5 27h13" fill="none" stroke-width="1.8"></path></svg></span>

          <span class="s-kick">Bringing together</span>

          <b data-to="500" data-suffix="+">500+</b>

          <span class="s-cap">Practitioners connected</span>

        </div>

        <div class="stat pop">

          <span class="s-ico" aria-hidden="true"><svg viewBox="0 0 40 40"><path d="M20 5 32 11v2H8v-2z"></path><path d="M11 15v13M17 15v13M23 15v13M29 15v13" fill="none" stroke-width="2.4"></path><path d="M7 30h26v3H7z"></path></svg></span>

          <span class="s-kick">Working with</span>

          <b data-to="200" data-suffix="+">200+</b>

          <span class="s-cap">Government officials engaged</span>

        </div>

        <div class="stat pop">

          <span class="s-ico" aria-hidden="true"><svg viewBox="0 0 40 40"><path d="M20 5c-5.8 0-10.5 4.6-10.5 10.4C9.5 23 20 35 20 35s10.5-12 10.5-19.6C30.5 9.6 25.8 5 20 5z"></path><circle cx="20" cy="15.2" r="3.6" fill="var(--teal-deep)"></circle></svg></span>

          <span class="s-kick">Across</span>

          <b data-to="25" data-suffix="+">25+</b>

          <span class="s-cap">Cities represented</span>

        </div>

      </div>

    </div>

  </div>



  <div class="wrap">

    <div class="impact-grid">

      <article class="i-card rv no-ico in">

        <p class="n">01</p>

        <h3>Bringing policy and lived experience to the same table</h3>

        <p>The Annual Forum brought 100+ participants across 20+ organisations together, including government officials at the Ministry of Housing and Urban Affairs.</p>

      </article>

      <article class="i-card rv d1 no-ico in">

        <p class="n">02</p>

        <h3>Advancing women's voices in urban practice</h3>

        <p>The U-CAN Women's Fellowship's inaugural, all-women cohort of 6 professionals and 2 entrepreneurs worked through embedded practice and mentorship, on projects spanning Gurugram, Bengaluru, Jaipur and Chennai.</p>

      </article>

      <article class="i-card rv d2 no-ico in">

        <p class="n">03</p>

        <h3>Scaling a governance platform across borders</h3>

        <p>The Request for Collaboration initiative turned cross-organisation work into a governance platform now running in 3 Indian cities and Nairobi, Kenya.</p>

      </article>

      <article class="i-card rv d3 no-ico in">

        <p class="n">04</p>

        <h3>Reaching 15,000+ people on urban issues</h3>

        <p>Through 4 podcast episodes, 10+ webinars, and 30+ newsletters, U-CAN has built an audience for substantive conversation about India's urban challenges.</p>

      </article>

    </div>

    <div style="margin-top:clamp(36px,4vw,52px)">

      <a class="btn ghost" href="<?php echo esc_url( home_url( '/impact/' ) ); ?>">See our full impact <span class="arrow" aria-hidden="true">→</span></a>

    </div>

  </div>

</section>



<!-- ===================== OUR MEMBERS ===================== -->

<section class="section" id="members" aria-labelledby="members-h">

  <div class="wrap">

    <div class="section-head rv in" style="margin-bottom:clamp(30px,3.6vw,44px)">

      <p class="eyebrow">Our Members</p>

      <h2 id="members-h" class="vh">Our Members</h2>

    </div>

    <div>

      <ul class="logo-grid" aria-label="U-CAN member organisations">

        <li class="chip rv in"><a href="https://artha.global/" target="_blank" rel="noopener noreferrer" aria-label="Artha Global — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/artha-global-logo-cc6ae7093a.webp" alt="Artha Global logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv in"><a href="https://cprindia.org/" target="_blank" rel="noopener noreferrer" aria-label="Centre for Policy Research — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/centre-for-policy-research-logo-fc8445d7a7.webp" alt="Centre for Policy Research logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d1 in"><a href="https://egov.org.in/" target="_blank" rel="noopener noreferrer" aria-label="eGov Foundation — visit website">

          <img srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/egov-foundation-logo-f623aa609f-800.webp 800w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/egov-foundation-logo-f623aa609f.webp 1024w" sizes="100vw" width="1024" height="260" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/egov-foundation-logo-f623aa609f.webp" alt="eGov Foundation logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d1 in"><a href="https://www.janaagraha.org/" target="_blank" rel="noopener noreferrer" aria-label="Janaagraha — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/janaagraha-logo-985127bfc1.webp" alt="Janaagraha logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d2 in"><a href="https://praja.org/" target="_blank" rel="noopener noreferrer" aria-label="Praja Foundation — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/praja-foundation-logo-7ae1e89a9b.webp" alt="Praja Foundation logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d2 in"><a href="https://www.reapbenefit.org/" target="_blank" rel="noopener noreferrer" aria-label="Reap Benefit — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/reap-benefit-logo-e142694e13.webp" alt="Reap Benefit logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d3 in"><a href="https://shelter-associates.org/" target="_blank" rel="noopener noreferrer" aria-label="Shelter Associates — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/shelter-associates-logo-5ebfb355a7.webp" alt="Shelter Associates logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d3 in"><a href="https://wri-india.org/" target="_blank" rel="noopener noreferrer" aria-label="WRI India — visit website">

          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/wri-india-logo-3052bd5545.svg" alt="WRI India logo" loading="lazy" decoding="async" width="185" height="37">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

      </ul>



      <p class="eyebrow tier-friends">Friends of U-CAN</p>

      <ul class="logo-grid friends" aria-label="Friends of U-CAN">

        <li class="chip rv in"><a href="https://www.mahilahousingtrust.org/" target="_blank" rel="noopener noreferrer" aria-label="Mahila Housing Trust — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/mahila-housing-trust-logo-d50608d443.webp" alt="Mahila Housing Trust logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv in"><a href="https://iisc.ac.in/" target="_blank" rel="noopener noreferrer" aria-label="Indian Institute of Science — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/indian-institute-of-science-logo-13c84eb948.webp" alt="Indian Institute of Science logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d1 in"><a href="https://www.c40.org/" target="_blank" rel="noopener noreferrer" aria-label="C40 Cities — visit website">

          <img width="185" height="115" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/c40-cities-logo-8b3d4f418e.png" alt="C40 Cities logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

      </ul>

    </div>

  </div>

</section>



<!-- ===================== STAY CONNECTED ===================== -->






<?php
get_footer();
