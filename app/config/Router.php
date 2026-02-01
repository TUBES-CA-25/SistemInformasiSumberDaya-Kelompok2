<?php
/**
 * Application Router Class
 * 
 * Menangani routing HTTP requests ke controller actions
 * Mendukung RESTful routes dengan parameter extraction
 */

class Router {

    // ============================================
    // PROPERTIES
    // ============================================
    /** @var array Penyimpanan seluruh route definitions */
    private $routes = [];

    /** @var string HTTP Request method (GET, POST, PUT, DELETE) */
    private $method;

    /** @var string Request path dari URL */
    private $path;

    /** @var array Parameters yang diextract dari URL pattern */
    private $params = [];

    // ============================================
    // CONSTRUCTOR
    // ============================================
    /**
     * Inisialisasi Router
     * Mendeteksi HTTP method dan request path
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = $this->getPath();
    }

    // ============================================
    // SECTION 1: PATH EXTRACTION
    // ============================================
    /**
     * Ekstrak clean path dari HTTP request
     * 
     * Priority:
     * 1. $_GET['route'] dari .htaccess RewriteRule
     * 2. $_SERVER['PATH_INFO'] dari server
     * 3. $_SERVER['REQUEST_URI'] (fallback)
     * 
     * @return string Clean path format: /path/to/route
     */
    private function getPath()
    {
        // Priority 1: Cek parameter 'route' dari RewriteRule
        $path = $_GET['route'] ?? null;

        // Priority 2: Cek PATH_INFO
        if (!$path) {
            $path = $_SERVER['PATH_INFO'] ?? null;
        }

        // Priority 3: Cek REQUEST_URI dan hapus query string
        if (!$path) {
            $uri = $_SERVER['REQUEST_URI'] ?? '/';
            $uri = explode('?', $uri)[0];

            // Hapus base directory jika di subdirectory XAMPP
            $script_name = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
            if ($script_name !== '/' && $script_name !== '') {
                $path = str_replace($script_name, '', $uri);
            } else {
                $path = $uri;
            }
        }

        // Normalisasi: selalu dimulai dengan / dan tidak ada trailing slash
        $path = '/' . trim($path, '/');

        return $path === '' ? '/' : $path;
    }


    // ============================================
    // SECTION 2: ROUTE REGISTRATION HELPERS
    // ============================================
    /**
     * Daftarkan route GET
     * @param string $route Route pattern (contoh: /users/{id})
     * @param string $controller Nama controller
     * @param string $action Nama method di controller
     */
    private function get($route, $controller, $action)
    {
        $this->routes['GET'][$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Daftarkan route POST
     */
    private function post($route, $controller, $action)
    {
        $this->routes['POST'][$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Daftarkan route PUT
     */
    private function put($route, $controller, $action)
    {
        $this->routes['PUT'][$route] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Daftarkan route DELETE
     */
    private function delete($route, $controller, $action)
    {
        $this->routes['DELETE'][$route] = ['controller' => $controller, 'action' => $action];
    }
    // ============================================
    // SECTION 3: ROUTE DEFINITIONS
    // ============================================
    /**
     * Definisikan semua route aplikasi
     * Dibagi menjadi: Public, Auth, Admin, dan API routes
     */
    private function defineRoutes()
    {
        // -------- PUBLIC ROUTES --------
        // Homepage dan halaman umum
        $this->get('/', 'HomeController', 'index');
        $this->get('/home', 'HomeController', 'index');
        $this->get('/alumni', 'AlumniController', 'index');
        $this->get('/alumni/{id}', 'AlumniController', 'detail');
        $this->get('/asisten', 'AsistenController', 'index');
        $this->get('/asisten/{id}', 'AsistenController', 'detail');
        $this->get('/jadwal', 'JadwalPraktikumController', 'index');
        $this->get('/fasilitas', 'FasilitasController', 'index');
        $this->get('/fasilitas/{id}', 'FasilitasController', 'detail');
        $this->get('/laboratorium', 'FasilitasController', 'index'); // Legacy alias
        $this->get('/laboratorium/{id}', 'FasilitasController', 'detail'); // Legacy alias
        $this->get('/praktikum', 'PraktikumController', 'index');
        $this->get('/praktikum/sanksi', 'SanksiController', 'index');
        $this->get('/praktikum/formatpenulisan', 'FormatPenulisanController', 'index');
        $this->get('/peraturan', 'PeraturanLabController', 'index');
        $this->get('/tata-tertib', 'PeraturanLabController', 'index'); // Alias
        $this->get('/tatatertib', 'PeraturanLabController', 'index');  // Alias
        $this->get('/kepala-lab', 'ManajemenController', 'kepalaIndex');
        $this->get('/kepala-lab/detail/{id}', 'ManajemenController', 'kepalaDetail');
        $this->get('/kepala', 'ManajemenController', 'kepalaIndex'); // Legacy alias

        // -------- AUTH ROUTES --------
        $this->get('/login', 'AuthController', 'login');
        $this->post('/login', 'AuthController', 'authenticate');
        $this->get('/logout', 'AuthController', 'logout');

        // -------- ADMIN DASHBOARD --------
        $this->get('/admin', 'DashboardController', 'index');
        $this->get('/admin/dashboard', 'DashboardController', 'index');

        // -------- ADMIN: ALUMNI --------
        $this->get('/admin/alumni', 'AlumniController', 'adminIndex');
        $this->get('/admin/alumni/create', 'AlumniController', 'create');
        $this->post('/admin/alumni', 'AlumniController', 'store');
        $this->get('/admin/alumni/{id}/edit', 'AlumniController', 'edit');
        $this->put('/admin/alumni/{id}', 'AlumniController', 'update');
        $this->delete('/admin/alumni/{id}', 'AlumniController', 'delete');

        // -------- ADMIN: ASISTEN --------
        $this->get('/admin/asisten', 'AsistenController', 'adminIndex');
        $this->get('/admin/asisten/create', 'AsistenController', 'create');
        $this->post('/admin/asisten', 'AsistenController', 'store');
        $this->get('/admin/asisten/{id}/edit', 'AsistenController', 'edit');
        $this->put('/admin/asisten/{id}', 'AsistenController', 'update');
        $this->delete('/admin/asisten/{id}', 'AsistenController', 'delete');
        $this->get('/admin/asisten/koordinator', 'AsistenController', 'pilihKoordinator');
        $this->post('/admin/asisten/koordinator', 'AsistenController', 'setKoordinator');

        // -------- ADMIN: USER MANAGEMENT --------
        $this->get('/admin/user', 'UserController', 'adminIndex');

        // -------- ADMIN: JADWAL PRAKTIKUM --------
        $this->get('/admin/jadwal', 'JadwalPraktikumController', 'adminIndex');
        $this->get('/admin/jadwal/create', 'JadwalPraktikumController', 'create');
        $this->post('/admin/jadwal', 'JadwalPraktikumController', 'store');
        $this->get('/admin/jadwal/{id}/edit', 'JadwalPraktikumController', 'edit');
        $this->put('/admin/jadwal/{id}', 'JadwalPraktikumController', 'update');
        $this->delete('/admin/jadwal/{id}', 'JadwalPraktikumController', 'delete');
        $this->post('/admin/jadwal/delete-multiple', 'JadwalPraktikumController', 'deleteMultiple');
        $this->get('/admin/jadwal/upload', 'JadwalPraktikumController', 'uploadForm');
        $this->post('/admin/jadwal/upload', 'JadwalPraktikumController', 'uploadProcess');
        $this->get('/admin/jadwal/csv-upload', 'JadwalPraktikumController', 'csvUploadForm');
        $this->post('/admin/jadwal/csv-upload', 'JadwalPraktikumController', 'csvUploadProcess');

        // -------- ADMIN: JADWAL UPK --------
        $this->get('/admin/jadwalupk', 'JadwalUpkController', 'adminIndex');
        $this->post('/admin/jadwalupk/upload', 'JadwalUpkController', 'upload');

        // -------- ADMIN: MODUL --------
        $this->get('/admin/modul', 'ModulController', 'adminIndex');
        $this->post('/admin/modul', 'ModulController', 'store');
        $this->put('/admin/modul/{id}', 'ModulController', 'update');
        $this->delete('/admin/modul/{id}', 'ModulController', 'delete');
        $this->get('/admin/modul/data', 'ModulController', 'getJson');

        // -------- ADMIN: SOP --------
        $this->get('/admin/sop', 'SopController', 'adminIndex');
        $this->post('/admin/sop', 'SopController', 'store');
        $this->put('/admin/sop/{id}', 'SopController', 'update');
        $this->delete('/admin/sop/{id}', 'SopController', 'delete');
        $this->get('/admin/sop/data', 'SopController', 'getJson');

        // -------- ADMIN: FASILITAS --------
        $this->get('/admin/fasilitas', 'FasilitasController', 'adminIndex');
        $this->get('/admin/fasilitas/create', 'FasilitasController', 'create');
        $this->post('/admin/fasilitas', 'FasilitasController', 'store');
        $this->get('/admin/fasilitas/{id}/edit', 'FasilitasController', 'edit');
        $this->put('/admin/fasilitas/{id}', 'FasilitasController', 'update');
        $this->delete('/admin/fasilitas/{id}', 'FasilitasController', 'delete');
        $this->delete('/admin/fasilitas/image/{id}', 'FasilitasController', 'deleteImage');
        $this->get('/admin/fasilitas/{id}/detail', 'FasilitasController', 'adminDetail');
        
        // Legacy aliases for backward compatibility
        $this->get('/admin/laboratorium', 'FasilitasController', 'adminIndex');
        $this->get('/admin/laboratorium/create', 'FasilitasController', 'create');
        $this->post('/admin/laboratorium', 'FasilitasController', 'store');
        $this->get('/admin/laboratorium/{id}/edit', 'FasilitasController', 'edit');
        $this->put('/admin/laboratorium/{id}', 'FasilitasController', 'update');
        $this->delete('/admin/laboratorium/{id}', 'FasilitasController', 'delete');
        $this->delete('/admin/laboratorium/image/{id}', 'FasilitasController', 'deleteImage');
        
        $this->get('/admin/informasi-lab', 'FasilitasController', 'adminIndex');
        $this->get('/admin/informasi-lab/create', 'FasilitasController', 'create');
        $this->post('/admin/informasi-lab', 'FasilitasController', 'store');
        $this->get('/admin/informasi-lab/{id}/edit', 'FasilitasController', 'edit');
        $this->put('/admin/informasi-lab/{id}', 'FasilitasController', 'update');
        $this->delete('/admin/informasi-lab/{id}', 'FasilitasController', 'delete');
        $this->get('/admin/informasi-lab/{id}/detail', 'FasilitasController', 'adminDetail');


        // -------- ADMIN: MATAKULIAH --------
        $this->get('/admin/matakuliah', 'MatakuliahController', 'adminIndex');
        $this->get('/admin/matakuliah/create', 'MatakuliahController', 'create');
        $this->post('/admin/matakuliah', 'MatakuliahController', 'store');
        $this->get('/admin/matakuliah/{id}/edit', 'MatakuliahController', 'edit');
        $this->put('/admin/matakuliah/{id}', 'MatakuliahController', 'update');
        $this->delete('/admin/matakuliah/{id}', 'MatakuliahController', 'delete');

        // -------- ADMIN: MANAJEMEN --------
        $this->get('/admin/manajemen', 'ManajemenController', 'adminIndex');
        $this->get('/admin/manajemen/create', 'ManajemenController', 'create');
        $this->post('/admin/manajemen', 'ManajemenController', 'store');
        $this->get('/admin/manajemen/{id}/edit', 'ManajemenController', 'edit');
        $this->put('/admin/manajemen/{id}', 'ManajemenController', 'update');
        $this->delete('/admin/manajemen/{id}', 'ManajemenController', 'delete');

        // -------- ADMIN: PERATURAN --------
        $this->get('/admin/peraturan', 'PeraturanLabController', 'adminIndex');
        $this->get('/admin/peraturan/create', 'PeraturanLabController', 'create');
        $this->post('/admin/peraturan', 'PeraturanLabController', 'store');
        $this->get('/admin/peraturan/{id}/edit', 'PeraturanLabController', 'edit');
        $this->put('/admin/peraturan/{id}', 'PeraturanLabController', 'update');
        $this->delete('/admin/peraturan/{id}', 'PeraturanLabController', 'delete');

        // -------- ADMIN: SANKSI --------
        $this->get('/admin/sanksi', 'SanksiController', 'adminIndex');
        $this->get('/admin/sanksi/create', 'SanksiController', 'create');
        $this->post('/admin/sanksi', 'SanksiController', 'store');
        $this->get('/admin/sanksi/{id}/edit', 'SanksiController', 'edit');
        $this->put('/admin/sanksi/{id}', 'SanksiController', 'update');
        $this->delete('/admin/sanksi/{id}', 'SanksiController', 'delete');

        // -------- ADMIN: FORMAT PENULISAN --------
        $this->get('/admin/formatpenulisan', 'FormatPenulisanController', 'adminIndex');
        $this->post('/admin/formatpenulisan', 'FormatPenulisanController', 'store');
        $this->put('/admin/formatpenulisan/{id}', 'FormatPenulisanController', 'update');
        $this->delete('/admin/formatpenulisan/{id}', 'FormatPenulisanController', 'delete');

        // ========== API ROUTES ==========

        // -------- API: PERATURAN LAB --------
        $this->get('/api/peraturan-lab', 'PeraturanLabController', 'index');
        $this->get('/api/peraturan-lab/{id}', 'PeraturanLabController', 'show');
        $this->post('/api/peraturan-lab', 'PeraturanLabController', 'store');
        $this->put('/api/peraturan-lab/{id}', 'PeraturanLabController', 'update');
        $this->delete('/api/peraturan-lab/{id}', 'PeraturanLabController', 'delete');

        // -------- API: SANKSI LAB --------
        $this->get('/api/sanksi-lab', 'SanksiController', 'apiIndex');
        $this->get('/api/sanksi-lab/{id}', 'SanksiController', 'apiShow');
        $this->post('/api/sanksi-lab', 'SanksiController', 'store');
        $this->put('/api/sanksi-lab/{id}', 'SanksiController', 'update');
        $this->delete('/api/sanksi-lab/{id}', 'SanksiController', 'delete');

        // -------- API: LABORATORIUM --------
        $this->get('/api/laboratorium', 'FasilitasController', 'apiIndex');
        $this->get('/api/laboratorium/{id}', 'FasilitasController', 'apiShow');
        $this->post('/api/laboratorium', 'FasilitasController', 'store');
        $this->put('/api/laboratorium/{id}', 'FasilitasController', 'update');
        $this->delete('/api/laboratorium/{id}', 'FasilitasController', 'delete');

        // -------- API: ASISTEN --------
        $this->get('/api/asisten', 'AsistenController', 'apiIndex');
        $this->get('/api/asisten/{id}', 'AsistenController', 'show');
        $this->get('/api/asisten/{id}/matakuliah', 'AsistenController', 'matakuliah');
        $this->post('/api/asisten', 'AsistenController', 'store');
        $this->put('/api/asisten/{id}', 'AsistenController', 'update');
        $this->delete('/api/asisten/{id}', 'AsistenController', 'delete');

        // -------- API: MATAKULIAH --------
        $this->get('/api/matakuliah', 'MatakuliahController', 'index');
        $this->get('/api/matakuliah/{id}', 'MatakuliahController', 'show');
        $this->get('/api/matakuliah/{id}/asisten', 'MatakuliahController', 'asisten');
        $this->post('/api/matakuliah', 'MatakuliahController', 'store');
        $this->put('/api/matakuliah/{id}', 'MatakuliahController', 'update');
        $this->delete('/api/matakuliah/{id}', 'MatakuliahController', 'delete');

        // -------- API: JADWAL PRAKTIKUM --------
        $this->get('/api/jadwal', 'JadwalPraktikumController', 'apiIndex');
        $this->get('/api/jadwal/{id}', 'JadwalPraktikumController', 'show');
        $this->post('/api/jadwal', 'JadwalPraktikumController', 'create');
        $this->post('/api/jadwal/delete-multiple', 'JadwalPraktikumController', 'deleteMultiple');
        $this->put('/api/jadwal/{id}', 'JadwalPraktikumController', 'update');
        $this->delete('/api/jadwal/{id}', 'JadwalPraktikumController', 'delete');

        // -------- API: INFORMASI LAB --------
        $this->get('/api/informasi', 'FasilitasController', 'apiIndex');
        $this->get('/api/informasi/{id}', 'FasilitasController', 'apiShow');
        $this->post('/api/informasi', 'FasilitasController', 'store');
        $this->put('/api/informasi/{id}', 'FasilitasController', 'update');
        $this->delete('/api/informasi/{id}', 'FasilitasController', 'delete');

        // -------- API: MANAJEMEN --------
        $this->get('/api/manajemen', 'ManajemenController', 'index');
        $this->get('/api/manajemen/{id}', 'ManajemenController', 'show');
        $this->post('/api/manajemen', 'ManajemenController', 'store');
        $this->put('/api/manajemen/{id}', 'ManajemenController', 'update');
        $this->delete('/api/manajemen/{id}', 'ManajemenController', 'delete');

        // -------- API: FORMAT PENULISAN --------
        $this->get('/api/formatpenulisan', 'FormatPenulisanController', 'apiIndex');
        $this->get('/api/formatpenulisan/{id}', 'FormatPenulisanController', 'apiShow');
        $this->post('/api/formatpenulisan', 'FormatPenulisanController', 'store');
        $this->put('/api/formatpenulisan/{id}', 'FormatPenulisanController', 'update');
        $this->delete('/api/formatpenulisan/{id}', 'FormatPenulisanController', 'delete');

        // -------- API: ALUMNI --------
        $this->get('/api/alumni', 'AlumniController', 'apiIndex');
        $this->get('/api/alumni/{id}', 'AlumniController', 'apiShow');
    }


    // ============================================
    // SECTION 4: REQUEST DISPATCHING
    // ============================================
    /**
     * Dispatch request ke controller action yang sesuai
     * 
     * Proses:
     * 1. Load route definitions
     * 2. Match URL path dengan route patterns
     * 3. Ekstrak parameters
     * 4. Execute controller action
     */
    public function dispatch()
    {
        // Load routes hanya saat pertama kali diperlukan (lazy loading)
        if (empty($this->routes)) {
            $this->defineRoutes();
        }

        $route_found = false;

        // Cek apakah HTTP method ada di routes
        if (isset($this->routes[$this->method])) {
            // Loop semua routes dengan method yang sesuai
            foreach ($this->routes[$this->method] as $route => $handler) {
                $params = [];
                // Cek apakah URL path cocok dengan route pattern
                if ($this->match($route, $this->path, $params)) {
                    $route_found = true;
                    $this->params = $params;
                    $this->execute($handler['controller'], $handler['action']);
                    return;
                }
            }
        }

        // Jika tidak ada route yang cocok, show 404
        if (!$route_found) {
            $this->notFound();
        }
    }

    // ============================================
    // SECTION 5: ROUTE MATCHING & EXECUTION
    // ============================================
    /**
     * Match URL path dengan route pattern dan ekstrak parameters
     * 
     * Contoh:
     *   Pattern: /users/{id}/edit
     *   Path: /users/123/edit
     *   Result: $params = ['id' => '123']
     * 
     * @param string $pattern Route pattern dengan {placeholder}
     * @param string $path Request URL path
     * @param array &$params Reference untuk hasil parameter extraction
     * @return bool True jika path cocok dengan pattern
     */
    private function match($pattern, $path, &$params)
    {
        // Konversi route pattern ke regex
        // {id} -> (?P<id>[a-zA-Z0-9_-]+)
        $regex = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        // Cek apakah path cocok dengan regex pattern
        if (preg_match($regex, $path, $matches)) {
            // Ekstrak named parameters
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
     * Eksekusi controller action dengan extracted parameters
     * 
     * Proses:
     * 1. Load controller file
     * 2. Validasi class dan method
     * 3. Check auth middleware (untuk admin routes)
     * 4. Call action method dengan parameters
     * 
     * @param string $controller Nama controller class
     * @param string $action Nama method untuk dieksekusi
     */
    private function execute($controller, $action)
    {
        // Tentukan path controller file
        $controller_file = CONTROLLER_PATH . '/' . $controller . '.php';

        // Validasi: cek apakah file controller exists
        if (!file_exists($controller_file)) {
            $this->notFound();
            return;
        }

        // Load controller file
        require_once $controller_file;

        // Validasi: cek apakah class exists
        if (!class_exists($controller)) {
            $this->notFound();
            return;
        }

        // Instansiasi controller
        $controller_instance = new $controller();

        // Validasi: cek apakah method exists
        if (!method_exists($controller_instance, $action)) {
            $this->notFound();
            return;
        }

        // Check auth middleware untuk admin routes
        if (strpos($this->path, '/admin') === 0) {
            require_once APP_PATH . '/middleware/AuthMiddleware.php';
            AuthMiddleware::check();
        }

        // Eksekusi action method dengan parameters
        call_user_func([$controller_instance, $action], $this->params);
    }

    // ============================================
    // SECTION 6: ERROR HANDLING
    // ============================================
    /**
     * Handle 404 Not Found error
     * 
     * - Untuk API requests: return JSON response
     * - Untuk regular requests: tampilkan 404 page
     */
    private function notFound()
    {
        // Set HTTP status code 404
        http_response_code(404);

        // Cek apakah ini API request (path dimulai dengan /api/)
        if (strpos($this->path, '/api/') === 0) {
            // Return JSON error response
            header('Content-Type: application/json');
            echo json_encode(['error' => 'API endpoint not found']);
        } else {
            // Load dan tampilkan 404 error page
            require_once VIEW_PATH . '/errors/404.php';
        }
    }
}
?>
