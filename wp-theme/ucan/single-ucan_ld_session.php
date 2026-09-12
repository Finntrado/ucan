<?php
/**
 * Phase 4: single L&D session (was ld-<slug>.html). Layout lifted
 * verbatim from ld-thinking-better-alone-together.html. "About the
 * session" is post_content; lead name/bio and the sidebar facts are meta
 * from functions.php's ucan_register_ld_session_cpt(). Prev/next nav uses
 * WP's own adjacent-post functions ordered by post_date - set each
 * session's post_date to its real session date on import and this just
 * works, no extra ordering meta needed.
 */

get_header();

while ( have_posts() ) :
	the_post();
	$f = ucan_ld_session_display_fields( get_post() );
	// "Later"/"Earlier" in the source is reverse-chronological browsing,
	// not literal next/previous by ID - get_previous_post() walks by
	// post_date descending (older), get_next_post() ascending (newer).
	$earlier = get_previous_post();
	$later   = get_next_post();
	?>

<section class="hero" aria-labelledby="pt">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <a href="<?php echo esc_url( home_url( '/fellowship-ld/' ) ); ?>">L&amp;D Calendar</a> <span aria-hidden="true">/</span> <span>Session</span></nav>
    <p class="hero-tag">Fellowship L&amp;D · Past session</p>
    <h1 id="pt"><?php the_title(); ?></h1>
    <div class="hero-chips">
      <span><?php echo esc_html( $f['date_display'] ); ?></span><?php if ( $f['time_range'] ) : ?><span><?php echo esc_html( $f['time_range'] ); ?></span><?php endif; ?>
    </div>
  </div>
</section>

<div class="backbar"><div class="wrap"><a href="<?php echo esc_url( home_url( '/fellowship-ld/' ) ); ?>">← Back to the L&amp;D Calendar</a></div></div>

<section class="sec" aria-labelledby="sd">
  <div class="wrap">
    <h2 id="sd" class="vh">Session details</h2>
    <div class="sart">
      <div class="body rv">
        <?php if ( $f['lead_name'] ) : ?>
        <div class="grp"><h2>Session lead <span style="color:var(--teal-text)"><?php echo esc_html( $f['lead_name'] ); ?></span></h2>
        <?php if ( $f['lead_bio'] ) : ?><p><?php echo esc_html( $f['lead_bio'] ); ?></p><?php endif; ?></div>
        <?php endif; ?>
        <div class="grp"><h2>About the session</h2>
        <?php the_content(); ?></div>
      </div>
      <aside class="sfacts rv d1">
        <dl>
          <dt>Date</dt><dd><?php echo esc_html( $f['date_display'] ); ?></dd>
          <?php if ( $f['time_range'] ) : ?><dt>Time</dt><dd><?php echo esc_html( $f['time_range'] . ' (' . $f['timezone'] . ')' ); ?></dd><?php endif; ?>
          <?php if ( $f['session_type'] ) : ?><dt>Type</dt><dd><?php echo esc_html( $f['session_type'] ); ?></dd><?php endif; ?>
          <dt>Programme</dt><dd>U-CAN Fellowship L&amp;D</dd>
        </dl>
      </aside>
    </div>
  </div>
</section>

<section class="ctaband" aria-label="Session navigation">
  <div class="wrap">
    <p>More from the Fellowship L&amp;D programme</p>
    <?php if ( $later ) : ?><a class="btn line" href="<?php echo esc_url( get_permalink( $later ) ); ?>">← Later session</a><?php endif; ?>
    <a class="btn line" href="<?php echo esc_url( home_url( '/fellowship-ld/' ) ); ?>">All sessions</a>
    <?php if ( $earlier ) : ?><a class="btn line" href="<?php echo esc_url( get_permalink( $earlier ) ); ?>">Earlier session →</a><?php endif; ?>
  </div>
</section>

<?php
endwhile;

get_footer();
