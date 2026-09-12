<?php
/**
 * Template Name: City Mixers
 * Auto-applies to a WP Page whose slug is "u-can-city-mixers" (file-name
 * convention - page-u-can-city-mixers.php).
 *
 * Hero/stats band extracted verbatim from standalone/city-mixers.html; the
 * 11-row mixer list is now a live ucan_mixer CPT query, newest first
 * (CLAUDE.md §28) - none of the rows link anywhere, matching the static
 * source (the old site never gave individual mixers their own URL
 * either). Renders empty until those posts exist (phase 7's importer).
 * The page's own JSON-LD (already carrying correct absolute urban.org.in
 * canonical URLs) is re-emitted unchanged, so functions.php's generic
 * wp_head hook skips its own Organization node for this page.
 */

$ucan_page_meta = array(
	'description' => 'U-CAN City Mixers bring together member organisations and urban practitioners for evenings of dialogue, film, and reflection on India\'s urban challenges.',
);
$ucan_page_jsonld = '{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "https://urban.org.in/#org",
      "name": "Urban Collective Action Network (U-CAN)",
      "url": "https://urban.org.in/",
      "email": "connect@urban.org.in"
    },
    {
      "@type": "WebPage",
      "@id": "https://urban.org.in/u-can-city-mixers/#page",
      "url": "https://urban.org.in/u-can-city-mixers/",
      "name": "U-CAN City Mixers | U-CAN",
      "description": "U-CAN City Mixers bring together member organisations and urban practitioners for evenings of dialogue, film, and reflection on India\'s urban challenges.",
      "isPartOf": {
        "@id": "https://urban.org.in/#org"
      },
      "inLanguage": "en-IN",
      "speakable": {
        "@type": "SpeakableSpecification",
        "cssSelector": [
          "h1",
          ".hero-lede"
        ]
      }
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://urban.org.in/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "U-CAN City Mixers",
          "item": "https://urban.org.in/u-can-city-mixers/"
        }
      ]
    },
    {
      "@type": "ItemList",
      "@id": "https://urban.org.in/u-can-city-mixers/#list",
      "name": "U-CAN City Mixers",
      "numberOfItems": 11,
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "item": {
            "@type": "Event",
            "name": "U-CAN City Mixer hosted by WRI India",
            "startDate": "April 11, 2025",
            "eventStatus": "https://schema.org/EventScheduled",
            "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
            "location": {
              "@type": "Place",
              "name": "Bangalore",
              "address": {
                "@type": "PostalAddress",
                "addressLocality": "Bangalore",
                "addressCountry": "IN"
              }
            },
            "organizer": {
              "@id": "https://urban.org.in/#org"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 2,
          "item": {
            "@type": "Event",
            "name": "U-CAN City Mixer hosted by Artha Global",
            "startDate": "April 14, 2025",
            "eventStatus": "https://schema.org/EventScheduled",
            "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
            "location": {
              "@type": "Place",
              "name": "Mumbai",
              "address": {
                "@type": "PostalAddress",
                "addressLocality": "Mumbai",
                "addressCountry": "IN"
              }
            },
            "organizer": {
              "@id": "https://urban.org.in/#org"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 3,
          "item": {
            "@type": "Event",
            "name": "U-CAN City Mixer hosted by Janaagraha",
            "startDate": "July 17, 2025",
            "eventStatus": "https://schema.org/EventScheduled",
            "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
            "location": {
              "@type": "Place",
              "name": "Bangalore",
              "address": {
                "@type": "PostalAddress",
                "addressLocality": "Bangalore",
                "addressCountry": "IN"
              }
            },
            "organizer": {
              "@id": "https://urban.org.in/#org"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 4,
          "item": {
            "@type": "Event",
            "name": "U-CAN City Mixer hosted by Praja Foundation",
            "startDate": "October 10, 2025",
            "eventStatus": "https://schema.org/EventScheduled",
            "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
            "location": {
              "@type": "Place",
              "name": "Mumbai",
              "address": {
                "@type": "PostalAddress",
                "addressLocality": "Mumbai",
                "addressCountry": "IN"
              }
            },
            "organizer": {
              "@id": "https://urban.org.in/#org"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 5,
          "item": {
            "@type": "Event",
            "name": "U-CAN City Mixer hosted by WRI India",
            "startDate": "December 12, 2025",
            "eventStatus": "https://schema.org/EventScheduled",
            "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
            "location": {
              "@type": "Place",
              "name": "New Delhi",
              "address": {
                "@type": "PostalAddress",
                "addressLocality": "New Delhi",
                "addressCountry": "IN"
              }
            },
            "organizer": {
              "@id": "https://urban.org.in/#org"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 6,
          "item": {
            "@type": "Event",
            "name": "U-CAN City Mixer hosted by Praja Foundation",
            "startDate": "December 19, 2025",
            "eventStatus": "https://schema.org/EventScheduled",
            "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
            "location": {
              "@type": "Place",
              "name": "Mumbai",
              "address": {
                "@type": "PostalAddress",
                "addressLocality": "Mumbai",
                "addressCountry": "IN"
              }
            },
            "organizer": {
              "@id": "https://urban.org.in/#org"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 7,
          "item": {
            "@type": "Event",
            "name": "U-CAN City Mixer hosted by Reap Benefit",
            "startDate": "January 30, 2026",
            "eventStatus": "https://schema.org/EventScheduled",
            "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
            "location": {
              "@type": "Place",
              "name": "Bangalore",
              "address": {
                "@type": "PostalAddress",
                "addressLocality": "Bangalore",
                "addressCountry": "IN"
              }
            },
            "organizer": {
              "@id": "https://urban.org.in/#org"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 8,
          "item": {
            "@type": "Event",
            "name": "U-CAN City Mixer hosted by Centre for Policy Research",
            "startDate": "February 20, 2026",
            "eventStatus": "https://schema.org/EventScheduled",
            "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
            "location": {
              "@type": "Place",
              "name": "New Delhi",
              "address": {
                "@type": "PostalAddress",
                "addressLocality": "New Delhi",
                "addressCountry": "IN"
              }
            },
            "organizer": {
              "@id": "https://urban.org.in/#org"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 9,
          "item": {
            "@type": "Event",
            "name": "U-CAN City Mixer hosted by eGov Foundation",
            "startDate": "April 10, 2026",
            "eventStatus": "https://schema.org/EventScheduled",
            "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
            "location": {
              "@type": "Place",
              "name": "Bangalore",
              "address": {
                "@type": "PostalAddress",
                "addressLocality": "Bangalore",
                "addressCountry": "IN"
              }
            },
            "organizer": {
              "@id": "https://urban.org.in/#org"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 10,
          "item": {
            "@type": "Event",
            "name": "U-CAN City Mixer hosted by Artha Global",
            "startDate": "April 24, 2026",
            "eventStatus": "https://schema.org/EventScheduled",
            "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
            "location": {
              "@type": "Place",
              "name": "Mumbai",
              "address": {
                "@type": "PostalAddress",
                "addressLocality": "Mumbai",
                "addressCountry": "IN"
              }
            },
            "organizer": {
              "@id": "https://urban.org.in/#org"
            }
          }
        },
        {
          "@type": "ListItem",
          "position": 11,
          "item": {
            "@type": "Event",
            "name": "U-CAN City Mixer hosted by Shelter Associates",
            "startDate": "May 15, 2026",
            "eventStatus": "https://schema.org/EventScheduled",
            "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
            "location": {
              "@type": "Place",
              "name": "Pune",
              "address": {
                "@type": "PostalAddress",
                "addressLocality": "Pune",
                "addressCountry": "IN"
              }
            },
            "organizer": {
              "@id": "https://urban.org.in/#org"
            }
          }
        }
      ]
    }
  ]
}';

get_header();
?>


<!-- HERO -->
<section class="hero" aria-labelledby="h1">
  <div class="hero-in">
    <nav class="crumb" aria-label="Breadcrumb">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">/</span><span>U-CAN City Mixers</span>
    </nav>
    <p class="hero-tag">Events</p>
    <h1 id="h1">U-CAN City Mixers</h1>
    <p class="hero-lede">U-CAN City Mixers bring together member organisations and urban practitioners for <b>evenings of learning, reflection, and dialogue</b>. These gatherings are designed to spark collaboration, share insights, and explore solutions for complex urban challenges.</p>
    <div class="hero-chips">
      <span>11 mixers since April 2025</span>
      <span>4 cities</span>
      <span>8 host organisations</span>
    </div>
  </div>
</section>

<!-- THE SERIES -->
<!-- THE SERIES -->
<?php
/**
 * Phase 5: dynamic replacement for the static 11-row mixer list - queries
 * the ucan_mixer CPT, newest first (matches the source's numbering,
 * counting down from 11 to 01). None of these rows link anywhere, same
 * as the static markup - see functions.php's ucan_register_mixer_cpt()
 * for why (the old site never gave individual mixers their own URL
 * either, with one unrelated exception handled as a ucan_etn_event post).
 */
$mixers = new WP_Query( array( 'post_type' => 'ucan_mixer', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC' ) );
$n = $mixers->found_posts;
?>
<section class="sec" id="series" aria-labelledby="series-h">
  <div class="wrap">
    <h2 class="vh" id="series-h">City Mixers</h2><div class="mxlist">
      <?php while ( $mixers->have_posts() ) : $mixers->the_post(); $f = ucan_mixer_display_fields( get_post() ); ?>
      <article class="mx rv in">
        <div class="mx-img">
          <?php if ( ! has_post_thumbnail() ) : ?><div class="ph" aria-hidden="true"><b><?php echo esc_html( $f['city'] ); ?></b><span>Photo loads on urban.org.in</span></div><?php endif; ?>
          <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'medium', array( 'width' => 640, 'height' => 480, 'loading' => 'lazy', 'decoding' => 'async', 'alt' => 'Guests in conversation at U-CAN City Mixer, ' . $f['host_organisation'] . ', ' . $f['city'] . ', ' . $f['date_display'] ) ); endif; ?>
        </div>
        <div class="mx-body">
          <p class="mx-meta"><span class="mx-n"><?php echo esc_html( str_pad( $n, 2, '0', STR_PAD_LEFT ) ); ?></span><span><?php echo esc_html( $f['date_display'] . ' · ' . $f['city'] ); ?></span></p>
          <h3><?php the_title(); ?></h3>
          <?php the_content(); ?>
        </div>
      </article>
      <?php $n--; endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="ctaband" aria-label="Explore more">
  <div class="wrap">
    <p>Want to host or join the next one?</p>
    <a class="btn" href="mailto:connect@urban.org.in">Write to us: connect@urban.org.in <span class="ar" aria-hidden="true">→</span></a>
    <a class="btn line" href="<?php echo esc_url( home_url( '/the-u-can-annual-forum-2025/' ) ); ?>">The Annual Forum <span class="ar" aria-hidden="true">→</span></a>
  </div>
</section>


<?php
get_footer();
