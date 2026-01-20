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

// --- DETEKSI URL PUBLIK ---
if (!defined('PUBLIC_URL')) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    
    // Cek apakah kita pakai port 8000 (Built-in server)
    if (strpos($host, ':8000') !== false) {
        define('PUBLIC_URL', $scheme . '://' . $host);
    } else {
        // Jika pakai Apache (XAMPP), ambil folder projectnya
        $script_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        define('PUBLIC_URL', $scheme . '://' . $host . $script_dir);
    }
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
   ROUTING ADMIN
=============================== */
if ($isAdminArea) {

    if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
        header("Location: " . PUBLIC_URL . "/home");
        exit;
    }

    // Load Router dan gunakan untuk dispatch admin requests
    require_once CONFIG_PATH . '/Router.php';
    $router = new Router();
    $router->dispatch();
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
    // UPDATE: Alias untuk denah
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
   CSS MAPPING
=============================== */

$pageCss = 'style.css'; 
if ($page == 'home')                               $pageCss = 'home.css';
if (in_array($page, ['tatatertib', 'jadwal', 'jadwalupk',  'modul','formatpenulisan'])) {
    $pageCss = 'praktikum.css';
}
if (in_array($page, ['kepala', 'asisten', 'detail', 'sop'])) {
    $pageCss = 'sumberdaya.css';
}
// UPDATE: Tambahkan 'denah' agar memuat fasilitas.css
if (in_array($page, ['laboratorium', 'riset', 'detail_fasilitas', 'denah'])) {
    $pageCss = 'fasilitas.css';
}
if ($page == 'apps')                               $pageCss = 'apps.css';
if ($page == 'contact')                            $pageCss = 'contact.css';
if (in_array($page, ['alumni', 'detail_alumni']))  $pageCss = 'alumni.css';


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
    'formatpenulisan' => 'praktikum/format_penulisan.php',
    'riset'        => 'fasilitas/riset.php',
    'laboratorium' => 'fasilitas/laboratorium.php',
    'detail_fasilitas' => 'fasilitas/detail.php',
    'kepala'       => 'sumberdaya/kepala.php',
    // Opsi tambahan jika ingin view langsung tanpa controller (tapi disarankan via MVC route di bawah)
    // 'denah'     => 'fasilitas/denah.php' 
];

if (array_key_exists($page, $direct_views)) {
    if ($page === 'formatpenulisan') {
        require_once CONTROLLER_PATH . '/FormatPenulisanController.php';
        $ctl = new FormatPenulisanController();
        $ctl->index();
        exit;
    }

    if ($page === 'kepala' && file_exists(CONTROLLER_PATH . '/ManajemenController.php')) {
        require_once CONTROLLER_PATH . '/ManajemenController.php';
        $ctl = new ManajemenController();
        $ctl->kepalaIndex();
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
    'pintuSISDA'       => ['AuthController', 'login', []],
    'auth'             => ['AuthController', 'authenticate', []],
    'logout'           => ['AuthController', 'logout', []],

    'asisten'          => ['AsistenController', 'index', []],
    'jadwalupk'        => ['JadwalUpkController', 'index', []],
    'formatpenulisan'  => ['FormatPenulisanController', 'index', []],
    'detail'           => ['AsistenController', 'detail', ['id' => $id]],
    'detail-asisten'   => ['AsistenController', 'detail', ['id' => $id]],

    'alumni'           => ['AlumniController', 'index', []],
    'detail_alumni'    => ['AlumniController', 'detail', ['id' => $id]],

    'detail_fasilitas' => ['LaboratoriumController', 'detail', ['id' => $id]],
    'detail_manajemen' => ['ManajemenController', 'kepalaDetail', ['id' => $id]],

    'modul'            => ['ModulController', 'index', []],

    'sop'              => ['SopController', 'index', []],
    
    // UPDATE: Menambahkan route untuk Denah
    'denah'            => ['LaboratoriumController', 'denah', []],
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