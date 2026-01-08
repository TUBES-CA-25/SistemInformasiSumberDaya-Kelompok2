<?php
/**
 * Router Class untuk MVC Application
 * Menangani routing dan dispatch request ke controller
 */

class Router {
    private $routes = [];
    private $method;
    private $path;
    private $params = [];

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = $this->getPath();
        // Lazy load routes - only define when needed
    }

    /**
     * Get clean path from request
     */
    private function getPath() {
        $path = $_GET['route'] ?? '/';
        
        // Clean path
        $path = '/' . trim($path, '/');
        if ($path === '/') {
            return '/';
        }
        
        return $path;
    }

    /**
     * Define all application routes
     */
    private function defineRoutes() {
        // Public routes
        $this->get('/', 'HomeController', 'index');
        $this->get('/home', 'HomeController', 'index');
        $this->get('/contact', 'ContactController', 'index');
        $this->get('/alumni', 'AlumniController', 'index');
        $this->get('/alumni/{id}', 'AlumniController', 'detail');
        $this->get('/asisten', 'AsistenController', 'index');
        $this->get('/asisten/{id}', 'AsistenController', 'detail');
        $this->get('/jadwal', 'JadwalPraktikumController', 'index');
        $this->get('/laboratorium', 'InformasiLabController', 'index');
        $this->get('/laboratorium/{id}', 'InformasiLabController', 'detail');
        $this->get('/praktikum', 'PraktikumController', 'index');
        $this->get('/praktikum/sanksi', 'SanksiController', 'index');
        $this->get('/praktikum/formatpenulisan', 'FormatPenulisanController', 'index');
        $this->get('/peraturan', 'PeraturanLabController', 'index');
        // Aliases for Indonesian naming used in legacy
        $this->get('/tata-tertib', 'PeraturanLabController', 'index');
        $this->get('/tatatertib', 'PeraturanLabController', 'index');
        $this->get('/riset', 'RisetController', 'index');
        $this->get('/profil', 'ProfilController', 'index');
        $this->get('/kepala-lab', 'KepalaLabController', 'index');
        $this->get('/kepala-lab/detail/{id}', 'KepalaLabController', 'detail');
        // Legacy alias
        $this->get('/kepala', 'KepalaLabController', 'index');

        // Auth Routes
        $this->get('/login', 'AuthController', 'login');
        $this->post('/login', 'AuthController', 'authenticate');
        $this->get('/logout', 'AuthController', 'logout');

        // Admin routes
        $this->get('/admin', 'DashboardController', 'index');
        $this->get('/admin/dashboard', 'DashboardController', 'index');
        
        // Admin Alumni
        $this->get('/admin/alumni', 'AlumniController', 'adminIndex');
        $this->get('/admin/alumni/create', 'AlumniController', 'create');
        $this->post('/admin/alumni', 'AlumniController', 'store');
        $this->get('/admin/alumni/{id}/edit', 'AlumniController', 'edit');
        $this->put('/admin/alumni/{id}', 'AlumniController', 'update');
        $this->delete('/admin/alumni/{id}', 'AlumniController', 'delete');

        // Admin Asisten
        $this->get('/admin/asisten', 'AsistenController', 'adminIndex');
        $this->get('/admin/asisten/create', 'AsistenController', 'create');
        $this->post('/admin/asisten', 'AsistenController', 'store');
        $this->get('/admin/asisten/{id}/edit', 'AsistenController', 'edit');
        $this->put('/admin/asisten/{id}', 'AsistenController', 'update');
        $this->delete('/admin/asisten/{id}', 'AsistenController', 'delete');
        $this->get('/admin/asisten/koordinator', 'AsistenController', 'pilihKoordinator');
        $this->post('/admin/asisten/koordinator', 'AsistenController', 'setKoordinator');

        // Admin Jadwal
        $this->get('/admin/jadwal', 'JadwalPraktikumController', 'adminIndex');
        $this->get('/admin/jadwal/create', 'JadwalPraktikumController', 'create');
        $this->post('/admin/jadwal', 'JadwalPraktikumController', 'store');
        $this->get('/admin/jadwal/{id}/edit', 'JadwalPraktikumController', 'edit');
        $this->put('/admin/jadwal/{id}', 'JadwalPraktikumController', 'update');
        $this->delete('/admin/jadwal/{id}', 'JadwalPraktikumController', 'delete');
        $this->get('/admin/jadwal/upload', 'JadwalPraktikumController', 'uploadForm');
        $this->post('/admin/jadwal/upload', 'JadwalPraktikumController', 'uploadProcess');
        $this->get('/admin/jadwal/csv-upload', 'JadwalPraktikumController', 'csvUploadForm');
        $this->post('/admin/jadwal/csv-upload', 'JadwalPraktikumController', 'csvUploadProcess');

        // Admin Laboratorium (Fasilitas)
        $this->get('/admin/laboratorium', 'LaboratoriumController', 'adminIndex');
        $this->get('/admin/laboratorium/create', 'LaboratoriumController', 'create');
        $this->post('/admin/laboratorium', 'LaboratoriumController', 'store');
        $this->get('/admin/laboratorium/{id}', 'LaboratoriumController', 'detail');
        $this->get('/admin/laboratorium/{id}/edit', 'LaboratoriumController', 'edit');
        $this->put('/admin/laboratorium/{id}', 'LaboratoriumController', 'update');
        $this->delete('/admin/laboratorium/{id}', 'LaboratoriumController', 'delete');
        $this->delete('/admin/laboratorium/image/{id}', 'LaboratoriumController', 'deleteImage');

        // Admin Informasi Lab (detail/konten)
        $this->get('/admin/informasi-lab', 'InformasiLabController', 'adminIndex');
        $this->get('/admin/informasi-lab/create', 'InformasiLabController', 'create');
        $this->post('/admin/informasi-lab', 'InformasiLabController', 'store');
        $this->get('/admin/informasi-lab/{id}/edit', 'InformasiLabController', 'edit');
        $this->put('/admin/informasi-lab/{id}', 'InformasiLabController', 'update');
        $this->delete('/admin/informasi-lab/{id}', 'InformasiLabController', 'delete');
        $this->get('/admin/informasi-lab/{id}/detail', 'InformasiLabController', 'adminDetail');
        $this->get('/admin/informasi-lab/{id}/detail/create', 'InformasiLabController', 'createDetail');
        $this->post('/admin/informasi-lab/{id}/detail', 'InformasiLabController', 'storeDetail');
        $this->get('/admin/informasi-lab/detail/{detail_id}/edit', 'InformasiLabController', 'editDetail');
        $this->put('/admin/informasi-lab/detail/{detail_id}', 'InformasiLabController', 'updateDetail');
        $this->delete('/admin/informasi-lab/detail/{detail_id}', 'InformasiLabController', 'deleteDetail');

        // Admin Matakuliah
        $this->get('/admin/matakuliah', 'MatakuliahController', 'adminIndex');
        $this->get('/admin/matakuliah/create', 'MatakuliahController', 'create');
        $this->post('/admin/matakuliah', 'MatakuliahController', 'store');
        $this->get('/admin/matakuliah/{id}/edit', 'MatakuliahController', 'edit');
        $this->put('/admin/matakuliah/{id}', 'MatakuliahController', 'update');
        $this->delete('/admin/matakuliah/{id}', 'MatakuliahController', 'delete');

        // Admin Manajemen
        $this->get('/admin/manajemen', 'ManajemenController', 'adminIndex');
        $this->get('/admin/manajemen/create', 'ManajemenController', 'create');
        $this->post('/admin/manajemen', 'ManajemenController', 'store');
        $this->get('/admin/manajemen/{id}/edit', 'ManajemenController', 'edit');
        $this->put('/admin/manajemen/{id}', 'ManajemenController', 'update');
        $this->delete('/admin/manajemen/{id}', 'ManajemenController', 'delete');

        // Admin Peraturan
        $this->get('/admin/peraturan', 'PeraturanLabController', 'adminIndex');
        $this->get('/admin/peraturan/create', 'PeraturanLabController', 'create');
        $this->post('/admin/peraturan', 'PeraturanLabController', 'store');
        $this->get('/admin/peraturan/{id}/edit', 'PeraturanLabController', 'edit');
        $this->put('/admin/peraturan/{id}', 'PeraturanLabController', 'update');
        $this->delete('/admin/peraturan/{id}', 'PeraturanLabController', 'delete');

        // Admin Sanksi
        $this->get('/admin/sanksi', 'SanksiController', 'adminIndex');
        $this->get('/admin/sanksi/create', 'SanksiController', 'create');
        $this->post('/admin/sanksi', 'SanksiController', 'store');
        $this->get('/admin/sanksi/{id}/edit', 'SanksiController', 'edit');
        $this->put('/admin/sanksi/{id}', 'SanksiController', 'update');
        $this->delete('/admin/sanksi/{id}', 'SanksiController', 'delete');

        // Admin Format Penulisan
        $this->get('/admin/formatpenulisan', 'FormatPenulisanController', 'adminIndex');
        $this->post('/admin/formatpenulisan', 'FormatPenulisanController', 'store');
        $this->put('/admin/formatpenulisan/{id}', 'FormatPenulisanController', 'update');
        $this->delete('/admin/formatpenulisan/{id}', 'FormatPenulisanController', 'delete');

        // API routes
        $this->get('/api/health', 'HealthController', 'check');
        $this->get('/api/alumni', 'AlumniController', 'apiIndex');
        $this->get('/api/alumni/{id}', 'AlumniController', 'apiShow');
        $this->get('/api/asisten', 'AsistenController', 'apiIndex');
        $this->get('/api/jadwal', 'JadwalPraktikumController', 'apiIndex');
        $this->get('/api/laboratorium', 'InformasiLabController', 'apiIndex');
        $this->get('/api/laboratorium/{id}', 'InformasiLabController', 'apiShow');
        $this->get('/api/sanksi-lab', 'SanksiController', 'apiIndex');
        $this->get('/api/peraturan-lab', 'PeraturanLabController', 'apiIndex');
        $this->get('/api/peraturan-lab/{id}', 'PeraturanLabController', 'apiShow');
        $this->get('/api/tata-tertib', 'PeraturanLabController', 'apiIndex');
        $this->get('/api/manajemen', 'KepalaLabController', 'apiIndex');
        $this->get('/api/formatpenulisan', 'FormatPenulisanController', 'apiIndex');
        $this->get('/api/formatpenulisan/{id}', 'FormatPenulisanController', 'apiShow');
    }

    /**
     * Define GET route
     */
    private function get($route, $controller, $action) {
        $this->routes['GET'][$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Define POST route
     */
    private function post($route, $controller, $action) {
        $this->routes['POST'][$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Define PUT route
     */
    private function put($route, $controller, $action) {
        $this->routes['PUT'][$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Define DELETE route
     */
    private function delete($route, $controller, $action) {
        $this->routes['DELETE'][$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Dispatch request to appropriate controller
     */
    public function dispatch() {
        // Define routes only when dispatching
        if (empty($this->routes)) {
            $this->defineRoutes();
        }
        
        $route_found = false;

        if (isset($this->routes[$this->method])) {
            foreach ($this->routes[$this->method] as $route => $handler) {
                $params = [];
                if ($this->match($route, $this->path, $params)) {
                    $route_found = true;
                    $this->params = $params;
                    $this->execute($handler['controller'], $handler['action']);
                    return;
                }
            }
        }

        if (!$route_found) {
            $this->notFound();
        }
    }

    /**
     * Match URL pattern dan extract parameters
     */
    private function match($pattern, $path, &$params) {
        // Convert route pattern to regex
        $regex = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $pattern);
        $regex = '#^' . $regex . '$#';
        
        if (preg_match($regex, $path, $matches)) {
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
    private function execute($controller, $action) {
        $controller_file = CONTROLLER_PATH . '/' . $controller . '.php';
        
        if (!file_exists($controller_file)) {
            $this->notFound();
            return;
        }

        require_once $controller_file;
        
        if (!class_exists($controller)) {
            $this->notFound();
            return;
        }

        $controller_instance = new $controller();

        if (!method_exists($controller_instance, $action)) {
            $this->notFound();
            return;
        }

        // Middleware check for admin routes
        if (strpos($this->path, '/admin') === 0) {
            require_once APP_PATH . '/middleware/AuthMiddleware.php';
            AuthMiddleware::check();
        }

        // Pass parameters to controller method
        call_user_func([$controller_instance, $action], $this->params);
    }

    /**
     * Handle 404 Not Found
     */
    private function notFound() {
        http_response_code(404);
        
        // Check if API request
        if (strpos($this->path, '/api/') === 0) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'API endpoint not found']);
        } else {
            // Show 404 page
            require_once VIEW_PATH . '/errors/404.php';
        }
    }
}
?>
