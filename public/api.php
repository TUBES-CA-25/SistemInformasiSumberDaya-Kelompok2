<?php
/**
 * API Entry Point with Proper HTTP Method Routing
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define paths
define('BASE_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', BASE_PATH . '/app');

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
$script_name = '/SistemManagementSumberDaya/public/api.php';

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

// Route mapping dengan HTTP METHOD
$routes = [
    'GET' => [
        '/health' => ['controller' => 'HealthController', 'method' => 'check'],
        '/laboratorium' => ['controller' => 'LaboratoriumController', 'method' => 'index'],
        '/laboratorium/{id}' => ['controller' => 'LaboratoriumController', 'method' => 'show'],
        '/asisten' => ['controller' => 'AsistenController', 'method' => 'index'],
        '/asisten/{id}' => ['controller' => 'AsistenController', 'method' => 'show'],
        '/asisten/{id}/matakuliah' => ['controller' => 'AsistenController', 'method' => 'matakuliah'],
        '/matakuliah' => ['controller' => 'MatakuliahController', 'method' => 'index'],
        '/matakuliah/{id}' => ['controller' => 'MatakuliahController', 'method' => 'show'],
        '/matakuliah/{id}/asisten' => ['controller' => 'MatakuliahController', 'method' => 'asisten'],
        '/jadwal' => ['controller' => 'JadwalPraktikumController', 'method' => 'index'],
        '/jadwal/{id}' => ['controller' => 'JadwalPraktikumController', 'method' => 'show'],
        '/informasi' => ['controller' => 'InformasiLabController', 'method' => 'index'],
        '/informasi/{id}' => ['controller' => 'InformasiLabController', 'method' => 'show'],
        '/informasi/tipe/{type}' => ['controller' => 'InformasiLabController', 'method' => 'byType'],
        '/visi-misi' => ['controller' => 'VisMisiController', 'method' => 'getLatest'],
        '/visi-misi/{id}' => ['controller' => 'VisMisiController', 'method' => 'show'],
        '/manajemen' => ['controller' => 'ManajemenController', 'method' => 'index'],
        '/manajemen/{id}' => ['controller' => 'ManajemenController', 'method' => 'show'],
        '/kontak' => ['controller' => 'KontakController', 'method' => 'getLatest'],
        '/kontak/{id}' => ['controller' => 'KontakController', 'method' => 'show'],
        '/asisten-matakuliah' => ['controller' => 'AsistenMatakuliahController', 'method' => 'index'],
        '/asisten-matakuliah/{id}' => ['controller' => 'AsistenMatakuliahController', 'method' => 'show'],
        '/tata-tertib' => ['controller' => 'TataTerbibController', 'method' => 'index'],
        '/tata-tertib/{id}' => ['controller' => 'TataTerbibController', 'method' => 'show'],
        '/integrasi-web' => ['controller' => 'IntegrsiWebController', 'method' => 'index'],
        '/integrasi-web/{id}' => ['controller' => 'IntegrsiWebController', 'method' => 'show'],
    ],
    'POST' => [
        '/laboratorium' => ['controller' => 'LaboratoriumController', 'method' => 'store'],
        '/asisten' => ['controller' => 'AsistenController', 'method' => 'store'],
        '/matakuliah' => ['controller' => 'MatakuliahController', 'method' => 'store'],
        '/jadwal' => ['controller' => 'JadwalPraktikumController', 'method' => 'store'],
        '/informasi' => ['controller' => 'InformasiLabController', 'method' => 'store'],
        '/visi-misi' => ['controller' => 'VisMisiController', 'method' => 'store'],
        '/manajemen' => ['controller' => 'ManajemenController', 'method' => 'store'],
        '/kontak' => ['controller' => 'KontakController', 'method' => 'store'],
        '/asisten-matakuliah' => ['controller' => 'AsistenMatakuliahController', 'method' => 'store'],
        '/tata-tertib' => ['controller' => 'TataTerbibController', 'method' => 'store'],
        '/integrasi-web' => ['controller' => 'IntegrsiWebController', 'method' => 'store'],
    ],
    'PUT' => [
        '/laboratorium/{id}' => ['controller' => 'LaboratoriumController', 'method' => 'update'],
        '/asisten/{id}' => ['controller' => 'AsistenController', 'method' => 'update'],
        '/matakuliah/{id}' => ['controller' => 'MatakuliahController', 'method' => 'update'],
        '/jadwal/{id}' => ['controller' => 'JadwalPraktikumController', 'method' => 'update'],
        '/informasi/{id}' => ['controller' => 'InformasiLabController', 'method' => 'update'],
        '/visi-misi/{id}' => ['controller' => 'VisMisiController', 'method' => 'update'],
        '/manajemen/{id}' => ['controller' => 'ManajemenController', 'method' => 'update'],
        '/kontak/{id}' => ['controller' => 'KontakController', 'method' => 'update'],
        '/asisten-matakuliah/{id}' => ['controller' => 'AsistenMatakuliahController', 'method' => 'update'],
        '/tata-tertib/{id}' => ['controller' => 'TataTerbibController', 'method' => 'update'],
        '/integrasi-web/{id}' => ['controller' => 'IntegrsiWebController', 'method' => 'update'],
    ],
    'DELETE' => [
        '/laboratorium/{id}' => ['controller' => 'LaboratoriumController', 'method' => 'delete'],
        '/asisten/{id}' => ['controller' => 'AsistenController', 'method' => 'delete'],
        '/matakuliah/{id}' => ['controller' => 'MatakuliahController', 'method' => 'delete'],
        '/jadwal/{id}' => ['controller' => 'JadwalPraktikumController', 'method' => 'delete'],
        '/informasi/{id}' => ['controller' => 'InformasiLabController', 'method' => 'delete'],
        '/visi-misi/{id}' => ['controller' => 'VisMisiController', 'method' => 'delete'],
        '/manajemen/{id}' => ['controller' => 'ManajemenController', 'method' => 'delete'],
        '/kontak/{id}' => ['controller' => 'KontakController', 'method' => 'delete'],
        '/asisten-matakuliah/{id}' => ['controller' => 'AsistenMatakuliahController', 'method' => 'delete'],
        '/tata-tertib/{id}' => ['controller' => 'TataTerbibController', 'method' => 'delete'],
        '/integrasi-web/{id}' => ['controller' => 'IntegrsiWebController', 'method' => 'delete'],
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
            $controller_class = 'App\\Controllers\\' . $handler['controller'];
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
    http_response_code(404);
    echo json_encode(['error' => 'Route not found', 'method' => $method, 'path' => $path]);
    exit;
}

// Load and execute controller
if (!class_exists($controller_class)) {
    require_once APP_PATH . '/controllers/Controller.php';
    $controller_file = APP_PATH . '/controllers/' . basename(str_replace('App\\Controllers\\', '', $controller_class)) . '.php';
    if (file_exists($controller_file)) {
        require_once $controller_file;
    }
}

if (!class_exists($controller_class)) {
    http_response_code(500);
    echo json_encode(['error' => 'Controller not found: ' . $controller_class]);
    exit;
}

$controller = new $controller_class();

if (!method_exists($controller, $action_method)) {
    http_response_code(500);
    echo json_encode(['error' => 'Method not found: ' . $action_method]);
    exit;
}

// Execute action
$controller->$action_method($params);
?>
