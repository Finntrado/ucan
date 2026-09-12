<?php
/**
 * Phase 4/5: single "etn" event - L&D session (was ld-<slug>.html),
 * Policy Webinar (was webinar-policy-webinar-<slug>.html / webinar-<slug>.html),
 * or the one City Mixer companion note (was event-hosted-by-artha-global.html).
 * One CPT, one template, branching on event_kind - see functions.php's
 * ucan_register_etn_event_cpt() for why these three share a post type
 * (they share the old site's real /etn/<slug>/ canonical, so they must
 * share one WordPress rewrite slug too).
 *
 * The "session lead" block only renders for ld_session posts that have
 * one set; the facts sidebar's rows (Type / Format) are kind-specific but
 * built from the same $f array either way - checked against
 * ld-thinking-better-alone-together.html and
 * webinar-policy-webinar-affordable-urban-housing.html line by line to
 * confirm the only real differences are that one row and the Type/Format
 * label.
 */

get_header();

while ( have_posts() ) :
	the_post();
	$f = ucan_etn_event_display_fields( get_post() );
	$earlier = get_previous_post();
	$later   = get_next_post();
	?>

<section class="hero" aria-labelledby="pt">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <a href="<?php echo esc_url( home_url( '/' . $f['hub_slug'] . '/' ) ); ?>"><?php echo esc_html( $f['hub_label'] ); ?></a> <span aria-hidden="true">/</span> <span>Session</span></nav>
    <p class="hero-tag"><?php echo esc_html( $f['hero_tag'] ); ?></p>
    <h1 id="pt"><?php the_title(); ?></h1>
    <div class="hero-chips">
      <span><?php echo esc_html( $f['date_display'] ); ?></span>
      <?php if ( $f['time_range'] ) : ?><span><?php echo esc_html( $f['time_range'] ); ?></span><?php endif; ?>
      <?php if ( 'policy_webinar' === $f['kind'] && $f['format'] ) : ?><span><?php echo esc_html( $f['format'] ); ?></span><?php endif; ?>
    </div>
  </div>
</section>

<div class="backbar"><div class="wrap"><a href="<?php echo esc_url( home_url( '/' . $f['hub_slug'] . '/' ) ); ?>">← Back to <?php echo esc_html( $f['hub_label'] ); ?></a></div></div>

<section class="sec" aria-labelledby="sd">
  <div class="wrap">
    <h2 id="sd" class="vh">Session details</h2>
    <div class="sart">
      <?php if ( 'ld_session' === $f['kind'] ) : ?>
      <div class="body rv">
        <?php if ( $f['lead_name'] ) : ?>
        <div class="grp"><h2>Session lead <span style="color:var(--teal-text)"><?php echo esc_html( $f['lead_name'] ); ?></span></h2>
        <?php if ( $f['lead_bio'] ) : ?><p><?php echo esc_html( $f['lead_bio'] ); ?></p><?php endif; ?></div>
        <?php endif; ?>
        <div class="grp"><h2>About the session</h2>
        <?php the_content(); ?></div>
      </div>
      <?php else : ?>
      <div class="rv">
        <div class="post-body">
        <?php the_content(); ?>
        </div>
      </div>
      <?php endif; ?>
      <aside class="sfacts rv d1">
        <dl>
          <dt>Date</dt><dd><?php echo esc_html( $f['date_display'] ); ?></dd>
          <?php if ( $f['time_range'] ) : ?><dt>Time</dt><dd><?php echo esc_html( $f['time_range'] . ' (' . $f['timezone'] . ')' ); ?></dd><?php endif; ?>
          <?php if ( 'ld_session' === $f['kind'] && $f['session_type'] ) : ?><dt>Type</dt><dd><?php echo esc_html( $f['session_type'] ); ?></dd><?php endif; ?>
          <?php if ( 'policy_webinar' === $f['kind'] && $f['format'] ) : ?><dt>Format</dt><dd><?php echo esc_html( $f['format'] ); ?></dd><?php endif; ?>
          <dt>Series</dt><dd><?php echo esc_html( $f['series'] ); ?></dd>
        </dl>
      </aside>
    </div>
  </div>
</section>

<section class="ctaband" aria-label="Session navigation">
  <div class="wrap">
    <p>More from <?php echo esc_html( $f['hub_label'] ); ?></p>
    <?php if ( $later ) : ?><a class="btn line" href="<?php echo esc_url( get_permalink( $later ) ); ?>">← Later <?php echo esc_html( $f['nav_word'] ); ?></a><?php endif; ?>
    <a class="btn line" href="<?php echo esc_url( home_url( '/' . $f['hub_slug'] . '/' ) ); ?>">All <?php echo esc_html( strtolower( $f['nav_word'] ) ); ?>s</a>
    <?php if ( $earlier ) : ?><a class="btn line" href="<?php echo esc_url( get_permalink( $earlier ) ); ?>">Earlier <?php echo esc_html( $f['nav_word'] ); ?> →</a><?php endif; ?>
  </div>
</section>

<?php
endwhile;

get_footer();
