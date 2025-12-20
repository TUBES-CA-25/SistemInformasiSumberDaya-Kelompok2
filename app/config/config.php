<?php
/**
 * Configuration File
 */

// Autoload Composer dependencies
// Autoload Composer dependencies
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Load Environment Variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->safeLoad();

// Database Configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USERNAME'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASSWORD'] ?? '');
define('DB_NAME', $_ENV['DB_DATABASE'] ?? 'sistem_manajemen_sumber_daya');

// Application Settings
define('APP_NAME', $_ENV['APP_NAME'] ?? 'Sistem Management Sumber Daya');
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost/SistemInformasiSumberDaya-Kelompok2');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development'); // development or production

// Auto-detect URLs untuk fleksibilitas deployment
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Detect environment dan set base paths accordingly
if (strpos($_SERVER['SERVER_NAME'] ?? '', 'localhost') !== false && ($_SERVER['SERVER_PORT'] ?? 80) == 8000) {
    // PHP Built-in server (localhost:8000)
    // Document root is already in public/, so paths are relative to root
    $baseScriptPath = '';
    $publicPath = '';
    $assetsPath = '';
} else {
    // XAMPP/Apache server (localhost/project/public/)
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    
    // Extract project path from script name
    if (strpos($scriptName, '/SistemInformasiSumberDaya-Kelompok2/') !== false) {
        $baseScriptPath = '/SistemInformasiSumberDaya-Kelompok2';
        $publicPath = '/SistemInformasiSumberDaya-Kelompok2/public';
        $assetsPath = '/SistemInformasiSumberDaya-Kelompok2/public';
    } else {
        $baseScriptPath = '';
        $publicPath = '';
        $assetsPath = '';
    }
}

// Define URL constants
if (!defined('BASE_URL')) {
    define('BASE_URL', $protocol . $host . $baseScriptPath);
}
if (!defined('PUBLIC_URL')) {
    define('PUBLIC_URL', $protocol . $host . $publicPath);
}
if (!defined('API_URL')) {
    define('API_URL', $publicPath . '/api.php');
}
if (!defined('ASSETS_URL')) {
    define('ASSETS_URL', $assetsPath);
}

// Display errors (disable in production)
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
}

// Session configuration
ini_set('session.gc_maxlifetime', 1440);
session_start();

// Timezone
date_default_timezone_set('Asia/Jakarta');
?>
