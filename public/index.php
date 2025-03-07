<?php

// Define constants
define('ROOT_PATH', dirname(__DIR__));
define('DEBUG', true); // Set to false in production

// Composer autoloader
require_once ROOT_PATH . '/vendor/autoload.php';

// Error handling
set_error_handler(['app\core\ErrorHandler', 'handleError']);
set_exception_handler(['app\core\ErrorHandler', 'handleException']);
register_shutdown_function(['app\core\ErrorHandler', 'handleFatalError']);

// Start session
session_start();

// Parse the URL
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Set default controller and action
$controller = !empty($url[0]) ? $url[0] : 'home';
$action = !empty($url[1]) ? $url[1] : 'index';

// Remove controller and action from the URL array
array_shift($url);
if (!empty($url)) {
    array_shift($url);
}

// Parameters are the remaining elements in the URL array
$params = $url;

// Build the controller class name with proper casing
$controllerName = ucfirst($controller) . 'Controller';
$controllerClass = "app\\controllers\\{$controllerName}";

try {
    // Check if controller exists
    if (!class_exists($controllerClass)) {
        throw new \Exception("Controller not found: {$controllerName}");
    }

    // Create controller instance
    $controller = new $controllerClass();
    
    // Check if action exists
    if (!method_exists($controller, $action)) {
        throw new \Exception("Action not found: {$action}");
    }

    // Call the action with parameters
    call_user_func_array([$controller, $action], $params);

} catch (\Exception $e) {
    if (DEBUG) {
        echo "<pre>";
        echo "Error: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . "\n";
        echo "Line: " . $e->getLine() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString();
        echo "</pre>";
    } else {
        header("Location: /NMaterailManegmentT/public/error/notFound");
        exit;
    }
} 