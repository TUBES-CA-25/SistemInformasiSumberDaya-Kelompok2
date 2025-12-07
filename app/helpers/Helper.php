<?php
/**
 * Helper Functions
 */

/**
 * Redirect to a specific URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Print debug information
 */
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die;
}

/**
 * Return JSON response
 */
function json_response($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

/**
 * Sanitize input
 */
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

/**
 * Check if POST request
 */
function is_post() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Check if GET request
 */
function is_get() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}
?>
