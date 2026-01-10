<?php
class AuthMiddleware {
    public static function check() {
        error_log('AUTH CHECK - Session ID: ' . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NONE'));
        error_log('AUTH CHECK - URI: ' . $_SERVER['REQUEST_URI']);
        
        if (!isset($_SESSION['user_id'])) {
            error_log('AUTH CHECK - UNAUTHORIZED! No session user_id');
            
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
        
        error_log('AUTH CHECK - AUTHORIZED');
    }
}

