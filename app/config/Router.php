<?php

/**
 * Application Router Class
 * Mendukung Single Route (get/post) dan Mass Route (addRoutes) untuk Web & API.
 */
class Router {

    private $routes = [];
    private $method;
    private $path;
    private $params = [];

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->path = $this->getPath();
        
        // PERBAIKAN: Selalu muat rute bawaan saat objek dibuat
        // Ini memastikan rute seperti /riset selalu terdaftar.
        $this->defineRoutes();
    }

    /**
     * Ekstrak clean path dari URL.
     */
        private function getPath(): string
        {
            $path = $_GET['route'] ?? $_SERVER['PATH_INFO'] ?? null;

            if (!$path) {
                $uri = $_SERVER['REQUEST_URI'] ?? '/';
                $uri = explode('?', $uri)[0];
                $script_name = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                
                if ($script_name !== '/' && $script_name !== '') {
                    $path = str_replace($script_name, '', $uri);
                } else {
                    $path = $uri;
                }
            }

            return '/' . trim($path, '/');
        }

    /**
     * Menambahkan method addRoutes untuk api.php
     */
    public function addRoutes(array $routeGroup): void
    {
        foreach ($routeGroup as $method => $rules) {
            $methodUpper = strtoupper($method);
            foreach ($rules as $route => $handler) {
                $this->addRoute($methodUpper, $route, $handler['controller'], $handler['method'] ?? $handler['action']);
            }
        }
    }

    /**
     * Definisi rute bawaan (Internal)
     */
    private function defineRoutes(): void
    {
        // -------- PUBLIC ROUTES (WEB) --------
        $this->get('/', 'HomeController', 'index');
        $this->get('/home', 'HomeController', 'index');
        $this->get('/apps', 'HomeController', 'apps');
        $this->get('/tatatertib', 'PeraturanLabController', 'index');
        $this->get('/peraturan', 'PeraturanLabController', 'index');
        $this->get('/sop', 'SopController', 'index');

        // Fasilitas & Laboratorium
        $this->get('/laboratorium', 'FasilitasController', 'index');
        $this->get('/laboratorium/{id}', 'FasilitasController', 'detail');
        $this->get('/riset', 'FasilitasController', 'riset');
        $this->get('/denah', 'FasilitasController', 'denah');

        // Sumber Daya
        $this->get('/alumni', 'AlumniController', 'index');
        $this->get('/alumni/{id}', 'AlumniController', 'detail');
        $this->get('/asisten', 'AsistenController', 'index');
        $this->get('/asisten/{id}', 'AsistenController', 'detail');
        $this->get('/kepala', 'ManajemenController', 'index');
        $this->get('/kepala/{id}', 'ManajemenController', 'detail');

        // Praktikum
        $this->get('/jadwal', 'JadwalPraktikumController', 'index');
        $this->get('/jadwalupk', 'JadwalUpkController', 'index');
        $this->get('/formatpenulisan', 'FormatPenulisanController', 'index');
        $this->get('/modul', 'ModulController', 'index');

        // API ROUTES
        $this->get('/api/jadwal', 'JadwalPraktikumController', 'apiIndex');
        $this->get('/api/peraturan', 'PeraturanLabController', 'apiIndex');
        $this->get('/api/sanksi', 'SanksiController', 'apiIndex');
        $this->get('/api/asisten', 'AsistenController', 'apiIndex');
        $this->get('/api/alumni', 'AlumniController', 'apiIndex');
        $this->get('/api/manajemen', 'ManajemenController', 'apiIndex');
        $this->get('/api/fasilitas', 'FasilitasController', 'apiIndex');
        $this->get('/api/matakuliah', 'MatakuliahController', 'apiIndex');

        // -------- AUTH & ADMIN --------
        $this->get('/iclabs-login', 'AuthController', 'login');
        $this->get('/login', 'AuthController', 'login');
        $this->post('/login', 'AuthController', 'authenticate');
        $this->get('/logout', 'AuthController', 'logout');

        $this->get('/admin', 'DashboardController', 'index');
        $this->get('/admin/dashboard/stats', 'DashboardController', 'stats');
        $this->get('/admin/informasi-lab', 'FasilitasController', 'adminIndex');
        $this->get('/admin/informasi-lab/{id}/detail', 'FasilitasController', 'adminDetail');
        $this->get('/admin/informasi-lab/{id}/edit', 'FasilitasController', 'edit');
        $this->get('/admin/asisten', 'AsistenController', 'adminIndex');
        $this->get('/admin/alumni', 'AlumniController', 'adminIndex');
        $this->get('/admin/manajemen', 'ManajemenController', 'adminIndex');
        $this->get('/admin/fasilitas', 'FasilitasController', 'adminIndex');
        $this->get('/admin/matakuliah', 'MatakuliahController', 'adminIndex');
        
    }

    public function dispatch(): void
    {
        // PERBAIKAN: Baris "if empty defineRoutes" dihapus karena sudah dipindah ke __construct
        $method = strtoupper($this->method);
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $handler) {
                if ($this->match($route, $this->path)) {
                    $this->execute($handler['controller'], $handler['action']);
                    return;
                }
            }
        }
        $this->notFound();
    }

    private function match($pattern, $path): bool
    {
        $regex = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $pattern);
        $regex = '#^' . $regex . '$#';
        if (preg_match($regex, $path, $matches)) {
            foreach ($matches as $key => $value) {
                if (!is_numeric($key)) $this->params[$key] = $value;
            }
            return true;
        }
        return false;
    }

    private function execute($controller, $action): void
    {
        $controller_file = CONTROLLER_PATH . '/' . $controller . '.php';
        if (!file_exists($controller_file)) { $this->notFound(); return; }

        require_once $controller_file;
        if (!class_exists($controller)) { $this->notFound(); return; }
        
        $instance = new $controller();
        if (!method_exists($instance, $action)) { $this->notFound(); return; }

        // Middleware Proteksi Admin
        if (strpos($this->path, '/admin') === 0) {
            if (file_exists(APP_PATH . '/middleware/AuthMiddleware.php')) {
                require_once APP_PATH . '/middleware/AuthMiddleware.php';
                AuthMiddleware::check();
            }
        }

        call_user_func([$instance, $action], $this->params);
    }

    private function notFound(): void
    {
        http_response_code(404);
        if (strpos($this->path, '/api/') === 0) {
            header('Content-Type: application/json');
            echo json_encode(['status' => false, 'message' => 'API Route not found: ' . $this->path]);
        } else {
            echo "<h1>404 Not Found</h1><p>Jalur <b>{$this->path}</b> tidak terdaftar di Router.</p>";
        }
        exit;
    }

    public function get($route, $ctl, $act) { $this->addRoute('GET', $route, $ctl, $act); }
    public function post($route, $ctl, $act) { $this->addRoute('POST', $route, $ctl, $act); }

    private function addRoute($method, $route, $ctl, $act) {
        $this->routes[strtoupper($method)]['/' . trim($route, '/')] = ['controller' => $ctl, 'action' => $act];
    }
}