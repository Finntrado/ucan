<?php
/**
 * Phase 6: single newsletter issue (was newsletter-<slug>.html). Two
 * branches per issue_kind (functions.php's ucan_newsletter_display_fields()):
 *   - cover_only (2 of 29, checked - Jan/Feb 2024): cover image + "not
 *     yet published" note, layout from
 *     newsletter-urban-collectiveaction-network-newsletter-january-2024.html.
 *   - designed (27 of 29): masthead + the editorial body as post_content
 *     (the whole table-of-contents-through-sections block - genuinely
 *     free-form per issue, so left as one WYSIWYG field rather than
 *     modelled into rigid sub-fields), then a structured download/share/
 *     prev-next footer built from meta + WP's own permalink - layout from
 *     newsletter-august-2024.html.
 */

$ucan_page_css = '*, ::before, ::after { box-sizing: border-box; }

html { text-size-adjust: 100%; scroll-behavior: smooth; }

@media (prefers-reduced-motion: reduce) {
  html { scroll-behavior: auto; }
  *, ::before, ::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
}

:root { --paper: #FBFAF6; --paper-warm: #F7F3EB; --mint: #E9F5F2; --mint-strong: #D4EDE8; --ink: #1B1B19; --ink-2: #3A3A36; --ink-soft: #57564F; --ink-light: #7A7974; --teal: #1F8F7B; --teal-text: #177A69; --teal-deep: #0E5348; --teal-mid: #145F50; --teal-light: #4EC6B2; --teal-glow: rgba(31,143,123,.08); --lime: #CDDE71; --lime-strong: #B8CC55; --lime-glow: rgba(205,222,113,.15); --orange: #FEAE00; --orange-glow: rgba(254,174,0,.1); --line: #DCEAE6; --line-strong: #B4CFC8; --sans: \'Public Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,sans-serif; --display: \'Archivo\',\'Public Sans\',-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif; --w: 760px; --w-wide: 900px; --gutter: clamp(1.25rem,4vw,2rem); --section: clamp(3.5rem,7vw,5rem); --radius: 3px; --ease: cubic-bezier(.4,0,.2,1); }

body { margin: 0px; background: var(--paper); color: var(--ink-2); font-family: var(--sans); font-size: 1.0625rem; line-height: 1.75; -webkit-font-smoothing: antialiased; text-rendering: optimizelegibility; }

img, svg { display: block; max-width: 100%; height: auto; }

a { color: var(--teal-text); text-decoration: underline 1px; text-underline-offset: 0.2em; font-weight: 600; transition: color 0.18s, text-decoration-color 0.18s; }

a:hover { color: var(--teal-deep); text-decoration-color: var(--lime); }

button { font: inherit; cursor: pointer; background: none; border: 0px; }

:focus-visible { outline: 2px solid var(--teal-text); outline-offset: 3px; border-radius: 2px; }

.skip { position: absolute; left: -9999px; top: 0px; background: var(--teal-deep); color: var(--paper); padding: 0.75rem 1rem; z-index: 100; text-decoration: none; font-weight: 600; }

.skip:focus { left: 0.5rem; top: 0.5rem; }

.wrap { max-width: var(--w); margin: 0px auto; padding: 0 var(--gutter); }

.wrap-wide { max-width: var(--w-wide); margin: 0px auto; padding: 0 var(--gutter); }

.progress { position: fixed; top: 0px; left: 0px; right: 0px; height: 3px; z-index: 100; background: transparent; }

.progress-fill { height: 100%; width: 0px; background: linear-gradient(90deg,var(--teal),var(--lime)); transition: width 0.1s linear; }

.topbar { background: var(--teal-deep); color: var(--paper); padding: 0px; position: sticky; top: 0px; z-index: 90; border-bottom: 1px solid rgba(255, 255, 255, 0.08); }

.topbar-inner { display: flex; align-items: center; justify-content: space-between; gap: 1rem; height: 56px; max-width: var(--w-wide); margin: 0px auto; padding: 0 var(--gutter); }

.topbar .logo { display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; color: var(--paper); font-family: var(--display); font-weight: 700; font-size: 0.9375rem; }

.topbar .logo-mark { width: 28px; height: 28px; background: rgba(255, 255, 255, 0.12); border-radius: 5px; display: grid; place-items: center; font-weight: 800; font-size: 0.8125rem; line-height: 1; position: relative; }

.topbar .logo-mark::after { content: ""; position: absolute; top: -2px; right: -2px; width: 6px; height: 6px; background: var(--orange); border-radius: 50%; }

.topbar .logo small { font-family: var(--sans); font-size: 7px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: rgba(255, 255, 255, 0.5); display: block; margin-top: 1px; }

.topbar-links { display: flex; gap: 1.25rem; font-size: 0.8125rem; font-weight: 600; }

.topbar-links a { color: rgba(255, 255, 255, 0.65); text-decoration: none; transition: color 0.15s; }

.topbar-links a:hover { color: var(--lime); }

.topbar-edition { font-family: var(--display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--lime); display: none; }

@media (min-width: 700px) {
  .topbar-edition { display: block; }
}

@media (max-width: 500px) {
  .topbar-links { gap: 0.75rem; }
  .topbar-links a:nth-child(3) { display: none; }
}

.masthead { padding: clamp(4rem, 9vw, 7rem) 0px clamp(3rem, 6vw, 5rem); text-align: center; position: relative; overflow: hidden; background: linear-gradient(175deg,var(--paper) 0%,var(--mint) 100%); }

.mast-bg { position: absolute; inset: 0px; pointer-events: none; }

.mast-bg svg { width: 100%; height: 100%; }

.mast-content { position: relative; z-index: 1; }

.mast-badge { display: inline-flex; align-items: center; gap: 0.6em; font-family: var(--sans); font-size: 11px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--teal-deep); background: rgba(14, 83, 72, 0.06); border: 1px solid rgba(14, 83, 72, 0.12); padding: 8px 18px; border-radius: 20px; margin-bottom: 2rem; }

.mast-badge .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--lime); }

.mast-title { font-family: var(--display); font-size: clamp(3rem, 8vw, 5.5rem); font-weight: 800; line-height: 0.95; letter-spacing: -0.04em; color: var(--ink); margin: 0px; }

.mast-title span { display: block; background-image: ; background-position-x: ; background-position-y: ; background-size: ; background-repeat: ; background-attachment: ; background-origin: ; background-color: ; -webkit-text-fill-color: transparent; background-clip: text; }

.mast-sub { font-family: var(--display); font-size: clamp(1rem, 2vw, 1.375rem); font-weight: 600; color: var(--ink-soft); letter-spacing: -0.01em; margin: 1.5rem auto 0px; max-width: 36ch; line-height: 1.35; }

.mast-date { font-size: 0.8125rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--teal-text); margin: 2rem 0px 0px; }

.mast-rule { width: 48px; height: 4px; background: var(--lime); border: 0px; margin: 2rem auto 0px; border-radius: 2px; }

.toc { padding: 2rem 0px; border-bottom: 1px solid var(--line); background: var(--paper); }

.toc-label { font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--ink-light); margin: 0px 0px 1rem; }

.toc-links { display: flex; flex-wrap: wrap; gap: 0.5rem; }

.toc-link { display: inline-flex; align-items: center; gap: 0.4em; padding: 10px 18px; font-size: 0.8125rem; font-weight: 700; letter-spacing: 0.02em; text-decoration: none; color: var(--teal-deep); background: var(--teal-glow); border: 1px solid rgba(14, 83, 72, 0.1); border-radius: 20px; transition: all .22s var(--ease); }

.toc-link:hover { background: var(--teal-deep); color: var(--paper); border-color: var(--teal-deep); transform: translateY(-1px); text-decoration: none; }

.toc-link svg { width: 8px; height: 8px; opacity: 0.5; }

.sec { padding: var(--section) 0; border-bottom: 1px solid var(--line); }

.sec:last-of-type { border-bottom: 0px; }

.sec-header { display: flex; align-items: center; gap: 1.25rem; margin-bottom: clamp(2rem, 4vw, 3rem); }

.sec-icon { width: 44px; height: 44px; background: var(--lime-glow); border: 1px solid rgba(205, 222, 113, 0.25); border-radius: 10px; display: grid; place-items: center; flex-shrink: 0; }

.sec-icon svg { width: 20px; height: 20px; color: var(--teal); }

.sec-header h2 { font-family: var(--display); font-size: clamp(1.5rem, 3vw, 1.875rem); font-weight: 800; letter-spacing: -0.02em; color: var(--ink); margin: 0px; line-height: 1.15; }

.sec-header::after { content: ""; flex: 1 1 0%; height: 1px; background: linear-gradient(90deg,var(--line) 0%,transparent 100%); }

.letter p { margin: 0px 0px 1.25rem; color: var(--ink-2); }

.letter p:last-child { margin-bottom: 0px; }

.letter-sign { margin-top: 2.5rem; padding: 1.75rem; background: var(--teal-glow); border: 1px solid rgba(14, 83, 72, 0.08); border-radius: var(--radius); display: flex; align-items: center; gap: 1.25rem; }

.sign-photo { width: 64px; height: 64px; border-radius: 50%; overflow: hidden; flex-shrink: 0; border: 2px solid var(--lime); box-shadow: rgba(14, 83, 72, 0.15) 0px 4px 12px; }

.sign-photo img, .sign-photo svg { width: 100%; height: 100%; object-fit: cover; display: block; }

.sign-text .name { font-family: var(--display); font-weight: 700; font-size: 1.0625rem; color: var(--ink); margin: 0px; }

.sign-text .role { font-size: 0.875rem; color: var(--ink-soft); margin: 0.15rem 0px 0px; }

.feature { background: linear-gradient(135deg,var(--mint) 0%,var(--mint-strong) 100%); border: 1px solid var(--line); border-radius: 8px; padding: clamp(2rem, 4vw, 2.75rem); position: relative; overflow: hidden; transition: box-shadow .28s var(--ease),transform .28s var(--ease); }

.feature:hover { box-shadow: rgba(14, 83, 72, 0.18) 0px 20px 60px -24px; transform: translateY(-2px); }

.feature::before { content: ""; position: absolute; top: 0px; left: 0px; right: 0px; height: 4px; background: linear-gradient(90deg,var(--teal),var(--lime)); }

.feature-title { font-family: var(--display); font-size: clamp(1.25rem, 2.5vw, 1.5rem); font-weight: 800; line-height: 1.2; letter-spacing: -0.015em; color: var(--ink); margin: 0px 0px 1.5rem; max-width: 36ch; }

.feature p { margin: 0px 0px 1.25rem; color: var(--ink-soft); line-height: 1.75; }

.feature p:last-of-type { margin-bottom: 0px; }

.articles { display: grid; gap: 1.25rem; }

.art { padding: clamp(1.5rem, 3vw, 2rem); background: var(--paper); border-top-color: ; border-top-style: ; border-top-width: ; border-right-color: ; border-right-style: ; border-right-width: ; border-bottom-color: ; border-bottom-style: ; border-bottom-width: ; border-image-source: ; border-image-slice: ; border-image-width: ; border-image-outset: ; border-image-repeat: ; border-left: 4px solid var(--line); border-radius: var(--radius); transition: all .28s var(--ease); position: relative; }

.art:hover { border-left-color: var(--teal); background: var(--paper-warm); box-shadow: rgba(14, 83, 72, 0.14) 0px 8px 32px -16px; transform: translateX(4px); }

.art-source { display: inline-flex; align-items: center; gap: 0.5em; font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--paper); background: var(--teal); padding: 4px 12px; border-radius: 20px; margin-bottom: 1rem; }

.art h3 { font-family: var(--display); font-size: clamp(1.125rem, 2vw, 1.3125rem); font-weight: 700; line-height: 1.25; letter-spacing: -0.01em; color: var(--ink); margin: 0px 0px 1rem; max-width: 42ch; }

.art p { margin: 0px 0px 1rem; color: var(--ink-soft); line-height: 1.75; font-size: 1rem; }

.art p:last-of-type { margin-bottom: 0px; }

.art-link { display: inline-flex; align-items: center; gap: 0.5em; font-size: 0.875rem; font-weight: 700; margin-top: 1.25rem; text-decoration: none; color: var(--teal-text); padding: 8px 16px; background: var(--teal-glow); border-radius: 20px; transition: 0.18s; }

.art-link:hover { background: var(--teal-deep); color: var(--paper); text-decoration: none; }

.art-link svg { width: 10px; height: 10px; transition: transform 0.18s; }

.art-link:hover svg { transform: translateX(3px); }

.stat-card { border-radius: 12px; padding: clamp(2.5rem, 5vw, 4rem) clamp(2rem, 4vw, 3rem); text-align: center; position: relative; overflow: hidden; background: linear-gradient(135deg,var(--teal-deep) 0%,var(--teal-mid) 50%,var(--teal) 100%); color: var(--paper); margin-bottom: 2.5rem; }

.stat-bg { position: absolute; inset: 0px; pointer-events: none; opacity: 0.15; }

.stat-bg svg { width: 100%; height: 100%; }

.stat-num { font-family: var(--display); font-size: clamp(4rem, 10vw, 7rem); font-weight: 800; line-height: 0.9; letter-spacing: -0.05em; margin: 0px; position: relative; z-index: 1; background-image: ; background-position-x: ; background-position-y: ; background-size: ; background-repeat: ; background-attachment: ; background-origin: ; background-color: ; -webkit-text-fill-color: transparent; background-clip: text; }

.stat-unit { font-family: var(--display); font-size: clamp(1rem, 2vw, 1.375rem); font-weight: 700; letter-spacing: -0.01em; color: var(--lime); margin: 0.75rem 0px 0px; position: relative; z-index: 1; }

.stat-desc { font-size: clamp(0.9375rem, 1.3vw, 1.0625rem); line-height: 1.6; color: rgba(251, 250, 246, 0.75); margin: 1.5rem auto 0px; max-width: 40ch; position: relative; z-index: 1; }

.stat-source { font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: rgba(251, 250, 246, 0.4); margin-top: 2rem; position: relative; z-index: 1; }

.quote { margin: 2rem 0px; padding: 2rem 2rem 2rem 2.5rem; position: relative; background: var(--lime-glow); border-radius: var(--radius); border: 1px solid rgba(205, 222, 113, 0.2); }

.quote::before { content: ""; position: absolute; left: 0px; top: 0px; bottom: 0px; width: 4px; background: linear-gradient(180deg,var(--lime) 0%,var(--teal) 100%); border-radius: 4px 0px 0px 4px; }

.quote p { font-family: var(--display); font-style: italic; font-weight: 600; font-size: clamp(1.0625rem, 1.5vw, 1.1875rem); line-height: 1.55; color: var(--ink); margin: 0px; }

.cities-highlight { display: grid; grid-template-columns: 1fr; gap: 1.25rem; margin-bottom: 2rem; }

@media (min-width: 600px) {
  .cities-highlight { grid-template-columns: 1fr 1fr; }
}

.cities-stat { padding: 1.5rem; background: var(--paper-warm); border: 1px solid var(--line); border-radius: 8px; text-align: center; transition: transform .22s var(--ease),border-color .22s var(--ease); }

.cities-stat:hover { transform: translateY(-2px); border-color: var(--lime); }

.cities-num { font-family: var(--display); font-size: 2.5rem; font-weight: 800; line-height: 1; letter-spacing: -0.03em; color: var(--teal); margin: 0px; }

.cities-label { font-size: 0.8125rem; font-weight: 600; color: var(--ink-soft); margin: 0.5rem 0px 0px; line-height: 1.4; }

.btn { display: inline-flex; align-items: center; gap: 0.5em; padding: 14px 24px; font-family: var(--sans); font-size: 0.9375rem; font-weight: 700; border-radius: var(--radius); text-decoration: none; transition: all .22s var(--ease); line-height: 1; border: 1px solid transparent; }

.btn-primary { background: var(--teal-deep); color: var(--paper); border-color: var(--teal-deep); }

.btn-primary:hover { background: var(--ink); border-color: var(--ink); transform: translateY(-1px); text-decoration: none; }

.btn-ghost { color: var(--teal-deep); border-color: var(--line-strong); background: var(--paper); }

.btn-ghost:hover { border-color: var(--teal-deep); background: var(--teal-glow); text-decoration: none; }

.btn svg { width: 12px; height: 12px; flex-shrink: 0; transition: transform 0.18s; }

.btn:hover svg { transform: translateX(3px); }

.btt { position: fixed; bottom: 2rem; right: 2rem; z-index: 80; width: 44px; height: 44px; background: var(--teal-deep); color: var(--paper); border-radius: 50%; display: grid; place-items: center; box-shadow: rgba(14, 83, 72, 0.3) 0px 4px 16px; opacity: 0; transform: translateY(12px); transition: opacity .28s var(--ease),transform .28s var(--ease),background .18s ease; pointer-events: none; }

.btt.show { opacity: 1; transform: translateY(0px); pointer-events: auto; }

.btt:hover { background: var(--ink); }

.btt svg { width: 16px; height: 16px; }

.nl-footer { padding: var(--section) 0 2rem; background: var(--ink); color: rgb(140, 160, 152); text-align: center; font-size: 0.875rem; line-height: 1.6; }

.nl-footer .logo { display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; color: var(--paper); font-family: var(--display); font-weight: 700; font-size: 1rem; margin-bottom: 1rem; }

.nl-footer .logo-mark { width: 28px; height: 28px; background: var(--teal); border-radius: 5px; display: grid; place-items: center; font-weight: 800; font-size: 0.8125rem; line-height: 1; color: var(--paper); }

.nl-footer .logo small { font-family: var(--sans); font-size: 7px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: rgb(140, 160, 152); display: block; margin-top: 1px; }

.nl-footer p { margin: 0.5rem auto; max-width: 48ch; }

.social { display: flex; justify-content: center; gap: 0.75rem; margin: 1.5rem 0px; }

.social a { width: 36px; height: 36px; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 50%; display: grid; place-items: center; text-decoration: none; transition: border-color 0.18s, background 0.18s; }

.social a:hover { border-color: var(--lime); background: rgba(255, 255, 255, 0.06); }

.social a svg { width: 16px; height: 16px; color: rgb(140, 160, 152); }

.social a:hover svg { color: var(--lime); }

.nl-footer-links { margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255, 255, 255, 0.06); font-size: 0.8125rem; }

.nl-footer-links a { color: rgb(140, 160, 152); text-decoration: none; font-weight: 500; }

.nl-footer-links a:hover { color: var(--lime); }

.sec-img { width: 100%; aspect-ratio: 16 / 7; border-radius: 8px; overflow: hidden; margin-bottom: 2rem; position: relative; }

.sec-img img, .sec-img svg { width: 100%; height: 100%; object-fit: cover; display: block; }

.sec-img figcaption { position: absolute; bottom: 0px; left: 0px; right: 0px; padding: 1rem 1.25rem; background: linear-gradient(0deg, rgba(14, 83, 72, 0.85) 0%, transparent 100%); font-size: 0.75rem; font-weight: 600; letter-spacing: 0.06em; color: rgba(251, 250, 246, 0.8); }

.illus { background: var(--mint); position: relative; overflow: hidden; }

.illus svg { position: absolute; inset: 0px; width: 100%; height: 100%; }

.illus-label { position: absolute; bottom: 1rem; left: 1.25rem; font-family: var(--display); font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--teal-deep); opacity: 0.6; }

.art-img { width: 100%; aspect-ratio: 16 / 8; border-radius: 6px; overflow: hidden; margin-bottom: 1.25rem; position: relative; }

.art-img img, .art-img svg { width: 100%; height: 100%; object-fit: cover; display: block; }

.art-img figcaption { position: absolute; bottom: 0px; left: 0px; right: 0px; padding: 0.65rem 1rem; background: linear-gradient(0deg, rgba(0, 0, 0, 0.6) 0%, transparent 100%); font-size: 0.6875rem; font-weight: 600; color: rgba(255, 255, 255, 0.8); }

.reveal { opacity: 0; transform: translateY(20px); transition: opacity .6s var(--ease),transform .6s var(--ease); }

.reveal.visible { opacity: 1; transform: translateY(0px); }

.reveal-delay-1 { transition-delay: 0.1s; }

.reveal-delay-2 { transition-delay: 0.2s; }

.reveal-delay-3 { transition-delay: 0.25s; }';

get_header();

while ( have_posts() ) :
	the_post();
	$f = ucan_newsletter_display_fields( get_post() );
	$prev = get_previous_post();
	$next = get_next_post();
	?>

<?php if ( 'cover_only' === $f['kind'] ) : ?>

<section class="hero" aria-labelledby="pt">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <a href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>">Newsletter</a> <span aria-hidden="true">/</span> <span><?php echo esc_html( $f['month_year'] ); ?></span></nav>
    <p class="hero-tag">Newsletter archive</p>
    <h1 id="pt"><?php the_title(); ?></h1>
    <p class="hero-lede">Urban governance updates, member news and opportunities from across the
      U-CAN network, as sent to subscribers in <?php echo esc_html( $f['month_year'] ); ?>.</p>
  </div>
</section>

<div class="backbar"><div class="wrap"><a href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>">← Back to all editions</a></div></div>

<section class="sec" aria-labelledby="ed">
  <div class="wrap">
    <div class="intro">
      <div class="rv">
        <h2 id="ed" class="vh"><?php echo esc_html( $f['month_year'] ); ?> edition</h2>
        <?php if ( has_post_thumbnail() ) : ?>
        <figure class="cover"><?php the_post_thumbnail( 'large', array( 'width' => 1200, 'height' => 630, 'fetchpriority' => 'high', 'decoding' => 'async', 'alt' => 'Cover of the U-CAN newsletter, ' . $f['month_year'] ) ); ?></figure>
        <?php endif; ?>
      </div>
      <div class="panel rv d1">
        <p class="covernote">The full text of this edition is not yet published online. Subscribe below and we will send each new edition straight to your inbox.</p>
        <p style="margin-top:18px"><a href="<?php echo esc_url( home_url( '/newsletter/#subscribe' ) ); ?>" style="color:var(--teal-text);font-weight:600;font-size:14.5px;text-decoration:none">Subscribe to the newsletter →</a></p>
      </div>
    </div>
  </div>
</section>

<section class="ctaband" aria-label="Browse editions">
  <div class="wrap">
    <p>Browse the archive</p>
    <?php if ( $prev ) : ?><a class="btn line" href="<?php echo esc_url( get_permalink( $prev ) ); ?>">← <?php echo esc_html( get_the_title( $prev ) ); ?></a><?php endif; ?>
    <a class="btn line" href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>">All editions</a>
    <?php if ( $next ) : ?><a class="btn line" href="<?php echo esc_url( get_permalink( $next ) ); ?>"><?php echo esc_html( get_the_title( $next ) ); ?> →</a><?php endif; ?>
  </div>
</section>

<?php else : ?>

<section class="masthead" aria-labelledby="mast-h">
  <div class="mast-bg" aria-hidden="true">
    <svg viewBox="0 0 900 400" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
      <circle cx="80" cy="50" r="220" fill="#1F8F7B" opacity=".07"></circle>
      <circle cx="820" cy="350" r="200" fill="#CDDE71" opacity=".1"></circle>
      <circle cx="450" cy="200" r="300" fill="#4EC6B2" opacity=".04"></circle>
      <line x1="0" y1="320" x2="900" y2="320" stroke="#B4CFC8" stroke-width="1" stroke-dasharray="6 12" opacity=".2"></line>
      <line x1="0" y1="80" x2="900" y2="80" stroke="#B4CFC8" stroke-width="1" stroke-dasharray="6 12" opacity=".12"></line>
    </svg>
  </div>
  <div class="wrap mast-content">
    <nav class="crumb-brief" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <a href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>">Newsletter</a> <span aria-hidden="true">/</span> <span><?php echo esc_html( $f['month_year'] ); ?></span></nav>
    <div class="mast-badge"><span class="dot"></span>Monthly Newsletter</div>
    <h1 id="mast-h" class="mast-title"><span><?php echo esc_html( $f['edition_name'] ); ?></span></h1>
    <?php if ( $f['masthead_sub'] ) : ?><p class="mast-sub"><?php echo esc_html( $f['masthead_sub'] ); ?></p><?php endif; ?>
    <p class="mast-date"><?php echo esc_html( $f['month_year'] ); ?></p>
    <hr class="mast-rule">
  </div>
</section>

<?php the_content(); ?>

<section class="sec" id="download">
  <div class="wrap">
    <div class="feature reveal">
      <p class="feature-title">Read or share the original edition</p>
      <p>This edition was sent to U-CAN subscribers in <?php echo esc_html( $f['month_year'] ); ?>.<?php echo $f['pdf_path'] ? ' Download the PDF, or subscribe to get the next one in your inbox.' : ' Subscribe to get the next one in your inbox.'; ?></p>
      <p style="display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.5rem">
        <?php if ( $f['pdf_path'] ) : ?><a class="btn btn-primary" href="<?php echo esc_url( get_template_directory_uri() . '/' . ltrim( $f['pdf_path'], '/' ) ); ?>" download>Download the PDF</a><?php endif; ?>
        <a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/newsletter/#subscribe' ) ); ?>">Subscribe</a>
      </p>
    </div>
    <div class="nl-share">
      <span class="lbl">Share this edition</span>
      <?php $share_url = rawurlencode( get_permalink() ); $share_title = rawurlencode( get_the_title() ); ?>
      <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_attr( $share_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on LinkedIn"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM.5 8h4V21h-4zM8 8h3.8v1.8h.05c.53-.95 1.83-1.95 3.76-1.95C19.7 7.85 21 10.1 21 13.3V21h-4v-6.9c0-1.65-.03-3.77-2.3-3.77-2.3 0-2.65 1.8-2.65 3.65V21H8z" fill="currentColor"/></svg>LinkedIn</a>
      <a href="https://twitter.com/intent/tweet?url=<?php echo esc_attr( $share_url ); ?>&amp;text=<?php echo esc_attr( $share_title ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on X"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17.5 2h3.3l-7.2 8.24L22 22h-6.6l-5.18-6.78L4.3 22H1l7.7-8.8L1.4 2H8.2l4.68 6.19L17.5 2zm-1.16 18h1.83L7.75 3.9H5.79z" fill="currentColor"/></svg>X</a>
      <a href="https://api.whatsapp.com/send?text=<?php echo esc_attr( $share_title . ' ' . $share_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on WhatsApp"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2zm5.8 14.18c-.24.68-1.42 1.31-1.95 1.35-.5.04-.98.22-3.3-.69-2.79-1.1-4.55-3.96-4.69-4.15-.14-.19-1.12-1.49-1.12-2.85s.71-2.02.96-2.3c.25-.27.55-.34.73-.34h.52c.17 0 .4-.06.62.48.24.57.8 1.98.87 2.12.07.14.12.31.02.5-.09.19-.14.31-.28.47l-.42.49c-.14.14-.28.29-.12.57.16.27.72 1.18 1.54 1.91 1.06.94 1.95 1.24 2.22 1.38.27.14.43.12.59-.07.16-.19.68-.79.86-1.06.18-.27.36-.22.61-.13.24.09 1.55.73 1.82.86.27.14.44.2.51.32.06.11.06.67-.18 1.35z" fill="currentColor"/></svg>WhatsApp</a>
      <a href="mailto:?subject=<?php echo esc_attr( $share_title ); ?>&amp;body=<?php echo esc_attr( $share_url ); ?>" aria-label="Share on Email"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 5h18a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zm9 8.2L4.4 7h15.2z" fill="currentColor"/></svg>Email</a>
      <button type="button" class="nl-copy" data-url="<?php the_permalink(); ?>" hidden><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10.6 13.4a4 4 0 0 0 5.66 0l3-3a4 4 0 1 0-5.66-5.66l-1.5 1.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M13.4 10.6a4 4 0 0 0-5.66 0l-3 3a4 4 0 1 0 5.66 5.66l1.5-1.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg><span>Copy link</span></button>
    </div>
    <p style="display:flex;flex-wrap:wrap;gap:.75rem;margin-top:2rem">
      <?php if ( $prev ) : ?><a class="btn btn-ghost" href="<?php echo esc_url( get_permalink( $prev ) ); ?>">&larr; <?php echo esc_html( get_the_title( $prev ) ); ?></a><?php endif; ?>
      <a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>">All editions</a>
      <?php if ( $next ) : ?><a class="btn btn-ghost" href="<?php echo esc_url( get_permalink( $next ) ); ?>"><?php echo esc_html( get_the_title( $next ) ); ?> &rarr;</a><?php endif; ?>
    </p>
  </div>
</section>

<?php endif; ?>

<?php
endwhile;

get_footer();
