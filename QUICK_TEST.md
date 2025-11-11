# ⚡ Quick Test Guide

## 🎯 Test the CMS Now

### 1. Update GitHub OAuth App (2 minutes)

Go to: https://github.com/settings/developers

**Set callback URL to:**
```
https://www.kagaramasec.org/admin/index.html
```

### 2. Test (1 minute)

1. Visit: `https://www.kagaramasec.org/admin`
2. Click "Login with GitHub"
3. **Check the redirect URL** - should be:
   ```
   https://github.com/login/oauth/authorize?client_id=...&redirect_uri=https://www.kagaramasec.org/admin/index.html&scope=repo
   ```
4. **NO duplication** should appear
5. After login, CMS should load

### 3. Verify (30 seconds)

- ✅ No URL duplication
- ✅ Redirects to GitHub correctly
- ✅ Redirects back after authorization
- ✅ CMS loads with collections

---

**If it works:** ✅ Success! CMS is ready.

**If URL still duplicates:** Clear cache and test in incognito window.

---

**Current Config:** Direct GitHub OAuth (cleanest approach)
**Status:** Ready to test

