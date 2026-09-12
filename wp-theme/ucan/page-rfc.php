<?php
/**
 * Template Name: Request for Collaboration
 * Auto-applies to a WP Page whose slug is "rfc" (file-name
 * convention - page-rfc.php). Content lifted verbatim from
 * standalone/rfc.html's <main> (CLAUDE.md: content stays verbatim unless a
 * named fix is requested); only asset paths and internal links were
 * rewritten to WP functions. The page's own JSON-LD (already carrying
 * correct absolute urban.org.in canonical URLs) is re-emitted unchanged,
 * so functions.php's generic wp_head hook skips its own Organization node
 * for this page.
 */

$ucan_page_meta = array(
	'description' => 'The Request for Collaboration (RFC) is U-CAN\'s initiative exploring how organisations move from intent to practice, testing what real collaboration requires.',
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
      "@id": "https://urban.org.in/requests-for-collaboration/#page",
      "url": "https://urban.org.in/requests-for-collaboration/",
      "name": "Request for Collaboration (RFC) | U-CAN",
      "isPartOf": {
        "@id": "https://urban.org.in/#org"
      },
      "description": "The Request for Collaboration (RFC) is U-CAN\'s initiative exploring how organisations move from intent to practice, testing what real collaboration requires.",
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
          "name": "Request for Collaboration",
          "item": "https://urban.org.in/requests-for-collaboration/"
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
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">/</span><span>Request for Collaboration</span>
    </nav>
    <p class="hero-tag">A U-CAN Initiative</p>
    <h1 id="h1">Enabling Collaboration as a Way of Working in India's Urban Ecosystem</h1>
    <p class="hero-lede">The <b>Request for Collaboration (RFC)</b> is an initiative by U-CAN that explores how organisations can move from <b>intent to practice</b> when it comes to collaboration, through experimentation, reflection, and shared learning.</p>
    <div class="hero-actions">
      <a class="btn on-photo" href="https://urban.org.in/from-parellel-to-together-building-a-collaboration-practice-for-indias-urban-ecosystem" target="_blank" rel="noopener noreferrer">Read the Phase I Report <span class="ar" aria-hidden="true">→</span></a>
      <a class="btn ghost-photo" href="#what">What is the RFC?</a>
    </div>
  </div>
</section>

<!-- WHY COLLABORATION MATTERS -->
<section class="sec" id="why" aria-labelledby="why-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <p class="kicker" data-num="—">The case</p>
        <h2 id="why-h">Why Collaboration Matters for Organisations and Cities</h2>
      </div>
    </div>
    <div class="split">
      <div class="body rv in">
        <p>The ecosystem of organisations and individuals working to improve our cities often focus on specific parts of a larger challenge. While this specialisation creates depth and credibility, it can also mean that actors working toward similar goals <strong>rarely have structured opportunities to combine their strengths</strong>.</p>
        <p style="margin-top:22px"><strong>The consequences are visible across the urban landscape:</strong></p>
        <ul class="blist">
          <li>Similar problems addressed through disconnected efforts</li>
          <li>Duplication of ideas, pilots, and engagement with government stakeholders</li>
          <li>Partial solutions to systemic challenges</li>
          <li>Valuable expertise remaining under-leveraged</li>
          <li>Limited pathways for field-level learning and joint action</li>
        </ul>
      </div>
      <aside class="whyside rv d1">
        <div class="pq">
          <p>No single organisation, however capable, can fully address the complexity of urban challenges alone.</p>
        </div>
        <div class="strengths">
          <p class="st-k">What specialisation leaves on the table</p>
          <svg viewBox="0 0 300 150" role="img"
               aria-label="Three organisations working separately, overlapping where their strengths combine">
            <circle cx="112" cy="70" r="46" fill="#1F8F7B" fill-opacity=".16" stroke="#1F8F7B"/>
            <circle cx="150" cy="70" r="46" fill="#1F8F7B" fill-opacity=".16" stroke="#1F8F7B"/>
            <circle cx="188" cy="70" r="46" fill="#1F8F7B" fill-opacity=".16" stroke="#1F8F7B"/>
            <text x="150" y="140" text-anchor="middle" class="st-t">Combined strengths</text>
          </svg>
          <ul class="st-list">
            <li>Depth</li><li>Credibility</li><li>Structured opportunities</li>
          </ul>
        </div>
      </aside>
    </div>

    <div class="body rv in" style="margin-top:clamp(34px,4.5vw,58px)">
      <p class="callout" style="max-width:70ch">Most organisations recognise the value of collaboration. But fewer are able to sustain it meaningfully. This is because:</p>
    </div>
    <div class="aims" style="margin-top:clamp(20px,2.6vw,30px)">
      <article class="aim rv">
        <span class="n">01</span>
        <p>Funding structures prioritise short-term, project-based outputs</p>
      </article>
      <article class="aim rv d1">
        <span class="n">02</span>
        <p>Organisational systems are optimised for individual delivery, not shared work</p>
      </article>
      <article class="aim rv d2">
        <span class="n">03</span>
        <p>Collaboration requires time, trust, and flexibility, often without immediate returns</p>
      </article>
    </div>
  </div>
</section>

<!-- WHAT IS THE RFC -->
<section class="sec alt" id="what" aria-labelledby="what-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <p class="kicker" data-num="—">The initiative</p>
        <h2 id="what-h">What is the RFC initiative?</h2>
        <p class="lead">The Request for Collaboration was created by U-CAN to explore what it takes to move collaboration from intent to practice. Rather than prescribing solutions, the RFC was designed to enable conditions where collaboration could emerge and evolve organically.</p>
      </div>
    </div>
    <p class="kicker" style="margin-bottom:6px">Core objectives</p>
    <div class="aims">
      <article class="aim icard rv">
        <div class="ihead"><span class="icb"><svg class="ic" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 21v-7.4"/><path d="M12 13.6C12 10 9.4 7.4 5.8 7.4c0 3.6 2.6 6.2 6.2 6.2z"/><path d="M12 12.4c0-3.3 2.4-5.7 5.7-5.7 0 3.3-2.4 5.7-5.7 5.7z"/><path d="M8 21h8"/></svg></span><h3>Enable early-stage collaboration</h3></div>
        <p>Support organisations to jointly explore ideas without rigid deliverables</p>
      </article>
      <article class="aim icard rv d1">
        <div class="ihead"><span class="icb"><svg class="ic" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9.6 3.2v5.5L4.9 17a2.4 2.4 0 0 0 2.1 3.6h10a2.4 2.4 0 0 0 2.1-3.6l-4.7-8.3V3.2"/><path d="M8.4 3.2h7.2"/><path d="M7 14.4h10"/></svg></span><h3>Support experimentation and learning</h3></div>
        <p>Allow teams to iterate and adapt as insights emerge</p>
      </article>
      <article class="aim icard rv d2">
        <div class="ihead"><span class="icb"><svg class="ic" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="5.5" cy="5.8" r="2.4"/><circle cx="18.5" cy="18.2" r="2.4"/><path d="M8 5.8h5.5a3.6 3.6 0 0 1 0 7.2h-3a3.6 3.6 0 0 0 0 7.2H16"/></svg></span><h3>Build collaborative capacity</h3></div>
        <p>Help organisations learn how to work across boundaries</p>
      </article>
    </div>
  </div>
</section>

<!-- FRAMEWORK -->
<section class="sec" id="framework" aria-labelledby="fw-h">
  <div class="wrap">
    <div class="split">
      <div class="body rv in">
        <p class="kicker" data-num="—">What we learned</p>
        <h2 id="fw-h">A Practice-informed framework for collaboration</h2>
        <div style="height:22px"></div>
        <p>The RFC has created an opportunity to observe how collaboration unfolded through the first phase of the initiative, and inform the creation of a <strong>practice-informed framework</strong> that will evolve with the RFC initiative in subsequent phases.</p>
        <div class="hero-actions" style="margin-top:28px">
          <a class="btn" href="https://urban.org.in/from-parellel-to-together-building-a-collaboration-practice-for-indias-urban-ecosystem" target="_blank" rel="noopener noreferrer">Read the report <span class="ar" aria-hidden="true">→</span></a>
        </div>
        
      </div>
      <div class="fw rv d1 in">
        <div class="ph" aria-hidden="true"><b>Framework</b><span>Diagram loads on urban.org.in</span></div>
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/wp/framework-bfd6050b.png" alt="The RFC practice-informed framework for collaboration" loading="lazy" decoding="async" onerror="this.style.display='none'">
      </div>
    </div>
  </div>
</section>

<!-- KNOW MORE -->
<section class="ctaband" id="know-more" aria-label="Know more">
  <div class="wrap">
    <a class="btn" href="mailto:connect@urban.org.in">Know more about the initiative: connect@urban.org.in <span class="ar" aria-hidden="true">→</span></a>
  </div>
</section>


<?php
get_footer();
