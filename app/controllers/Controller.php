<?php
namespace App\Controllers;

/**
 * Base Controller Class
 */

class Controller {
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
    protected function success($data, $message = 'Success', $status = 200) {
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
}
?>
