<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
if (!defined('SITE_URL')) {
    define('SITE_URL', $_ENV['SITE_URL']);
}

if (!defined('SITE_TIMEZONE')) {
    define('SITE_TIMEZONE', $_ENV['SITE_TIMEZONE']);
}

if (!defined('DB_NAME')) {
    define('DB_NAME', $_ENV['DB_NAME']);
}

if (!defined('DB_PASS')) {
    define('DB_PASS', $_ENV['DB_PASS']);
}

if (!defined('DB_HOST')) {
    define('DB_HOST', $_ENV['DB_HOST']);
}

if (!defined('DB_USER')) {
    define('DB_USER', $_ENV['DB_USER']);
}

if (!defined('SMS_API_KEY')) {
    define('SMS_API_KEY', 'pHB4LGiLdC3eYOhodu1ZxgrgGVZJxygvYHotru8dXMrBtGNs');
}

if (!defined('SMS_DEBUG_PHONE')) {
    define('SMS_DEBUG_PHONE', false);
}


if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'موزیکیفا');
}

if (!defined('SITE_DESCRIPTION')) {
    define('SITE_DESCRIPTION', 'پلتفرم آنلاین پخش موزیک');
}

if (!defined('SITE_STATUS')) {
    define('SITE_STATUS', 'active');
}

if (!defined('OTP_DURATION')) {
    define('OTP_DURATION', 30);
}

if (!defined('OTP_LENGTH')) {
    define('OTP_LENGTH', 6);
}

if (!defined('HOME_MUSIC_COUNT')) {
    define('HOME_MUSIC_COUNT', 12);
}
?>