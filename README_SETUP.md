# KSS Updates System - Setup Guide

## Overview
The KSS Updates System is a comprehensive content management system that allows the KSS media team to manage news articles, blogs, announcements, and gallery images. The system includes a user-friendly admin panel and a public-facing frontend.

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
kss-v2/
├── admin/              # Admin panel files
│   ├── login.php       # Login page
│   ├── dashboard.php   # Admin dashboard
│   └── ...
├── api/                # API endpoints
│   ├── auth.php        # Authentication API
│   ├── content.php     # Content management API
│   ├── gallery.php     # Gallery API
│   └── categories.php  # Categories API
├── config/             # Configuration files
│   ├── database.php    # Database configuration
│   └── functions.php   # Utility functions
├── database/           # Database files
│   └── schema.sql      # Database schema
├── uploads/            # Uploaded files
│   └── gallery/        # Gallery images
├── assets/             # Frontend assets
└── *.html              # Frontend pages
```

## Usage

### Admin Panel
1. Navigate to `/admin/login.php`
2. Login with your credentials
3. Use the dashboard to:
   - Create news articles
   - Write blog posts
   - Post announcements
   - Upload gallery images
   - Manage categories
   - View statistics

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

## Support
For issues or questions, contact the development team or refer to the documentation.

## License
Proprietary - Kagarama Secondary School

