<?php
// FILE: public/index.php - Full MVC Router Entry

// 1. Tampilkan Error (Penting untuk debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Definisi Folder Utama (ROOT)
define('ROOT_PROJECT', dirname(__DIR__)); 
define('APP_PATH', ROOT_PROJECT . '/app');
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('MODEL_PATH', APP_PATH . '/models');
define('VIEW_PATH', APP_PATH . '/views');
define('CONFIG_PATH', APP_PATH . '/config');

// 3. Load configuration (minimal)
require_once CONFIG_PATH . '/config.php';

// 4. Optional: Redirect legacy `?page=` ke rute MVC (kompatibilitas sementara)
$page = $_GET['page'] ?? '';
if (!empty($page)) {
    $legacyMap = [
        'home' => '/home',
        'tatatertib' => '/tata-tertib',
        'jadwal' => '/jadwal',
        'asisten' => '/asisten',
        'kepala' => '/kepala-lab',
        'laboratorium' => '/laboratorium',
        'riset' => '/riset',
        'alumni' => '/alumni',
        'contact' => '/contact',
        'login' => '/login',
        'admin' => '/admin',
        'dashboard' => '/admin/dashboard',
    ];
    if (isset($legacyMap[$page])) {
        header('Location: ' . PUBLIC_URL . $legacyMap[$page]);
        exit;
    }
}

// 5. Normalisasi path rute dari REQUEST_URI (mendukung Apache & php -S)
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';

if (!empty($request_uri) && strpos($request_uri, $script_name) === 0) {
    $path = substr($request_uri, strlen($script_name));
} else {
    $path = parse_url($request_uri, PHP_URL_PATH);
    // Hilangkan prefix ke index.php jika ada
    $path = preg_replace('#.*/public/index\.php#', '', $path);
}

$path = urldecode($path);
$path = '/' . trim($path, '/ ');
if ($path === '') { $path = '/'; }

// 6. Load MVC dependencies & dispatch Router
require_once CONFIG_PATH . '/Database.php';
require_once APP_PATH . '/helpers/Helper.php';
require_once CONFIG_PATH . '/Router.php';

// Pastikan Router membaca path yang sudah dinormalisasi
$_GET['route'] = $_GET['route'] ?? $path;

try {
    $router = new Router();
    $router->dispatch();
    exit; // Penting: keluar setelah router menangani request
} catch (Exception $e) {
    http_response_code(500);
    echo "<h1>Routing Error</h1>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='" . (defined('PUBLIC_URL') ? PUBLIC_URL : '/') . "'>← Kembali ke Home</a>";
    exit;
}
?>