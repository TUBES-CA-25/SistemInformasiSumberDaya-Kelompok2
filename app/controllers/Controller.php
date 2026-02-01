<?php

/**
 * Controller - Kelas Dasar untuk Semua Controller MVC
 * 
 * Menyediakan utilities dan helper methods untuk:
 * - Pemuatan view dengan layout (admin/public)
 * - Penanganan response JSON (API)
 * - Session dan flash message management
 * - HTTP method checking (GET, POST, PUT, DELETE)
 * - Input data retrieval dan validasi
 * - Redirect handling dengan BASE_URL
 * 
 * Semua controller aplikasi harus extend class ini untuk mengakses
 * semua utility methods yang tersedia.
 */

class Controller {
    // =========================================================================
    // BAGIAN 1: PROPERTI
    // =========================================================================
    
    /** @var array Parameter yang dikirim dari router */
    protected $params = [];

    
    // =========================================================================
    // BAGIAN 2: VIEW LOADING
    // =========================================================================
    
    /**
     * Load View - Muat view dengan layout yang sesuai
     * 
     * Proses:
     * 1. Extract data array menjadi variabel terpisah untuk diakses di view
     * 2. Auto-detect CSS file berdasarkan view path (fallback jika belum ditentukan)
     * 3. Tentukan layout (admin atau public) berdasarkan route/view path
     * 4. Untuk API: return JSON response langsung
     * 5. Untuk Admin: load admin header + view + admin footer
     * 6. Untuk Public: load public header + view + public footer
     * 
     * Auto-detect CSS Mapping:
     * - home/* → home.css
     * - contact/* → contact.css
     * - praktikum/* → praktikum.css
     * - sumberdaya/* → sumberdaya.css
     * - fasilitas/* → fasilitas.css
     * - alumni/* → alumni.css
     * 
     * Layout Detection:
     * - API routes (api/*) → JSON response only
     * - Admin routes (admin/*) → Admin layout
     * - Public routes → Public layout
     * 
     * @param string $view View path tanpa extension (misal 'home/index')
     * @param array $data Data untuk dikirim ke view (akan di-extract menjadi variabel)
     * @return void Output view dengan layout atau JSON response
     */
    protected function view($view, $data = []) {
        // Extract data array menjadi variabel individual di scope view
        extract($data);

        // Auto-detect CSS halaman berdasarkan view path
        // Jika $pageCss belum ditentukan, tentukan berdasarkan view folder
        if (!isset($pageCss) || empty($pageCss)) {
            if (strpos($view, 'home/') === 0) {
                $pageCss = 'home.css';
            } elseif (strpos($view, 'contact/') === 0) {
                $pageCss = 'contact.css';
            } elseif (strpos($view, 'praktikum/') === 0) {
                $pageCss = 'praktikum.css';
            } elseif (strpos($view, 'sumberdaya/') === 0) {
                $pageCss = 'sumberdaya.css';
            } elseif (strpos($view, 'fasilitas/') === 0) {
                $pageCss = 'fasilitas.css';
            } elseif (strpos($view, 'alumni/') === 0) {
                $pageCss = 'alumni.css';
            } else {
                $pageCss = '';
            }
        }
        
        // Tentukan layout berdasarkan route dengan multiple detection methods
        $route = $_GET['route'] ?? '';
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        $routePath = ltrim($route, '/');
        
        // Deteksi admin route dengan multiple methods
        // Check: route param, REQUEST_URI, atau view path
        $isAdmin = (strpos($routePath, 'admin/') === 0) || 
                   ($routePath === 'admin') || 
                   (strpos($requestUri, '/admin') !== false) ||
                   (strpos($requestUri, '/dashboard') !== false) ||
                   (strpos($view, 'admin/') === 0);
        
        // Deteksi API route
        $isApi = strpos($routePath, 'api/') === 0;
        
        // Untuk API, langsung return JSON response
        if ($isApi) {
            $this->response($data);
            return;
        }
        
        // Untuk admin, gunakan admin layout
        if ($isAdmin) {
            require_once VIEW_PATH . '/admin/templates/header.php';
            require_once VIEW_PATH . '/' . $view . '.php';
            require_once VIEW_PATH . '/admin/templates/footer.php';
        } else {
            // Untuk public, gunakan public layout
            require_once VIEW_PATH . '/templates/header.php';
            require_once VIEW_PATH . '/' . $view . '.php';
            require_once VIEW_PATH . '/templates/footer.php';
        }
    }

    /**
     * Partial View - Muat view tanpa layout (untuk AJAX/partial content)
     * 
     * Menampilkan hanya file view tanpa header dan footer.
     * Cocok untuk AJAX requests atau content fragments.
     * 
     * @param string $view View path tanpa extension (misal 'modals/confirm')
     * @param array $data Data untuk dikirim ke view
     * @return void Output view partial
     */
    protected function partial($view, $data = []) {
        extract($data);
        require_once VIEW_PATH . '/' . $view . '.php';
    }

    
    // =========================================================================
    // BAGIAN 3: NAVIGATION & REDIRECT
    // =========================================================================
    
    /**
     * Redirect - Arahkan user ke URL lain
     * 
     * Menangani:
     * - Internal URL: tambahkan BASE_URL prefix jika belum ada
     * - External URL: redirect langsung tanpa modifikasi
     * - Slash handling: hapus leading slash untuk menghindari double slash
     * 
     * @param string $url URL tujuan (internal: '/admin', atau external: 'https://...')
     * @return void Mengirim header Location dan exit
     */
    protected function redirect($url) {
        // Jika URL tidak dimulai dengan http (bukan external link)
        if (strpos($url, 'http') !== 0) {
            // Pastikan BASE_URL didefinisikan
            if (defined('BASE_URL')) {
                // Hapus slash di awal url jika ada, untuk menghindari double slash
                $url = ltrim($url, '/');
                $url = BASE_URL . '/' . $url;
            }
        }
        header('Location: ' . $url);
        exit;
    }

    
    // =========================================================================
    // BAGIAN 4: JSON RESPONSE HELPERS
    // =========================================================================
    
    /**
     * Response - Kirim JSON response dengan status code
     * 
     * Menangani:
     * - Bersihkan output buffer (mencegah warning dalam JSON)
     * - Set Content-Type: application/json header
     * - Set HTTP status code
     * - Encode dan output data sebagai JSON
     * - Exit untuk menghentikan eksekusi
     * 
     * @param array $data Data untuk dikonversi ke JSON
     * @param int $status HTTP status code (default: 200)
     * @return void Kirim JSON response dan exit
     */
    protected function response($data, $status = 200) {
        // Bersihkan output buffer jika ada (menghindari warning yang merusak JSON)
        if (ob_get_length()) ob_clean();
        
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    /**
     * Success Response - Kirim response sukses dengan format standar
     * 
     * Format response:
     * ```json
     * {
     *   "status": "success",
     *   "code": 200,
     *   "message": "Operasi berhasil",
     *   "data": { ... }
     * }
     * ```
     * 
     * @param mixed $data Data payload (array, object, atau null)
     * @param string $message Pesan sukses untuk user
     * @param int $status HTTP status code (default: 200)
     * @return void Kirim JSON response dan exit
     */
    protected function success($data = null, $message = 'Success', $status = 200) {
        $this->response([
            'status' => 'success',
            'code' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Error Response - Kirim response error dengan format standar
     * 
     * Format response:
     * ```json
     * {
     *   "status": "error",
     *   "code": 400,
     *   "message": "Deskripsi error",
     *   "data": null
     * }
     * ```
     * 
     * @param string $message Pesan error untuk user
     * @param mixed $data Data tambahan error (optional)
     * @param int $status HTTP status code (default: 400)
     * @return void Kirim JSON response dan exit
     */
    protected function error($message = 'Error', $data = null, $status = 400) {
        $this->response([
            'status' => 'error',
            'code' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    
    // =========================================================================
    // BAGIAN 5: INPUT DATA RETRIEVAL
    // =========================================================================
    
    /**
     * Get JSON Body - Ambil JSON payload dari request body
     * 
     * Membaca raw input stream dan decode sebagai JSON array.
     * Berguna untuk API requests dengan Content-Type: application/json
     * 
     * @return array|null Array hasil decode JSON atau null jika tidak valid
     */
    protected function getJson() {
        return json_decode(file_get_contents('php://input'), true);
    }

    /**
     * Get POST Data - Ambil data POST dari form submission
     * 
     * Jika key tidak diberikan, return seluruh $_POST array.
     * Jika key diberikan, return nilai key atau default jika tidak ada.
     * 
     * @param string|null $key Nama field POST (optional)
     * @param mixed $default Nilai default jika key tidak ada
     * @return mixed Data POST yang diminta atau seluruh array
     */
    protected function getPost($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET Data - Ambil data GET dari query string
     * 
     * Jika key tidak diberikan, return seluruh $_GET array.
     * Jika key diberikan, return nilai key atau default jika tidak ada.
     * 
     * @param string|null $key Nama parameter GET (optional)
     * @param mixed $default Nilai default jika key tidak ada
     * @return mixed Data GET yang diminta atau seluruh array
     */
    protected function getGet($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /**
     * Validate Required - Validasi field-field wajib ada
     * 
     * Memeriksa array data untuk memastikan field-field yang diperlukan
     * tidak kosong. Mengembalikan list field yang missing.
     * 
     * @param array $data Data yang akan divalidasi
     * @param array $required List field yang wajib ada (non-empty)
     * @return array Array berisi nama-nama field yang missing/kosong
     */
    protected function validateRequired($data, $required) {
        $missing = [];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $missing[] = $field;
            }
        }
        return $missing;
    }

    
    // =========================================================================
    // BAGIAN 6: SESSION & FLASH MESSAGE
    // =========================================================================
    
    /**
     * Set Flash Message - Simpan pesan yang akan ditampilkan satu kali
     * 
     * Flash message adalah pesan yang disimpan di session dan dihapus
     * setelah diambil. Cocok untuk success/error messages setelah action.
     * 
     * @param string $type Tipe pesan ('success', 'error', 'info', dll)
     * @param string $message Isi pesan untuk ditampilkan ke user
     * @return void
     */
    protected function setFlash($type, $message) {
        if (!session_id()) session_start();
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Get Flash Message - Ambil dan hapus flash message dari session
     * 
     * Mengambil flash message dari session dan langsung menghapusnya
     * agar tidak ditampilkan lagi. Mengembalikan null jika tidak ada.
     * 
     * @param string $type Tipe pesan yang ingin diambil
     * @return string|null Pesan yang disimpan atau null
     */
    protected function getFlash($type) {
        if (!session_id()) session_start();
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }

    
    // =========================================================================
    // BAGIAN 7: HTTP METHOD CHECKING
    // =========================================================================
    
    /**
     * Is POST - Cek apakah request menggunakan method POST
     * 
     * @return bool True jika request method adalah POST
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Is GET - Cek apakah request menggunakan method GET
     * 
     * @return bool True jika request method adalah GET
     */
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Is PUT - Cek apakah request menggunakan method PUT
     * 
     * @return bool True jika request method adalah PUT
     */
    protected function isPut() {
        return $_SERVER['REQUEST_METHOD'] === 'PUT';
    }

    /**
     * Is DELETE - Cek apakah request menggunakan method DELETE
     * 
     * @return bool True jika request method adalah DELETE
     */
    protected function isDelete() {
        return $_SERVER['REQUEST_METHOD'] === 'DELETE';
    }
}
?>
