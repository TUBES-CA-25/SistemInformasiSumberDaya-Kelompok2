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

// 4. Legacy `?page=` deprecated: tangani dengan 410 Gone
$page = $_GET['page'] ?? '';
if (!empty($page)) {
    http_response_code(410);
    echo "<h1>Halaman Legacy Tidak Tersedia</h1>";
    echo "<p>Parameter <code>?page=</code> telah dihentikan. Silakan gunakan rute MVC yang baru.</p>";
    echo "<ul>";
    echo "<li>Home: <a href='" . PUBLIC_URL . "/home'>/home</a></li>";
    echo "<li>Tata Tertib: <a href='" . PUBLIC_URL . "/tata-tertib'>/tata-tertib</a></li>";
    echo "<li>Jadwal: <a href='" . PUBLIC_URL . "/jadwal'>/jadwal</a></li>";
    echo "<li>Asisten: <a href='" . PUBLIC_URL . "/asisten'>/asisten</a></li>";
    echo "<li>Kepala Lab: <a href='" . PUBLIC_URL . "/kepala-lab'>/kepala-lab</a></li>";
    echo "<li>Laboratorium: <a href='" . PUBLIC_URL . "/laboratorium'>/laboratorium</a></li>";
    echo "<li>Alumni: <a href='" . PUBLIC_URL . "/alumni'>/alumni</a></li>";
    echo "<li>Contact: <a href='" . PUBLIC_URL . "/contact'>/contact</a></li>";
    echo "<li>Login: <a href='" . PUBLIC_URL . "/login'>/login</a></li>";
    echo "<li>Admin: <a href='" . PUBLIC_URL . "/admin'>/admin</a></li>";
    echo "</ul>";
    exit;
}

// 5. Ambil route dari ?route= parameter atau REQUEST_URI
$route = $_GET['route'] ?? null;

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
    $path = '/' . ltrim($path, '/');
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