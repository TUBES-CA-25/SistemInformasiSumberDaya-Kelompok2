<?php
/**
 * API Entry Point
 * * Menggunakan Centralized Routing melalui class Router.
 * Menghilangkan redundansi logika regex dan manual dispatching.
 */

// 1. Inisialisasi Environment
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Definisi Path Konstanta
define('BASE_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', BASE_PATH . '/app');
define('ROOT_PROJECT', BASE_PATH);
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('VIEW_PATH', APP_PATH . '/views');

// 3. Autoloading & Core Files
if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require_once BASE_PATH . '/vendor/autoload.php';
}

// Include core system files
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/helpers/Helper.php';
require_once APP_PATH . '/config/Router.php';

// Pastikan kelas Controller (base) dimuat agar controller API dapat di-extend
if (file_exists(CONTROLLER_PATH . '/Controller.php')) {
    require_once CONTROLLER_PATH . '/Controller.php';
}

// 4. Header CORS & JSON Content Type
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 5. Inisialisasi Router
$router = new Router();

/**
 * 6. Definisi API Routes
 * Seluruh endpoint API didaftarkan di sini menggunakan prefix /api.
 * Key 'method' pada array lama disesuaikan menjadi 'action' agar sinkron dengan Router.
 */
$apiRoutes = [
    'GET' => [
        '/sop'                          => ['controller' => 'SopController', 'action' => 'getJson'],
        '/peraturan-lab'                => ['controller' => 'PeraturanLabController', 'action' => 'apiIndex'],
        '/peraturan-lab/{id}'           => ['controller' => 'PeraturanLabController', 'action' => 'apiShow'],
        '/sanksi-lab'                   => ['controller' => 'SanksiController', 'action' => 'apiIndex'],
        '/sanksi-lab/{id}'              => ['controller' => 'SanksiController', 'action' => 'apiShow'],
        '/alumni'                       => ['controller' => 'AlumniController', 'action' => 'apiIndex'],
        '/alumni/{id}'                  => ['controller' => 'AlumniController', 'action' => 'apiShow'],
        '/fasilitas'                 => ['controller' => 'FasilitasController', 'action' => 'apiIndex'],
        '/fasilitas/{id}'            => ['controller' => 'FasilitasController', 'action' => 'detail'],
        '/asisten'                      => ['controller' => 'AsistenController', 'action' => 'apiIndex'],
        '/asisten/{id}'                 => ['controller' => 'AsistenController', 'action' => 'apiShow'],
        '/asisten/{id}/matakuliah'      => ['controller' => 'AsistenController', 'action' => 'matakuliah'],
        '/matakuliah'                   => ['controller' => 'MatakuliahController', 'action' => 'apiIndex'],
        '/matakuliah/{id}'              => ['controller' => 'MatakuliahController', 'action' => 'apiShow'],
        '/matakuliah/{id}/asisten'      => ['controller' => 'MatakuliahController', 'action' => 'asisten'],
        '/jadwal'                       => ['controller' => 'JadwalPraktikumController', 'action' => 'apiIndex'],
        '/jadwal/{id}'                  => ['controller' => 'JadwalPraktikumController', 'action' => 'apiShow'],
        '/informasi'                    => ['controller' => 'FasilitasController', 'action' => 'apiIndex'],
        '/informasi/{id}'               => ['controller' => 'FasilitasController', 'action' => 'detail'],
        '/manajemen'                    => ['controller' => 'ManajemenController', 'action' => 'apiIndex'],
        '/manajemen/{id}'               => ['controller' => 'ManajemenController', 'action' => 'apiShow'],
        '/formatpenulisan'              => ['controller' => 'FormatPenulisanController', 'action' => 'apiIndex'],
        '/formatpenulisan/{id}'         => ['controller' => 'FormatPenulisanController', 'action' => 'apiShow'],
        '/modul'                        => ['controller' => 'ModulController', 'action' => 'getJson'],
        '/dashboard/stats'              => ['controller' => 'DashboardController', 'action' => 'stats'],
    ],
    'POST' => [
        '/sop'                          => ['controller' => 'SopController', 'action' => 'store'],
        '/peraturan-lab'                => ['controller' => 'PeraturanLabController', 'action' => 'store'],
        '/sanksi-lab'                   => ['controller' => 'SanksiController', 'action' => 'store'],
        '/fasilitas'                    => ['controller' => 'FasilitasController', 'action' => 'store'],
        '/asisten'                      => ['controller' => 'AsistenController', 'action' => 'store'],
        '/asisten/{id}/koordinator'     => ['controller' => 'AsistenController', 'action' => 'setKoordinator'],
        '/jadwal'                       => ['controller' => 'JadwalPraktikumController', 'action' => 'create'],
        '/jadwal/delete-multiple'       => ['controller' => 'JadwalPraktikumController', 'action' => 'deleteMultiple'],
        '/informasi'                    => ['controller' => 'FasilitasController', 'action' => 'store'],
        '/manajemen'                    => ['controller' => 'ManajemenController', 'action' => 'store'],
        '/formatpenulisan'              => ['controller' => 'FormatPenulisanController', 'action' => 'store'],
    ],
    'PUT' => [
        '/sop/{id}'                     => ['controller' => 'SopController', 'action' => 'update'],
        '/peraturan-lab/{id}'           => ['controller' => 'PeraturanLabController', 'action' => 'update'],
        '/sanksi-lab/{id}'              => ['controller' => 'SanksiController', 'action' => 'update'],
        '/fasilitas/{id}'            => ['controller' => 'FasilitasController', 'action' => 'update'],
        '/asisten/{id}'                 => ['controller' => 'AsistenController', 'action' => 'update'],
        '/jadwal/{id}'                  => ['controller' => 'JadwalPraktikumController', 'action' => 'update'],
    ],
    'DELETE' => [
        '/sop/{id}'                     => ['controller' => 'SopController', 'action' => 'delete'],
        '/peraturan-lab/{id}'           => ['controller' => 'PeraturanLabController', 'action' => 'delete'],
        '/sanksi-lab/{id}'              => ['controller' => 'SanksiController', 'action' => 'delete'],
        '/fasilitas/{id}'               => ['controller' => 'FasilitasController', 'action' => 'delete'],
        '/fasilitas/image/{id}'         => ['controller' => 'FasilitasController', 'action' => 'deleteImage'],
        '/asisten/{id}'                 => ['controller' => 'AsistenController', 'action' => 'delete'],
    ]
];

// 7. Daftarkan Routes ke Router
$router->addRoutes($apiRoutes);

// 8. Jalankan Router
$router->dispatch();