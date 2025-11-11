# Production Deployment Guide
## KSS CMS - Converting Static Site to Dynamic CMS

This guide will help you deploy the KSS CMS to your live website at **https://www.kagaramasec.org/**

---

## Pre-Deployment Checklist

- [ ] Backup your current website
- [ ] Ensure PHP 7.4+ is installed on server
- [ ] Ensure MySQL 5.7+ is available
- [ ] Have database credentials ready
- [ ] Have FTP/cPanel access to server

---

## Step 1: Database Setup

### On Your Server (via cPanel or SSH)

1. **Create Database**
   ```sql
   CREATE DATABASE kss_updates CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Create Database User**
   ```sql
   CREATE USER 'kss_admin'@'localhost' IDENTIFIED BY 'your_strong_password';
   GRANT ALL PRIVILEGES ON kss_updates.* TO 'kss_admin'@'localhost';
   FLUSH PRIVILEGES;
   ```

3. **Import Schema**
   - Via phpMyAdmin: Import `database/schema.sql`
   - Via SSH: `mysql -u kss_admin -p kss_updates < database/schema.sql`

---

## Step 2: Upload Files to Server

### File Structure to Upload

```
www.kagaramasec.org/
├── admin/              (Upload entire folder)
├── api/                (Upload entire folder)
├── assets/             (Keep existing)
├── config/             (Upload entire folder)
├── database/           (Upload schema.sql only)
├── uploads/            (Create folder, set permissions 755)
│   └── gallery/        (Create folder, set permissions 755)
├── config.php          (IMPORTANT: Edit before upload)
├── index.php           (Replace existing index.html)
├── Updates.html        (New file)
├── Gallery.html         (Updated file)
├── UpdateDetail.html    (New file)
└── .htaccess           (Upload)
```

### Important Notes:
- **Keep existing HTML files** (About.html, Contact.html, etc.)
- **Replace index.html** with index.php (or rename index.html to index.html.bak)
- **Upload all new files** from the project

---

## Step 3: Configure config.php

### Edit Before Uploading:

```php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'kss_updates');
define('DB_USER', 'kss_admin');  // Your database username
define('DB_PASS', 'your_strong_password');  // Your database password

// Application Configuration
define('APP_URL', 'https://www.kagaramasec.org');
```

### Security:
- **Never commit config.php** with real credentials to version control
- Use strong database passwords
- Restrict file permissions: `chmod 600 config.php`

---

## Step 4: Set File Permissions

### Via SSH or cPanel File Manager:

```bash
# Uploads directory (must be writable)
chmod 755 uploads/
chmod 755 uploads/gallery/

# Config file (readable by web server only)
chmod 600 config.php

# PHP files (standard permissions)
chmod 644 *.php
chmod 644 admin/*.php
chmod 644 api/*.php
```

---

## Step 5: Update .htaccess

### Ensure .htaccess includes:

```apache
# Force HTTPS (uncomment for production)
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Protect config.php
<FilesMatch "^config\.php$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

---

## Step 6: Test Installation

### 1. Test Database Connection
- Visit: `https://www.kagaramasec.org/admin/login.php`
- Should see login page (not database errors)

### 2. Test Login
- Username: `admin`
- Password: `admin123`
- Should redirect to dashboard

### 3. Test Homepage
- Visit: `https://www.kagaramasec.org/`
- Should load dynamic content from database

### 4. Test Content Creation
- Create a test news article
- Verify it appears on Updates page

---

## Step 7: Security Hardening

### 1. Change Default Password
```sql
-- Generate new password hash using PHP:
-- $hash = password_hash('your_new_password', PASSWORD_DEFAULT);

UPDATE users 
SET password_hash = '$2y$10$...your_new_hash...' 
WHERE username = 'admin';
```

### 2. Disable Error Display
In `config.php`:
```php
ini_set('display_errors', 0);
error_reporting(0);  // In production
```

### 3. Enable HTTPS
- Ensure SSL certificate is active
- Update .htaccess to force HTTPS
- Update APP_URL to use https://

### 4. Protect Admin Directory
Add to `admin/.htaccess`:
```apache
# Restrict admin access (optional - IP whitelist)
# Order Deny,Allow
# Deny from all
# Allow from YOUR_IP_ADDRESS
```

---

## Step 8: Post-Deployment

### 1. Initial Content Setup
- Login to admin panel
- Update homepage sections with actual content
- Upload homepage banners
- Create initial news/announcements
- Upload gallery images

### 2. Test All Features
- [ ] Create news article → Verify appears on site
- [ ] Upload gallery image → Verify appears in gallery
- [ ] Edit homepage section → Verify changes reflect
- [ ] Test on mobile devices
- [ ] Test on different browsers

### 3. Monitor
- Check error logs regularly
- Monitor database size
- Check uploads folder size
- Review activity logs in admin panel

---

## Troubleshooting

### Issue: "Database connection failed"
**Solution:**
- Verify database credentials in config.php
- Check database user has proper permissions
- Ensure database exists

### Issue: "Permission denied" on uploads
**Solution:**
```bash
chmod 755 uploads/
chmod 755 uploads/gallery/
chown www-data:www-data uploads/  # Adjust user/group as needed
```

### Issue: "404 Not Found" on admin pages
**Solution:**
- Check .htaccess is uploaded
- Verify mod_rewrite is enabled
- Check file paths are correct

### Issue: Images not displaying
**Solution:**
- Verify uploads folder permissions
- Check APP_URL in config.php matches your domain
- Verify image URLs in database

---

## Maintenance

### Regular Tasks

**Weekly:**
- Review and publish pending content
- Upload new gallery images
- Update homepage if needed

**Monthly:**
- Backup database
- Backup uploads folder
- Review error logs
- Check for PHP/MySQL updates

**Backup Commands:**
```bash
# Database backup
mysqldump -u kss_admin -p kss_updates > backup_$(date +%Y%m%d).sql

# Files backup
tar -czf website_backup_$(date +%Y%m%d).tar.gz /path/to/website
```

---

## Support

For issues:
1. Check error logs: `logs/php_errors.log`
2. Check database connection
3. Verify file permissions
4. Review .htaccess configuration

---

## Success Indicators

✅ Admin login works
✅ Can create/edit content
✅ Content appears on frontend
✅ Images upload successfully
✅ Homepage loads dynamic content
✅ No PHP errors in logs

---

**Your site is now a fully functional CMS!** 🎉

The media team can now log in from any computer at school and update the website content remotely.

