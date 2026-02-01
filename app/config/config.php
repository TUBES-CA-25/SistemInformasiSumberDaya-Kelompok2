<?php
/**
 * Application Configuration File
 * 
 * Konfigurasi utama untuk Sistem Informasi Sumber Daya
 * Menangani database, SMTP, URL, session, dan error handling
 */

// ============================================
// 1. LOAD ENVIRONMENT VARIABLES
// ============================================
// Memuat file .env untuk kredensial yang aman
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
    $dotenv->safeLoad();
} catch (Exception $e) {
    // Silent fail jika file .env tidak ada
}

// ============================================
// 2. DATABASE CONFIGURATION
// ============================================
// Konfigurasi koneksi database MySQL/MariaDB
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USERNAME'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASSWORD'] ?? '');
define('DB_NAME', $_ENV['DB_DATABASE'] ?? 'sistem_manajemen_sumber_daya');

// ============================================
// 3. EMAIL CONFIGURATION (SMTP)
// ============================================
// Konfigurasi SMTP untuk pengiriman email
// Gunakan environment variables untuk keamanan
define('SMTP_HOST', $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com');
define('SMTP_USER', $_ENV['SMTP_USER'] ?? 'nahwakakaa@gmail.com');
define('SMTP_PASS', $_ENV['SMTP_PASS'] ?? 'pzwx lfbx shzm jwoo');
define('SMTP_PORT', $_ENV['SMTP_PORT'] ?? 587);

// ============================================
// 4. APPLICATION SETTINGS
// ============================================
// Pengaturan aplikasi umum
define('APP_NAME', $_ENV['APP_NAME'] ?? 'Sistem Management Sumber Daya');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');
define('DEBUG_MODE', APP_ENV === 'development');

// Set timezone default untuk aplikasi
date_default_timezone_set('Asia/Makassar');

// ============================================
// 5. AUTOMATIC URL DETECTION
// ============================================
// Mendeteksi protokol (HTTP/HTTPS)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$publicPath = '';

// Cek jika menggunakan PHP Built-in Server (localhost:8000)
if (strpos($host, ':8000') !== false) {
    $publicPath = $protocol . $host;
}
// Cek jika menggunakan XAMPP/Apache
else {
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    $scriptDir = str_replace('\\', '/', $scriptDir);
    $scriptDir = rtrim($scriptDir, '/');
    $publicPath = $protocol . $host . $scriptDir;
}

// ============================================
// 6. DEFINE URL CONSTANTS
// ============================================
// Tentukan URL-URL penting untuk aplikasi
if (!defined('PUBLIC_URL')) {
    define('PUBLIC_URL', $publicPath);
}

if (!defined('BASE_URL')) {
    // URL dasar untuk asset dan resource
    define('BASE_URL', PUBLIC_URL);
}

if (!defined('ASSETS_URL')) {
    // URL untuk folder public/assets
    define('ASSETS_URL', PUBLIC_URL);
}

if (!defined('API_URL')) {
    // URL untuk endpoint API
    define('API_URL', PUBLIC_URL . '/api.php');
}

// ============================================
// 7. ERROR REPORTING & DISPLAY
// ============================================
// Konfigurasi error handling sesuai environment
$whitelist = ['127.0.0.1', '::1', 'localhost'];
$isDeveloper = APP_ENV === 'development' || in_array($_SERVER['REMOTE_ADDR'] ?? '', $whitelist);

if ($isDeveloper) {
    // Tampilkan error untuk development
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    // Sembunyikan error untuk production
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
}

// ============================================
// 8. SESSION INITIALIZATION
// ============================================
// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================
// 9. ERROR LOGGING
// ============================================
// Konfigurasi logging error ke file
$logPath = dirname(__DIR__, 2) . '/storage/logs/error.log';

ini_set('log_errors', 1);
ini_set('error_log', $logPath);

// Adjust display errors berdasarkan DEBUG_MODE
ini_set('display_errors', DEBUG_MODE ? 1 : 0);
?>

