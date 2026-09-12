<?php
/**
 * Phase 4: replaces the 13 separate blog-tag-<slug>.html pages (was
 * blog-tag-air-quality.html etc, all sharing the identical layout except
 * the tag name and results). WP's own template hierarchy serves every
 * fellow_blog_tag term through this one file - no per-tag page needed,
 * and a term created later (phase 7's importer, or just an editor adding
 * a new topic in wp-admin) gets this template automatically.
 */

get_header();

global $wp_query;
$term  = get_queried_object();
$count = $wp_query->found_posts;

$other_tags = get_terms( array( 'taxonomy' => 'fellow_blog_tag', 'hide_empty' => true, 'exclude' => array( $term->term_id ) ) );
$other_tags = is_wp_error( $other_tags ) ? array() : $other_tags;
?>

<section class="hero" aria-labelledby="h1">
  <div class="hero-in">
    <p class="hero-tag">Blogs by Our Fellows</p>
    <h1 id="h1"><?php echo esc_html( $term->name ); ?></h1>
    <p class="hero-lede"><?php echo esc_html( $count ); ?> post<?php echo 1 === $count ? '' : 's'; ?> from the 2024-25 Fellowship cohort on <?php echo esc_html( mb_strtolower( $term->name ) ); ?>, newest first.</p>
  </div>
</section>

<section class="sec" aria-label="Posts tagged <?php echo esc_attr( $term->name ); ?>">
  <div class="wrap">
    <div class="fbar" role="group" aria-label="Browse other topics">
      <a class="filter-btn" href="<?php echo esc_url( home_url( '/blogs-by-our-fellows/' ) ); ?>">All posts<span class="c"><?php echo esc_html( ( new WP_Query( array( 'post_type' => 'ucan_blog', 'posts_per_page' => -1, 'fields' => 'ids' ) ) )->found_posts ); ?></span></a>
      <?php foreach ( $other_tags as $t ) : ?>
      <a class="filter-btn" href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a>
      <?php endforeach; ?>
    </div>
    <div class="bgrid">
      <?php while ( have_posts() ) : the_post();
        $fellow = ucan_blog_author( get_post() );
        ?>
      <a class="bcard rv" href="<?php the_permalink(); ?>"
        data-author="<?php echo esc_attr( $fellow ? $fellow->post_name : '' ); ?>" data-date="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
        <span class="bi"><?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title(), 'loading' => 'lazy', 'decoding' => 'async', 'onerror' => "this.closest('.bi').style.display='none'" ) ); ?></span>
        <span class="bb">
          <h3><?php the_title(); ?></h3>
          <span class="bx"><?php echo esc_html( get_the_excerpt() ); ?></span>
          <span class="btags"><?php foreach ( ucan_post_tags( get_the_ID() ) as $tg ) : ?><span class="tag" data-tag="<?php echo esc_attr( $tg->slug ); ?>" role="link" tabindex="0" title="See all posts tagged <?php echo esc_attr( $tg->name ); ?>"><?php echo esc_html( $tg->name ); ?></span><?php endforeach; ?></span>
          <span class="bm"><b><?php echo esc_html( $fellow ? $fellow->post_title : '' ); ?></b><span><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span></span>
        </span>
      </a>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<?php
get_footer();
