<?php
$routerContent = file_get_contents(__DIR__ . '/../app/config/Router.php');
preg_match_all('/\$this->(get|post|put|delete)\(\'\/api([^\']+)\',\s*\'([^\']+)\',\s*\'([^\']+)\'\)/i', $routerContent, $matches, PREG_SET_ORDER);

$routerRoutes = [];
foreach ($matches as $m) {
    $method = strtoupper($m[1]);
    $path = $m[2];
    $routerRoutes[$method][$path] = ['controller' => $m[3], 'action' => $m[4]];
}

// Read public/api.php $apiRoutes
require_once __DIR__ . '/../app/config/config.php';
$apiFile = file_get_contents(__DIR__ . '/../public/api.php');
// Extract array
preg_match('/\$apiRoutes\s*=\s*(\[.*?\]);/s', $apiFile, $arrMatch);
eval('$apiRoutes = ' . $arrMatch[1] . ';');

echo "=== CHECKING MISSING ROUTES IN public/api.php ===\n";
foreach ($routerRoutes as $method => $routes) {
    foreach ($routes as $path => $handler) {
        if (!isset($apiRoutes[$method][$path])) {
            echo "MISSING in api.php: $method $path -> {$handler['controller']}@{$handler['action']}\n";
        }
    }
}
