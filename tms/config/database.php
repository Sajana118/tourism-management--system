<?php
/**
 * Database Configuration - Nepal Tourism Management System
 * BCA 8th Semester Project
 * 
 * Modern PDO configuration with error handling and security
 */

// Environment Configuration
define('ENVIRONMENT', 'development'); // production | development

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tms');
define('DB_CHARSET', 'utf8mb4');

// Application Settings
define('APP_NAME', 'Nepal Tourism Management System');
define('APP_VERSION', '2.0');
define('APP_URL', 'http://localhost/Tourism-Management-System-PHP/tms/');

// Security Settings
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
define('PASSWORD_MIN_LENGTH', 6);

// File Upload Settings
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Nepal Specific Settings
define('CURRENCY', 'NPR');
define('CURRENCY_SYMBOL', 'Rs.');
define('TIMEZONE', 'Asia/Kathmandu');

// Set timezone
date_default_timezone_set(TIMEZONE);

// Error Reporting based on environment
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}

// Database Connection with PDO
try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
    ];
    
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $dbh = new PDO($dsn, DB_USER, DB_PASS, $options);
    
} catch (PDOException $e) {
    if (ENVIRONMENT === 'development') {
        die("Database Connection Error: " . $e->getMessage());
    } else {
        die("Sorry, we're experiencing technical difficulties. Please try again later.");
    }
}

/**
 * Helper function to sanitize output
 */
function sanitize_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Helper function to format currency
 */
function format_currency($amount) {
    return CURRENCY . ' ' . number_format($amount, 2);
}

/**
 * Helper function to format date (Nepal format)
 */
function format_date($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['login']) && !empty($_SESSION['login']);
}

/**
 * Redirect helper
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Generate CSRF Token
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
