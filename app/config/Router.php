<?php
/**
 * Router Class
 * Menangani routing dan dispatch request ke controller
 */

class Router {
    private $routes = [];
    private $method;
    private $path;
    private $controller;
    private $action;
    private $params = [];

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        
        // Get route dari query parameter atau REQUEST_URI
        if (isset($_GET['route'])) {
            $this->path = $_GET['route'];
        } else {
            $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $this->path = str_replace('/SistemManagementSumberDaya/public/api.php', '', $this->path);
        }
        
        if (empty($this->path) || $this->path === '/api.php') {
            $this->path = '/';
        }
    }

    /**
     * Define GET route
     */
    public function get($route, $controller, $action) {
        $this->routes['GET'][$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Define POST route
     */
    public function post($route, $controller, $action) {
        $this->routes['POST'][$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Define PUT route
     */
    public function put($route, $controller, $action) {
        $this->routes['PUT'][$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Define DELETE route
     */
    public function delete($route, $controller, $action) {
        $this->routes['DELETE'][$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Match route dan dispatch
     */
    public function dispatch() {
        $route_found = false;

        // Check exact match atau pattern match
        if (isset($this->routes[$this->method])) {
            foreach ($this->routes[$this->method] as $route => $handler) {
                $params = [];
                if ($this->match($route, $this->path, $params)) {
                    $route_found = true;
                    $this->controller = $handler['controller'];
                    $this->action = $handler['action'];
                    $this->params = $params;
                    break;
                }
            }
        }

        if (!$route_found) {
            $this->notFound();
            return;
        }

        $this->execute();
    }

    /**
     * Match URL pattern dengan extract params
     */
    private function match($pattern, $path, &$params) {
        // Escape special regex characters except {param}
        $regex = preg_quote($pattern, '#');
        
        // Replace {param} with regex pattern
        $regex = preg_replace_callback('#\\\{([a-zA-Z_][a-zA-Z0-9_]*)\\\}#', function($matches) {
            return '(?P<' . $matches[1] . '>[a-zA-Z0-9_-]+)';
        }, $regex);
        
        // Match exact path
        if (preg_match('#^' . $regex . '$#', $path, $matches)) {
            // Extract named parameters
            foreach ($matches as $key => $value) {
                if (!is_numeric($key)) {
                    $params[$key] = $value;
                }
            }
            return true;
        }
        
        return false;
    }

    /**
     * Execute controller action
     */
    private function execute() {
        $controller_class = 'App\\Controllers\\' . $this->controller;
        $action_method = $this->action;

        if (!class_exists($controller_class)) {
            $this->notFound();
            return;
        }

        $controller_instance = new $controller_class();

        if (!method_exists($controller_instance, $action_method)) {
            $this->notFound();
            return;
        }

        call_user_func_array([$controller_instance, $action_method], [$this->params]);
    }

    /**
     * Handle 404 Not Found
     */
    private function notFound() {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Route not found']);
    }
}
?>
