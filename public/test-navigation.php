<?php
// Test navigation routing
require_once '../app/config/config.php';
require_once CONFIG_PATH . '/Router.php';

echo "<h2>Testing Navigation Routes</h2>\n";

// Initialize router
$router = new Router();

// Test routes yang mungkin dipanggil dari navigation
$testRoutes = [
    'praktikum',
    'alumni', 
    'contact',
    'laboratorium',
    'jadwal',
    'asisten',
    'riset'
];

foreach ($testRoutes as $route) {
    echo "<h3>Testing route: {$route}</h3>\n";
    
    // Set $_GET untuk simulasi
    $_GET['route'] = $route;
    
    try {
        $match = $router->match($route);
        if ($match) {
            echo "✅ Route found: " . $match['controller'] . "::" . $match['action'] . "\n";
            
            // Check if controller file exists
            $controllerFile = ROOT_PROJECT . '/app/controllers/' . $match['controller'] . 'Controller.php';
            if (file_exists($controllerFile)) {
                echo "✅ Controller file exists\n";
            } else {
                echo "❌ Controller file NOT found: {$controllerFile}\n";
            }
            
            // Check if method exists
            require_once $controllerFile;
            $controllerClass = $match['controller'] . 'Controller';
            if (class_exists($controllerClass) && method_exists($controllerClass, $match['action'])) {
                echo "✅ Controller method exists\n";
            } else {
                echo "❌ Controller method NOT found: {$controllerClass}::{$match['action']}\n";
            }
        } else {
            echo "❌ Route NOT found\n";
        }
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "<br><hr><br>\n";
}

echo "<h2>Available Routes in Router:</h2>\n";
$router_reflection = new ReflectionClass($router);
$routes_property = $router_reflection->getProperty('routes');
$routes_property->setAccessible(true);
$routes = $routes_property->getValue($router);

foreach ($routes as $route => $config) {
    echo "• {$route} → {$config['controller']}::{$config['action']} [{$config['method']}]<br>\n";
}
?>