<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<style data-ucan="base">@font-face { font-family: "PS Fallback"; src: local("Helvetica Neue"), local("Arial"); size-adjust: 99%; ascent-override: 92%; descent-override: 24%; line-gap-override: 0%; }

@font-face { font-family: "Arch Fallback"; src: local("Arial Bold"), local("Helvetica Neue Bold"), local("Arial"); size-adjust: 104%; ascent-override: 90%; descent-override: 22%; line-gap-override: 0%; }

:root { --paper: #FBFAF6; --paper-alt: #E9F5F2; --ink: #222120; --ink-soft: #57564F; --teal: #1F8F7B; --teal-text: #177A69; --teal-deep: #0E5348; --teal-light: #4EC6B2; --lime: #CDDE71; --orange: #FEAE00; --line: #DCEAE6; --wrap: 1240px; --gutter: clamp(20px,4.5vw,52px); --sans: 'Public Sans','PS Fallback',system-ui,-apple-system,Segoe UI,Roboto,sans-serif; --display: 'Archivo','Arch Fallback',system-ui,Segoe UI,Arial,sans-serif; --shadow: 0 2px 4px rgba(14,83,72,.05),0 14px 40px -20px rgba(14,83,72,.28); --e: cubic-bezier(.22,.61,.36,1); }

*, ::before, ::after { box-sizing: border-box; }

html { text-size-adjust: 100%; scroll-behavior: smooth; scroll-padding-top: 92px; }

body { margin: 0px; background: var(--paper); color: var(--ink); font-family: var(--sans); font-size: 17px; line-height: 1.62; -webkit-font-smoothing: antialiased; text-rendering: optimizelegibility; overflow-x: hidden; }

h1, h2, h3, h4 { font-family: var(--display); font-weight: 700; letter-spacing: -0.012em; margin: 0px; line-height: 1.12; }

p { margin: 0px; }

a { color: inherit; }

img, svg { display: block; max-width: 100%; }

ul { margin: 0px; padding: 0px; list-style: none; }

:focus-visible { outline: 2.5px solid var(--teal-deep); outline-offset: 3px; border-radius: 2px; }

::selection { background: var(--lime); color: var(--ink); }

.wrap { max-width: var(--wrap); margin: 0px auto; padding: 0 var(--gutter); }

.skip { position: absolute; left: -9999px; top: 0px; background: var(--teal-deep); color: var(--paper); padding: 12px 20px; z-index: 200; font-weight: 600; }

.skip:focus { left: 12px; top: 12px; }

.vh { width: 1px; height: 1px; padding: 0px; margin: -1px; overflow: hidden; clip: rect(0px, 0px, 0px, 0px); white-space: nowrap; border: 0px; position: absolute !important; }

.kicker { font-family: var(--sans); font-weight: 700; font-size: 12.5px; letter-spacing: 0.16em; text-transform: uppercase; color: var(--teal-text); margin: 0px; display: inline-flex; align-items: center; gap: 12px; }

.kicker::before { content: attr(data-num); font-family: var(--display); font-size: 12.5px; letter-spacing: 0px; color: var(--teal); opacity: 0.85; }

.kicker.on-dark { color: var(--lime); }

.kicker.on-dark::before { color: var(--lime); opacity: 0.7; }

.btn { display: inline-flex; align-items: center; gap: 11px; padding: 16px 30px; background: var(--teal-deep); color: var(--paper); font-weight: 600; font-size: 15px; text-decoration: none; border: 1.5px solid var(--teal-deep); border-radius: 2px; transition: background .22s var(--e),border-color .22s var(--e),transform .22s var(--e),box-shadow .22s var(--e); }

.btn:hover { background: var(--ink); border-color: var(--ink); transform: translateY(-2px); box-shadow: var(--shadow); }

.btn .ar { transition: transform .22s var(--e); }

.btn:hover .ar { transform: translateX(5px); }

.btn.line { background: transparent; color: var(--teal-deep); border-color: var(--teal-deep); }

.btn.line:hover { background: var(--teal-deep); color: var(--paper); }

.btn.on-photo { background: var(--paper); color: var(--teal-deep); border-color: var(--paper); }

.btn.on-photo:hover { background: var(--lime); border-color: var(--lime); color: var(--ink); }

.btn.ghost-photo { background: transparent; color: rgb(255, 255, 255); border-color: rgba(255, 255, 255, 0.6); }

.btn.ghost-photo:hover { background: rgba(255, 255, 255, 0.14); border-color: rgb(255, 255, 255); transform: translateY(-2px); }

.bar { position: sticky; top: 0px; z-index: 90; background: rgba(251, 250, 246, 0.9); backdrop-filter: saturate(180%) blur(12px); border-bottom: 1px solid var(--line); transition: box-shadow .25s var(--e); }

.bar.scrolled { box-shadow: rgba(14, 83, 72, 0.4) 0px 1px 22px -10px; }

.bar-in { max-width: var(--wrap); margin: 0px auto; padding: 0 var(--gutter); height: 78px; display: flex; align-items: center; justify-content: space-between; gap: 24px; }

.brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }

.brand img { width: 38px; height: 38px; object-fit: contain; flex: 0 0 38px; }

.brand b { font-family: var(--display); font-weight: 800; font-size: 21px; letter-spacing: -0.02em; line-height: 1; display: block; }

.brand small { display: block; font-weight: 600; font-size: 8.4px; letter-spacing: 0.13em; text-transform: uppercase; color: var(--ink-soft); margin-top: 3px; }

.nav { display: flex; gap: 30px; align-items: center; }

.nav a { font-size: 14.5px; font-weight: 600; text-decoration: none; color: var(--ink-soft); position: relative; padding: 8px 0px; transition: color .18s var(--e); }

.nav a::after { content: ""; position: absolute; left: 0px; right: 100%; bottom: 2px; height: 2px; background: var(--teal); transition: right .3s var(--e); }

.nav a:hover, .nav a.on { color: var(--ink); }

.nav a:hover::after, .nav a.on::after { right: 0px; }

.bar-cta { display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; text-decoration: none; color: var(--paper); background: var(--teal-deep); padding: 11px 20px; border-radius: 2px; border: 1.5px solid var(--teal-deep); transition: background .2s var(--e),transform .2s var(--e); }

.bar-cta:hover { background: var(--ink); border-color: var(--ink); transform: translateY(-2px); }

.burger { display: none; width: 46px; height: 46px; border: 1px solid var(--line); background: transparent; border-radius: 2px; cursor: pointer; padding: 0px; align-items: center; justify-content: center; }

.burger span, .burger span::before, .burger span::after { display: block; width: 19px; height: 1.7px; background: var(--ink); transition: transform .25s var(--e),background .2s; }

.burger span { position: relative; }

.burger span::before, .burger span::after { content: ""; position: absolute; left: 0px; }

.burger span::before { top: -6px; }

.burger span::after { top: 6px; }

.burger[aria-expanded="true"] span { background: transparent; }

.burger[aria-expanded="true"] span::before { transform: translateY(6px) rotate(45deg); }

.burger[aria-expanded="true"] span::after { transform: translateY(-6px) rotate(-45deg); }

.msheet { display: none; border-top: 1px solid var(--line); background: var(--paper); }

.msheet.open { display: block; }

.msheet a { display: block; padding: 16px 0px; border-bottom: 1px solid var(--line); text-decoration: none; font-weight: 600; font-size: 16px; color: var(--ink); }

.msheet a:last-child { border-bottom: 0px; }

.hero { position: relative; background: var(--teal-deep); color: rgb(255, 255, 255); overflow: hidden; }

.hero::before { content: ""; position: absolute; inset: 0px; opacity: 0.5; background-image: radial-gradient(circle at 1px 1px, rgba(205, 222, 113, 0.16) 1.4px, transparent 0px); background-size: 26px 26px; }

.hero::after { content: ""; position: absolute; right: -140px; top: -140px; width: 440px; height: 440px; border-radius: 50%; background: radial-gradient(circle, rgba(78, 198, 178, 0.28), transparent 62%); }

.hero-in { position: relative; z-index: 1; max-width: var(--wrap); margin: 0px auto; padding: clamp(74px,11vw,128px) var(--gutter) clamp(56px,8vw,92px); }

.crumb { display: flex; gap: 9px; align-items: center; font-size: 12.5px; font-weight: 600; color: rgb(174, 203, 195); margin-bottom: 26px; }

.crumb a { color: rgb(174, 203, 195); text-decoration: none; transition: color 0.18s; }

.crumb a:hover { color: var(--lime); }

.crumb span { color: rgb(110, 148, 139); }

.hero-tag { display: inline-flex; align-items: center; gap: 10px; font-size: 12px; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; color: var(--lime); margin-bottom: 22px; }

.hero-tag::before { content: ""; width: 26px; height: 2px; background: var(--lime); }

.hero h1 { font-size: clamp(40px, 6.4vw, 76px); line-height: 1.02; letter-spacing: -0.03em; color: rgb(255, 255, 255); }

.hero-lede { margin-top: 26px; font-size: clamp(17px, 1.7vw, 20px); line-height: 1.6; color: rgb(228, 239, 235); max-width: 64ch; }

.hero-lede b { color: rgb(255, 255, 255); font-weight: 600; }

.sec { padding: clamp(68px, 8.5vw, 116px) 0px; position: relative; }

.sec.alt { background: var(--paper-alt); }

.sec-head { display: grid; grid-template-columns: auto 1fr; gap: clamp(20px, 4vw, 56px); align-items: end; margin-bottom: clamp(38px, 5vw, 60px); }

.sec-num { font-family: var(--display); font-weight: 800; font-size: clamp(48px, 8vw, 104px); line-height: 0.8; color: var(--line); letter-spacing: -0.04em; user-select: none; }

.sec.alt .sec-num { color: rgb(207, 230, 223); }

.sec-head h2 { font-size: clamp(27px, 3.6vw, 40px); letter-spacing: -0.02em; max-width: 22ch; }

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
  .sec-num { font-size: clamp(40px, 14vw, 64px); }
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
}</style>

<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<link rel="preload" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/fonts/archivo-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/fonts/public-sans-latin.woff2" as="font" type="font/woff2" crossorigin>
<style data-ucan="fonts">@font-face{font-family:'Archivo';font-style:normal;font-weight:500 800;font-display:swap;src:url(<?php echo esc_url( get_template_directory_uri() ); ?>/assets/fonts/archivo-latin-ext.woff2) format('woff2');unicode-range:U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF}@font-face{font-family:'Archivo';font-style:normal;font-weight:500 800;font-display:swap;src:url(<?php echo esc_url( get_template_directory_uri() ); ?>/assets/fonts/archivo-latin.woff2) format('woff2');unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD}@font-face{font-family:'Public Sans';font-style:italic;font-weight:400;font-display:swap;src:url(<?php echo esc_url( get_template_directory_uri() ); ?>/assets/fonts/public-sans-italic-latin-ext.woff2) format('woff2');unicode-range:U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF}@font-face{font-family:'Public Sans';font-style:italic;font-weight:400;font-display:swap;src:url(<?php echo esc_url( get_template_directory_uri() ); ?>/assets/fonts/public-sans-italic-latin.woff2) format('woff2');unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD}@font-face{font-family:'Public Sans';font-style:normal;font-weight:400 700;font-display:swap;src:url(<?php echo esc_url( get_template_directory_uri() ); ?>/assets/fonts/public-sans-latin-ext.woff2) format('woff2');unicode-range:U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF}@font-face{font-family:'Public Sans';font-style:normal;font-weight:400 700;font-display:swap;src:url(<?php echo esc_url( get_template_directory_uri() ); ?>/assets/fonts/public-sans-latin.woff2) format('woff2');unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD}</style>

<link rel="icon" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/favicon-78b77e29b1.png">
<link rel="preconnect" href="https://urban.org.in/">

<style data-ucan="chrome">

/* ——— site chrome: one header + one footer on every page ———

   Self-contained on purpose: three different base64 stylesheets exist across

   the site, so nothing here may depend on page-specific custom properties. */

.bar{position:sticky;top:0;z-index:90;background:rgba(251,250,246,.92);

  -webkit-backdrop-filter:saturate(180%) blur(12px);backdrop-filter:saturate(180%) blur(12px);

  border-bottom:1px solid var(--line,#DCEAE6);transition:box-shadow .25s cubic-bezier(.22,.61,.36,1)}

.bar.scrolled{box-shadow:0 1px 22px -10px rgba(14,83,72,.4)}

.bar-in{max-width:1240px;margin:0 auto;padding:0 clamp(20px,4.5vw,52px);height:78px;

  display:flex;align-items:center;justify-content:space-between;gap:24px}

.bar .brand{display:flex;align-items:center;gap:12px;text-decoration:none;color:var(--ink,#222120)}

.bar .brand img{width:38px;height:38px;object-fit:contain;flex:0 0 38px}

.bar .brand b{font-family:var(--display,'Archivo',Arial,sans-serif);font-weight:800;font-size:21px;

  letter-spacing:-.02em;line-height:1;display:block}

.bar .brand small{display:block;font-weight:600;font-size:8.4px;letter-spacing:.13em;

  text-transform:uppercase;color:var(--ink-soft,#57564F);margin-top:3px}

.bar-cta{display:inline-flex;align-items:center;gap:8px;font-family:var(--sans,'Public Sans',sans-serif);

  font-size:14px;font-weight:700;text-decoration:none;color:var(--paper,#FBFAF6);

  background:var(--teal-deep,#0E5348);padding:11px 20px;border-radius:2px;white-space:nowrap;

  border:1.5px solid var(--teal-deep,#0E5348);transition:background .2s,transform .2s}

.bar-cta:hover{background:var(--ink,#222120);border-color:var(--ink,#222120);transform:translateY(-2px)}

/* Neutralise anything the page's own stylesheet says about these elements —

   three different base64 stylesheets are in play and two of them still carry

   rules from when .nav / .header WAS that page's header. */

.bar .ucnav-bar{margin:0;padding:0;border:0;background:none;box-shadow:none;

  height:auto;line-height:normal;position:static;display:block}

.bar .brand span{line-height:1}

.bar-cta{line-height:1;height:44px;letter-spacing:0}

.burger{display:none;width:46px;height:46px;border:1px solid var(--line,#DCEAE6);background:transparent;

  border-radius:2px;cursor:pointer;padding:0;align-items:center;justify-content:center;flex:0 0 46px}

.burger span,.burger span::before,.burger span::after{display:block;width:19px;height:1.7px;

  background:var(--ink,#222120);transition:transform .25s cubic-bezier(.22,.61,.36,1),background .2s}

.burger span{position:relative}

.burger span::before,.burger span::after{content:"";position:absolute;left:0}

.burger span::before{top:-6px}

.burger span::after{top:6px}

.burger[aria-expanded="true"] span{background:transparent}

.burger[aria-expanded="true"] span::before{transform:translateY(6px) rotate(45deg)}

.burger[aria-expanded="true"] span::after{transform:translateY(-6px) rotate(-45deg)}

.msheet{display:none;border-top:1px solid var(--line,#DCEAE6);background:var(--paper,#FBFAF6)}

.msheet.open{display:block}

.msheet>.wrap{max-width:1240px;margin:0 auto;padding:8px clamp(20px,4.5vw,52px) 20px}

@media(max-width:1100px){

  .bar .ucnav-bar{display:none}

  .burger{display:inline-flex}

}

@media(max-width:420px){

  .bar-cta{display:none}

}



/* ——— cookie banner ———

   Bottom-of-viewport popup. Defined here (not in a page stylesheet) so it

   behaves identically on all 148 pages. */

.cc{position:fixed;left:0;right:0;bottom:0;z-index:120;

  background:var(--paper,#FBFAF6);border-top:3px solid var(--teal-deep,#0E5348);

  box-shadow:0 -8px 40px -18px rgba(14,83,72,.5);

  transform:translateY(110%);visibility:hidden;

  transition:transform .45s cubic-bezier(.22,.61,.36,1),visibility .45s;

  font-family:var(--sans,'Public Sans',sans-serif)}

.cc.show{transform:none;visibility:visible}

.cc-in{max-width:1240px;margin:0 auto;padding:20px clamp(20px,4.5vw,52px);

  display:grid;grid-template-columns:1fr auto;gap:24px;align-items:center}

.cc-in>*{min-width:0}

.cc-badge{display:inline-flex;align-items:center;gap:7px;font-size:11px;font-weight:700;

  letter-spacing:.08em;text-transform:uppercase;color:var(--teal-text,#177A69);margin-bottom:7px}

.cc-badge::before{content:"";width:7px;height:7px;border-radius:50%;background:var(--teal,#1F8F7B)}

.cc h2{font-family:var(--display,'Archivo',Arial,sans-serif);font-size:16px;margin:0 0 6px;

  color:var(--ink,#222120);line-height:1.3}

.cc p{margin:0;font-size:13.5px;line-height:1.6;color:var(--ink-soft,#57564F);max-width:82ch}

.cc p a{color:var(--teal-text,#177A69);font-weight:600}

.cc-act{display:flex;gap:10px;flex-wrap:wrap}

.cc-btn{padding:11px 20px;font-family:inherit;font-size:14px;font-weight:700;line-height:1;

  border-radius:2px;cursor:pointer;border:1.5px solid var(--teal-deep,#0E5348);

  background:var(--teal-deep,#0E5348);color:var(--paper,#FBFAF6);white-space:nowrap;

  transition:transform .2s,background .2s}

.cc-btn:hover{transform:translateY(-2px)}

.cc-btn.alt{background:transparent;color:var(--teal-deep,#0E5348);border-color:var(--line,#DCEAE6)}

.cc-btn.alt:hover{border-color:var(--teal,#1F8F7B)}

@media(max-width:720px){

  .cc-in{grid-template-columns:1fr;gap:14px}

  .cc-act{width:100%}

  .cc-btn{flex:1 1 auto}

}



/* ——— footer ——— */

footer{background:var(--ink,#222120);color:#B9C4BF;padding:clamp(52px,6vw,72px) 0 26px;

  border-top:1px solid rgba(255,255,255,.08);font-family:var(--sans,'Public Sans',sans-serif)}

footer>.wrap{max-width:1240px;margin:0 auto;padding:0 clamp(20px,4.5vw,52px)}

.f-grid{display:grid;grid-template-columns:1.35fr .85fr .85fr 1.5fr;gap:clamp(26px,3.4vw,46px);

  padding-bottom:42px;border-bottom:1px solid rgba(255,255,255,.1)}

.f-grid>*{min-width:0}

footer .brand{display:flex;align-items:center;gap:12px;text-decoration:none;color:#fff}

footer .brand img{width:38px;height:38px;object-fit:contain;flex:0 0 38px}

footer .brand b{font-family:var(--display,'Archivo',Arial,sans-serif);font-weight:800;font-size:21px;

  letter-spacing:-.02em;line-height:1;display:block;color:#fff}

footer .brand small{display:block;font-weight:600;font-size:8.4px;letter-spacing:.13em;

  text-transform:uppercase;color:#8CA098;margin-top:3px}

footer .blurb{max-width:300px;margin:20px 0 0;font-size:14.5px;color:#8CA098;line-height:1.6}

footer h4{font-family:var(--sans,'Public Sans',sans-serif);font-weight:700;font-size:12.5px;

  letter-spacing:.1em;text-transform:uppercase;color:#fff;margin:0 0 18px}

footer ul{margin:0;padding:0;list-style:none}

footer li{margin-bottom:11px;list-style:none}

footer a{text-decoration:none;font-size:14.5px;color:#B9C4BF;transition:color .18s}

footer a:hover{color:var(--lime,#CDDE71)}

.f-subtext{margin:0 0 16px;font-size:14px;line-height:1.6;color:#8CA098}

.fnrow{display:flex;gap:8px;flex-wrap:wrap}

.fnrow input{flex:1 1 150px;min-width:0;padding:11px 13px;font-family:inherit;font-size:14.5px;

  color:#fff;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.22);border-radius:2px}

.fnrow input::placeholder{color:#8CA098}

.fnrow input:focus{outline:2px solid var(--lime,#CDDE71);outline-offset:1px}

.fnrow button{padding:11px 20px;font-family:inherit;font-size:14px;font-weight:700;cursor:pointer;

  color:var(--ink,#222120);background:var(--lime,#CDDE71);border:1.5px solid var(--lime,#CDDE71);

  border-radius:2px;white-space:nowrap;transition:transform .2s}

.fnrow button:hover{transform:translateY(-2px)}

.fconsent{display:grid;grid-template-columns:auto 1fr;gap:10px;margin-top:13px;align-items:start}

.fconsent input{margin-top:3px;width:16px;height:16px;flex:0 0 16px;accent-color:var(--teal,#1F8F7B)}

.fconsent label{font-size:12.5px;line-height:1.55;color:#8CA098;min-width:0}

.fconsent label a{font-size:12.5px;color:var(--lime,#CDDE71)}

.f-sub .nerr,.f-sub .nok{display:none;margin:12px 0 0;font-size:13px;line-height:1.5;font-weight:600}

.f-sub .nerr{color:#FFC9B0}

.f-sub .nok{color:var(--lime,#CDDE71)}

.f-legal{margin:13px 0 0;font-size:11.5px;line-height:1.55;color:#75857E}

.f-legal a{font-size:11.5px;color:#8CA098}

.f-social{display:flex;flex-wrap:wrap;gap:8px 18px;margin-top:20px !important}

.f-social li{margin-bottom:0}

.f-bottom{display:flex;justify-content:space-between;flex-wrap:wrap;gap:12px;padding-top:24px;

  font-size:13px;color:#7C8B85}

.f-bottom a{color:#7C8B85;margin-left:18px;font-size:13px}

.cookie-link{margin-left:18px;background:none;border:0;padding:0;font-family:inherit;font-size:13px;

  color:#7C8B85;cursor:pointer;text-decoration:underline}

.cookie-link:hover{color:var(--lime,#CDDE71)}

@media(max-width:1040px){

  .f-grid{grid-template-columns:1fr 1fr;row-gap:36px}

  .f-sub{grid-column:1/-1}

}

@media(max-width:560px){

  .f-grid{grid-template-columns:1fr}

  .f-bottom{flex-direction:column;gap:8px}

  .f-bottom a,.cookie-link{margin-left:0;margin-right:16px}

}

</style>
<style data-ucan="nav">

/* ——— site nav: 5 top-level items with dropdowns (mirrors urban.org.in IA) ——— */

.ucnav-bar{display:block}

.ucnav{display:flex;align-items:center;gap:clamp(2px,1.1vw,14px);flex-wrap:nowrap}

.ucnav-i{position:relative;display:flex;align-items:center}

.ucnav-t{display:inline-flex;align-items:center;gap:6px;white-space:nowrap;

  font-family:var(--sans);font-size:14.5px;font-weight:600;line-height:1;

  color:var(--ink);text-decoration:none;background:none;border:0;cursor:pointer;

  padding:10px 10px;border-radius:2px;transition:color .18s ease,background .18s ease}

.ucnav-t:hover,.ucnav-i:hover>.ucnav-t{color:var(--teal-deep);background:rgba(31,143,123,.07)}

.ucnav-t.on{color:var(--teal-deep)}

.ucnav-t.on::after{content:"";position:absolute;left:10px;right:10px;bottom:2px;height:2px;background:var(--teal)}

.ucnav-i>.ucnav-t.on::after{left:10px;right:22px}

.ucnav-cv{width:9px;height:6px;flex:0 0 9px;transition:transform .2s ease;opacity:.75}

.ucnav-i:hover .ucnav-cv,.ucnav-i.open .ucnav-cv{transform:rotate(180deg)}

.ucnav-dd{position:absolute;top:100%;left:0;min-width:246px;padding:8px;

  background:var(--paper,#FBFAF6);border:1px solid var(--line);

  box-shadow:0 18px 44px -22px rgba(14,83,72,.55);

  opacity:0;visibility:hidden;transform:translateY(-6px);

  transition:opacity .18s ease,transform .18s ease,visibility .18s;z-index:80}

.ucnav-i:hover .ucnav-dd,.ucnav-i.open .ucnav-dd,.ucnav-i:focus-within .ucnav-dd{opacity:1;visibility:visible;transform:translateY(0)}

.ucnav-dd a{display:block;padding:10px 12px;font-family:var(--sans);font-size:14px;font-weight:500;

  line-height:1.35;color:var(--ink-soft);text-decoration:none;border-radius:2px}

.ucnav-dd a:hover,.ucnav-dd a:focus-visible{background:var(--paper-alt,#E9F5F2);color:var(--teal-deep)}

.ucnav-i:last-child .ucnav-dd{left:auto;right:0}

.ucnav-gh{margin:8px 12px 4px;padding-top:9px;border-top:1px solid var(--line);

  font-family:var(--sans);font-weight:700;font-size:10.5px;letter-spacing:.13em;

  text-transform:uppercase;color:var(--ink-soft);opacity:.8}

.ucnav-dd .ucnav-gh+a{padding-left:12px}



/* mobile sheet */

.ucmob{display:flex;flex-direction:column;gap:2px}

.ucmob-t{display:block;padding:13px 0;font-family:var(--display);font-weight:700;

  font-size:19px;color:var(--ink);text-decoration:none;border-bottom:1px solid var(--line)}

.ucmob-g{border-bottom:1px solid var(--line);padding:13px 0}

.ucmob-h{font-family:var(--display);font-weight:700;font-size:19px;color:var(--ink);margin:0 0 6px}

.ucmob-l{display:flex;flex-direction:column}

.ucmob-l a{padding:8px 0 8px 14px;font-family:var(--sans);font-size:15px;font-weight:500;

  color:var(--ink-soft);text-decoration:none;border-left:2px solid var(--line)}

.ucmob-l a:hover{color:var(--teal-deep);border-left-color:var(--teal)}

.ucmob-gh{margin:12px 0 4px;font-family:var(--sans);font-weight:700;font-size:10.5px;

  letter-spacing:.13em;text-transform:uppercase;color:var(--ink-soft);opacity:.8}

.ucmob-cta{color:var(--teal-deep)}

</style>
<style data-ucan="rhythm">

/* ——— vertical rhythm ——— */

.crumb{display:none}.art-link,.letter a,.art a,.feature a{overflow-wrap:anywhere}.letter p,.art p,.feature p,.stat-desc,.letter h3{overflow-wrap:break-word}.nl-fig{margin:0 0 1.5rem;border-radius:8px;overflow:hidden;background:var(--mint,#E9F5F2);border:1px solid var(--line,#DCEAE6)}.nl-fig img{display:block;width:100%;height:auto}.art .nl-fig{margin-bottom:1.25rem}.nl-fig figcaption{padding:.6rem 1rem;font-size:.75rem;font-weight:600;color:var(--ink-soft,#57564F);background:var(--paper,#FBFAF6)}.chip img{filter:none!important;opacity:1!important}.proof-quote{--wash:rgba(31,143,123,.10)!important;--acc:#1F8F7B!important}.ptrow .pt{min-height:150px!important}.ptrow .pt img{max-height:96px!important}.mgrid a{min-height:150px!important}.mgrid img{max-height:88px!important}.sec-head h2{font-size:clamp(27px,3.6vw,40px)!important;letter-spacing:-.02em!important;max-width:22ch}.brand img{width:auto!important;height:auto!important;flex:0 0 auto!important}.vm-card{display:flex;flex-direction:column}.vm-card .brochure-btn{margin-top:auto;align-self:flex-start;padding-top:14px}.vm-card .vm-text+.brochure-btn,.vm-card .brochure-btn{margin-top:auto}@media(min-width:1001px){.vm-card .brochure-btn{margin-top:auto}.vm-card{min-height:100%}}.bar .brand img{width:168px!important;max-width:168px!important}footer .brand img{width:178px!important;max-width:178px!important}.bar-in{height:92px}.ucnav-t{font-size:16px}.bar-cta{font-size:15px;height:46px;padding:0 22px}html{scroll-padding-top:108px}@media(max-width:1100px){.bar .brand img{width:148px!important;max-width:148px!important}.bar-in{height:80px}html{scroll-padding-top:96px}}@media(max-width:560px){.bar .brand img{width:130px!important;max-width:130px!important}.bar-in{height:70px}}.sec{padding:clamp(52px,5.6vw,80px) 0}

.section{padding:clamp(54px,5.8vw,84px) 0}

.sec-head{grid-template-columns:1fr;margin-bottom:clamp(26px,3vw,38px)}

.sec-head>*{min-width:0}

.section-head{max-width:74ch}



/* a heading sitting directly on a full-bleed band needs far less air below it */

.sec:has(+.impact-band),.sec[style*="padding-bottom:0"]{padding-bottom:0}

.sec[style*="padding-bottom:0"] .sec-head{margin-bottom:clamp(20px,2.4vw,30px)}

.impact-band,.stat-band{padding:clamp(34px,4vw,52px) 0}



/* a text column next to a tall diagram: centre it rather than leaving the

   diagram's lower half beside empty space */

.split-mid{align-items:center}

.split-mid .fw{min-height:0}



/* session dates wrapped onto two lines ("February 21, 2025") */

@media(min-width:901px){.sdate{width:11.5em;white-space:nowrap}}



/* the hero already carries generous padding; don't stack another full gap on it */

.hero+.sec,.hero+.section{padding-top:clamp(44px,4.8vw,68px)}

</style>
<style data-ucan="fallback">@font-face{font-family:"Arch Fallback";src:local("Arial Bold"),local("Arial-BoldMT"),local("Helvetica Neue Bold");font-weight:500;size-adjust:93.4%;ascent-override:90%;descent-override:22%;line-gap-override:0%}@font-face{font-family:"Arch Fallback";src:local("Arial Bold"),local("Arial-BoldMT"),local("Helvetica Neue Bold");font-weight:600;size-adjust:95.5%;ascent-override:90%;descent-override:22%;line-gap-override:0%}@font-face{font-family:"Arch Fallback";src:local("Arial Bold"),local("Arial-BoldMT"),local("Helvetica Neue Bold");font-weight:700;size-adjust:99%;ascent-override:90%;descent-override:22%;line-gap-override:0%}@font-face{font-family:"Arch Fallback";src:local("Arial Bold"),local("Arial-BoldMT"),local("Helvetica Neue Bold");font-weight:800;size-adjust:104.3%;ascent-override:90%;descent-override:22%;line-gap-override:0%}@font-face{font-family:"PS Fallback";src:local("Arial"),local("ArialMT"),local("Helvetica Neue");font-weight:400;size-adjust:105.3%;ascent-override:92%;descent-override:24%;line-gap-override:0%}@font-face{font-family:"PS Fallback";src:local("Arial"),local("ArialMT"),local("Helvetica Neue");font-weight:500;size-adjust:106.1%;ascent-override:92%;descent-override:24%;line-gap-override:0%}@font-face{font-family:"PS Fallback";src:local("Arial Bold"),local("Arial-BoldMT"),local("Helvetica Neue Bold");font-weight:600;size-adjust:98.9%;ascent-override:92%;descent-override:24%;line-gap-override:0%}@font-face{font-family:"PS Fallback";src:local("Arial Bold"),local("Arial-BoldMT"),local("Helvetica Neue Bold");font-weight:700;size-adjust:100.3%;ascent-override:92%;descent-override:24%;line-gap-override:0%}.hero-lede{max-width:39.2em}.sec-head h2{max-width:13.1em}.section-head{max-width:45.3em}.post-hero img{aspect-ratio:16/9;height:auto}</style>
<style data-ucan="type">
/* form controls don't inherit font-family from the page; without this a
   <button class="btn"> falls back to the UA default (Arial) while the
   same class on an <a> renders in Public Sans */
button,input,select,textarea{font-family:inherit}

/* kickers inside a .body column lost their styling to `.body p` */
main p.kicker{font-family:var(--sans,'Public Sans',sans-serif)!important;font-size:12.5px!important;line-height:1.62!important;font-weight:700!important;letter-spacing:.14em!important;text-transform:uppercase!important;color:var(--teal-text,#177A69)!important}
main .on-dark.kicker,main .kicker.on-dark{color:var(--lime,#CDDE71)!important}

/* One type scale and one bullet, applied last so it settles the differences between the shell pages, the newsletter template and the imported WordPress bodies. Classed paragraphs (kickers, leads, captions) keep their own role sizes. */
main h1{font-family:var(--display,Archivo)!important;font-weight:700!important;font-size:clamp(38px,6.4vw,76px)!important;line-height:1.05!important;letter-spacing:-.03em}
main h2{font-family:var(--display,Archivo)!important;font-weight:700!important;font-size:clamp(27px,3.6vw,40px)!important;line-height:1.18!important;letter-spacing:-.02em}
main h3{font-family:var(--display,Archivo)!important;font-weight:700!important;font-size:clamp(18px,1.9vw,21px)!important;line-height:1.32!important;letter-spacing:-.01em}
main p:not([class]){font-size:17px!important;line-height:1.62!important}
/* prose lists had list-style:none with no marker, so bullets were invisible; match the .blist component the rest of the site uses */
main ul:not([class]){list-style:none;margin:18px 0 0;padding:0}
main ul:not([class])>li{list-style:none!important;display:block!important;position:relative;padding:11px 0 11px 20px;border-top:1px dashed var(--line,#DCEAE6);font-size:16px;color:var(--ink-soft,#57564F);line-height:1.62;min-width:0;overflow-wrap:break-word}
main ul:not([class])>li:first-child{border-top:0}
main ul:not([class])>li::before{content:"";position:absolute;left:0;top:calc(11px + .81em - 3.5px);width:7px;height:7px;background:var(--teal,#1F8F7B);border-radius:50%}
main ol:not([class]){margin:18px 0 0;padding-left:22px}
main ol:not([class])>li{font-size:16px;color:var(--ink-soft,#57564F);line-height:1.62;padding:5px 0}
main ol:not([class])>li::marker{color:var(--teal,#1F8F7B);font-weight:700}
</style>
<style data-ucan="heads">.sec-head:has(>.kicker:first-child){row-gap:0!important}.sec-head>.kicker:first-child+:is(h2,h3){margin-top:2px!important}.sec-head>:is(h2,h3)+.lead{margin-top:16px!important}main h3.fx-h3-lg{font-size:clamp(27px,3.6vw,40px)!important;line-height:1.18!important;letter-spacing:-.02em!important}</style>
<style>

/* 7-item nav needs more room than the shell's 860px burger breakpoint allowed */

@media (max-width:1100px){.bar .nav,.bar .bar-cta{display:none}.bar .burger{display:flex}}



</style>

<?php
/**
 * Per-page <title>/meta description/canonical/OG/JSON-LD are added on
 * wp_head by ucan_document_head() in functions.php (title-tag support +
 * canonical are WP core; description/OG/JSON-LD are this theme's own hook).
 * Phase 0 covers the shared Organization graph node only - Person/Article/
 * ProfilePage/FAQPage nodes are added per template in later phases.
 */
wp_head();
?>
</head>
<body <?php body_class(); ?>>
<a class="skip" href="#main">Skip to main content</a>

<header class="bar" id="bar">

  <div class="bar-in">

    <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="U-CAN — Urban Collective Action Network, home">

      <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/u-can-urban-collective-action-network-08e2484273.svg" width="107" height="40" alt="U-CAN — Urban Collective Action Network" fetchpriority="high" decoding="async">

    </a>

    <?php ucan_nav_menu( 'desktop' ); ?>

    <a class="bar-cta" href="<?php echo esc_url( home_url( '/newsletter/#subscribe' ) ); ?>">Subscribe</a>

    <button class="burger" id="burger" aria-expanded="false" aria-controls="msheet" aria-label="Open navigation menu"><span></span></button>

  </div>

  <?php ucan_nav_menu( 'mobile' ); ?>
</header>

<main id="main">
