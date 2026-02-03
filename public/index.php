<?php
/**
 * SISTEM INFORMASI SUMBER DAYA - KELOMPOK 2
 * Entry Point Utama (Bootstrap)
 */

session_start();

// 1. ERROR REPORTING (Aktifkan saat development)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. DEFINISI PATH ABSOLUT
define('ROOT_PROJECT', dirname(__DIR__));
define('APP_PATH',        ROOT_PROJECT . '/app');
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('VIEW_PATH',       APP_PATH . '/views');
define('CORE_PATH',       APP_PATH . '/core');
define('CONFIG_PATH',     APP_PATH . '/config');

// 3. LOAD CONFIG & HELPERS
if (file_exists(APP_PATH . '/config/config.php')) {
    require_once APP_PATH . '/config/config.php';
}
if (file_exists(APP_PATH . '/helpers/Helper.php')) {
    require_once APP_PATH . '/helpers/Helper.php';
}

// 4. DETEKSI URL PUBLIK (Base URL)
if (!defined('PUBLIC_URL')) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    $script_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    define('PUBLIC_URL', $scheme . '://' . $host . $script_dir);
}

// 5. LOAD CORE COMPONENTS (Urutan sangat krusial agar tidak Class Not Found)

// A. Muat Database Terlebih Dahulu

$db_file = APP_PATH . '/config/Database.php';
if (file_exists($db_file)) {
    require_once $db_file;
} else {
    die("Fatal Error: Database.php tidak ditemukan di " . $db_file);
}

// B. Muat Controller Induk (Mengecek folder controllers dan core)
$induk_controller = CONTROLLER_PATH . '/Controller.php';
if (file_exists($induk_controller)) {
    require_once $induk_controller;
} elseif (file_exists(CORE_PATH . '/Controller.php')) {
    require_once CORE_PATH . '/Controller.php';
} else {
    die("Fatal Error: Controller Induk tidak ditemukan di folder controllers maupun core.");
}

// C. Muat Router
if (file_exists(CONFIG_PATH . '/Router.php')) {
    require_once CONFIG_PATH . '/Router.php';
} else {
    die("Fatal Error: Router.php tidak ditemukan di " . CONFIG_PATH);
}

// 6. LOGIKA ROUTING & PROTEKSI ADMIN
$router = new Router();
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri_clean   = explode('?', $request_uri)[0];

// Cek apakah user mengakses area admin
$isAdminArea = (strpos($uri_clean, '/admin') !== false) || (strpos($uri_clean, '/dashboard') !== false);

if ($isAdminArea) {
    if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
        header("Location: " . PUBLIC_URL . "/iclabs-login");
        exit;
    }
}

// Redirect root '/' ke '/home'
if (in_array($uri_clean, ['/', '', '/index.php'], true)) {
    header('Location: ' . PUBLIC_URL . '/home', true, 302);
    exit;
}

// 7. JALANKAN DISPATCH
// Sekarang semua Core (Database & Controller) sudah ter-load, 
// sehingga JadwalUpkModel tidak akan error lagi.
$router->dispatch();