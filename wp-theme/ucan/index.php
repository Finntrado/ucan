<?php
/**
 * Fallback template WordPress requires every theme to have. Page-specific
 * templates (page-about.php, single-ucan_member.php, etc.) are added phase
 * by phase - see the plan file. Until a template exists for a given
 * page/post type, WP falls back to this.
 */
get_header();
?>
<div class="wrap sec">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <article <?php post_class(); ?>>
    <h1><?php the_title(); ?></h1>
    <div><?php the_content(); ?></div>
  </article>
<?php endwhile; endif; ?>
</div>
<?php
get_footer();
