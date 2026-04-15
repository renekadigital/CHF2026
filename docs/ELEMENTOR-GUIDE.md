# Elementor Guide

Everything you need to know about using Elementor on the CHF site — for both
content editors making routine updates and developers building new layouts.

---

## 1. Opening Elementor on a page

1. **Pages → All Pages** in WordPress admin
2. Hover over the page, click **Edit with Elementor** (not "Edit")
3. Wait 3–5 seconds for the editor to load
4. You'll see:
   - **Left panel** — widgets and settings
   - **Right side** — live preview of your page
   - **Bottom bar** — save button, history, responsive preview

To exit: click the ≡ menu (top left) → **Exit to Dashboard**.

---

## 2. The 3 Elementor concepts you MUST understand

### Containers
Rectangular boxes that hold widgets or other containers. Think of them like
layers in a Photoshop file — everything is nested inside a container.

CHF uses **Flexbox containers** (the modern Elementor container system), not
the old Section/Column system. All 10 templates are built this way.

### Widgets
The individual building blocks: heading, text, button, image, form, etc.
Widgets live inside containers, not on the page directly.

### Templates
Full page layouts saved as reusable designs. CHF ships 10 templates (see
[ARCHITECTURE.md](./ARCHITECTURE.md) § 4).

---

## 3. Making safe edits

### Editing text
1. Click the text in the preview
2. The left panel switches to that widget's settings
3. Edit in the left panel's text field, or directly in the preview
4. Changes apply live

### Editing images
1. Click the image in the preview
2. In the left panel under **Content → Image**, click the current image
3. Choose from Media Library or upload new
4. Make sure **Image Size** is set to **Full** unless you have a reason

### Editing colors
**Don't set colors manually inside widgets.** Always use Global Colors:

1. Click any text widget
2. **Style → Color → [globe icon] Global Colors**
3. Pick from the CHF palette (Navy, Green, Gold, etc.)

This way if the brand color ever changes, updating the global color updates
every widget site-wide.

### Editing typography
Same principle: use **Global Fonts**, not manual font pickers.

1. Click a text widget
2. **Style → Typography → [globe icon] Global Fonts**
3. Pick from the CHF scale (H1, H2, Body, etc.)

---

## 4. Common layout tasks

### Adding a new section to an existing page

1. Scroll to where you want the new section
2. Hover between two existing containers — a "+" button appears
3. Click + → **Select your structure** → pick a column layout (most often
   full width or 2-column)
4. Drag widgets from the left panel into the new container

### Duplicating a section

1. Right-click the container's handle (top-left corner of the container)
2. Click **Duplicate**
3. Drag the copy into position
4. Edit the content

This is the fastest way to add another "card" or "stat block" without
rebuilding the layout.

### Deleting a section

1. Right-click the container handle
2. Click **Delete**
3. Confirm

If you delete something by accident, use **History** (bottom left, rewind icon).

### Changing column count on mobile

1. Click the container
2. Switch to mobile view (bottom bar → phone icon)
3. **Layout → Items → Width** → change to 100% for full-width stacking

Most CHF templates already handle this. Don't override unless the layout
actually breaks on mobile.

---

## 5. Responsive preview

Use the bottom-bar device icons to preview:

- **Desktop** (default)
- **Tablet** (768px wide)
- **Mobile** (375px wide)

**Every edit must look good on all three.** CHF templates are built
responsive, but custom changes can break mobile if you're not careful.

### Mobile-specific rules
- Font sizes: H1 max 32px, H2 max 26px
- Padding: reduce to 64px top/bottom on sections
- Columns: stack to single column below 768px
- Images: full width, 16:9 ratio

---

## 6. Theme Builder templates

These are the "master templates" that control the header, footer, and post
page layouts. **Only administrators should edit these.** A mistake here affects
the entire site.

### Accessing Theme Builder

1. **Templates → Theme Builder** (in WordPress admin, under Elementor Pro)
2. You'll see cards for Header, Footer, Single Post, Archive, etc.
3. Click a card to open it in the editor

### The 10 pre-built templates

| Template | Controls | Conditions |
|---|---|---|
| CHF Global Header | Top nav, logo, menu | Entire site |
| CHF Global Footer | Newsletter, footer menus, social | Entire site |
| CHF Homepage | The home page layout | Front page only |
| CHF Interior Page | All other pages | Pages (not front page) |
| CHF Initiative Single | Each initiative's page | Initiatives CPT |
| CHF Event Single | Each event's page | Events CPT |
| CHF Events Archive | `/events-archive/` listing | Events archive |
| CHF News Archive | `/news/` listing | Posts archive |
| CHF Hero Section | Re-usable hero block | Inserted into pages manually |
| CHF Newsletter Section | Re-usable CTA block | Inserted into pages manually |

### Display conditions

When you first import a Theme Builder template, you must set its **Display
Condition** so Elementor knows where to apply it:

1. Open the template in Elementor
2. Click **Publish** (or **Update** if already published)
3. A dialog appears: **Display Conditions**
4. Click **Add Condition**
5. Pick the scope from the dropdowns

For the 10 CHF templates, the correct conditions are documented in
[../INSTALL-GUIDE.md](../INSTALL-GUIDE.md) § "Theme Builder conditions".

---

## 7. Dynamic tags (ACF integration)

Dynamic tags let widgets pull their content from ACF fields — so the same
template can show different content on different posts.

### Available CHF dynamic tags

| Tag name | Reads from | Where to use |
|---|---|---|
| **CHF Hero Eyebrow** | `hero_eyebrow` ACF field | Text widget above hero headline |
| **CHF Hero Subtitle** | `hero_subtitle` ACF field | Text widget below hero headline |
| **CHF Event Date** | `event_date` ACF field (formatted) | Event pages |
| **CHF Event Location** | `event_location` ACF field | Event pages |

### How to use a dynamic tag

1. Click a text or heading widget
2. **Content → Title → [database icon on right]**
3. The dropdown shows all dynamic tags — scroll to **CHF Fields**
4. Pick the one you want
5. The preview updates to show the current post's value

### Where to use them
The pre-built templates already use dynamic tags. You only need to add them if
you're building a new custom template that should pull post-specific content.

---

## 8. Popups (Elementor Pro)

CHF does not currently use popups, but Elementor Pro supports them. If you
want to add one (e.g., a newsletter exit-intent popup):

1. **Templates → Popups → Add New**
2. Design the popup content with widgets
3. **Publish** → set **Display Conditions** + **Triggers** (exit intent, scroll
   depth, time delay)

**Guidelines:**
- No more than 1 popup per session per user
- Mobile popups must be dismissible with a large close button
- Never auto-open within the first 5 seconds (Google ranks this as
  intrusive interstitial)

---

## 9. Forms (Elementor Pro)

The newsletter form and any contact form use the **Elementor Pro Form widget**.

### Editing a form

1. Click the form widget in the editor
2. **Content → Form Fields** — add/remove/reorder
3. **Content → Actions After Submit** — control what happens:
   - **Collect Submissions** (always enable — stores in WP admin)
   - **Email** (sends to site admin)
   - **Webhook** (for CRM integration like HubSpot or Mailchimp)
   - **Redirect** (send user to thank-you page)

### Where submissions live

**Elementor → Submissions** in WordPress admin. You can export as CSV.

### Preventing spam

1. **Content → Form Fields** → add a **Honeypot** field (invisible to humans)
2. Enable **reCAPTCHA v3** via **Elementor → Settings → Integrations**
3. Use a site key from [google.com/recaptcha](https://www.google.com/recaptcha)

---

## 10. Performance tips

Elementor can generate bloated pages if you're not careful. Follow these:

### Do
- ✅ Use containers, not sections+columns (already done in CHF templates)
- ✅ Use Global Colors and Global Fonts
- ✅ Set **Image Size** on every image widget (don't leave on "Full" for thumbnails)
- ✅ Enable **Improved CSS Loading** in Elementor → Settings → Experiments
- ✅ Enable **Optimized DOM Output** in Experiments
- ✅ Use the built-in Elementor icons (Font Awesome) rather than custom SVG uploads

### Don't
- ❌ Don't use 5 nested containers where 2 would work
- ❌ Don't set custom CSS on individual widgets — use classes and the theme's
  stylesheet
- ❌ Don't upload 5 MB hero images and let Elementor resize them
- ❌ Don't use more than 2 Google Fonts (we use Inter + Merriweather only)
- ❌ Don't disable the default CHF stylesheets to "start fresh"

---

## 11. Revisions and history

Elementor saves every change as you work. To revert:

1. **History** (bottom left, rewind icon)
2. **Actions tab** — step-by-step undo for the current session
3. **Revisions tab** — named snapshots from previous saves

Each save creates a revision. Revisions are kept indefinitely (or until you
prune them with a DB cleanup plugin).

---

## 12. Custom CSS

Elementor Pro lets you add CSS at three levels. Use the lowest-possible level
for any customization:

| Level | Where | When to use |
|---|---|---|
| **Widget** | Click widget → Advanced → Custom CSS | One-off tweak to a specific widget |
| **Container** | Click container → Advanced → Custom CSS | Section-level override |
| **Site-wide** | Elementor → Custom Code | Global CSS or JS tweaks |

**Important:** do NOT add site-wide overrides in Elementor Custom Code that
could go in `design-system.css` instead. Keep the design system in the theme
file so it survives Elementor uninstalls.

---

## 13. When Elementor breaks

### "The editor won't load / shows a blank screen"
1. Deactivate any new plugin you added recently
2. Clear site cache (including browser cache)
3. Check browser console for errors (F12 → Console)
4. Try **Elementor → Tools → Regenerate CSS & Data**

### "My changes don't appear on the front end"
1. **Elementor → Tools → Regenerate CSS & Data**
2. Clear page cache (WP Super Cache or host-managed)
3. Browser hard refresh (Cmd+Shift+R / Ctrl+Shift+R)

### "Layout looks fine in editor but broken on live site"
Almost always a caching issue. In order:
1. Browser hard refresh
2. WP Super Cache → Delete Cache
3. Cloudflare → Purge Everything
4. Hosting panel → Purge server cache

### "I can't undo a change"
1. **History → Actions** for this-session changes
2. **History → Revisions** for prior saves
3. **WordPress editor → Revisions sidebar** for very old versions

### "A template imported but looks wrong"
Re-import it. Templates → Saved Templates → Delete the broken one → Import again.

---

## 14. Keyboard shortcuts

| Action | Shortcut |
|---|---|
| Save | Cmd/Ctrl + S |
| Undo | Cmd/Ctrl + Z |
| Redo | Cmd/Ctrl + Shift + Z |
| Duplicate element | Cmd/Ctrl + D |
| Delete element | Delete |
| Finder (find widget) | Cmd/Ctrl + E |
| Toggle responsive mode | Cmd/Ctrl + Shift + M |
| Toggle navigator panel | Cmd/Ctrl + I |

Memorize Cmd+S, Cmd+Z, Cmd+D, Cmd+E. Those four will save you hours.
