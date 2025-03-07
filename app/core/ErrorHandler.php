<?php

namespace app\core;

class ErrorHandler {
    public static function handleException(\Throwable $exception) {
        // Log the error
        error_log($exception->getMessage());
        
        // If it's an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred. Please try again later.',
                'debug' => DEBUG ? $exception->getMessage() : null
            ]);
            exit;
        }

        // For regular requests
        $errorFile = ROOT_PATH . '/app/views/error/500.php';
        if (file_exists($errorFile)) {
            require_once $errorFile;
        } else {
            echo "<h1>500 Internal Server Error</h1>";
            if (DEBUG) {
                echo "<p>Error: " . htmlspecialchars($exception->getMessage()) . "</p>";
            }
        }
    }

    public static function handleError($errno, $errstr, $errfile, $errline) {
        if (!(error_reporting() & $errno)) {
            return false;
        }

        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public static function handleFatalError() {
        $error = error_get_last();
        if ($error !== NULL && $error['type'] === E_ERROR) {
            self::handleException(new \ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            ));
        }
    }
} 