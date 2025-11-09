<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'food_web_app');

// Application configuration
define('SITE_URL', 'http://localhost/Food-Web-App_IE4727/food-web-app-php');
define('SITE_NAME', 'LeckerHaus');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
