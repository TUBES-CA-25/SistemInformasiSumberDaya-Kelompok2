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
        
        // Tentukan layout berdasarkan route
        $route = $_GET['route'] ?? '';
        $isAdmin = (strpos($route, 'admin/') === 0) || ($route === 'admin');
        $isApi = strpos($route, 'api/') === 0;
        
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
