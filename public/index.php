<?php
/**
 * MoveFlow — Front Controller
 * All requests are routed through this file.
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Define base path
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', __DIR__);

// Simple autoloader
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/Core/',
        APP_PATH . '/Config/',
        APP_PATH . '/Controllers/',
        APP_PATH . '/Models/',
        APP_PATH . '/Helpers/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load configuration
require_once APP_PATH . '/Config/App.php';

// Initialize session
Session::start();

// Load routes and dispatch
require_once APP_PATH . '/Config/Routes.php';
