# Quick Start Guide - KSS Updates System

## 5-Minute Setup

### Step 1: Database Setup (2 minutes)
```bash
# 1. Create database
mysql -u root -p
CREATE DATABASE kss_updates CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# 2. Import schema
mysql -u root -p kss_updates < database/schema.sql
```

### Step 2: Configuration (1 minute)
Edit `config.php` (main config file):
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'kss_updates');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('APP_URL', 'https://www.kagaramasec.org');
```

### Step 3: Permissions (1 minute)
```bash
mkdir -p uploads/gallery
chmod 755 uploads/
chmod 755 uploads/gallery/
```

### Step 4: First Login (1 minute)
1. Go to: `https://www.kagaramasec.org/admin/login.php`
2. Login with:
   - Username: `admin`
   - Password: `admin123`
3. **Change password immediately!**

### Step 5: Edit Homepage (Optional)
1. Click **"Homepage"** in sidebar
2. Edit sections (titles, descriptions)
3. Add/edit banners for hero carousel
4. Changes appear on homepage immediately

---

## Your First Content

### Create Your First News Article

1. **Login** to admin panel
2. Click **"News"** in sidebar
3. Click **"Create News"** button
4. Fill in:
   - Title: "Welcome to KSS Updates"
   - Content: Write your article
   - Status: Select "Published"
5. Click **"Publish"**

### Upload Your First Image

1. Click **"Gallery"** in sidebar
2. Click **"Upload Image"** button
3. Choose an image file
4. Fill in:
   - Title: "School Building"
   - Alt Text: "KSS Main Building"
   - Category: Select a category
5. Click **"Upload"**

---

## Daily Workflow

### Morning Routine
1. Check dashboard for statistics
2. Review drafts
3. Publish scheduled content

### Creating Content
1. Click appropriate section (News/Blogs/Announcements)
2. Click "Create" button
3. Fill in form
4. Save as draft or publish

### Managing Gallery
1. Click "Gallery"
2. Upload new images
3. Organize by categories
4. Set featured images

---

## Common Tasks

### Publishing an Announcement
```
1. Admin Panel → Announcements
2. Create Announcement
3. Enter title and content
4. Select category
5. Set status to "Published"
6. Click "Publish"
```

### Adding Gallery Images
```
1. Admin Panel → Gallery
2. Upload Image
3. Select file
4. Add title and description
5. Choose category
6. Click "Upload"
```

### Editing Existing Content
```
1. Navigate to content section
2. Find item in list
3. Click "Edit" button
4. Make changes
5. Click "Update"
```

---

## Important Reminders

✅ **Always**:
- Save drafts before publishing
- Add alt text to images
- Use appropriate categories
- Proofread before publishing

❌ **Never**:
- Delete content without backup
- Upload very large files (>10MB)
- Share admin credentials
- Publish without reviewing

---

## Need Help?

- Full guide: See `USER_GUIDE.md`
- Setup help: See `README_SETUP.md`
- Technical issues: Check error logs

---

**You're ready to go!** Start creating content and building your gallery.

