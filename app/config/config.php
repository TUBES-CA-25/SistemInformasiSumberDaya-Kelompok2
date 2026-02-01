<?php
/**
 * --------------------------------------------------------------------------
 * Configuration File (Clean & Documented)
 * --------------------------------------------------------------------------
 * File ini mengatur konfigurasi global aplikasi, database, email,
 * serta deteksi lingkungan (Development/Production).
 */

// Memuat file autoload dari Composer untuk manajemen dependency otomatis
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// --------------------------------------------------------------------------
// 1. Memuat Variabel Lingkungan (.env)
// --------------------------------------------------------------------------
try {
    // Membuat instance Dotenv untuk membaca file .env dari root folder (naik 2 level)
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
    
    // Memuat variabel secara aman (tidak error fatal jika file .env tidak ada)
    $dotenv->safeLoad();
} catch (Exception $e) {
    // Menangkap error jika terjadi masalah pada Dotenv, namun dibiarkan lanjut (silent fail)
    // Aplikasi akan menggunakan nilai default (fallback) yang didefinisikan di bawah
}

// --------------------------------------------------------------------------
// 2. Konfigurasi Aplikasi Dasar
// --------------------------------------------------------------------------

// Menentukan nama aplikasi, mengambil dari .env atau default jika kosong
define('APP_NAME', $_ENV['APP_NAME'] ?? 'Sistem Management Sumber Daya');

// Menentukan lingkungan aplikasi (local/development atau production)
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');

// Menentukan mode debug berdasarkan APP_ENV. True jika 'development', False jika lainnya
define('DEBUG_MODE', APP_ENV === 'development');

// Mengatur zona waktu default agar sinkron dengan waktu server/lokal pengguna
// Menggunakan 'Asia/Makassar' (WITA) sesuai lokasi Anda, atau ubah ke 'Asia/Jakarta' jika perlu
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Asia/Makassar');

// --------------------------------------------------------------------------
// 3. Konfigurasi Database
// --------------------------------------------------------------------------

// Host database (biasanya localhost)
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');

// Username database
define('DB_USER', $_ENV['DB_USERNAME'] ?? 'root');

// Password database (kosongkan string jika tidak ada password di lokal)
define('DB_PASS', $_ENV['DB_PASSWORD'] ?? '');

// Nama database yang akan digunakan
define('DB_NAME', $_ENV['DB_DATABASE'] ?? 'sistem_manajemen_sumber_daya');

// --------------------------------------------------------------------------
// 4. Konfigurasi Email (SMTP)
// --------------------------------------------------------------------------
// PENTING: Password diambil dari .env demi keamanan.

// Host SMTP (Google Mail)
define('SMTP_HOST', $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com');

// Port SMTP (587 untuk TLS, 465 untuk SSL)
define('SMTP_PORT', $_ENV['SMTP_PORT'] ?? 587);

// Akun email pengirim
define('SMTP_USER', $_ENV['SMTP_USERNAME'] ?? 'nahwakakaa@gmail.com');

// Password aplikasi email (App Password), jangan hardcode di sini!
define('SMTP_PASS', $_ENV['SMTP_PASSWORD'] ?? 'bapr gojk hboy oqiz');

// --------------------------------------------------------------------------
// 5. Deteksi URL Otomatis (Core Logic)
// --------------------------------------------------------------------------

// Menentukan protokol yang digunakan (HTTP atau HTTPS)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';

// Mendapatkan host saat ini (misal: localhost atau domain.com)
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Variabel penampung path publik
$publicPath = '';

// Logika percabangan untuk menentukan Base URL
if (strpos($host, ':8000') !== false) {
    // Skenario 1: PHP Built-in Server (php -S localhost:8000)
    // Root URL langsung mengarah ke host dan port
    $publicPath = $protocol . $host;
} else {
    // Skenario 2: XAMPP / Apache / Nginx
    // Mengambil direktori skrip yang sedang berjalan
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    
    // Normalisasi slash (mengubah backslash Windows menjadi slash biasa)
    $scriptDir = str_replace('\\', '/', $scriptDir);
    
    // Menghapus slash di akhir string untuk konsistensi
    $scriptDir = rtrim($scriptDir, '/');
    
    // Menggabungkan protokol, host, dan folder direktori
    $publicPath = $protocol . $host . $scriptDir;
}

// --------------------------------------------------------------------------
// 6. Definisi Konstanta URL
// --------------------------------------------------------------------------

// URL Publik utama (Entry point aplikasi)
if (!defined('PUBLIC_URL')) {
    define('PUBLIC_URL', $publicPath);
}

// Base URL (bisa sama dengan Public URL atau root project, tergantung struktur)
if (!defined('BASE_URL')) {
    define('BASE_URL', PUBLIC_URL);
}

// URL untuk aset statis (CSS, JS, Images)
if (!defined('ASSETS_URL')) {
    define('ASSETS_URL', PUBLIC_URL);
}

// URL untuk akses API internal
if (!defined('API_URL')) {
    define('API_URL', PUBLIC_URL . '/api.php');
}

// --------------------------------------------------------------------------
// 7. Error Reporting & Logging
// --------------------------------------------------------------------------

// Daftar IP yang diizinkan melihat error secara detail (Whitelisting)
$whitelist = ['127.0.0.1', '::1', 'localhost'];

// Lokasi file log error (disimpan di folder storage/logs)
$logPath = dirname(__DIR__, 2) . '/storage/logs/error.log';

// Pastikan folder log ada, jika tidak, log error sistem PHP standar akan dipakai
ini_set('log_errors', 1);
ini_set('error_log', $logPath);

// Logika tampilan error
if (DEBUG_MODE === true || in_array($_SERVER['REMOTE_ADDR'] ?? '', $whitelist)) {
    // Mode Development: Tampilkan semua error ke layar
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    // Mode Production: Sembunyikan error dari layar (hanya catat di log)
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT); // Melaporkan error kritis saja
}

// --------------------------------------------------------------------------
// 8. Session Handling
// --------------------------------------------------------------------------

// Memulai session hanya jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>