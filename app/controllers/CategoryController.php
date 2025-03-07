<?php

namespace app\controllers;

use app\core\Database;
use app\models\Category;
use Exception;

class CategoryController {
    private $db;
    private $category;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->category = new Category($this->db);
    }

    public function index() {
        try {
            $categories = $this->category->getAll();
            require_once __DIR__ . '/../views/categories/index.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = "حدث خطأ أثناء تحميل الأقسام: " . $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=home&action=index');
            exit;
        }
    }

    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                require_once __DIR__ . '/../views/categories/create.php';
                exit;
            }

            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'] ?? ''
            ];

            if ($this->category->create($data)) {
                $_SESSION['success_message'] = "تم إضافة القسم بنجاح";
                header('Location: /NMaterailManegmentT/public/index.php?controller=category&action=index');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=category&action=create');
            exit;
        }
    }

    public function edit() {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('معرف القسم مطلوب');
            }

            $id = $_GET['id'];
            $category = $this->category->getById($id);

            if (!$category) {
                throw new Exception('القسم غير موجود');
            }

            require_once __DIR__ . '/../views/categories/edit.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=category&action=index');
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
                'name' => $_POST['name'],
                'description' => $_POST['description'] ?? ''
            ];

            if ($this->category->update($data)) {
                $_SESSION['success_message'] = "تم تحديث القسم بنجاح";
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: /NMaterailManegmentT/public/index.php?controller=category&action=index');
        exit;
    }

    public function delete() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('طريقة الطلب غير صحيحة');
            }

            if (!isset($_POST['id'])) {
                throw new Exception('معرف القسم مطلوب');
            }

            $id = $_POST['id'];
            if ($this->category->delete($id)) {
                $_SESSION['success_message'] = "تم حذف القسم بنجاح";
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: /NMaterailManegmentT/public/index.php?controller=category&action=index');
        exit;
    }
} 