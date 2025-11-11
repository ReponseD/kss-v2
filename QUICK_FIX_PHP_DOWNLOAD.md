# Quick Fix: PHP Files Downloading Instead of Executing

## 🚨 Immediate Solution

Your PHP files are downloading because PHP isn't configured to execute on your server. Here's how to fix it:

---

## Option 1: cPanel Fix (Most Common - 2 Minutes)

1. **Log into cPanel**
2. **Go to "Select PHP Version"** (or "PHP Selector")
3. **Select PHP 7.4 or higher**
4. **Click "Extensions" tab**
5. **Enable these extensions:**
   - ✅ php-mysqli
   - ✅ php-pdo
   - ✅ php-gd
   - ✅ php-session
6. **Click "Save"**
7. **Try accessing login page again**

**That's it!** This fixes 90% of cases.

---

## Option 2: Update .htaccess (Already Done)

I've already updated your `.htaccess` file with PHP handler directives. 

**What to do:**
1. Upload the updated `.htaccess` file to your server root
2. Make sure it's in the same directory as `index.php`
3. Try accessing `admin/login.php` again

---

## Option 3: Test PHP First

Before fixing, test if PHP works:

1. **Upload `test.php`** to your server root
2. **Visit:** `https://www.kagaramasec.org/test.php`
3. **Check result:**
   - ✅ Shows "PHP IS WORKING!" → PHP works, check other issues
   - ❌ Downloads file → Use Option 1 (cPanel fix)
   - ❌ Shows code → Use Option 2 (.htaccess)

**⚠️ DELETE `test.php` after testing!**

---

## Option 4: Contact Hosting Support

If Options 1-3 don't work:

**Contact your hosting provider and say:**

> "Hello, I'm trying to run PHP files on my website www.kagaramasec.org, but when I access .php files, they download instead of executing. Can you please:
> 1. Verify PHP is installed and enabled for my account
> 2. Check that PHP handler is configured correctly
> 3. Ensure .php files are set to execute, not download"

---

## What I've Done

I've already:
- ✅ Updated `.htaccess` with PHP handler directives
- ✅ Created `admin/.htaccess` with PHP handler
- ✅ Created `test.php` for diagnostics
- ✅ Created `TROUBLESHOOTING.md` with detailed solutions

---

## Next Steps

1. **Try Option 1 first** (cPanel - easiest)
2. **If that doesn't work**, upload the updated `.htaccess` files
3. **Test with `test.php`** to verify PHP works
4. **If still not working**, contact hosting support

---

## Verification

After fixing, verify:

1. ✅ `test.php` shows output (not downloads)
2. ✅ `admin/login.php` shows login form
3. ✅ Can login successfully
4. ✅ Dashboard loads

---

## Common Causes

- **cPanel**: PHP version not selected
- **Shared hosting**: PHP handler not configured
- **VPS**: PHP module not enabled in Apache
- **Nginx**: PHP-FPM not configured

---

**Most likely solution:** Option 1 (cPanel PHP Version selection)

Try that first - it's the quickest fix!


