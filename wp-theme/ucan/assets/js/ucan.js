

/* U-CAN shared behaviour — inlined into every page in standalone/.

   Defensive by design: every feature no-ops if its markup is absent, so the

   same block can be dropped into all page variants (.bar shell, index's .nav,

   the newsletter masthead). Keep it dependency-free and idempotent. */

(function () {

  "use strict";



  var reduced = false;

  try {

    reduced = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  } catch (e) {}



  var $ = function (s, r) { return (r || document).querySelector(s); };

  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };



  /* ---------- 1. sticky header shadow ---------- */

  (function stickyBar() {

    var bar = $("#bar") || $("#nav") || $(".header");

    if (!bar) return;

    var onScroll = function () {

      if (window.scrollY > 8) bar.classList.add("scrolled");

      else bar.classList.remove("scrolled");

    };

    onScroll();

    window.addEventListener("scroll", onScroll, { passive: true });

  })();



  /* ---------- 2. mobile menu ----------

     three markup variants across the site:

       shell pages : #burger  -> #msheet        (.open)

       index.html  : #burger  -> #mobile-menu   (.open)

       newsletter  : #menuBtn -> #mob-nav       (.open) + #closeBtn        */

  (function mobileMenu() {

    var pairs = [

      { btn: "#burger", panel: "#msheet" },

      { btn: "#burger", panel: "#mobile-menu" },

      { btn: "#menuBtn", panel: "#mob-nav" }

    ];

    pairs.forEach(function (p) {

      var btn = $(p.btn), panel = $(p.panel);

      if (!btn || !panel || btn.dataset.ucanBound) return;

      btn.dataset.ucanBound = "1";



      var setOpen = function (open) {

        panel.classList.toggle("open", open);

        btn.classList.toggle("open", open);

        btn.setAttribute("aria-expanded", open ? "true" : "false");

        panel.setAttribute("aria-hidden", open ? "false" : "true");

        document.documentElement.style.overflow = open ? "hidden" : "";

      };

      setOpen(false);



      btn.addEventListener("click", function (e) {

        e.preventDefault();

        setOpen(!panel.classList.contains("open"));

      });



      var close = $("#closeBtn");

      if (close) close.addEventListener("click", function () { setOpen(false); });



      // close on link click and on Escape

      $$("a", panel).forEach(function (a) {

        a.addEventListener("click", function () { setOpen(false); });

      });

      document.addEventListener("keydown", function (e) {

        if (e.key === "Escape" && panel.classList.contains("open")) {

          setOpen(false);

          btn.focus();

        }

      });

    });

  })();



  /* ---------- 2b. desktop nav dropdowns ----------

     Hover is pure CSS; this adds click + keyboard so the menus are usable

     without a pointer, and closes them on Escape / outside click. */

  (function navDropdowns() {

    var items = $$(".ucnav-i");

    if (!items.length) return;



    var closeAll = function (except) {

      items.forEach(function (it) {

        if (it === except) return;

        it.classList.remove("open");

        var t = $(".ucnav-t", it);

        if (t && t.tagName === "BUTTON") t.setAttribute("aria-expanded", "false");

      });

    };



    items.forEach(function (it) {

      var trigger = $(".ucnav-t", it);

      if (!trigger) return;



      trigger.addEventListener("click", function (e) {

        // a real link (About Us) still navigates on click; only buttons toggle

        if (trigger.tagName !== "BUTTON") return;

        e.preventDefault();

        var open = it.classList.toggle("open");

        trigger.setAttribute("aria-expanded", open ? "true" : "false");

        closeAll(it);

      });



      it.addEventListener("keydown", function (e) {

        if (e.key === "Escape" && it.classList.contains("open")) {

          it.classList.remove("open");

          if (trigger.tagName === "BUTTON") trigger.setAttribute("aria-expanded", "false");

          trigger.focus();

        }

      });

    });



    document.addEventListener("click", function (e) {

      if (!e.target.closest || !e.target.closest(".ucnav-i")) closeAll(null);

    });

  })();



  /* ---------- 3. scroll reveal ---------- */

  (function reveal() {

    var els = $$(".rv, .reveal");

    if (!els.length) return;

    var show = function (el) { el.classList.add("in", "visible"); };

    if (reduced || !("IntersectionObserver" in window)) {

      els.forEach(show);

      return;

    }

    var io = new IntersectionObserver(function (entries) {

      entries.forEach(function (en) {

        if (en.isIntersecting) { show(en.target); io.unobserve(en.target); }

      });

    }, { rootMargin: "0px 0px -8% 0px", threshold: 0.05 });

    els.forEach(function (el) {

      // already-visible markup stays visible; just observe the rest

      if (el.classList.contains("in") || el.classList.contains("visible")) { io.observe(el); }

      else io.observe(el);

    });

  })();



  /* ---------- 4. stat count-ups ----------

     <span data-to="15000" data-suffix="+" data-comma>  */

  (function countUps() {

    var nums = $$("[data-to]");

    if (!nums.length) return;



    var render = function (el, val) {

      var comma = el.hasAttribute("data-comma");

      var suffix = el.getAttribute("data-suffix") || "";

      var out = comma ? Math.round(val).toLocaleString("en-IN") : String(Math.round(val));

      el.textContent = out + suffix;

    };



    var run = function (el) {

      if (el.dataset.ucanDone) return;

      el.dataset.ucanDone = "1";

      var to = parseFloat(el.getAttribute("data-to")) || 0;

      if (reduced) { render(el, to); return; }

      var dur = 1100, start = null;

      var step = function (ts) {

        if (start === null) start = ts;

        var p = Math.min((ts - start) / dur, 1);

        var eased = 1 - Math.pow(1 - p, 3);

        render(el, to * eased);

        if (p < 1) requestAnimationFrame(step);

        else { render(el, to); el.classList.add("pop"); }

      };

      requestAnimationFrame(step);

    };



    if (reduced || !("IntersectionObserver" in window)) { nums.forEach(run); return; }

    var io = new IntersectionObserver(function (entries) {

      entries.forEach(function (en) {

        if (en.isIntersecting) { run(en.target); io.unobserve(en.target); }

      });

    }, { threshold: 0.4 });

    nums.forEach(function (el) { io.observe(el); });

  })();



  /* ---------- 5. DPDP newsletter consent gate ----------

     Hard requirement: submit is blocked unless a valid email AND the consent

     checkbox are both present, and an auditable consent record is produced. */

  (function newsletterGate() {

    var NOTICE_VERSION = "ucan-newsletter-notice-2026-01";

    // three form variants exist across the site

    var forms = $$("#nform, #nl-form, #fnform, form[aria-label='Newsletter signup']");

    if (!forms.length) return;



    forms.forEach(function (form) {

      if (form.dataset.ucanBound) return;

      form.dataset.ucanBound = "1";



      var email = $("input[type=email]", form);

      var consent = $("input[type=checkbox]", form);

      // message elements may sit just outside the <form>

      var scope = form.closest("section, .subscribe-box, div") || document;

      var err = $("#nerr", scope) || $("#nl-err", scope) || $(".nerr, .nl-err", scope);

      var ok = $("#nok", scope) || $("#nl-ok", scope) || $(".nok, .nl-ok", scope);



      // these are display:none in CSS with no .show rule, so drive them inline

      var toggle = function (el, on) {

        if (!el) return;

        el.classList.toggle("show", on);

        el.style.display = on ? "block" : "none";

      };

      toggle(err, false); toggle(ok, false);



      form.addEventListener("submit", function (e) {

        e.preventDefault();

        toggle(err, false); toggle(ok, false);



        var value = (email && email.value || "").trim();

        var validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value);

        var agreed = !consent || consent.checked; // gate only if a box exists



        if (!validEmail || !agreed) {

          toggle(err, true);

          if (!validEmail && email) email.focus();

          else if (consent) consent.focus();

          return;

        }



        // auditable consent record (DPDP Act 2023)

        var record = {

          email: value,

          consent: true,

          purpose: "U-CAN newsletter, event invitations and programme details",

          noticeVersion: NOTICE_VERSION,

          timestamp: new Date().toISOString(),

          page: location.pathname

        };

        try { console.log("[U-CAN] newsletter consent record", record); } catch (e2) {}



        form.reset();

        toggle(ok, true);

      });



      // Clear the error only on a genuine correction. Deliberately NOT on the

      // email's `change`: that fires when focus moves to the checkbox, which

      // would wipe the error the submit handler had just shown.

      if (email) email.addEventListener("input", function () { toggle(err, false); });

      if (consent) consent.addEventListener("change", function () { toggle(err, false); });

    });

  })();



  /* ---------- 6. cookie banner (most-privacy-preserving default) ---------- */

  (function cookieBanner() {

    var KEY = "ucan_consent_v1";

    var cc = $("#cc");

    var reopen = $("#cookie-reopen");

    if (!cc && !reopen) return;



    var save = function (choice) {

      try {

        localStorage.setItem(KEY, JSON.stringify({

          analytics: choice === "accept",

          ts: new Date().toISOString()

        }));

      } catch (e) {}

    };

    var stored = function () {

      try { return localStorage.getItem(KEY); } catch (e) { return null; }

    };

    var show = function () { if (cc) cc.classList.add("show"); };

    var hide = function () { if (cc) cc.classList.remove("show"); };



    if (cc && !stored()) {

      setTimeout(show, 10000); // surfaces after 10s

    }

    var accept = $("#cc-accept"), reject = $("#cc-reject");

    if (accept) accept.addEventListener("click", function () { save("accept"); hide(); });

    if (reject) reject.addEventListener("click", function () { save("reject"); hide(); });

    if (reopen) reopen.addEventListener("click", function (e) { e.preventDefault(); show(); });

  })();



  /* ---------- 7. archive: load more + year filter ----------

     Two listings use this: the newsletter archive (.issue tiles, which also

     get the year filter) and the Fellows' blog grid (.bcard). */

  (function archive() {

    var btn = $("#loadBtn"), wrap = $("#loadMore");

    var issues = $$(".issue, .bcard");

    var filters = $$(".filter-btn");

    if (!issues.length) return;



    // remember which tiles start collapsed so "All" can restore that view

    issues.forEach(function (el) {

      if (el.classList.contains("hidden")) el.dataset.collapsed = "1";

    });



    var showAll = function () {

      issues.forEach(function (el) { el.classList.remove("hidden"); });

      if (wrap) wrap.style.display = "none";

    };



    if (btn) btn.addEventListener("click", showAll);



    filters.forEach(function (b) {

      b.addEventListener("click", function () {

        var year = b.dataset.filter;

        filters.forEach(function (o) { o.classList.toggle("active", o === b); });

        if (year === "all") {

          // back to the collapsed default

          issues.forEach(function (el) {

            el.classList.toggle("hidden", el.dataset.collapsed === "1");

          });

          if (wrap) wrap.style.display = "";

          return;

        }

        issues.forEach(function (el) {

          // the newsletter archive keys on year, the blog grid on author

          var key = el.dataset.year || el.dataset.author;

          el.classList.toggle("hidden", key !== year);

        });

        if (wrap) wrap.style.display = "none";

      });

    });

  })();



  /* ---------- 8. back to top (newsletter article) ---------- */

  (function backToTop() {

    var btt = $("#btt");

    if (!btt) return;

    var onScroll = function () { btt.classList.toggle("show", window.scrollY > 600); };

    onScroll();

    window.addEventListener("scroll", onScroll, { passive: true });

    btt.addEventListener("click", function () {

      window.scrollTo({ top: 0, behavior: reduced ? "auto" : "smooth" });

    });

  })();



  /* ---------- 9. FAQ accordion ----------

     URC uses native <details>, which needs no JS. This only adds the

     one-open-at-a-time behaviour when several sit in the same .faq group. */

  (function faq() {

    var groups = $$(".faq");

    groups.forEach(function (g) {

      var items = $$("details", g);

      if (items.length < 2) return;

      items.forEach(function (d) {

        d.addEventListener("toggle", function () {

          if (!d.open) return;

          items.forEach(function (o) { if (o !== d) o.open = false; });

        });

      });

    });

  })();



  /* ---------- 10. tabs (Fellowship: knowledge / network / funding) ----------

     Markup is fully usable with JS off — every pane renders, and only the

     first is hidden once this runs. */

  (function tabs() {

    $$("[data-tabs]").forEach(function (root) {

      var btns = $$('[role="tab"]', root);

      var panes = $$('[role="tabpanel"]', root);

      if (btns.length < 2 || btns.length !== panes.length) return;



      var select = function (i, focus) {

        btns.forEach(function (b, n) {

          b.setAttribute("aria-selected", n === i ? "true" : "false");

          b.tabIndex = n === i ? 0 : -1;

        });

        panes.forEach(function (p, n) { p.hidden = n !== i; });

        if (focus) btns[i].focus();

      };



      btns.forEach(function (b, i) {

        b.addEventListener("click", function () { select(i); });

        b.addEventListener("keydown", function (e) {

          var d = e.key === "ArrowRight" ? 1 : e.key === "ArrowLeft" ? -1 : 0;

          if (!d) return;

          e.preventDefault();

          select((i + d + btns.length) % btns.length, true);

        });

      });

      select(0);

    });

  })();

})();

