</main>

<footer>

  <div class="wrap">

    <div class="f-grid">

      <div>

        <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="U-CAN home">

          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/u-can-urban-collective-action-network-08e2484273.svg" width="107" height="40" alt="U-CAN — Urban Collective Action Network" loading="lazy" decoding="async">

        </a>

        <p class="blurb">Uniting diverse urban voices to collectively shape our cities.</p>

      </div>

      <nav aria-label="Quick links">

        <h2 class="f-h">Explore</h2>

        <ul>

          <li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">About U-CAN</a></li>

          <li><a href="<?php echo esc_url( home_url( '/our-people/' ) ); ?>">Our People</a></li>

          <li><a href="<?php echo esc_url( home_url( '/impact/' ) ); ?>">Impact</a></li>

          <li><a href="<?php echo esc_url( home_url( '/our-members/' ) ); ?>">Our Members</a></li>

          <li><a href="<?php echo esc_url( home_url( '/u-can-city-mixers/' ) ); ?>">City Mixers</a></li>

          <li><a href="<?php echo esc_url( home_url( '/the-u-can-annual-forum-2025/' ) ); ?>">Annual Forum 2025</a></li>

        </ul>

      </nav>

      <nav aria-label="Initiatives">

        <h2 class="f-h">Initiatives</h2>

        <ul>

          <li><a href="<?php echo esc_url( home_url( '/urban-reforms-collective/' ) ); ?>">Urban Reforms Collective</a></li>

          <li><a href="<?php echo esc_url( home_url( '/requests-for-collaboration/' ) ); ?>">Request for Collaboration</a></li>

          <li><a href="<?php echo esc_url( home_url( '/learning-network-for-urban-managers/' ) ); ?>">Learning Network</a></li>

          <li><a href="<?php echo esc_url( home_url( '/u-can-fellowship/' ) ); ?>">U-CAN Fellowship</a></li>

          <li><a href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>">Newsletter</a></li>

          <li><a href="<?php echo esc_url( home_url( '/policy-webinars/' ) ); ?>">Policy Webinars</a></li>

          <li><a href="<?php echo esc_url( home_url( '/city-champions/' ) ); ?>">City Champions</a></li>

        </ul>

      </nav>

      <div class="f-sub" id="connect">

        <h2 class="f-h">Stay connected</h2>

        <p class="f-subtext">One email a month on urban governance, our programmes and ways to get involved.</p>

        <form class="fnform" id="fnform" method="post" action="https://urban.org.in/newsletter/" novalidate="">

          <div class="fnrow">

            <label class="vh" for="f-email">Your email address</label>

            <input id="f-email" name="email" type="email" autocomplete="email" required="" placeholder="Your email address">

            <button type="submit">Subscribe</button>

          </div>

          <div class="fconsent">

            <input type="checkbox" id="f-consent" name="consent" required="">

            <label for="f-consent">I agree that U-CAN may use my email address to send its newsletter, event invitations and programme details. I can withdraw this consent at any time by writing to <a href="mailto:privacy@urban.org.in">privacy@urban.org.in</a>.</label>

          </div>

          <p class="nerr" role="alert">Please enter a valid email address and tick the consent box to continue.</p>

          <p class="nok" role="status">Thanks — you're on the list. We've recorded your consent and the time it was given.</p>

          <p class="f-legal">U-CAN is the Data Fiduciary for this data under the Digital Personal Data Protection Act, 2023. Request access, correction or erasure at <a href="mailto:privacy@urban.org.in">privacy@urban.org.in</a>. See our <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a>.</p>

        </form>

        <ul class="f-social">

          <li><a href="https://www.youtube.com/@U-CAN24" target="_blank" rel="noopener noreferrer">YouTube</a></li>

          <li><a href="https://www.linkedin.com/company/urban-collective-action-network-u-can/" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>

          <li><a href="mailto:connect@urban.org.in">connect@urban.org.in</a></li>

        </ul>

      </div>

    </div>

    <div class="f-bottom">

      <span><?php echo esc_html( '© U-CAN ' . date( 'Y' ) . '. All Rights Reserved' ); ?></span>

      <span><a href="<?php echo esc_url( home_url( '/terms-of-use/' ) ); ?>">Terms of Use</a><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a><a href="<?php echo esc_url( home_url( '/data-rights/' ) ); ?>">Your Data Rights</a><button type="button" class="cookie-link" id="cookie-reopen">Manage cookies</button></span>

    </div>

  </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
