# Netlify CMS Troubleshooting Guide

## Error: Failed to load config.yml (404)

### Solution 1: Check File Location

Ensure `config.yml` is in the `admin/` folder:
```
admin/
  ├── index.html
  └── config.yml  ← Must be here
```

### Solution 2: Verify File is Committed

1. Check if `admin/config.yml` is in your Git repository
2. Commit and push if it's not:
   ```bash
   git add admin/config.yml
   git commit -m "Add config.yml"
   git push
   ```

### Solution 3: Check Vercel Deployment

1. Go to Vercel dashboard
2. Check if `admin/config.yml` is in the deployment
3. Visit: `https://www.kagaramasec.org/admin/config.yml` directly
4. If you get 404, the file isn't being deployed

### Solution 4: Verify File Permissions

Make sure the file is readable and has proper permissions.

### Solution 5: Check Branch Name

In `admin/config.yml`, verify the branch name matches your repository:
```yaml
backend:
  name: git-gateway
  branch: main  # ← Make sure this matches your repo branch
```

### Solution 6: Clear Browser Cache

1. Hard refresh: `Ctrl+Shift+R` (Windows) or `Cmd+Shift+R` (Mac)
2. Or clear browser cache completely

### Solution 7: Check Browser Console

1. Open browser developer tools (F12)
2. Go to Console tab
3. Look for specific error messages
4. Check Network tab to see if config.yml request is failing

### Solution 8: Verify Vercel Configuration

The `vercel.json` file should be in the root directory and configured correctly.

---

## Still Not Working?

### Alternative: Inline Config

If the file still won't load, you can inline the config directly in `admin/index.html`:

1. Copy the contents of `admin/config.yml`
2. Add it as a script in `admin/index.html`:

```html
<script>
  CMS.init({
    config: {
      backend: {
        name: "git-gateway",
        branch: "main"
      },
      media_folder: "assets/uploads",
      public_folder: "/assets/uploads",
      // ... rest of config
    }
  });
</script>
```

---

## Common Issues

### Issue: "Git Gateway not enabled"

**Solution:** Enable Git Gateway in Netlify dashboard:
- Site Settings → Identity → Services → Git Gateway → Enable

### Issue: "Authentication failed"

**Solution:** 
- Check Netlify Identity is enabled
- Verify user email is confirmed
- Try logging out and back in

### Issue: "Cannot commit to repository"

**Solution:**
- Verify repository is connected
- Check branch name is correct
- Ensure user has write permissions

---

## Quick Checklist

- [ ] `admin/config.yml` exists
- [ ] File is committed to Git
- [ ] File is deployed to Vercel
- [ ] Branch name in config matches repository
- [ ] Netlify Identity is enabled
- [ ] Git Gateway is enabled
- [ ] Browser cache is cleared
- [ ] `vercel.json` is configured

---

**Need more help?** Check the main setup guide: `NETLIFY_CMS_SETUP.md`

