<?php
/**
 * Phase 4: single fellow profile (was profile-fellow-<slug>.html).
 * Layout lifted verbatim from profile-fellow-aanchal-aggarwal.html - bio
 * is post_content; host org/joining location/education/social links are
 * the meta set up in functions.php's ucan_register_fellow_cpt(). The
 * "N posts by <name>" section queries ucan_blog posts whose fellow_id
 * meta points at this fellow - renders nothing until those exist (phase
 * 7's importer), same expected gap as Our People (CLAUDE.md §28).
 */

get_header();

while ( have_posts() ) :
	the_post();
	$f = ucan_fellow_display_fields( get_post() );

	$posts = new WP_Query(
		array(
			'post_type'      => 'ucan_blog',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_key'       => 'fellow_id',
			'meta_value'     => get_the_ID(),
		)
	);
	?>

<section class="hero" aria-labelledby="pt">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <a href="<?php echo esc_url( home_url( '/meet-our-fellows/' ) ); ?>">Fellows</a> <span aria-hidden="true">/</span> <span><?php the_title(); ?></span></nav>
    <div class="phero">
      <?php if ( has_post_thumbnail() ) : ?>
        <?php the_post_thumbnail( 'medium', array( 'width' => 340, 'height' => 340, 'fetchpriority' => 'high', 'decoding' => 'async', 'alt' => get_the_title() . ', U-CAN Fellow at ' . $f['host_organisation'] . ', portrait photo' ) ); ?>
      <?php else : ?>
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/monogram-placeholder.svg" width="340" height="340" alt="<?php echo esc_attr( get_the_title() ); ?>">
      <?php endif; ?>
      <div>
        <span class="pbadge"><?php echo esc_html( $f['cohort_label'] ); ?></span>
        <h1 id="pt" style="margin-top:18px"><?php the_title(); ?></h1>
        <p class="hero-lede"><?php echo esc_html( $f['host_organisation'] ); ?></p>
        <?php if ( $f['social_urls'] ) : ?>
          <div class="soc on-dark"><?php echo ucan_social_row( $f['social_urls'], get_the_title() ); ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<div class="backbar"><div class="wrap"><a href="<?php echo esc_url( home_url( '/meet-our-fellows/' ) ); ?>">← Back to all Fellows</a></div></div>

<section class="sec" aria-labelledby="bio">
  <div class="wrap">
    <div class="pwrap">
      <div class="body rv">
        <h2 id="bio" class="vh">About <?php the_title(); ?></h2>
        <p class="kicker" data-num="—" style="margin-bottom:18px">Profile</p>
        <?php the_content(); ?>
      </div>
      <aside class="glance rv d1">
        <h2>At a glance</h2>
        <dl>
          <dt>Role</dt><dd><?php echo esc_html( $f['cohort_label'] ); ?></dd>
          <dt>Host organisation</dt><dd><?php echo esc_html( $f['host_organisation'] ); ?></dd>
          <?php if ( $f['joining_location'] ) : ?><dt>Joining location</dt><dd><?php echo esc_html( $f['joining_location'] ); ?></dd><?php endif; ?>
          <?php if ( $f['education'] ) : ?><dt>Education</dt><dd><?php echo esc_html( $f['education'] ); ?></dd><?php endif; ?>
        </dl>
      </aside>
    </div>
  </div>
</section>

<?php if ( $posts->have_posts() ) : ?>
<section class="sec alt" aria-labelledby="wr">
  <div class="wrap">
    <div class="sec-head rv">
      <p class="kicker" data-num="—">Field notes</p>
      <h2 id="wr"><?php echo esc_html( $posts->found_posts ); ?> post<?php echo 1 === $posts->found_posts ? '' : 's'; ?> by <?php the_title(); ?></h2>
    </div>
    <ul class="alist">
      <?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
        <li><a class="arow" href="<?php the_permalink(); ?>">
          <span class="at"><?php the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => '', 'onerror' => "this.closest('.at').style.display='none'" ) ); ?></span>
          <span>
            <span class="ad"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
            <h3><?php the_title(); ?></h3>
            <span class="btags">
              <?php foreach ( ucan_post_tags( get_the_ID() ) as $term ) : ?>
                <span class="tag" data-tag="<?php echo esc_attr( $term->slug ); ?>" role="link" tabindex="0" title="See all posts tagged <?php echo esc_attr( $term->name ); ?>"><?php echo esc_html( $term->name ); ?></span>
              <?php endforeach; ?>
            </span>
          </span>
        </a></li>
      <?php endwhile; wp_reset_postdata(); ?>
    </ul>
  </div>
</section>
<?php endif; ?>

<?php
endwhile;

get_footer();
