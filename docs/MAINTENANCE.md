# Maintenance Guide

Keeping the CHF website healthy, fast, and secure. Every task in this guide
is scheduled — follow the rhythm and you'll avoid 95% of problems.

---

## 1. Maintenance calendar

### Weekly (≈15 minutes)
- [ ] Check uptime alerts (should be 99.9%+)
- [ ] Review Wordfence activity log for blocked attacks
- [ ] Check pending WordPress + plugin updates
- [ ] Review form submissions (Elementor → Submissions)
- [ ] Check broken links (Yoast → Tools → Text Link Counter or a scan tool)

### Monthly (≈1 hour)
- [ ] Apply safe WordPress core updates
- [ ] Apply safe plugin updates (Elementor Pro, ACF Pro, Yoast, Wordfence)
- [ ] Run a Wordfence full scan
- [ ] Check Google Search Console for coverage errors
- [ ] Check Google Analytics for traffic anomalies
- [ ] Review and approve comment queue (if comments enabled)
- [ ] Verify backups are running and restorable
- [ ] Check database size (Tools → Site Health → Info → Database)

### Quarterly (≈2 hours)
- [ ] Review and clean Media Library (remove unused uploads)
- [ ] Optimize database (Advanced Database Cleaner or WP-Optimize)
- [ ] Test disaster-recovery restore from a backup
- [ ] Audit user accounts — remove unused, update roles
- [ ] Review and update SSL certificate if not auto-renewed
- [ ] Check page speed (PageSpeed Insights, GTmetrix)
- [ ] Run accessibility scan (WAVE tool or axe DevTools)
- [ ] Review Google Search Console queries + CTR

### Annually (≈4 hours)
- [ ] Major WordPress version upgrade (if not done incrementally)
- [ ] PHP version upgrade test (on staging first)
- [ ] Hosting plan review — are you on the right tier?
- [ ] Domain renewal check
- [ ] Privacy policy and terms review
- [ ] Full security audit (or pay for external audit)
- [ ] Update this documentation packet if anything has changed

---

## 2. Updates — how to apply safely

**Never update on Friday afternoon.** If something breaks, you want to be at a
computer with time to fix it.

### Safe order
1. Back up first (see § 3)
2. Update plugins one at a time
3. Check the site works after each
4. Update WordPress core last
5. Update themes last (or never — CHF theme rarely needs updates)

### Update checklist after every update
- [ ] Load the homepage — does it look right?
- [ ] Load 2–3 interior pages
- [ ] Load an initiative and event single page
- [ ] Open a page in Elementor — does it open cleanly?
- [ ] Submit the newsletter form — does it still work?
- [ ] Check admin dashboard for new error notices
- [ ] Run **Tools → Site Health** — any new issues?

### If an update breaks something

**Option A — Roll back the plugin (fastest):**
1. Install **WP Rollback** plugin (free)
2. **Plugins → Installed Plugins** → find the broken one → **Rollback**
3. Pick the previous version

**Option B — Restore from backup:**
1. If using UpdraftPlus: **Settings → UpdraftPlus → Restore** → pick most recent backup
2. If using host-managed backups: log into host panel → Backups → Restore

---

## 3. Backups

### Backup strategy — follow the 3-2-1 rule
- **3** copies of your data
- **2** different storage types (host + cloud)
- **1** offsite (not in the same data center)

### Recommended setup

**Primary: host-managed daily backups**
- WP Engine, Kinsta, Pressable include this automatically
- Retention: 30 days minimum

**Secondary: UpdraftPlus → Dropbox or Google Drive**
- Free plugin, pro version adds more destinations
- Settings:
  - Files backup: weekly → Dropbox
  - Database backup: daily → Dropbox
  - Retention: 14 days

**Alternative: BackupBuddy or BlogVault** — paid, more powerful

### What to back up
- WordPress database (`wp_*` tables)
- `wp-content/uploads/` (all your images)
- `wp-content/themes/chf-theme/` (the theme)
- `wp-content/plugins/` (all plugin folders)
- `wp-config.php` (site config)
- `.htaccess` (Apache rules)

### Test your backups
**A backup you've never restored is not a backup.**

Every quarter:
1. Spin up a staging environment (most hosts offer this)
2. Import your backup
3. Verify the site loads and content is present
4. Delete the staging environment

---

## 4. Security response

### If Wordfence alerts you about an attack
1. Log into WordPress immediately
2. **Wordfence → Firewall → Live Traffic** to see what's happening
3. Block the attacking IP range if needed
4. If the attacker got in (unlikely with Wordfence blocking): restore from
   backup immediately, change all passwords, scan for malware

### If you get a "site hacked" warning from Google Search Console
1. **DO NOT** panic and reinstall WordPress blindly
2. Take the site offline (or enable maintenance mode)
3. Check **wp-content/uploads/** for recent PHP files (these should not exist)
4. Check **wp-content/plugins/** for unfamiliar plugins
5. Restore from the most recent backup **before** the infection
6. Change all passwords (WordPress admin, database, hosting, FTP)
7. Scan with Wordfence full scan → Remove any infected files
8. Request review in Google Search Console → Security Issues → Request Review
9. Once clean, enable 2FA for all admin users

### Password rules
- Minimum 16 characters
- Unique per service (use a password manager — 1Password, Bitwarden)
- Change every 90 days for admin accounts
- Never reuse an email address as a username
- Disable the `admin` username (create a new admin, delete `admin`)

### Strongly recommended
- **Two-factor authentication** via Wordfence or a dedicated 2FA plugin
- **Limit Login Attempts Reloaded** plugin — blocks after 3 failed attempts
- **Activity log** plugin (WP Activity Log) — records every admin action

---

## 5. Performance

### Measure first, optimize second
Use these tools monthly to catch regressions:

| Tool | URL | What it tells you |
|---|---|---|
| PageSpeed Insights | [pagespeed.web.dev](https://pagespeed.web.dev) | Core Web Vitals, LCP, CLS, FID |
| GTmetrix | [gtmetrix.com](https://gtmetrix.com) | Page load waterfall, asset sizes |
| WebPageTest | [webpagetest.org](https://webpagetest.org) | Real-device simulation |

**Target scores:**
- PageSpeed mobile: 85+
- PageSpeed desktop: 95+
- LCP: under 2.5s
- CLS: under 0.1

### Common performance fixes

**Problem: LCP over 4 seconds**
- Compress the hero image (it's the largest element)
- Use WebP format instead of JPG
- Add `loading="eager"` to the hero image (not lazy)
- Enable Cloudflare or similar CDN

**Problem: CLS over 0.1**
- Add width + height to every image
- Reserve space for dynamic content (ads, embeds)
- Avoid inserting elements above existing content

**Problem: Huge CSS bundle**
- Elementor → Settings → Features → enable **Improved CSS Loading**
- Make sure experimental **Optimized CSS Loading** is on

**Problem: JavaScript execution slow**
- Defer all third-party scripts
- Remove unused plugins
- Disable Elementor motion effects if overused

### Caching strategy

**Level 1 — Page cache (hosted):**
- Most managed hosts (Kinsta, WP Engine, Pressable) include this
- If on shared hosting, install **WP Super Cache** (free, reliable)
- **Do not use W3 Total Cache or WP Rocket unless you test Elementor
  compatibility carefully**

**Level 2 — Object cache:**
- Redis preferred, Memcached acceptable
- Requires hosting support — ask your host to enable it

**Level 3 — CDN:**
- Cloudflare free tier is sufficient
- Configure: Auto Minify ON, Brotli ON, Rocket Loader OFF (breaks Elementor)

**Level 4 — Browser cache:**
- Host should set far-future expiry on `/wp-content/uploads/*` and `/wp-content/themes/*.css`
- If not, add to `.htaccess`:
  ```apache
  <IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
  </IfModule>
  ```

---

## 6. Database maintenance

Over time, the WordPress database accumulates:
- Post revisions (one per save — thousands over a year)
- Spam comments
- Transient options
- Orphaned postmeta

### Monthly cleanup
1. Install **WP-Optimize** or **Advanced Database Cleaner** (free)
2. Run "Clean database" on:
   - Post revisions older than 30 days
   - Spam comments
   - Trashed items
   - Expired transients
   - Orphaned postmeta

### Limit post revisions
Add to `wp-config.php`:
```php
define('WP_POST_REVISIONS', 10);
```
Keeps the last 10 only. Default is unlimited.

### Database table monitoring
**Tools → Site Health → Info → Database** shows:
- Total size
- Table sizes

A healthy CHF site DB should be **under 100 MB**. If it crosses 500 MB,
something is wrong (usually a plugin logging too aggressively, or post
revisions not being limited).

---

## 7. SEO maintenance

### Monthly
- [ ] Check Google Search Console → Performance → top queries (what's ranking?)
- [ ] Check Search Console → Coverage → any errors?
- [ ] Check Search Console → Core Web Vitals → any failing URLs?

### Quarterly
- [ ] Review Yoast SEO analysis on top 10 pages
- [ ] Update meta descriptions if CTR is low
- [ ] Submit new content URLs to Search Console for indexing

### Annually
- [ ] Full content audit — what's outdated?
- [ ] Broken link scan (Screaming Frog or Yoast)
- [ ] Review schema markup (Yoast handles this automatically for most)

---

## 8. Monitoring setup

### Uptime monitoring
**Free options:**
- UptimeRobot (50 monitors free)
- Better Uptime (10 monitors free)

Monitor every 5 minutes from multiple locations. Set SMS + email alerts.

### Error monitoring
WordPress debug logs: enable in `wp-config.php` during troubleshooting only:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```
Logs to `wp-content/debug.log`. Turn off in production once done.

For ongoing PHP error monitoring, use a service like:
- Bugsnag
- Sentry
- NewRelic

### Analytics
**Google Analytics 4** — install via Yoast or a dedicated plugin.
Don't use client-side analytics in a way that requires cookie consent banners
unless you actually need user-level tracking.

**Privacy-respecting alternatives:**
- Plausible
- Fathom
- Simple Analytics

---

## 9. Plugin policy

### Required plugins (do not deactivate)
- **Elementor Pro** — page builder, site depends on it
- **Advanced Custom Fields Pro** — dynamic fields
- **Yoast SEO** — meta, schema, sitemap
- **Wordfence** — security firewall and scanner

### Strongly recommended
- **UpdraftPlus** — offsite backups
- **Redirection** — manage URL redirects (especially post-launch)
- **WP Rollback** — revert plugin updates

### Optional based on need
- **WP Mail SMTP** — if transactional emails don't deliver
- **WP-Optimize** or **Advanced Database Cleaner** — monthly DB cleanup
- **Loco Translate** — if translating content

### Never install
- Multiple caching plugins (pick one)
- Multiple SEO plugins (pick one — Yoast here)
- Multiple security plugins (pick one — Wordfence here)
- **Any plugin not updated in the last 12 months** (abandonware is a risk)
- Plugins from unknown developers (check the WordPress.org plugin page)

### Before installing any new plugin
1. Check last updated date (within 6 months)
2. Check active installs (over 10,000 is safer)
3. Check support forum (recent resolutions?)
4. Install on staging first
5. Test for 3 days before moving to production

---

## 10. Hosting notes

### Minimum hosting requirements
- PHP 8.2+
- MySQL 5.7+ or MariaDB 10.3+
- 2 GB RAM
- 20 GB disk
- SSL included
- Daily backups
- Staging environment
- SSH access

### Recommended hosts
| Tier | Host | Monthly |
|---|---|---|
| Budget | SiteGround GrowBig | $10–20 |
| Mid | Kinsta Starter | $35 |
| Premium | WP Engine Professional | $50+ |

Avoid: GoDaddy managed WordPress (slow), Bluehost (overloaded shared servers),
any $3/month host.

### Server config — what to ask your host for
- `memory_limit = 256M`
- `max_execution_time = 300`
- `post_max_size = 64M`
- `upload_max_filesize = 64M`
- `max_input_vars = 5000` (Elementor needs this)
- `max_allowed_packet = 64M` (MySQL)
- OPcache enabled
- Redis or Memcached object cache

---

## 11. Contact + escalation

When something is beyond routine maintenance:

1. **First:** consult [TROUBLESHOOTING.md](./TROUBLESHOOTING.md)
2. **If still stuck:** contact your web vendor (see CREDENTIALS-TEMPLATE.md for contact info)
3. **Elementor issues:** Elementor Pro includes premium support — [elementor.com/support](https://elementor.com/support)
4. **Host issues:** support ticket via hosting panel
5. **Security incidents:** Wordfence Response (paid plan) or independent security audit
