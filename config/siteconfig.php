<?php
    
    require_once 'load.php';
// Determine base URL dynamically or use from .env
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = str_replace('/index.php', '', $scriptName);

define('BASE_URL', $protocol . '://' . $host . $basePath);
define('BASE_PATH', $basePath);
    
    define('SITE_URL', $_ENV['SITE_URL']);
    define('SITE_NAME', $_ENV['SITE_NAME']);
    define('RECAPTCHA_SITE_KEY', $_ENV['RECAPTCHA_SITE_KEY']);
    define('RECAPTCHA_SECRET_KEY', $_ENV['RECAPTCHA_SECRET_KEY']);
    define('SESSION_LIFETIME', $_ENV['SESSION_LIFETIME']);
    define('TOKEN_EXPIRY', $_ENV['TOKEN_EXPIRY']);
    date_default_timezone_set($_ENV['TIMEZONE']);    
    define('DEBUG_MODE', $_ENV['DEBUG_MODE']);

    if (DEBUG_MODE) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', 0);
        error_reporting(0);
    }
?>