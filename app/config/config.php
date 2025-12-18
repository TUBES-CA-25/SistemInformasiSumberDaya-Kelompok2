<?php
/**
 * Configuration File
 */

// Autoload Composer dependencies
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistem_manajemen_sumber_daya');

// Application Settings
define('APP_NAME', 'Sistem Management Sumber Daya');
define('APP_URL', 'http://localhost/SistemInformasiSumberDaya-Kelompok2');
define('APP_ENV', 'development'); // development or production

// Auto-detect URLs untuk fleksibilitas deployment
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseScriptPath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if (empty($baseScriptPath) || $baseScriptPath === '.') {
    $baseScriptPath = '';
}

// Define URL constants
define('BASE_URL', $protocol . $host . $baseScriptPath);
define('API_URL', $baseScriptPath . '/public/api.php');
define('ASSETS_URL', $baseScriptPath . '/public');

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
