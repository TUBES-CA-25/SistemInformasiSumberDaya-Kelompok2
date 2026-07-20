<?php
class AuthMiddleware {
    /**
     * @param bool $isApi Paksa mode API (JSON 401) terlepas dari bentuk REQUEST_URI,
     *                     karena entry point api.php tidak selalu mengandung '/api/' di URI-nya.
     */
    public static function check($isApi = false) {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
            $isApi = $isApi
                || strpos($_SERVER['REQUEST_URI'], '/api/') !== false
                || strpos($_SERVER['REQUEST_URI'], 'api.php') !== false;

            if ($isApi) {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Silakan login terlebih dahulu.']);
                exit;
            }

            header('Location: ' . PUBLIC_URL . '/login');
            exit;
        }
    }
}

