# CLAUDE.md — U-CAN Website Redesign

Working guide for continuing the **urban.org.in** (Urban Collective Action Network / U-CAN) website redesign in Claude Code. Read this fully before touching any page.

---

## 1. What this project is

Redesigning the live urban.org.in site, one page at a time, as **brand-compliant, single-file, responsive HTML pages** in a fresh "editorial civic" design language. Each page is a self-contained `.html` file (inline CSS + JS + base64 assets) that can be dropped straight into WordPress/Elementor or served statically.

**Repo layout (Claude Code, persistent) — mirrors the dastangoi project's `standalone/` convention so Vercel deploys cleanly:**
```
ucan/
├── CLAUDE.md
├── standalone/             # the actual editable pages — work here — this is the Vercel Root Directory
│   ├── index.html                   # homepage
│   ├── about.html                    # THE SHELL — copy this to start a new page
│   ├── our-people.html
│   ├── impact.html
│   ├── profile-siddharth-pandit.html
│   ├── learning-network.html
│   ├── newsletter.html
│   ├── newsletter-urban-brief-june-2026.html
│   ├── urban-reforms-collective.html
│   ├── rfc.html
│   ├── city-mixers.html
│   ├── annual-forum-2025.html
│   └── profile-<slug>.html          # 23 member profiles, generated — see §14
└── _mhtml-originals/       # read-only backup of the browser-saved .mhtml snapshots these came from — don't edit
```
This replaced an earlier handoff where pages only existed as `.mhtml` browser snapshots (Chrome "Save Page As → Single File"); they were unpacked into plain self-contained `.html` (resources re-inlined as data URIs). Filenames were later renamed to drop the `u-can-` prefix and the homepage renamed to `index.html`, matching dastangoi's naming (`about.html`, `dastan.html`, etc.) so the site has a real root document and no naming clashes on Vercel. Work directly in `standalone/*.html` going forward.

**Non-negotiables for every page:**
- Content is used **verbatim from the source page** — no rewriting, no paraphrasing, no invented copy. Only change something if the user explicitly asks (usually named bug fixes). If a typo exists in the source, preserve it and flag it; don't silently fix.
- SEO/AEO/GEO optimised: JSON-LD `@graph`, single `<h1>`, canonical + hreflang (`en-IN` + `x-default`), OG + Twitter tags, `speakable`.
- Targets: 90+ PageSpeed / GTmetrix A+, **CLS 0**, zero JS console errors, clean responsive from **320px to 1440px**.
- **No dead space** (see §4 — this is a hard rule the user cares about a lot).
- DPDP (India Digital Personal Data Protection Act 2023) compliant newsletter consent + cookie banner on every page.

**User working style:** casual, direct, action-oriented. Wants complete copy-ready files (never snippets), verbatim source content, strong emphasis on zero dead space. When asked to redesign pages, **use Claude Sonnet 4.6** (the user's stated model preference for this project).

---

## 1a. CONTENT SOURCE OF TRUTH — `Website - Plan and Content Revisions.pdf`

`Website - Plan and Content Revisions.pdf` (57pp, committed at repo root) is the **authoritative content spec** and **outranks the live urban.org.in site** wherever the two disagree. It is the client's own 12-month plan + approved page-by-page copy, including exact title tags, meta descriptions, H1s, opening hooks, section copy, alt text, and named bug fixes.

Extract its text with:
```bash
python -c "import pypdf; r=pypdf.PdfReader('Website - Plan and Content Revisions.pdf'); print('\n'.join((p.extract_text() or '') for p in r.pages))"
```
(`pypdf` is installed. The raw extract puts each word on its own line — collapse whitespace before reading.)

**What the PDF covers, by page:**
| PDF pages | Content |
|---|---|
| 2–4 | 12-month plan, Phases 1–3 (what to build/change, in priority order) |
| 5–12 | Homepage: full new copy + SEO block + before/after table |
| 13–15 | About Us, Our People (SEO copy, hooks, timeline addition) |
| 15–17 | Impact: all 8 proof points + "In their words" quotes |
| 19–32 | **U-CAN Fellowship**, Blogs by Fellows, Meet the 2024-25 Fellows, L&D Calendar (12 sessions) |
| 33–43 | Events: City Mixers (11 events, full write-ups) — *built, see §16* |
| 44–46 | Annual Forum 2025 — *built, see §16* |
| 49–50 | Learning Network (explicitly: "Content - to be kept as is") |
| 51–53 | Request for Collaboration (RFC) — *built, see §12* |
| 54–55 | Urban Reforms Collective (URC) + its 2 named bug fixes — *built, see §12* |
| 56–57 | **Our Members** (organisations — 8 members + 3 Friends, alt text, H2 fixes) |

Bolded rows are pages that **do not exist in `standalone/` yet** — the PDF contains their full approved copy, so they can be built without re-fetching anything.

**Verified-aligned as of the last pass** (homepage, about, our-people, impact, learning-network all match the PDF; see §6 for the per-page audit result).

---

## 2. Design system ("editorial civic")

Verified against the official **U-CAN Brand Guidelines** (`/mnt/user-data/uploads/U-CAN_-_Brand_Guidelines.docx`).

### Colour tokens (CSS variables)
```css
--paper:#FBFAF6;        /* page background */
--paper-alt:#E9F5F2;    /* alt section background (mint) */
--ink:#222120;          /* primary text / dark bg */
--ink-soft:#57564F;     /* secondary text */
--teal:#1F8F7B;         /* primary brand teal */
--teal-text:#177A69;    /* teal for small text (AA contrast) */
--teal-deep:#0E5348;    /* dark teal — hero/feature bands */
--teal-light:#4EC6B2;   /* light teal accent */
--lime:#CDDE71;         /* lime — ONLY on dark backgrounds */
--orange:#FEAE00;       /* logo mark + AT MOST one accent per page */
--line:#DCEAE6;         /* hairline borders */
```
**Rules:** Lime (`--lime`) appears **only on dark backgrounds** (teal-deep / ink). Orange is reserved for the logo mark and at most a single accent — do not scatter it. Stay within the teal family for accents.

### Typography
- **Display:** Archivo (weights 500/700/800) — headings, numbers, stat figures.
- **Body:** Public Sans — paragraphs, UI.
- Both loaded non-blocking with **metric-matched fallbacks** ("PS Fallback" / "Arch Fallback" via `@font-face` size-adjust) to prevent CLS. Never remove the fallbacks.

### Shared components (present in every page, inherited from the About shell)
- Sticky `.bar` top nav + burger/`.msheet` mobile menu.
- `.hero` on `--teal-deep` with dot pattern + radial glow, `.crumb` breadcrumb, `.hero-tag`, `.hero-lede`.
- `.kicker` eyebrow labels (with `data-num="—"` dash prefix).
- `.sec` / `.sec.alt` section wrappers; `.sec-head` header (kicker + h2 + optional `.lead`).
- `.rv` scroll-reveal (IntersectionObserver adds `.in`; `.d1`/`.d2`/`.d3` stagger delays).
- Count-up JS for stat figures: `data-to`, `data-suffix`, `data-comma` (Indian `toLocaleString('en-IN')` grouping). `.pop` animation on finish.
- `.ctaband` (CTA pair), `.news` (dark newsletter), `footer`.
- **DPDP newsletter consent**: gated submit (valid email + checkbox required), builds an auditable consent record via `console.log` with timestamp + notice version. Contact for data rights: `privacy@urban.org.in`.
- **Cookie banner**: surfaces after 10 seconds, `localStorage` key `ucan_consent_v1`, reopen via footer "Manage cookies", most-privacy-preserving default.
- Respects `prefers-reduced-motion` (count-ups jump to final values, reveals show immediately).

### Contacts used across the site
- `privacy@urban.org.in` — DPDP / data-protection contact (newsletter + cookie copy).
- `connect@urban.org.in` — general contact.
- `reforms@urban.org.in` — Urban Reforms Collective contact.

---

## 3. Removed design elements (do NOT reintroduce)

These were tried and explicitly removed by the user across all pages:
- **Big ghost section numerals** (`.sec-num` — large faded "01/02/03" beside headings). Removed everywhere. Section headers lead with **kicker + heading only**.
- **Light logo-mark section headers** (`.sec-mark` — faded U-CAN family-figure mark beside headings). Tried, then removed everywhere.

If you see either class name resurface, it's a regression — strip it and collapse the `.sec-head` back to a single column.

---

## 3a. Site-wide navigation — 5 top-level items with dropdowns

**The nav mirrors urban.org.in's real IA**, not a flat list. An earlier flat 7-item bar was replaced because the long labels wrapped onto two lines and looked broken. Structure (live site's top level; About Us submenu is the one the PDF prescribes on p.14):

| Top level | Href | Dropdown |
|---|---|---|
| About Us | `about.html` | About U-CAN · Our People · Impact |
| Initiatives | — | Urban Reforms Collective (URC) · Request for Collaboration (RFC) · Learning Network for Urban Managers · U-CAN Fellowship · *(group: Fellowship)* Meet the 2024-25 Fellows · Blogs by Our Fellows · L&D Calendar |
| Events | — | U-CAN City Mixers · The U-CAN Annual Forum 2025 |
| Our Members | `our-members.html` | — |
| Media | — | Newsletter · City Champions *(live)* · Policy Webinars *(live)* |

Rules:
- **No in-page anchors in the nav.** The About Us dropdown used to expose `#vision` / `#mission` / `#principles` / `#timeline`. They added confusion without adding destinations and were removed — dropdowns list **pages only**. A dropdown may carry a `__GROUP__<label>` pseudo-item, which renders as a small uppercase divider heading (`.ucnav-gh` / `.ucmob-gh`) rather than a link; that's how the three Fellowship sub-pages are grouped.
- **"Our People" ≠ "Our Members".** Our People = individuals (under About Us); Our Members = the 8 member organisations + 3 Friends (top-level). Both are now local pages.
- **Brand/logo always links to `index.html`**; the header CTA "Subscribe" points at `newsletter.html#subscribe` (the newsletter page's own signup box).
- **Individual detail pages are not nav items** (member profiles, fellow profiles, L&D session pages, a single newsletter issue). They still carry the shared header/footer; member profiles mark **About Us** active, fellow/L&D pages mark **Initiatives**.
- Not-yet-built pages stay pointed at the live `urban.org.in` URL. Only City Champions and Policy Webinars still are.
- **The nav is generated, not hand-written.** `rebuild_nav.py` (scratchpad) holds the single `NAV` definition plus the `.ucnav`/`.ucmob` markup and CSS, and rewrites every page. Edit that and re-run — never hand-edit one page's nav.
- **One header, one footer, everywhere** — see §3c. The nav component drops into the single `.bar` header on all 59 pages.
- All 8 originally-unpacked pages use `


` (double-CR) line endings; pages generated later use plain `
`. Check which you're editing before doing multi-line string substitution.

## 3c. One header + one footer on every page

Four different header markups had grown up (`.bar` on the shell pages, `.nav` on index, `.header` on newsletter, `.masthead` on the newsletter issue), each with its own footer. **All 59 pages now share one header and one footer**, generated by `chrome.py` (scratchpad) and styled by an injected `<style data-ucan="chrome">` block.

- The chrome stylesheet is **deliberately self-contained** — three different base64 stylesheet variants exist across the site, so it depends on no page-specific custom properties beyond the colour tokens, and every one of those carries a literal fallback (`var(--line,#DCEAE6)`). That's why it renders identically on top of any of them.
- **Injection order matters**: chrome → nav → rhythm, each inserted immediately before `</head>`, so the later blocks win. Re-run in that order after any change.
- The footer is now a 4-column grid: brand · Explore · Initiatives · **the site's one newsletter signup** (`#fnform`, full DPDP consent + notice). Run order note: `ucan.js`'s form selector must include `#fnform`.
- **Trap:** `newsletter-urban-brief-june-2026.html`'s `<header class="masthead">` was *not* chrome — it carried that edition's `<h1>`, standfirst and date. Swapping it left the page with zero `<h1>`. `fix_brief2.py` restores it as a `<section class="masthead">` and lifts the shared header above `<main>`. If the chrome swap is ever re-run from scratch on that page, re-run that fix too.

## 3b. RESOLVED: JavaScript is now wired up (was: zero JS anywhere)

**Fixed.** Every page now carries one inlined `<script data-ucan="behaviour">` block (identical on all pages, injected before `</body>`). Source of truth: `ucan.js` in the session scratchpad — **edit that and re-run `inject_js.py`, never hand-edit one page's copy**, or the pages drift apart.

What it wires up, all defensively (each feature no-ops when its markup is absent, so the one block suits every page variant):
1. Sticky header `.scrolled` state
2. Mobile menu — handles all three markups: `#burger`→`#msheet`, `#burger`→`#mobile-menu`, `#menuBtn`→`#mob-nav` (+`#closeBtn`); closes on link-click and Escape, manages `aria-expanded`
3. Scroll reveal `.rv`/`.reveal` → `.in`/`.visible`
4. Count-ups (`data-to`, `data-suffix`, `data-comma` with `en-IN` grouping)
5. **DPDP newsletter consent gate** — blocks submit without a valid email *and* a ticked box, and emits an auditable consent record (`console.log`) with timestamp + notice version
6. Cookie banner — 10s delay, `localStorage` `ucan_consent_v1`, privacy-preserving default, reopen from the footer link
7. Newsletter archive "Load older editions"
8. Back-to-top on the newsletter article
9. FAQ one-open-at-a-time (URC's accordion is native `<details>`, so it works even with JS off)

All of it respects `prefers-reduced-motion` (count-ups jump to final values, reveals show immediately).

**Two gotchas worth keeping:**
- **Don't clear the form error on the email's `change` event.** Focusing the consent checkbox blurs the email, which fires `change` and instantly wipes the error the submit handler just showed — the gate then looks broken while actually working. Clear on the email's `input` and the checkbox's `change` only.
- **Every newsletter form needs `novalidate`.** Otherwise native HTML5 validation blocks submit before the handler runs, so the custom DPDP message never appears. `newsletter.html`'s form was missing it.

Also added while wiring this up: the **cookie banner markup** (it existed only in CSS — no page actually had the `#cc` element), and a **DPDP consent checkbox + notice on `newsletter.html`'s subscribe form**, which had neither.

---

## 4. THE DEAD-SPACE RULE (hard requirement)

> **Never leave large blank/dead space in any section.** Either (a) spread text full-width left-to-right, or (b) fill the empty side with a relevant, purposeful design element (stat callouts, motif, image, quote card, question panel, etc.).

This applies to **every section on every page**, retroactively and going forward. When a big display heading or short paragraph only occupies ~half the row, that's a violation — restructure it as a genuine two-column layout where **both** columns carry content, or spread the text across the full width.

Patterns already used to satisfy this (reuse these):
- Text left + **teal-deep numbered card** right (e.g. Learning Network "during pilot phase", URC "alignment gap").
- Text left + **stat panel** right (e.g. Learning Network overview: 5 stat rows with icons).
- Statement left + **stat callouts over a network-motif SVG** right (e.g. Learning Network "why this matters").
- Problem text + bullet lists left + **dark Ink pull-quote card** right (RFC "why collaboration matters").
- Description + stacked cards left + **numbered "questions" panel** right (RFC "what is the RFC").
- Body text in a **two-column grid** when there's no natural right-side element (Impact aims, URC aims).
- Section heading left + **context note** right (URC gallery, RFC framework).

**This rule is saved in the user's Claude memory.** Treat it as always-on.

---

## 5. Build & verify workflow

Everything lives in the repo now — no sandbox, no reset. Work directly on the files in `standalone/`.

### The reusable shell
`standalone/about.html` is the **canonical shell** — it contains the full head, CSS, nav, footer, cookie banner, and JS. To build a new page:
1. Copy the About page to a new file in `standalone/`.
2. Extract the logo data-URI: regex for `<img src="(data:image/webp;base64,...)" width="38"` → save as the logo string.
3. Swap `<title>`, meta description, canonical, hreflang, OG/Twitter, and the JSON-LD block.
4. Inject page-specific CSS before `</style>`.
5. Swap the nav links and the active-section `ids` array in the JS.
6. Replace `<main>…</main>` with the new page body.
7. Append any page-specific JS (e.g. FAQ accordion) before the IIFE close `})();`.

Do this directly by hand-editing the HTML in Claude Code. The earlier Python-fragment build scaffolding (`<page>_data.py` / `build_<page>.py` / `assemble_<page>.py`) was a sandbox convenience from the original chat-based session — it's not needed here and shouldn't be reintroduced.

### Verification (run before every export — Playwright)
Check on the rendered file:
- Exactly **one `<h1>`**.
- **CLS = 0** (PerformanceObserver on `layout-shift`).
- **Zero `pageerror`** events.
- **Zero horizontal overflow** at 12–14 widths: 1440, 1180, 1024, 960, 900, 820, 768, 640, 560, 480, 390, 360, 320. (`scrollWidth - clientWidth === 0`).
- JSON-LD parses as valid JSON.
- Count-ups fire on scroll AND reduced-motion shows final values.
- Newsletter gate blocks without email+consent, accepts with both.
- Cookie banner hidden at 5s, visible at ~10s.
- Interactive components work (e.g. FAQ accordion toggles `hidden` + `aria-expanded`).
- No `.sec-num` / `.sec-mark` elements remain.

### Common gotchas (learned the hard way)
- **Double-escaping links:** if you pass HTML through `html.escape()`, intended `<a>` tags become `&lt;a&gt;`. Escape text only; inject links after, or use placeholder tokens like `__MAILTO__` and replace post-escape.
- **320px overflow** usually comes from a grid child without `min-width:0` or a long unbroken string — add `min-width:0` / `overflow-wrap:break-word`.
- **Sandbox can't reach urban.org.in (403).** Member logos, people photos, gallery/framework images hosted on WordPress **only load in a real browser on the live server**. Use `onerror` fallbacks that swap in a clean branded placeholder tile (e.g. "loads on urban.org.in"). Never guess WordPress upload filenames — broken images look worse than placeholders.
- `<ol>` default markers + a CSS `counter()` = doubled numbers. Set `list-style:none` on the `<ol>` and `<li>`.
- A prompt implying an uploaded image doesn't guarantee one is attached — check.

### Version control & checkpoints (do this every round)
Same habit as the dastangoi project: **commit and push a labeled checkpoint after every round of changes**, without waiting to be asked. This is what lets the user roll back to any previous version.

- Repo (private): **https://github.com/Finntrado/ucan**, default branch `main`, identity `Shashikant`.
- Auth: `gh` CLI is already logged in as `Finntrado` (keyring-backed, `repo` scope) — no separate token file needed here. Push commands need network, so run them with `dangerouslyDisableSandbox: true`.

Checkpoint commands:
```bash
cd C:\Users\Chatw\Desktop\ucan
git add -A
git commit -m "<short summary of this round>"
git push origin main
```

### Vercel deployment
- **Root Directory:** `standalone` (Project Settings → General → Root Directory).
- **Build/Install Command:** none — static HTML, no build step. **Output Directory:** default/`.`.
- `index.html` is the homepage, so the bare domain resolves correctly — no redirect/rewrite needed.
- `_mhtml-originals/` and `_scripts/` sit outside `standalone/`, so they're never deployed.

---

## 6. Page inventory (all in `standalone/`)

**Recovered and present in this repo** — all 8 audited against the content PDF (§1a) and confirmed aligned:

| File | Page | H1 | Status |
|---|---|---|---|
| `index.html` | Homepage (editorial-civic redesign) | — | Done, in repo |
| `about.html` | About Us — **THE SHELL** | About U-CAN | Done, in repo |
| `our-people.html` | Our People (28 cards, 22/23 real photos) | Our People | Done, in repo |
| `impact.html` | Impact ("What We've Built Together") | What We've Built Together | Done, in repo |
| `profile-siddharth-pandit.html` | Individual profile **template** | Siddharth Pandit | Done, in repo (template) |
| `learning-network.html` | Learning Network for Urban Managers | Learning Network for Urban Managers | Done, in repo |
| `newsletter.html` | Newsletter — Urban Governance Updates | Newsletter — Urban Governance Updates | Done, in repo (wasn't in the original chat's inventory — recovered from a saved snapshot) |
| `newsletter-urban-brief-june-2026.html` | The Urban Brief — June 2026 (newsletter detail) | The Urban Brief — June 2026 | Done, in repo (also not in the original inventory) |
| `urban-reforms-collective.html` | Urban Reforms Collective (URC) | Championing a Collective Reform Agenda for India's Cities | **Rebuilt from scratch** — see §12 |
| `rfc.html` | Request for Collaboration (RFC) | Enabling Collaboration as a Way of Working in India's Urban Ecosystem | **Rebuilt from scratch** — see §12 |
| `city-mixers.html` | U-CAN City Mixers (11 events) | U-CAN City Mixers | **Built** — see §16 |
| `annual-forum-2025.html` | The U-CAN Annual Forum 2025 | The U-CAN Annual Forum 2025 | **Built** — see §16 |
| `our-members.html` | Our Members (8 orgs + 3 Friends) | Our Members | **Built** — see §18 |
| `fellowship.html` | The U-CAN Fellowship | Empowering Women Change-Makers to Transform Urban Communities | **Built** — see §18 |
| `meet-the-fellows.html` | Meet the 2024-25 Fellows (8) | Meet the 2024-25 Fellows | **Built** — see §18 |
| `profile-fellow-<slug>.html` | 8 fellow profiles | *(fellow's name)* | **Built** — see §18 |
| `fellow-blogs.html` | Blogs by Our Fellows (58 posts) | Blogs by Our Fellows | **Built** — see §18 |
| `ld-calendar.html` | Fellowship L&D Calendar | L&D Calendar | **Built** — see §18 |
| `ld-<slug>.html` | 12 L&D session pages | *(session title)* | **Built** — see §18 |

**59 pages total.** Every page the content PDF specifies now exists locally; the only nav destinations still pointing at urban.org.in are City Champions and Policy Webinars, which the PDF does not cover.

**NOT recovered:**

| File | Page | Status |
|---|---|---|
| homepage-redesign variant vs original homepage | Two homepage variants were mentioned in the original session; only one homepage snapshot (`index.html`) made it out. If the other variant matters, it needs to be re-pulled from the original chat. |

### Page-specific notes

**About Us** — the shell. Vision (dark teal card + "Download our Brochure"), Mission, 5 Governing Principles, 20-milestone Timeline (newest June 2026 "Mumbai Declaration / Urban Reforms Collective launch" at top, flagged "Latest" with lime pill + orange marker; alternating L/R desktop, single left rail mobile; JSON-LD ItemList intentionally kept chronological ascending).

**Our People** — 4 groups: Founding Circle (12), Steering Committee (8), Stewardship Team (4), Our Team (4) = 28 card instances (some people appear twice). **22 of 23 people have real circular photos** (optimised ~120px WebP, base64-embedded, ~55KB total; stored in `avatars_b64.json`; sources in `/mnt/user-data/uploads/`). **Only Meghna Bandelwar Indurkar (Praja Foundation) lacks a photo** — uses an on-brand monogram/person-glyph placeholder. Cards link to `/member/{slug}/`. Org-colour-coded within the teal family.

**Impact** — rebuilt from the user's uploaded `impact-preview` file. Hero + 4-figure animated band + **8 proof points as a 2-column card grid** (cycling teal accents, 3 inset testimonials: Nidhi Batra / Shubhi Kesarwani / Gurjit Singh Dhillon) + **"In Their Words"** = 3 voice cards **with portraits** (Mayura Gadkari + Pratima Joshi real photos; Meghna Indurkar on-brand placeholder, tagged `data-ref="placeholder"`).

**Profile template** (Siddharth Pandit) — split hero (large circular portrait + group badge + LinkedIn button), bio + sticky "At a glance" sidebar (Role / Group / Organisation / LinkedIn), back-bar. This is the **approved template for all 23 individual profile pages** — see §7.

**Learning Network** — content verbatim incl. preserved source typo **"saeries"** (should be "series") and a double space in the source title (both flagged to user, fix still pending their OK). Hero (3 chips) → Overview (2-col split: statement + body left, 5-row stat panel right) → How it took shape (text + teal-deep numbered card) → 3 pilot workshop cards (Maharashtra 35+, Andhra Pradesh ~40·13 cities, Maharashtra virtual) → What pilot revealed (3 numbered) → From pilot to platform (5 target-dot items) → Why this matters (teal band, 4 lime stat callouts + network motif) → report/one-pager PDF links.

**Urban Reforms Collective (URC)** — **built, see §12.** Historical notes from the original session: two bug fixes were applied (FAQ #6 heading "part ofthe" → "part of the"; FAQ #11 mailto `info@urbanreformscollective.org` → `reforms@urban.org.in`, visible text was already correct). Sections: hero (3 chips) → Need for URC (text + teal-deep "alignment gap" 01/02/03 card) → What URC aims to do (2-col body + 3 aim cards) → Gallery (heading left + context note right, 6 WordPress images with `onerror` placeholders, asymmetric grid) → **11-FAQ accordion** (keyboard-accessible: `aria-expanded`/`aria-controls`/`hidden`) → Get Involved band ("Write to us" → `mailto:reforms@urban.org.in`). `FAQPage` JSON-LD.

**Request for Collaboration (RFC)** — **built, see §12.** Historical notes from the original session: "Read the Phase I Report" hero CTA → live report URL. Sections: hero (3 chips + 2 CTAs) → Why collaboration matters (text + 2 bullet lists left, dark Ink "collaboration gap" pull-quote card right) → What is the RFC (description + 3 stacked objective cards left, "Three questions the RFC explores" 01/02/03 panel right) → Practice-informed framework (text + report CTA left, 2 framework images right with `onerror` placeholders) → Know more band (`connect@urban.org.in`). All content verbatim.

---

## 7. Pending / standing offers

1. ~~**Generate all 23 individual profile pages**~~ — **done, see §14.** The old blocker ("only Siddharth's bio is available") no longer applies: every bio is fetchable from `urban.org.in/member/<slug>/`, which the original chat's sandbox couldn't reach.
2. ~~**Meghna Bandelwar Indurkar photo**~~ — **found.** Her live member page does carry a real portrait. Its WordPress filename is misleading (`mayura-gadkari-principal-artha-global.png`), which is likely why it was assumed missing, but the image is verifiably a different person from Mayura's (different SHA, visually confirmed). Now inlined on her profile page. **Worth a human sanity-check that it is in fact Meghna**, given the filename.
3. **Learning Network typo fixes** — "saeries" → "series" and the title double-space. **Resolved: leave as-is.** PDF p.50 explicitly states "Content - to be kept as is" for this page, which matches the user's earlier instruction. Don't re-raise.
4. **Retroactive dead-space audit** on any older/thin sections — offered for About / Our People / Impact / Profile.
5. **Real images for gallery/framework/member-logo/photo elements** load only on the live server. Placeholders are in place; confirm they resolve once deployed.
6. ~~**Build the remaining pages the PDF has full approved copy for**~~ — **done.** URC + RFC (§12), City Mixers + Annual Forum 2025 (§16), Our Members + the whole Fellowship group (§18). Every page the PDF specifies now exists locally.
7. ~~**Wire up the JavaScript**~~ — **done, see §3b.**
8. **Still open on the profile pages:** `our-people.html` now links all 28 cards to local profiles, but `impact.html`'s three "In their words" portraits are not linked to them. Low priority.
9. **Photos on the profile pages are 220×220 WebP re-encodes** of the live originals (which are only 250–300px to begin with). If higher-resolution portraits exist, swapping them in would improve the hero on large screens.

---

## 8. Pre-launch checklist (all pages)

- Confirm the canonical domain/URLs are live and correct. **`our-people.html` now claims `https://urban.org.in/our-people/`, which does not exist yet** — it previously claimed `/our-members/`, but that URL genuinely serves the member *organisations* page, so two local pages were asserting one canonical. Either create `/our-people/` on WordPress or pick the real URL and re-run `fix_canonical.py`. URC is at `/urban-reforms-collective/`; RFC at `/requests-for-collaboration/`.
- **The "Shaping Urban Futures" Fellowship report has no URL yet.** `build_fellowship.py` has `REPORT_URL = None`; the section renders without its CTA. Set the constant and re-run once the report is published.
- Confirm mailboxes are live: `privacy@urban.org.in`, `connect@urban.org.in`, `reforms@urban.org.in`.
- **Schema image URLs must resolve for crawlers** — base64-inline images don't satisfy JSON-LD `image` fields. Point those at real hosted URLs before launch.
- Optional: split base64 assets into an `/assets` folder to shrink HTML and improve caching (currently everything is inline for portability).
- Re-run the full verification (§5) on the deployed URL.

**Local preview:** no build step — just open a file in `standalone/` directly in a browser, or serve the folder (`python -m http.server 8080` from `standalone/`, then visit `http://localhost:8080/about.html`).

---

## 9. Assets & references

**Not in this repo — these were sandbox working files from the original chat and were never exported. If they're needed again, they must be re-requested from that chat before its sandbox resets again:**
- **Brand guidelines** (`U-CAN_-_Brand_Guidelines.docx`) — missing.
- **12-month planning doc** (`Website_-_12-month_planning.docx`) — missing.
- **Raw people photos** (22 headshots, `.webp`/`.png`) — missing.
- **`avatars_b64.json`** (slug → base64 WebP avatar map) — missing. Note: the photos themselves are still present *inside* `standalone/our-people.html` and `standalone/impact.html` as inlined base64 `<img>` data, since those pages were recovered as full self-contained HTML. It's only the standalone JSON map (useful for reuse across the 23 individual profile pages, see §7) that's gone.

**Present:**
- **Logo:** inlined inside each page's HTML (`data:image/webp;base64,…` at `width="38"`) — no separate file needed.
- **Live report (RFC):** https://urban.org.in/from-parellel-to-together-building-a-collaboration-practice-for-indias-urban-ecosystem
- **Learning Network PDFs:** one-pager + knowledge report (URLs inline in `standalone/learning-network.html`)

---

## 10. Quick-start for the next page

1. Get the target URL + the user's spec (title tag, meta, H1 to keep, any bug fixes, which sections stay verbatim).
2. Fetch the live page; extract content **verbatim**.
3. Copy `standalone/about.html` (the shell); swap meta/title/canonical/OG/JSON-LD/nav/`ids`.
4. Build the `<main>` — **every section must pass the dead-space rule (§4)**.
5. Reuse established patterns (teal-deep cards, stat panels, pull-quote cards, numbered question panels, card grids). Cycle accents through the teal family; lime only on dark.
6. Add `onerror` placeholders for any WordPress-hosted images.
7. Verify: 1 h1, CLS 0, no console errors, clean 320–1440, valid JSON-LD, consent + banner working, no ghost numerals/marks. (No Playwright/sandbox here — check by opening the file in a real browser; ask the user to confirm visually if you can't verify something yourself.)
8. Save the new file into `standalone/` (short filename, no `u-can-` prefix — see §1) and commit it (see the checkpoint workflow — same habit as other repos: commit after every round of changes).
9. Model choice is up to the user's current Claude Code default; the "Sonnet 4.6" note was specific to the original chat-based session and doesn't necessarily carry over.

**Golden rules:** content verbatim · no dead space · single h1 · CLS 0 · lime on dark only · no ghost numerals or logo marks · placeholders for server-only images · verify before shipping.

---

## 11. PDF content audit — result log (last full pass)

Every existing page was diffed against `Website - Plan and Content Revisions.pdf` (§1a) and against live urban.org.in. Result: **the redesigned pages already implement the PDF's approved copy closely.** Title tags, meta descriptions, H1s and opening hooks matched verbatim on homepage / about / our-people / learning-network; Impact's 8 proof points matched word-for-word.

Deltas found and fixed:
1. **Nav mislabel** — "Our Members" pointed at `our-people.html`. Per PDF these are different pages (§3a). Relabelled to "Our People" across all 8 files (23 links).
2. **`impact.html` meta description** — was bespoke; replaced with the PDF's exact wording (also synced the OG + Twitter copies).
3. **`impact.html` "Nine ways…"** — factually wrong, only 8 proof points exist → "Eight ways".
4. **`impact.html` Pratima Joshi quote** — two clauses had been dropped ("how officials perceive us", "thereby shortening our learning curve"). Restored to the PDF's full text; the verbatim rule (§1) applies to quotes especially.
5. **`about.html` "See our full impact"** — pointed at live `/media/` instead of the local `impact.html`.
6. **`index.html` "Meet our members" hero CTA** — pointed at the People page; now targets the on-page `#members` organisations section, so label and destination agree.
7. **Member logo naming** — "Praja.org" → "Praja Foundation" (alt + aria-label), matching the PDF's alt-text list.

Checked and deliberately **not** changed:
- **"saeries"** typo in `learning-network.html` — PDF says keep content as-is (§7.3).
- **Homepage "Our Members" / "Friends of U-CAN" headings** — the PDF's `"our members" → "Member Organisations"` H2 fix targets the standalone Our Members *page*, which isn't built locally; the homepage section headings were already correctly cased.
- **Member logos** — all 11 are present and correctly inlined as base64 with proper alt text; they are not broken.

---

## 12. URC + RFC build log (newest round)

Both previously-missing Phase 1 pages are now built, verified, and in `standalone/`.

**Sources used:** the content PDF (§1a pp.51–55) as the spec, plus the live pages for the sections the PDF marks "remains as is" (URC's *Need for a URC*, *What the URC aims to do*, and all 11 FAQs). Content is verbatim; nothing was invented. The live pages were pulled with `curl` into the scratchpad and parsed locally — `WebFetch`'s summariser refuses to reproduce long verbatim passages, so don't rely on it for content migration.

**PDF bug fixes applied (both verified in the built file):**
- FAQ #6 heading: `part ofthe URC` → **`part of the URC`**
- FAQ #11 mailto: now points to **`reforms@urban.org.in`** (was `info@urbanreformscollective.org`)

**How they were built:** `about.html` is still the shell, but the two new pages were assembled by a small builder (`shellkit.py` + `build_urc.py` / `build_rfc.py`, kept in the session scratchpad, not the repo). The shell's base64 stylesheet link is reused byte-for-byte; page-specific CSS is added as a plain `<style>` block before `</head>` rather than trying to edit the base64 blob. Reusable classes taken from the shell: `.wrap .sec .sec.alt .sec-head .kicker .lead .hero .hero-in .hero-tag .hero-lede .crumb .rv .d1/.d2/.d3 .btn .btn.line .btn.on-photo .btn.ghost-photo .ctaband .news .vh`. New classes added by these pages: `.hero-chips .hero-actions .split .body .cols2 .numcard .aims/.aim .blist .pq .gal .faq .fw`.

**Layout patterns used (all pass the §4 dead-space rule):**
- URC *Need for a URC* — body text left + teal-deep numbered `.numcard` right ("Urban reform requires:" 01/02/03).
- URC *What the URC aims to do* — two-column body (`.cols2`) + three `.aim` cards. The three aims are verbatim from FAQ #1's "it focuses on three things".
- URC *Gallery* — heading left + context note right, then 6 WordPress photos in a 3-col grid, each with a branded placeholder behind and `onerror="this.style.display='none'"` so the tile degrades cleanly.
- URC *FAQs* — 11 `<details>/<summary>` items, keyboard-accessible, first one open. **Native `<details>` was used deliberately so the accordion works with zero JS** (§3b).
- RFC *Why collaboration matters* — text + 5-item `.blist` left, dark-Ink `.pq` pull-quote right; then a full-width lead-in and the 3 barriers as `.aim` cards (an earlier two-column version left the left column thin — that was a dead-space violation and was restructured).
- RFC *Framework* — text + report CTA left, framework diagram right with the same `onerror` placeholder treatment.

**These two pages have real JSON-LD** (`WebPage` + `BreadcrumbList` + `Organization`, and a full 11-question `FAQPage` on URC). Note this makes them the **only** pages in the repo with structured data — see §13.

---

## 13. Two more repo-wide gaps found while building (not introduced by this round)

1. ~~**JSON-LD is missing from all 8 unpacked pages.**~~ **Fixed.** Every page now carries exactly one `<script type="application/ld+json" data-ucan="schema">` block: `Organization` + `WebPage` + `BreadcrumbList` (plus `WebSite` on the homepage, `FAQPage` on URC, `Article` on the newsletter issue, and `Person` + `ProfilePage` on each member profile). Regenerate with `add_jsonld.py`; profile schema is emitted by `build_profiles.py`.
2. **`about.html` had the `.sec-num` ghost numerals back** — 3 of them, fully styled and rendering, which §3 says is a regression. Removed, and `.sec-head` collapsed to a single column via a small `<style>` override.

### Line endings differ between old and new pages
The 8 unpacked pages use `\r\r\n` (§3a). **The two new pages use normal `\n`.** Check which you're editing before doing multi-line string substitution — a script that assumes one will silently fail on the other.

### Nav breakpoint overrides (why they exist)
Going from 6 to 7 nav items overflowed three pages horizontally, which is a hard §5 failure. Each header has its own breakpoint, so each got a small `<style>` override appended before `</head>`:
- shell pages (`.bar`): burger now at **≤1000px** (was 860px), plus a tighter gap between 1001–1180px
- `index.html` (`.nav .links`): burger now at **≤1000px** (was 880px)
- `newsletter.html` (`.header .nav`): mobile sheet now at **≤1240px** (was ≤1000px)

If nav items are ever added or removed, **re-run the overflow check** — these thresholds are tuned to exactly 7 items.

### Verification harness
Playwright is installed in the session scratchpad with `verify.js`, which checks all 10 pages at 13 widths (1440→320) for horizontal overflow, plus single-`h1`, valid JSON-LD, no `.sec-num`/`.sec-mark`, and zero console/page errors. **All 10 pages currently pass.** Rebuild it with `npm i playwright && npx playwright install chromium`, serve `standalone/` on a port, and point the script at it.

While fixing the nav overflow, three **pre-existing** overflow bugs were also fixed (confirmed against the previous commit first, so they weren't mine):
- `newsletter.html` @320/@360 — `.nl-hero-grid` track was sized by min-content; `.header-cta` buttons and the `.featured-card` track also overflowed.
- `newsletter-urban-brief-june-2026.html` @768 and below — `figure.sec-img` carried 40px side margins inside an already-padded `.wrap`.

---

## 14. Member profile pages (23 of them) + how to regenerate

`profile-<slug>.html` exists for **every one of the 23 people** on Our People, all built from the approved `profile-siddharth-pandit.html` layout, and all 28 cards on `our-people.html` now link to them locally.

### The old blocker is gone
§7 used to say the other 22 bios had to come from the user. They didn't: **every bio, portrait and LinkedIn URL is on `urban.org.in/member/<slug>/`**, which the original chat's sandbox couldn't reach (403). `curl` from here reaches it fine. Nothing was written by hand — all content is verbatim from those pages.

### The generation pipeline (session scratchpad, not in the repo)
Run in this order:
1. `roster.py` → `roster.json` — scrapes name / slug / role / group / inlined 120px avatar out of `our-people.html`. **Gotcha:** Our Team cards use `class="p-role team"`, not `class="p-role"` — a regex pinned to the exact class silently drops all 4 team members and yields 19 people instead of 23.
2. `fetch_bios.py` → `bios.json` — bio paragraphs + personal LinkedIn. **Gotcha:** match `linkedin.com/in/` specifically; a looser pattern grabs the footer's U-CAN *company* page and every member ends up with the same link. 16 of 23 have a personal profile; the other 7 correctly render no LinkedIn button and `—` in the At-a-glance panel.
3. `fetch_photos.py` → `photos220.json` — downloads the full-res portrait, centre-square-crops, resizes to 220px, re-encodes as WebP (~4–9KB each). Also SHA-compares every source image and reports duplicates, which is how Meghna's photo was confirmed genuine rather than a copy of Mayura's.
4. `build_profiles.py` — writes all 23 pages and `profile_map.json` (slug → filename) for relinking `our-people.html`.

### Conventions baked into the generated pages
- **Filename:** `profile-<slug>.html`, with any trailing `-2` stripped (`viraj-tyagi-2` → `profile-viraj-tyagi.html`). Canonical still points at the real live URL, `/member/viraj-tyagi-2/`.
- **Our Team vs everyone else:** for team members `role` is a job title and the organisation is U-CAN; for everyone else `role` holds their *organisation*. The template's role/affiliation lines and the At-a-glance panel switch accordingly.
- **People in two groups** (e.g. Founding Circle *and* Stewardship Team) get the first as the hero badge and both listed in At-a-glance.
- Each page carries `Person` + `ProfilePage` + `BreadcrumbList` + `Organization` JSON-LD, `og:type=profile`, and a meta description trimmed from the opening bio sentence.
- A monogram placeholder exists for anyone without a photo — currently unused, since all 23 have one.

### Regenerating after a change
The builders are **idempotent**: re-running overwrites all 23 pages. But they read the *current* `profile-siddharth-pandit.html` as the template, so anything injected site-wide afterwards (JS, cookie banner, nav edits) is inherited automatically — which also means **re-running after a site-wide change will pick it up, and re-running before it will not**. Order matters: inject site-wide changes first, then rebuild profiles, or just re-run both.

---

## 15. Verification harness (expanded)

Two scripts in the scratchpad, both pointed at a static server on `standalone/`:

- **`verify.js`** — all 32 pages × 13 widths (1440→320): zero horizontal overflow, exactly one `<h1>`, ≥1 valid JSON-LD block, no `.sec-num`/`.sec-mark`, zero console/page errors.
- **`test_js.js`** — 47 functional assertions: the DPDP gate on all four form variants (blocks empty, blocks email-without-consent, accepts both, logs the consent record only on success), the cookie banner (hidden at 5s, visible at ~11s, dismiss, `localStorage`, footer reopen), the burger menu on all three markups (open/close/aria/Escape), count-ups (normal + reduced-motion), and the FAQ accordion.

**Both suites pass fully as of this round.** Re-run them after any site-wide change; the nav-width thresholds in §13 and the form gotchas in §3b are exactly the kind of thing they catch.

---

## 16. Events pages: City Mixers + Annual Forum 2025

Both built from the PDF (§1a pp.35–46), cross-checked against the live pages for the sections the PDF marks unchanged. Builder: `build_events.py` in the scratchpad (uses `shellkit.py`).

**`city-mixers.html`** — all 11 mixers, newest first, each an alternating image + write-up row (`.mx`). Copy is verbatim from the PDF.
**`annual-forum-2025.html`** — hero + Quick Overview (text + a stats card) + the report block + all 8 testimonials in a 2-column grid + a 15-photo gallery + the 4 partner logos. The PDF's two named heading fixes are applied: **"In Their Words"** (trailing ellipsis dropped) and **"Photos from the Forum"** (capitalised).

### Two content discrepancies found — PDF was followed, live site looks wrong
1. **The live City Mixers page dates two mixers as 2026 that the PDF dates 2025** (WRI India, April 11; Artha Global, April 14). The PDF's sequence is internally consistent and calls the WRI one "the first-ever U-CAN City Mixer", so the PDF was followed. The live page also lists two extra entries (Artha 24 Apr 2025, eGov 10 Apr 2025) that look like duplicates of the 2026 ones with the wrong year, and is **missing Shelter Associates (15 May 2026)** which the PDF has. **Worth someone reconciling the live page.**
2. **The Reap Benefit / CPR photos may be swapped on the live site.** The file named `RB-mixer.webp` sits on the CPR entry and `gdgdg.jpg` on the Reap Benefit entry. The live DOM pairing was mirrored (it's verifiably correct for the other nine — the file `WhatsApp-Image-2025-10-10…` lands exactly on the 10 Oct 2025 mixer), so the pairing here is the source's, not an error introduced in the rebuild. Flagged for a human to confirm.

Shelter Associates has no photo on the live page, so its card shows a branded "Photographs coming soon" tile.

---

## 17. Four traps worth knowing before touching the build scripts

These all cost real debugging time this round.

1. **`rebuild_nav.py` is only idempotent because it explicitly strips its own previous output first.** Its container swap finds an opening tag and then the next `</div></div>`, but the markup it inserts *contains* `</div></div>` — so a second pass matches inside its own output and orphans the remainder loose into `<header>`, where the mobile menu then renders on desktop. If you change that markup, keep `strip_previous()` in step with it. Symptom: mobile nav links appear stacked under the header at desktop width.
2. **`shellkit.py` reads `about.html` as the shell, and about.html now carries its own JSON-LD, `<style>` and `<script>` blocks.** These are stripped during head extraction — without that, every newly built page silently inherits *About's* schema (telling crawlers the new page is the About page) plus a duplicate nav stylesheet. Check `ld=1` in `verify.js` output after building anything new.
3. **Never construct a WordPress image URL.** Both the folder (`/2025/09/` vs `/2025/10/`) and the size suffix (`-768x576`) matter, and neither is guessable — the upload month often doesn't match the event month. Copy the exact `src` string out of the fetched live page. Stripping `-768x576` to get "the original" 404s about half the time.
4. **`loading="lazy"` makes image checks lie.** A headless check right after `load` reports most images broken simply because they never entered the viewport. Scroll the full page first, then wait, then count `naturalWidth`. (`imgcheck2.js` does this; it's how both pages were confirmed at 10/10 and 20/20.)

---

## 18. Our Members + the Fellowship group (newest round)

The last PDF-backed pages are built. 34 pages → **59**.

| Page | Source |
|---|---|
| `our-members.html` | PDF p.57 verbatim. Both H2 casing fixes applied ("our members" → **Member Organisations**, "friends of u-can" → **Friends of U-CAN**) and the PDF's alt text on all 11 logos. Logos were lifted from `index.html`, where they were already base64-inlined — no re-download. **"Roots and Horizons" is deliberately absent**: the PDF moves it to Impact. |
| `fellowship.html` | PDF pp.20–22 verbatim. Note the PDF **rewrites** the live page (past tense, "women leaders", inaugural cohort framing) — the PDF wins. |
| `meet-the-fellows.html` | PDF p.26, including its exact `alt` strings. |
| `profile-fellow-<slug>.html` ×8 | Bios/photos/host org from `urban.org.in/<slug>/`; title tag + meta description verbatim from PDF pp.27–28. |
| `fellow-blogs.html` | PDF p.24 H1 + intro line; 58 posts scraped from the 10 paginated listing pages. Posts open on urban.org.in. |
| `ld-calendar.html` + `ld-<slug>.html` ×12 | PDF p.30 H1/intro and the per-session title tags + meta descriptions from pp.30–31; session lead / about-the-session copy from `urban.org.in/etn/`. |

### Scrapers (scratchpad)
`prep_members.py` · `prep_fellows.py` · `prep_fellow_photos.py` · `prep_sessions.py` · `prep_blogs.py` · `prep_issues.py`, then `build_members.py` · `build_fellowship.py` · `build_ld.py` (both use `shellkit.py` + `newcss.py`).

### Scraping gotchas that cost time
- **Every fellow/session page renders its content twice** (a desktop block and a mobile block), often *reworded* rather than duplicated, so de-duplicating by exact text fails. Cut the document at the **second** occurrence of the block's anchor phrase ("Meet the Fellow", "Session Lead") instead.
- **The anchor is not always first.** Ramya's page puts the icon-box widgets *above* "Host Organization", so an earlier heuristic that cut at that label returned zero bio paragraphs for her alone. Anchor on "Meet the Fellow".
- **Elementor icon-box fields** are `<h3 …>LABEL</h3> … <p class="elementor-icon-box-description">VALUE</p>`. A loose regex between label and value silently jumps to the *next* field when a value is empty (Shubhi and Shreya have no Joining Location). Require `</span></h3>` immediately before the `<p>`.
- **The blog listing has no `<article>`** — cards are `<div class="post-item …">`. Split on that, not on `<article>`.
- **Fellow portrait URLs are not on the profile pages** (the first `2024/10/` upload there is a decorative shape). Take them from the listing page and pair each with the nearest following `/slug/` link. Two are named `unnamed.jpg` / `unnamed-1.jpg` — verified against each fellow's own page rather than guessed.
- **Name spellings disagree**: the roster says *Sharathppriyaa*, the blog bylines say *Sharathappriyaa*, and PDF p.28 says *Sharathpriyaa*. The pages use PDF p.26's **Sharathppriyaa Venkatesan**; only the p.28 meta description keeps the PDF's own spelling. Worth one human decision.

### Watch out when editing these builders
- **Heredocs mangle backslash escapes.** A `\b` written through a `python - <<'PY'` heredoc landed in `prep_sessions.py` as a literal 0x08 byte, so the regex silently never matched. `cat -A` is how it was found. The same channel turns `\n` into a real newline inside JS string literals and corrupts em-dashes. Prefer `Write` for anything containing escapes or non-ASCII.
- **`rfc.html` uses `\r\n`**, not `\r\r\n` and not `\n`. The old two-way eol sniff (`'\r\r\n' if … else '\n'`) picks the wrong one and every multi-line match fails. Sniff all three.

---

## 19. Clutter removal + rhythm pass

The user called out repeated filler; these are now hard rules.

- **No per-page newsletter band.** The full-width `.news` / `.newsletter` section is gone from all 9 pages that had it. The site's single signup lives in the footer (§3c) and on `newsletter.html` itself. `shellkit.build()` no longer emits one.
- **No navigation-only CTA bands.** "Go deeper into U-CAN" and friends were removed from about / impact / our-people / annual-forum / learning-network, and the "Explore more about U-CAN" link grid from newsletter.html. Header and footer already carry those links. Bands that lead with a **real action** (a mailto invitation on URC / RFC / City Mixers) are kept.
- **No scaffolding sentences.** e.g. "Names, roles and organisations as listed on U-CAN's About page…" — copy that explains the page rather than being the page.
- **Vertical rhythm** (`rhythm.py`, injected last so it beats the base64 stylesheets): `.sec` and `.section` padding dropped from up to 116px to ~80px; `.sec-head` collapsed to a **single** column — it was `auto 1fr` with an empty second column, which is what pushed headings into the left third; a section sitting directly on a full-bleed stat band loses its bottom padding.
- **RFC framework section**: the heading was in its own full-width `.sec-head` above a two-column `.split`, so the tall diagram had nothing beside its lower half. The heading now sits *inside* the left column (`.split-mid`, vertically centred).

## 20. The newsletter page told lies

Fixed in `fix_newsletter3.py`:
- The "latest edition" card pointed at `…/2026/07/U-CAN-Newsletter-July-2026.pdf`, which **404s** — that edition was never published.
- The archive listed 2024/2025/2026 months as tiles but **every one except June 2026 was `href="#"`**.
- The year-filter buttons had no behaviour behind them.
- An orphaned `<div class="sheet" id="mob-nav">` survived the header unification.

The archive is now the **13 real issues** on urban.org.in (Nov 2023 – Nov 2024, scraped by `prep_issues.py` across both listing pages), each linking to its live edition, with the one fully designed edition in this redesign — The Urban Brief, June 2026 — as the featured card. The year filter and "show all" are wired in `ucan.js`.

**Pre-existing bug found here, not introduced:** `.mast-title span` and `.stat-num` on the June 2026 brief were serialised by the original mhtml unpack as `background-image: ;` with `-webkit-text-fill-color: transparent` — i.e. **invisible text**, and invisible in the committed baseline too. A brand gradient is restored in the injected `<style data-ucan="brief">`, with an `@supports` fallback.

## 21. Verification harness (current)

Both suites run against `python -m http.server 8099` in `standalone/`, and both take the page list from disk.

- **`verify.js`** — all **59** pages × 13 widths (1440→320): zero horizontal overflow, exactly one `<h1>`, ≥1 valid JSON-LD block, no `.sec-num`/`.sec-mark`, zero console/page errors. **All pass.**
- **`test_js.js`** — **91 assertions, 0 failing**: the DPDP gate on 9 pages (now targeting the single footer form `#fnform`), the cookie banner, the burger on 5 pages (one markup now), count-ups normal + reduced-motion, the FAQ accordion, and the newsletter archive's load-more + year filter.
- **`imgcheck2.js` / `imgblogs2.js`** — remote images, after scrolling (and after expanding collapsed grids). City Mixers 10/10, Annual Forum 20/20, RFC 1/1, **fellow-blogs 58/58**.

**Trap this round:** the blog grid's "Show all posts" button was a no-op because the archive handler was scoped to `.issue` only, so 46 of 58 thumbnails never even requested. The handler covers `.issue, .bcard`. When a listing looks like it has broken images, **check the reveal button before blaming the URLs.**
