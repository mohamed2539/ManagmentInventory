<?php

namespace app\core;

class ErrorHandler {
    public static function handleError($errno, $errstr, $errfile, $errline) {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public static function handleException($exception) {
        $error = $exception->getMessage();
        if (defined('DEBUG') && DEBUG) {
            $error .= ' in ' . $exception->getFile() . ' on line ' . $exception->getLine();
            $error .= "\nStack trace:\n" . $exception->getTraceAsString();
        }

        if (self::isAjaxRequest()) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $error]);
            exit;
        }

        http_response_code(500);
        $view = new View();
        $view->setLayout(null);
        $view->render('error/500', ['error' => $error]);
        exit;
    }

    public static function handleFatalError() {
        $error = error_get_last();
        if ($error !== null && $error['type'] === E_ERROR) {
            self::handleException(new \ErrorException(
                $error['message'], 
                0, 
                $error['type'], 
                $error['file'], 
                $error['line']
            ));
        }
    }

    private static function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
} 