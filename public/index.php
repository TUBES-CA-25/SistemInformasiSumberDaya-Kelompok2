<?php
// FILE: public/index.php - Integrated MVC Router System

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

// 4. Check routing system to use (early optimization)
$route = $_GET['route'] ?? '';
$page = $_GET['page'] ?? '';

// Priority: route system (MVC) > page system (legacy)
if (!empty($route)) {
    // Load dependencies only when needed for MVC routes
    require_once CONFIG_PATH . '/Database.php';
    require_once APP_PATH . '/helpers/Helper.php';
    require_once CONFIG_PATH . '/Router.php';
    
    try {
        $router = new Router();
        $router->dispatch();
        exit; // Important: exit after router handles the request
    } catch (Exception $e) {
        // If router fails, show error
        http_response_code(500);
        echo "<h1>Routing Error</h1>";
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<a href='" . ($_ENV['APP_URL'] ?? '/') . "'>← Back to Home</a>";
        exit;
    }
}

// 5. Fallback to legacy page system if no route
if (empty($page)) {
    $page = 'home';
}

// 6. Legacy Page System Configuration (optimized)
$contentView = '';
$pageCss = ''; 

switch ($page) {
    case 'home':
        $contentView = '/app/views/home/index.php';
        $pageCss = 'home.css';
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

    // Redirect to MVC routes
    case 'login':
    case 'admin':
    case 'dashboard':
        header('Location: ' . PUBLIC_URL . '/' . $page);
        exit;
        break;

    default:
        echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
        echo "<p>Halaman yang Anda cari tidak ditemukan.</p>";
        echo "<p><strong>Tips:</strong></p>";
        echo "<ul>";
        echo "<li>Untuk halaman admin, gunakan: <a href='" . PUBLIC_URL . "/login'>Login Admin</a></li>";
        echo "<li>Untuk halaman publik, gunakan parameter ?page=nama</li>";
        echo "</ul>";
        exit;
}

// 7. RAKIT HALAMAN
// Gunakan require_once dengan Path Absolut (ROOT_PROJECT)

// a. Header
require_once ROOT_PROJECT . '/app/views/templates/header.php';

// b. Konten Tengah
if (file_exists(ROOT_PROJECT . $contentView)) {
    require_once ROOT_PROJECT . $contentView;
} else {
    echo "<div style='text-align:center; padding:50px; font-family:sans-serif;'>";
    echo "<h2 style='color:red;'>Error: File View Tidak Ditemukan</h2>";
    echo "<p>Sistem mencoba membuka: <strong>" . $contentView . "</strong></p>";
    echo "<p>Pastikan file tersebut ada di dalam folder <code>app/views/...</code></p>";
    echo "</div>";
}

// c. Footer
require_once ROOT_PROJECT . '/app/views/templates/footer.php';

// =====================================
// ROUTING INTEGRATION SUMMARY
// =====================================
// 
// This application now supports TWO routing systems:
//
// 1. MVC ROUTE SYSTEM (Recommended for admin/auth):
//    - URL: /login, /admin, /admin/dashboard, etc.
//    - Handled by Router.php with Controllers
//    - Supports authentication middleware
//    - Examples:
//      * /login → AuthController::login
//      * /admin → DashboardController::index
//      * /admin/alumni → AlumniController::adminIndex
//
// 2. LEGACY PAGE SYSTEM (For public pages):
//    - URL: ?page=home, ?page=contact, etc.
//    - Simple file-based routing
//    - No authentication required
//    - Examples:
//      * ?page=home → /app/views/home/index.php
//      * ?page=contact → /app/views/contact/index.php
//
// How to access:
// - Admin Login: http://localhost/SistemInformasiSumberDaya-Kelompok2/public/login
// - Admin Dashboard: http://localhost/SistemInformasiSumberDaya-Kelompok2/public/admin
// - Public Pages: http://localhost/SistemInformasiSumberDaya-Kelompok2/public/?page=home
//
// =====================================
?>