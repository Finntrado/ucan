<?php
/**
 * Template Name: Fellow Blogs
 * Auto-applies to a WP Page whose slug is "blogs-by-our-fellows" (file-name
 * convention - page-blogs-by-our-fellows.php).
 *
 * Hero extracted verbatim from standalone/fellow-blogs.html; the topic-tag
 * bar, the per-Fellow filter bar, and the 58-card grid are now live
 * queries against ucan_blog/ucan_fellow/fellow_blog_tag (CLAUDE.md §28) -
 * this also replaced the 13 separate blog-tag-*.html pages, since
 * get_term_link() now points each topic chip at the taxonomy archive
 * (taxonomy-fellow_blog_tag.php) instead of a hand-built page per tag.
 * Renders empty until real posts exist (phase 7's importer). ucan.js's
 * existing archive-filter handler and "Show all N posts" reveal button
 * are unchanged - the card markup they depend on (data-author/data-date,
 * `.bcard`, `#loadMore`/`#loadBtn`) is identical to the static version.
 * The page's own JSON-LD (already carrying correct absolute urban.org.in
 * canonical URLs) is re-emitted unchanged, so functions.php's generic
 * wp_head hook skips its own Organization node for this page.
 */

$ucan_page_meta = array(
	'description' => 'Read stories and reflections from U-CAN Fellows working on urban governance, climate action and public space design across Indian cities.',
);
$ucan_page_jsonld = '{
 "@context": "https://schema.org",
 "@graph": [
  {"@type": "Organization", "@id": "https://urban.org.in/#org",
   "name": "Urban Collective Action Network (U-CAN)", "alternateName": "U-CAN",
   "url": "https://urban.org.in/", "email": "connect@urban.org.in",
   "areaServed": {"@type": "Country", "name": "India"}},
  {"@type": "WebPage", "@id": "https://urban.org.in/blogs-by-our-fellows/#webpage", "url": "https://urban.org.in/blogs-by-our-fellows/",
   "name": "Blogs by Our Fellows | U-CAN", "description": "Stories and reflections from U-CAN Fellows working across Indian cities.", "inLanguage": "en-IN",
   "isPartOf": {"@id": "https://urban.org.in/#website"},
   "about": {"@id": "https://urban.org.in/#org"},
   "speakable": {"@type": "SpeakableSpecification", "cssSelector": ["h1", ".hero-lede"]}},
  {"@type": "BreadcrumbList", "@id": "https://urban.org.in/blogs-by-our-fellows/#breadcrumb", "itemListElement": [
   {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://urban.org.in/"},
   {"@type": "ListItem", "position": 2, "name": "U-CAN Fellowship", "item": "https://urban.org.in/u-can-fellowship/"},
   {"@type": "ListItem", "position": 3, "name": "Blogs by Our Fellows", "item": "https://urban.org.in/blogs-by-our-fellows/"}]}
 ]
}';

get_header();
?>


<section class="hero" aria-labelledby="pt">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">/</span> <a href="<?php echo esc_url( home_url( '/u-can-fellowship/' ) ); ?>">U-CAN Fellowship</a> <span aria-hidden="true">/</span> <span>Blogs by Our Fellows</span></nav>
    <p class="hero-tag">Blogs by U-CAN Fellows</p>
    <h1 id="pt">Blogs by Our Fellows</h1>
    <p class="hero-lede">Reflections and field notes from the U-CAN Fellowship, written by fellows
      working directly with host institutions across India's cities.</p>
  </div>
</section>

<?php
/**
 * Phase 4: dynamic replacement for the static 58-card archive - both
 * filter bars and the grid are now live queries. Renders nothing (and
 * "All Fellows 0") until ucan_blog/ucan_fellow posts exist (phase 7's
 * importer) - the topic-tag bar simply shows no chips if no
 * fellow_blog_tag terms have been created yet, same expected gap as
 * elsewhere in this phase (CLAUDE.md §28).
 *
 * The "Show all N posts" reveal button and each card's data-author/
 * data-date attributes are unchanged from the static markup on purpose -
 * ucan.js's existing archive-filter handler (already generalised to
 * `.issue, .bcard`, §21/§25) drives both the topic links and the
 * author-filter chips with no JS changes needed here.
 */
$all_posts = new WP_Query( array( 'post_type' => 'ucan_blog', 'posts_per_page' => -1 ) );
$total     = $all_posts->found_posts;

$tags = get_terms( array( 'taxonomy' => 'fellow_blog_tag', 'hide_empty' => true ) );
$tags = is_wp_error( $tags ) ? array() : $tags;

$fellows = get_posts( array( 'post_type' => 'ucan_fellow', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
?>
<section class="sec alt" aria-labelledby="al">
  <div class="wrap">
    <div class="sec-head rv">
      <p class="kicker" data-num="—">The archive</p>
      <h2 id="al">All posts</h2>
    </div>
    <?php if ( $tags ) : ?>
    <div class="fbar" role="group" aria-label="Browse posts by topic" style="margin-bottom:14px">
      <span class="lbl" style="font-size:.6875rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-soft);align-self:center;margin-right:.35rem">By topic</span>
      <?php foreach ( $tags as $tag ) : ?>
      <a class="filter-btn" href="<?php echo esc_url( get_term_link( $tag ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div class="fbar" role="group" aria-label="Filter posts by Fellow">
      <button type="button" class="filter-btn active" data-filter="all">All Fellows<span class="c"><?php echo esc_html( $total ); ?></span></button>
      <?php foreach ( $fellows as $fellow ) :
        $count = new WP_Query( array( 'post_type' => 'ucan_blog', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => 'fellow_id', 'meta_value' => $fellow->ID ) );
        if ( ! $count->found_posts ) { continue; }
        ?>
      <button type="button" class="filter-btn" data-filter="<?php echo esc_attr( $fellow->post_name ); ?>"><?php echo esc_html( $fellow->post_title ); ?><span class="c"><?php echo esc_html( $count->found_posts ); ?></span></button>
      <?php endforeach; ?>
    </div>
    <div class="bgrid">
      <?php while ( $all_posts->have_posts() ) : $all_posts->the_post();
        $fellow = ucan_blog_author( get_post() );
        ?>
      <a class="bcard rv" href="<?php the_permalink(); ?>"
        data-author="<?php echo esc_attr( $fellow ? $fellow->post_name : '' ); ?>" data-date="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
        <span class="bi"><?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title(), 'loading' => 'lazy', 'decoding' => 'async', 'onerror' => "this.closest('.bi').style.display='none'" ) ); ?></span>
        <span class="bb">
          <h3><?php the_title(); ?></h3>
          <span class="bx"><?php echo esc_html( get_the_excerpt() ); ?></span>
          <span class="btags"><?php foreach ( ucan_post_tags( get_the_ID() ) as $term ) : ?><span class="tag" data-tag="<?php echo esc_attr( $term->slug ); ?>" role="link" tabindex="0" title="See all posts tagged <?php echo esc_attr( $term->name ); ?>"><?php echo esc_html( $term->name ); ?></span><?php endforeach; ?></span>
          <span class="bm"><b><?php echo esc_html( $fellow ? $fellow->post_title : '' ); ?></b><span><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span></span>
        </span>
      </a>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <div class="moreWrap" id="loadMore">
      <button type="button" class="btn line" id="loadBtn">Show all <?php echo esc_html( $total ); ?> posts <span class="ar" aria-hidden="true">→</span></button>
    </div>
  </div>
</section>


<?php
get_footer();
