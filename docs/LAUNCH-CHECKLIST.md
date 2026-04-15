# Launch Checklist

Every item here must be checked off before going live. Launch is a one-way
door — invest the hour going through this list.

---

## T minus 2 weeks

### Hosting & domain
- [ ] Production hosting account active
- [ ] PHP 8.2+ verified
- [ ] MySQL 5.7+ verified
- [ ] SSL certificate installed (Let's Encrypt or host-provided)
- [ ] Domain registrar access confirmed
- [ ] DNS management access confirmed
- [ ] Email / MX records planned (Google Workspace? Microsoft 365?)
- [ ] Backup strategy in place (UpdraftPlus + host-managed)

### Staging
- [ ] Staging environment running
- [ ] Full site built on staging
- [ ] Content imported and verified
- [ ] Elementor templates imported with correct conditions
- [ ] All 46 content items present and linked correctly
- [ ] Featured images uploaded and assigned

### Stakeholders
- [ ] Client has reviewed staging site
- [ ] Client has signed off on content
- [ ] Client has signed off on design
- [ ] Legal has reviewed privacy policy + terms
- [ ] Launch date confirmed in writing

---

## T minus 1 week

### Content QA
- [ ] Proofread every page (typos, broken sentences)
- [ ] Verify all links work (use Screaming Frog or Dr. Link Check)
- [ ] Verify all images display correctly
- [ ] Verify all images have alt text
- [ ] Verify all forms submit successfully
- [ ] Check phone numbers are correct
- [ ] Check email addresses are correct
- [ ] Check all social media links
- [ ] Verify footer year is current
- [ ] Verify copyright line is correct

### SEO
- [ ] Every page has a unique meta title (Yoast)
- [ ] Every page has a unique meta description (Yoast)
- [ ] Focus keyphrase set for key pages
- [ ] Open Graph images set on homepage + key pages
- [ ] Schema markup verified (rich results test)
- [ ] XML sitemap accessible at `/sitemap_index.xml`
- [ ] `robots.txt` configured correctly (allow all for launch)
- [ ] Google Analytics 4 installed
- [ ] Google Search Console verified
- [ ] Google Tag Manager installed (if applicable)

### Performance
- [ ] PageSpeed Insights mobile score 85+
- [ ] PageSpeed Insights desktop score 95+
- [ ] LCP under 2.5s
- [ ] CLS under 0.1
- [ ] All images compressed (max 400 KB hero, 200 KB content)
- [ ] WebP used where supported
- [ ] Fonts preloaded
- [ ] CSS minified (Elementor handles this)
- [ ] JavaScript deferred
- [ ] Caching plugin configured and tested
- [ ] CDN configured (Cloudflare recommended)

### Accessibility
- [ ] WAVE accessibility tool — zero errors, max 3 warnings
- [ ] All images have alt text
- [ ] All form fields have labels
- [ ] All buttons have accessible names
- [ ] Color contrast passes WCAG AA (4.5:1 body, 3:1 large)
- [ ] Keyboard navigation works on all interactive elements
- [ ] Focus indicators visible
- [ ] Page has proper heading hierarchy (one H1, no skipped levels)
- [ ] Skip-to-content link works
- [ ] `<html lang="en">` set
- [ ] Screen reader tested (VoiceOver or NVDA) on homepage + 2 interior pages

### Security
- [ ] Strong admin password (16+ chars)
- [ ] Default `admin` username removed
- [ ] Two-factor authentication enabled
- [ ] Wordfence activated and scanned
- [ ] XML-RPC disabled (already in CHF theme)
- [ ] File editing disabled (already in CHF theme)
- [ ] HTTPS enforced site-wide
- [ ] HSTS header verified
- [ ] Security headers verified via [securityheaders.com](https://securityheaders.com)
- [ ] `wp-config.php` has unique salts
- [ ] `wp-config.php` is outside web root OR has restrictive permissions
- [ ] Debug mode OFF

### Forms & integrations
- [ ] Newsletter form submits successfully
- [ ] Newsletter integration wired up (Mailchimp, HubSpot, etc.)
- [ ] Contact form submits successfully
- [ ] Contact form sends email to right address
- [ ] Email delivery tested from site to external address
- [ ] SMTP configured (WP Mail SMTP + SendGrid/SES/Postmark)
- [ ] reCAPTCHA active on forms
- [ ] Spam protection working (test with a gibberish submission)
- [ ] Google Analytics events firing on form submit

### Elementor
- [ ] All Theme Builder conditions set correctly
- [ ] Global header displaying on every page
- [ ] Global footer displaying on every page
- [ ] Single post template on initiatives
- [ ] Single post template on events
- [ ] Archive template on events archive
- [ ] Archive template on news archive
- [ ] No "default WordPress editor" pages remaining

### Browser testing
- [ ] Chrome (latest) — desktop + mobile
- [ ] Safari (latest) — desktop + mobile (macOS + iOS)
- [ ] Firefox (latest) — desktop
- [ ] Edge (latest) — desktop
- [ ] iPhone — real device (not just simulator)
- [ ] Android — real device
- [ ] iPad — real device

---

## T minus 1 day

### Final checks
- [ ] Fresh backup taken and verified
- [ ] Content frozen (no more edits until after launch)
- [ ] Staging site matches production plan exactly
- [ ] DNS TTL lowered to 5 minutes (for quick rollback if needed)
- [ ] Launch window scheduled (avoid Friday afternoons)
- [ ] Team available during launch window
- [ ] Rollback plan documented
- [ ] Client notified of exact launch time

### Pre-flight smoke test
- [ ] Homepage loads
- [ ] Navigation works
- [ ] 3 random interior pages load
- [ ] 1 initiative loads
- [ ] 1 event loads
- [ ] Newsletter form submits
- [ ] Contact form submits
- [ ] 404 page appears for a bad URL
- [ ] Search returns results

---

## Launch day (T-0)

### Go-live sequence

1. [ ] **Final backup** of staging site (the version going live)
2. [ ] **Update DNS A record / CNAME** to point to production
3. [ ] **Install SSL certificate** on production if not auto-installed
4. [ ] **Verify site loads over HTTPS** at the real domain
5. [ ] **Test the full user journey:**
   - Homepage → about → contact → form submission
   - Homepage → initiatives → single initiative
   - Homepage → events → single event → registration
6. [ ] **Test from a different network** (phone on cellular)
7. [ ] **Test from a different country** via VPN if audience is global
8. [ ] **Submit sitemap to Google Search Console**
9. [ ] **Fetch as Google** via Search Console on 3 pages
10. [ ] **Post launch announcement** if planned

### Post-launch within 1 hour
- [ ] Verify Google Analytics is receiving data
- [ ] Verify Wordfence is scanning
- [ ] Verify uptime monitor is live (UptimeRobot, etc.)
- [ ] Check for any console errors on homepage
- [ ] Check server error logs for PHP warnings

### Post-launch within 24 hours
- [ ] Google Search Console verified on production domain
- [ ] Yoast sitemap submitted
- [ ] DNS TTL raised back to 1 hour (if lowered earlier)
- [ ] Inform team launch is complete
- [ ] Schedule 7-day post-launch review

---

## T plus 1 week (first review)

### Check
- [ ] Uptime: should be 100%
- [ ] Traffic coming in (GA4 realtime)
- [ ] No crawl errors in Search Console
- [ ] Pages indexed (search for `site:yoursite.com` on Google)
- [ ] No spam form submissions (if so, enable/tighten reCAPTCHA)
- [ ] No admin lockouts
- [ ] No security alerts

### Follow up
- [ ] Client feedback meeting scheduled
- [ ] Analytics report sent
- [ ] Any launch-day issues documented + fixed
- [ ] Issues log reviewed and closed

---

## T plus 1 month

### Review
- [ ] Page speed still acceptable
- [ ] No new Search Console errors
- [ ] Top-performing pages identified
- [ ] Low-performing pages identified
- [ ] Form conversion rate analyzed
- [ ] Bounce rate analyzed

### Optimize
- [ ] Update meta descriptions on low-CTR pages
- [ ] Improve content on low-performing pages
- [ ] Promote high-value pages via social/email
- [ ] Add new content based on client strategy
- [ ] Handoff to ongoing maintenance schedule (see MAINTENANCE.md)

---

## Rollback plan

If launch goes catastrophically wrong in the first hour:

1. **Revert DNS** to the old site (if it still exists)
2. **Restore from backup** taken immediately before launch
3. **Put maintenance mode** on the new site
4. **Communicate** with stakeholders honestly
5. **Post-mortem** within 48 hours

Preparation for rollback:
- Keep the old site running for 7 days after launch
- Note the old DNS values before changing them
- Save the staging backup somewhere accessible even if the site is down

---

## Common launch-day mistakes

- ❌ Launching Friday afternoon (no one to fix issues over the weekend)
- ❌ Forgetting to set the production environment to `noindex` → `index`
- ❌ Leaving Yoast on "Discourage search engines" (check Settings → Reading)
- ❌ Not testing forms with real email delivery
- ❌ Not verifying SSL before pointing DNS
- ❌ Updating multiple things at once — makes rollback impossible
- ❌ Not documenting what was changed at launch time
- ❌ Celebrating before verifying search indexing
