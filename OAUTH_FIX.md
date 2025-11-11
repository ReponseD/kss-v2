# ✅ Fixed: URL Duplication in OAuth

## Problem
The authentication URL was being duplicated:
```
https://api.netlify.com/https://api.netlify.com/auth/done?provider=github&...
```

## Root Cause
The `base_url` field in the `backend` section was being prepended to `auth_endpoint`, causing duplication.

## Solution
**Removed `base_url` from the backend section** in `admin/config.yml`.

### Before (❌ Wrong):
```yaml
backend:
  name: github
  repo: ReponseD/kss-v2
  branch: main
  base_url: https://www.kagaramasec.org  # ← This caused duplication!
  auth_endpoint: https://api.netlify.com/auth/done
```

### After (✅ Correct):
```yaml
backend:
  name: github
  repo: ReponseD/kss-v2
  branch: main
  auth_scope: repo
  # DO NOT set base_url here - it causes URL duplication
  auth_endpoint: https://api.netlify.com/auth/done
```

## Why This Happens
- For **GitHub backend**, Netlify CMS doesn't need `base_url` in the backend section
- The `auth_endpoint` must be a **complete URL** (not relative)
- When `base_url` is set, Netlify CMS tries to combine it with `auth_endpoint`, causing duplication

## What's Still There
The `site_url` at the root level is **fine** - it's used for other purposes (like generating preview URLs), not for authentication:
```yaml
site_url: https://www.kagaramasec.org  # ← This is OK, used for previews
```

## Testing
After this fix:
1. Clear browser cache
2. Visit `/admin`
3. Click "Login with GitHub"
4. The URL should now be: `https://api.netlify.com/auth/done?provider=github&...` (no duplication)

---

**Status**: ✅ Fixed
**Date**: $(date)

