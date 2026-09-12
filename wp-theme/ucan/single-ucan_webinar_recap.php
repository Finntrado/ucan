<?php
/**
 * Phase 5: single webinar recap article (was webinar-recap-<slug>.html).
 * Layout lifted verbatim from webinar-recap-affordable-urban-housing.html -
 * simplest of the event templates: hero + rich-text body only, no facts
 * sidebar, no author (checked against the source - it genuinely has
 * neither).
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>

<section class="hero" aria-labelledby="pt">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <a href="<?php echo esc_url( home_url( '/policy-webinars/' ) ); ?>">Policy Webinars</a> <span aria-hidden="true">/</span> <span>Recap</span></nav>
    <p class="hero-tag">Policy Webinars · Recap</p>
    <h1 id="pt"><?php the_title(); ?></h1>
  </div>
</section>

<div class="backbar"><div class="wrap"><a href="<?php echo esc_url( home_url( '/policy-webinars/' ) ); ?>">← Back to Policy Webinars</a></div></div>

<section class="sec" aria-labelledby="rc">
  <div class="wrap">
    <h2 id="rc" class="vh"><?php the_title(); ?></h2>
    <div class="post-body rv" style="max-width:80ch">
      <?php the_content(); ?>
    </div>
  </div>
</section>

<?php
endwhile;

get_footer();
