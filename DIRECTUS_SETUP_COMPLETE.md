# Directus Setup - Complete Reference

## 🎯 Quick Reference

### Directus Admin URL
```
https://your-directus-instance.onrender.com/admin
```

### Directus API Base URL
```
https://your-directus-instance.onrender.com
```

### API Endpoints

**Get Posts:**
```
GET /items/posts?filter[draft][_eq]=false&sort[]=-date_published
```

**Get Single Post:**
```
GET /items/posts?filter[slug][_eq]=your-slug
```

**Get Events:**
```
GET /items/events?sort[]=-event_date
```

**Get Media:**
```
GET /items/media?sort[]=-date_created
```

---

## 📋 Collections Summary

### posts
- News articles, blog posts, announcements
- Fields: title, slug, author, content, excerpt, featured_image, category, draft, featured, date_published

### events
- School events and announcements
- Fields: title, slug, description, event_date, end_date, location, image, event_type, featured

### media
- Videos and media content
- Fields: title, slug, description, video_url, video_type, thumbnail, featured

---

## 🔐 Default Admin Credentials

**Email:** `admin@kagaramasec.org`  
**Password:** (set during deployment)

**Change these immediately after first login!**

---

## 🔧 Environment Variables Reference

```bash
# Database
DB_CLIENT=sqlite3
DB_FILENAME=./data/database.sqlite

# Directus
KEY=<random-string>
SECRET=<random-string>

# Admin
ADMIN_EMAIL=admin@kagaramasec.org
ADMIN_PASSWORD=<secure-password>

# Server
PORT=8055
PUBLIC_URL=https://your-directus-instance.onrender.com

# CORS
CORS_ENABLED=true
CORS_ORIGIN=https://www.kagaramasec.org

# Storage
STORAGE_LOCATIONS=local
STORAGE_LOCAL_ROOT=./uploads
```

---

## 📱 Frontend Integration

### Update Directus URL

Edit `assets/js/directus-api.js`:
```javascript
const DIRECTUS_URL = 'https://your-directus-instance.onrender.com';
```

### Usage in Frontend

```javascript
// Get all posts
const posts = await DirectusAPI.getPosts();

// Get posts by category
const news = await DirectusAPI.getPosts('News');
const blogs = await DirectusAPI.getPosts('Blog');

// Get single post
const post = await DirectusAPI.getPostBySlug('my-post-slug');

// Get events
const events = await DirectusAPI.getEvents();

// Get image URL
const imageUrl = DirectusAPI.getImageUrl(fileId, width, height);
```

---

## 🚀 Deployment Checklist

- [ ] Directus deployed on Render
- [ ] Environment variables configured
- [ ] Admin user created
- [ ] Collections created (posts, events, media)
- [ ] Permissions configured (public read access)
- [ ] Frontend updated with Directus URL
- [ ] Test API endpoints
- [ ] Test frontend integration
- [ ] Remove old CMS files
- [ ] Update documentation

---

## 🆘 Support

**Directus Documentation:** https://docs.directus.io  
**Render Support:** https://render.com/docs  
**API Examples:** See `directus/api-examples.js`

---

**Setup Complete!** 🎉

