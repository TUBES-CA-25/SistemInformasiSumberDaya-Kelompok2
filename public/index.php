<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT_PROJECT', dirname(__DIR__));
define('APP_PATH',        ROOT_PROJECT . '/app');
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('VIEW_PATH',       APP_PATH . '/views');
define('CORE_PATH',       APP_PATH . '/core');
define('CONFIG_PATH',     APP_PATH . '/config');

require_once APP_PATH . '/config/config.php';

// Tambahan: fallback aman untuk PUBLIC_URL dan ASSETS_URL jika belum didefinisikan
if (!defined('PUBLIC_URL')) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000';
    define('PUBLIC_URL', rtrim($scheme . '://' . $host, '/'));
}
if (!defined('ASSETS_URL')) {
    define('ASSETS_URL', PUBLIC_URL);
}

// Pastikan koneksi Database tersedia
$db = null;
if (file_exists(CORE_PATH . '/Database.php')) {
    require_once CORE_PATH . '/Database.php';
    global $pdo;
    $db = new Database();
    $pdo = $db->getPdo();
} elseif (file_exists(APP_PATH . '/config/Database.php')) {
    require_once APP_PATH . '/config/Database.php';
    global $pdo;
    if (class_exists('Database')) {
        $db = new Database();
        if (!isset($pdo) && method_exists($db, 'getPdo')) {
            $pdo = $db->getPdo();
        }
    }
}

if (file_exists(CORE_PATH . '/Controller.php')) {
    require_once CORE_PATH . '/Controller.php';
} elseif (file_exists(CONTROLLER_PATH . '/Controller.php')) {
    require_once CONTROLLER_PATH . '/Controller.php';
}

$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri_clean   = explode('?', $request_uri)[0];

// Redirect root access to /home (kecuali admin)
if (in_array($uri_clean, ['/', '', '/index.php'], true)) {
    if (strpos($uri_clean, '/admin') === false && strpos($uri_clean, '/dashboard') === false) {
        $hasRoutingQuery = !empty($_GET['page']) || !empty($_GET['route']) || !empty($_GET['id']);
        if (!$hasRoutingQuery) {
            header('Location: ' . PUBLIC_URL . '/home', true, 302);
            exit;
        }
    }
}

$isAdminArea = (strpos($uri_clean, '/admin') !== false) || (strpos($uri_clean, '/dashboard') !== false);

/* ===============================
   ROUTING ADMIN (DIPERBAIKI)
=============================== */
if ($isAdminArea) {

    if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
        header("Location: " . PUBLIC_URL . "/login");
        exit;
    }

    $parts   = explode('/admin/', $uri_clean);
    $subPath = isset($parts[1]) ? trim($parts[1], '/') : '';

    if (empty($subPath)) {
        $module = 'dashboard';
        $action = 'index';
    } else {
        $subParts = array_values(array_filter(explode('/', $subPath), fn($s) => $s !== ''));
        $module   = $subParts[0] ?? 'dashboard';
        
        // [FIX] Cek apakah segmen ke-2 adalah ID (angka)
        if (isset($subParts[1]) && is_numeric($subParts[1])) {
            // Format: /admin/module/ID/action (contoh: /admin/asisten/2/edit)
            $_GET['id'] = $subParts[1]; // Inject ID ke $_GET agar view bisa membacanya
            $action     = $subParts[2] ?? 'index'; // Jika tidak ada action, default ke index/detail
        } else {
            // Format: /admin/module/action (contoh: /admin/asisten/create)
            $action = $subParts[1] ?? 'index';
        }
    }

    $adminHeader = VIEW_PATH . '/admin/templates/header.php';
    $adminFooter = VIEW_PATH . '/admin/templates/footer.php';

    if ($module === 'dashboard') {
        $targetFile = VIEW_PATH . '/admin/index.php';
    } else {
        // Cari file view spesifik berdasarkan action (misal: edit.php, create.php)
        $specificFile = VIEW_PATH . '/admin/' . $module . '/' . $action . '.php';
        $indexFile    = VIEW_PATH . '/admin/' . $module . '/index.php';

        if (file_exists($specificFile)) {
            $targetFile = $specificFile;
        } else {
            // Fallback: jika create/edit tidak ada file spesifik, cek form.php
            if (in_array($action, ['create', 'edit'])) {
                $formFile = VIEW_PATH . '/admin/' . $module . '/form.php';
                if (file_exists($formFile)) {
                    $targetFile = $formFile;
                } else {
                    $targetFile = $indexFile;
                }
            } else {
                $targetFile = $indexFile;
            }
        }
    }

    if (file_exists($adminHeader)) require_once $adminHeader;

    if (file_exists($targetFile)) {
        chdir(dirname($targetFile));
        require_once $targetFile;
    } else {
        echo "<div style='margin-left:260px; padding:30px; color:red;'><h3>404 - Admin Page Not Found</h3><p>Target: $targetFile</p></div>";
    }

    chdir(ROOT_PROJECT . '/public');
    if (file_exists($adminFooter)) require_once $adminFooter;
    exit;
}


/* ===============================
   NORMALISASI URL (PUBLIC)
=============================== */

$pageParam = $_GET['page'] ?? null;
$routeParam = $_GET['route'] ?? null;
$uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

$path = trim($pageParam ?? $routeParam ?? $uriPath, '/');

$segments = array_values(array_filter(
    explode('/', $path),
    fn($s) => $s !== ''
));

$page = $segments[0] ?? 'home';
if ($page === 'index.php') $page = 'home';

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
    'daftar-asisten' => 'asisten'
];

if (array_key_exists($page, $aliases)) {
    $page = $aliases[$page];
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
   CSS
=============================== */

$pageCss = '';
if ($page == 'home')                              $pageCss = 'home.css';
if ($page == 'tatatertib' || $page == 'jadwal')   $pageCss = 'praktikum.css';
if ($page == 'kepala' || $page == 'asisten' || $page == 'detail')      $pageCss = 'sumberdaya.css';
if ($page == 'laboratorium' || $page == 'riset' || $page == 'detail_fasilitas')  $pageCss = 'fasilitas.css';
if ($page == 'apps')                              $pageCss = 'apps.css';
if ($page == 'contact')                           $pageCss = 'contact.css';
if ($page == 'alumni' || $page == 'detail_alumni')                            $pageCss = 'alumni.css';


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
   DIRECT VIEW
=============================== */

$direct_views = [
    'home'         => 'home/index.php',
    'apps'         => 'home/apps.php',
    'jadwal'       => 'praktikum/jadwal.php',
    'tatatertib'   => 'praktikum/tatatertib.php',
    'riset'        => 'fasilitas/riset.php',
    'laboratorium' => 'fasilitas/laboratorium.php',
    'detail_fasilitas' => 'fasilitas/detail.php',
    'kepala'       => 'sumberdaya/kepala.php'
];

if (array_key_exists($page, $direct_views)) {
    if ($page === 'kepala' && file_exists(CONTROLLER_PATH . '/KepalaLabController.php')) {
        require_once CONTROLLER_PATH . '/KepalaLabController.php';
        $ctl = new KepalaLabController();
        $ctl->index();
        exit;
    }

    if (file_exists(VIEW_PATH . '/templates/header.php')) require_once VIEW_PATH . '/templates/header.php';
    $viewFile = VIEW_PATH . '/' . $direct_views[$page];
    if (file_exists($viewFile)) {
        require_once $viewFile;
    } else {
        echo "<div style='padding:50px; text-align:center;'><h3>Halaman Tidak Ditemukan</h3></div>";
    }
    if (file_exists(VIEW_PATH . '/templates/footer.php')) require_once VIEW_PATH . '/templates/footer.php';
    exit;
}


/* ===============================
   MVC ROUTES
=============================== */

$mvc_routes = [
    'contact'          => ['ContactController', 'index', []],
    'login'            => ['AuthController', 'login', []],
    'auth'             => ['AuthController', 'authenticate', []],
    'logout'           => ['AuthController', 'logout', []],

    'asisten'          => ['AsistenController', 'index', []],
    'detail'           => ['AsistenController', 'detail', ['id' => $id]],
    'detail-asisten'   => ['AsistenController', 'detail', ['id' => $id]],

    'alumni'           => ['AlumniController', 'index', []],
    'detail_alumni'    => ['AlumniController', 'detail', ['id' => $id]],

    'detail_fasilitas' => ['LaboratoriumController', 'detail', ['id' => $id]],
    'detail_manajemen' => ['KepalaLabController', 'detail', ['id' => $id]],
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
    if (file_exists(VIEW_PATH . '/templates/header.php')) require_once VIEW_PATH . '/templates/header.php';
    if (file_exists(VIEW_PATH . '/home/index.php')) require_once VIEW_PATH . '/home/index.php';
    if (file_exists(VIEW_PATH . '/templates/footer.php')) require_once VIEW_PATH . '/templates/footer.php';
}
?>