<?php

namespace app\core;

use app\core\View;
use config\Database;

abstract class BaseController {
    protected $db;
    protected $view;
    protected $model;

    public function __construct() {
        $this->db = Database::getInstance()->connect();
        $this->view = new View();
    }

    protected function loadModel($model) {
        $modelClass = "app\\models\\" . $model;
        if (class_exists($modelClass)) {
            return new $modelClass($this->db);
        }
        throw new \Exception("Model not found: {$model}");
    }

    protected function renderView($view, $data = []) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Add user data and active menu
        $data['currentUser'] = [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'role' => $_SESSION['role'] ?? null,
            'full_name' => $_SESSION['full_name'] ?? null,
            'branch_id' => $_SESSION['branch_id'] ?? null,
            'branch_name' => $_SESSION['branch_name'] ?? null
        ];

        extract($data);

        // Store page content
        ob_start();
        
        // Determine file path
        $viewFile = $this->resolveViewPath($view);
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View $view not found at: $viewFile");
        }

        require_once $viewFile;
        $content = ob_get_clean();

        // Check for custom layout
        $layoutFile = "../app/views/layouts/layout.php";
        
        if (file_exists($layoutFile)) {
            require_once $layoutFile;
        } else {
            echo $content;
        }
    }

    private function resolveViewPath($view) {
        // Remove any ../views/ prefix if present
        $view = str_replace('../views/', '', $view);
        
        // If the view already has a full path, return it
        if (strpos($view, '/') === 0) {
            return $view;
        }
        
        // Otherwise, construct the path relative to app/views
        return '../app/views/' . $view . '.php';
    }

    protected function jsonResponse($data, $statusCode = 200) {
        try {
            header('Content-Type: application/json');
            http_response_code($statusCode);
            echo json_encode($data);
            exit;
        } catch (\Exception $e) {
            error_log("JSON RESPONSE ERROR: " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error formatting data']);
            exit;
        }
    }

    protected function getRequestData() {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    protected function redirect($url) {
        header('Location: ' . $this->view->url($url));
        exit;
    }

    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function getPost($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    protected function getQuery($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    protected function validateCSRF() {
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
            $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new \Exception('CSRF token validation failed');
        }
    }

    protected function apiUrl($controller, $action = 'index', $params = []) {
        $url = "/NMaterailManegmentT/public/api/{$controller}/{$action}";
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }
} 