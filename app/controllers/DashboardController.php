<?php

namespace app\controllers;

use app\core\BaseController;
use Exception;

class DashboardController extends BaseController {
    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth', 'login');
            return;
        }
    }

    public function index() {
        try {
            // Get user data from session
            $userData = [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'role' => $_SESSION['user_role']
            ];

            // Render dashboard view with user data
            $this->renderView('dashboard/index', [
                'user' => $userData
            ]);
        } catch (Exception $e) {
            error_log("Dashboard error: " . $e->getMessage());
            $this->redirect('auth', 'login');
        }
    }
} 