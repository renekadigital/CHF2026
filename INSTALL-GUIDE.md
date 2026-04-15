# Center for Houston's Future — Implementation Guide

**Theme:** Center for Houston's Future v5.0.0
**Architecture:** Hello Elementor child theme + Elementor Pro
**Author:** reneka DIGITAL
**Date:** April 2026

---

## Table of Contents

1. [Prerequisites](#1-prerequisites)
2. [WordPress Setup](#2-wordpress-setup)
3. [Theme Installation](#3-theme-installation)
4. [Plugin Installation](#4-plugin-installation)
5. [Content Import](#5-content-import)
6. [Navigation Setup](#6-navigation-setup)
7. [Elementor Global Settings](#7-elementor-global-settings)
8. [Elementor Template Import](#8-elementor-template-import)
9. [Theme Builder Configuration](#9-theme-builder-configuration)
10. [ACF Fields & Dynamic Tags](#10-acf-fields--dynamic-tags)
11. [Page-by-Page Content Entry](#11-page-by-page-content-entry)
12. [Design System Reference](#12-design-system-reference)
13. [JavaScript Features](#13-javascript-features)
14. [Security](#14-security)
15. [Performance](#15-performance)
16. [SEO](#16-seo)
17. [File Reference](#17-file-reference)
18. [Quality Assurance Checklist](#18-quality-assurance-checklist)
19. [URL Redirects](#19-url-redirects)
20. [Troubleshooting](#20-troubleshooting)

---

## 1. Prerequisites

### Server Requirements

| Requirement | Minimum | Recommended |
|---|---|---|
| WordPress | 6.4 | 6.7+ |
| PHP | 8.2 | 8.3 |
| MySQL / MariaDB | 5.7 / 10.4 | 8.0 / 10.11 |
| PHP Memory Limit | 256 MB | 512 MB |
| Max Upload Size | 32 MB | 64 MB |
| SSL Certificate | Required | Required |

### Software Requirements

| Software | Version | License |
|---|---|---|
| Hello Elementor (parent theme) | 3.0+ | Free |
| Elementor | 3.20+ | Free |
| Elementor Pro | 3.20+ | Commercial |
| Advanced Custom Fields PRO | 6.0+ | Commercial |

### Accounts Needed

- WordPress admin account with `manage_options` capability
- Elementor Pro license key
- ACF PRO license key

---

### Post-Import Slug Cleanup

The XML import file (`chf-content-import.xml`) uses short, clean slugs (e.g., `home`, `about`, `team`, `news`). After import, WordPress will create URLs based on these slugs.

If you want more descriptive URLs (e.g., `/about-us/` instead of `/about/`), you can edit slugs post-import:

1. Go to **Pages → All Pages** (or **Initiatives → All Initiatives**, **Events → All Events**)
2. Click **Quick Edit** on any item
3. Change the **Slug** field to your preferred value
4. Click **Update**

**Recommended slug changes for readability:**

| Import Slug | Suggested Clean Slug | URL Result |
|---|---|---|
| `home` | `home` (or leave as-is; front page URL is `/`) | `/` |
| `team` | `our-team` | `/our-team/` |
| `supporters` | `our-supporters` | `/our-supporters/` |
| `financials` | `annual-reports` | `/annual-reports/` |
| `news` | `news-media` | `/news-media/` |
| `leadership` | `leadership-forum` | `/leadership-forum/` |
| `portal` | `forum-portal` | `/forum-portal/` |
| `top-25` | `top-25-alumni` | `/top-25-alumni/` |
| `alumni` | `alumni-directory` | `/alumni-directory/` |
| `initiatives` | `strategic-initiatives` | `/strategic-initiatives/` |
| `privacy` | `privacy-policy` | `/privacy-policy/` |
| `driving-future` | `driving-the-future` | `/driving-the-future/` |
| `energy-webcasts` | `energy-webcast-series` | `/energy-webcast-series/` |

> **Important:** If you change slugs after the site is live, set up 301 redirects from the old slug to the new one (see Section 19). Menu links and internal references will need updating as well.

---

## 2. WordPress Setup

### 2A. Fresh Install or Existing Site

**Fresh install (recommended):** Install WordPress via your host's control panel, Softaculous, or manually. Use the latest stable release.

**Existing site:** Back up the entire site (database + files) before proceeding. Export current content via **Tools → Export** as a safety net.

### 2B. Configure Core Settings

After WordPress is installed, configure these settings:

**Settings → General:**

| Setting | Value |
|---|---|
| Site Title | `Center for Houston's Future` |
| Tagline | `Houston's premier cross-sector think tank driving research, civic leadership, and strategic initiatives for the region's future.` |
| Timezone | `America/Chicago` |

**Settings → Permalinks:**

| Setting | Value |
|---|---|
| Permalink Structure | Post name (`/%postname%/`) |

Click **Save Changes** after setting permalinks. This flushes rewrite rules.

**Settings → Reading** (configure after content import in Step 5):

| Setting | Value |
|---|---|
| Your homepage displays | A static page |
| Homepage | `Future Houston` |
| Posts page | `News & Media` |

**Settings → Discussion:**

| Setting | Value |
|---|---|
| Allow people to submit comments on new posts | Unchecked (recommended) |

---

## 3. Theme Installation

The CHF theme is a **child theme** of Hello Elementor. Install the parent first.

### Step 1 — Install Hello Elementor

1. Go to **Appearance → Themes → Add New**
2. Search for `Hello Elementor`
3. Click **Install**, then **Activate**

### Step 2 — Install the CHF Child Theme

**Option A — ZIP upload:**

1. Go to **Appearance → Themes → Add New → Upload Theme**
2. Select `chf-theme.zip`
3. Click **Install Now**, then **Activate**

**Option B — FTP / File Manager:**

1. Upload the `chf-theme/` folder to `/wp-content/themes/`
2. Go to **Appearance → Themes**
3. Find **Center for Houston's Future** and click **Activate**

### Step 3 — Verify

Go to **Appearance → Themes**. Confirm:

- **Active:** Center for Houston's Future
- **Parent:** Hello Elementor

### What Fires on Activation

When the child theme activates, these run automatically via `after_switch_theme`:

| Action | Effect |
|---|---|
| `chf_flush_rewrite_rules()` | Registers CPTs + taxonomies and flushes permalink rules |
| `chf_insert_default_terms()` | Inserts 3 initiative categories + 6 event types |
| `chf_set_elementor_defaults()` | Sets Elementor color palette, fonts, and container mode |

---

## 4. Plugin Installation

### Required Plugins

| # | Plugin | Install Method | Purpose |
|---|---|---|---|
| 1 | **Elementor** | WP Plugin Repository | Core page builder engine |
| 2 | **Elementor Pro** | Upload ZIP from elementor.com | Theme Builder, Dynamic Tags, Forms |
| 3 | **ACF PRO** | Upload ZIP from advancedcustomfields.com | Hero fields, Event details |

### Installation Steps

1. **Plugins → Add New** → Search `Elementor` → **Install** → **Activate**
2. **Plugins → Add New → Upload Plugin** → Upload Elementor Pro ZIP → **Install** → **Activate**
3. Go to **Elementor → License** → Enter your license key → **Activate**
4. **Plugins → Add New → Upload Plugin** → Upload ACF PRO ZIP → **Install** → **Activate**
5. Go to **Custom Fields → Updates** → Enter your license key → **Activate**

### Recommended Plugins

| Plugin | Purpose |
|---|---|
| Yoast SEO | Meta tags, XML sitemaps, breadcrumbs, schema markup |
| WP Fastest Cache *or* LiteSpeed Cache | Page caching, minification, Gzip/Brotli |
| Wordfence Security | Firewall, brute-force protection, malware scanning |
| Redirection | 301 redirect management from old URLs |
| WP Mail SMTP | Reliable email delivery for Elementor form submissions |

---

## 5. Content Import

The theme ships with one import file that creates all content:

| File | Creates |
|---|---|
| `chf-content-import.xml` | 20 pages, 20 initiatives, 6 events, 32 nav menu items, taxonomy terms |

> **Note:** ACF field groups are registered automatically via PHP in `inc/custom-post-types.php`. No separate ACF import is needed.

### Import Steps

1. Go to **Tools → Import**
2. Under **WordPress**, click **Install Now** (if not already installed), then **Run Importer**
3. Click **Choose File** → Select `chf-content-import.xml` from the theme folder
4. Click **Upload file and import**
5. On the author mapping screen:
   - Map `admin` to your existing WordPress admin user
   - Check **Download and import file attachments**
6. Click **Submit**

### Verify the Import

| Content Type | Expected Count | Where to Check |
|---|---|---|
| Pages | 20 | Pages → All Pages |
| Initiatives | 20 | Initiatives → All Initiatives |
| Events | 6 | Events → All Events |
| Initiative Categories | 3 | Initiatives → Categories |
| Event Types | 6 | Events → Event Types |
| Nav Menu Items | 32 | Appearance → Menus |

All 46 content items are pre-tagged with `_elementor_edit_mode = builder` and `_wp_page_template = elementor_header_footer`, making them Elementor-ready on import.

### Pages Created

| Page | Slug | Parent |
|---|---|---|
| Future Houston | `home` | — (Homepage) |
| About the Center | `about` | — |
| Our Team | `team` | About |
| Our Supporters | `supporters` | — |
| Annual Reports & Financials | `financials` | — |
| Events | `events` | — |
| News & Media | `news` | — |
| Donate | `donate` | — |
| Contact Us | `contact` | — |
| Business & Civic Leadership Forum | `leadership` | — |
| Forum Portal | `portal` | Leadership |
| Top 25 Alumni | `top-25` | Leadership |
| 75 Leaders Who Stand Apart | `75-leaders` | Leadership |
| Alumni Directory | `alumni` | Leadership |
| Strategic Initiatives | `initiatives` | — |
| Privacy Policy | `privacy` | — |
| Driving the Future | `driving-future` | — |
| Kid Charged WonderWeek | `wonderweek` | — |
| Signature Conferences | `signature-conferences` | — |
| Energy Webcast Series | `energy-webcasts` | — |

### Initiatives Created

| Initiative | Slug | Category |
|---|---|---|
| Energy and Climate | `energy` | Energy & Climate |
| Health & Health Equity | `health` | Health & Health Equity |
| Immigration | `immigration` | Immigration |
| Climate Change & Health Care | `climate-health` | Health & Health Equity |
| Health Care 2020 | `healthcare-2020` | Health & Health Equity |
| Health Care 2022 | `healthcare-2022` | Health & Health Equity |
| Maternal Health | `maternal-health` | Health & Health Equity |
| AI in Healthcare | `ai-healthcare` | Health & Health Equity |
| Clean Hydrogen Hub | `hydrogen-hub` | Energy & Climate |
| Hydrogen Manufacturing | `hydrogen-manufacturing` | Energy & Climate |
| HyVelocity Hub | `hyvelocity` | Energy & Climate |
| Immigration Report 2021 | `immigration-report` | Immigration |
| 2019 Immigration Study | `immigration-2019` | Immigration |
| Difficult Conversations on Immigration | `immigration-conversations` | Immigration |
| COVID-19 Essential Workers & Immigration | `immigration-covid` | Immigration |
| Immigration & Construction Industry | `immigration-construction` | Immigration |
| Immigration Partners & Projects | `immigration-projects` | Immigration |
| Immigration Resources | `immigration-resources` | Immigration |
| Project Metis | `project-metis` | Health & Health Equity |
| Vision 2050 | `vision-2050` | Energy & Climate |

### Events Created

| Event | Slug | Type |
|---|---|---|
| CHF Spring Policy Forum | `spring-forum` | Forum |
| Project Metis: Brain Health Summit | `brain-health-summit` | Summit |
| Houston Hydrogen Economy Workshop | `hydrogen-workshop` | Workshop |
| 2026 Annual Meeting | `annual-meeting-2026` | Meeting |
| 2025 Dinner and Conversation | `dinner-2025` | Dinner |
| Energy Independence Summit | `energy-summit` | Summit |

### Post-Import: Set Static Front Page

1. Go to **Settings → Reading**
2. Select **A static page**
3. Homepage: **Future Houston**
4. Posts page: **News & Media**
5. Click **Save Changes**

---

## 6. Navigation Setup

The import creates all 5 menus automatically. Assign them to theme locations.

### Menu Locations

| Menu Name | Theme Location | Content |
|---|---|---|
| Primary Navigation | `primary` | About (dropdown), Initiatives (dropdown), Leadership (dropdown), Events, News & Media, Donate |
| Footer - About | `footer-about` | About the Center, Our Team, Our Supporters, Financials |
| Footer - Initiatives | `footer-initiatives` | Energy & Climate, Health & Health Equity, Immigration, Project Metis, Vision 2050 |
| Footer - Leadership | `footer-leadership` | Leadership Forum, Forum Portal, Alumni Directory |
| Footer - Connect | `footer-connect` | Events, News & Media, Donate |

### Assign Menus

**Quick method:** Go to **Appearance → Menus → Manage Locations** tab. Assign all 5 in one screen.

**Manual method:**

1. Go to **Appearance → Menus**
2. Select a menu from the dropdown → **Select**
3. Under **Menu Settings** at the bottom, check the correct **Display location**
4. Click **Save Menu**
5. Repeat for all 5 menus

### Primary Navigation Hierarchy

```
About
  ├── About the Center
  ├── Our Supporters
  └── Financials
Initiatives
  ├── Project Metis
  ├── Vision 2050
  └── Past Initiatives
Leadership
  ├── Business & Civic Leadership Forum
  ├── Forum Portal
  ├── Top 25 Alumni
  ├── 75 Leaders Who Stand Apart
  └── Alumni Directory
Events
News & Media
Donate
```

---

## 7. Elementor Global Settings

### 7A. Disable Default Schemes

1. Go to **Elementor → Settings → General**
2. Check both:
   - ☑ **Disable Default Colors**
   - ☑ **Disable Default Fonts**
3. Click **Save Changes**

> These are set automatically on theme activation, but verify them.

### 7B. Enable Container Layout

1. Go to **Elementor → Settings → Features** (or **Experiments**)
2. Set **Flexbox Container** to **Active**
3. Click **Save Changes**

> All 10 included templates use container-based layout. Legacy section/column mode will not work.

### 7C. Global Colors

Open any page in Elementor → click the hamburger menu (☰) → **Site Settings → Global Colors**.

Verify or add these colors:

| Name | Hex | Usage |
|---|---|---|
| CHF Navy | `#1B2A4A` | Text, headings, header background |
| CHF Navy Deep | `#0f1a2e` | Hero sections, dark backgrounds |
| CHF Green | `#56B84A` | Primary accent — buttons, links, CTAs |
| CHF Green Dark | `#3d8f35` | Button hover state |
| CHF Gold | `#C9A84C` | Footer headings, badges, accents |
| CHF White | `#ffffff` | Backgrounds, text on dark |
| CHF Off White | `#f7f6f4` | Alternating section backgrounds |
| CHF Rule | `#e5e5e5` | Borders, dividers |

### 7D. Global Fonts

In **Site Settings → Global Fonts**:

| Slot | Family | Weight | Usage |
|---|---|---|---|
| Primary | Merriweather | 700 | H1–H3 headings |
| Secondary | Inter | 400 | Body text |
| Text | Inter | 400 | Paragraphs |
| Accent | Inter | 600 | Buttons, labels, navigation |

> The theme enqueues both fonts from Google Fonts via `functions.php`. Elementor does not need to load them separately.

### 7E. Default Button Style

In **Site Settings → Buttons**:

| Setting | Value |
|---|---|
| Font Family | Inter |
| Font Size | 13px |
| Font Weight | 600 |
| Text Transform | Uppercase |
| Letter Spacing | 0.08em |
| Background | `#56B84A` |
| Text Color | `#ffffff` |
| Border Radius | 28px |
| Padding | 12px 28px |
| Hover Background | `#3d8f35` |
| Hover Box Shadow | `0 8px 32px rgba(86,184,74,0.25)` |

### 7F. Layout Defaults

In **Site Settings → Layout**:

| Setting | Value |
|---|---|
| Content Width | 1200px |
| Widgets Space | 0px |

---

## 8. Elementor Template Import

The theme includes 10 pre-built Elementor templates in `/elementor-templates/`.

### Template Inventory

| File | Type | Purpose |
|---|---|---|
| `global-header.json` | Header | Sticky nav with logo, menu, donate button |
| `global-footer.json` | Footer | 5-column footer with socials, gold headings |
| `section-hero.json` | Saved Section | Reusable interior page hero |
| `section-newsletter.json` | Saved Section | Reusable newsletter signup form |
| `template-homepage.json` | Page | Full homepage layout |
| `template-interior.json` | Page | Standard interior page layout |
| `template-initiative-single.json` | Single | Initiative detail with sidebar |
| `template-event-single.json` | Single | Event detail with info bar + register CTA |
| `template-events-archive.json` | Archive | Events listing with filter tabs |
| `template-news-archive.json` | Archive | News grid with featured post |

### Import Steps

1. Go to **Templates → Saved Templates**
2. Click **Import Templates** at the top
3. Select a `.json` file → **Import Now**
4. Repeat for all 10 files

### Recommended Import Order

Import in this sequence so dependencies resolve correctly:

```
1. section-hero.json
2. section-newsletter.json
3. global-header.json
4. global-footer.json
5. template-homepage.json
6. template-interior.json
7. template-initiative-single.json
8. template-event-single.json
9. template-events-archive.json
10. template-news-archive.json
```

---

## 9. Theme Builder Configuration

After importing templates, assign each one to the correct display conditions in Elementor Pro's Theme Builder.

Go to **Templates → Theme Builder** (or **Elementor → Theme Builder**).

### Header

1. Click the **Header** tab → **Add New** (or select the imported header)
2. If adding new: insert the imported `CHF Global Header` template
3. Click **Publish**
4. Display Condition: **Include → Entire Site**
5. **Save & Close**

### Footer

1. Click the **Footer** tab → **Add New**
2. Insert the imported `CHF Global Footer` template
3. Click **Publish**
4. Display Condition: **Include → Entire Site**
5. **Save & Close**

### Single Page

1. Click **Single** → **Add New** → Template Type: **Single Page**
2. Insert the imported `CHF Interior Page` template
3. Click **Publish**
4. Display Conditions:
   - **Include → Pages → All Pages**
   - **Exclude → Front Page**
5. **Save & Close**

### Single Initiative

1. **Single** → **Add New** → Template Type: **Single Post**
2. Insert `CHF Initiative Single`
3. Click **Publish**
4. Display Condition: **Include → Initiatives → All Initiatives**
5. **Save & Close**

### Single Event

1. **Single** → **Add New** → Template Type: **Single Post**
2. Insert `CHF Event Single`
3. Click **Publish**
4. Display Condition: **Include → Events → All Events**
5. **Save & Close**

### News Archive

1. Click **Archive** → **Add New**
2. Insert `CHF News Archive`
3. Click **Publish**
4. Display Condition: **Include → Posts Archive**
5. **Save & Close**

### Events Archive

1. **Archive** → **Add New**
2. Insert `CHF Events Archive`
3. Click **Publish**
4. Display Condition: **Include → Events Archive**
5. **Save & Close**

### Homepage

The homepage is edited directly as a page (not via Theme Builder):

1. Go to **Pages → All Pages → Future Houston → Edit with Elementor**
2. Insert the `CHF Homepage` template from the template library
3. Customize content as needed
4. Click **Publish**

### Template Priority

When multiple templates match, Elementor uses specificity (most specific wins):

```
1. Individual page (edited directly in Elementor)     ← highest
2. Single template with specific CPT condition
3. Single template with generic "All Pages" condition
4. Hello Elementor parent theme fallback               ← lowest
```

---

## 10. ACF Fields & Dynamic Tags

### Field Groups

ACF field groups are registered automatically via PHP in `inc/custom-post-types.php`. They appear in the editor as soon as the theme and ACF are both active.

#### Hero Fields

**Appears on:** Pages, Initiatives, Events
**Position:** Below title

| Field | Machine Name | Type | Notes |
|---|---|---|---|
| Hero Eyebrow | `hero_eyebrow` | Text | Uppercase label above h1 (e.g., `ABOUT`, `INITIATIVE`) |
| Hero Highlight Word | `hero_highlight_word` | Text | Word in h1 that gets green gradient treatment |
| Hero Subtitle | `hero_subtitle` | Textarea | Supporting sentence below the heading |
| Hero Background Image | `hero_background_image` | Image (URL) | Recommended 1920×1080 |

#### Event Details

**Appears on:** Events only
**Position:** Normal (below content editor)

| Field | Machine Name | Type | Notes |
|---|---|---|---|
| Event Date | `event_date` | Date Picker | Internal format: `Ymd`, Display: `F j, Y` |
| Event Time | `event_time` | Text | e.g., `6:00 PM – 9:00 PM CST` |
| Event Location | `event_location` | Text | e.g., `Partnership Tower, Houston, TX` |
| Registration URL | `event_registration_url` | URL | Link to registration page |

### Dynamic Tags

The theme registers 4 custom Elementor Dynamic Tags in `inc/elementor-setup.php`. These allow Elementor widgets to pull ACF data automatically.

| Dynamic Tag | Group | Reads From | Widget Use |
|---|---|---|---|
| Hero Eyebrow | CHF Fields | `hero_eyebrow` | Text, Heading |
| Hero Subtitle | CHF Fields | `hero_subtitle` | Text, Heading |
| Event Date | CHF Fields | `event_date` | Text (formatted as `F j, Y`) |
| Event Location | CHF Fields | `event_location` | Text |

#### How to Use Dynamic Tags

1. Edit any widget in the Elementor editor
2. Click the **Dynamic Tags** icon (🏷 stacked papers) next to any text field
3. Under the **CHF Fields** group, select the desired tag
4. The widget now pulls its content from the current post/page's ACF field

> **Important:** Dynamic Tags require Elementor Pro + ACF PRO. Both must be active.

---

## 11. Page-by-Page Content Entry

After templates are applied, open each page/post and fill in ACF hero fields and body content.

### Pages (20)

| Page | Eyebrow | Highlight Word | Subtitle | Template |
|---|---|---|---|---|
| Future Houston | Energy & Climate | WonderWeek | Exploring how innovation goes hand in hand with play, curiosity and experimentation | Homepage |
| About the Center | Health & Health Equity | 2022 | COVID-19 Highlights Challenges for and Advances by Greater Houston's Health Care Sector | Interior |
| Our Team | Our People | Board | The team behind Center for Houston's Future — driving research, leadership, and action across the region. | Interior |
| Our Supporters | Our Supporters | future | Center for Houston's Future is supported by leading corporations, foundations, and civic organizations committed to the long-term success of Greater Houston. | Interior |
| Annual Reports & Financials | Financials | Financials | Center for Houston's Future is a nonprofit 501(c)3 organization. Donations are tax deductible to the extent allowed by law. | Interior |
| Events | Events | Events | Join us for conferences, forums, and conversations shaping Houston's future. | Interior |
| News & Media | NEWS & MEDIA | News | Stay informed on CHF's latest research, initiatives, and media coverage. | Interior |
| Donate | Support Our Mission | Difference | Houston Is the Future. | Interior |
| Contact Us | GET IN TOUCH | Us | We'd love to hear from you. | Interior |
| Business & Civic Leadership Forum | Leadership | Leadership | We inspire leaders to identify matters of the highest importance to the long-term future of the greater Houston region. | Interior |
| Forum Portal | Leadership | Portal | Access resources, session materials, and connect with fellow forum participants. | Interior |
| Top 25 Alumni | Leadership | 25 | Celebrating outstanding alumni who exemplify civic leadership and community impact. | Interior |
| 75 Leaders Who Stand Apart | Leadership | Stand Apart | A special recognition celebrating Houston's most impactful civic leaders. | Interior |
| Alumni Directory | Leadership | Directory | Connect with more than 1,400 Business/Civic Leadership Forum alumni. | Interior |
| Strategic Initiatives | Strategic Initiatives | action | We bring business, government, community and academic stakeholders to engage in planning, research, consensus building and action. | Interior |
| Privacy Policy | Legal | Policy | Center for Houston's Future Website Privacy Policy | Interior |
| Driving the Future | Energy & Climate | Future | After-school enrichment program about low-carbon energy | Interior |
| Kid Charged WonderWeek | Energy & Climate | WonderWeek | Exploring how innovation goes hand in hand with play, curiosity and experimentation | Interior |
| Signature Conferences | Energy & Climate | Conferences | The Center began hosting conferences on low-carbon energy in 2019 | Interior |
| Energy Webcast Series | Energy & Climate | Webcasts | Featuring industry leaders, academic experts, journalists and activists on climate and energy transition | Interior |

### Initiatives (20)

For each initiative, fill in:

1. **Hero Eyebrow** — e.g., `INITIATIVE` or the category name
2. **Hero Highlight Word** — one word from the title
3. **Hero Subtitle** — one-sentence description
4. **Featured Image** — representative photo (1920×1080 recommended)
5. **Body Content** — via Elementor or WordPress editor

### Events (6)

For each event, fill in:

1. **Hero Eyebrow** — e.g., `UPCOMING EVENT` or `PAST EVENT`
2. **Hero Subtitle** — brief description
3. **Event Date** — use the date picker
4. **Event Time** — e.g., `6:00 PM – 9:00 PM CST`
5. **Event Location** — e.g., `Partnership Tower, Houston, TX`
6. **Registration URL** — link to registration (leave blank for past events)
7. **Featured Image** — event photo

---

## 12. Design System Reference

All design tokens are defined as CSS custom properties in `style.css` `:root` and cascade to every page.

### Color Tokens

| Token | Value | Usage |
|---|---|---|
| `--navy` | `#1B2A4A` | Primary text, headings, header background |
| `--navy-deep` | `#0f1a2e` | Hero overlays, deep dark sections |
| `--green` | `#56B84A` | Buttons, links, CTAs, hover accents |
| `--green-dark` | `#3d8f35` | Button hover state |
| `--green-glow` | `0 8px 32px rgba(86,184,74,0.25)` | Button hover shadow |
| `--gold` | `#C9A84C` | Footer headings, badges, awards |
| `--white` | `#ffffff` | Backgrounds, text on dark sections |
| `--off` | `#f7f6f4` | Alternating section backgrounds |
| `--rule` | `#e5e5e5` | Borders, dividers, card outlines |

### Typography Tokens

| Token | Value | Usage |
|---|---|---|
| `--font-sans` | `'Inter', system-ui, -apple-system, sans-serif` | Body, buttons, labels, nav |
| `--font-serif` | `'Merriweather', Georgia, serif` | H1–H3 headings |

### Heading Scale

| Element | Size | Family |
|---|---|---|
| H1 | `clamp(32px, 5vw, 56px)` | Merriweather 700 |
| H2 | `clamp(26px, 4vw, 42px)` | Merriweather 700 |
| H3 | `clamp(22px, 3vw, 32px)` | Merriweather 700 |
| H4 | `clamp(18px, 2.5vw, 24px)` | Inter 600 (sans) |
| H5 | `18px` | Inter 600 (sans) |
| H6 | `16px` | Inter 600 (sans) |
| Body | `16px / 1.6` | Inter 400 |

### Spacing & Layout Tokens

| Token | Value | Usage |
|---|---|---|
| `--nav-height` | `72px` | Fixed header height, hero top offset |
| `--container-pad` | `clamp(20px, 5vw, 48px)` | Responsive side padding |
| `--radius-pill` | `28px` | Buttons, badges, pill shapes |
| `--radius-md` | `12px` | Cards, images, dropdowns |

### Effect Tokens

| Token | Value | Usage |
|---|---|---|
| `--focus-ring` | `0 0 0 3px rgba(86,184,74,0.5)` | Accessibility focus indicator |
| `--shadow` | `0 4px 24px rgba(0,0,0,0.06)` | Card hover elevation |
| `--ease-spring` | `linear(0, 0.006, ...)` | Spring physics animation curve |
| `--ease-out-expo` | `cubic-bezier(0.16, 1, 0.3, 1)` | Reveal/entrance animations |
| `--ease-out-quart` | `cubic-bezier(0.25, 1, 0.5, 1)` | Interaction transitions |

### CSS Utility Classes

Apply via Elementor's **Advanced → CSS Classes** field on any container or widget:

| Class | Effect |
|---|---|
| `.grad-text` | Green gradient text (for hero highlight words) |
| `.btn-primary` | Green pill button with hover glow |
| `.btn-outline` | Outlined pill button |
| `.btn-text` | Text-only link with arrow on hover |
| `.card-hover` | Lift + shadow on hover |
| `.section-dark` | Navy deep background, white text cascade |
| `.section-off` | Off-white background |
| `.section-gradient` | Navy gradient background with white text |
| `.section-eyebrow` | Green pill badge (uppercase, 11px) |
| `.reveal` | Scroll-triggered fade-up animation |
| `.skip-link` | Accessible skip-to-content link |

### Dark Section Behavior

When `.section-dark` or `.section-gradient` is applied to a container:

- Headings flip to white
- Links default to green
- Outline buttons flip to white border/text
- Dividers become `rgba(255,255,255,0.12)`
- Text editor content becomes `rgba(255,255,255,0.8)`

---

## 13. JavaScript Features

`assets/js/frontend.js` — Vanilla ES2022+, zero jQuery dependency, 426 lines.

| Feature | Behavior |
|---|---|
| **Scroll Reveal** | Elements with `.reveal` class fade up when they enter the viewport (IntersectionObserver, threshold 0.15) |
| **Header Scroll** | Adds `.scrolled` class to header after 60px scroll (for background opacity change). Uses `requestAnimationFrame` + passive listener |
| **Back to Top** | Button appears after 400px scroll, smooth-scrolls to top on click |
| **Counter Animation** | `.stat-number[data-target]` elements animate from 0 to target using easeOutExpo curve. Supports `data-suffix` (K, M, +, %) |
| **Smooth Scroll** | Anchor links (`a[href^="#"]`) scroll smoothly with focus management |
| **Viewport Height** | Sets `--vh` custom property for consistent mobile viewport units |
| **Elementor Hooks** | Re-initializes observers on `elementor/frontend/init`, widget ready, and popup events |
| **Reduced Motion** | All animations are disabled when `prefers-reduced-motion: reduce` is active |

---

## 14. Security

All security features are in `inc/security.php` and are active automatically.

### HTTP Security Headers

Sent on every response via the `send_headers` action:

| Header | Value |
|---|---|
| Content-Security-Policy | `default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src https://fonts.gstatic.com; img-src 'self' data: https:; frame-src https://www.google.com;` |
| X-Content-Type-Options | `nosniff` |
| X-Frame-Options | `SAMEORIGIN` |
| Referrer-Policy | `strict-origin-when-cross-origin` |
| Permissions-Policy | `camera=(), microphone=(), geolocation=()` |
| Strict-Transport-Security | `max-age=31536000; includeSubDomains` |

> **CSP Note:** `'unsafe-eval'` is required by Elementor's frontend JavaScript. If you add third-party scripts (analytics, chat, embeds), add their domains to the appropriate CSP directive in `inc/security.php`.

### Additional Hardening

| Feature | Function | Effect |
|---|---|---|
| XML-RPC Disabled | `chf_disable_xmlrpc()` | Blocks XML-RPC entirely |
| WP Version Stripped | `chf_remove_wp_version()` | Removes version from `<head>` and RSS |
| File Editor Disabled | `chf_disable_file_editing()` | Defines `DISALLOW_FILE_EDIT` |
| Generic Login Errors | `chf_login_error_message()` | Prevents username enumeration |
| Head Cleanup | `chf_clean_wp_head()` | Removes RSD, WLW, shortlink, REST API, oEmbed links |
| Emoji Removal | `chf_disable_emojis()` | Strips emoji detection scripts/styles |

---

## 15. Performance

### Built-in Optimizations

| Optimization | Implementation |
|---|---|
| Google Fonts preconnect | Resource hints for `fonts.googleapis.com` and `fonts.gstatic.com` |
| Deferred JavaScript | `frontend.js` loaded with `strategy: 'defer'` in footer |
| No jQuery | All JS is vanilla ES2022+ |
| IntersectionObserver | Scroll animations use observers instead of scroll listeners |
| Passive Listeners | All scroll/resize handlers use `{ passive: true }` |
| Reduced Motion | CSS and JS both respect `prefers-reduced-motion: reduce` |

### Recommended Server-Side Setup

1. **Caching Plugin** — Enable page caching, Gzip/Brotli compression, browser cache headers
2. **Elementor Performance** — Go to **Elementor → Settings → Performance**:
   - Improved Asset Loading: **Active**
   - CSS Print Method: **Internal Embedding**
   - Google Fonts Loading: **Swap**
3. **Images** — Use WebP format. Consider ShortPixel or Imagify for compression. Theme image sizes: `news-card` (600×338), `hero-bg` (1920×1080), `supporter-logo` (240×80)
4. **CDN** — Cloudflare or CloudFront for static assets if serving global audience

---

## 16. SEO

### Built-in Meta Description

The theme outputs `<meta name="description">` via `chf_meta_description()` in `functions.php`:

1. If Yoast SEO is active and the page is not the front page → Yoast handles it
2. Singular pages → Yoast `_yoast_wpseo_metadesc` field → excerpt fallback
3. Front page → Site tagline from **Settings → General**
4. Truncated to 160 characters

### Yoast SEO Setup (Recommended)

1. Run the **Configuration Wizard** at **SEO → General → First-time configuration**
2. Set **Organization** schema: Name = `Center for Houston's Future`, upload logo
3. **Content Types:** Show pages, initiatives, and events in search results
4. **Taxonomies:** Show initiative categories and event types in search results
5. Enable **XML Sitemaps** at **SEO → General → Features**
6. Submit `https://centerforhoustonsfuture.org/sitemap_index.xml` to Google Search Console

---

## 17. File Reference

### Theme Directory Structure

```
chf-theme/
├── style.css                              # Child theme header + CSS design tokens
├── functions.php                          # Setup, asset loading, includes
│
├── inc/
│   ├── custom-post-types.php              # CPTs, taxonomies, ACF field groups
│   ├── security.php                       # HTTP headers, login hardening
│   └── elementor-setup.php                # Widget category, Dynamic Tags, defaults
│
├── assets/
│   ├── css/
│   │   ├── design-system.css              # Reset, typography, buttons, utilities
│   │   └── elementor-overrides.css        # Elementor widget brand overrides
│   ├── js/
│   │   └── frontend.js                    # Scroll reveal, counters, header scroll
│   └── images/
│       ├── hero-skyline.jpg               # Default hero background (3 MB)
│       ├── logo.png                       # CHF logo PNG
│       └── logo.svg                       # CHF logo SVG
│
├── elementor-templates/
│   ├── global-header.json                 # Header — Theme Builder
│   ├── global-footer.json                 # Footer — Theme Builder
│   ├── section-hero.json                  # Interior hero — Saved Section
│   ├── section-newsletter.json            # Newsletter form — Saved Section
│   ├── template-homepage.json             # Homepage — Page
│   ├── template-interior.json             # Interior page — Page
│   ├── template-initiative-single.json    # Initiative — Single
│   ├── template-event-single.json         # Event — Single
│   ├── template-events-archive.json       # Events listing — Archive
│   └── template-news-archive.json         # News grid — Archive
│
├── chf-content-import.xml                 # WXR 1.2 content import (78 items)
├── INSTALL-GUIDE.md                       # This file
│
└── _archive_v4/                           # Archived v4 theme files (safe to delete)
```

### Stylesheet Load Order

```
1. Google Fonts (Inter + Merriweather)           ← external
2. hello-elementor (parent theme style.css)      ← parent
3. chf-style (child theme style.css)             ← depends on 2
4. chf-design-system (design-system.css)         ← depends on 3
5. chf-elementor-overrides (elementor-overrides.css) ← depends on 4
```

### Hooks & Functions Reference

| Hook | Function | File |
|---|---|---|
| `after_setup_theme` | `chf_setup()` | functions.php |
| `wp_enqueue_scripts` | `chf_enqueue_assets()` | functions.php |
| `wp_resource_hints` | `chf_resource_hints()` | functions.php |
| `init` | `chf_disable_emojis()` | functions.php |
| `wp_head` | `chf_meta_description()` | functions.php |
| `admin_notices` | `chf_elementor_required_notice()` | functions.php |
| `init` | `chf_register_post_types()` | inc/custom-post-types.php |
| `init` | `chf_register_taxonomies()` | inc/custom-post-types.php |
| `after_switch_theme` | `chf_insert_default_terms()` | inc/custom-post-types.php |
| `after_switch_theme` | `chf_flush_rewrite_rules()` | inc/custom-post-types.php |
| `acf/init` | `chf_register_acf_fields()` | inc/custom-post-types.php |
| `elementor/elements/categories_registered` | `chf_register_elementor_widget_category()` | inc/elementor-setup.php |
| `elementor/dynamic_tags/register` | `chf_register_dynamic_tags()` | inc/elementor-setup.php |
| `after_switch_theme` | `chf_set_elementor_defaults()` | inc/elementor-setup.php |
| `elementor/theme/register_locations` | `chf_register_elementor_locations()` | inc/elementor-setup.php |
| `body_class` | `chf_elementor_body_classes()` | inc/elementor-setup.php |
| `send_headers` | `chf_security_headers()` | inc/security.php |
| `xmlrpc_enabled` | `chf_disable_xmlrpc()` | inc/security.php |
| `the_generator` | `chf_remove_wp_version()` | inc/security.php |
| `init` | `chf_disable_file_editing()` | inc/security.php |
| `login_errors` | `chf_login_error_message()` | inc/security.php |
| `after_setup_theme` | `chf_clean_wp_head()` | inc/security.php |

### Custom Post Types

| Post Type | Slug | Archive URL | Single URL |
|---|---|---|---|
| Initiative | `chf_initiative` | `/initiatives/` | `/initiative/{slug}/` |
| Event | `chf_event` | `/events-archive/` | `/event/{slug}/` |

### Taxonomies

| Taxonomy | Slug | Post Type | URL |
|---|---|---|---|
| Initiative Category | `initiative_category` | `chf_initiative` | `/initiative-category/{slug}/` |
| Event Type | `event_type` | `chf_event` | `/event-type/{slug}/` |

---

## 18. Quality Assurance Checklist

### Pre-Launch

- [ ] **Theme:** Child theme active, Hello Elementor is parent
- [ ] **Plugins:** Elementor, Elementor Pro, ACF PRO all active with valid licenses
- [ ] **Content:** 20 pages, 20 initiatives, 6 events imported and verified
- [ ] **Menus:** All 5 menus assigned to correct locations
- [ ] **Reading:** Static front page set to Future Houston
- [ ] **Permalinks:** Post name structure, saved/flushed

### Elementor

- [ ] Default colors and fonts disabled in Elementor settings
- [ ] Container layout mode active
- [ ] Global colors match Section 7C (8 colors)
- [ ] Global fonts match Section 7D (Merriweather + Inter)
- [ ] All 10 templates imported
- [ ] Header assigned: Entire Site condition
- [ ] Footer assigned: Entire Site condition
- [ ] Single Page assigned: All Pages, exclude Front Page
- [ ] Single Initiative assigned: All Initiatives
- [ ] Single Event assigned: All Events
- [ ] News Archive assigned: Posts Archive
- [ ] Events Archive assigned: Events Archive
- [ ] Homepage edited with Elementor and published

### Content

- [ ] Hero eyebrow, highlight word, and subtitle filled for all 20 pages
- [ ] Event details (date, time, location, registration URL) filled for all 6 events
- [ ] Initiative details filled for all 20 initiatives
- [ ] Featured images uploaded for key pages
- [ ] Body content entered for all pages

### Visual QA

- [ ] Desktop (1440px+): all pages render correctly
- [ ] Tablet (768px–1024px): layouts stack properly
- [ ] Mobile (375px–480px): all content accessible
- [ ] Navigation links work (primary + all 4 footer menus)
- [ ] Header gets `.scrolled` class after 60px scroll
- [ ] Back-to-top button appears after 400px scroll
- [ ] Scroll reveal animations fire on scroll
- [ ] Counter animations count up on stat sections
- [ ] Newsletter form submits (if Elementor Pro form configured)
- [ ] Dynamic Tags display correct ACF data on each page

### Accessibility

- [ ] Keyboard navigation works (Tab through all interactive elements)
- [ ] Focus rings visible (green outline on `:focus-visible`)
- [ ] Skip-to-content link works on Tab press
- [ ] `prefers-reduced-motion` disables all animations
- [ ] Color contrast meets WCAG 2.1 AA (white on navy, white on green)

### Performance

- [ ] Lighthouse score 90+ on Performance, Accessibility, SEO
- [ ] Caching plugin active and configured
- [ ] Elementor improved asset loading enabled
- [ ] Images optimized (WebP where possible)

### Security

- [ ] Security headers verified at securityheaders.com
- [ ] SSL certificate valid
- [ ] HSTS header active
- [ ] Login page shows generic error messages
- [ ] XML-RPC disabled (test with `curl -X POST https://yourdomain.com/xmlrpc.php`)

---

## 19. URL Redirects

If migrating from a previous version of the CHF site, set up 301 redirects using the **Redirection** plugin or `.htaccess`.

### Redirect Setup

1. Install the **Redirection** plugin
2. Go to **Tools → Redirection**
3. For each redirect: enter Source URL → Target URL → 301 Moved Permanently

### Common Redirect Patterns

These target URLs reflect the import slugs from the XML. If you renamed slugs post-import (see Post-Import Slug Cleanup above), update the target URLs accordingly.

| Old URL | New URL (import slug) |
|---|---|
| `/about-us/` | `/about/` |
| `/about-us/team/` | `/team/` |
| `/about-us/supporters/` | `/supporters/` |
| `/about-us/reports/` | `/financials/` |
| `/strategic-initiatives/` | `/initiatives/` |
| `/news-media/` | `/news/` |
| `/events-page/` | `/events/` |
| `/leadership-forum/` | `/leadership/` |
| `/donate-now/` | `/donate/` |

### Initiative URL Pattern

Old: `/initiatives/{slug}/` -> New: `/initiative/{slug}/`

Note: The initiative CPT uses singular `/initiative/` in its URL structure. If your old site used `/initiatives/` (plural), add a redirect:

```apache
# .htaccess (Apache)
RedirectMatch 301 ^/initiatives/(.*)$ /initiative/$1
```

### Post-Launch Monitoring

1. Check **Google Search Console** for crawl errors
2. Monitor the Redirection plugin's **404 log**
3. Set alerts for new 404 patterns in the first 2 weeks

---

## 20. Troubleshooting

### Elementor Editor Not Loading

**Cause:** Usually a memory or JS conflict issue.

1. Add to `wp-config.php`: `define( 'WP_MEMORY_LIMIT', '512M' );`
2. Check browser console (F12) for JavaScript errors
3. Deactivate all plugins except Elementor/Pro, reactivate one by one
4. Clear all caches (plugin, browser, CDN)

### "Elementor Required" Admin Notice

**Cause:** Elementor plugin is not active.

Install and activate Elementor. The notice (`chf_elementor_required_notice()`) auto-dismisses once Elementor loads.

### Dynamic Tags Not Appearing

**Cause:** Missing Elementor Pro or ACF PRO.

1. Verify **Elementor Pro** is active (Dynamic Tags require Pro)
2. Verify **ACF PRO** is active (tags use `get_field()`)
3. Make sure the current post type matches the ACF field group's location rules
4. Save the page in WordPress first — ACF fields must exist before Dynamic Tags can read them

### Custom Post Types Missing from Admin

**Cause:** Rewrite rules not flushed.

Go to **Settings → Permalinks** → Click **Save Changes** (even without changing anything).

### Styles Look Wrong / Design Broken

1. Verify the **child theme** (not Hello Elementor) is active
2. Verify Elementor default colors/fonts are **disabled** (Section 7A)
3. Check browser console for 404 errors on CSS files
4. Clear all caches
5. Verify stylesheet order: `hello-elementor` → `chf-style` → `design-system` → `elementor-overrides`

### CSP Blocks Third-Party Resources

**Symptom:** Browser console shows "Refused to load..." errors.

Edit `inc/security.php` and add the blocked domain to the correct CSP directive:

| Resource Type | CSP Directive |
|---|---|
| Scripts | `script-src` |
| Styles | `style-src` |
| Images | `img-src` |
| Iframes (YouTube, Vimeo) | `frame-src` |
| Fonts | `font-src` |

### 404 on Initiative/Event Pages

Flush permalinks: **Settings → Permalinks → Save Changes**.

---

## Version History

| Version | Date | Changes |
|---|---|---|
| 5.0.0 | April 2026 | Full Elementor Pro rebuild. Hello Elementor child theme, 10 Elementor templates, container-based layout, 4 Dynamic Tags, comprehensive security hardening. |
