# Troubleshooting Guide

Symptom → cause → fix. Start at the symptom that matches your problem and
work down.

---

## Quick diagnostic checklist

When something is wrong, run through this before anything else:

1. [ ] Can you reproduce it on a different browser? (Chrome → Firefox)
2. [ ] Can you reproduce it in incognito mode? (rules out browser cache/extensions)
3. [ ] Can you reproduce it on mobile? (rules out device-specific issues)
4. [ ] What was the last change made to the site? (updates, new plugin, edit)
5. [ ] Is there an error message? Write it down verbatim.

---

## 1. White screen of death (WSoD)

### Symptom
Blank white page instead of the site. Sometimes on frontend only, sometimes
admin too.

### Cause
PHP fatal error. Something is throwing an exception that kills the whole
WordPress bootstrap.

### Fix

**Step 1 — enable debug mode** (via FTP / cPanel File Manager):

Edit `wp-config.php`, add before `/* That's all, stop editing! */`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

**Step 2 — trigger the error again**, then check `/wp-content/debug.log`. The
last line is your error. It'll look like:
```
PHP Fatal error: Uncaught Error: Call to undefined function xyz() in /path/file.php:123
```

**Step 3 — based on the file path in the error:**

| File path | Cause | Fix |
|---|---|---|
| `/wp-content/plugins/[NAME]/` | Plugin crashed | Rename plugin folder to disable: `[NAME].disabled` |
| `/wp-content/themes/chf-theme/` | Theme code issue | Revert recent theme changes or activate a default theme temporarily |
| `/wp-content/themes/hello-elementor/` | Parent theme update broke | Roll back Hello Elementor via WP Rollback |
| `/wp-includes/` | WordPress core issue | Re-upload WordPress core files via FTP |

**Step 4 — turn off debug mode** once fixed:
```php
define('WP_DEBUG', false);
```

---

## 2. "Database connection error"

### Symptom
Red error box: **"Error establishing a database connection"**

### Cause
WordPress can't talk to MySQL. Could be credentials, server down, or
corrupted tables.

### Fix

**Check 1 — credentials in `wp-config.php`:**
```php
define( 'DB_NAME', 'database_name_here' );
define( 'DB_USER', 'username_here' );
define( 'DB_PASSWORD', 'password_here' );
define( 'DB_HOST', 'localhost' );  // sometimes IP or domain
```
Verify these match what your host provides. Credentials often change after a
migration.

**Check 2 — MySQL server is up:**
In your hosting panel (cPanel, Plesk, Kinsta, etc.), check if the MySQL
service is running. If down, restart it or contact support.

**Check 3 — repair the database:**
Add to `wp-config.php` temporarily:
```php
define('WP_ALLOW_REPAIR', true);
```
Then visit `yoursite.com/wp-admin/maint/repair.php` → click **Repair
Database**. Remove the line when done.

---

## 3. "Briefly unavailable for scheduled maintenance"

### Symptom
Maintenance mode message stuck after a failed update.

### Cause
A plugin or core update crashed partway through. WordPress created a
`.maintenance` file and never removed it.

### Fix
FTP/SFTP into the site root, delete `.maintenance`. Site comes back.

```
/
├── .maintenance   ← delete this file
├── wp-admin/
├── wp-content/
└── wp-config.php
```

---

## 4. Changes don't appear on the live site

### Symptom
You edit a page, click Update, the editor says saved, but visitors see the old version.

### Cause
Caching. Somewhere in the stack, something is serving a stale copy.

### Fix (in order)

1. **Browser hard refresh:** Cmd+Shift+R (Mac) or Ctrl+Shift+F5 (Windows)
2. **Clear WordPress page cache:**
   - WP Super Cache → Delete Cache
   - WP Rocket → Clear Cache
   - Host panel → Purge server cache (Kinsta, WP Engine have this)
3. **Clear Elementor CSS cache:** Elementor → Tools → Regenerate CSS & Data
4. **Clear CDN cache:**
   - Cloudflare → Caching → Purge Everything
5. **Check Object Cache:**
   - If using Redis: host panel → flush Redis
6. **Check your DNS:** `dig yoursite.com` — is it pointing where you think?

Still stale? Add `?debug=1` to the URL. WordPress will bypass most caches.
If that works, the cache is the culprit.

---

## 5. Elementor editor won't load

### Symptom
Blue loading bar, then nothing. Blank grey area. Or "Preview could not be loaded."

### Fix

1. **Reload the page** and wait 30 seconds before giving up
2. **Deactivate recently added plugins** — any one can break Elementor
3. **Elementor → Tools → Regenerate CSS & Data**
4. **Elementor → Tools → Safe Mode** → reload → does it work?
   - If yes: a plugin is interfering, find it by deactivating one at a time
   - If no: theme or core WordPress issue
5. **Check `memory_limit`** — needs 256M minimum. Add to `wp-config.php`:
   ```php
   define('WP_MEMORY_LIMIT', '256M');
   ```
6. **Check PHP version** — must be 8.0+ for Elementor 3.x
7. **Check for console errors** — F12 → Console tab in browser. JavaScript
   errors tell you exactly what's broken.
8. **Re-upload Elementor Pro** — in your Elementor account, download the zip,
   then Plugins → Add New → Upload → overwrite

---

## 6. Form submissions not arriving

### Symptom
User submits the newsletter or contact form. You never receive the email.

### Cause
WordPress uses `wp_mail()` which uses PHP `mail()` by default. Shared hosts
often silently drop these.

### Fix

**Step 1 — check Elementor submissions log:**
**Elementor → Submissions** — is the submission there? If yes, the form
worked. The problem is email delivery.

**Step 2 — install WP Mail SMTP:**
1. **Plugins → Add New** → search "WP Mail SMTP"
2. Install and activate
3. Configure with SMTP credentials:
   - SendGrid (free 100/day)
   - Amazon SES (cheap, reliable)
   - Postmark (premium, fastest)
4. Send a test email from WP Mail SMTP → Tools → Email Test
5. Check spam folder if not in inbox

**Step 3 — verify Elementor email action:**
1. Edit the form in Elementor
2. **Actions After Submit** → should include "Email"
3. **Email** → recipient address, subject, body — verify they're correct
4. Enable **Email 2** as a backup recipient if needed

---

## 7. Images not uploading

### Symptom
Upload fails, "HTTP error", or "Post-processing of the image failed."

### Fix

**Check 1 — file size:**
- Max upload size is visible in Media → Add New
- If too low, increase in `php.ini` or `.htaccess`:
  ```ini
  upload_max_filesize = 64M
  post_max_size = 64M
  memory_limit = 256M
  ```

**Check 2 — file permissions:**
- `wp-content/uploads/` must be writable (755 or 775)
- Via FTP: right-click uploads folder → Permissions → 755 → apply recursively

**Check 3 — disk space:**
- Hosting panel → check disk quota
- Run out of space? Delete old backups, clear media library, remove dev files

**Check 4 — image dimensions too large:**
- Images over 4000px on either side sometimes fail
- Resize before upload (Photoshop, Preview, ImageOptim)

**Check 5 — GD or Imagick library:**
- Tools → Site Health → Info → Media Handling
- Must show "GD" or "ImageMagick" available
- If neither: ask host to install ImageMagick

---

## 8. 404 errors on new pages

### Symptom
New page or post returns "Not Found" even though it exists.

### Cause
WordPress rewrite rules are stale.

### Fix
**Settings → Permalinks → Save Changes**

This rebuilds the `.htaccess` rewrite rules. No actual setting needs to
change — just clicking Save fixes it.

Still 404? Check:
- Is the page status Published (not Draft)?
- Is there a typo in the URL?
- Is there a conflicting page/post with the same slug?

---

## 9. Hero fields not showing

### Symptom
You filled in Eyebrow / Highlight / Subtitle in the WordPress editor, but
they don't appear on the page.

### Cause
The Elementor template for that page doesn't reference the dynamic fields, OR
the page cache is stale, OR ACF is not active.

### Fix

1. **Check ACF Pro is activated** (Plugins → Installed Plugins)
2. **Re-save the page** — sometimes ACF fields don't fire without a save
3. **Clear all caches** (see § 4)
4. **Edit the page template in Elementor:**
   - Open the page with "Edit with Elementor"
   - Find the hero heading/text widgets
   - Click the database icon next to each text field
   - Verify it uses "CHF Hero Eyebrow" / "CHF Hero Subtitle" dynamic tag
   - If not, switch to dynamic
5. **Verify ACF field group assignment:**
   - Custom Fields → Field Groups → Hero Fields
   - Location rules should include the post type (page, initiative, event, post)

---

## 10. Newsletter form styling broken

### Symptom
Newsletter signup form renders but styling is wrong (no button color, wrong font).

### Cause
`elementor-overrides.css` is not loading, OR the form widget was edited outside
the brand template.

### Fix

1. **Check stylesheet loads:**
   - View page source → Ctrl+F → search `elementor-overrides.css`
   - Should be there. If missing, check `functions.php` enqueue block
2. **Regenerate Elementor CSS:**
   - Elementor → Tools → Regenerate CSS & Data
3. **Re-import the newsletter template:**
   - Templates → Saved Templates → Delete existing CHF Newsletter Section
   - Templates → Import → `elementor-templates/section-newsletter.json`
4. **Re-link global color/font:**
   - Open the form widget, re-assign Global Color to "Green" for the button

---

## 11. Login locked out

### Symptom
Too many failed login attempts, now blocked from `/wp-admin/`.

### Cause
Wordfence or Limit Login Attempts blocked your IP.

### Fix

**Option A — from another IP:**
- Try logging in from phone (off wifi, on cellular)
- Works? Unblock your office IP in Wordfence → Firewall → Blocked IPs

**Option B — whitelist your IP via `wp-config.php`:**
Via FTP, edit `wp-config.php`, add:
```php
define('WORDFENCE_DISABLE_IP_BLOCKING', true);
```
Log in, unblock the IP, remove the line.

**Option C — via database:**
Via phpMyAdmin, delete rows from `wp_wflocked_out_ips` matching your IP.

---

## 12. SSL certificate error / "Not Secure"

### Symptom
Browser shows "Not Secure" warning, or HTTPS is broken.

### Fix

1. **Check if SSL is installed:**
   - `https://yoursite.com` should load without warnings
   - If not, SSL not installed — ask host to install Let's Encrypt (free)
2. **Check WordPress address:**
   - Settings → General → both URLs must start with `https://`
   - If not, change them (careful — can lock you out; back up first)
3. **Mixed content warnings:**
   - Some assets load over HTTP, triggering the warning
   - Install **Really Simple SSL** plugin → it auto-rewrites URLs
4. **Check certificate expiry:**
   - `https://www.ssllabs.com/ssltest/` → enter your domain
   - Should show valid cert with recent expiry
5. **HSTS cache:**
   - If you had HTTPS then reverted, HSTS blocks HTTP
   - Browser → clear HSTS for the domain (Chrome: `chrome://net-internals/#hsts`)

---

## 13. Search shows wrong results

### Symptom
WordPress search returns irrelevant or no results.

### Cause
Default WordPress search is weak. It only searches title + content, ignores
CPTs sometimes.

### Fix

1. **Include custom post types in search** — add to `functions.php`:
   ```php
   function chf_include_cpt_in_search($query) {
       if ($query->is_search && !is_admin()) {
           $query->set('post_type', ['post', 'page', 'chf_initiative', 'chf_event']);
       }
   }
   add_action('pre_get_posts', 'chf_include_cpt_in_search');
   ```
2. **Better search plugins:**
   - **Relevanssi** — full-text relevance search
   - **SearchWP** (paid) — more configurable
3. **Algolia or MeiliSearch** for enterprise-quality search

---

## 14. PHP version incompatibility

### Symptom
"Your site requires PHP 8.2 or higher" or deprecation warnings.

### Fix

1. **Check current PHP version:**
   - Tools → Site Health → Info → Server
2. **Upgrade PHP via hosting panel:**
   - cPanel → MultiPHP Manager → select 8.2
   - Kinsta/WP Engine → settings → PHP version → 8.2
3. **Test first on staging:**
   - Some old plugins may not support PHP 8.2
   - Upgrade one environment, test, then promote to production

---

## 15. When all else fails — restore from backup

If you can't figure out what's wrong and the site is actively broken:

1. **Identify the last known good backup** (time-stamped)
2. **Back up the current broken state** (in case you need forensics later)
3. **Restore the good backup:**
   - UpdraftPlus: **Settings → UpdraftPlus → Existing Backups → Restore**
   - Host-managed: via hosting panel
4. **Verify the site loads correctly**
5. **Don't repeat whatever broke it** until you know what it was
6. **Investigate on staging** before re-attempting

---

## Emergency contact escalation

| Problem | First contact | If no response within |
|---|---|---|
| Site down | Hosting support | 1 hour |
| Hacked | Wordfence Response OR your vendor | 30 minutes |
| Domain expired | Registrar support | 4 hours |
| SSL expired | Hosting support | 4 hours |
| DNS misconfigured | DNS provider support | 2 hours |
| Can't reach vendor | Second opinion — post on Elementor support forum or r/WordPress | 24 hours |
