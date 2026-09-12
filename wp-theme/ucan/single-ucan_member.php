<?php
/**
 * Phase 3: single member profile (was profile-<slug>.html, CLAUDE.md §14).
 * Layout lifted verbatim from profile-siddharth-pandit.html /
 * profile-gautham-ravichander.html's <main> - the two display branches
 * ("Our Team" vs everyone else) are computed once by
 * ucan_member_display_fields() in functions.php and used identically here.
 *
 * Bio paragraphs are the post_content (editable in wp-admin as normal);
 * everything else (role/org/LinkedIn/group) is the meta + taxonomy set up
 * in functions.php's ucan_register_member_cpt().
 */

get_header();

while ( have_posts() ) :
	the_post();
	$f = ucan_member_display_fields( get_post() );
	?>

<section class="prof-hero" aria-labelledby="h1">
  <div class="prof-in">
    <div class="prof-photo">
      <?php if ( has_post_thumbnail() ) : ?>
        <?php the_post_thumbnail( 'thumbnail', array( 'width' => 220, 'height' => 220, 'fetchpriority' => 'high', 'decoding' => 'async', 'alt' => get_the_title() ) ); ?>
      <?php else : ?>
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/monogram-placeholder.svg" width="220" height="220" alt="<?php echo esc_attr( get_the_title() ); ?>">
      <?php endif; ?>
      <span class="badge"><?php echo esc_html( $f['primary_group'] ); ?></span>
    </div>
    <div>
      <nav class="prof-crumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">/</span>
        <a href="<?php echo esc_url( home_url( '/our-people/' ) ); ?>">Our People</a><span aria-hidden="true">/</span>
        <span><?php the_title(); ?></span>
      </nav>
      <p class="prof-tag"><?php echo esc_html( $f['primary_group'] ); ?></p>
      <h1 id="h1"><?php the_title(); ?></h1>
      <p class="prof-role"><?php echo esc_html( $f['role'] ); ?></p>
      <p class="prof-affil"><?php echo esc_html( $f['affiliation'] ); ?></p>
      <div class="prof-actions">
        <a class="btn on-photo sm" href="<?php echo esc_url( home_url( '/our-people/' ) ); ?>"><span aria-hidden="true">←</span> All people</a>
        <?php if ( $f['linkedin'] ) : ?>
        <a class="btn line sm" href="<?php echo esc_url( $f['linkedin'] ); ?>" target="_blank" rel="noopener noreferrer">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.25 8h4.5v12.5H.25V8zm7.5 0h4.3v1.71h.06c.6-1.14 2.07-2.34 4.26-2.34 4.56 0 5.4 3 5.4 6.9v7.73h-4.5v-6.85c0-1.63-.03-3.73-2.27-3.73-2.27 0-2.62 1.78-2.62 3.61v6.97h-4.5V8z"></path></svg>
          LinkedIn</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section class="sec" id="bio" aria-labelledby="bio-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <p class="kicker" data-num="—">Profile</p>
        <h2 id="bio-h">About <?php echo esc_html( get_the_title() ); ?></h2>
      </div>
    </div>
    <div class="bio">
      <div class="bio-body rv in">
        <?php the_content(); ?>
      </div>
      <aside class="bio-aside rv d1 in">
        <h2>At a glance</h2>
        <dl>
          <div class="fact"><dt>Role</dt><dd><?php echo esc_html( $f['role'] ); ?></dd></div>
          <div class="fact"><dt>Group</dt><dd><?php echo esc_html( $f['all_groups'] ); ?></dd></div>
          <div class="fact"><dt>Organisation</dt><dd><?php echo esc_html( $f['organisation'] ); ?></dd></div>
          <div class="fact"><dt>Connect</dt><dd><?php echo $f['linkedin'] ? sprintf( '<a href="%s" target="_blank" rel="noopener noreferrer">LinkedIn ↗</a>', esc_url( $f['linkedin'] ) ) : '—'; ?></dd></div>
        </dl>
      </aside>
    </div>
  </div>
</section>

<section class="backbar" aria-label="Explore more">
  <div class="wrap">
    <p>Explore the network</p>
    <a class="btn" href="<?php echo esc_url( home_url( '/our-people/' ) ); ?>">Back to Our People <span class="ar" aria-hidden="true">→</span></a>
    <a class="btn line" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">About U-CAN <span class="ar" aria-hidden="true">→</span></a>
  </div>
</section>

<?php
endwhile;

get_footer();
