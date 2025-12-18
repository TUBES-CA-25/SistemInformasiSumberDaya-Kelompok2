<?php
/**
 * Single Entry Point untuk MVC Application
 * Semua request akan masuk melalui file ini
 */

// Error reporting untuk development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define constants
define('ROOT_PROJECT', dirname(__DIR__));
define('APP_PATH', ROOT_PROJECT . '/app');
define('VIEW_PATH', APP_PATH . '/views');
define('CONTROLLER_PATH', APP_PATH . '/controllers');

// Autoload classes
require_once ROOT_PROJECT . '/vendor/autoload.php';
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/Database.php';
require_once APP_PATH . '/config/Router.php';
require_once CONTROLLER_PATH . '/Controller.php';

// Initialize and dispatch router
$router = new Router();
$router->dispatch();
?>