<?php
    
    require_once 'load.php';

    
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