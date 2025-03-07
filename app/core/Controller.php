<?php

namespace app\core;

class Controller {
    protected $db;
    protected $view;
    protected $model;

    public function __construct() {
        $this->db = \config\Database::getInstance()->connect();
        $this->view = new View();
    }

    protected function loadModel($model) {
        $modelClass = "app\\models\\" . $model;
        if (class_exists($modelClass)) {
            return new $modelClass($this->db);
        }
        throw new \Exception("Model not found: {$model}");
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url) {
        header("Location: /NMaterailManegmentT/public/{$url}");
        exit;
    }

    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    protected function requireAuth() {
        if (!$this->isAuthenticated()) {
            $this->redirect('auth/login');
        }
    }

    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    protected function getPostData() {
        return json_decode(file_get_contents('php://input'), true) ?? $_POST;
    }

    protected function validateCSRF() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new \Exception('CSRF token validation failed');
            }
        }
    }

    protected function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
} 