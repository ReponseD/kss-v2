# 🧪 CMS Testing & Verification Guide

## ✅ Configuration Status

### Current Setup: Direct GitHub OAuth

```yaml
backend:
  name: github
  repo: ReponseD/kss-v2
  branch: main
  auth_scope: repo
  # No auth_endpoint or proxy_url - direct GitHub OAuth
```

**This configuration:**
- ✅ Uses direct GitHub OAuth (no Netlify proxy)
- ✅ Avoids URL duplication issues
- ✅ Works with Vercel hosting
- ✅ Simpler setup

## 🔧 Required GitHub OAuth App Configuration

### Step 1: Create/Update GitHub OAuth App

1. Go to: https://github.com/settings/developers
2. Click **"New OAuth App"** (or edit existing)
3. Configure:
   ```
   Application name: KSS CMS
   Homepage URL: https://www.kagaramasec.org
   Authorization callback URL: https://www.kagaramasec.org/admin/index.html
   ```
4. Click **"Register application"**
5. **Copy Client ID** (you'll need this)
6. **Generate Client Secret** (save securely)

### Step 2: Update Netlify CMS Config (if needed)

The current config should work, but if you need to specify the callback explicitly, you can add:

```yaml
backend:
  name: github
  repo: ReponseD/kss-v2
  branch: main
  auth_scope: repo
  # Optional: Explicitly set callback (usually not needed)
  # base_url: https://www.kagaramasec.org
```

**Note:** For direct GitHub OAuth, Netlify CMS automatically uses the current page URL as the callback.

## 🧪 Testing Steps

### Test 1: Verify Config File is Accessible

1. Visit: `https://www.kagaramasec.org/admin/config.yml`
2. Should see YAML content (not 404)
3. Check Content-Type header is `text/yaml`

### Test 2: Verify Admin Page Loads

1. Visit: `https://www.kagaramasec.org/admin`
2. Should see Netlify CMS interface
3. Should see "Login with GitHub" button
4. Check browser console for errors

### Test 3: Test Authentication Flow

1. Click "Login with GitHub"
2. **Expected redirect:** `https://github.com/login/oauth/authorize?client_id=...&redirect_uri=https://www.kagaramasec.org/admin/index.html&scope=repo`
3. Authorize the application
4. **Expected redirect back:** `https://www.kagaramasec.org/admin/index.html?code=...`
5. CMS should load with content collections

### Test 4: Test Content Creation

1. After login, click "New Post"
2. Fill in form fields
3. Click "Publish"
4. Should create commit in GitHub
5. Vercel should auto-rebuild

## 🐛 Common Issues & Fixes

### Issue: "Config file not found (404)"
**Fix:**
- Verify `admin/config.yml` is committed to Git
- Check Vercel deployment includes the file
- Verify `vercel.json` headers are correct

### Issue: "Login with GitHub" doesn't appear
**Fix:**
- Check browser console for errors
- Verify config.yml is valid YAML
- Check Netlify CMS script is loading

### Issue: Redirect URL mismatch
**Fix:**
- Verify GitHub OAuth App callback URL matches exactly:
  `https://www.kagaramasec.org/admin/index.html`
- Check for trailing slashes
- Ensure HTTPS (not HTTP)

### Issue: "Repository not found"
**Fix:**
- Verify repo name: `ReponseD/kss-v2`
- Check repository is public (or user has access)
- Verify branch name: `main`

### Issue: "Cannot commit"
**Fix:**
- Verify user has write access to repository
- Check OAuth App has `repo` scope
- Verify branch protection rules allow commits

## ✅ Verification Checklist

- [ ] `admin/config.yml` exists and is valid YAML
- [ ] `admin/index.html` loads Netlify CMS script
- [ ] `vercel.json` has correct routing
- [ ] GitHub OAuth App created/updated
- [ ] Callback URL matches: `https://www.kagaramasec.org/admin/index.html`
- [ ] Repository is public (or users have access)
- [ ] Branch name is `main`
- [ ] No `base_url` in backend section
- [ ] No `site_url` at root level
- [ ] No `proxy_url` or `auth_endpoint` (direct OAuth)

## 🎯 Expected Behavior

### Successful Flow:
1. User visits `/admin`
2. Sees CMS interface with "Login with GitHub"
3. Clicks button → Redirects to GitHub
4. Authorizes → Redirects back to `/admin/index.html?code=...`
5. CMS loads with collections visible
6. Can create/edit content
7. Changes commit to GitHub
8. Vercel rebuilds automatically

### URL Flow:
```
/admin
  ↓
GitHub OAuth: https://github.com/login/oauth/authorize?...
  ↓
Callback: https://www.kagaramasec.org/admin/index.html?code=...
  ↓
CMS Interface (authenticated)
```

## 📝 Next Steps

1. **Update GitHub OAuth App** with correct callback URL
2. **Test the flow** using the steps above
3. **Check browser console** for any errors
4. **Verify commits** appear in GitHub after publishing

---

**Status**: Ready for Testing
**Configuration**: Direct GitHub OAuth
**No Netlify Dependency**: ✅

