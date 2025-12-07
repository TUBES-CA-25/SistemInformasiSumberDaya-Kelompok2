<?php
/**
 * Entry Point - Public Index
 * File ini adalah entry point utama aplikasi
 */

// Define base path
define('BASE_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Auto load files
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/routes/web.php';

// Start application
// Routing logic here
?>
