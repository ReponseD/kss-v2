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
2. Click **"New OAuth App"**
3. Fill in:
   - **Application name**: `KSS CMS`
   - **Homepage URL**: `https://www.kagaramasec.org`
   - **Authorization callback URL**: `https://api.netlify.com/auth/done` ⚠️ **Must be exactly this!**
4. Click **"Register application"**
5. **Copy the Client ID** (you'll need this)
6. Click **"Generate a new client secret"**
7. **Copy the Client Secret** (save it securely - you can only see it once!)

### Step 4: Configure Netlify (for OAuth only)

**IMPORTANT:** Even though you host on Vercel, you need Netlify for OAuth authentication.

1. Go to https://app.netlify.com
2. Click **"Add new site"** → **"Import an existing project"**
3. Connect GitHub and select repository: **ReponseD/kss-v2**
4. Click **"Deploy site"** (build settings don't matter - this is just for OAuth)
5. Once deployed, go to: **Site Settings → Identity**
6. Click **"Enable Identity"**
7. Go to: **Services → GitHub**
8. Enter your **Client ID** and **Client Secret** (from Step 3)
9. Click **"Save"**

**Note:** Your Netlify site URL doesn't need to match your domain - it's just for OAuth.

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

