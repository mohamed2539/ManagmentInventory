<?php

// Set error display
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base path
define('ROOT_PATH', dirname(__DIR__));

// Load autoloader
require_once ROOT_PATH . '/vendor/autoload.php';

// Register custom autoloader
spl_autoload_register(function ($class) {
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Check for .env file
if (file_exists(ROOT_PATH . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
    $dotenv->load();
} else {
    die('.env file not found. Please copy .env.example to .env');
}

// Set timezone
date_default_timezone_set('Africa/Cairo');

// Start session
session_start();

// Initialize router and dispatch request
$router = app\core\Router::getInstance();
$router->dispatch(); 