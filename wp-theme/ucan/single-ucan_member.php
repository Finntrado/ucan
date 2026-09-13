<?php
/**
 * Phase 3: single member profile (was profile-<slug>.html, CLAUDE.md §14).
 * Layout lifted verbatim from profile-siddharth-pandit.html /
 * profile-gautham-ravichander.html's <main> - the two display branches
 * ("Our Team" vs everyone else) are computed once by
 * ucan_member_display_fields() in functions.php and used identically here.
 *
 * Bio paragraphs are the post_content (editable in wp-admin as normal);
 * everything else (role/org/LinkedIn/group) is the meta + taxonomy set up
 * in functions.php's ucan_register_member_cpt().
 */

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

.prof-hero { position: relative; background: var(--teal-deep); color: rgb(255, 255, 255); overflow: hidden; }

.prof-hero::before { content: ""; position: absolute; inset: 0px; opacity: 0.5; background-image: radial-gradient(circle at 1px 1px, rgba(205, 222, 113, 0.16) 1.4px, transparent 0px); background-size: 26px 26px; }

.prof-hero::after { content: ""; position: absolute; right: -140px; top: -120px; width: 420px; height: 420px; border-radius: 50%; background: radial-gradient(circle, rgba(78, 198, 178, 0.26), transparent 62%); }

.prof-in { position: relative; z-index: 1; max-width: var(--wrap); margin: 0px auto; padding: clamp(70px,10vw,110px) var(--gutter) clamp(48px,6vw,72px); display: grid; grid-template-columns: auto 1fr; gap: clamp(28px, 5vw, 64px); align-items: center; }

.prof-photo { position: relative; flex: 0 0 auto; }

.prof-photo img, .prof-photo .mono { width: clamp(160px, 20vw, 220px); height: clamp(160px, 20vw, 220px); border-radius: 50%; object-fit: cover; object-position: center 22%; border: 4px solid rgba(255, 255, 255, 0.14); box-shadow: rgba(0, 0, 0, 0.5) 0px 20px 50px -20px; }

.prof-photo .mono { display: flex; align-items: center; justify-content: center; font-family: var(--display); font-weight: 800; font-size: 64px; color: var(--teal-deep); background: var(--lime); }

.prof-photo .badge { position: absolute; bottom: 6px; right: 6px; background: var(--lime); color: var(--ink); font-family: var(--sans); font-weight: 700; font-size: 11px; letter-spacing: 0.06em; text-transform: uppercase; padding: 6px 12px; border-radius: 100px; box-shadow: rgba(0, 0, 0, 0.4) 0px 6px 18px -6px; }

.prof-crumb { display: flex; gap: 9px; align-items: center; font-size: 12.5px; font-weight: 600; color: rgb(174, 203, 195); margin-bottom: 20px; flex-wrap: wrap; }

.prof-crumb a { color: rgb(174, 203, 195); text-decoration: none; transition: color 0.18s; }

.prof-crumb a:hover { color: var(--lime); }

.prof-crumb span { color: rgb(110, 148, 139); }

.prof-tag { display: inline-flex; align-items: center; gap: 9px; font-size: 11.5px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--lime); margin-bottom: 16px; }

.prof-tag::before { content: ""; width: 22px; height: 2px; background: var(--lime); }

.prof-in h1 { font-size: clamp(32px, 4.6vw, 52px); line-height: 1.04; letter-spacing: -0.025em; color: rgb(255, 255, 255); }

.prof-role { margin-top: 14px; font-family: var(--display); font-weight: 600; font-size: clamp(17px, 1.9vw, 21px); color: var(--lime); letter-spacing: -0.01em; }

.prof-affil { margin-top: 6px; font-size: 15px; color: rgb(207, 224, 218); }

.prof-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 26px; }

.btn.sm { padding: 11px 20px; font-size: 14px; gap: 8px; }

.btn.on-photo.sm { background: var(--paper); color: var(--teal-deep); border-color: var(--paper); }

.btn.on-photo.sm:hover { background: var(--lime); border-color: var(--lime); color: var(--ink); }

.btn.line.sm { background: transparent; color: rgb(255, 255, 255); border-color: rgba(255, 255, 255, 0.5); }

.btn.line.sm:hover { background: rgba(255, 255, 255, 0.12); border-color: rgb(255, 255, 255); color: rgb(255, 255, 255); transform: translateY(-2px); }

.bio { display: grid; grid-template-columns: 1fr 320px; gap: clamp(32px, 5vw, 72px); align-items: start; }

.bio-body { font-size: clamp(16.5px, 1.5vw, 18px); color: var(--ink); line-height: 1.72; }

.bio-body p + p { margin-top: 20px; }

.bio-body p:first-child { font-size: clamp(18px, 1.7vw, 20.5px); color: var(--ink); font-family: var(--display); font-weight: 500; line-height: 1.5; letter-spacing: -0.01em; }

.bio-aside { position: sticky; top: 104px; border: 1px solid var(--line); background: var(--paper-alt); padding: clamp(24px, 3vw, 32px); }

.bio-aside h2 { font-family: var(--sans); font-weight: 700; font-size: 12.5px; letter-spacing: 0.12em; text-transform: uppercase; color: var(--teal-text); margin-bottom: 20px; }

.fact { display: flex; flex-direction: column; gap: 3px; padding: 14px 0px; border-top: 1px solid var(--line); }

.fact:first-of-type { border-top: 0px; padding-top: 0px; }

.fact dt { font-size: 11.5px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; color: var(--ink-soft); }

.fact dd { margin: 0px; font-family: var(--display); font-weight: 700; font-size: 16px; color: var(--ink); letter-spacing: -0.01em; }

.fact dd a { color: var(--teal-text); text-decoration: none; border-bottom: 1px solid transparent; transition: border-color 0.2s; }

.fact dd a:hover { border-color: var(--teal-text); }

.backbar { background: var(--paper-alt); }

.backbar .wrap { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: center; padding: clamp(36px,4.5vw,56px) var(--gutter); }

.backbar p { font-family: var(--display); font-weight: 600; font-size: clamp(17px, 2vw, 21px); color: var(--ink); margin-right: 8px; }

@media (max-width: 820px) {
  .prof-in { grid-template-columns: 1fr; text-align: center; gap: 24px; justify-items: center; }
  .prof-crumb, .prof-tag, .prof-actions { justify-content: center; }
  .bio { grid-template-columns: 1fr; }
  .bio-aside { position: static; order: -1; }
}

@media (max-width: 480px) {
  .backbar .wrap { flex-direction: column; align-items: stretch; text-align: center; }
  .backbar .btn { justify-content: center; }
}';

get_header();

while ( have_posts() ) :
	the_post();
	$f = ucan_member_display_fields( get_post() );
	?>

<section class="prof-hero" aria-labelledby="h1">
  <div class="prof-in">
    <div class="prof-photo">
      <?php if ( has_post_thumbnail() ) : ?>
        <?php the_post_thumbnail( 'thumbnail', array( 'width' => 220, 'height' => 220, 'fetchpriority' => 'high', 'decoding' => 'async', 'alt' => get_the_title() ) ); ?>
      <?php else : ?>
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/monogram-placeholder.svg" width="220" height="220" alt="<?php echo esc_attr( get_the_title() ); ?>">
      <?php endif; ?>
      <span class="badge"><?php echo esc_html( $f['primary_group'] ); ?></span>
    </div>
    <div>
      <nav class="prof-crumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">/</span>
        <a href="<?php echo esc_url( home_url( '/our-people/' ) ); ?>">Our People</a><span aria-hidden="true">/</span>
        <span><?php the_title(); ?></span>
      </nav>
      <p class="prof-tag"><?php echo esc_html( $f['primary_group'] ); ?></p>
      <h1 id="h1"><?php the_title(); ?></h1>
      <p class="prof-role"><?php echo esc_html( $f['role'] ); ?></p>
      <p class="prof-affil"><?php echo esc_html( $f['affiliation'] ); ?></p>
      <div class="prof-actions">
        <a class="btn on-photo sm" href="<?php echo esc_url( home_url( '/our-people/' ) ); ?>"><span aria-hidden="true">←</span> All people</a>
        <?php if ( $f['linkedin'] ) : ?>
        <a class="btn line sm" href="<?php echo esc_url( $f['linkedin'] ); ?>" target="_blank" rel="noopener noreferrer">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.25 8h4.5v12.5H.25V8zm7.5 0h4.3v1.71h.06c.6-1.14 2.07-2.34 4.26-2.34 4.56 0 5.4 3 5.4 6.9v7.73h-4.5v-6.85c0-1.63-.03-3.73-2.27-3.73-2.27 0-2.62 1.78-2.62 3.61v6.97h-4.5V8z"></path></svg>
          LinkedIn</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section class="sec" id="bio" aria-labelledby="bio-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <p class="kicker" data-num="—">Profile</p>
        <h2 id="bio-h">About <?php echo esc_html( get_the_title() ); ?></h2>
      </div>
    </div>
    <div class="bio">
      <div class="bio-body rv in">
        <?php the_content(); ?>
      </div>
      <aside class="bio-aside rv d1 in">
        <h2>At a glance</h2>
        <dl>
          <div class="fact"><dt>Role</dt><dd><?php echo esc_html( $f['role'] ); ?></dd></div>
          <div class="fact"><dt>Group</dt><dd><?php echo esc_html( $f['all_groups'] ); ?></dd></div>
          <div class="fact"><dt>Organisation</dt><dd><?php echo esc_html( $f['organisation'] ); ?></dd></div>
          <div class="fact"><dt>Connect</dt><dd><?php echo $f['linkedin'] ? sprintf( '<a href="%s" target="_blank" rel="noopener noreferrer">LinkedIn ↗</a>', esc_url( $f['linkedin'] ) ) : '—'; ?></dd></div>
        </dl>
      </aside>
    </div>
  </div>
</section>

<section class="backbar" aria-label="Explore more">
  <div class="wrap">
    <p>Explore the network</p>
    <a class="btn" href="<?php echo esc_url( home_url( '/our-people/' ) ); ?>">Back to Our People <span class="ar" aria-hidden="true">→</span></a>
    <a class="btn line" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">About U-CAN <span class="ar" aria-hidden="true">→</span></a>
  </div>
</section>

<?php
endwhile;

get_footer();
