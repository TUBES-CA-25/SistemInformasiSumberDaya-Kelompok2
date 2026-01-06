<?php
/**
 * Configuration File (FINAL & ROBUST)
 */

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
    $dotenv->safeLoad();
} catch (Exception $e) {
    // Silent fail
}

// Database Configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USERNAME'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASSWORD'] ?? '');
define('DB_NAME', $_ENV['DB_DATABASE'] ?? 'sistem_manajemen_sumber_daya');

// Application Settings
define('APP_NAME', $_ENV['APP_NAME'] ?? 'Sistem Management Sumber Daya');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development'); 

// --- DETEKSI URL OTOMATIS (CORE LOGIC) ---
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$publicPath = '';

// Cek 1: Apakah pakai PHP Built-in (localhost:8000)?
if (strpos($host, ':8000') !== false) {
    // Asumsi: dijalankan dengan "php -S localhost:8000 -t public"
    $publicPath = $protocol . $host;
} 
// Cek 2: Apakah pakai XAMPP / Apache biasa?
else {
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']); 
    $scriptDir = str_replace('\\', '/', $scriptDir);
    $scriptDir = rtrim($scriptDir, '/'); 
    
    // Hasil: http://localhost/NamaFolder/public
    $publicPath = $protocol . $host . $scriptDir;
}

// --- DEFINISI KONSTANTA ---

if (!defined('PUBLIC_URL')) {
    define('PUBLIC_URL', $publicPath);
}

if (!defined('BASE_URL')) {
    // Base URL menunjuk ke folder public (untuk akses assets)
    define('BASE_URL', PUBLIC_URL);
}

if (!defined('ASSETS_URL')) {
    // Arahkan assets ke root folder public
    define('ASSETS_URL', PUBLIC_URL);
}

if (!defined('API_URL')) {
    define('API_URL', PUBLIC_URL . '/api.php');
}

// Error Reporting
$whitelist = ['127.0.0.1', '::1', 'localhost'];
if (APP_ENV === 'development' || in_array($_SERVER['REMOTE_ADDR'] ?? '', $whitelist)) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
}

// Session & Timezone
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', 1440);
    session_start();
}
date_default_timezone_set('Asia/Jakarta');
?>