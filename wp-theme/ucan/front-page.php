<?php
/**
 * Phase 0/2: Home. Content lifted verbatim from standalone/index.html's
 * <main> (see CLAUDE.md - content stays verbatim unless a named fix is
 * requested); only asset paths and internal links were rewritten to WP
 * functions. get_header() / get_footer() pull in header.php / footer.php,
 * which is where the actual <main id="main"> open/close tags live, so this
 * file supplies only what goes inside them.
 */
get_header();
?>




<!-- ===================== HERO ===================== -->

<section class="hero" aria-labelledby="h1">
  <canvas class="hero-field" aria-hidden="true"></canvas>

  <div class="wrap hero-grid">

    <div>

      <p class="eyebrow">Urban Collective Action Network</p>

      <h1 id="h1"><span class="hw" style="--i:0;--dx:-60px;--dy:-26px;--r:-6deg">Turning</span> <span class="hw" style="--i:1;--dx:44px;--dy:-40px;--r:5deg">individual</span> <span class="hw" style="--i:2;--dx:70px;--dy:18px;--r:7deg">effort</span> <span class="hw" style="--i:3;--dx:-30px;--dy:36px;--r:-4deg">into</span><br class="hbr"> <span class="accent"><span class="hw" style="--i:4;--dx:-46px;--dy:30px;--r:0deg">collective</span> <span class="hw" style="--i:5;--dx:52px;--dy:-28px;--r:0deg">action</span></span> <span class="hw" style="--i:6;--dx:-64px;--dy:22px;--r:-5deg">across</span><br class="hbr"> <span class="hw" style="--i:7;--dx:38px;--dy:40px;--r:4deg">India's</span> <span class="hw" style="--i:8;--dx:-24px;--dy:-34px;--r:-3deg">cities</span> <span class="hw" style="--i:9;--dx:60px;--dy:-16px;--r:6deg">and</span> <span class="hw" style="--i:10;--dx:-40px;--dy:30px;--r:-5deg">towns</span></h1>

      <p class="lede">U-CAN convenes urban practitioners, governments, researchers and philanthropies to champion liveable cities for their residents, and communicate the need to attract capital to the urban sector.</p>

      <div class="hero-actions">

        <a class="btn" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">Explore our work <span class="arrow" aria-hidden="true">→</span></a>

        <a class="btn ghost" href="<?php echo esc_url( home_url( '/our-members/' ) ); ?>">Meet our members</a>

      </div>

      <div class="hero-proof" id="hero-proof">

        <div class="pop"><b data-to="8">8</b><span>Members</span></div>

        <div class="pop"><b data-to="500" data-suffix="+">500+</b><span>Practitioners</span></div>

        <div class="pop"><b data-to="25" data-suffix="+">25+</b><span>Cities</span></div>

      </div>

    </div>

  </div>



  <!-- Photo band: real people, real forum -->

  <div class="photo-band">

    <div class="shot">

      <img loading="lazy" srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/practitioners-researchers-media-and-government-o-d8f17bc4d5-760.webp 760w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/practitioners-researchers-media-and-government-o-d8f17bc4d5.webp 1400w" sizes="100vw" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/practitioners-researchers-media-and-government-o-d8f17bc4d5.webp" width="1400" height="612" decoding="async" fetchpriority="low" alt="Practitioners, researchers, media and government officials at U-CAN's first Annual Forum">

    </div>

    <p class="cap">Key attendees at U-CAN's first Annual Forum</p>

  </div>



  <div class="wrap">

    <div class="capsule rv in">

      <p class="eyebrow">In brief</p>

      <p class="txt">U-CAN is a national network connecting urban practitioners, government officials, researchers and philanthropies working to build more liveable cities across India. Since launching, the network has brought together 500+ practitioners, 200+ government officials and 25+ cities to share what works and act on it together. Here's how U-CAN turns individual effort into collective action, and what it's helped build so far.</p>

    </div>

  </div>

</section>



<!-- ===================== WHY U-CAN ===================== -->

<section class="section" id="about" aria-labelledby="why-h">

  <!-- WHY:start --><div class="wrap why2"><div class="why2-head rv"><p class="eyebrow">Why U-CAN?</p><h2 id="why-h">India's urban challenges are too complex for any one organisation to solve alone, so we create the conditions to enable collaboration between organisations.</h2></div><div class="why2-grid"><div class="why2-story"><figure class="why2-scale rv"><svg viewBox="0 0 560 160" aria-hidden="true" focusable="false"><g class="w2-metro"><circle cx="46" cy="62" r="17" style="--d:0ms"/><circle cx="98" cy="38" r="13" style="--d:90ms"/><circle cx="92" cy="100" r="15" style="--d:180ms"/><circle cx="148" cy="72" r="12" style="--d:270ms"/><circle cx="40" cy="128" r="11" style="--d:360ms"/></g><g class="w2-towns"><circle cx="236.0" cy="22.0" r="2.6" style="--d:300ms"/><circle cx="250.6" cy="22.0" r="2.6" style="--d:307ms"/><circle cx="265.2" cy="22.0" r="2.6" style="--d:314ms"/><circle cx="279.8" cy="22.0" r="2.6" style="--d:321ms"/><circle cx="294.4" cy="22.0" r="2.6" style="--d:328ms"/><circle cx="309.0" cy="22.0" r="2.6" style="--d:335ms"/><circle cx="323.6" cy="22.0" r="2.6" style="--d:342ms"/><circle cx="338.2" cy="22.0" r="2.6" style="--d:349ms"/><circle cx="352.8" cy="22.0" r="2.6" style="--d:356ms"/><circle cx="367.4" cy="22.0" r="2.6" style="--d:363ms"/><circle cx="382.0" cy="22.0" r="2.6" style="--d:370ms"/><circle cx="396.6" cy="22.0" r="2.6" style="--d:377ms"/><circle cx="411.2" cy="22.0" r="2.6" style="--d:384ms"/><circle cx="425.8" cy="22.0" r="2.6" style="--d:391ms"/><circle cx="440.4" cy="22.0" r="2.6" style="--d:398ms"/><circle cx="455.0" cy="22.0" r="2.6" style="--d:405ms"/><circle cx="469.6" cy="22.0" r="2.6" style="--d:412ms"/><circle cx="484.2" cy="22.0" r="2.6" style="--d:419ms"/><circle cx="498.8" cy="22.0" r="2.6" style="--d:426ms"/><circle cx="513.4" cy="22.0" r="2.6" style="--d:433ms"/><circle cx="528.0" cy="22.0" r="2.6" style="--d:440ms"/><circle cx="542.6" cy="22.0" r="2.6" style="--d:447ms"/><circle cx="243.0" cy="36.4" r="2.6" style="--d:454ms"/><circle cx="257.6" cy="36.4" r="2.6" style="--d:461ms"/><circle cx="272.2" cy="36.4" r="2.6" style="--d:468ms"/><circle cx="286.8" cy="36.4" r="2.6" style="--d:475ms"/><circle cx="301.4" cy="36.4" r="2.6" style="--d:482ms"/><circle cx="316.0" cy="36.4" r="2.6" style="--d:489ms"/><circle cx="330.6" cy="36.4" r="2.6" style="--d:496ms"/><circle cx="345.2" cy="36.4" r="2.6" style="--d:503ms"/><circle cx="359.8" cy="36.4" r="2.6" style="--d:510ms"/><circle cx="374.4" cy="36.4" r="2.6" style="--d:517ms"/><circle cx="389.0" cy="36.4" r="2.6" style="--d:524ms"/><circle cx="403.6" cy="36.4" r="2.6" style="--d:531ms"/><circle cx="418.2" cy="36.4" r="2.6" style="--d:538ms"/><circle cx="432.8" cy="36.4" r="2.6" style="--d:545ms"/><circle cx="447.4" cy="36.4" r="2.6" style="--d:552ms"/><circle cx="462.0" cy="36.4" r="2.6" style="--d:559ms"/><circle cx="476.6" cy="36.4" r="2.6" style="--d:566ms"/><circle cx="491.2" cy="36.4" r="2.6" style="--d:573ms"/><circle cx="505.8" cy="36.4" r="2.6" style="--d:580ms"/><circle cx="520.4" cy="36.4" r="2.6" style="--d:587ms"/><circle cx="535.0" cy="36.4" r="2.6" style="--d:594ms"/><circle cx="549.6" cy="36.4" r="2.6" style="--d:601ms"/><circle cx="236.0" cy="50.8" r="2.6" style="--d:608ms"/><circle cx="250.6" cy="50.8" r="2.6" style="--d:615ms"/><circle cx="265.2" cy="50.8" r="2.6" style="--d:622ms"/><circle cx="279.8" cy="50.8" r="2.6" style="--d:629ms"/><circle cx="294.4" cy="50.8" r="2.6" style="--d:636ms"/><circle cx="309.0" cy="50.8" r="2.6" style="--d:643ms"/><circle cx="323.6" cy="50.8" r="2.6" style="--d:650ms"/><circle cx="338.2" cy="50.8" r="2.6" style="--d:657ms"/><circle cx="352.8" cy="50.8" r="2.6" style="--d:664ms"/><circle cx="367.4" cy="50.8" r="2.6" style="--d:671ms"/><circle cx="382.0" cy="50.8" r="2.6" style="--d:678ms"/><circle cx="396.6" cy="50.8" r="2.6" style="--d:685ms"/><circle cx="411.2" cy="50.8" r="2.6" style="--d:692ms"/><circle cx="425.8" cy="50.8" r="2.6" style="--d:699ms"/><circle cx="440.4" cy="50.8" r="2.6" style="--d:706ms"/><circle cx="455.0" cy="50.8" r="2.6" style="--d:713ms"/><circle cx="469.6" cy="50.8" r="2.6" style="--d:720ms"/><circle cx="484.2" cy="50.8" r="2.6" style="--d:727ms"/><circle cx="498.8" cy="50.8" r="2.6" style="--d:734ms"/><circle cx="513.4" cy="50.8" r="2.6" style="--d:741ms"/><circle cx="528.0" cy="50.8" r="2.6" style="--d:748ms"/><circle cx="542.6" cy="50.8" r="2.6" style="--d:755ms"/><circle cx="243.0" cy="65.2" r="2.6" style="--d:762ms"/><circle cx="257.6" cy="65.2" r="2.6" style="--d:769ms"/><circle cx="272.2" cy="65.2" r="2.6" style="--d:776ms"/><circle cx="286.8" cy="65.2" r="2.6" style="--d:783ms"/><circle cx="301.4" cy="65.2" r="2.6" style="--d:790ms"/><circle cx="316.0" cy="65.2" r="2.6" style="--d:797ms"/><circle cx="330.6" cy="65.2" r="2.6" style="--d:804ms"/><circle cx="345.2" cy="65.2" r="2.6" style="--d:811ms"/><circle cx="359.8" cy="65.2" r="2.6" style="--d:818ms"/><circle cx="374.4" cy="65.2" r="2.6" style="--d:825ms"/><circle cx="389.0" cy="65.2" r="2.6" style="--d:832ms"/><circle cx="403.6" cy="65.2" r="2.6" style="--d:839ms"/><circle cx="418.2" cy="65.2" r="2.6" style="--d:846ms"/><circle cx="432.8" cy="65.2" r="2.6" style="--d:853ms"/><circle cx="447.4" cy="65.2" r="2.6" style="--d:860ms"/><circle cx="462.0" cy="65.2" r="2.6" style="--d:867ms"/><circle cx="476.6" cy="65.2" r="2.6" style="--d:874ms"/><circle cx="491.2" cy="65.2" r="2.6" style="--d:881ms"/><circle cx="505.8" cy="65.2" r="2.6" style="--d:888ms"/><circle cx="520.4" cy="65.2" r="2.6" style="--d:895ms"/><circle cx="535.0" cy="65.2" r="2.6" style="--d:902ms"/><circle cx="549.6" cy="65.2" r="2.6" style="--d:909ms"/><circle cx="236.0" cy="79.6" r="2.6" style="--d:916ms"/><circle cx="250.6" cy="79.6" r="2.6" style="--d:923ms"/><circle cx="265.2" cy="79.6" r="2.6" style="--d:930ms"/><circle cx="279.8" cy="79.6" r="2.6" style="--d:937ms"/><circle cx="294.4" cy="79.6" r="2.6" style="--d:944ms"/><circle cx="309.0" cy="79.6" r="2.6" style="--d:951ms"/><circle cx="323.6" cy="79.6" r="2.6" style="--d:958ms"/><circle cx="338.2" cy="79.6" r="2.6" style="--d:965ms"/><circle cx="352.8" cy="79.6" r="2.6" style="--d:972ms"/><circle cx="367.4" cy="79.6" r="2.6" style="--d:979ms"/><circle cx="382.0" cy="79.6" r="2.6" style="--d:986ms"/><circle cx="396.6" cy="79.6" r="2.6" style="--d:993ms"/><circle cx="411.2" cy="79.6" r="2.6" style="--d:1000ms"/><circle cx="425.8" cy="79.6" r="2.6" style="--d:1007ms"/><circle cx="440.4" cy="79.6" r="2.6" style="--d:1014ms"/><circle cx="455.0" cy="79.6" r="2.6" style="--d:1021ms"/><circle cx="469.6" cy="79.6" r="2.6" style="--d:1028ms"/><circle cx="484.2" cy="79.6" r="2.6" style="--d:1035ms"/><circle cx="498.8" cy="79.6" r="2.6" style="--d:1042ms"/><circle cx="513.4" cy="79.6" r="2.6" style="--d:1049ms"/><circle cx="528.0" cy="79.6" r="2.6" style="--d:1056ms"/><circle cx="542.6" cy="79.6" r="2.6" style="--d:1063ms"/><circle cx="243.0" cy="94.0" r="2.6" style="--d:1070ms"/><circle cx="257.6" cy="94.0" r="2.6" style="--d:1077ms"/><circle cx="272.2" cy="94.0" r="2.6" style="--d:1084ms"/><circle cx="286.8" cy="94.0" r="2.6" style="--d:1091ms"/><circle cx="301.4" cy="94.0" r="2.6" style="--d:1098ms"/><circle cx="316.0" cy="94.0" r="2.6" style="--d:1105ms"/><circle cx="330.6" cy="94.0" r="2.6" style="--d:1112ms"/><circle cx="345.2" cy="94.0" r="2.6" style="--d:1119ms"/><circle cx="359.8" cy="94.0" r="2.6" style="--d:1126ms"/><circle cx="374.4" cy="94.0" r="2.6" style="--d:1133ms"/><circle cx="389.0" cy="94.0" r="2.6" style="--d:1140ms"/><circle cx="403.6" cy="94.0" r="2.6" style="--d:1147ms"/><circle cx="418.2" cy="94.0" r="2.6" style="--d:1154ms"/><circle cx="432.8" cy="94.0" r="2.6" style="--d:1161ms"/><circle cx="447.4" cy="94.0" r="2.6" style="--d:1168ms"/><circle cx="462.0" cy="94.0" r="2.6" style="--d:1175ms"/><circle cx="476.6" cy="94.0" r="2.6" style="--d:1182ms"/><circle cx="491.2" cy="94.0" r="2.6" style="--d:1189ms"/><circle cx="505.8" cy="94.0" r="2.6" style="--d:1196ms"/><circle cx="520.4" cy="94.0" r="2.6" style="--d:303ms"/><circle cx="535.0" cy="94.0" r="2.6" style="--d:310ms"/><circle cx="549.6" cy="94.0" r="2.6" style="--d:317ms"/><circle cx="236.0" cy="108.4" r="2.6" style="--d:324ms"/><circle cx="250.6" cy="108.4" r="2.6" style="--d:331ms"/><circle cx="265.2" cy="108.4" r="2.6" style="--d:338ms"/><circle cx="279.8" cy="108.4" r="2.6" style="--d:345ms"/><circle cx="294.4" cy="108.4" r="2.6" style="--d:352ms"/><circle cx="309.0" cy="108.4" r="2.6" style="--d:359ms"/><circle cx="323.6" cy="108.4" r="2.6" style="--d:366ms"/><circle cx="338.2" cy="108.4" r="2.6" style="--d:373ms"/><circle cx="352.8" cy="108.4" r="2.6" style="--d:380ms"/><circle cx="367.4" cy="108.4" r="2.6" style="--d:387ms"/><circle cx="382.0" cy="108.4" r="2.6" style="--d:394ms"/><circle cx="396.6" cy="108.4" r="2.6" style="--d:401ms"/><circle cx="411.2" cy="108.4" r="2.6" style="--d:408ms"/><circle cx="425.8" cy="108.4" r="2.6" style="--d:415ms"/><circle cx="440.4" cy="108.4" r="2.6" style="--d:422ms"/><circle cx="455.0" cy="108.4" r="2.6" style="--d:429ms"/><circle cx="469.6" cy="108.4" r="2.6" style="--d:436ms"/><circle cx="484.2" cy="108.4" r="2.6" style="--d:443ms"/><circle cx="498.8" cy="108.4" r="2.6" style="--d:450ms"/><circle cx="513.4" cy="108.4" r="2.6" style="--d:457ms"/><circle cx="528.0" cy="108.4" r="2.6" style="--d:464ms"/><circle cx="542.6" cy="108.4" r="2.6" style="--d:471ms"/><circle cx="243.0" cy="122.8" r="2.6" style="--d:478ms"/><circle cx="257.6" cy="122.8" r="2.6" style="--d:485ms"/><circle cx="272.2" cy="122.8" r="2.6" style="--d:492ms"/><circle cx="286.8" cy="122.8" r="2.6" style="--d:499ms"/><circle cx="301.4" cy="122.8" r="2.6" style="--d:506ms"/><circle cx="316.0" cy="122.8" r="2.6" style="--d:513ms"/><circle cx="330.6" cy="122.8" r="2.6" style="--d:520ms"/><circle cx="345.2" cy="122.8" r="2.6" style="--d:527ms"/><circle cx="359.8" cy="122.8" r="2.6" style="--d:534ms"/><circle cx="374.4" cy="122.8" r="2.6" style="--d:541ms"/><circle cx="389.0" cy="122.8" r="2.6" style="--d:548ms"/><circle cx="403.6" cy="122.8" r="2.6" style="--d:555ms"/><circle cx="418.2" cy="122.8" r="2.6" style="--d:562ms"/><circle cx="432.8" cy="122.8" r="2.6" style="--d:569ms"/><circle cx="447.4" cy="122.8" r="2.6" style="--d:576ms"/><circle cx="462.0" cy="122.8" r="2.6" style="--d:583ms"/><circle cx="476.6" cy="122.8" r="2.6" style="--d:590ms"/><circle cx="491.2" cy="122.8" r="2.6" style="--d:597ms"/><circle cx="505.8" cy="122.8" r="2.6" style="--d:604ms"/><circle cx="520.4" cy="122.8" r="2.6" style="--d:611ms"/><circle cx="535.0" cy="122.8" r="2.6" style="--d:618ms"/><circle cx="549.6" cy="122.8" r="2.6" style="--d:625ms"/><circle cx="236.0" cy="137.2" r="2.6" style="--d:632ms"/><circle cx="250.6" cy="137.2" r="2.6" style="--d:639ms"/><circle cx="265.2" cy="137.2" r="2.6" style="--d:646ms"/><circle cx="279.8" cy="137.2" r="2.6" style="--d:653ms"/><circle cx="294.4" cy="137.2" r="2.6" style="--d:660ms"/><circle cx="309.0" cy="137.2" r="2.6" style="--d:667ms"/><circle cx="323.6" cy="137.2" r="2.6" style="--d:674ms"/><circle cx="338.2" cy="137.2" r="2.6" style="--d:681ms"/><circle cx="352.8" cy="137.2" r="2.6" style="--d:688ms"/><circle cx="367.4" cy="137.2" r="2.6" style="--d:695ms"/><circle cx="382.0" cy="137.2" r="2.6" style="--d:702ms"/><circle cx="396.6" cy="137.2" r="2.6" style="--d:709ms"/><circle cx="411.2" cy="137.2" r="2.6" style="--d:716ms"/><circle cx="425.8" cy="137.2" r="2.6" style="--d:723ms"/><circle cx="440.4" cy="137.2" r="2.6" style="--d:730ms"/><circle cx="455.0" cy="137.2" r="2.6" style="--d:737ms"/><circle cx="469.6" cy="137.2" r="2.6" style="--d:744ms"/><circle cx="484.2" cy="137.2" r="2.6" style="--d:751ms"/><circle cx="498.8" cy="137.2" r="2.6" style="--d:758ms"/><circle cx="513.4" cy="137.2" r="2.6" style="--d:765ms"/><circle cx="528.0" cy="137.2" r="2.6" style="--d:772ms"/><circle cx="542.6" cy="137.2" r="2.6" style="--d:779ms"/></g></svg><figcaption><span><b>A handful</b> of metropolitan cities</span><span><b>Nearly 10,000</b> smaller towns and cities</span></figcaption></figure><div class="body-text"><p class="rv d1">Most of what shapes urban policy in India comes from a handful of metropolitan cities. But the real story, and much of the country's urban growth, is playing out in nearly 10,000 smaller towns and cities, often with little data and even less coordination between the people working to improve them.</p><p class="rv d2">U-CAN exists to be the connective tissue that's missing: a trusted space for practitioners, government, researchers and philanthropies to connect, learn from each other, and act together.</p></div></div><figure class="why2-quote rv d1"><span class="why2-mark" aria-hidden="true">&ldquo;</span><blockquote><p>The spark for U-CAN emerged from two intertwined realisations. First, we may be running out of time for slow, linear change; our cities need bold, accelerated action built on collective momentum. Second, when the right people and ideas meet in a shared conversation, they can catalyse transformative change at scale. U-CAN was born from that belief: a space for collaboration beyond boundaries.</p></blockquote><figcaption class="why2-by"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/shilpa-kumar-founding-member-of-u-can-and-managi-c9c0ed061c.webp" width="132" height="132" loading="lazy" decoding="async" alt="Shilpa Kumar, Founding Member of U-CAN and Managing Director &amp; Head of India at British International Investment"><span><b>Shilpa Kumar</b><em>Founding Member, U-CAN and Managing Director &amp; Head of India, British International Investment</em></span></figcaption></figure></div></div><!-- WHY:end -->



  <!-- ===================== WHAT WE DO ===================== -->

  <div class="wrap" id="what-we-do">

    <div class="cs-head rv in">

      <p class="eyebrow">What we do</p>

      <h2>Our 3Cs of Collective Action</h2>

    </div>

    <div class="cards-3">

      <article class="c-card rv in" tabindex="0" style="--accent:var(--teal);--wash:rgba(31,143,123,.10)">

        <span class="c-index" aria-hidden="true">01</span>

        <span class="c-ico" aria-hidden="true">

          <svg viewBox="0 0 40 40" role="presentation"><circle cx="20" cy="20" r="6.5"></circle><circle cx="8" cy="12" r="3.4"></circle><circle cx="32" cy="12" r="3.4"></circle><circle cx="8" cy="28" r="3.4"></circle><circle cx="32" cy="28" r="3.4"></circle><path d="M11 13.4 14.6 16M29 13.4 25.4 16M11 26.6 14.6 24M29 26.6 25.4 24"></path></svg>

        </span>

        <h3><span class="c-word">Convene</span>Create spaces to connect</h3>

        <p>We create safe, structured spaces, both online and in-person, for members to share what's worked, what hasn't, and where new collaborations can begin.</p>

      </article>

      <article class="c-card rv d1 in" tabindex="0" style="--accent:var(--teal-light);--wash:rgba(78,198,178,.14)">

        <span class="c-index" aria-hidden="true">02</span>

        <span class="c-ico" aria-hidden="true">

          <svg viewBox="0 0 40 40" role="presentation"><path d="M20 30V15"></path><path d="m13 21 7-7 7 7"></path><path d="M9 32h22"></path><circle cx="20" cy="9" r="3"></circle></svg>

        </span>

        <h3><span class="c-word">Champion</span>Align action, drive reform</h3>

        <p>We help members align strategies, pool resources and coordinate action around shared goals, working through the Urban Reforms Collective's engagement with government and a shared narrative on the role of Tier II and III cities in India's growth.</p>

      </article>

      <article class="c-card rv d2 in" tabindex="0" style="--accent:var(--teal-deep);--wash:rgba(14,83,72,.10)">

        <span class="c-index" aria-hidden="true">03</span>

        <span class="c-ico" aria-hidden="true">

          <svg viewBox="0 0 40 40" role="presentation"><path d="M8 16v8h5l8 6V10l-8 6z"></path><path d="M26 15a7 7 0 0 1 0 10M30 11a12 12 0 0 1 0 18"></path></svg>

        </span>

        <h3><span class="c-word">Communicate</span>Build the case for cities</h3>

        <p>We communicate cities as complex, interconnected systems, making the case for the human and financial capital needed to address urban challenges collectively.</p>

      </article>

    </div>

  </div>

</section>



<!-- ===================== IMPACT ===================== -->

<section class="section alt" id="impact" aria-labelledby="impact-h" style="padding-bottom:clamp(72px,9vw,116px)">

  <div class="wrap">

    <div class="section-head rv in">

      <p class="eyebrow">Impact</p>

      <h2 id="impact-h">What we've built together</h2>

    </div>

  </div>



  <div class="stats" id="stats">

    <div class="wrap">

      <p class="lab">At a glance</p>

      <div class="stats-row">

        <div class="stat pop">

          <span class="s-ico" aria-hidden="true"><svg viewBox="0 0 40 40"><path d="M20 8.5 8 15v10l12 6.5L32 25V15z" opacity=".28"></path><path d="M20 8.5 8 15v10l12 6.5L32 25V15z" fill="none" stroke-width="1.8"></path><path d="M13 12.5h5.4M13 16h5.4M13 19.5h5.4M22 12.5h5M22 16h5M22 19.5h5" stroke-width="1.6"></path></svg></span>

          <span class="s-kick">A network of</span>

          <b data-to="8">8</b>

          <span class="s-cap">Member organisations</span>

        </div>

        <div class="stat pop">

          <span class="s-ico" aria-hidden="true"><svg viewBox="0 0 40 40"><circle cx="20" cy="12" r="4.4"></circle><circle cx="9.5" cy="27" r="4"></circle><circle cx="30.5" cy="27" r="4"></circle><path d="M17 15.5 12 23.5M23 15.5l5 8M13.5 27h13" fill="none" stroke-width="1.8"></path></svg></span>

          <span class="s-kick">Bringing together</span>

          <b data-to="500" data-suffix="+">500+</b>

          <span class="s-cap">Practitioners connected</span>

        </div>

        <div class="stat pop">

          <span class="s-ico" aria-hidden="true"><svg viewBox="0 0 40 40"><path d="M20 5 32 11v2H8v-2z"></path><path d="M11 15v13M17 15v13M23 15v13M29 15v13" fill="none" stroke-width="2.4"></path><path d="M7 30h26v3H7z"></path></svg></span>

          <span class="s-kick">Working with</span>

          <b data-to="200" data-suffix="+">200+</b>

          <span class="s-cap">Government officials engaged</span>

        </div>

        <div class="stat pop">

          <span class="s-ico" aria-hidden="true"><svg viewBox="0 0 40 40"><path d="M20 5c-5.8 0-10.5 4.6-10.5 10.4C9.5 23 20 35 20 35s10.5-12 10.5-19.6C30.5 9.6 25.8 5 20 5z"></path><circle cx="20" cy="15.2" r="3.6" fill="var(--teal-deep)"></circle></svg></span>

          <span class="s-kick">Across</span>

          <b data-to="25" data-suffix="+">25+</b>

          <span class="s-cap">Cities represented</span>

        </div>

      </div>

    </div>

  </div>



  <div class="wrap">

    <div class="impact-grid">

      <article class="i-card rv no-ico in">

        <p class="n">01</p>

        <h3>Bringing policy and lived experience to the same table</h3>

        <p>The Annual Forum brought 100+ participants across 20+ organisations together, including government officials at the Ministry of Housing and Urban Affairs.</p>

      </article>

      <article class="i-card rv d1 no-ico in">

        <p class="n">02</p>

        <h3>Advancing women's voices in urban practice</h3>

        <p>The U-CAN Women's Fellowship's inaugural, all-women cohort of 6 professionals and 2 entrepreneurs worked through embedded practice and mentorship, on projects spanning Gurugram, Bengaluru, Jaipur and Chennai.</p>

      </article>

      <article class="i-card rv d2 no-ico in">

        <p class="n">03</p>

        <h3>Scaling a governance platform across borders</h3>

        <p>The Request for Collaboration initiative turned cross-organisation work into a governance platform now running in 3 Indian cities and Nairobi, Kenya.</p>

      </article>

      <article class="i-card rv d3 no-ico in">

        <p class="n">04</p>

        <h3>Reaching 15,000+ people on urban issues</h3>

        <p>Through 4 podcast episodes, 10+ webinars, and 30+ newsletters, U-CAN has built an audience for substantive conversation about India's urban challenges.</p>

      </article>

    </div>

    <div style="margin-top:clamp(36px,4vw,52px)">

      <a class="btn ghost" href="<?php echo esc_url( home_url( '/impact/' ) ); ?>">See our full impact <span class="arrow" aria-hidden="true">→</span></a>

    </div>

  </div>

</section>



<!-- ===================== OUR MEMBERS ===================== -->

<section class="section" id="members" aria-labelledby="members-h">

  <div class="wrap">

    <div class="section-head rv in" style="margin-bottom:clamp(30px,3.6vw,44px)">

      <p class="eyebrow">Our Members</p>

      <h2 id="members-h" class="vh">Our Members</h2>

    </div>

    <div>

      <ul class="logo-grid" aria-label="U-CAN member organisations">

        <li class="chip rv in"><a href="https://artha.global/" target="_blank" rel="noopener noreferrer" aria-label="Artha Global — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/artha-global-logo-cc6ae7093a.webp" alt="Artha Global logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv in"><a href="https://cprindia.org/" target="_blank" rel="noopener noreferrer" aria-label="Centre for Policy Research — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/centre-for-policy-research-logo-fc8445d7a7.webp" alt="Centre for Policy Research logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d1 in"><a href="https://egov.org.in/" target="_blank" rel="noopener noreferrer" aria-label="eGov Foundation — visit website">

          <img srcset="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/egov-foundation-logo-f623aa609f-800.webp 800w, <?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/egov-foundation-logo-f623aa609f.webp 1024w" sizes="100vw" width="1024" height="260" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/egov-foundation-logo-f623aa609f.webp" alt="eGov Foundation logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d1 in"><a href="https://www.janaagraha.org/" target="_blank" rel="noopener noreferrer" aria-label="Janaagraha — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/janaagraha-logo-985127bfc1.webp" alt="Janaagraha logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d2 in"><a href="https://praja.org/" target="_blank" rel="noopener noreferrer" aria-label="Praja Foundation — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/praja-foundation-logo-7ae1e89a9b.webp" alt="Praja Foundation logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d2 in"><a href="https://www.reapbenefit.org/" target="_blank" rel="noopener noreferrer" aria-label="Reap Benefit — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/reap-benefit-logo-e142694e13.webp" alt="Reap Benefit logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d3 in"><a href="https://shelter-associates.org/" target="_blank" rel="noopener noreferrer" aria-label="Shelter Associates — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/shelter-associates-logo-5ebfb355a7.webp" alt="Shelter Associates logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d3 in"><a href="https://wri-india.org/" target="_blank" rel="noopener noreferrer" aria-label="WRI India — visit website">

          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/wri-india-logo-3052bd5545.svg" alt="WRI India logo" loading="lazy" decoding="async" width="185" height="37">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

      </ul>



      <p class="eyebrow tier-friends">Friends of U-CAN</p>

      <ul class="logo-grid friends" aria-label="Friends of U-CAN">

        <li class="chip rv in"><a href="https://www.mahilahousingtrust.org/" target="_blank" rel="noopener noreferrer" aria-label="Mahila Housing Trust — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/mahila-housing-trust-logo-d50608d443.webp" alt="Mahila Housing Trust logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv in"><a href="https://iisc.ac.in/" target="_blank" rel="noopener noreferrer" aria-label="Indian Institute of Science — visit website">

          <img width="700" height="300" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/indian-institute-of-science-logo-13c84eb948.webp" alt="Indian Institute of Science logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

        <li class="chip rv d1 in"><a href="https://www.c40.org/" target="_blank" rel="noopener noreferrer" aria-label="C40 Cities — visit website">

          <img width="185" height="115" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/c40-cities-logo-8b3d4f418e.png" alt="C40 Cities logo" loading="lazy" decoding="async">

          <span class="go" aria-hidden="true">↗</span>

        </a></li>

      </ul>

    </div>

  </div>

</section>



<!-- ===================== STAY CONNECTED ===================== -->






<?php
get_footer();
