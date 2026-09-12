<?php
/**
 * Template Name: Urban Reforms Collective
 * Auto-applies to a WP Page whose slug is "urban-reforms-collective" (file-name
 * convention - page-urban-reforms-collective.php). Content lifted verbatim from
 * standalone/urban-reforms-collective.html's <main> (CLAUDE.md: content stays verbatim unless a
 * named fix is requested); only asset paths and internal links were
 * rewritten to WP functions. The page's own JSON-LD (already carrying
 * correct absolute urban.org.in canonical URLs) is re-emitted unchanged,
 * so functions.php's generic wp_head hook skips its own Organization node
 * for this page.
 */

$ucan_page_meta = array(
	'description' => 'The Urban Reforms Collective (URC) is a pan-India platform convened by U-CAN, bringing organisations together to shape and champion systemic urban reforms.',
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
      "@id": "https://urban.org.in/urban-reforms-collective/#page",
      "url": "https://urban.org.in/urban-reforms-collective/",
      "name": "Urban Reforms Collective (URC) | U-CAN",
      "isPartOf": {
        "@id": "https://urban.org.in/#org"
      },
      "description": "The Urban Reforms Collective (URC) is a pan-India platform convened by U-CAN, bringing organisations together to shape and champion systemic urban reforms.",
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
          "name": "Urban Reforms Collective",
          "item": "https://urban.org.in/urban-reforms-collective/"
        }
      ]
    },
    {
      "@type": "FAQPage",
      "@id": "https://urban.org.in/urban-reforms-collective/#faq",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What is U-CAN?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The Urban Collective Action Network (U-CAN) is a network of organisations working to strengthen urban problem-solving in emerging Indian cities. It brings together actors across sectors to create spaces where practitioners, communities, and policymakers can engage with each other, fostering more collaborative approaches to addressing urban challenges. This, in turn, supports more inclusive governance and contributes to more livable cities. U-CAN’s approach is grounded in collective action, a multisectoral systems lens, and a strong emphasis on citizen participation in policymaking. It serves as the backbone for the URC — supporting coordination, convening organisations, and enabling the collective to function effectively. The U-CAN Secretariat plays a key role in facilitating collaboration, maintaining alignment, and supporting implementation."
          }
        },
        {
          "@type": "Question",
          "name": "How is the URC related to U-CAN? What is U-CAN’s role in URC?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The URC is an initiative of U-CAN, and will serve as its backbone organisation (or secretariat). The URC is also one of U-CAN’s three identified strategic goals, reflecting its focus on enabling collective action towards urban reforms. The U-CAN secretariat is responsible for day-to-day coordination, ensuring that the Collective remains aligned and action-oriented."
          }
        },
        {
          "@type": "Question",
          "name": "Why do we need an Urban Reforms Collective? Why are policy reforms for our cities important, and why now?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Improving cities isn’t just about better infrastructure projects, but also developing better integrated policies and delivering efficient and citizen-centric governance. Policy reforms matter because they shape how programmes are designed, how institutions function, and how services are delivered by the functionaries. Without innovation and improvements in the way policies are designed and developed, even well-intended government initiatives struggle to scale or sustain impact. Moreover, the urban sector continues to have a huge backlog of reforms to usher in good quality of life for residents of Indian cities. At the same time, there is a growing recognition across the sector that collaboration is no longer optional. Many organisations are working on similar urban challenges, but often without enough alignment or active coordination. The URC emerged from this moment, as a way to build a shared reform agenda, strengthen collective voice, and enable more coordinated engagement with decision-makers."
          }
        },
        {
          "@type": "Question",
          "name": "What activities will be undertaken by the URC? What reforms will the URC prioritise?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The URC’s work is centred on enabling a stronger, more connected field around urban governance reform. This includes creating spaces for organisations to learn from each other, share insights from research and practice, and build a deeper understanding of urban governance reforms. It also involves bringing together actors across the ecosystem, from civil society to research institutions, and enabling more meaningful collaboration for engagement with key stakeholders in the government and the allied eco-system. A key priority will be to co-create a shared reform agenda and policy engagement roadmap for long-term and short-term, and aligning efforts across organisations to champion this agenda. Alongside this, the URC will work to build the infrastructure that collaboration requires, such as working groups, communication channels, and coordination mechanisms, and explore ways to better align resources in support of shared priorities. The URC’s reform priorities will be developed with input from all members. This will involve collective brainstorming to shape the agenda to focus on systemic improvements in urban governance, service delivery, and policy design. Specific priorities will evolve over time based on collective input and emerging opportunities."
          }
        },
        {
          "@type": "Question",
          "name": "How does my organisation become part of the URC?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Joining the URC involves a simple but curated process: Submitting an expression of interest through an email to reforms@urban.org.in Participating in a follow-up conversation with the URC team. Membership and its level is curated to ensure alignment with the Collective’s goals. Acceptance and membership into the network will be decided by mutual agreement and alignment of both parties."
          }
        },
        {
          "@type": "Question",
          "name": "Why should my organisation join the Urban Reforms Collective?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Every member organisation\'s agenda moves faster collectively than individually. The government is more likely to act when it hears the same ask from multiple credible organisations than when it hears competing asks from separate ones. Members get a platform and collective legitimacy to amplify their own work. Their research reaches more decision-makers, government relationships become part of a larger strategic map, and their voice on urban reform carries more weight when it is visibly part of a coordinated collective. Finally, members get access to a shared evidence base, political economy intelligence, and peer learning across organisations — resources that would cost any single organisation significantly more to build alone."
          }
        },
        {
          "@type": "Question",
          "name": "Are there categories or levels of membership/participation?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The URC brings together organisations with different strengths, recognising that meaningful reform requires complementary expertise and roles. a. Anchor Partners are organisations with deep expertise and experience in urban governance, particularly in research, policy engagement, and institutional reform. – They play a central role in shaping the reform agenda, contributing technical inputs, and engaging with policymakers. – Anchor Partners also serve on the Coordination Committee by rotation and set the overall direction of the Collective. b. Affiliate Organisations are organisations committed to advancing urban reforms, but may be limited by bandwidth, being new to policy engagement or to the urban sector. They contribute in a range of ways, from participating in discussions and learning spaces, to amplifying key messages, to actively engaging in inputting into shaping reforms, strategy and collaborative initiatives. Organisations other than those eligible to play the role of Anchor Partners join as Affiliates. Over time, based on engagement, contribution, and readiness, and after a minimum engagement period of 15 months, affiliate organisations could move to become Anchor Partners."
          }
        },
        {
          "@type": "Question",
          "name": "What does my organisation need to commit to?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The URC is built on voluntary association, flexibility in participation based on capacities and shared ownership. Organisations are expected to: Contribute to co-creation of reform priorities Participate actively in discussions, convenings, and collaborative outreach activities Engage according to their capacity and areas of expertise/focus The Collective values flexibility, allowing organisations to contribute in ways that align with their expertise and resources. It will work on the tenet of ‘commit what you can deliver, and deliver what you commit."
          }
        },
        {
          "@type": "Question",
          "name": "Is there a fee or financial implication to join the URC?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Currently there is no fee or financial implication. However, there is an expectation of time and human resources to build the collective and its joint efforts. Organisations will have to defray their own costs to participate in the convenings, deliberations and outreach activities of the collective."
          }
        },
        {
          "@type": "Question",
          "name": "Who can I speak to for more information?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "You can reach out to the team at reforms@urban.org.in for any further questions"
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
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">/</span><span>Urban Reforms Collective</span>
    </nav>
    <p class="hero-tag">A U-CAN Initiative</p>
    <h1 id="h1">Championing a Collective Reform Agenda for India's Cities</h1>
    <p class="hero-lede">India's urban transition presents a historic opportunity alongside deep structural challenges. Convened by U-CAN, the <b>Urban Reforms Collective (URC)</b> is a pan-India collaborative platform that brings together organisations to collectively <b>shape, advance, and champion systemic urban reforms</b>.</p>
    <div class="hero-actions">
      <a class="btn on-photo" href="#faq">Read the FAQs <span class="ar" aria-hidden="true">→</span></a>
      <a class="btn ghost-photo" href="#involved">Get involved</a>
    </div>
  </div>
</section>

<!-- NEED FOR A URC -->
<section class="sec" id="need" aria-labelledby="need-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <p class="kicker" data-num="—">The gap</p>
        <h2 id="need-h">Need for an Urban Reforms Collective</h2>
      </div>
    </div>
    <div class="split coord"><div class="body rv in">
        <p>India's urban civil society has no shortage of expertise, but has historically operated in fragments. Organisations working on pieces of the same problem have done so without a coordinated joint agenda, resulting in duplicated efforts, fragmented evidence, and reform asks that sometimes conflict once they reach government on the same issue. The result is that the cumulative weight of civil society's work on urban reform has never landed with the force it should. The Urban Reforms Collective (URC) exists to fix the coordination failure, not to replace the organisations solving the substantive problems.</p>
      </div>
      <aside class="coordfig rv d1" aria-labelledby="coord-h">
        <p class="coord-k" id="coord-h">The coordination failure</p>
        <svg viewBox="0 0 340 150" role="img"
             aria-label="Separate organisations working on pieces of the same problem, converging into one coordinated agenda">
          <g class="cf-dots" fill="none" stroke="#1F8F7B" stroke-width="1.6">
            <circle cx="30" cy="26" r="7"/><circle cx="66" cy="60" r="7"/>
            <circle cx="26" cy="96" r="7"/><circle cx="72" cy="124" r="7"/>
            <circle cx="104" cy="40" r="7"/><circle cx="110" cy="104" r="7"/>
          </g>
          <g class="cf-rays" stroke="#4EC6B2" stroke-width="1.2" stroke-linecap="round" opacity=".75">
            <path d="M37 26h150"/><path d="M73 60h114"/><path d="M33 96h154"/>
            <path d="M79 124h108"/><path d="M111 40h76"/><path d="M117 104h70"/>
          </g>
          <circle class="cf-hub" cx="206" cy="75" r="19" fill="#0E5348"/>
          <path class="cf-arrow" d="M232 75h74" stroke="#0E5348" stroke-width="2.4"
                stroke-linecap="round"/>
          <path class="cf-arrow" d="m297 66 10 9-10 9" fill="none" stroke="#0E5348"
                stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <ul class="coord-list">
          <li>Duplicated efforts</li>
          <li>Fragmented evidence</li>
          <li>Reform asks that conflict</li>
        </ul>
        <p class="coord-out">A coordinated joint agenda</p>
      </aside>
    </div>
    </div>
</section>

<!-- WHAT THE URC AIMS TO DO -->
<section class="sec alt" id="aims" aria-labelledby="aims-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <p class="kicker" data-num="—">The work</p>
        <h2 id="aims-h">What the URC aims to do</h2>
      </div>
    </div>
    <div class="body cols2 rv in">
      <p>The Urban Reforms Collective's (URC) work is centred on enabling a stronger, more connected field around urban governance reform. A key priority will be to co-create a shared reform agenda and policy engagement roadmap for long-term and short-term, and aligning efforts across organisations to champion this agenda. The URC's reform priorities will be developed with input from all members.</p>
      <p>Alongside this, the URC will work to build the infrastructure that collaboration requires, and explore ways to better align resources in support of shared priorities.</p>
    </div>
    <div class="aims">
      <article class="aim rv">
        <span class="n">01</span>
        <h3>A shared reform agenda</h3>
        <p>Co-creating a shared and actionable reform agenda for cities.</p>
      </article>
      <article class="aim rv d1">
        <span class="n">02</span>
        <h3>A more aligned ecosystem</h3>
        <p>Building a stronger and more aligned ecosystem for sustained urban transformation.</p>
      </article>
      <article class="aim rv d2">
        <span class="n">03</span>
        <h3>Greater public salience</h3>
        <p>Amplifying the salience of urban reforms within public and policy discourse.</p>
      </article>
    </div>
  </div>
</section>

<!-- PRIORITIES AHEAD -->
<section class="sec" id="priorities" aria-labelledby="pri-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <p class="kicker" data-num="—">Next 12–18 months</p>
        <h2 id="pri-h">Priorities Ahead</h2>
        <p class="lead">Over the next 12 to 18 months, URC's work will centre on three things:</p>
      </div>
    </div>
    <div class="aims">
      <article class="aim rv">
        <span class="n">01</span>
        <p>First, building a foundational, shared understanding of the reforms Indian cities need, covering decentralisation and participatory governance, institutional capacity, inclusive planning systems, and service delivery, then translating that into specific, actionable reform suggestions pitched at different levels of government and, where possible, tailored to a state's or city's specific context.</p>
      </article>
      <article class="aim rv d1">
        <span class="n">02</span>
        <p>Second, building real buy-in for these ideas through sustained engagement with elected representatives and bureaucrats at the national, state, and local level, as well as constitutional institutions, political influencers, media, and citizens. Buy-in here means something concrete: a change in law or policy, or funds and functionaries actually allocated to make reform possible. That requires identifying the government engagements different organisations are already having, and embedding urban reforms within them.</p>
      </article>
      <article class="aim rv d2">
        <span class="n">03</span>
        <p>Third, growing the range of voices URC represents, by bringing in more organisations from more geographies and sectors across urban India.</p>
      </article>
    </div>
  </div>
</section>

<!-- WHAT MEMBERS GAIN -->
<section class="sec alt" id="members-gain" aria-labelledby="mg-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <p class="kicker" data-num="—">The value exchange</p>
        <h2 id="mg-h">What Members Gain</h2>
      </div>
    </div>
    <div class="aims">
      <article class="aim rv">
        <span class="n">01</span>
        <p>Every member organisation's agenda moves faster collectively than individually. The government is more likely to act when it hears the same ask from multiple credible organisations than when it hears competing asks from separate ones.</p>
      </article>
      <article class="aim rv d1">
        <span class="n">02</span>
        <p>Members get a platform and collective legitimacy to amplify their own work. Their research reaches more decision-makers, government relationships become part of a larger strategic map, and their voice on urban reform carries more weight when it is visibly part of a coordinated collective.</p>
      </article>
      <article class="aim rv d2">
        <span class="n">03</span>
        <p>Finally, members get access to a shared evidence base, political economy intelligence, and peer learning across organisations. These are resources that would cost any single organisation significantly more to build alone.</p>
      </article>
    </div>
  </div>
</section>

<!-- MUMBAI DECLARATION -->
<section class="sec" id="mumbai-declaration" aria-labelledby="md-h">
  <div class="wrap">
    <div class="split rv in">
      <div class="body">
        <p class="kicker" data-num="—">1 June 2026 · Mumbai</p>
        <h2 id="md-h" style="font-size:clamp(27px,3.6vw,40px);letter-spacing:-.02em;max-width:20ch">The Mumbai Declaration</h2>
        <p>On 1 June 2026, marking 33 years of the 74th Constitutional Amendment, the founding members of the Urban Reforms Collective signed the Mumbai Declaration, reaffirming that India's path to becoming a developed nation runs through its cities, and that no single institution, organisation, or sector can transform them alone.</p>
        <p>As the Urban Reforms Collective, signatories pledged to:</p>
        <ol style="margin:14px 0 0;padding-left:20px;color:var(--ink-soft);font-size:15.5px;line-height:1.7">
          <li>Advance a shared and actionable reform agenda for urban governance</li>
          <li>Collaborate across different dimensions of urban transformation</li>
          <li>Collectively engage with institutions and leaders shaping urban policy at national, state, and local levels</li>
          <li>Strengthen the salience of urban reforms in public discourse and policymaking</li>
          <li>Create spaces for learning and exchange to deepen collective understanding of urban governance challenges and solutions</li>
        </ol>
      </div>
      <aside class="numcard rv d1 in">
        <blockquote style="margin:0;font-family:var(--display);font-weight:500;font-style:italic;font-size:16.5px;line-height:1.6;color:#fff">&ldquo;We envision cities that are empowered, inclusive of all citizens' needs, are supported by strong institutions, and are guided by evidence-based planning. The time to act and make our vision a reality is now.&rdquo;</blockquote>
        <p style="margin-top:22px;padding-top:18px;border-top:1px solid rgba(255,255,255,.16);color:#DCEAE6;font-size:14px;line-height:1.7"><strong style="color:#fff">Signatories:</strong> Artha Global, Civis, eGov, Foundation for Responsive Governance, Habitat Forum (INHAF), Janaagraha, Oorvani, Praja Foundation, PRIA, SAATH, Safetipin, Shelter Associates, WRI India</p>
      </aside>
    </div>
  </div>
</section>

<!-- GALLERY -->
<section class="sec" id="convening" aria-labelledby="gal-h">
  <div class="wrap">
    <div class="split rv in" style="align-items:end">
      <div>
        <p class="kicker" data-num="—">Inaugural convening</p>
        <h2 id="gal-h" style="font-size:clamp(27px,3.6vw,40px);letter-spacing:-.02em;max-width:22ch">Photographs from the Inaugural Convening of the Urban Reforms Collective</h2>
      </div>
      <div class="body">
        <p>The URC was formally launched in Mumbai in June 2026, where <strong>12 organisations signed the Mumbai Declaration</strong>.</p>
      </div>
    </div>
    <div class="gal">
      <figure class="rv in">
        <div class="ph" aria-hidden="true"><b>U-CAN</b><span>Photograph loads on urban.org.in</span></div>
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/wp/image-1-76f564b3.png" alt="Delegates at the inaugural convening of the Urban Reforms Collective" loading="lazy" decoding="async" onerror="this.style.display='none'">
      </figure>
      <figure class="rv in">
        <div class="ph" aria-hidden="true"><b>U-CAN</b><span>Photograph loads on urban.org.in</span></div>
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/wp/image-2-1-db06fc50.png" alt="Delegates at the inaugural convening of the Urban Reforms Collective" loading="lazy" decoding="async" onerror="this.style.display='none'">
      </figure>
      <figure class="rv in">
        <div class="ph" aria-hidden="true"><b>U-CAN</b><span>Photograph loads on urban.org.in</span></div>
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/wp/image-3-1-06fe379e.png" alt="Delegates at the inaugural convening of the Urban Reforms Collective" loading="lazy" decoding="async" onerror="this.style.display='none'">
      </figure>
      <figure class="rv in">
        <div class="ph" aria-hidden="true"><b>U-CAN</b><span>Photograph loads on urban.org.in</span></div>
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/wp/image-4-1-ec66e8b9.png" alt="Delegates at the inaugural convening of the Urban Reforms Collective" loading="lazy" decoding="async" onerror="this.style.display='none'">
      </figure>
      <figure class="rv in">
        <div class="ph" aria-hidden="true"><b>U-CAN</b><span>Photograph loads on urban.org.in</span></div>
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/wp/image-5-1-4072f6a6.png" alt="Delegates at the inaugural convening of the Urban Reforms Collective" loading="lazy" decoding="async" onerror="this.style.display='none'">
      </figure>
      <figure class="rv in">
        <div class="ph" aria-hidden="true"><b>U-CAN</b><span>Photograph loads on urban.org.in</span></div>
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/wp/image-6-1-23d780f2.png" alt="Delegates at the inaugural convening of the Urban Reforms Collective" loading="lazy" decoding="async" onerror="this.style.display='none'">
      </figure>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="sec alt" id="faq" aria-labelledby="faq-h">
  <div class="wrap">
    <div class="sec-head rv in">
      <div>
        <p class="kicker" data-num="—">Questions</p>
        <h2 id="faq-h">To know more, read our FAQs</h2>
      </div>
    </div>
    <div class="faq">
      <details open=""><summary><span class="qn">01</span><span class="qt">What is U-CAN?</span><span class="ic" aria-hidden="true"></span></summary>
        <div class="a">
          <p>The Urban Collective Action Network (U-CAN) is a network of organisations working to strengthen urban problem-solving in emerging Indian cities.</p>
          <p>It brings together actors across sectors to create spaces where practitioners, communities, and policymakers can engage with each other, fostering more collaborative approaches to addressing urban challenges. This, in turn, supports more inclusive governance and contributes to more livable cities.</p>
          <p>U-CAN’s approach is grounded in collective action, a multisectoral systems lens, and a strong emphasis on citizen participation in policymaking. It serves as the backbone for the URC — supporting coordination, convening organisations, and enabling the collective to function effectively. The U-CAN Secretariat plays a key role in facilitating collaboration, maintaining alignment, and supporting implementation.</p>
        </div>
      </details>
      <details>
        <summary><span class="qn">02</span><span class="qt">How is the URC related to U-CAN? What is U-CAN’s role in URC?</span><span class="ic" aria-hidden="true"></span></summary>
        <div class="a">
          <p>The URC is an initiative of U-CAN, and will serve as its backbone organisation (or secretariat). The URC is also one of U-CAN’s three identified strategic goals, reflecting its focus on enabling collective action towards urban reforms. The U-CAN secretariat is responsible for day-to-day coordination, ensuring that the Collective remains aligned and action-oriented.</p>
        </div>
      </details>
      <details>
        <summary><span class="qn">03</span><span class="qt">Why do we need an Urban Reforms Collective? Why are policy reforms for our cities important, and why now?</span><span class="ic" aria-hidden="true"></span></summary>
        <div class="a">
          <p>Improving cities isn’t just about better infrastructure projects, but also developing better integrated policies and delivering efficient and citizen-centric governance. Policy reforms matter because they shape how programmes are designed, how institutions function, and how services are delivered by the functionaries. Without innovation and improvements in the way policies are designed and developed, even well-intended government initiatives struggle to scale or sustain impact. Moreover, the urban sector continues to have a huge backlog of reforms to usher in good quality of life for residents of Indian cities.</p>
          <p>At the same time, there is a growing recognition across the sector that collaboration is no longer optional. Many organisations are working on similar urban challenges, but often without enough alignment or active coordination. The URC emerged from this moment, as a way to build a shared reform agenda, strengthen collective voice, and enable more coordinated engagement with decision-makers.</p>
        </div>
      </details>
      <details>
        <summary><span class="qn">04</span><span class="qt">What activities will be undertaken by the URC? What reforms will the URC prioritise?</span><span class="ic" aria-hidden="true"></span></summary>
        <div class="a">
          <p>The URC’s work is centred on enabling a stronger, more connected field around urban governance reform. This includes creating spaces for organisations to learn from each other, share insights from research and practice, and build a deeper understanding of urban governance reforms.</p>
          <p>It also involves bringing together actors across the ecosystem, from civil society to research institutions, and enabling more meaningful collaboration for engagement with key stakeholders in the government and the allied eco-system. A key priority will be to co-create a shared reform agenda and policy engagement roadmap for long-term and short-term, and aligning efforts across organisations to champion this agenda.</p>
          <p>Alongside this, the URC will work to build the infrastructure that collaboration requires, such as working groups, communication channels, and coordination mechanisms, and explore ways to better align resources in support of shared priorities.</p>
          <p>The URC’s reform priorities will be developed with input from all members. This will involve collective brainstorming to shape the agenda to focus on systemic improvements in urban governance, service delivery, and policy design. Specific priorities will evolve over time based on collective input and emerging opportunities.</p>
        </div>
      </details>
      <details>
        <summary><span class="qn">05</span><span class="qt">How does my organisation become part of the URC?</span><span class="ic" aria-hidden="true"></span></summary>
        <div class="a">
          <p>Joining the URC involves a simple but curated process:</p>
          <ul class="blist">
            <li>Submitting an expression of interest through an email to <a href="mailto:reforms@urban.org.in">reforms@urban.org.in</a></li>
            <li>Participating in a follow-up conversation with the URC team</li>
          </ul>
          <p>Membership and its level is curated to ensure alignment with the Collective’s goals. Acceptance and membership into the network will be decided by mutual agreement and alignment of both parties.</p>
        </div>
      </details>
      <details>
        <summary><span class="qn">06</span><span class="qt">Why should my organisation join the Urban Reforms Collective?</span><span class="ic" aria-hidden="true"></span></summary>
        <div class="a">
          <p>Every member organisation's agenda moves faster collectively than individually. The government is more likely to act when it hears the same ask from multiple credible organisations than when it hears competing asks from separate ones.</p>
          <p>Members get a platform and collective legitimacy to amplify their own work. Their research reaches more decision-makers, government relationships become part of a larger strategic map, and their voice on urban reform carries more weight when it is visibly part of a coordinated collective.</p>
          <p>Finally, members get access to a shared evidence base, political economy intelligence, and peer learning across organisations — resources that would cost any single organisation significantly more to build alone.</p>
        </div>
      </details>
      <details>
        <summary><span class="qn">07</span><span class="qt">Are there categories or levels of membership/participation?</span><span class="ic" aria-hidden="true"></span></summary>
        <div class="a">
          <p>The URC brings together organisations with different strengths, recognising that meaningful reform requires complementary expertise and roles.</p>
          <div class="lvl">
            <p>a. Anchor Partners are organisations with deep expertise and experience in urban governance, particularly in research, policy engagement, and institutional reform.</p>
            <p>– They play a central role in shaping the reform agenda, contributing technical inputs, and engaging with policymakers.</p>
            <p>– Anchor Partners also serve on the Coordination Committee by rotation and set the overall direction of the Collective.</p>
          </div>
          <div class="lvl">
            <p>b. Affiliate Organisations are organisations committed to advancing urban reforms, but may be limited by bandwidth, being new to policy engagement or to the urban sector. They contribute in a range of ways, from participating in discussions and learning spaces, to amplifying key messages, to actively engaging in inputting into shaping reforms, strategy and collaborative initiatives.</p>
          </div>
          <p>Organisations other than those eligible to play the role of Anchor Partners join as Affiliates. Over time, based on engagement, contribution, and readiness, and after a minimum engagement period of 15 months, affiliate organisations could move to become Anchor Partners.</p>
        </div>
      </details>
      <details>
        <summary><span class="qn">08</span><span class="qt">What does my organisation need to commit to?</span><span class="ic" aria-hidden="true"></span></summary>
        <div class="a">
          <p>The URC is built on voluntary association, flexibility in participation based on capacities and shared ownership. Organisations are expected to:</p>
          <ul class="blist">
            <li>Contribute to co-creation of reform priorities</li>
            <li>Participate actively in discussions, convenings, and collaborative outreach activities</li>
            <li>Engage according to their capacity and areas of expertise/focus</li>
          </ul>
          <p>The Collective values flexibility, allowing organisations to contribute in ways that align with their expertise and resources. It will work on the tenet of ‘commit what you can deliver, and deliver what you commit.</p>
        </div>
      </details>
      <details>
        <summary><span class="qn">09</span><span class="qt">Is there a fee or financial implication to join the URC?</span><span class="ic" aria-hidden="true"></span></summary>
        <div class="a">
          <p>Currently there is no fee or financial implication. However, there is an expectation of time and human resources to build the collective and its joint efforts. Organisations will have to defray their own costs to participate in the convenings, deliberations and outreach activities of the collective.</p>
        </div>
      </details>
      <details>
        <summary><span class="qn">10</span><span class="qt">Who can I speak to for more information?</span><span class="ic" aria-hidden="true"></span></summary>
        <div class="a">
          <p>You can reach out to the team at <a href="mailto:reforms@urban.org.in">reforms@urban.org.in</a> for any further questions</p>
        </div>
      </details>
    </div>
  </div>
</section>

<!-- GET INVOLVED -->
<section class="ctaband" id="involved" aria-label="Get involved">
  <div class="wrap">
    <p>If this sounds interesting to you, <a href="mailto:reforms@urban.org.in">reach out to us at reforms@urban.org.in</a> to express your interest in being part of the Urban Reforms Collective.</p>
  </div>
</section>


<?php
get_footer();
