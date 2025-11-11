<?php
/**
 * PHP Test File
 * 
 * This file tests if PHP is working on your server.
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to your server root
 * 2. Visit: https://www.kagaramasec.org/test.php
 * 3. If you see "PHP IS WORKING!" below, PHP is configured correctly
 * 4. If the file downloads or shows code, PHP is not configured
 * 5. DELETE THIS FILE after testing (security risk!)
 */

// Display PHP information
echo "<h1 style='color: green;'>✅ PHP IS WORKING!</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<hr>";
echo "<h2>PHP Configuration:</h2>";
echo "<ul>";
echo "<li>PHP Version: " . phpversion() . "</li>";
echo "<li>Server API: " . php_sapi_name() . "</li>";
echo "<li>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</li>";
echo "<li>Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "</li>";
echo "</ul>";

// Check required extensions
echo "<h2>Required Extensions:</h2>";
echo "<ul>";
echo "<li>PDO: " . (extension_loaded('pdo') ? "✅ Installed" : "❌ Missing") . "</li>";
echo "<li>PDO MySQL: " . (extension_loaded('pdo_mysql') ? "✅ Installed" : "❌ Missing") . "</li>";
echo "<li>GD Library: " . (extension_loaded('gd') ? "✅ Installed" : "❌ Missing") . "</li>";
echo "<li>Session: " . (extension_loaded('session') ? "✅ Installed" : "❌ Missing") . "</li>";
echo "</ul>";

// Check file permissions
echo "<h2>File Permissions:</h2>";
echo "<ul>";
echo "<li>Current file readable: " . (is_readable(__FILE__) ? "✅ Yes" : "❌ No") . "</li>";
echo "<li>Current file writable: " . (is_writable(__FILE__) ? "✅ Yes" : "❌ No") . "</li>";
echo "</ul>";

echo "<hr>";
echo "<p style='color: red;'><strong>⚠️ SECURITY WARNING: Delete this file after testing!</strong></p>";
?>


