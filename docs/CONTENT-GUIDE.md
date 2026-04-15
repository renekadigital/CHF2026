# Content Editor Guide

For people who write, edit, and publish content on the CHF website. You do
not need to know code to follow this guide. If something here seems technical,
you can skip it — ask a developer.

---

## 1. Before you start

### Get your login
You need:
- WordPress admin URL (something like `centerforhoustonsfuture.org/wp-admin/`)
- Username or email
- Password

If you don't have one, ask your site administrator.

### Understand the three content types

| Type | What it's for | Examples |
|---|---|---|
| **Page** | Static information | About Us, Our Team, Contact |
| **Initiative** | Ongoing strategic programs | Project Metis, Vision 2050 |
| **Event** | Upcoming or past conferences, dinners, workshops | Annual Dinner 2026, Energy Summit |
| **Post** | Time-sensitive news and announcements | "CHF Releases 2026 Housing Report" |

Choose the right type for your content. A news article should be a **Post**,
not a Page. A conference should be an **Event**, not a Page.

---

## 2. Adding a news article (Post)

1. Log in at `/wp-admin/`
2. Click **Posts → Add New**
3. Fill in:
   - **Title** — headline
   - **Permalink** — will auto-generate from the title, edit if needed
   - **Content** — the body of the article
4. On the right sidebar:
   - **Category** — pick one (Press, Research, Announcements, etc.)
   - **Featured Image** — upload a 16:9 image, at least 1200×675 pixels
5. Scroll down to the **Hero Fields** block:
   - **Eyebrow** — short label, e.g., "PRESS RELEASE" or "ANNOUNCEMENT"
   - **Highlight Word** — one word from your headline to color green
   - **Subtitle** — one sentence summary
6. Click **Publish** in the top right

**Time estimate:** 5–10 minutes per post.

---

## 3. Adding an event

1. Click **Events → Add New**
2. Title the event — e.g., "Houston Climate Summit 2026"
3. In the main content area, write the event description (what it's about, who
   should attend, what to expect)
4. On the right sidebar, set:
   - **Event Type** — pick one (conference, forum, workshop, dinner, summit, meeting)
   - **Featured Image** — 16:9 hero image
5. Scroll to **Event Details**:
   - **Event Date** — pick the date from the calendar
   - **Event Time** — e.g., "6:00 PM – 9:00 PM CST"
   - **Event Location** — venue name + city, e.g., "Hyatt Regency Downtown, Houston TX"
   - **Registration URL** — full URL with `https://`, e.g., the Eventbrite or internal form link
6. Fill in **Hero Fields** (same as for posts)
7. Click **Publish**

**After publishing:** the event automatically appears in the Events archive at
`/events-archive/` and on the homepage events block.

---

## 4. Adding an initiative

1. Click **Initiatives → Add New**
2. Title the initiative — e.g., "Project Metis"
3. In the main content area, describe the initiative (goal, approach, partners,
   outcomes)
4. On the right sidebar, set:
   - **Initiative Category** — pick one (energy-climate, health-equity, immigration)
   - **Featured Image** — 16:9 hero
5. Fill in **Hero Fields**
6. Click **Publish**

---

## 5. Updating an existing page

Pages are built in Elementor. For content edits (changing words, swapping
images, updating stats), you have two options:

### Option A — Edit via WordPress editor (easiest, but limited)

Works if you only need to change:
- Hero Fields (eyebrow, highlight word, subtitle)
- Featured Image
- Page title or SEO settings

1. Go to **Pages → All Pages**
2. Hover over the page and click **Edit**
3. Make your changes
4. Click **Update**

### Option B — Edit via Elementor (full control)

Use this for changing any visible content, layout, or images inside the page.

1. Go to **Pages → All Pages**
2. Hover and click **Edit with Elementor**
3. Elementor opens in editing mode — the left panel has controls, the right
   side shows your page
4. Click any text or image to edit it in the left panel
5. When done, click **Update** in the bottom left
6. Click the ≡ menu (top left) → **Exit** to return to WordPress

**See [ELEMENTOR-GUIDE.md](./ELEMENTOR-GUIDE.md) for detailed Elementor instructions.**

---

## 6. Managing images

### Where images go
All images upload to the **Media Library** (left menu → **Media**).

### Size requirements

| Image role | Min size | Aspect | Format |
|---|---|---|---|
| Hero background | 1920×1080 | 16:9 | JPG or WebP |
| Featured image | 1200×675 | 16:9 | JPG or WebP |
| Card thumbnail | 800×450 | 16:9 | JPG or WebP |
| Team portrait | 600×600 | 1:1 | JPG or WebP |
| Logo partner | 400×200 | 2:1 | PNG (transparent) |

### Before uploading — optimize

Unoptimized images are the #1 cause of slow pages.

**Use one of these tools (free):**
- [tinypng.com](https://tinypng.com) — drag and drop, downloads compressed
- [squoosh.app](https://squoosh.app) — Google's image compressor
- Photoshop → File → Export → Save for Web → JPG quality 70-80

**Target file sizes:**
- Hero background: under 400 KB
- Featured image: under 200 KB
- Thumbnail: under 100 KB
- Logo: under 50 KB

### Alt text
**Always fill in alt text.** This is the short description of the image for
screen readers and SEO. Example:
- ❌ "IMG_3847.jpg"
- ✅ "Houston skyline at sunset with Green Loop trail in foreground"

In the Media Library, click any image → fill in the **Alternative Text** field.

---

## 7. Hero Fields explained

Every page, initiative, event, and post has a hero section with three
configurable parts:

### Eyebrow
Short uppercase label above the headline. Gives the user instant context.

**Good examples:**
- "ABOUT US"
- "2026 ANNUAL REPORT"
- "PRESS RELEASE"

**Bad examples:**
- "This is a really long eyebrow that won't fit well"
- Mixed case ("About Us")
- Empty

### Highlight Word
A single word from your headline that will appear in a green gradient. This
creates visual emphasis on the most important concept.

**Example:** If your headline is "Building Houston's energy **future**",
set Highlight Word to "future".

**Rules:**
- One word only (a short 2-word phrase works, but one is best)
- Must exactly match a word that appears in the page title
- Leave blank if you don't want any highlight

### Subtitle
One or two sentences that expand on the headline. Explains what the page is
about in plain language.

**Good examples:**
- "Houston's premier cross-sector think tank driving data-informed policy solutions."
- "A decade of research on energy transition, health equity, and inclusive growth."

**Bad examples:**
- "Welcome to our page" (generic)
- Four paragraphs (too long)

---

## 8. Navigation menus

To add, remove, or reorder menu items:

1. **Appearance → Menus**
2. Pick the menu from the dropdown at the top (there are 5: Primary, Footer
   Utility, Footer Initiatives, Footer Leadership, Social)
3. On the left, find the item you want to add (Pages, Custom Links, Initiatives)
4. Check it and click **Add to Menu**
5. Drag to reorder on the right
6. Drag slightly right to make a sub-item (dropdown)
7. Click **Save Menu**

### Menu hierarchy rules
- **Primary menu:** Max 7 top-level items, max 2 levels deep
- **Dropdowns:** Keep to 5 items per dropdown
- **Labels:** 1–3 words each, Title Case

---

## 9. Publishing vs drafting

| Status | What it means |
|---|---|
| **Draft** | Saved but not visible on the site. Use while you're still writing. |
| **Pending Review** | You're finished but want a second set of eyes. Sends to admin. |
| **Schedule** | Auto-publishes at a future date/time. Great for press releases. |
| **Published** | Live on the site. |
| **Private** | Visible only to logged-in users with permission. |

To schedule: click the date next to "Publish" in the top right, pick a future
date, the button changes to "Schedule."

---

## 10. Common content mistakes

### "I published but it's not showing up"
- Check the status — is it Draft or Pending?
- Is it assigned to the right category?
- Clear your browser cache (Cmd+Shift+R / Ctrl+Shift+R)
- If still not showing: **Settings → Permalinks → Save Changes** (this flushes the URL cache)

### "The image looks stretched or cropped weird"
- Upload a larger image that matches the aspect ratio
- Check the required size in the Size Requirements table above
- In Elementor, make sure the image widget has **Image Size → Full** set

### "The headline wraps weird on mobile"
- Short headlines work best (under 8 words)
- Use shorter words ("use" not "utilize")
- Check it on your phone before publishing

### "Hero field isn't showing up"
- Make sure you saved/updated the page after filling it in
- Clear the browser cache
- If still blank: check that the Elementor template for that page includes the
  dynamic hero block (ask a developer to verify)

### "I accidentally deleted a page"
- Go to **Pages → All Pages**, click the **Trash** tab at the top
- Hover over the deleted page and click **Restore**
- Deleted pages stay in trash for 30 days then purge permanently

### "I broke the layout"
- Every Elementor save creates a revision
- In Elementor: click **History** (bottom left, rewind icon) → revert to the
  last good version
- Or: in WordPress editor, scroll to **Revisions** on the right sidebar → click
  the one you want → click **Restore**

---

## 11. SEO best practices (Yoast)

Every page/post has a **Yoast SEO** box at the bottom. Fill in:

1. **Focus keyphrase** — the one term you want this page to rank for
2. **SEO title** — 50–60 characters, starts with the keyphrase
3. **Meta description** — 150–160 characters, includes keyphrase, ends with CTA
4. **URL slug** — short, lowercase, hyphens not underscores

**Example for a news post:**
- Focus: "Houston hydrogen hub"
- SEO title: "Houston Hydrogen Hub: CHF's Role in Energy Transition"
- Meta: "CHF's Hydrogen Hub initiative will position Houston as the center of America's clean hydrogen economy. Read the full plan."
- Slug: `houston-hydrogen-hub`

Yoast gives you a green / orange / red indicator — aim for green. It's OK if
readability is orange if content quality is high.

---

## 12. Style guide quick-reference

### Capitalization
- Page titles: **Title Case** (Capitalize Major Words)
- Headlines (H1, H2): **Title Case**
- Body headings (H3, H4): **Sentence case**
- Eyebrow labels: **UPPERCASE**
- Buttons: **Title Case** ("Read the Report", not "READ THE REPORT")

### Numbers
- **Under 10:** spelled out ("three initiatives")
- **10 and over:** numerals ("12 partners")
- **Stats:** always numerals ("$28 billion", "2,400 leaders")
- **Percentages:** numeral + % ("67%")

### Dates
- Events: "January 15, 2026" (full month, ordinal comma, year)
- News bylines: "Jan 15, 2026" (abbreviated, comma, year)

### Em dashes vs hyphens
- Em dash (—) for parenthetical asides
- En dash (–) for ranges ("6:00 PM – 9:00 PM")
- Hyphen (-) for compound words ("cross-sector")

### Avoid
- ❌ "!!" or "!!!" in headlines
- ❌ ALL CAPS for emphasis in body (use bold)
- ❌ Passive voice when active works ("The report was released" → "CHF released the report")
- ❌ Industry jargon on public-facing pages
