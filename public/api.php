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
        '/api/sop'                          => ['controller' => 'SopController', 'action' => 'getJson'],
        '/api/peraturan-lab'                => ['controller' => 'PeraturanLabController', 'action' => 'apiIndex'],
        '/api/peraturan-lab/{id}'           => ['controller' => 'PeraturanLabController', 'action' => 'apiShow'],
        '/api/sanksi-lab'                   => ['controller' => 'SanksiController', 'action' => 'apiIndex'],
        '/api/sanksi-lab/{id}'              => ['controller' => 'SanksiController', 'action' => 'apiShow'],
        '/api/alumni'                       => ['controller' => 'AlumniController', 'action' => 'apiIndex'],
        '/api/alumni/{id}'                  => ['controller' => 'AlumniController', 'action' => 'apiShow'],
        '/api/laboratorium'                 => ['controller' => 'FasilitasController', 'action' => 'apiIndex'],
        '/api/laboratorium/{id}'            => ['controller' => 'FasilitasController', 'action' => 'detail'],
        '/api/asisten'                      => ['controller' => 'AsistenController', 'action' => 'apiIndex'],
        '/api/asisten/{id}'                 => ['controller' => 'AsistenController', 'action' => 'apiShow'],
        '/api/asisten/{id}/matakuliah'      => ['controller' => 'AsistenController', 'action' => 'matakuliah'],
        '/api/matakuliah'                   => ['controller' => 'MatakuliahController', 'action' => 'apiIndex'],
        '/api/matakuliah/{id}'              => ['controller' => 'MatakuliahController', 'action' => 'apiShow'],
        '/api/matakuliah/{id}/asisten'      => ['controller' => 'MatakuliahController', 'action' => 'asisten'],
        '/api/jadwal'                       => ['controller' => 'JadwalPraktikumController', 'action' => 'apiIndex'],
        '/api/jadwal/{id}'                  => ['controller' => 'JadwalPraktikumController', 'action' => 'apiShow'],
        '/api/informasi'                    => ['controller' => 'FasilitasController', 'action' => 'apiIndex'],
        '/api/informasi/{id}'               => ['controller' => 'FasilitasController', 'action' => 'detail'],
        '/api/manajemen'                    => ['controller' => 'ManajemenController', 'action' => 'apiIndex'],
        '/api/manajemen/{id}'               => ['controller' => 'ManajemenController', 'action' => 'apiShow'],
        '/api/formatpenulisan'              => ['controller' => 'FormatPenulisanController', 'action' => 'apiIndex'],
        '/api/formatpenulisan/{id}'         => ['controller' => 'FormatPenulisanController', 'action' => 'apiShow'],
        '/api/modul'                        => ['controller' => 'ModulController', 'action' => 'getJson'],
        '/api/dashboard/stats'              => ['controller' => 'DashboardController', 'action' => 'stats'],
    ],
    'POST' => [
        '/api/sop'                          => ['controller' => 'SopController', 'action' => 'store'],
        '/api/peraturan-lab'                => ['controller' => 'PeraturanLabController', 'action' => 'store'],
        '/api/sanksi-lab'                   => ['controller' => 'SanksiController', 'action' => 'store'],
        '/api/laboratorium'                 => ['controller' => 'FasilitasController', 'action' => 'store'],
        '/api/asisten'                      => ['controller' => 'AsistenController', 'action' => 'store'],
        '/api/asisten/{id}/koordinator'     => ['controller' => 'AsistenController', 'action' => 'setKoordinator'],
        '/api/jadwal'                       => ['controller' => 'JadwalPraktikumController', 'action' => 'create'],
        '/api/jadwal/delete-multiple'       => ['controller' => 'JadwalPraktikumController', 'action' => 'deleteMultiple'],
        '/api/informasi'                    => ['controller' => 'FasilitasController', 'action' => 'store'],
        '/api/manajemen'                    => ['controller' => 'ManajemenController', 'action' => 'store'],
        '/api/formatpenulisan'              => ['controller' => 'FormatPenulisanController', 'action' => 'store'],
    ],
    'PUT' => [
        '/api/sop/{id}'                     => ['controller' => 'SopController', 'action' => 'update'],
        '/api/laboratorium/{id}'            => ['controller' => 'FasilitasController', 'action' => 'update'],
        '/api/asisten/{id}'                 => ['controller' => 'AsistenController', 'action' => 'update'],
        '/api/jadwal/{id}'                  => ['controller' => 'JadwalPraktikumController', 'action' => 'update'],
    ],
    'DELETE' => [
        '/api/sop/{id}'                     => ['controller' => 'SopController', 'action' => 'delete'],
        '/api/laboratorium/{id}'            => ['controller' => 'FasilitasController', 'action' => 'delete'],
        '/api/laboratorium/image/{id}'      => ['controller' => 'FasilitasController', 'action' => 'deleteImage'],
        '/api/asisten/{id}'                 => ['controller' => 'AsistenController', 'action' => 'delete'],
    ]
];

// 7. Daftarkan Routes ke Router
$router->addRoutes($apiRoutes);

// 8. Jalankan Router
$router->dispatch();