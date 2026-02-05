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


/* ===============================
   NORMALISASI URL (PUBLIC)
=============================== */

$pageParam = $_GET['page'] ?? null;
$routeParam = $_GET['route'] ?? null;

$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri_clean   = explode('?', $request_uri)[0];
$script_name = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$uriPath     = str_replace($script_name, '', $uri_clean);

$path = trim($pageParam ?? $routeParam ?? $uriPath, '/');

$segments = array_values(array_filter(
    explode('/', $path),
    fn($s) => $s !== ''
));

$page = $segments[0] ?? 'home';
if ($page === 'index.php' || empty($page)) {
    $page = 'home';
}

$id = $_GET['id'] ?? ($segments[1] ?? null);


/* ===============================
   ALIASES
=============================== */

$aliases = [
    'tata-tertib'    => 'tatatertib',
    'peraturan'      => 'tatatertib',
    'kepala-lab'     => 'kepala',
    'struktur'       => 'kepala',
    'profil'         => 'kepala',
    'fasilitas'      => 'riset',
    'kontak'         => 'contact',
    'hubungi'        => 'contact',
    'daftar-asisten' => 'asisten',
    'peta'           => 'denah',
    'floorplan'      => 'denah'
];

if (array_key_exists($page, $aliases)) {
    $page = $aliases[$page];
}

// UPDATE: Routing khusus untuk /laboratorium/denah agar dikenali sebagai 'denah'
if ($page === 'laboratorium' && isset($segments[1]) && $segments[1] === 'denah') {
    $page = 'denah'; 
}

// Support shorthand URLs logic (Public)
if (isset($segments[1]) && $segments[1] !== '') {
    $second = $segments[1];
    if (is_numeric($second)) {
        if ($page === 'asisten' || $page === 'daftar-asisten') {
            $id = $second;
            $page = 'detail';
        } elseif ($page === 'alumni') {
            $id = $second;
            $page = 'detail_alumni';
        } elseif (in_array($page, ['laboratorium', 'fasilitas', 'riset'])) {
            $id = $second;
            $page = 'detail_fasilitas';
        }
    } else {
        if ($second === 'detail' && isset($segments[2]) && is_numeric($segments[2])) {
            $id = $segments[2];
            if ($page === 'asisten') $page = 'detail';
            if ($page === 'alumni') $page = 'detail_alumni';
            if (in_array($page, ['laboratorium', 'fasilitas', 'riset'])) $page = 'detail_fasilitas';
        }
    }
}


/* ===============================
   ROUTE DETAIL
=============================== */

if (($segments[1] ?? '') === 'detail') {
    $id = $segments[2] ?? $id;

    if ($page === 'asisten') {
        $page = 'detail';
    } elseif ($page === 'alumni') {
        $page = 'detail_alumni';
    } elseif ($page === 'laboratorium') {
        $page = 'detail_fasilitas';
    }
}

/* ===============================
   MVC ROUTES
=============================== */

$mvc_routes = [
    // --- AUTH & HOME ---
    'home'             => ['HomeController', 'index', []],
    'contact'          => ['ContactController', 'index', []],
    'apps'             => ['HomeController', 'apps', []],
    'iclabs-login'     => ['AuthController', 'login', []],
    'auth'             => ['AuthController', 'authenticate', []],
    'logout'           => ['AuthController', 'logout', []],

    // --- SUMBER DAYA & DETAIL (PERUBAHAN UTAMA DISINI) ---
    'kepala'          => ['ManajemenController', 'index', []],
    'asisten'          => ['AsistenController', 'index', []],
    'alumni'           => ['AlumniController', 'index', []],
    'detail'           => ['DetailSumberDayaController', 'index', []],

    // Jika ingin halaman detail alumni tetap terpisah:
    'detail_alumni'    => ['AlumniController', 'detail', []], 

    // --- MANAJEMEN LAINNYA ---
    'jadwal'           => ['JadwalPraktikumController', 'index', []],
    'jadwalupk'        => ['JadwalUpkController', 'index', []],
    'formatpenulisan'  => ['FormatPenulisanController', 'index', []],
    
    // --- LABORATORIUM & FASILITAS ---
    'laboratorium'     => ['LaboratoriumController', 'index', []], 
    'riset'            => ['LaboratoriumController', 'riset', []], 
    'detail_fasilitas' => ['LaboratoriumController', 'detail', []],
    'denah'            => ['LaboratoriumController', 'denah', []],

    // --- DOKUMEN & ATURAN ---
    'modul'            => ['ModulController', 'index', []],
    'sop'              => ['SopController', 'index', []],
    'tatatertib'       => ['PraktikumController', 'tatatertib', []],
    'peraturan'        => ['PraktikumController', 'tatatertib', []],
];

if (array_key_exists($page, $mvc_routes)) {

    $route = $mvc_routes[$page];
    $controllerName = $route[0];
    $methodName     = $route[1];
    $params         = $route[2];

    $controllerFile = CONTROLLER_PATH . '/' . $controllerName . '.php';

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $controllerName();
        call_user_func_array([$controller, $methodName], [$params]);
    } else {
        $fallback = null;
        if ($page == 'contact') $fallback = 'contact/index.php';
        if ($page == 'alumni')  $fallback = 'alumni/index.php';

        if ($fallback && file_exists(VIEW_PATH . '/' . $fallback)) {
            if (file_exists(VIEW_PATH . '/templates/header.php')) require_once VIEW_PATH . '/templates/header.php';
            require_once VIEW_PATH . '/' . $fallback;
            if (file_exists(VIEW_PATH . '/templates/footer.php')) require_once VIEW_PATH . '/templates/footer.php';
        } else {
            echo "<div style='padding:50px; text-align:center;'>";
            echo "<h3>Halaman Sedang Dalam Perbaikan</h3>";
            echo "<p>Controller <b>$controllerName</b> belum tersedia.</p>";
            echo "<a href='".PUBLIC_URL."/home'>Kembali ke Home</a>";
            echo "</div>";
        }
    }

} else {
    // Jika rute tidak ditemukan, arahkan ke home
    header('Location: ' . PUBLIC_URL . '/home');
    exit;
}
?>
