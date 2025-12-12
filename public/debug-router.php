<?php
/**
 * Debug Router
 */

define('BASE_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', BASE_PATH . '/app');

require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/Router.php';
require_once APP_PATH . '/helpers/Helper.php';

// Header CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Debug info
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($uri, PHP_URL_PATH);

echo json_encode([
    'debug' => true,
    'uri' => $uri,
    'method' => $method,
    'path' => $path,
    'path_after_replace' => str_replace('/SistemManagementSumberDaya/public/api.php', '', $path),
    'message' => 'Check the path_after_replace value'
], JSON_PRETTY_PRINT);
?>
