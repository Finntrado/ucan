<?php
/**
 * Phase 4: single fellow blog post (was blog-<slug>.html). Layout lifted
 * verbatim from blog-a-city-i-knew-a-crisis-i-see.html. Body is
 * post_content; hero image is the featured image; author sidebar/byline
 * pulls the linked ucan_fellow post via ucan_blog_author() rather than
 * storing the author's name/org/photo a second time on the blog post
 * itself - one source of truth if a fellow's org ever changes.
 */

get_header();

while ( have_posts() ) :
	the_post();
	$fellow = ucan_blog_author( get_post() );
	$fellow_fields = $fellow ? ucan_fellow_display_fields( $fellow ) : null;
	// "Keep reading" browses all posts chronologically, not scoped to a
	// tag (checked against the source's own prev/next targets) - default
	// $in_same_term=false already does that.
	$earlier = get_previous_post();
	$later   = get_next_post();
	?>

<section class="hero" aria-labelledby="pt">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <a href="<?php echo esc_url( home_url( '/blogs-by-our-fellows/' ) ); ?>">Blogs by Our Fellows</a> <span aria-hidden="true">/</span> <span>Post</span></nav>
    <p class="hero-tag">Fellowship field notes</p>
    <h1 id="pt"><?php the_title(); ?></h1>
    <p class="post-byline">By
      <?php if ( $fellow ) : ?>
        <a href="<?php echo esc_url( get_permalink( $fellow ) ); ?>" style="color:var(--lime);font-weight:600;text-decoration:none"><?php echo esc_html( $fellow->post_title ); ?></a>
      <?php else : ?>
        a U-CAN Fellow
      <?php endif; ?>
      <span aria-hidden="true">·</span> <?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></p>
  </div>
</section>

<div class="backbar"><div class="wrap"><a href="<?php echo esc_url( home_url( '/blogs-by-our-fellows/' ) ); ?>">← Back to all posts</a></div></div>

<section class="sec" aria-labelledby="pb">
  <div class="wrap">
    <h2 id="pb" class="vh"><?php the_title(); ?></h2>
    <?php if ( has_post_thumbnail() ) : ?>
    <figure class="post-hero"><?php the_post_thumbnail( 'large', array( 'fetchpriority' => 'high', 'decoding' => 'async', 'onerror' => "this.closest('.post-hero').style.display='none'" ) ); ?></figure>
    <?php endif; ?>
    <div class="pwrap" style="margin-top:clamp(24px,3vw,36px)">
      <article class="post-body rv">
        <?php the_content(); ?>
      </article>
      <aside class="glance rv d1">
        <h2>About the author</h2>
        <?php if ( $fellow && has_post_thumbnail( $fellow->ID ) ) : ?>
          <?php echo get_the_post_thumbnail( $fellow->ID, 'thumbnail', array( 'style' => 'width:88px;height:88px;border-radius:50%;object-fit:cover;margin-bottom:14px', 'alt' => $fellow->post_title . ', U-CAN Fellow' ) ); ?>
        <?php endif; ?>
        <dl>
          <dt>Author</dt><dd><?php if ( $fellow ) : ?><a href="<?php echo esc_url( get_permalink( $fellow ) ); ?>" style="color:var(--teal-text);font-weight:600;text-decoration:none"><?php echo esc_html( $fellow->post_title ); ?></a><?php else : ?>—<?php endif; ?></dd>
          <?php if ( $fellow_fields && $fellow_fields['host_organisation'] ) : ?><dt>U-CAN Fellow at</dt><dd><?php echo esc_html( $fellow_fields['host_organisation'] ); ?></dd><?php endif; ?>
          <dt>Published</dt><dd><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></dd>
        </dl>
        <p style="margin-top:16px"><a href="<?php echo esc_url( home_url( '/blogs-by-our-fellows/' ) ); ?>" style="color:var(--teal-text);font-weight:600;font-size:14.5px;text-decoration:none">More posts by our Fellows →</a></p>
      </aside>
    </div>
  </div>
</section>

<section class="ctaband" aria-label="More posts">
  <div class="wrap">
    <p>Keep reading</p>
    <?php if ( $later ) : ?><a class="btn line" href="<?php echo esc_url( get_permalink( $later ) ); ?>">← Newer post</a><?php endif; ?>
    <a class="btn line" href="<?php echo esc_url( home_url( '/blogs-by-our-fellows/' ) ); ?>">All posts</a>
    <?php if ( $earlier ) : ?><a class="btn line" href="<?php echo esc_url( get_permalink( $earlier ) ); ?>">Older post →</a><?php endif; ?>
  </div>
</section>

<?php
endwhile;

get_footer();
