<?php

namespace app\controllers;

use app\core\BaseController;
use app\models\User;
use Exception;
error_reporting(E_ALL);
ini_set('display_errors', 1);
class AuthController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
            return;
        }
        
        $this->renderView('auth/login');
    }

    public function authenticate() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('طريقة طلب غير صحيحة');
            }
    
            if (empty($_POST['username']) || empty($_POST['password'])) {
                throw new Exception('يرجى إدخال اسم المستخدم وكلمة المرور');
            }

            // Debug log
            error_log("Authentication attempt - Username: " . $_POST['username']);
    
            $user = $this->userModel->login($_POST['username'], $_POST['password']);
            
            if ($user) {
                // Debug log
                error_log("User authenticated successfully - ID: " . $user['id']);
                
                // Set session data with correct variable names
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];  // Changed from username to user_name
                $_SESSION['user_role'] = $user['role'];       // Changed from role to user_role
                
                // We don't need to set branch_id in session since it's NULL
                // and branches haven't been added yet
    
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'تم تسجيل الدخول بنجاح'
                ]);
            } else {
                error_log("Authentication failed - Invalid credentials");
                throw new Exception('اسم المستخدم أو كلمة المرور غير صحيحة');
            }
        } catch (Exception $e) {
            error_log("Authentication error: " . $e->getMessage());
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 401);
        }
    }

    public function logout() {
        // Destroy session
        session_destroy();
        
        // Redirect to login page
        $this->redirect('auth', 'login');
    }

    public function register() {
        // Only admin can access this page
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('auth', 'login');
            return;
        }

        $this->renderView('auth/register');
    }

    public function store() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('طريقة طلب غير صحيحة');
            }

            // Only admin can create users
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                throw new Exception('غير مصرح لك بإنشاء مستخدمين');
            }

            $result = $this->userModel->create($_POST);
            
            if ($result) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'تم إنشاء المستخدم بنجاح'
                ]);
            } else {
                throw new Exception('فشل في إنشاء المستخدم');
            }
        } catch (Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('طريقة طلب غير صحيحة');
            }

            // Check if user is updating their own profile or is admin
            if (!isset($_SESSION['role']) || 
                ($_SESSION['role'] !== 'admin' && $_SESSION['user_id'] != $_POST['id'])) {
                throw new Exception('غير مصرح لك بتحديث هذا المستخدم');
            }

            $result = $this->userModel->update($_POST);
            
            if ($result) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'تم تحديث المستخدم بنجاح'
                ]);
            } else {
                throw new Exception('فشل في تحديث المستخدم');
            }
        } catch (Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete() {
        try {
            // Only admin can delete users
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                throw new Exception('غير مصرح لك بحذف المستخدمين');
            }

            if (!isset($_GET['id'])) {
                throw new Exception('معرف المستخدم مطلوب');
            }

            $result = $this->userModel->delete($_GET['id']);
            
            if ($result) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'تم حذف المستخدم بنجاح'
                ]);
            } else {
                throw new Exception('فشل في حذف المستخدم');
            }
        } catch (Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createAdmin() {
        try {
            // Create new admin user
            $adminData = [
                'username' => 'admin_new',
                'password' => 'admin123',
                'full_name' => 'مدير النظام',
                'email' => 'admin@example.com',
                'role' => 'admin'
            ];

            $result = $this->userModel->create($adminData);
            
            if ($result) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'تم إنشاء المستخدم بنجاح',
                    'username' => 'admin_new',
                    'password' => 'admin123'
                ]);
            } else {
                throw new Exception('فشل في إنشاء المستخدم');
            }
        } catch (Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 