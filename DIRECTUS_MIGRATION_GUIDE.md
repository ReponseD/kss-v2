# Directus Migration Guide - Complete Setup

This guide will help you migrate from the Markdown-based CMS to Directus backend.

---

## 📋 Overview

**Old System:**
- Netlify CMS (Git-based)
- Markdown files in `content/` folder
- Build script converts MD → JSON
- Frontend loads from static JSON

**New System:**
- Directus backend (self-hosted on Render)
- SQLite database
- Directus admin panel for content management
- Frontend loads from Directus API

---

## 🚀 Step 1: Deploy Directus Backend

### Option A: Deploy to Render (Recommended - Free Tier)

1. **Create Render Account**
   - Go to https://render.com
   - Sign up with GitHub

2. **Create New Web Service**
   - Click "New +" → "Web Service"
   - Connect your GitHub repository
   - Select the `directus` folder as root directory

3. **Configure Service**
   - **Name**: `kss-directus` (or your preferred name)
   - **Environment**: Node
   - **Build Command**: `npm install`
   - **Start Command**: `npx directus start`
   - **Plan**: Free

4. **Set Environment Variables**
   ```
   KEY=<generate-random-string>
   SECRET=<generate-random-string>
   DB_CLIENT=sqlite3
   DB_FILENAME=./data/database.sqlite
   ADMIN_EMAIL=admin@kagaramasec.org
   ADMIN_PASSWORD=<your-secure-password>
   PORT=8055
   CORS_ENABLED=true
   CORS_ORIGIN=https://www.kagaramasec.org
   STORAGE_LOCATIONS=local
   STORAGE_LOCAL_ROOT=./uploads
   ```

5. **Deploy**
   - Click "Create Web Service"
   - Wait for deployment (5-10 minutes)
   - Note your Directus URL (e.g., `https://kss-directus.onrender.com`)

### Option B: Local Development

1. **Install Dependencies**
   ```bash
   cd directus
   npm install
   ```

2. **Create `.env` file**
   ```bash
   cp .env.example .env
   # Edit .env with your configuration
   ```

3. **Initialize Directus**
   ```bash
   npx directus bootstrap
   ```

4. **Start Directus**
   ```bash
   npm start
   ```

5. **Access Admin Panel**
   - Open http://localhost:8055
   - Login with your admin credentials

---

## 📊 Step 2: Create Collections in Directus

After logging into Directus admin panel:

### Collection: posts

1. Go to **Settings** → **Data Model** → **Create Collection**
2. Name: `posts`
3. Add fields:

| Field Name | Type | Options |
|------------|------|---------|
| `id` | UUID | Primary Key (auto) |
| `title` | String | Required |
| `slug` | String | Unique, Required |
| `author` | String | Default: "Media Club" |
| `content` | Text | Required (Markdown supported) |
| `excerpt` | Text | Optional |
| `featured_image` | File | Image, Optional |
| `category` | Dropdown | Options: News, Blog, Announcement |
| `draft` | Boolean | Default: false |
| `featured` | Boolean | Default: false |
| `date_published` | DateTime | Required |
| `date_created` | Timestamp | Auto |
| `date_updated` | Timestamp | Auto |

4. **Display Template**: `{{title}}`

### Collection: events

1. Create collection: `events`
2. Add fields:

| Field Name | Type | Options |
|------------|------|---------|
| `id` | UUID | Primary Key (auto) |
| `title` | String | Required |
| `slug` | String | Unique, Required |
| `description` | Text | Required (Markdown supported) |
| `event_date` | DateTime | Required |
| `end_date` | DateTime | Optional |
| `location` | String | Optional |
| `image` | File | Image, Optional |
| `event_type` | Dropdown | Options: Announcement, Event, Meeting, Workshop, Sports, Academic |
| `featured` | Boolean | Default: false |
| `date_created` | Timestamp | Auto |
| `date_updated` | Timestamp | Auto |

3. **Display Template**: `{{title}}`

### Collection: media

1. Create collection: `media`
2. Add fields:

| Field Name | Type | Options |
|------------|------|---------|
| `id` | UUID | Primary Key (auto) |
| `title` | String | Required |
| `slug` | String | Unique, Required |
| `description` | Text | Optional |
| `video_url` | String | Required |
| `video_type` | Dropdown | Options: YouTube, Vimeo |
| `thumbnail` | File | Image, Optional |
| `featured` | Boolean | Default: false |
| `date_created` | Timestamp | Auto |
| `date_updated` | Timestamp | Auto |

3. **Display Template**: `{{title}}`

---

## 🔐 Step 3: Configure Permissions

1. Go to **Settings** → **Roles & Permissions**
2. Select **Public** role
3. For each collection (`posts`, `events`, `media`):
   - Enable **Read** permission
   - Set filter: `{"draft": {"_eq": false}}` (for posts only)
4. Save permissions

---

## 🔗 Step 4: Update Frontend Configuration

1. **Update Directus URL**
   - Open `assets/js/directus-api.js`
   - Replace `https://your-directus-instance.onrender.com` with your actual Directus URL

2. **Test API Connection**
   - Open browser console on your website
   - Run: `DirectusAPI.getPosts()`
   - Should return array of posts

---

## 📝 Step 5: Migrate Existing Content (Optional)

If you have existing Markdown content:

1. **Export from Markdown**
   - Read files from `content/` folder
   - Parse frontmatter and content

2. **Import to Directus**
   - Use Directus admin panel
   - Or use Directus API to bulk import
   - See `directus/api-examples.js` for API examples

**Quick Import Script:**
```javascript
// Run in browser console on Directus admin panel
// After logging in, get your access token

const posts = [
  {
    title: "Your Title",
    slug: "your-slug",
    author: "Media Club",
    content: "Your content here...",
    category: "News",
    date_published: "2024-01-15T10:00:00Z",
    draft: false
  }
];

// Import each post via API
```

---

## 🧹 Step 6: Clean Up Old Files

### Files to Remove:

1. **Netlify CMS Files:**
   - `admin/index.html` (Netlify CMS interface)
   - `admin/config.yml` (Netlify CMS config)
   - `admin/config-alternative.yml`

2. **Build Scripts:**
   - `scripts/build-content.js`
   - `package.json` (root level, if only used for build)

3. **Content Folder (Optional):**
   - `content/` folder (if you've migrated all content)

4. **Generated Files:**
   - `assets/data/content.json` (no longer needed)

### Files to Keep:

- All HTML files (frontend)
- `assets/js/directus-api.js` (updated)
- `vercel.json` (can remove build command)

---

## ✅ Step 7: Verify Everything Works

### Checklist:

- [ ] Directus backend deployed and accessible
- [ ] Can login to Directus admin panel
- [ ] Collections created (posts, events, media)
- [ ] Permissions configured (public read access)
- [ ] Frontend updated with Directus URL
- [ ] `Updates.html` loads content from Directus
- [ ] `UpdateDetail.html` displays individual posts
- [ ] Images load correctly
- [ ] Old CMS files removed

### Test URLs:

1. **Directus Admin**: `https://your-directus-url.onrender.com/admin`
2. **Directus API**: `https://your-directus-url.onrender.com/items/posts`
3. **Website Updates**: `https://www.kagaramasec.org/Updates.html`
4. **Website Detail**: `https://www.kagaramasec.org/UpdateDetail.html?slug=test-slug`

---

## 🔄 Workflow After Migration

### Content Editor Workflow:

1. **Login to Directus**
   - Go to `https://your-directus-url.onrender.com/admin`
   - Login with email/password

2. **Create/Edit Content**
   - Navigate to collection (Posts, Events, Media)
   - Click "Create Item" or edit existing
   - Fill in fields
   - Save

3. **Content Goes Live**
   - Changes are immediately available via API
   - Frontend fetches from Directus API
   - No build process needed!

### No More:
- ❌ Git commits for content
- ❌ Build scripts
- ❌ Markdown files
- ❌ JSON generation

### Now:
- ✅ Direct database access
- ✅ Real-time updates
- ✅ Rich admin interface
- ✅ Built-in image management
- ✅ User management
- ✅ Permissions system

---

## 🐛 Troubleshooting

### Directus Not Starting

**Error**: Database connection failed
- **Fix**: Check `DB_FILENAME` path is correct
- **Fix**: Ensure data directory exists

**Error**: Port already in use
- **Fix**: Change `PORT` in environment variables

### Frontend Can't Load Content

**Error**: CORS error
- **Fix**: Check `CORS_ORIGIN` includes your website URL
- **Fix**: Verify `CORS_ENABLED=true`

**Error**: 404 on API calls
- **Fix**: Verify Directus URL is correct in `directus-api.js`
- **Fix**: Check collection names match (posts, events, media)

### Images Not Loading

**Error**: Image URLs return 404
- **Fix**: Check file storage configuration
- **Fix**: Verify `STORAGE_LOCAL_ROOT` path
- **Fix**: Ensure files are uploaded via Directus admin

---

## 📚 Additional Resources

- **Directus Documentation**: https://docs.directus.io
- **Directus API Reference**: https://docs.directus.io/reference/introduction
- **Render Documentation**: https://render.com/docs

---

## 🎉 Success!

After completing these steps, you'll have:
- ✅ Self-hosted Directus backend
- ✅ Professional admin panel
- ✅ Real-time content updates
- ✅ No build process needed
- ✅ Better content management experience

Your CMS is now production-ready with Directus!

