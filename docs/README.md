# Center for Houston's Future — Technical Documentation Packet

**Version:** 5.0.0
**Last updated:** April 2026
**Prepared by:** reneka DIGITAL

---

## What this packet is

This is the complete reference for the Center for Houston's Future website — how
it's built, how to manage content, how to keep it running, and how to extend it.
Every file you need is here. Nothing is stored "in the consultant's head."

Keep this folder with your website assets. If you change vendors, hand this
packet to the next developer on day one.

---

## How to use this packet

The docs are split by audience. Start with the one that matches your role:

### I'm a content editor (I update pages and publish news)
1. **[CONTENT-GUIDE.md](./CONTENT-GUIDE.md)** — how to add pages, initiatives, and events
2. **[ELEMENTOR-GUIDE.md](./ELEMENTOR-GUIDE.md)** — how to use Elementor without breaking anything
3. **[TROUBLESHOOTING.md](./TROUBLESHOOTING.md)** — when something looks wrong

### I'm a site administrator (I keep the site running)
1. **[MAINTENANCE.md](./MAINTENANCE.md)** — weekly, monthly, and quarterly tasks
2. **[LAUNCH-CHECKLIST.md](./LAUNCH-CHECKLIST.md)** — pre-launch and go-live steps
3. **[CREDENTIALS-TEMPLATE.md](./CREDENTIALS-TEMPLATE.md)** — record all logins here (fill in, keep secure)
4. **[TROUBLESHOOTING.md](./TROUBLESHOOTING.md)** — diagnostics when things break

### I'm a developer (I'm extending or debugging code)
1. **[ARCHITECTURE.md](./ARCHITECTURE.md)** — how the theme is put together
2. **[DESIGN-SYSTEM.md](./DESIGN-SYSTEM.md)** — tokens, typography, components
3. **[DEVELOPER-REFERENCE.md](./DEVELOPER-REFERENCE.md)** — hooks, filters, PHP reference

### I'm installing the site for the first time
1. **[../INSTALL-GUIDE.md](../INSTALL-GUIDE.md)** — step-by-step WordPress + Elementor setup
2. **[../chf-import-split/IMPORT-ORDER.md](../chf-import-split/IMPORT-ORDER.md)** — if the single XML import fails

---

## Project at a glance

| | |
|---|---|
| **Domain** | centerforhoustonsfuture.org |
| **CMS** | WordPress 6.4+ |
| **Theme** | CHF (child of Hello Elementor) |
| **Page builder** | Elementor Pro 3.x |
| **Required plugins** | Elementor Pro, Advanced Custom Fields Pro, Yoast SEO, Wordfence |
| **PHP** | 8.2 required |
| **MySQL** | 5.7+ / MariaDB 10.3+ |
| **Content** | 20 pages, 20 initiatives, 6 events, 32 nav items |
| **Primary colors** | Navy #1B2A4A · Green #56B84A · Gold #C9A84C |
| **Typography** | Inter (body) + Merriweather (headings) |

---

## Directory structure

```
chf-theme/
├── style.css                       Design tokens + child theme header
├── functions.php                   Setup, enqueues, includes
├── inc/
│   ├── custom-post-types.php       Initiatives + Events CPTs, ACF fields
│   ├── security.php                Headers, hardening, cleanup
│   └── elementor-setup.php         Dynamic tags, theme builder, defaults
├── assets/
│   ├── css/
│   │   ├── design-system.css       Reset, typography, utilities
│   │   └── elementor-overrides.css Widget-level overrides
│   └── js/
│       └── frontend.js             Scroll, counters, back-to-top
├── elementor-templates/            10 importable JSON templates
├── chf-content-import.xml          Full WXR import (78 items)
├── chf-import-split/               Split fallback if full import fails
│   ├── 1-pages.xml
│   ├── 2-initiatives.xml
│   ├── 3-events.xml
│   ├── 4-nav-menus.xml
│   └── IMPORT-ORDER.md
├── INSTALL-GUIDE.md                Step-by-step installation
├── _archive_v4/                    Legacy files (do not use)
└── docs/                           ← YOU ARE HERE
    ├── README.md                   This file
    ├── ARCHITECTURE.md
    ├── DESIGN-SYSTEM.md
    ├── CONTENT-GUIDE.md
    ├── ELEMENTOR-GUIDE.md
    ├── DEVELOPER-REFERENCE.md
    ├── MAINTENANCE.md
    ├── TROUBLESHOOTING.md
    ├── LAUNCH-CHECKLIST.md
    └── CREDENTIALS-TEMPLATE.md
```

---

## Quick contacts

| Role | Current vendor | Notes |
|---|---|---|
| **Design & development** | reneka DIGITAL | Theme, Elementor templates, content import |
| **Hosting** | _record in CREDENTIALS-TEMPLATE.md_ | |
| **Domain registrar** | _record in CREDENTIALS-TEMPLATE.md_ | |
| **Email / DNS** | _record in CREDENTIALS-TEMPLATE.md_ | |

---

## Where to get help

1. **Something broken after a WordPress update?** → [TROUBLESHOOTING.md](./TROUBLESHOOTING.md)
2. **Content won't save or display wrong?** → [CONTENT-GUIDE.md](./CONTENT-GUIDE.md) § "Common content mistakes"
3. **Need to add a new page type or field?** → [DEVELOPER-REFERENCE.md](./DEVELOPER-REFERENCE.md)
4. **Site feels slow?** → [MAINTENANCE.md](./MAINTENANCE.md) § "Performance"
5. **Security alert from Wordfence?** → [MAINTENANCE.md](./MAINTENANCE.md) § "Security response"

---

## Documentation version history

| Version | Date | Change |
|---|---|---|
| 5.0.0 | 2026-04 | Initial packet — full rewrite for Elementor Pro architecture |
