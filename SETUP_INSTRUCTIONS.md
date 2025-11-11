# Quick Setup Instructions - GitHub OAuth

## ⚠️ IMPORTANT: You're on Vercel, Not Netlify!

Since your site is hosted on **Vercel**, you **cannot** use Netlify Identity + Git Gateway. You **must** use GitHub OAuth.

---

## 🚀 Quick Setup (5 Steps)

### Step 1: Get Your GitHub Repository Name

Find your repository name. It should look like: `username/repo-name`

**Example:** `kagaramasec/kss-v2`

### Step 2: Update config.yml

Open `admin/config.yml` and replace this line:

```yaml
repo: your-username/your-repo-name
```

With your actual repository:

```yaml
repo: kagaramasec/kss-v2  # ← Your actual repo
```

### Step 3: Create GitHub OAuth App

1. Go to: https://github.com/settings/developers
2. Click "New OAuth App"
3. Fill in:
   - **Name**: "KSS CMS"
   - **Homepage URL**: `https://www.kagaramasec.org`
   - **Callback URL**: `https://api.netlify.com/auth/done`
4. Click "Register application"
5. **Copy the Client ID**
6. Click "Generate a new client secret"
7. **Copy the Client Secret**

### Step 4: Configure Netlify (for OAuth only)

1. Go to https://app.netlify.com
2. Add your GitHub repository (just for OAuth, not hosting)
3. Go to: **Site Settings → Identity → Services → GitHub**
4. Enter your **Client ID** and **Client Secret**
5. Save

### Step 5: Commit and Deploy

1. Commit your updated `config.yml`:
   ```bash
   git add admin/config.yml
   git commit -m "Configure GitHub OAuth for CMS"
   git push
   ```

2. Wait for Vercel to deploy

3. Visit: https://www.kagaramasec.org/admin

4. Click "Login with GitHub"

5. Authorize and start using the CMS!

---

## ✅ That's It!

Once set up, users will:
- Click "Login with GitHub" 
- Authorize the app
- Get access to the CMS
- All changes commit to GitHub automatically
- Vercel rebuilds the site automatically

---

## 📝 Need Help?

See `GITHUB_OAUTH_SETUP.md` for detailed instructions.

