<?php

namespace app\core;

abstract class BaseController {
    protected function loadModel($model) {
        $modelPath = "../app/models/$model.php";
        $modelClass = "app\\models\\$model";

        if (file_exists($modelPath)) {
            require_once $modelPath;
            if (class_exists($modelClass)) {
                return new $modelClass();
            }
            throw new \Exception("Class $modelClass not found in file");
        }
        throw new \Exception("Model $model not found");
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

    protected function redirect($controller, $action = 'index', $params = []) {
        $url = "/NMaterailManegmentT/public/index.php?controller=$controller&action=$action";
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $url .= "&$key=$value";
            }
        }
        header("Location: $url");
        exit;
    }
} 