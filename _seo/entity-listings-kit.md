# U-CAN entity listings kit (Wikidata and other platforms)

Prepared 2026-11-26. Purpose: help search engines and AI assistants recognise U-CAN as one clear organisation. Facts below come from urban.org.in itself or from a web check made on the date above; anything not verified is marked **needs U-CAN**.

## 1. Status check (2026-11-26)

- **Wikidata:** a search for "Urban Collective Action Network" returned no items. There is no entry today.
- **Own profiles that exist:** website https://urban.org.in/, LinkedIn company page `urban-collective-action-network-u-can`, YouTube channel `@U-CAN24` (these two are already in the site's Organization `sameAs`).
- **Third-party pages found:** fellowship calls on concoursn.com and andpurpose.world, a job posting on devinfo.in and NGOBOX, and a WRI India LinkedIn post about the U-CAN City Mixers. They show U-CAN exists, but they are listings, not independent coverage.

## 2. Honest assessment: is a Wikidata entry safe to create now?

Not yet. Wikidata is easier than Wikipedia, but an item must be backed by serious, publicly available, independent references, and thin items are deleted.

- **What would count as good references:** a government or registry listing (NGO Darpan, GuideStar India, company or society registration), a member organisation's page that describes U-CAN, a news or sector-media article about the Annual Forum or the Mumbai Declaration, a published report that names U-CAN.
- **What is weak:** U-CAN's own site, its own LinkedIn and YouTube, job and fellowship listings.
- **Wikipedia:** do not attempt. Notability and conflict-of-interest rules make rejection likely, and it can harm the brand.
- **Conflict of interest and payment:** anyone editing about their own organisation should keep a neutral tone. A paid editor must disclose employer, client and affiliation on their Wikimedia user page (Wikimedia Terms of Use). Use one personal account with two-factor authentication. **Never share the password with anyone, including an assistant.**

## 3. Wikidata data sheet (enter by hand once references exist)

Verify every property ID on its Wikidata page before use.

| Field | Value | Source / status |
| --- | --- | --- |
| Label (en) | Urban Collective Action Network | site |
| Alias | U-CAN | site |
| Description (en) | network of organisations working to strengthen urban problem-solving in Indian cities | neutral wording from the site's own description; keep it factual |
| instance of (P31) | organization (Q43229); refine to a more exact type only when the legal form is known | **needs U-CAN** (legal form) |
| country (P17) | India (Q668) | site |
| inception (P571) | 2022 | site timeline and structured data say 2022 (first milestone March 2022); needs an independent source before use |
| official website (P856) | https://urban.org.in/ | site |
| LinkedIn company ID (P4264) | urban-collective-action-network-u-can | public page; confirm it is the official page |
| YouTube channel ID (P2397) | the channel ID of @U-CAN24 (not the handle) | resolve from the channel page |
| headquarters location (P159) | leave out | **needs U-CAN** (none is stated on the site) |

Every statement except the website needs a reference (P854 reference URL plus retrieved date). Do not add statements that no reference supports.

## 4. Platform plan

| Platform | Why it helps | Needs from U-CAN | Who does it |
| --- | --- | --- | --- |
| Google Search Console and Bing Webmaster Tools | Faster indexing, error reports, sitemap status | A verification token (a meta tag or DNS record) | U-CAN owner; Claude can add the tag |
| Partner organisation websites | The strongest legitimate signal: independent sites describing U-CAN and linking to it | Agreement from the member organisations to use one standard sentence and the name "Urban Collective Action Network (U-CAN)" | U-CAN; Claude can draft the sentence and the email |
| Press note | Independent coverage is what Wikidata and AI assistants cite | An approved announcement (Annual Forum 2025, Mumbai Declaration, Urban Reforms Initiative launch) and a media list | U-CAN; Claude can draft |
| NGO Darpan or GuideStar India | An authoritative registry entry, if U-CAN or its host organisation is a registered non-profit | Legal entity name, registration number, host organisation | U-CAN |
| LinkedIn and YouTube | Already exist; complete the About text, website link and consistent name | Admin access | U-CAN |
| Wikidata | Machine-readable identity that search engines and AI assistants read | References from the rows above | A named U-CAN person with their own account |
| Google Business Profile | Only useful with a public office or service address | A public address, if one exists | U-CAN, only if applicable |

Not recommended: Wikipedia, Crunchbase and similar company databases (they suit registered companies, not a network).

## 5. After a Wikidata item exists

1. Add its address to the Organization `sameAs` list on urban.org.in (the site's structured data), using the repo's build, and record the item number in this file.
2. Repeat the monthly AI-citation check in `_seo/ai-citation-log.md` and note any change.
3. Keep the name, description and logo identical across the website, LinkedIn, YouTube and the registry listing.

## 6. What Claude can and cannot do here

- **Can:** verify facts against the site, find and check references, draft the press note and the partner email, update the site's structured data, add verification tags, and track results.
- **Cannot, and should not:** create or edit the Wikidata item itself. It needs a person's own Wikimedia account, a conflict-of-interest disclosure, and references that only U-CAN and its partners can produce. Never send passwords or tokens in chat.
