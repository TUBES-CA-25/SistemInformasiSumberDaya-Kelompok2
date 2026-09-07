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
if (file_exists(APP_PATH . '/helpers/Cache.php')) {
    require_once APP_PATH . '/helpers/Cache.php';
}
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
        '/peraturan'                    => ['controller' => 'PeraturanLabController', 'action' => 'apiIndex'],
        '/sanksi-lab'                   => ['controller' => 'SanksiController', 'action' => 'apiIndex'],
        '/sanksi-lab/{id}'              => ['controller' => 'SanksiController', 'action' => 'apiShow'],
        '/sanksi'                       => ['controller' => 'SanksiController', 'action' => 'apiIndex'],
        '/alumni'                       => ['controller' => 'AlumniController', 'action' => 'apiIndex'],
        '/alumni/{id}'                  => ['controller' => 'AlumniController', 'action' => 'apiShow'],
        '/fasilitas'                    => ['controller' => 'FasilitasController', 'action' => 'apiIndex'],
        '/fasilitas/{id}'               => ['controller' => 'FasilitasController', 'action' => 'detail'],
        '/asisten'                      => ['controller' => 'AsistenController', 'action' => 'apiIndex'],
        '/asisten/coordinator/current'  => ['controller' => 'AsistenController', 'action' => 'getCoordinator'],
        '/asisten/{id}'                 => ['controller' => 'AsistenController', 'action' => 'apiShow'],
        '/asisten/{id}/matakuliah'      => ['controller' => 'AsistenController', 'action' => 'matakuliah'],
        '/matakuliah'                   => ['controller' => 'MatakuliahController', 'action' => 'apiIndex'],
        '/matakuliah/{id}'              => ['controller' => 'MatakuliahController', 'action' => 'apiShow'],
        '/matakuliah/{id}/asisten'      => ['controller' => 'MatakuliahController', 'action' => 'asisten'],
        '/dosen'                        => ['controller' => 'DosenController', 'action' => 'apiIndex'],
        '/dosen/{id}'                   => ['controller' => 'DosenController', 'action' => 'apiShow'],
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
        '/jadwal-praktikum/template'    => ['controller' => 'JadwalPraktikumController', 'action' => 'downloadTemplate'],
        '/jadwal-upk'                   => ['controller' => 'JadwalUpkController', 'action' => 'apiIndex'],
        '/jadwal-upk/status'            => ['controller' => 'JadwalUpkController', 'action' => 'getStatus'],
        '/jadwal-upk/{id}'              => ['controller' => 'JadwalUpkController', 'action' => 'apiShow'],
        '/jadwal-upk/template'          => ['controller' => 'JadwalUpkController', 'action' => 'downloadTemplate'],
        '/showcase'                     => ['controller' => 'ShowcaseController', 'action' => 'apiIndex'],
        '/sumberdaya/detail/{id}'       => ['controller' => 'DetailSumberDayaController', 'action' => 'apiDetail'],
        '/user'                         => ['controller' => 'UserController', 'action' => 'apiIndex'],
        '/user/{id}'                    => ['controller' => 'UserController', 'action' => 'apiShow'],
        '/kontak-info'                  => ['controller' => 'KontakController', 'action' => 'apiIndex'],
        '/kontak-info/{id}'             => ['controller' => 'KontakController', 'action' => 'apiShow'],
        '/kontak-pesan'                 => ['controller' => 'KontakController', 'action' => 'apiMessages'],
        '/kontak-pesan/{id}'            => ['controller' => 'KontakController', 'action' => 'apiMessageDetail'],
        '/apps'                         => ['controller' => 'AppsController', 'action' => 'apiIndex'],
        '/apps/{id}'                    => ['controller' => 'AppsController', 'action' => 'apiShow'],
    ],
    'POST' => [
        '/sop'                          => ['controller' => 'SopController', 'action' => 'store'],
        '/peraturan-lab'                => ['controller' => 'PeraturanLabController', 'action' => 'store'],
        '/peraturan-lab/{id}'           => ['controller' => 'PeraturanLabController', 'action' => 'update'],
        '/sanksi-lab'                   => ['controller' => 'SanksiController', 'action' => 'store'],
        '/sanksi-lab/{id}'              => ['controller' => 'SanksiController', 'action' => 'update'],
        '/fasilitas'                    => ['controller' => 'FasilitasController', 'action' => 'store'],
        '/fasilitas/{id}'               => ['controller' => 'FasilitasController', 'action' => 'update'],
        '/asisten'                      => ['controller' => 'AsistenController', 'action' => 'store'],
        '/asisten/{id}'                 => ['controller' => 'AsistenController', 'action' => 'update'],
        '/asisten/{id}/move-to-alumni'  => ['controller' => 'AsistenController', 'action' => 'moveToAlumni'],
        '/asisten/coordinator/set'      => ['controller' => 'AsistenController', 'action' => 'setCoordinator'],
        '/asisten/{id}/koordinator'     => ['controller' => 'AsistenController', 'action' => 'setKoordinator'],
        '/alumni'                       => ['controller' => 'AlumniController', 'action' => 'store'],
        '/alumni/{id}'                  => ['controller' => 'AlumniController', 'action' => 'update'],
        '/matakuliah'                   => ['controller' => 'MatakuliahController', 'action' => 'store'],
        '/matakuliah/{id}'              => ['controller' => 'MatakuliahController', 'action' => 'update'],
        '/dosen'                        => ['controller' => 'DosenController', 'action' => 'store'],
        '/dosen/{id}'                   => ['controller' => 'DosenController', 'action' => 'update'],
        '/jadwal'                       => ['controller' => 'JadwalPraktikumController', 'action' => 'store'],
        '/jadwal/delete-multiple'       => ['controller' => 'JadwalPraktikumController', 'action' => 'deleteMultiple'],
        '/jadwal-praktikum/upload'      => ['controller' => 'JadwalPraktikumController', 'action' => 'uploadApi'],
        '/jadwal/{id}'                  => ['controller' => 'JadwalPraktikumController', 'action' => 'update'],
        '/informasi'                    => ['controller' => 'FasilitasController', 'action' => 'store'],
        '/manajemen'                    => ['controller' => 'ManajemenController', 'action' => 'store'],
        '/manajemen/{id}'               => ['controller' => 'ManajemenController', 'action' => 'update'],
        '/formatpenulisan'              => ['controller' => 'FormatPenulisanController', 'action' => 'store'],
        '/formatpenulisan/{id}'         => ['controller' => 'FormatPenulisanController', 'action' => 'update'],
        '/modul'                        => ['controller' => 'ModulController', 'action' => 'store'],
        '/modul/{id}'                   => ['controller' => 'ModulController', 'action' => 'update'],
        '/jadwal-upk'                   => ['controller' => 'JadwalUpkController', 'action' => 'store'],
        '/jadwal-upk/toggle-status'     => ['controller' => 'JadwalUpkController', 'action' => 'toggleStatus'],
        '/jadwal-upk/delete-multiple'   => ['controller' => 'JadwalUpkController', 'action' => 'deleteMultiple'],
        '/jadwal-upk/upload'            => ['controller' => 'JadwalUpkController', 'action' => 'upload'],
        '/jadwal-upk/{id}'              => ['controller' => 'JadwalUpkController', 'action' => 'update'],
        '/showcase'                     => ['controller' => 'ShowcaseController', 'action' => 'store'],
        '/showcase/{id}'                => ['controller' => 'ShowcaseController', 'action' => 'update'],
        '/kontak'                       => ['controller' => 'KontakController', 'action' => 'send'],
        '/kontak-info'                  => ['controller' => 'KontakController', 'action' => 'apiStore'],
        '/kontak-info/{id}'             => ['controller' => 'KontakController', 'action' => 'apiUpdate'],
        '/kontak-info/{id}/toggle'      => ['controller' => 'KontakController', 'action' => 'apiToggle'],
        '/kontak-pesan/{id}/baca'       => ['controller' => 'KontakController', 'action' => 'apiMarkRead'],
        '/apps'                         => ['controller' => 'AppsController', 'action' => 'apiStore'],
        '/apps/{id}'                    => ['controller' => 'AppsController', 'action' => 'apiUpdate'],
        '/apps/{id}/toggle'             => ['controller' => 'AppsController', 'action' => 'apiToggle'],
        '/user'                         => ['controller' => 'UserController', 'action' => 'apiStore'],
        '/user/{id}'                    => ['controller' => 'UserController', 'action' => 'apiUpdate'],
    ],
    'PUT' => [
        '/sop/{id}'                     => ['controller' => 'SopController', 'action' => 'update'],
        '/peraturan-lab/{id}'           => ['controller' => 'PeraturanLabController', 'action' => 'update'],
        '/sanksi-lab/{id}'              => ['controller' => 'SanksiController', 'action' => 'update'],
        '/fasilitas/{id}'               => ['controller' => 'FasilitasController', 'action' => 'update'],
        '/asisten/{id}'                 => ['controller' => 'AsistenController', 'action' => 'update'],
        '/alumni/{id}'                  => ['controller' => 'AlumniController', 'action' => 'update'],
        '/matakuliah/{id}'              => ['controller' => 'MatakuliahController', 'action' => 'update'],
        '/dosen/{id}'                   => ['controller' => 'DosenController', 'action' => 'update'],
        '/jadwal/{id}'                  => ['controller' => 'JadwalPraktikumController', 'action' => 'update'],
        '/manajemen/{id}'               => ['controller' => 'ManajemenController', 'action' => 'update'],
        '/jadwal-upk/{id}'              => ['controller' => 'JadwalUpkController', 'action' => 'update'],
        '/modul/{id}'                   => ['controller' => 'ModulController', 'action' => 'update'],
        '/formatpenulisan/{id}'         => ['controller' => 'FormatPenulisanController', 'action' => 'update'],
        '/showcase/{id}'                => ['controller' => 'ShowcaseController', 'action' => 'update'],
        '/kontak-info/{id}'             => ['controller' => 'KontakController', 'action' => 'apiUpdate'],
        '/apps/{id}'                    => ['controller' => 'AppsController', 'action' => 'apiUpdate'],
        '/user/{id}'                    => ['controller' => 'UserController', 'action' => 'apiUpdate'],
    ],
    'DELETE' => [
        '/sop/{id}'                     => ['controller' => 'SopController', 'action' => 'delete'],
        '/peraturan-lab/{id}'           => ['controller' => 'PeraturanLabController', 'action' => 'delete'],
        '/sanksi-lab/{id}'              => ['controller' => 'SanksiController', 'action' => 'delete'],
        '/fasilitas/{id}'               => ['controller' => 'FasilitasController', 'action' => 'delete'],
        '/fasilitas/image/{id}'         => ['controller' => 'FasilitasController', 'action' => 'deleteImage'],
        '/asisten/{id}'                 => ['controller' => 'AsistenController', 'action' => 'delete'],
        '/alumni/{id}'                  => ['controller' => 'AlumniController', 'action' => 'delete'],
        '/matakuliah/{id}'              => ['controller' => 'MatakuliahController', 'action' => 'delete'],
        '/dosen/{id}'                   => ['controller' => 'DosenController', 'action' => 'delete'],
        '/jadwal/{id}'                  => ['controller' => 'JadwalPraktikumController', 'action' => 'delete'],
        '/manajemen/{id}'               => ['controller' => 'ManajemenController', 'action' => 'delete'],
        '/jadwal-upk/{id}'              => ['controller' => 'JadwalUpkController', 'action' => 'delete'],
        '/modul/{id}'                   => ['controller' => 'ModulController', 'action' => 'delete'],
        '/formatpenulisan/{id}'         => ['controller' => 'FormatPenulisanController', 'action' => 'delete'],
        '/showcase/{id}'                => ['controller' => 'ShowcaseController', 'action' => 'delete'],
        '/kontak-info/{id}'             => ['controller' => 'KontakController', 'action' => 'apiDelete'],
        '/kontak-pesan/{id}'            => ['controller' => 'KontakController', 'action' => 'apiDeleteMessage'],
        '/apps/{id}'                    => ['controller' => 'AppsController', 'action' => 'apiDelete'],
        '/user/{id}'                    => ['controller' => 'UserController', 'action' => 'apiDelete'],
    ]
];

// 7. Daftarkan Routes ke Router
$router->addRoutes($apiRoutes);

// 8. Jalankan Router
$router->dispatch();