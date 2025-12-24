<?php
// Router script for PHP built-in server to support clean URLs
// Usage: php -S localhost:8000 -t public public/router.php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . $uri;

// Serve existing files directly (assets, images, etc.)
if ($uri !== '/' && file_exists($file) && is_file($file)) {
    return false; // Let the built-in server handle the file
}

// Rewrite everything else to index.php with route
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_GET['route'] = ltrim($uri, '/');

require __DIR__ . '/index.php';
?>
