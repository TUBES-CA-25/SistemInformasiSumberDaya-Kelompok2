<?php
/**
 * Base Controller Class untuk MVC Application
 */

class Controller {
    protected $params = [];
    
    /**
     * Load view dengan data
     */
    protected function view($view, $data = []) {
        // Extract data untuk digunakan sebagai variabel di view
        extract($data);

        // Tentukan CSS halaman berdasarkan lokasi view (fallback jika $pageCss belum ditentukan)
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
        
        // Tentukan layout berdasarkan route
        $route = $_GET['route'] ?? '';
        $routePath = ltrim($route, '/');
        // Robust admin detection: by route or by view path prefix
        $isAdmin = (strpos($routePath, 'admin/') === 0) || ($routePath === 'admin') || (strpos($view, 'admin/') === 0);
        $isApi = strpos($routePath, 'api/') === 0;
        
        if ($isApi) {
            // Untuk API, langsung return JSON
            $this->response($data);
            return;
        }
        
        if ($isAdmin) {
            // Load admin layout
            require_once VIEW_PATH . '/admin/templates/header.php';
            require_once VIEW_PATH . '/' . $view . '.php';
            require_once VIEW_PATH . '/admin/templates/footer.php';
        } else {
            // Load public layout
            require_once VIEW_PATH . '/templates/header.php';
            require_once VIEW_PATH . '/' . $view . '.php';
            require_once VIEW_PATH . '/templates/footer.php';
        }
    }

    /**
     * Load view tanpa layout (untuk AJAX)
     */
    protected function partial($view, $data = []) {
        extract($data);
        require_once VIEW_PATH . '/' . $view . '.php';
    }

    /**
     * Redirect ke URL lain
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

    /**
     * Return JSON response
     */
    protected function response($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    /**
     * Return success response
     */
    protected function success($data = null, $message = 'Success', $status = 200) {
        $this->response([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Return error response
     */
    protected function error($message = 'Error', $data = null, $status = 400) {
        $this->response([
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Get JSON request body
     */
    protected function getJson() {
        return json_decode(file_get_contents('php://input'), true);
    }

    /**
     * Get POST data
     */
    protected function getPost($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET data
     */
    protected function getGet($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /**
     * Check required fields
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

    /**
     * Set flash message
     */
    protected function setFlash($type, $message) {
        if (!session_id()) session_start();
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Get flash message
     */
    protected function getFlash($type) {
        if (!session_id()) session_start();
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }

    /**
     * Check if method is POST
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Check if method is GET
     */
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Check if method is PUT
     */
    protected function isPut() {
        return $_SERVER['REQUEST_METHOD'] === 'PUT';
    }

    /**
     * Check if method is DELETE
     */
    protected function isDelete() {
        return $_SERVER['REQUEST_METHOD'] === 'DELETE';
    }
}
?>
