<?php
/**
 * Router Class
 * --------------------------------------------------------------------------
 * Inti dari aplikasi MVC. Bertugas membedah URL yang diminta user,
 * mencocokkannya dengan daftar route yang terdaftar, dan memanggil
 * Controller yang sesuai.
 *
 * @package Core
 * @author  System Dev
 */

class Router
{
    /** @var array Daftar route yang terdaftar [METHOD][URL] => handler */
    private array $routes = [];

    /** @var string Metode request HTTP (GET, POST, PUT, DELETE) */
    private string $method;

    /** @var string URL Path yang sedang diakses (bersih dari query string) */
    private string $path;

    /** @var array Parameter dinamis yang diekstrak dari URL (misal: {id}) */
    private array $params = [];

    /**
     * Constructor
     * Menginisialisasi metode request dan path saat ini.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->path   = $this->resolvePath();
    }

    /**
     * Resolve Path
     * Mendapatkan path URL yang bersih, menangani berbagai skenario server
     * (RewriteRule, PATH_INFO, atau REQUEST_URI untuk XAMPP).
     *
     * @return string Path yang sudah dinormalisasi (contoh: '/admin/dashboard')
     */
    private function resolvePath(): string
    {
        // 1. Prioritas: Cek parameter 'route' dari .htaccess (RewriteRule)
        if (isset($_GET['route'])) {
            return $this->normalizePath($_GET['route']);
        }

        // 2. Cek PATH_INFO (biasanya tersedia di server standar)
        if (isset($_SERVER['PATH_INFO'])) {
            return $this->normalizePath($_SERVER['PATH_INFO']);
        }

        // 3. Fallback: Parse REQUEST_URI (Handling untuk XAMPP/Subfolder)
        $uri = explode('?', $_SERVER['REQUEST_URI'] ?? '/')[0];
        
        // Dapatkan folder script berjalan (misal: /nama_project)
        $scriptName = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        
        // Hapus folder project dari URI jika ada
        if ($scriptName !== '/' && $scriptName !== '') {
            $uri = str_replace($scriptName, '', $uri);
        }

        return $this->normalizePath($uri);
    }

    /**
     * Normalisasi string path agar selalu diawali dengan slash.
     *
     * @param string $path Path mentah
     * @return string Path bersih
     */
    private function normalizePath(string $path): string
    {
        $path = trim($path, '/');
        return $path === '' ? '/' : '/' . $path;
    }

    /**
     * Mendaftarkan Route GET
     *
     * @param string $route Pattern URL (contoh: '/user/{id}')
     * @param string $controller Nama Class Controller
     * @param string $action Nama Method di Controller
     */
    public function get(string $route, string $controller, string $action): void
    {
        $this->addRoute('GET', $route, $controller, $action);
    }

    /**
     * Mendaftarkan Route POST
     */
    public function post(string $route, string $controller, string $action): void
    {
        $this->addRoute('POST', $route, $controller, $action);
    }

    /**
     * Mendaftarkan Route PUT
     */
    public function put(string $route, string $controller, string $action): void
    {
        $this->addRoute('PUT', $route, $controller, $action);
    }

    /**
     * Mendaftarkan Route DELETE
     */
    public function delete(string $route, string $controller, string $action): void
    {
        $this->addRoute('DELETE', $route, $controller, $action);
    }

    /**
     * Helper internal untuk menyimpan route ke array.
     */
    private function addRoute(string $method, string $route, string $controller, string $action): void
    {
        $this->routes[$method][$route] = [
            'controller' => $controller,
            'action'     => $action
        ];
    }

    /**
     * Dispatcher
     * Mencari route yang cocok dengan URL saat ini dan mengeksekusinya.
     */
    public function dispatch(): void
    {
        // Load definisi route hanya saat dibutuhkan (Lazy Loading)
        if (empty($this->routes)) {
            $this->defineRoutes();
        }

        // Cek apakah ada route untuk method saat ini
        if (isset($this->routes[$this->method])) {
            foreach ($this->routes[$this->method] as $routePattern => $handler) {
                // Reset params setiap iterasi
                $params = [];

                // Jika pattern cocok dengan URL saat ini
                if ($this->match($routePattern, $this->path, $params)) {
                    $this->params = $params;
                    $this->execute($handler['controller'], $handler['action']);
                    return; // Berhenti mencari setelah ketemu
                }
            }
        }

        // Jika loop selesai dan tidak ada yang cocok
        $this->handleNotFound();
    }

    /**
     * Mencocokkan URL Pattern dengan Path aktual menggunakan Regex.
     * Mendukung parameter dinamis seperti {id}.
     *
     * @param string $pattern Route pattern (misal: /user/{id})
     * @param string $path Path aktual (misal: /user/5)
     * @param array &$params Reference variable untuk menyimpan hasil capture param
     * @return bool True jika cocok
     */
    private function match(string $pattern, string $path, array &$params): bool
    {
        // Ubah {parameter} menjadi regex named group (?P<parameter>...)
        $regex = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $pattern);
        
        // Tambahkan delimiter regex
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $path, $matches)) {
            // Filter hasil match agar hanya mengambil key string (nama parameter)
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            return true;
        }

        return false;
    }

    /**
     * Eksekusi Controller dan Action yang ditemukan.
     *
     * @param string $controllerName Nama class controller
     * @param string $actionName Nama method controller
     */
    private function execute(string $controllerName, string $actionName): void
    {
        // 1. Validasi File Controller
        $controllerFile = CONTROLLER_PATH . '/' . $controllerName . '.php';
        if (!file_exists($controllerFile)) {
            $this->handleNotFound();
            return;
        }

        require_once $controllerFile;

        // 2. Validasi Class Controller
        if (!class_exists($controllerName)) {
            $this->handleNotFound();
            return;
        }

        $controllerInstance = new $controllerName();

        // 3. Validasi Method/Action
        if (!method_exists($controllerInstance, $actionName)) {
            $this->handleNotFound();
            return;
        }

        // 4. Cek Middleware (Khusus Admin)
        $this->checkMiddleware();

        // 5. Panggil Method Controller dengan Parameter
        call_user_func([$controllerInstance, $actionName], $this->params);
    }

    /**
     * Middleware Logic
     * Mengecek autentikasi untuk route tertentu (misal: /admin).
     */
    private function checkMiddleware(): void
    {
        if (strpos($this->path, '/admin') === 0) {
            $middlewarePath = APP_PATH . '/middleware/AuthMiddleware.php';
            if (file_exists($middlewarePath)) {
                require_once $middlewarePath;
                AuthMiddleware::check();
            }
        }
    }

    /**
     * Handle Error 404 (Not Found)
     * Menampilkan respon JSON untuk API atau View HTML untuk web biasa.
     */
    private function handleNotFound(): void
    {
        http_response_code(404);

        // Jika request diawali /api/, kembalikan JSON
        if (strpos($this->path, '/api/') === 0) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 404, 'error' => 'API endpoint not found']);
        } else {
            // Tampilkan halaman error jika file view ada
            $errorView = VIEW_PATH . '/errors/404.php';
            if (file_exists($errorView)) {
                require_once $errorView;
            } else {
                echo "<h1>404 Not Found</h1><p>The requested page does not exist.</p>";
            }
        }
        exit;
    }

    /**
     * Mendefinisikan seluruh route aplikasi.
     * Hardcoded list of routes.
     */
    private function defineRoutes(): void
    {
        // ==========================================
        // 1. PUBLIC ROUTES (Frontend)
        // ==========================================
        $this->get('/', 'HomeController', 'index');
        $this->get('/home', 'HomeController', 'index');
        
        // Informasi & Profil
        $this->get('/profil', 'ProfilController', 'index');
        $this->get('/alumni', 'AlumniController', 'index');
        $this->get('/alumni/{id}', 'AlumniController', 'detail');
        $this->get('/asisten', 'AsistenController', 'index');
        $this->get('/asisten/{id}', 'AsistenController', 'detail');
        $this->get('/jadwal', 'JadwalPraktikumController', 'index');
        $this->get('/laboratorium', 'InformasiLabController', 'index');
        $this->get('/laboratorium/{id}', 'InformasiLabController', 'detail');
        
        // Praktikum & Peraturan
        $this->get('/praktikum', 'PraktikumController', 'index');
        $this->get('/praktikum/sanksi', 'SanksiController', 'index');
        $this->get('/praktikum/formatpenulisan', 'FormatPenulisanController', 'index');
        $this->get('/peraturan', 'PeraturanLabController', 'index');
        $this->get('/tata-tertib', 'PeraturanLabController', 'index'); // Alias
        $this->get('/tatatertib', 'PeraturanLabController', 'index'); // Alias
        $this->get('/riset', 'RisetController', 'index');

        // Manajemen Lab
        $this->get('/kepala-lab', 'ManajemenController', 'kepalaIndex');
        $this->get('/kepala-lab/detail/{id}', 'ManajemenController', 'kepalaDetail');
        $this->get('/kepala', 'ManajemenController', 'kepalaIndex'); // Legacy Alias

        // ==========================================
        // 2. AUTHENTICATION ROUTES
        // ==========================================
        $this->get('/login', 'AuthController', 'login');
        $this->post('/login', 'AuthController', 'authenticate');
        $this->get('/logout', 'AuthController', 'logout');

        // ==========================================
        // 3. ADMIN ROUTES (Backend)
        // ==========================================
        $this->get('/admin', 'DashboardController', 'index');
        $this->get('/admin/dashboard', 'DashboardController', 'index');
        
        // Admin User
        $this->get('/admin/user', 'UserController', 'adminIndex');

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

        // Admin Jadwal Praktikum & UPK
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
        
        $this->get('/admin/jadwalupk', 'JadwalUpkController', 'adminIndex');
        $this->post('/admin/jadwalupk/upload', 'JadwalUpkController', 'upload');

        // Admin Modul & SOP
        $this->get('/admin/modul', 'ModulController', 'adminIndex');
        $this->post('/admin/modul', 'ModulController', 'store');
        $this->put('/admin/modul/{id}', 'ModulController', 'update');
        $this->delete('/admin/modul/{id}', 'ModulController', 'delete');
        $this->get('/admin/modul/data', 'ModulController', 'getJson');

        $this->get('/admin/sop', 'SopController', 'adminIndex');
        $this->post('/admin/sop', 'SopController', 'store');
        $this->put('/admin/sop/{id}', 'SopController', 'update');
        $this->delete('/admin/sop/{id}', 'SopController', 'delete');
        $this->get('/admin/sop/data', 'SopController', 'getJson');

        // Admin Laboratorium (Fasilitas)
        $this->get('/admin/laboratorium', 'LaboratoriumController', 'adminIndex');
        $this->get('/admin/laboratorium/create', 'LaboratoriumController', 'create');
        $this->post('/admin/laboratorium', 'LaboratoriumController', 'store');
        $this->get('/admin/laboratorium/{id}', 'LaboratoriumController', 'detail');
        $this->get('/admin/laboratorium/{id}/edit', 'LaboratoriumController', 'edit');
        $this->put('/admin/laboratorium/{id}', 'LaboratoriumController', 'update');
        $this->delete('/admin/laboratorium/{id}', 'LaboratoriumController', 'delete');
        $this->delete('/admin/laboratorium/image/{id}', 'LaboratoriumController', 'deleteImage');

        // Admin Informasi Lab (Konten)
        $this->get('/admin/informasi-lab', 'InformasiLabController', 'adminIndex');
        $this->get('/admin/informasi-lab/create', 'InformasiLabController', 'create');
        $this->post('/admin/informasi-lab', 'InformasiLabController', 'store');
        $this->get('/admin/informasi-lab/{id}/edit', 'InformasiLabController', 'edit');
        $this->put('/admin/informasi-lab/{id}', 'InformasiLabController', 'update');
        $this->delete('/admin/informasi-lab/{id}', 'InformasiLabController', 'delete');
        
        // Admin Informasi Detail
        $this->get('/admin/informasi-lab/{id}/detail', 'InformasiLabController', 'adminDetail');
        $this->get('/admin/informasi-lab/{id}/detail/create', 'InformasiLabController', 'createDetail');
        $this->post('/admin/informasi-lab/{id}/detail', 'InformasiLabController', 'storeDetail');
        $this->get('/admin/informasi-lab/detail/{detail_id}/edit', 'InformasiLabController', 'editDetail');
        $this->put('/admin/informasi-lab/detail/{detail_id}', 'InformasiLabController', 'updateDetail');
        $this->delete('/admin/informasi-lab/detail/{detail_id}', 'InformasiLabController', 'deleteDetail');

        // Admin Master Data Lainnya
        $this->get('/admin/matakuliah', 'MatakuliahController', 'adminIndex');
        $this->get('/admin/matakuliah/create', 'MatakuliahController', 'create');
        $this->post('/admin/matakuliah', 'MatakuliahController', 'store');
        $this->get('/admin/matakuliah/{id}/edit', 'MatakuliahController', 'edit');
        $this->put('/admin/matakuliah/{id}', 'MatakuliahController', 'update');
        $this->delete('/admin/matakuliah/{id}', 'MatakuliahController', 'delete');

        $this->get('/admin/manajemen', 'ManajemenController', 'adminIndex');
        $this->get('/admin/manajemen/create', 'ManajemenController', 'create');
        $this->post('/admin/manajemen', 'ManajemenController', 'store');
        $this->get('/admin/manajemen/{id}/edit', 'ManajemenController', 'edit');
        $this->put('/admin/manajemen/{id}', 'ManajemenController', 'update');
        $this->delete('/admin/manajemen/{id}', 'ManajemenController', 'delete');

        $this->get('/admin/peraturan', 'PeraturanLabController', 'adminIndex');
        $this->get('/admin/peraturan/create', 'PeraturanLabController', 'create');
        $this->post('/admin/peraturan', 'PeraturanLabController', 'store');
        $this->get('/admin/peraturan/{id}/edit', 'PeraturanLabController', 'edit');
        $this->put('/admin/peraturan/{id}', 'PeraturanLabController', 'update');
        $this->delete('/admin/peraturan/{id}', 'PeraturanLabController', 'delete');

        $this->get('/admin/sanksi', 'SanksiController', 'adminIndex');
        $this->get('/admin/sanksi/create', 'SanksiController', 'create');
        $this->post('/admin/sanksi', 'SanksiController', 'store');
        $this->get('/admin/sanksi/{id}/edit', 'SanksiController', 'edit');
        $this->put('/admin/sanksi/{id}', 'SanksiController', 'update');
        $this->delete('/admin/sanksi/{id}', 'SanksiController', 'delete');

        $this->get('/admin/formatpenulisan', 'FormatPenulisanController', 'adminIndex');
        $this->post('/admin/formatpenulisan', 'FormatPenulisanController', 'store');
        $this->put('/admin/formatpenulisan/{id}', 'FormatPenulisanController', 'update');
        $this->delete('/admin/formatpenulisan/{id}', 'FormatPenulisanController', 'delete');

        // ==========================================
        // 4. API ROUTES (JSON Response)
        // ==========================================
        
        // API Read (GET)
        $this->get('/api/alumni', 'AlumniController', 'apiIndex');
        $this->get('/api/alumni/{id}', 'AlumniController', 'apiShow');
        $this->get('/api/asisten', 'AsistenController', 'apiIndex');
        $this->get('/api/asisten/{id}', 'AsistenController', 'show');
        $this->get('/api/asisten/{id}/matakuliah', 'AsistenController', 'matakuliah');
        $this->get('/api/jadwal', 'JadwalPraktikumController', 'apiIndex');
        $this->get('/api/jadwal/{id}', 'JadwalPraktikumController', 'show');
        $this->get('/api/jadwal-upk', 'JadwalUpkController', 'apiIndex');
        $this->get('/api/jadwal-upk/{id}', 'JadwalUpkController', 'apiShow');
        $this->get('/api/laboratorium', 'InformasiLabController', 'apiIndex'); // Alias to InformasiLab
        $this->get('/api/laboratorium/{id}', 'InformasiLabController', 'apiShow'); // Alias to InformasiLab
        $this->get('/api/informasi', 'InformasiLabController', 'index');
        $this->get('/api/informasi/{id}', 'InformasiLabController', 'show');
        $this->get('/api/informasi/tipe/{type}', 'InformasiLabController', 'byType');
        $this->get('/api/peraturan-lab', 'PeraturanLabController', 'apiIndex');
        $this->get('/api/peraturan-lab/{id}', 'PeraturanLabController', 'apiShow');
        $this->get('/api/tata-tertib', 'PeraturanLabController', 'apiIndex');
        $this->get('/api/sanksi-lab', 'SanksiController', 'apiIndex');
        $this->get('/api/sanksi-lab/{id}', 'SanksiController', 'apiShow');
        $this->get('/api/matakuliah', 'MatakuliahController', 'index');
        $this->get('/api/matakuliah/{id}', 'MatakuliahController', 'show');
        $this->get('/api/matakuliah/{id}/asisten', 'MatakuliahController', 'asisten');
        $this->get('/api/manajemen', 'ManajemenController', 'apiIndex');
        $this->get('/api/manajemen/{id}', 'ManajemenController', 'show');
        $this->get('/api/formatpenulisan', 'FormatPenulisanController', 'apiIndex');
        $this->get('/api/formatpenulisan/{id}', 'FormatPenulisanController', 'apiShow');

        // API Write (POST, PUT, DELETE) - Usually protected
        // Peraturan
        $this->post('/api/peraturan-lab', 'PeraturanLabController', 'store');
        $this->post('/api/peraturan-lab/{id}', 'PeraturanLabController', 'update'); // Support POST for method override
        $this->put('/api/peraturan-lab/{id}', 'PeraturanLabController', 'update');
        $this->delete('/api/peraturan-lab/{id}', 'PeraturanLabController', 'delete');

        // Sanksi
        $this->post('/api/sanksi-lab', 'SanksiController', 'store');
        $this->post('/api/sanksi-lab/{id}', 'SanksiController', 'update');
        $this->put('/api/sanksi-lab/{id}', 'SanksiController', 'update');
        $this->delete('/api/sanksi-lab/{id}', 'SanksiController', 'delete');

        // Laboratorium (Fisik)
        $this->post('/api/laboratorium', 'LaboratoriumController', 'store');
        $this->put('/api/laboratorium/{id}', 'LaboratoriumController', 'update');
        $this->delete('/api/laboratorium/{id}', 'LaboratoriumController', 'delete');

        // Asisten
        $this->post('/api/asisten', 'AsistenController', 'store');
        $this->put('/api/asisten/{id}', 'AsistenController', 'update');
        $this->delete('/api/asisten/{id}', 'AsistenController', 'delete');

        // Matakuliah
        $this->post('/api/matakuliah', 'MatakuliahController', 'store');
        $this->put('/api/matakuliah/{id}', 'MatakuliahController', 'update');
        $this->delete('/api/matakuliah/{id}', 'MatakuliahController', 'delete');

        // Jadwal
        $this->post('/api/jadwal', 'JadwalPraktikumController', 'create');
        $this->post('/api/jadwal/delete-multiple', 'JadwalPraktikumController', 'deleteMultiple');
        $this->put('/api/jadwal/{id}', 'JadwalPraktikumController', 'update');
        $this->delete('/api/jadwal/{id}', 'JadwalPraktikumController', 'delete');
        $this->post('/api/jadwal-upk', 'JadwalUpkController', 'store');
        $this->post('/api/jadwal-upk/delete-multiple', 'JadwalUpkController', 'deleteMultiple');
        $this->put('/api/jadwal-upk/{id}', 'JadwalUpkController', 'update');
        $this->delete('/api/jadwal-upk/{id}', 'JadwalUpkController', 'delete');

        // Informasi
        $this->post('/api/informasi', 'InformasiLabController', 'store');
        $this->put('/api/informasi/{id}', 'InformasiLabController', 'update');
        $this->delete('/api/informasi/{id}', 'InformasiLabController', 'delete');

        // Manajemen
        $this->post('/api/manajemen', 'ManajemenController', 'store');
        $this->put('/api/manajemen/{id}', 'ManajemenController', 'update');
        $this->delete('/api/manajemen/{id}', 'ManajemenController', 'delete');

        // Format Penulisan
        $this->post('/api/formatpenulisan', 'FormatPenulisanController', 'store');
        $this->put('/api/formatpenulisan/{id}', 'FormatPenulisanController', 'update');
        $this->delete('/api/formatpenulisan/{id}', 'FormatPenulisanController', 'delete');
    }
}
?>