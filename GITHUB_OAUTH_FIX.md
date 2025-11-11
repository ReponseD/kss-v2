# Fix: GitHub OAuth "Not Found" Error

## The Problem

When clicking "Login with GitHub", you get a "Not Found" error. This happens because:

1. **GitHub OAuth App not created yet** - You need to create it first
2. **Netlify OAuth proxy not configured** - You need Netlify for the OAuth flow (even though you host on Vercel)

## Solution: Two-Step Setup

### Step 1: Create GitHub OAuth App

1. Go to: https://github.com/settings/developers
2. Click **"New OAuth App"**
3. Fill in:
   - **Application name**: `KSS CMS`
   - **Homepage URL**: `https://www.kagaramasec.org`
   - **Authorization callback URL**: `https://api.netlify.com/auth/done`
4. Click **"Register application"**
5. **Copy the Client ID** (you'll need this)
6. Click **"Generate a new client secret"**
7. **Copy the Client Secret** (save it securely!)

### Step 2: Set Up Netlify OAuth Proxy

Even though you host on Vercel, you need Netlify for the OAuth authentication:

1. Go to: https://app.netlify.com
2. Click **"Add new site"** → **"Import an existing project"**
3. Connect your GitHub account
4. Select repository: **ReponseD/kss-v2**
5. Click **"Deploy site"** (don't worry about build settings - this is just for OAuth)
6. Once deployed, go to: **Site Settings → Identity**
7. Click **"Enable Identity"**
8. Go to: **Services → GitHub**
9. Enter:
   - **Client ID**: (from Step 1)
   - **Client Secret**: (from Step 1)
10. Click **"Save"**

### Step 3: Get Your Netlify Site URL

1. In Netlify dashboard, note your site URL (e.g., `https://random-name-123.netlify.app`)
2. This is your OAuth proxy - it doesn't need to match your domain

### Step 4: Update Config (Optional - Already Done)

The `admin/config.yml` is already configured correctly. Just make sure it has:

```yaml
backend:
  name: github
  repo: ReponseD/kss-v2
  branch: main
  auth_endpoint: https://api.netlify.com/auth/done
```

### Step 5: Test

1. Visit: https://www.kagaramasec.org/admin
2. Click **"Login with GitHub"**
3. Authorize the application
4. You should now have access!

---

## Alternative: Direct GitHub OAuth (Advanced)

If you don't want to use Netlify at all, you can set up direct GitHub OAuth, but it requires:

1. Custom auth endpoint on your server
2. More complex setup
3. Not recommended for beginners

**Recommendation:** Use the Netlify OAuth proxy (Steps 1-2 above) - it's much simpler!

---

## Troubleshooting

### Still Getting "Not Found"?

1. **Check OAuth App callback URL**: Must be exactly `https://api.netlify.com/auth/done`
2. **Check Netlify GitHub service**: Make sure Client ID and Secret are saved
3. **Clear browser cache**: Hard refresh (Ctrl+Shift+R)
4. **Check repository access**: Make sure your GitHub account has access to `ReponseD/kss-v2`
5. **Verify repository is public**: Or ensure OAuth app has access to private repos

### "Authorization callback mismatch"

- The callback URL in GitHub OAuth app must match exactly: `https://api.netlify.com/auth/done`
- No trailing slashes, no variations

### "Repository not found"

- Make sure the repository name in `config.yml` is correct: `ReponseD/kss-v2`
- Verify the repository exists and is accessible
- If private, ensure OAuth app has access

---

## Quick Checklist

- [ ] GitHub OAuth App created
- [ ] Callback URL set to: `https://api.netlify.com/auth/done`
- [ ] Netlify site created (for OAuth proxy)
- [ ] Netlify Identity enabled
- [ ] GitHub service configured in Netlify
- [ ] Client ID and Secret entered correctly
- [ ] `config.yml` has correct repo name
- [ ] Repository is accessible
- [ ] Browser cache cleared

---

**Once these steps are complete, the CMS should work!** 🎉

