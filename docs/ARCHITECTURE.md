# Architecture

How the CHF website is put together at a technical level. Read this first if
you're a developer inheriting the project, or any time you need to understand
why something is structured the way it is.

---

## 1. Stack overview

```
┌─────────────────────────────────────────────┐
│            WordPress 6.4+ (PHP 8.2)         │
├─────────────────────────────────────────────┤
│  Hello Elementor (parent theme)             │
│       ↓ extends                             │
│  CHF Theme (child, this repo)               │
├─────────────────────────────────────────────┤
│  Elementor Pro 3.x     Page builder        │
│  ACF Pro               Custom fields        │
│  Yoast SEO             Meta + schema        │
│  Wordfence             Security             │
└─────────────────────────────────────────────┘
```

**Why Hello Elementor as parent?** It's Elementor's official starter, weighs
~12 KB, and ships zero opinions. The CHF child adds all design tokens,
typography, custom post types, security headers, and Elementor dynamic tags on
top.

**Why a child theme at all?** So you can update Hello Elementor safely without
losing CHF customizations. Never edit the parent.

---

## 2. Theme file responsibilities

### `style.css`
- Declares the child theme to WordPress
- Defines the `:root` CSS custom property system (all design tokens)
- Zero layout or component CSS — Elementor handles visual layout

### `functions.php`
- Loads the three `inc/` modules
- Enqueues fonts and stylesheets in the correct order:
  1. Parent Hello Elementor stylesheet
  2. CHF child stylesheet (design tokens)
  3. `design-system.css` (reset + utilities)
  4. `elementor-overrides.css` (widget tweaks)
  5. `frontend.js` (deferred)
- Registers navigation menus (Primary, Footer Utility, Footer Initiatives, Footer Leadership, Social)
- Declares theme support (HTML5, title tag, post thumbnails, custom logo)
- Adds preconnect hints for Google Fonts

### `inc/custom-post-types.php`
- Registers `chf_initiative` CPT (20 items)
- Registers `chf_event` CPT (6 items)
- Registers `initiative_category` taxonomy (energy-climate, health-equity, immigration)
- Registers `event_type` taxonomy (conference, forum, workshop, dinner, summit, meeting)
- Registers ACF field groups programmatically via `acf_add_local_field_group`:
  - **Hero Fields** (all pages/initiatives): `hero_eyebrow`, `hero_highlight_word`, `hero_subtitle`, `hero_background_image`
  - **Event Details** (events only): `event_date`, `event_time`, `event_location`, `event_registration_url`
- Seeds default taxonomy terms on theme activation
- Flushes rewrite rules on activation

### `inc/security.php`
- Sends hardening HTTP headers on every response:
  - `Strict-Transport-Security` (HSTS, 1 year, subdomains)
  - `Content-Security-Policy` (with `unsafe-eval` required by Elementor editor)
  - `X-Content-Type-Options: nosniff`
  - `X-Frame-Options: SAMEORIGIN`
  - `Referrer-Policy: strict-origin-when-cross-origin`
  - `Permissions-Policy` (camera, microphone, geolocation off)
- Disables XML-RPC (primary brute-force surface in WordPress)
- Strips `<meta name="generator">` tags (removes WordPress version fingerprinting)
- Disables in-admin file editing (`DISALLOW_FILE_EDIT`)
- Forces generic login error messages (prevents username enumeration)
- Removes RSD, WLW manifest, shortlink, feed links from `<head>`

### `inc/elementor-setup.php`
- Registers the `chf-widgets` widget category in the Elementor panel
- Defines 4 custom Dynamic Tags (each guarded with `class_exists()`):
  - `CHF_Hero_Eyebrow_Tag` → reads ACF `hero_eyebrow`
  - `CHF_Hero_Subtitle_Tag` → reads ACF `hero_subtitle`
  - `CHF_Event_Date_Tag` → reads ACF `event_date`, formats as "January 15, 2026"
  - `CHF_Event_Location_Tag` → reads ACF `event_location`
- Enables Theme Builder locations (`register_all_core_locations()`)
- Sets Elementor global defaults on theme activation (colors, fonts, container mode)
- Adds body classes for Elementor-managed pages

### `assets/css/design-system.css`
- CSS reset / normalization
- Typography scale using `clamp()` for fluid type
- Link styles, focus rings, button utilities (`.btn-primary`, `.btn-outline`)
- Section utilities (`.section-dark`, `.section-off`, `.section-gradient`)
- `.reveal` scroll animation class
- `prefers-reduced-motion` overrides
- `@media print` rules

### `assets/css/elementor-overrides.css`
- Widget-level styling for Elementor's native widgets to match CHF brand:
  - Heading, text-editor, button, nav menu, form, social icons, image, posts
- `.e-con` (container) max-width of 1200px
- Responsive breakpoints at 768px and 480px

### `assets/js/frontend.js`
- Vanilla ES2022+ IIFE, zero jQuery
- `IntersectionObserver`-based scroll reveal
- Counter animation for stat numbers (supports `.stat-number[data-target]`, `[data-count]`, `.counter[data-target]`)
- Smooth scroll with reduced-motion respect
- Back-to-top button with scroll-threshold visibility
- Header `.scrolled` class toggle past 60px scroll
- Elementor frontend lifecycle hooks for dynamically-loaded widgets

---

## 3. Data model

### Post types

| Type | Slug | Archive URL | Single URL | Count |
|---|---|---|---|---|
| Page | `page` | — | `/{slug}/` | 20 |
| Initiative | `chf_initiative` | `/initiatives/` | `/initiative/{slug}/` | 20 |
| Event | `chf_event` | `/events-archive/` | `/event/{slug}/` | 6 |
| Post (news) | `post` | `/news/` | `/news/{slug}/` | as added |

### Taxonomies

| Taxonomy | Applies to | Terms |
|---|---|---|
| `initiative_category` | chf_initiative | energy-climate, health-equity, immigration |
| `event_type` | chf_event | conference, forum, workshop, dinner, summit, meeting |
| `category` | post | (default WordPress categories) |

### ACF field groups

**Hero Fields** — attached to `page`, `chf_initiative`, `chf_event`, `post`
- `hero_eyebrow` (text) — small uppercase label above the hero headline
- `hero_highlight_word` (text) — word within the headline to gradient-color
- `hero_subtitle` (textarea) — one or two sentences under the headline
- `hero_background_image` (image) — optional background

**Event Details** — attached to `chf_event` only
- `event_date` (date picker, stored as `Ymd`)
- `event_time` (text, e.g., "6:00 PM – 9:00 PM CST")
- `event_location` (text)
- `event_registration_url` (url)

These are registered in PHP via `acf_add_local_field_group()` in
`inc/custom-post-types.php` — **not** in the ACF UI. This means they survive
plugin updates, site migrations, and database resets. Do not re-register them
in the ACF admin UI.

---

## 4. Elementor Theme Builder layout

Elementor Pro's Theme Builder controls the non-content chrome (header, footer,
single post templates, archives). All 10 templates are pre-built in
`elementor-templates/` as JSON.

| Template | File | Type | Displays for |
|---|---|---|---|
| CHF Global Header | `global-header.json` | header | Entire site |
| CHF Global Footer | `global-footer.json` | footer | Entire site |
| CHF Homepage | `template-homepage.json` | page | Homepage only |
| CHF Interior Page | `template-interior.json` | page | All other pages |
| CHF Initiative Single | `template-initiative-single.json` | single | Each initiative |
| CHF Event Single | `template-event-single.json` | single | Each event |
| CHF Events Archive | `template-events-archive.json` | archive | `/events-archive/` |
| CHF News Archive | `template-news-archive.json` | archive | `/news/` |
| CHF Hero Section | `section-hero.json` | section | Re-usable hero block |
| CHF Newsletter Section | `section-newsletter.json` | section | Re-usable CTA block |

See [ELEMENTOR-GUIDE.md](./ELEMENTOR-GUIDE.md) for import steps and conditions.

---

## 5. Enqueue order and dependencies

```
Priority   Handle                     Depends on
────────   ────────────────────────   ──────────────────
10         google-fonts               (none)
20         hello-elementor            (parent)
25         chf-child-theme            hello-elementor
30         chf-design-system          chf-child-theme
40         chf-elementor-overrides    chf-design-system
50         chf-frontend               (none, deferred)
```

JavaScript loads with `defer` so it never blocks first paint. CSS loads in
order so cascade wins in the expected direction:
parent → child → design system → Elementor overrides.

---

## 6. Caching strategy (runtime)

The theme itself does **no** server-side caching — that is the host's job.
Recommended setup:

1. **Page cache:** WP Super Cache OR hosting-provided page cache (Kinsta, WP Engine, SiteGround)
2. **Object cache:** Redis or Memcached (via host)
3. **Asset cache:** Browser cache headers set by host, plus `defer` on JS
4. **CDN:** Cloudflare (free tier works) for image + CSS edge delivery

**Do not install W3 Total Cache or WP Rocket without testing** — they
aggressively minify and can break Elementor's runtime-generated CSS for
containers and custom colors.

---

## 7. Security posture

| Layer | Mechanism |
|---|---|
| HTTPS | Enforced via HSTS header (1 year, includeSubDomains) |
| XSS | CSP + `X-Content-Type-Options: nosniff` |
| Clickjacking | `X-Frame-Options: SAMEORIGIN` |
| Brute force | XML-RPC disabled, Wordfence rate limiting, strong passwords required |
| Version disclosure | `<meta generator>` stripped, RSD/WLW removed |
| File integrity | `DISALLOW_FILE_EDIT` set, file perms 644/755 |
| Login | Generic error messages, 2FA recommended |
| Database | Strong prefix (not `wp_`), Wordfence scanning |
| Backups | UpdraftPlus or host-managed daily backups (see MAINTENANCE.md) |

---

## 8. What does NOT live in the theme

So there's no confusion:

- **Page content** — lives in `wp_posts` / `wp_postmeta`, edited in Elementor
- **Navigation menus** — live in `wp_term_taxonomy`, managed in Appearance → Menus
- **Site title / tagline** — managed in Settings → General
- **Widgets** — we don't use WordPress widgets; everything is Elementor
- **Redirects** — managed by Redirection plugin (install post-launch)
- **Forms** — Elementor Pro Form widget, submissions stored in Elementor → Submissions
- **Sitemap** — auto-generated by Yoast at `/sitemap_index.xml`
- **robots.txt** — served by Yoast; edit in Yoast → Tools → File Editor
