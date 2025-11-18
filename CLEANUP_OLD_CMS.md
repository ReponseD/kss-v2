# Cleanup Guide - Remove Old CMS Files

This guide helps you safely remove the old Markdown-based CMS files.

---

## ⚠️ Before You Start

1. **Backup Everything**
   - Make sure you've migrated all content to Directus
   - Export any important data
   - Commit current state to Git

2. **Verify Directus Works**
   - Test that Directus backend is running
   - Verify frontend loads content from Directus
   - Ensure all collections are set up

---

## 🗑️ Files to Remove

### 1. Netlify CMS Files

```bash
# Remove Netlify CMS admin interface
rm admin/index.html
rm admin/config.yml
rm admin/config-alternative.yml
```

**Note**: You can keep the `admin/` folder if you want to add a redirect to Directus later.

### 2. Build Scripts

```bash
# Remove build script
rm scripts/build-content.js

# Remove scripts directory if empty
rmdir scripts
```

### 3. Package.json (if only used for build)

```bash
# Check if package.json is only for build
# If yes, remove it:
rm package.json
```

**Note**: Keep it if you have other npm scripts you use.

### 4. Generated JSON File

```bash
# Remove generated content JSON
rm assets/data/content.json

# Remove data directory if empty
rmdir assets/data
```

### 5. Content Folder (Optional)

**Only remove if you've migrated all content to Directus:**

```bash
# Remove Markdown content files
rm -rf content/
```

**Recommendation**: Keep `content/` as backup for now. You can remove it later.

---

## 📝 Update vercel.json

Remove the build command since we no longer need it:

```json
{
  "rewrites": [
    {
      "source": "/admin",
      "destination": "/admin/index.html"
    }
  ],
  "headers": [
    {
      "source": "/admin/config.yml",
      "headers": [
        {
          "key": "Content-Type",
          "value": "text/yaml; charset=utf-8"
        }
      ]
    }
  ]
}
```

**Or remove vercel.json entirely** if you don't need these rewrites.

---

## 🔄 Alternative: Keep Files for Reference

If you want to keep files for reference but not use them:

1. **Create archive folder:**
   ```bash
   mkdir _archive
   mv admin/index.html _archive/
   mv admin/config.yml _archive/
   mv scripts/ _archive/
   mv content/ _archive/
   ```

2. **Add to .gitignore:**
   ```
   _archive/
   ```

---

## ✅ Verification Checklist

After cleanup, verify:

- [ ] Directus backend still works
- [ ] Frontend loads content from Directus API
- [ ] No broken links or missing files
- [ ] Git repository is clean
- [ ] Vercel deployment succeeds

---

## 🚀 Final Steps

1. **Commit Changes:**
   ```bash
   git add .
   git commit -m "Remove old CMS files, migrate to Directus"
   git push
   ```

2. **Update Documentation:**
   - Remove references to Netlify CMS
   - Update README with Directus info
   - Update any setup guides

3. **Test Everything:**
   - Test content creation in Directus
   - Verify frontend displays content
   - Check all pages load correctly

---

## 📚 Files Structure After Cleanup

```
kss-v2/
├── directus/              # Directus backend (new)
│   ├── package.json
│   ├── render.yaml
│   └── ...
├── assets/
│   └── js/
│       └── directus-api.js  # Updated for Directus
├── Updates.html            # Updated for Directus API
├── UpdateDetail.html       # Updated for Directus API
├── index.html
├── About.html
└── ... (other HTML files)
```

**Removed:**
- ❌ `admin/index.html` (Netlify CMS)
- ❌ `admin/config.yml`
- ❌ `scripts/build-content.js`
- ❌ `assets/data/content.json`
- ❌ `content/` (optional, after migration)

---

## 🎉 Done!

Your project is now clean and using Directus exclusively!

