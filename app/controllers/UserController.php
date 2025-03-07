<?php

namespace app\controllers;

use app\core\Database;
use app\models\User;
use Exception;

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->user = new User($this->db);
    }

    public function index() {
        try {
            $users = $this->user->getAll();
            require_once __DIR__ . '/../views/users/index.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = "حدث خطأ أثناء تحميل المستخدمين: " . $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=home&action=index');
            exit;
        }
    }

    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                require_once __DIR__ . '/../views/users/create.php';
                exit;
            }

            $data = [
                'username' => $_POST['username'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'role' => $_POST['role']
            ];

            if ($this->user->create($data)) {
                $_SESSION['success_message'] = "تم إنشاء المستخدم بنجاح";
                header('Location: /NMaterailManegmentT/public/index.php?controller=user&action=index');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=user&action=create');
            exit;
        }
    }

    public function edit() {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('معرف المستخدم مطلوب');
            }

            $id = $_GET['id'];
            $user = $this->user->getById($id);

            if (!$user) {
                throw new Exception('المستخدم غير موجود');
            }

            require_once __DIR__ . '/../views/users/edit.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=user&action=index');
            exit;
        }
    }

    public function update() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('طريقة الطلب غير صحيحة');
            }

            $data = [
                'id' => $_POST['id'],
                'username' => $_POST['username'],
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'role' => $_POST['role']
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if ($this->user->update($data)) {
                $_SESSION['success_message'] = "تم تحديث المستخدم بنجاح";
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: /NMaterailManegmentT/public/index.php?controller=user&action=index');
        exit;
    }

    public function delete() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('طريقة الطلب غير صحيحة');
            }

            if (!isset($_POST['id'])) {
                throw new Exception('معرف المستخدم مطلوب');
            }

            $id = $_POST['id'];
            if ($this->user->delete($id)) {
                $_SESSION['success_message'] = "تم حذف المستخدم بنجاح";
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: /NMaterailManegmentT/public/index.php?controller=user&action=index');
        exit;
    }
} 