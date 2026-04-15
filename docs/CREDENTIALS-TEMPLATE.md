# Credentials & Access Template

**⚠️ SECURITY WARNING**

This is a **template** for recording access credentials. Fill in your actual
credentials and store this file **securely** — never commit it to git, never
send over unencrypted email, never share in Slack or any chat tool.

**Recommended secure storage:**
- **1Password** (Business or Family plan) — shared vault for the team
- **Bitwarden** — free tier works for small teams
- **Dashlane** — enterprise plan
- **LastPass** — acceptable but has had security issues

**Never store in:**
- Plain text files on a shared drive
- Google Docs without password protection
- Post-it notes on monitors
- Browser's built-in password manager (for sensitive admin access)
- Email

---

## How to use this template

1. Copy this file, rename to `CREDENTIALS.md` (not committed to git — already
   gitignored)
2. Fill in the values you have
3. Store the completed file in your password manager as a secure note
4. Delete the local copy when done
5. Update whenever credentials change

---

## 1. Hosting

### Primary hosting account
- **Provider:** _(e.g., Kinsta, WP Engine, SiteGround)_
- **Account email:** _
- **Admin panel URL:** _
- **Username:** _
- **Password:** _ (store in password manager)
- **Two-factor method:** _
- **Support phone/email:** _
- **Account number:** _
- **Plan name:** _
- **Monthly cost:** $_
- **Renewal date:** _
- **Payment method on file:** _

### SSH/SFTP access
- **Host:** _
- **Port:** _ (usually 22 or 2222)
- **Username:** _
- **SSH key location:** _ (e.g., `~/.ssh/id_rsa_client`)
- **Password (if applicable):** _

### Database access
- **Host:** _
- **Database name:** _
- **Username:** _
- **Password:** _
- **phpMyAdmin URL:** _

---

## 2. Domain

### Domain registrar
- **Provider:** _(e.g., Namecheap, GoDaddy, Google Domains)_
- **Registrar URL:** _
- **Account email:** _
- **Username:** _
- **Password:** _
- **Two-factor method:** _
- **Renewal date:** _
- **Auto-renew enabled:** _ (yes/no)
- **WHOIS privacy enabled:** _ (yes/no)

### DNS management
- **DNS provider:** _(may differ from registrar, e.g., Cloudflare)_
- **Admin URL:** _
- **Username:** _
- **Password:** _

### Nameservers
```
ns1._
ns2._
```

---

## 3. WordPress

### Admin accounts

**Primary admin**
- **URL:** https://centerforhoustonsfuture.org/wp-admin/
- **Username:** _
- **Email:** _
- **Password:** _
- **Role:** Administrator
- **2FA enabled:** _

**Secondary admin (backup)**
- **Username:** _
- **Email:** _
- **Password:** _
- **Role:** Administrator

**Editor accounts** (content team)
- Name → Username → Email → Role
- _ → _ → _ → Editor
- _ → _ → _ → Editor

---

## 4. Plugin licenses

### Elementor Pro
- **License email:** _
- **License key:** _
- **Purchase date:** _
- **Renewal date:** _
- **Renewal URL:** https://my.elementor.com/

### Advanced Custom Fields Pro
- **License email:** _
- **License key:** _
- **Purchase date:** _
- **Renewal URL:** https://www.advancedcustomfields.com/my-account/

### Yoast SEO Premium (if applicable)
- **License email:** _
- **License key:** _
- **Renewal URL:** https://yoast.com/my-yoast/

### Wordfence Premium (if applicable)
- **License email:** _
- **License key:** _
- **Renewal URL:** https://www.wordfence.com/central/

### Other plugin licenses
- _

---

## 5. Email / SMTP

### Transactional email service
- **Provider:** _(SendGrid, Amazon SES, Postmark, Mailgun)_
- **Admin URL:** _
- **Account email:** _
- **API key:** _ (store in password manager)
- **From address:** _
- **Monthly send limit:** _

### Google Workspace / Microsoft 365
- **Admin URL:** _
- **Admin email:** _
- **Admin password:** _
- **Super admin email:** _
- **Number of users:** _
- **Monthly cost:** $_

---

## 6. Marketing integrations

### Mailchimp (or equivalent newsletter provider)
- **Admin URL:** _
- **Account email:** _
- **Password:** _
- **API key:** _
- **Audience name:** _
- **Plan:** _

### HubSpot (if applicable)
- **Portal ID:** _
- **Admin email:** _
- **API key:** _

### Social media accounts

**Twitter/X**
- **Handle:** @_
- **Account email:** _
- **Password:** _

**LinkedIn**
- **Page URL:** _
- **Admin email:** _

**Facebook**
- **Page URL:** _
- **Admin email:** _

**Instagram**
- **Handle:** @_
- **Account email:** _

**YouTube**
- **Channel URL:** _
- **Admin email:** _

---

## 7. Analytics & Search

### Google Analytics 4
- **Property ID:** _
- **Measurement ID:** G-_
- **Admin email:** _
- **URL:** https://analytics.google.com/

### Google Search Console
- **Property:** https://centerforhoustonsfuture.org/
- **Verified by:** _(DNS, HTML file, meta tag, Analytics)_
- **Admin email:** _

### Google Tag Manager (if applicable)
- **Container ID:** GTM-_
- **Admin email:** _

### Bing Webmaster Tools (if applicable)
- **Admin email:** _

---

## 8. CDN / Performance

### Cloudflare
- **Account email:** _
- **Password:** _
- **API token:** _
- **Zone ID:** _
- **Plan:** _ (Free / Pro / Business)

### Other CDN / edge
- _

---

## 9. Backups

### Backup storage account
- **Provider:** _(Dropbox, Google Drive, Backblaze, AWS S3)_
- **Account email:** _
- **Password:** _
- **API credentials:** _
- **Retention policy:** _ (e.g., daily for 30 days)

---

## 10. Monitoring

### Uptime monitor
- **Provider:** _(UptimeRobot, Better Uptime)_
- **Account email:** _
- **Dashboard URL:** _
- **Alert contacts:** _

### Error monitor (if applicable)
- **Provider:** _
- **Account email:** _
- **Dashboard URL:** _

---

## 11. Third-party services

### reCAPTCHA
- **Site key:** _
- **Secret key:** _
- **Admin URL:** https://www.google.com/recaptcha/admin

### Stripe (if payments enabled)
- **Account email:** _
- **Publishable key:** pk_live_
- **Secret key:** sk_live_ (NEVER commit)

### Other services
- _

---

## 12. Emergency contacts

### Vendor / developer
- **Company:** reneka DIGITAL
- **Primary contact:** _
- **Email:** _
- **Phone:** _
- **Support hours:** _

### Hosting support
- **Support URL:** _
- **Support phone:** _
- **Emergency escalation:** _

### Registrar support
- **Support URL:** _
- **Support phone:** _

---

## 13. Important URLs quick reference

| Service | URL |
|---|---|
| Live site | https://centerforhoustonsfuture.org |
| WordPress admin | https://centerforhoustonsfuture.org/wp-admin/ |
| Hosting panel | _ |
| DNS panel | _ |
| Google Analytics | https://analytics.google.com/ |
| Google Search Console | https://search.google.com/search-console |
| Elementor account | https://my.elementor.com/ |
| Cloudflare dashboard | https://dash.cloudflare.com/ |

---

## 14. Password rotation schedule

Update these credentials on the schedule below:

| Credential | Rotation frequency | Last rotated |
|---|---|---|
| WordPress admin | Every 90 days | _ |
| Hosting account | Every 180 days | _ |
| Database password | Every 180 days | _ |
| SFTP password | Every 180 days | _ |
| API keys (Stripe, SendGrid, etc.) | Every 365 days | _ |
| reCAPTCHA secret | Only if compromised | _ |

**When a team member leaves:**
- [ ] Remove their WordPress user account
- [ ] Remove them from hosting account
- [ ] Remove from Google Workspace / email
- [ ] Remove from password manager shared vault
- [ ] Rotate any shared passwords they had access to
- [ ] Revoke any personal API keys they generated

---

## 15. License & renewal tracker

| Service | Cost | Renewal | Auto-renew |
|---|---|---|---|
| Domain | $_/yr | _ | _ |
| Hosting | $_/mo | _ | _ |
| Elementor Pro | $_/yr | _ | _ |
| ACF Pro | $_/yr | _ | _ |
| SSL cert | $_ | _ | _ |
| Google Workspace | $_/mo | _ | _ |
| Backup storage | $_/mo | _ | _ |
| Uptime monitor | $_/mo | _ | _ |
| CDN | $_/mo | _ | _ |
| **TOTAL MONTHLY** | **$_** | | |
| **TOTAL ANNUAL** | **$_** | | |

---

**Last updated:** _
**Updated by:** _
