# Repository Setup Guide

The CHF theme has been initialized as a local git repository with the initial
v5.0.0 release committed and tagged. This guide walks you through pushing it
to a private remote (GitHub, GitLab, or Bitbucket) so you have versioned,
off-site source of truth.

---

## Current repo state

```
Branch:           main
Latest commit:    chore: initial v5.0.0 release
Tag:              v5.0.0
Files tracked:    41
Remote:           (none yet — this guide walks you through adding one)
```

To verify this at any time:
```bash
cd /path/to/chf-theme
git status
git log --oneline
git tag
```

---

## Why a private repo?

You want **private** (not public) because:

- The repo contains the `chf-content-import.xml` with real page content.
- The theme is a client asset, not an open-source contribution.
- It lets you control who sees and edits the code.

**Cost:** private repos are free on GitHub, GitLab, and Bitbucket for small teams.

---

## Option A — Push to GitHub (most popular)

### Step 1: Create the repo on GitHub

1. Go to [github.com/new](https://github.com/new)
2. **Repository name:** `chf-theme`
3. **Description:** `Center for Houston's Future WordPress theme (Elementor Pro)`
4. **Visibility:** ⚠️ **Private** (critical — do not choose Public)
5. Leave **"Initialize this repository with"** options all **unchecked**
   — we already have commits locally, and GitHub would reject the push if
   you initialized with a README
6. Click **Create repository**

### Step 2: Copy the remote URL

After creating, GitHub shows a page with a URL like:
```
https://github.com/YOUR-USERNAME/chf-theme.git
```
or if using SSH:
```
git@github.com:YOUR-USERNAME/chf-theme.git
```

Use **SSH** if you've set up an SSH key. Otherwise use **HTTPS**.

### Step 3: Push from your local machine

```bash
cd /path/to/chf-theme

# Add the remote
git remote add origin https://github.com/YOUR-USERNAME/chf-theme.git

# Push the main branch + all tags
git push -u origin main
git push --tags
```

If asked to authenticate with HTTPS, use a **Personal Access Token** (not
your password — GitHub deprecated password auth in 2021). Generate one at:
[github.com/settings/tokens](https://github.com/settings/tokens) with the
`repo` scope.

### Step 4: Verify on GitHub

Refresh the GitHub page. You should see:
- 41 files
- The `v5.0.0` tag under **Releases** (GitHub auto-promotes tags to releases)
- The README (which is `docs/README.md` — GitHub auto-renders it on the repo page)

---

## Option B — Push to GitLab

### Step 1: Create the project

1. Go to [gitlab.com/projects/new](https://gitlab.com/projects/new)
2. Click **Create blank project**
3. **Project name:** `chf-theme`
4. **Visibility:** ⚠️ **Private**
5. ⚠️ Uncheck **Initialize repository with a README**
6. Click **Create project**

### Step 2: Push

```bash
cd /path/to/chf-theme

git remote add origin https://gitlab.com/YOUR-USERNAME/chf-theme.git
git push -u origin main
git push --tags
```

GitLab uses Personal Access Tokens the same way GitHub does — create at
**User Settings → Access Tokens**.

---

## Option C — Push to Bitbucket

### Step 1: Create the repo

1. Go to [bitbucket.org/repo/create](https://bitbucket.org/repo/create)
2. **Repository name:** `chf-theme`
3. **Access level:** ⚠️ **This is a private repository**
4. **Version control:** Git
5. ⚠️ Set **Include a README** to **No**
6. Click **Create repository**

### Step 2: Push

```bash
cd /path/to/chf-theme

git remote add origin https://bitbucket.org/YOUR-USERNAME/chf-theme.git
git push -u origin main
git push --tags
```

Bitbucket uses **App Passwords** for authentication — create at
**Personal settings → App passwords**.

---

## Invite your team

Once the repo is on a remote, add collaborators:

### GitHub
Repository → **Settings → Collaborators → Add people**

### GitLab
Project → **Members → Invite member**

### Bitbucket
Repository → **Repository settings → User and group access → Invite**

**Recommended roles:**
- **Admin:** primary maintainer (you, or the lead dev)
- **Write / Developer:** team members who make changes
- **Read / Reporter:** content editors who only need to view history

---

## Day-to-day workflow

Once the remote is set up, the normal flow is:

### Pull the latest before you start work
```bash
git pull
```

### Make changes, then stage them
```bash
git add .        # stage everything changed
# OR
git add path/to/specific/file.php
```

### Commit with a descriptive message
Follow the Conventional Commits format (see `docs/DEVELOPER-REFERENCE.md` § 12):
```bash
git commit -m "feat: add Spanish translation support"
git commit -m "fix: correct event date format in archive"
git commit -m "docs: update installation steps for PHP 8.3"
```

### Push to the remote
```bash
git push
```

### Pushing tags when cutting a new release
```bash
git tag -a v5.1.0 -m "Release v5.1.0 — Spanish translation"
git push --tags
```

---

## Branching strategy (optional but recommended)

For anything more than a one-person project:

### Simple flow — `main` + feature branches
```
main                ── always deployable
├── feature/xxx     ── new work
├── fix/xxx         ── bug fixes
└── hotfix/xxx      ── emergency production fixes
```

**Workflow:**
1. Create a feature branch: `git checkout -b feature/new-cpt`
2. Do the work, commit as you go
3. Push the branch: `git push -u origin feature/new-cpt`
4. Open a **Pull Request / Merge Request** on the remote
5. Have a teammate review it
6. Merge to `main`
7. Delete the feature branch

### More structured — Git Flow
Add a `develop` branch between `main` and features. Use when you have
multiple features in flight and want to stabilize before releases.
See [nvie.com/posts/a-successful-git-branching-model](https://nvie.com/posts/a-successful-git-branching-model/).

---

## Protecting the `main` branch

Once multiple people are committing, **protect `main`** so nothing lands
without review:

### GitHub
**Settings → Branches → Add branch protection rule**
- Branch name pattern: `main`
- ✅ Require a pull request before merging
- ✅ Require approvals (1 minimum)
- ✅ Require status checks to pass before merging (if you add CI)
- ✅ Require conversation resolution before merging
- ✅ Include administrators (no one bypasses the rules)

### GitLab
**Settings → Repository → Protected branches**
- Same idea, called "Merge request approvals"

### Bitbucket
**Repository settings → Branch restrictions**

---

## Backups

A remote git repo is **not a backup** — it's a source control system. You
still need proper off-site backups of the running WordPress site. See
`docs/MAINTENANCE.md` § 3 for the backup strategy.

That said, if your GitHub/GitLab repo is private and you push regularly, it
does serve as a de facto off-site backup of the theme code. Push often.

---

## What NOT to commit

The `.gitignore` already handles this, but the general rule is:

❌ **Never commit:**
- `wp-config.php` (contains DB credentials)
- `.env` files (contains API keys)
- `CREDENTIALS.md` (the filled-in version; template is OK)
- SSH private keys (`id_rsa`, `.pem` files)
- Database dumps (`.sql` files — too big, may contain PII)
- Backup zips (too big)
- `node_modules/` or `vendor/` (reinstallable from package files)
- `.DS_Store` or editor swap files

✅ **Always commit:**
- Theme source files (PHP, CSS, JS)
- Elementor template JSON files
- WXR content import files (they're public content)
- Documentation (markdown + generated PDF)
- `.gitignore` itself
- `CHANGELOG.md`

---

## Continuous deployment (optional, for power users)

Once the repo is on a remote, you can automate deployment so every push to
`main` updates the production WordPress site.

### Simple option: **GitHub Actions → SFTP**
Create `.github/workflows/deploy.yml`:
```yaml
name: Deploy to production
on:
  push:
    branches: [main]
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: SFTP deploy
        uses: SamKirkland/FTP-Deploy-Action@v4.3.5
        with:
          server: ${{ secrets.FTP_SERVER }}
          username: ${{ secrets.FTP_USER }}
          password: ${{ secrets.FTP_PASSWORD }}
          local-dir: ./
          server-dir: /wp-content/themes/chf-theme/
          exclude: |
            **/.git*
            **/.git*/**
            _archive_v4/**
            docs/**
            CHF-Technical-Documentation.pdf
```

Add the FTP credentials as **Secrets** in GitHub → Settings → Secrets.

### More robust: **GitHub Actions → rsync over SSH**
Uses SSH keys instead of FTP. Faster, more secure, atomic deploys.
Setup is similar but requires SSH key provisioning on the server.

**Do NOT set this up until you're comfortable with git and have tested
locally.** A misconfigured deploy workflow can overwrite production
unexpectedly. Manual deploys are fine for most sites.

---

## Quick command reference

```bash
# See what's changed
git status

# See commit history
git log --oneline
git log --all --graph --oneline

# See what a commit changed
git show <commit-hash>

# Revert the last commit (but keep the changes unstaged)
git reset HEAD~1

# Discard all local changes (careful)
git checkout -- .

# Fetch remote changes without merging
git fetch

# See remote URLs
git remote -v

# List all branches
git branch -a

# Switch branches
git checkout <branch-name>

# Create a new branch
git checkout -b <new-branch-name>

# Delete a local branch
git branch -d <branch-name>

# Create a tag on the current commit
git tag -a v5.1.0 -m "Release message"

# Push tags
git push --tags

# Pull latest from remote
git pull

# Push local to remote
git push
```

---

## If something goes wrong

### "I committed something I shouldn't have"
```bash
# Remove the last commit entirely (local only)
git reset --soft HEAD~1

# Unstage the bad file
git reset HEAD path/to/file

# Now you can re-commit without it
```

### "I committed secrets"
1. **Immediately rotate** the secrets that were exposed
2. Use `git filter-branch` or [BFG Repo-Cleaner](https://rtyley.github.io/bfg-repo-cleaner/) to remove from history
3. Force-push the cleaned history (`git push --force`)
4. Inform the team — they need to re-clone

### "I pushed to the wrong branch"
```bash
# Create the correct branch, move the commits, delete the wrong one
git branch correct-branch
git reset --hard origin/main  # or wherever it should have gone
git checkout correct-branch
git push origin correct-branch
```

### "I have a merge conflict"
1. Git tells you which files have conflicts
2. Open each file, look for `<<<<<<<` / `=======` / `>>>>>>>` markers
3. Pick the version you want (or merge manually)
4. Remove the markers
5. `git add <file>` to mark resolved
6. `git commit` to complete the merge

---

## Need help?

- **Git basics:** [git-scm.com/book](https://git-scm.com/book/en/v2) (free book)
- **Pro git:** [Atlassian Git tutorials](https://www.atlassian.com/git)
- **GitHub specifics:** [docs.github.com](https://docs.github.com)
- **GitLab specifics:** [docs.gitlab.com](https://docs.gitlab.com)
- **Stuck on a specific error:** search the exact error message on Stack Overflow — 9 times out of 10 someone has asked it before
