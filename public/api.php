<?php
/**
 * API Entry Point with Proper HTTP Method Routing
 */

// Start session for authenticated requests
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define paths
define('BASE_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', BASE_PATH . '/app');
define('ROOT_PROJECT', BASE_PATH);
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('MODEL_PATH', APP_PATH . '/models');
define('VIEW_PATH', APP_PATH . '/views');

// Include configuration
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/helpers/Helper.php';

// Header CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Parse request
$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Get the correct script name dynamically
$script_name = $_SERVER['SCRIPT_NAME'];

// Debug logging (remove in production)
error_log("API Debug - Method: $method, URI: $request_uri, Script: $script_name");

// Remove base path from URI
if (strpos($request_uri, $script_name) === 0) {
    $path = substr($request_uri, strlen($script_name));
} else {
    $path = parse_url($request_uri, PHP_URL_PATH);
    $path = preg_replace('#.*/public/api\.php#', '', $path);
}

// Clean path - decode URL and remove whitespace
$path = urldecode($path);
$path = '/' . trim($path, '/ ');

// Debug logging
error_log("API Debug - Parsed path: $path");

// Route mapping dengan HTTP METHOD
$routes = [
    'GET' => [
        '/sop' => ['controller' => 'SopController', 'method' => 'getJson'],
            '/peraturan-lab' => ['controller' => 'PeraturanLabController', 'method' => 'apiIndex'],
            '/peraturan-lab/{id}' => ['controller' => 'PeraturanLabController', 'method' => 'apiShow'],
            '/sanksi-lab' => ['controller' => 'SanksiLabController', 'method' => 'apiIndex'],
            '/sanksi-lab/{id}' => ['controller' => 'SanksiLabController', 'method' => 'apiShow'],
            '/alumni' => ['controller' => 'AlumniController', 'method' => 'apiIndex'],
            '/alumni/{id}' => ['controller' => 'AlumniController', 'method' => 'apiShow'],
        '/health' => ['controller' => 'HealthController', 'method' => 'check'],
        '/laboratorium' => ['controller' => 'LaboratoriumController', 'method' => 'apiIndex'],
        '/laboratorium/{id}' => ['controller' => 'LaboratoriumController', 'method' => 'apiShow'],
        '/asisten' => ['controller' => 'AsistenController', 'method' => 'apiIndex'],
        '/asisten/{id}' => ['controller' => 'AsistenController', 'method' => 'apiShow'],
        '/asisten/{id}/matakuliah' => ['controller' => 'AsistenController', 'method' => 'matakuliah'],
        '/matakuliah' => ['controller' => 'MatakuliahController', 'method' => 'apiIndex'],
        '/matakuliah/{id}' => ['controller' => 'MatakuliahController', 'method' => 'apiShow'],
        '/matakuliah/{id}/asisten' => ['controller' => 'MatakuliahController', 'method' => 'asisten'],
        '/jadwal' => ['controller' => 'JadwalPraktikumController', 'method' => 'apiIndex'],
        '/jadwal/{id}' => ['controller' => 'JadwalPraktikumController', 'method' => 'apiShow'],
        '/jadwal-praktikum' => ['controller' => 'JadwalPraktikumController', 'method' => 'apiIndex'],
        '/jadwal-praktikum/{id}' => ['controller' => 'JadwalPraktikumController', 'method' => 'apiShow'],
        '/jadwal-praktikum/template' => ['controller' => 'JadwalPraktikumController', 'method' => 'downloadTemplate'],
        '/jadwal-praktikum/csv-template' => ['controller' => 'JadwalPraktikumUploadAlternativeController', 'method' => 'downloadCSVTemplate'],
        '/jadwal-upk' => ['controller' => 'JadwalUpkController', 'method' => 'apiIndex'],
        '/jadwal-upk/{id}' => ['controller' => 'JadwalUpkController', 'method' => 'apiShow'],
        '/informasi' => ['controller' => 'InformasiLabController', 'method' => 'apiIndex'],
        '/informasi/{id}' => ['controller' => 'InformasiLabController', 'method' => 'apiShow'],
        '/informasi/tipe/{type}' => ['controller' => 'InformasiLabController', 'method' => 'byType'],
        '/visi-misi' => ['controller' => 'VisMisiController', 'method' => 'getLatest'],
        '/visi-misi/{id}' => ['controller' => 'VisMisiController', 'method' => 'show'],
        '/manajemen' => ['controller' => 'ManajemenController', 'method' => 'apiIndex'],
        '/manajemen/{id}' => ['controller' => 'ManajemenController', 'method' => 'apiShow'],
        '/kontak' => ['controller' => 'KontakController', 'method' => 'getLatest'],
        '/kontak/{id}' => ['controller' => 'KontakController', 'method' => 'show'],
        '/user' => ['controller' => 'UserController', 'method' => 'apiIndex'],
        '/user/{id}' => ['controller' => 'UserController', 'method' => 'apiShow'],
        '/asisten-matakuliah' => ['controller' => 'AsistenMatakuliahController', 'method' => 'index'],
        '/asisten-matakuliah/{id}' => ['controller' => 'AsistenMatakuliahController', 'method' => 'show'],
        '/formatpenulisan' => ['controller' => 'FormatPenulisanController', 'method' => 'apiIndex'],
        '/formatpenulisan/{id}' => ['controller' => 'FormatPenulisanController', 'method' => 'apiShow'],
        '/tata-tertib' => ['controller' => 'TataTerbibController', 'method' => 'index'],
        '/tata-tertib/{id}' => ['controller' => 'TataTerbibController', 'method' => 'show'],
        '/integrasi-web' => ['controller' => 'IntegrsiWebController', 'method' => 'index'],
        '/integrasi-web/{id}' => ['controller' => 'IntegrsiWebController', 'method' => 'show'],            '/dashboard/stats' => ['controller' => 'DashboardController', 'method' => 'stats'],    ],
    'POST' => [
        '/sop' => ['controller' => 'SopController', 'method' => 'store'],
        '/sop/{id}' => ['controller' => 'SopController', 'method' => 'update'],
            '/peraturan-lab' => ['controller' => 'PeraturanLabController', 'method' => 'store'],
            '/peraturan-lab/{id}' => ['controller' => 'PeraturanLabController', 'method' => 'update'], // For file upload
            '/sanksi-lab' => ['controller' => 'SanksiLabController', 'method' => 'store'],
            '/sanksi-lab/{id}' => ['controller' => 'SanksiLabController', 'method' => 'update'], // For file upload
            '/tata-tertib' => ['controller' => 'TataTerbibController', 'method' => 'store'],
            '/tata-tertib/{id}' => ['controller' => 'TataTerbibController', 'method' => 'update'], // For file upload
            '/alumni' => ['controller' => 'AlumniController', 'method' => 'store'],
            '/alumni/{id}' => ['controller' => 'AlumniController', 'method' => 'update'], // For file upload
        '/laboratorium' => ['controller' => 'LaboratoriumController', 'method' => 'store'],
        '/laboratorium/{id}' => ['controller' => 'LaboratoriumController', 'method' => 'update'], // For file upload
        '/asisten' => ['controller' => 'AsistenController', 'method' => 'store'],
        '/asisten/{id}' => ['controller' => 'AsistenController', 'method' => 'update'], // For file upload
        '/asisten/{id}/koordinator' => ['controller' => 'AsistenController', 'method' => 'setKoordinator'], // Set koordinator
        '/matakuliah' => ['controller' => 'MatakuliahController', 'method' => 'store'],
        '/jadwal' => ['controller' => 'JadwalPraktikumController', 'method' => 'store'],
        '/jadwal/delete-multiple' => ['controller' => 'JadwalPraktikumController', 'method' => 'deleteMultiple'],
        '/jadwal-praktikum/upload' => ['controller' => 'JadwalPraktikumController', 'method' => 'uploadExcel'],
        '/jadwal-praktikum/upload-csv' => ['controller' => 'JadwalPraktikumUploadAlternativeController', 'method' => 'uploadCSV'],
        '/jadwal-upk' => ['controller' => 'JadwalUpkController', 'method' => 'store'],
        '/jadwal-upk/upload' => ['controller' => 'JadwalUpkController', 'method' => 'upload'],
        '/informasi' => ['controller' => 'InformasiLabController', 'method' => 'store'],
        '/visi-misi' => ['controller' => 'VisMisiController', 'method' => 'store'],
        '/manajemen' => ['controller' => 'ManajemenController', 'method' => 'store'],
        '/manajemen/{id}' => ['controller' => 'ManajemenController', 'method' => 'update'], // For file upload
        '/formatpenulisan' => ['controller' => 'FormatPenulisanController', 'method' => 'store'],
        '/formatpenulisan/{id}' => ['controller' => 'FormatPenulisanController', 'method' => 'update'],
        '/user' => ['controller' => 'UserController', 'method' => 'apiStore'],
        '/kontak' => ['controller' => 'KontakController', 'method' => 'store'],
        '/asisten-matakuliah' => ['controller' => 'AsistenMatakuliahController', 'method' => 'store'],
        '/tata-tertib' => ['controller' => 'TataTerbibController', 'method' => 'store'],
        '/integrasi-web' => ['controller' => 'IntegrsiWebController', 'method' => 'store'],
    ],
    'PUT' => [
        '/sop/{id}' => ['controller' => 'SopController', 'method' => 'update'],
            '/peraturan-lab/{id}' => ['controller' => 'PeraturanLabController', 'method' => 'update'],
        '/laboratorium/{id}' => ['controller' => 'LaboratoriumController', 'method' => 'update'],
        '/asisten/{id}' => ['controller' => 'AsistenController', 'method' => 'update'],
        '/matakuliah/{id}' => ['controller' => 'MatakuliahController', 'method' => 'update'],
        '/peraturan-lab/{id}' => ['controller' => 'PeraturanLabController', 'method' => 'update'],
        '/sanksi-lab/{id}' => ['controller' => 'SanksiLabController', 'method' => 'update'],
        '/alumni/{id}' => ['controller' => 'AlumniController', 'method' => 'update'],
        '/jadwal/{id}' => ['controller' => 'JadwalPraktikumController', 'method' => 'update'],
        '/informasi/{id}' => ['controller' => 'InformasiLabController', 'method' => 'update'],
        '/visi-misi/{id}' => ['controller' => 'VisMisiController', 'method' => 'update'],
        '/manajemen/{id}' => ['controller' => 'ManajemenController', 'method' => 'update'],
        '/kontak/{id}' => ['controller' => 'KontakController', 'method' => 'update'],
        '/asisten-matakuliah/{id}' => ['controller' => 'AsistenMatakuliahController', 'method' => 'update'],
        '/tata-tertib/{id}' => ['controller' => 'TataTerbibController', 'method' => 'update'],
        '/integrasi-web/{id}' => ['controller' => 'IntegrsiWebController', 'method' => 'update'],
        '/user/{id}' => ['controller' => 'UserController', 'method' => 'apiUpdate'],
    ],
    'DELETE' => [
        '/sop/{id}' => ['controller' => 'SopController', 'method' => 'delete'],

        '/laboratorium/image/{id}' => ['controller' => 'LaboratoriumController', 'method' => 'deleteImage'],
        '/laboratorium/{id}'       => ['controller' => 'LaboratoriumController', 'method' => 'delete'],
        '/asisten/{id}'            => ['controller' => 'AsistenController', 'method' => 'delete'],
        '/matakuliah/{id}'         => ['controller' => 'MatakuliahController', 'method' => 'delete'],
        '/peraturan-lab/{id}'      => ['controller' => 'PeraturanLabController', 'method' => 'delete'],
        '/sanksi-lab/{id}'         => ['controller' => 'SanksiLabController', 'method' => 'delete'],
        '/alumni/{id}'             => ['controller' => 'AlumniController', 'method' => 'delete'],
        '/jadwal/{id}'             => ['controller' => 'JadwalPraktikumController', 'method' => 'delete'],
        '/jadwal-upk/{id}' => ['controller' => 'JadwalUpkController', 'method' => 'delete'],
        '/informasi/{id}'          => ['controller' => 'InformasiLabController', 'method' => 'delete'],
        '/visi-misi/{id}'          => ['controller' => 'VisMisiController', 'method' => 'delete'],
        '/manajemen/{id}'          => ['controller' => 'ManajemenController', 'method' => 'delete'],
        '/kontak/{id}'             => ['controller' => 'KontakController', 'method' => 'delete'],
        '/formatpenulisan/{id}'    => ['controller' => 'FormatPenulisanController', 'method' => 'delete'],
        '/asisten-matakuliah/{id}' => ['controller' => 'AsistenMatakuliahController', 'method' => 'delete'],
        '/tata-tertib/{id}'        => ['controller' => 'TataTerbibController', 'method' => 'delete'],
        '/integrasi-web/{id}'      => ['controller' => 'IntegrsiWebController', 'method' => 'delete'],
        '/user/{id}'               => ['controller' => 'UserController', 'method' => 'apiDelete'],
    ],
];

// Match route
$matched = false;
$params = [];
$controller_class = null;
$action_method = null;

if (isset($routes[$method])) {
    foreach ($routes[$method] as $route => $handler) {
        $pattern = preg_replace('/\{[a-zA-Z_][a-zA-Z0-9_]*\}/', '([a-zA-Z0-9_-]+)', $route);
        $pattern = str_replace('/', '\/', $pattern);
        
        if (preg_match('/^' . $pattern . '$/', $path, $matches)) {
            $matched = true;
            $controller_class = $handler['controller']; // Remove namespace
            $action_method = $handler['method'];
            
            // Extract parameters
            if (count($matches) > 1) {
                preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $route, $param_names);
                for ($i = 0; $i < count($param_names[1]); $i++) {
                    $params[$param_names[1][$i]] = $matches[$i + 1];
                }
            }
            break;
        }
    }
}

if (!$matched) {
    error_log("API Debug - Route not found. Method: $method, Path: $path");
    error_log("API Debug - Available routes for $method: " . print_r($routes[$method] ?? [], true));
    http_response_code(404);
    echo json_encode([
        'error' => 'Route not found', 
        'method' => $method, 
        'path' => $path,
        'available_routes' => array_keys($routes[$method] ?? [])
    ]);
    exit;
}

// Load and execute controller
if (!class_exists($controller_class)) {
    require_once APP_PATH . '/controllers/Controller.php';
    $controller_file = APP_PATH . '/controllers/' . basename(str_replace('App\\Controllers\\', '', $controller_class)) . '.php';
    error_log("API Debug - Loading controller file: $controller_file");
    if (file_exists($controller_file)) {
        require_once $controller_file;
    } else {
        error_log("API Debug - Controller file not found: $controller_file");
    }
}

if (!class_exists($controller_class)) {
    error_log("API Debug - Controller class not found: $controller_class");
    http_response_code(500);
    echo json_encode(['error' => 'Controller not found: ' . $controller_class]);
    exit;
}

error_log("API Debug - Creating controller instance: $controller_class");
$controller = new $controller_class();

if (!method_exists($controller, $action_method)) {
    error_log("API Debug - Method not found: $action_method in $controller_class");
    http_response_code(500);
    echo json_encode(['error' => 'Method not found: ' . $action_method]);
    exit;
}

error_log("API Debug - Executing method: $action_method with params: " . print_r($params, true));
// Execute action
$controller->$action_method($params);
?>
