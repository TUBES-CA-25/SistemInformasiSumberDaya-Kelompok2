<?php
class AuthMiddleware {
    public static function check() {
        if (!isset($_SESSION['user_id'])) {
            // Jika request API, return 401
            if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
                exit;
            }
            
            // Redirect ke login page
            header('Location: ' . PUBLIC_URL . '/login');
            exit;
        }
    }
}
