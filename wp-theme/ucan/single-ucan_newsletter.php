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
