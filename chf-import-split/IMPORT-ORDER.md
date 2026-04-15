# CHF Split Import — MySQL Fallback

If `chf-content-import.xml` (the full 380KB file) fails with a MySQL error,
the cause is almost always a **host resource limit hit during the 78-item
batch insert** — not corrupted data. The original XML passed full validation
(valid UTF-8, no control chars, no duplicate slugs, largest item 17KB).

This folder splits the single import into **4 smaller files** so each import
request stays well under your host's memory/execution/packet limits.

## Import order (MUST be followed)

Import one file at a time via **Tools → Import → WordPress → Run Importer**.
Wait for each to complete before starting the next.

| # | File | Items | Size | Notes |
|---|------|-------|------|-------|
| 1 | `1-pages.xml` | 20 pages | 155 KB | Must run first — nav menu items reference these |
| 2 | `2-initiatives.xml` | 20 initiatives | 156 KB | Depends on `initiative_category` taxonomy (auto-created) |
| 3 | `3-events.xml` | 6 events | 42 KB | Depends on `event_type` taxonomy (auto-created) |
| 4 | `4-nav-menus.xml` | 32 nav menu items | 40 KB | **Import LAST** — references pages by slug/ID |

**Why this order?**
WordPress nav menu items store `_menu_item_object_id` meta pointing to the
page/post they link to. If the target page doesn't exist yet, the nav item
imports but points at nothing. Importing pages first ensures all link targets
resolve correctly.

## During each import

1. Check **"Download and import file attachments"** (even though we have no
   attachments — this is harmless and prevents some importer bugs).
2. Assign all posts to an existing author (the XML declares `admin` but pick
   whichever user on your site).
3. Watch for red error text at the end. Green "All done" means success.

## If a single split file STILL fails with MySQL error

The host has very tight limits. Ask your host to raise these in `php.ini`:

```ini
memory_limit        = 256M
max_execution_time  = 300
post_max_size       = 64M
upload_max_filesize = 64M
```

And in MySQL `my.cnf`:

```ini
max_allowed_packet  = 64M
wait_timeout        = 600
```

Alternatively, install the **WP All Import** plugin (free version is fine) —
it chunks imports automatically and survives shared-host resource limits that
the built-in WordPress Importer cannot.

## If you get "duplicate entry" errors

Means you already imported this content once. Either:
- Delete the existing posts first (Pages → Trash → Empty Trash), OR
- Use WP-CLI: `wp post delete $(wp post list --post_type=page --format=ids) --force`

## After all 4 files are imported

1. Pages → **All Pages** should show 20 items
2. Initiatives → **All Initiatives** should show 20 items
3. Events → **All Events** should show 6 items
4. Appearance → **Menus** → should show 4 menus with 32 total items

Then proceed with `INSTALL-GUIDE.md` from Step 5 onward (Elementor template
import, theme builder conditions, featured images).
