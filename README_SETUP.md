# KSS CMS - Complete Setup Guide

## Overview
The KSS CMS is a comprehensive content management system that converts your static HTML website into a fully dynamic, database-driven site. The media team can log in from any computer, update content remotely, and see changes reflected live on https://www.kagaramasec.org/ without touching local files.

**Key Features:**
- ✅ Secure admin login system
- ✅ Manage News, Blogs, and Announcements
- ✅ Upload and organize Gallery images
- ✅ Edit Homepage sections and banners
- ✅ All data stored in MySQL database
- ✅ File uploads stored on server
- ✅ Responsive admin panel
- ✅ Production-ready security

## Features
- **Content Management**: Create, edit, and manage news, blogs, and announcements
- **Gallery Management**: Upload and organize images with categories
- **User Authentication**: Secure login system for media team members
- **Role-Based Access**: Admin, Editor, and Author roles
- **Responsive Design**: Works on all devices
- **SEO Friendly**: Clean URLs and meta tags
- **Activity Logging**: Track all changes and actions

## Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher (or MariaDB 10.2+)
- Apache web server with mod_rewrite enabled
- GD Library or ImageMagick for image processing

## Installation

### 1. Database Setup
1. Create a MySQL database:
```sql
CREATE DATABASE kss_updates CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import the database schema:
```bash
mysql -u your_username -p kss_updates < database/schema.sql
```

3. Update database credentials in `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'kss_updates');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 2. File Permissions
Set proper permissions for the uploads directory:
```bash
chmod 755 uploads/
chmod 755 uploads/gallery/
```

### 3. Configuration
1. Update `config/database.php` with your application URL:
```php
define('APP_URL', 'http://your-domain.com/kss-v2');
```

2. Ensure the uploads directory exists and is writable:
```bash
mkdir -p uploads/gallery
chmod 777 uploads/gallery
```

### 4. Default Login Credentials
- **Username**: `admin`
- **Password**: `admin123`

**⚠️ IMPORTANT**: Change the default password immediately after first login!

To change the password, you can use this SQL query:
```sql
UPDATE users SET password_hash = '$2y$10$...' WHERE username = 'admin';
```
(Generate a new hash using PHP's `password_hash()` function)

## Directory Structure
```
www.kagaramasec.org/
├── admin/              # Admin panel files
│   ├── login.php       # Login page
│   ├── dashboard.php   # Admin dashboard
│   ├── content.php     # Content management
│   ├── gallery.php     # Gallery management
│   └── homepage.php    # Homepage editor
├── api/                # API endpoints
│   ├── auth.php        # Authentication API
│   ├── content.php     # Content management API
│   ├── gallery.php     # Gallery API
│   ├── homepage.php    # Homepage API
│   └── categories.php  # Categories API
├── config/             # Configuration files
│   ├── functions.php   # Utility functions
│   └── database.php    # (Legacy - use config.php)
├── config.php          # MAIN CONFIG FILE (Edit this!)
├── database/           # Database files
│   └── schema.sql      # Database schema
├── uploads/            # Uploaded files (set permissions 755)
│   └── gallery/        # Gallery images
├── assets/             # Frontend assets (keep existing)
├── index.php           # Dynamic homepage (replaces index.html)
├── Updates.html        # Updates page
├── Gallery.html         # Gallery page
├── UpdateDetail.html   # Content detail page
├── About.html          # (Keep existing)
├── Contact.html        # (Keep existing)
└── *.html              # Other existing pages
```

## Usage

### Admin Panel Access
1. Navigate to: **https://www.kagaramasec.org/admin/login.php**
2. Login with credentials:
   - Username: `admin`
   - Password: `admin123` (change immediately!)
3. Use the dashboard to:
   - **News**: Create and manage news articles
   - **Blogs**: Write and publish blog posts
   - **Announcements**: Post school announcements
   - **Gallery**: Upload and organize images
   - **Homepage**: Edit homepage sections and banners
   - **Categories**: Organize content
   - **Users**: Manage team members (Admin only)

### Remote Access
- ✅ Login from any computer at school
- ✅ Update content from anywhere
- ✅ Changes appear live immediately
- ✅ No local file editing needed
- ✅ All data stored in database

### API Endpoints

#### Authentication
- `POST /api/auth.php?action=login` - Login
- `POST /api/auth.php?action=logout` - Logout
- `GET /api/auth.php?action=check` - Check authentication

#### Content
- `GET /api/content.php?action=list&type=news` - List content
- `GET /api/content.php?action=get&id=1` - Get single content
- `POST /api/content.php?action=create` - Create content
- `PUT /api/content.php?action=update&id=1` - Update content
- `DELETE /api/content.php?action=delete&id=1` - Delete content

#### Gallery
- `GET /api/gallery.php?action=list` - List gallery items
- `GET /api/gallery.php?action=get&id=1` - Get single item
- `POST /api/gallery.php?action=upload` - Upload image
- `PUT /api/gallery.php?action=update&id=1` - Update item
- `DELETE /api/gallery.php?action=delete&id=1` - Delete item

#### Homepage
- `GET /api/homepage.php?action=get_sections` - Get all homepage sections
- `GET /api/homepage.php?action=get_section&key=hero_title` - Get single section
- `PUT /api/homepage.php?action=update_section` - Update section
- `GET /api/homepage.php?action=get_banners` - Get all banners
- `POST /api/homepage.php?action=create_banner` - Create banner
- `PUT /api/homepage.php?action=update_banner&id=1` - Update banner
- `DELETE /api/homepage.php?action=delete_banner&id=1` - Delete banner

## Security Considerations

1. **Change Default Password**: Immediately change the default admin password
2. **HTTPS**: Enable HTTPS in production (uncomment in .htaccess)
3. **File Permissions**: Ensure uploads directory has proper permissions
4. **Database Security**: Use strong database passwords
5. **Regular Backups**: Backup database and uploads regularly
6. **Updates**: Keep PHP and MySQL updated

## Troubleshooting

### Images not uploading
- Check file permissions on uploads directory
- Verify PHP upload_max_filesize and post_max_size settings
- Check .htaccess file is not blocking uploads

### Database connection errors
- Verify database credentials in config/database.php
- Ensure MySQL service is running
- Check database exists and user has proper permissions

### API not working
- Check mod_rewrite is enabled
- Verify .htaccess file is present
- Check PHP error logs

## Production Deployment

### Quick Deployment Steps

1. **Upload Files**
   - Upload all files to your web server
   - Keep existing HTML files
   - Replace index.html with index.php

2. **Configure**
   - Edit `config.php` with your database credentials
   - Set `APP_URL` to `https://www.kagaramasec.org`

3. **Database**
   - Create database and user
   - Import `database/schema.sql`

4. **Permissions**
   - Set uploads/ folder to 755
   - Set config.php to 600

5. **Test**
   - Visit admin login page
   - Login and create test content
   - Verify it appears on frontend

**See `DEPLOYMENT_GUIDE.md` for detailed instructions.**

## Support
For issues or questions:
- Check `DEPLOYMENT_GUIDE.md` for deployment help
- Review `PRODUCTION_CHECKLIST.md` before going live
- Check error logs: `logs/php_errors.log`
- Contact the development team

## License
Proprietary - Kagarama Secondary School

