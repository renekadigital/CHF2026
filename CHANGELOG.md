# Changelog

All notable changes to the Center for Houston's Future WordPress theme will
be documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/) and the
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/) format.

- **MAJOR** version — incompatible structural changes (e.g., post type rename, DB schema change)
- **MINOR** version — backwards-compatible features (new CPT, new template, new hook)
- **PATCH** version — bug fixes and small improvements

---

## [Unreleased]

_Nothing yet. Add planned changes here as they're worked on._

---

## [5.0.0] — 2026-04-15

Initial release of the Elementor Pro-optimized v5 theme. Full rewrite of the
v4 static-HTML architecture into a modern WordPress + Elementor Pro + ACF Pro
stack built for 2026-standard performance, accessibility, and maintainability.

### Added

#### Theme architecture
- Hello Elementor child theme foundation (`Template: hello-elementor`)
- Three modular `inc/` files: `custom-post-types.php`, `security.php`, `elementor-setup.php`
- `CHF_VERSION` constant (5.0.0) for cache busting
- PHP 8.2 requirement with `Requires PHP: 8.2` header

#### Design system
- CSS custom property token system in `style.css` `:root`
  - Colors: `--navy`, `--navy-deep`, `--green`, `--green-dark`, `--green-light`, `--gold`, `--white`, `--off`, `--rule`
  - Typography: `--font-sans` (Inter), `--font-serif` (Merriweather)
  - Layout: `--nav-height`, `--container-pad`, `--radius-pill`, `--radius-md`
  - Motion: `--ease-spring`, `--ease-out-expo`, `--ease-out-quart`
  - Effects: `--shadow`, `--green-glow`, `--focus-ring`
- `assets/css/design-system.css` — reset, typography scale using `clamp()`,
  button utilities, section utilities, scroll reveal, print styles, reduced-motion
- `assets/css/elementor-overrides.css` — widget-level styling for Elementor
  native widgets (headings, buttons, nav menu, forms, posts, archives)
- WCAG AA contrast-verified color pairs documented in design system

#### Content model
- `chf_initiative` custom post type
  - Archive at `/initiatives/`
  - Single at `/initiative/{slug}/`
  - REST API enabled (`show_in_rest: true`)
- `chf_event` custom post type
  - Archive at `/events-archive/`
  - Single at `/event/{slug}/`
- `initiative_category` taxonomy with 3 default terms:
  energy-climate, health-equity, immigration
- `event_type` taxonomy with 6 default terms:
  conference, forum, workshop, dinner, summit, meeting
- ACF field groups registered programmatically (survive migrations):
  - **Hero Fields:** `hero_eyebrow`, `hero_highlight_word`, `hero_subtitle`,
    `hero_background_image` (attached to page, initiative, event, post)
  - **Event Details:** `event_date` (date picker, Ymd), `event_time`,
    `event_location`, `event_registration_url`
- Rewrite rules auto-flush on theme activation

#### Elementor Pro integration
- "CHF Widgets" custom widget category in the Elementor panel
- 4 custom Dynamic Tags (all guarded with `class_exists()`):
  - `CHF_Hero_Eyebrow_Tag` → reads ACF `hero_eyebrow`
  - `CHF_Hero_Subtitle_Tag` → reads ACF `hero_subtitle`
  - `CHF_Event_Date_Tag` → reads and formats ACF `event_date`
  - `CHF_Event_Location_Tag` → reads ACF `event_location`
- `register_all_core_locations()` enabled for Theme Builder
- Global Elementor defaults set on theme activation (colors, fonts, container mode)
- 10 importable Elementor JSON templates in `elementor-templates/`:
  - `global-header.json` — site-wide header
  - `global-footer.json` — site-wide footer
  - `section-hero.json` — reusable hero block
  - `section-newsletter.json` — reusable newsletter CTA
  - `template-homepage.json` — homepage layout (119 elements)
  - `template-interior.json` — interior page layout
  - `template-initiative-single.json` — initiative single
  - `template-event-single.json` — event single
  - `template-events-archive.json` — events listing
  - `template-news-archive.json` — news/posts listing

#### Security (via `inc/security.php`)
- HTTP security headers on every response:
  - `Strict-Transport-Security` (HSTS, 1 year, includeSubDomains)
  - `Content-Security-Policy` (with `unsafe-eval` for Elementor editor)
  - `X-Content-Type-Options: nosniff`
  - `X-Frame-Options: SAMEORIGIN`
  - `Referrer-Policy: strict-origin-when-cross-origin`
  - `Permissions-Policy` (camera/mic/geolocation off)
- XML-RPC disabled
- WordPress version generator meta stripped
- File editing disabled (`DISALLOW_FILE_EDIT`)
- Generic login error messages (prevents username enumeration)
- RSD, WLW, shortlink, feed links removed from `<head>`

#### Frontend JavaScript (`assets/js/frontend.js`)
- Vanilla ES2022+ IIFE, zero jQuery dependency
- `IntersectionObserver`-based scroll reveal
- Counter animation for stat numbers
  - Unified selector: `.stat-number[data-target]`, `[data-count]`, `.counter[data-target]`
  - `getTarget()` helper reads both `data-target` and `data-count`
- Smooth scroll with reduced-motion respect
- Back-to-top button (appears after 400px scroll)
- Header `.scrolled` class toggle past 60px scroll
- Elementor frontend lifecycle hooks for dynamically-loaded widgets
- Full `prefers-reduced-motion` support

#### Content import
- `chf-content-import.xml` — complete WXR 1.2 import (78 items: 20 pages,
  20 initiatives, 6 events, 32 nav menu items)
- All items pre-populated with:
  - Body content extracted from v4 static HTML archive
  - ACF hero field meta (eyebrow, highlight, subtitle)
  - Elementor meta flags (`_elementor_edit_mode`, `_wp_page_template`)
  - Yoast SEO meta descriptions
- Split-import fallback in `chf-import-split/` for hosts with tight resource limits:
  - `1-pages.xml` (20 items)
  - `2-initiatives.xml` (20 items)
  - `3-events.xml` (6 items)
  - `4-nav-menus.xml` (32 items)
  - `IMPORT-ORDER.md` with step-by-step guidance

#### Documentation packet
- `docs/README.md` — master index and audience router
- `docs/ARCHITECTURE.md` — technical architecture reference
- `docs/DESIGN-SYSTEM.md` — tokens, typography, components, motion
- `docs/CONTENT-GUIDE.md` — editor guide (non-technical)
- `docs/ELEMENTOR-GUIDE.md` — editor + theme builder guide
- `docs/DEVELOPER-REFERENCE.md` — hooks, filters, code snippets, release process
- `docs/MAINTENANCE.md` — weekly/monthly/quarterly/annual maintenance calendar
- `docs/TROUBLESHOOTING.md` — 15 symptom → fix playbooks
- `docs/LAUNCH-CHECKLIST.md` — pre-launch, launch day, post-launch checklists
- `docs/CREDENTIALS-TEMPLATE.md` — secure template for recording all site access
- `CHF-Technical-Documentation.pdf` — all 10 docs as a single 78-page printable PDF
- `INSTALL-GUIDE.md` — complete step-by-step installation walkthrough

### Changed

- Migrated from v4 static-HTML architecture to Elementor Pro container-based
  layouts (no more legacy Section/Column, no more PHP templates)
- Moved all layout responsibility from theme files to Elementor templates
- Theme CSS now contains **only** tokens and utilities — all component/layout
  CSS lives in Elementor overrides or inside Elementor itself
- Content type slugs migrated to the clean pattern documented in
  `INSTALL-GUIDE.md` § "Post-Import Slug Cleanup"

### Fixed (internal v5.0.0 QA audit)

- **Critical:** `register_all_core_location()` → `register_all_core_locations()`
  (missing trailing 's' — would silently break Theme Builder locations)
- Event dates converted from ISO `2026-05-14` format to ACF-compatible Ymd
  `20260514` format (all 6 events)
- Added missing `event_time` meta to 5 of 6 events
- Replaced duplicate Team content on Our Supporters page with proper
  supporters/donors content + correct hero subtitle
- Added parent assignment annotations for Supporters and Financials pages
- Resolved 27+ slug mismatches between INSTALL-GUIDE.md and actual XML values
- Added `class_exists()` guards to all 4 Dynamic Tag classes (prevents
  PHP fatal errors on plugin update races)
- Added `--green-light: #7dd870` design token (previously hardcoded in `.grad-text`)
- Counter animation selector now unified to cover all imported content markup
  (`.stat-number[data-target]`, `[data-count]`, `.counter[data-target]`)
- Populated empty Elementor template metadata on 6 JSON templates
- Replaced 2 off-brand button hover colors (`#489e3e` → `#3d8f35`)
- Fixed invalid font unit (`"custom"` → `"px"`) in homepage template
- 5 H3 headings switched from Inter to Merriweather (brand consistency)
- Added responsive font sizes (tablet + mobile) to H1/H2 across 9 templates
- Removed stale `homepage.css` enqueue block from `functions.php`

### Removed

- All legacy v4 files moved to `_archive_v4/` (retained as backup):
  - Old PHP templates and template-parts
  - Old CSS/JS files
  - Old `functions.php` and `style.css` (v4 versions)
  - Old XML import and install guide
- jQuery dependency — theme now runs fully on vanilla JavaScript
- Legacy Section/Column Elementor markup — all templates rebuilt with containers

### Security

- See "Security" under Added above. All hardening measures introduced in v5.0.0.

---

## Unreleased planning notes

_These are ideas under consideration for future versions. Not yet committed._

### Possible 5.1.0 features
- New `chf_report` CPT for downloadable PDF reports
- Elementor template for a "Projects" grid view
- Integration with HubSpot newsletter signup
- Internal link audit automation

### Possible 5.2.0 features
- Spanish translation support via Loco Translate
- Team member CPT (replace current Team page with dynamic listing)
- Donation form integration (Stripe + Elementor)

### Under consideration for 6.0.0
- Full conversion to WordPress Full Site Editing (if Elementor becomes unmaintained)
- Headless mode (WordPress as CMS + Next.js frontend)

---

## Version support policy

| Version | Released | Status | Support until |
|---|---|---|---|
| 5.x | 2026-04 | **current** | TBD |
| 4.x | 2024-xx | legacy | archived in `_archive_v4/` |
| 3.x and earlier | — | unsupported | — |

Only the current major version receives fixes. Security issues in legacy
versions require upgrading to the current version.

---

## How to cut a new release

1. Update `CHF_VERSION` constant in `functions.php`
2. Update `Version:` header in `style.css`
3. Add a new `## [X.Y.Z] — YYYY-MM-DD` section at the top of this file
   (below Unreleased)
4. Move relevant items from `[Unreleased]` into the new section
5. Commit: `git commit -m "chore: release vX.Y.Z"`
6. Tag: `git tag vX.Y.Z && git push --tags`
7. Regenerate the PDF doc packet
8. Upload a zip of the theme to the client's private asset storage
9. Notify the client with the changelog excerpt
