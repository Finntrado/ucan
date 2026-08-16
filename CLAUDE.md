# CLAUDE.md — U-CAN Website Redesign

Working guide for continuing the **urban.org.in** (Urban Collective Action Network / U-CAN) website redesign in Claude Code. Read this fully before touching any page.

---

## 1. What this project is

Redesigning the live urban.org.in site, one page at a time, as **brand-compliant, single-file, responsive HTML pages** in a fresh "editorial civic" design language. Each page is a self-contained `.html` file (inline CSS + JS + base64 assets) that can be dropped straight into WordPress/Elementor or served statically.

**Repo layout (Claude Code, persistent):**
```
ucan/
├── CLAUDE.md
├── pages/                  # the actual editable pages — work here
│   ├── u-can-homepage.html
│   ├── u-can-about-us.html          # THE SHELL — copy this to start a new page
│   ├── u-can-our-people.html
│   ├── u-can-impact.html
│   ├── u-can-profile-siddharth-pandit.html
│   ├── u-can-learning-network.html
│   ├── u-can-newsletter.html
│   └── u-can-newsletter-urban-brief-june-2026.html
└── _mhtml-originals/       # read-only backup of the browser-saved .mhtml snapshots these came from — don't edit
```
This replaced an earlier handoff where pages only existed as `.mhtml` browser snapshots (Chrome "Save Page As → Single File"); they were unpacked into plain self-contained `.html` (resources re-inlined as data URIs) and moved into `pages/`. Work directly in `pages/*.html` going forward.

**Non-negotiables for every page:**
- Content is used **verbatim from the source page** — no rewriting, no paraphrasing, no invented copy. Only change something if the user explicitly asks (usually named bug fixes). If a typo exists in the source, preserve it and flag it; don't silently fix.
- SEO/AEO/GEO optimised: JSON-LD `@graph`, single `<h1>`, canonical + hreflang (`en-IN` + `x-default`), OG + Twitter tags, `speakable`.
- Targets: 90+ PageSpeed / GTmetrix A+, **CLS 0**, zero JS console errors, clean responsive from **320px to 1440px**.
- **No dead space** (see §4 — this is a hard rule the user cares about a lot).
- DPDP (India Digital Personal Data Protection Act 2023) compliant newsletter consent + cookie banner on every page.

**User working style:** casual, direct, action-oriented. Wants complete copy-ready files (never snippets), verbatim source content, strong emphasis on zero dead space. When asked to redesign pages, **use Claude Sonnet 4.6** (the user's stated model preference for this project).

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

Everything lives in the repo now — no sandbox, no reset. Work directly on the files in `pages/`.

### The reusable shell
`pages/u-can-about-us.html` is the **canonical shell** — it contains the full head, CSS, nav, footer, cookie banner, and JS. To build a new page:
1. Copy the About page to a new file in `pages/`.
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

---

## 6. Page inventory (all in `pages/`)

**Recovered and present in this repo:**

| File | Page | H1 | Status |
|---|---|---|---|
| `u-can-homepage.html` | Homepage (editorial-civic redesign) | — | Done, in repo |
| `u-can-about-us.html` | About Us — **THE SHELL** | About U-CAN | Done, in repo |
| `u-can-our-people.html` | Our People (28 cards, 22/23 real photos) | Our People | Done, in repo |
| `u-can-impact.html` | Impact ("What We've Built Together") | What We've Built Together | Done, in repo |
| `u-can-profile-siddharth-pandit.html` | Individual profile **template** | Siddharth Pandit | Done, in repo (template) |
| `u-can-learning-network.html` | Learning Network for Urban Managers | Learning Network for Urban Managers | Done, in repo |
| `u-can-newsletter.html` | Newsletter — Urban Governance Updates | Newsletter — Urban Governance Updates | Done, in repo (wasn't in the original chat's inventory — recovered from a saved snapshot) |
| `u-can-newsletter-urban-brief-june-2026.html` | The Urban Brief — June 2026 (newsletter detail) | The Urban Brief — June 2026 | Done, in repo (also not in the original inventory) |

**NOT recovered — no source exists in this repo, needs to be regenerated or re-fetched from the original chat before it's lost:**

| File | Page | Status |
|---|---|---|
| `u-can-urban-reforms-collective.html` | Urban Reforms Collective (URC) | **Missing.** No `.mhtml`/`.html` snapshot was ever saved for this one. |
| `u-can-rfc.html` | Request for Collaboration (RFC) | **Missing.** Same — never saved out of the original chat's sandbox. |
| `u-can-homepage-redesign.html` vs original homepage | Two homepage variants were mentioned in the original session; only one homepage snapshot (`u-can-homepage.html`) made it out. If the other variant matters, it needs to be re-pulled from the original chat. |

### Page-specific notes

**About Us** — the shell. Vision (dark teal card + "Download our Brochure"), Mission, 5 Governing Principles, 20-milestone Timeline (newest June 2026 "Mumbai Declaration / Urban Reforms Collective launch" at top, flagged "Latest" with lime pill + orange marker; alternating L/R desktop, single left rail mobile; JSON-LD ItemList intentionally kept chronological ascending).

**Our People** — 4 groups: Founding Circle (12), Steering Committee (8), Stewardship Team (4), Our Team (4) = 28 card instances (some people appear twice). **22 of 23 people have real circular photos** (optimised ~120px WebP, base64-embedded, ~55KB total; stored in `avatars_b64.json`; sources in `/mnt/user-data/uploads/`). **Only Meghna Bandelwar Indurkar (Praja Foundation) lacks a photo** — uses an on-brand monogram/person-glyph placeholder. Cards link to `/member/{slug}/`. Org-colour-coded within the teal family.

**Impact** — rebuilt from the user's uploaded `impact-preview` file. Hero + 4-figure animated band + **8 proof points as a 2-column card grid** (cycling teal accents, 3 inset testimonials: Nidhi Batra / Shubhi Kesarwani / Gurjit Singh Dhillon) + **"In Their Words"** = 3 voice cards **with portraits** (Mayura Gadkari + Pratima Joshi real photos; Meghna Indurkar on-brand placeholder, tagged `data-ref="placeholder"`).

**Profile template** (Siddharth Pandit) — split hero (large circular portrait + group badge + LinkedIn button), bio + sticky "At a glance" sidebar (Role / Group / Organisation / LinkedIn), back-bar. This is the **approved template for all 23 individual profile pages** — see §7.

**Learning Network** — content verbatim incl. preserved source typo **"saeries"** (should be "series") and a double space in the source title (both flagged to user, fix still pending their OK). Hero (3 chips) → Overview (2-col split: statement + body left, 5-row stat panel right) → How it took shape (text + teal-deep numbered card) → 3 pilot workshop cards (Maharashtra 35+, Andhra Pradesh ~40·13 cities, Maharashtra virtual) → What pilot revealed (3 numbered) → From pilot to platform (5 target-dot items) → Why this matters (teal band, 4 lime stat callouts + network motif) → report/one-pager PDF links.

**Urban Reforms Collective (URC)** — **file missing from this repo, see §6.** Notes preserved from the original session in case it needs rebuilding: two bug fixes were applied (FAQ #6 heading "part ofthe" → "part of the"; FAQ #11 mailto `info@urbanreformscollective.org` → `reforms@urban.org.in`, visible text was already correct). Sections: hero (3 chips) → Need for URC (text + teal-deep "alignment gap" 01/02/03 card) → What URC aims to do (2-col body + 3 aim cards) → Gallery (heading left + context note right, 6 WordPress images with `onerror` placeholders, asymmetric grid) → **11-FAQ accordion** (keyboard-accessible: `aria-expanded`/`aria-controls`/`hidden`) → Get Involved band ("Write to us" → `mailto:reforms@urban.org.in`). `FAQPage` JSON-LD.

**Request for Collaboration (RFC)** — **file missing from this repo, see §6.** Notes preserved in case it needs rebuilding: "Read the Phase I Report" hero CTA → live report URL. Sections: hero (3 chips + 2 CTAs) → Why collaboration matters (text + 2 bullet lists left, dark Ink "collaboration gap" pull-quote card right) → What is the RFC (description + 3 stacked objective cards left, "Three questions the RFC explores" 01/02/03 panel right) → Practice-informed framework (text + report CTA left, 2 framework images right with `onerror` placeholders) → Know more band (`connect@urban.org.in`). All content verbatim.

---

## 7. Pending / standing offers

1. **Generate all 23 individual profile pages** using the Siddharth Pandit template (numeral-not-mark treatment; §3). **Blocker:** only Siddharth's full bio is available (fetched earlier). For the other 22, the user must supply bios, OR accept (a) header/photo/facts complete with a placeholder bio line, or (b) name/role/org/group only. **Photos:** `avatars_b64.json` itself is gone (see §9), but the 22 real photos still exist inlined inside `pages/u-can-our-people.html` and `pages/u-can-impact.html` — they can be re-extracted from there if needed rather than re-requesting from the original chat. Meghna Indurkar photo still missing regardless.
2. **Meghna Bandelwar Indurkar photo** — still needed (Our People monogram + Impact placeholder). Drop-in swap when provided.
3. **Learning Network typo fixes** — "saeries" → "series" and the title double-space. Offered; awaiting user's go-ahead (they said "use content as-is").
4. **Retroactive dead-space audit** on any older/thin sections — offered for About / Our People / Impact / Profile.
5. **Real images for gallery/framework/member-logo/photo elements** load only on the live server. Placeholders are in place; confirm they resolve once deployed.

---

## 8. Pre-launch checklist (all pages)

- Confirm the canonical domain/URLs are live and correct (note: People page lives at `/our-members/`; URC at `/urban-reforms-collective/`; RFC at `/requests-for-collaboration/`).
- Confirm mailboxes are live: `privacy@urban.org.in`, `connect@urban.org.in`, `reforms@urban.org.in`.
- **Schema image URLs must resolve for crawlers** — base64-inline images don't satisfy JSON-LD `image` fields. Point those at real hosted URLs before launch.
- Optional: split base64 assets into an `/assets` folder to shrink HTML and improve caching (currently everything is inline for portability).
- Re-run the full verification (§5) on the deployed URL.

**Local preview:** no build step — just open a file in `pages/` directly in a browser, or serve the folder (`python -m http.server 8080` from `pages/`, then visit `http://localhost:8080/u-can-about-us.html`).

---

## 9. Assets & references

**Not in this repo — these were sandbox working files from the original chat and were never exported. If they're needed again, they must be re-requested from that chat before its sandbox resets again:**
- **Brand guidelines** (`U-CAN_-_Brand_Guidelines.docx`) — missing.
- **12-month planning doc** (`Website_-_12-month_planning.docx`) — missing.
- **Raw people photos** (22 headshots, `.webp`/`.png`) — missing.
- **`avatars_b64.json`** (slug → base64 WebP avatar map) — missing. Note: the photos themselves are still present *inside* `pages/u-can-our-people.html` and `pages/u-can-impact.html` as inlined base64 `<img>` data, since those pages were recovered as full self-contained HTML. It's only the standalone JSON map (useful for reuse across the 23 individual profile pages, see §7) that's gone.

**Present:**
- **Logo:** inlined inside each page's HTML (`data:image/webp;base64,…` at `width="38"`) — no separate file needed.
- **Live report (RFC):** https://urban.org.in/from-parellel-to-together-building-a-collaboration-practice-for-indias-urban-ecosystem
- **Learning Network PDFs:** one-pager + knowledge report (URLs inline in `pages/u-can-learning-network.html`)

---

## 10. Quick-start for the next page

1. Get the target URL + the user's spec (title tag, meta, H1 to keep, any bug fixes, which sections stay verbatim).
2. Fetch the live page; extract content **verbatim**.
3. Copy `pages/u-can-about-us.html` (the shell); swap meta/title/canonical/OG/JSON-LD/nav/`ids`.
4. Build the `<main>` — **every section must pass the dead-space rule (§4)**.
5. Reuse established patterns (teal-deep cards, stat panels, pull-quote cards, numbered question panels, card grids). Cycle accents through the teal family; lime only on dark.
6. Add `onerror` placeholders for any WordPress-hosted images.
7. Verify: 1 h1, CLS 0, no console errors, clean 320–1440, valid JSON-LD, consent + banner working, no ghost numerals/marks. (No Playwright/sandbox here — check by opening the file in a real browser; ask the user to confirm visually if you can't verify something yourself.)
8. Save the new file into `pages/` and commit it (see the checkpoint workflow — same habit as other repos: commit after every round of changes).
9. Model choice is up to the user's current Claude Code default; the "Sonnet 4.6" note was specific to the original chat-based session and doesn't necessarily carry over.

**Golden rules:** content verbatim · no dead space · single h1 · CLS 0 · lime on dark only · no ghost numerals or logo marks · placeholders for server-only images · verify before shipping.
