<?php
// FILE: public/index.php - Full MVC Router Entry

// 1. Tampilkan Error (Debugging)
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

// [2.1] LOAD CONFIGURATION (Wajib untuk Konstanta ASSETS_URL, DB_HOST, dll)
require_once ROOT_PROJECT . '/app/config/config.php';

// [2.2] LOAD DATABASE CONNECTION (Wajib agar $pdo tersedia)
// Pastikan file ini ada di folder app/config/
require_once ROOT_PROJECT . '/app/config/database.php';

// 3. Tangkap Request Halaman (Default 'home')
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// 4. Konfigurasi Halaman
$contentView = '';
$pageCss = ''; 

switch ($page) {
    case 'home':
        $contentView = '/app/views/home/index.php';
        $pageCss = 'home.css';
        break;

    // --- FITUR APPS (BARU) ---
    case 'apps':
        // Pastikan Anda menyimpan file apps.php di folder app/views/home/
        $contentView = '/app/views/home/apps.php'; 
        $pageCss = 'apps.css';
        break;

    // --- PRAKTIKUM ---
    case 'tatatertib':
        $contentView = '/app/views/praktikum/tatatertib.php';
        $pageCss = 'praktikum.css';
        break;
    case 'jadwal':
        $contentView = '/app/views/praktikum/jadwal.php';
        $pageCss = 'praktikum.css';
        break;

    // --- SUMBER DAYA ---
    case 'asisten':
        $contentView = '/app/views/sumberdaya/asisten.php';
        $pageCss = 'sumberdaya.css'; 
        break;
    case 'detail': 
        $contentView = '/app/views/sumberdaya/detail.php';
        $pageCss = 'sumberdaya.css'; 
        break;
    case 'kepala':
        $contentView = '/app/views/sumberdaya/kepala.php';
        $pageCss = 'sumberdaya.css';
        break;
    
    // --- FASILITAS ---
    case 'laboratorium':
        $contentView = '/app/views/fasilitas/laboratorium.php';
        $pageCss = 'fasilitas.css';
        break;
    case 'riset':
        $contentView = '/app/views/fasilitas/riset.php';
        $pageCss = 'fasilitas.css';
        break;
    case 'detail_fasilitas':
        $contentView = '/app/views/fasilitas/detail.php'; 
        $pageCss = 'fasilitas.css';
        break;

    // --- LAINNYA ---
    case 'alumni':
        $contentView = '/app/views/alumni/alumni.php'; 
        $pageCss = 'alumni.css'; 
        break;

    case 'detail_alumni':
        $contentView = '/app/views/alumni/detail.php'; 
        $pageCss = 'alumni.css';
        break;

    case 'contact':
        $contentView = '/app/views/contact/index.php';
        $pageCss = 'contact.css';
        break;

    default:
        echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
        exit;
}

// 5. RAKIT HALAMAN

// Jika tidak ada route param, coba parse dari REQUEST_URI
if (empty($route)) {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '/';
    // Remove /SistemInformasiSumberDaya-Kelompok2/public/ prefix if exists
    $route = preg_replace('#^/SistemInformasiSumberDaya-Kelompok2/public/?#', '', $request_uri);
    // Remove query string
    $route = explode('?', $route)[0];
}

$path = '/' . trim($route, '/ ');
if ($path === '/') { 
    $path = '/'; 
} else {
    echo "<div style='text-align:center; padding:50px;'>";
    echo "<h2 style='color:red;'>Error: File View Tidak Ditemukan</h2>";
    echo "<p>Sistem mencoba membuka: <strong>" . $contentView . "</strong></p>";
    echo "</div>";
}

// 6. Load MVC dependencies & dispatch Router
require_once CONFIG_PATH . '/Database.php';
require_once APP_PATH . '/helpers/Helper.php';
require_once CONFIG_PATH . '/Router.php';

// Set route untuk Router
$_GET['route'] = $path;

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