# Troubleshooting Guide - KSS CMS

## Issue: PHP File Downloads Instead of Executing

### Problem
When accessing `https://www.kagaramasec.org/admin/login.php`, the browser downloads the file instead of displaying the login page.

### Root Cause
This happens when:
1. PHP is not installed on the server
2. PHP is not configured to run with your web server
3. The web server doesn't recognize `.php` files
4. PHP handler is not configured correctly

---

## Solutions

### Solution 1: Check PHP Installation (cPanel/Shared Hosting)

**If you're using cPanel or shared hosting:**

1. **Check PHP Version**
   - Log into cPanel
   - Go to "Select PHP Version" or "PHP Selector"
   - Ensure PHP 7.4+ is selected
   - Click "Save"

2. **Check PHP Handler**
   - In cPanel, go to "Select PHP Version"
   - Look for "Handler" option
   - Select: `php-fpm` or `suphp` or `cgi`
   - Avoid: `dso` (can cause issues)

3. **Verify .htaccess**
   - Ensure `.htaccess` file exists in root directory
   - Check that it doesn't have conflicting rules

---

### Solution 2: Add PHP Handler to .htaccess

Add this to your root `.htaccess` file (if using Apache):

```apache
# Force PHP execution
<IfModule mod_php7.c>
    AddType application/x-httpd-php .php
    AddHandler application/x-httpd-php .php
</IfModule>

# For PHP 8.x
<IfModule mod_php8.c>
    AddType application/x-httpd-php .php
    AddHandler application/x-httpd-php .php
</IfModule>

# Alternative handler (if above doesn't work)
AddHandler application/x-httpd-php .php
```

---

### Solution 3: Check File Permissions

Ensure PHP files have correct permissions:

```bash
# Via SSH or File Manager
chmod 644 *.php
chmod 644 admin/*.php
chmod 644 api/*.php
```

---

### Solution 4: Create PHP Test File

Create a test file to verify PHP is working:

**File: `test.php`** (in root directory)
```php
<?php
phpinfo();
?>
```

**Access:** `https://www.kagaramasec.org/test.php`

- **If it works**: PHP is installed, check other issues
- **If it downloads**: PHP is not configured (see Solution 1)

**⚠️ IMPORTANT**: Delete `test.php` after testing for security!

---

### Solution 5: Contact Your Hosting Provider

If none of the above work:

1. **Contact your hosting support** and ask:
   - "Is PHP installed on my account?"
   - "What PHP version is available?"
   - "Is PHP configured to execute .php files?"
   - "What PHP handler should I use?"

2. **Provide them with:**
   - Your domain: `www.kagaramasec.org`
   - The issue: "PHP files are downloading instead of executing"
   - The file: `/admin/login.php`

---

### Solution 6: Check Server Configuration (VPS/Dedicated)

If you have server access:

1. **Check PHP Installation**
   ```bash
   php -v
   ```

2. **Check Apache Configuration**
   ```bash
   # For Apache
   sudo a2enmod php7.4
   sudo systemctl restart apache2
   ```

3. **Check Nginx Configuration**
   ```nginx
   # In your Nginx config
   location ~ \.php$ {
       fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
       fastcgi_index index.php;
       include fastcgi_params;
   }
   ```

---

## Quick Diagnostic Steps

### Step 1: Create Test File
Create `test.php` in root:
```php
<?php echo "PHP is working!"; ?>
```

### Step 2: Access Test File
Visit: `https://www.kagaramasec.org/test.php`

### Step 3: Check Result
- **Shows "PHP is working!"** → PHP works, check other issues
- **Downloads file** → PHP not configured (use Solution 1 or 2)
- **Shows code** → PHP not executing (use Solution 2)

### Step 4: Check Error Logs
- cPanel: "Error Log" section
- Look for PHP-related errors
- Check: `logs/php_errors.log`

---

## Most Common Fix (cPanel)

**90% of cases - cPanel users:**

1. Log into cPanel
2. Go to "Select PHP Version"
3. Select PHP 7.4 or higher
4. Click "Extensions" tab
5. Enable: `php-mysqli`, `php-pdo`, `php-gd`
6. Click "Save"
7. Try accessing login page again

---

## Alternative: Use .php5 Extension

If PHP still doesn't work, try renaming files temporarily:

```bash
# Rename login.php to login.php5
# Some servers recognize .php5 but not .php
```

**Note:** This is a temporary workaround. Fix the root cause instead.

---

## Verify After Fix

Once fixed, verify:

1. ✅ `test.php` shows output (not downloads)
2. ✅ `admin/login.php` shows login form
3. ✅ Can login successfully
4. ✅ Dashboard loads correctly

---

## Still Not Working?

If none of these solutions work:

1. **Check hosting plan**: Some plans don't support PHP
2. **Check server type**: Ensure it's Apache/Nginx, not static hosting
3. **Contact support**: Your hosting provider can fix this quickly

---

## Prevention

After fixing:

1. **Delete test.php** (security risk)
2. **Verify .htaccess** is correct
3. **Document PHP version** for future reference
4. **Set up monitoring** to catch issues early

---

**Need more help?** Check your hosting provider's documentation or contact their support team.


