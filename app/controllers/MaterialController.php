<?php

namespace app\controllers;

use app\core\Database;
use app\models\Material;
use app\models\Category;
use app\models\Supplier;
use Exception;

class MaterialController {
    private $db;
    private $material;
    private $category;
    private $supplier;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->material = new Material($this->db);
        $this->category = new Category($this->db);
        $this->supplier = new Supplier($this->db);
    }

    public function index() {
        try {
            $materials = $this->material->getAll();
            $categories = $this->category->getAll();
            $suppliers = $this->supplier->getAll();

            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                require_once __DIR__ . '/../views/materials/_table.php';
                exit;
            }

            require_once __DIR__ . '/../views/materials/index.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = "حدث خطأ أثناء تحميل المواد: " . $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=material&action=index');
            exit;
        }
    }

    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('طريقة الطلب غير صحيحة');
            }

            $data = [
                'code' => $_POST['code'],
                'name' => $_POST['name'],
                'description' => $_POST['description'] ?? '',
                'quantity' => $_POST['quantity'],
                'unit' => $_POST['unit'],
                'category_id' => $_POST['category_id'],
                'supplier_id' => $_POST['supplier_id']
            ];

            if ($this->material->create($data)) {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                    echo json_encode(['status' => 'success', 'message' => 'تمت إضافة المادة بنجاح']);
                    exit;
                }
                $_SESSION['success_message'] = "تمت إضافة المادة بنجاح";
            } else {
                throw new Exception("فشل في إضافة المادة");
            }
        } catch (Exception $e) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: /NMaterailManegmentT/public/index.php?controller=material&action=index');
        exit;
    }

    public function edit() {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('معرف المادة مطلوب');
            }

            $id = $_GET['id'];
            $material = $this->material->getById($id);
            $categories = $this->category->getAll();
            $suppliers = $this->supplier->getAll();

            if (!$material) {
                throw new Exception('المادة غير موجودة');
            }

            require_once __DIR__ . '/../views/materials/edit.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=material&action=index');
            exit;
        }
    }

    public function update() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('طريقة الطلب غير صحيحة');
            }

            if (!isset($_POST['id'])) {
                throw new Exception('معرف المادة مطلوب');
            }

            $id = $_POST['id'];
            $data = [
                'code' => $_POST['code'],
                'name' => $_POST['name'],
                'description' => $_POST['description'] ?? '',
                'quantity' => $_POST['quantity'],
                'unit' => $_POST['unit'],
                'category_id' => $_POST['category_id'],
                'supplier_id' => $_POST['supplier_id']
            ];

            if ($this->material->update($id, $data)) {
                $_SESSION['success_message'] = "تم تحديث المادة بنجاح";
            } else {
                throw new Exception("فشل في تحديث المادة");
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: /NMaterailManegmentT/public/index.php?controller=material&action=index');
        exit;
    }

    public function delete() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('طريقة الطلب غير صحيحة');
            }

            if (!isset($_POST['id'])) {
                throw new Exception('معرف المادة مطلوب');
            }

            $id = $_POST['id'];
            if ($this->material->delete($id)) {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                    echo json_encode(['status' => 'success', 'message' => 'تم حذف المادة بنجاح']);
                    exit;
                }
                $_SESSION['success_message'] = "تم حذف المادة بنجاح";
            } else {
                throw new Exception("فشل في حذف المادة");
            }
        } catch (Exception $e) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: /NMaterailManegmentT/public/index.php?controller=material&action=index');
        exit;
    }

    public function search() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['term'])) {
                // عرض صفحة البحث المباشر
                require_once __DIR__ . '/../views/materials/search.php';
                exit;
            }

            if (!isset($_GET['term'])) {
                throw new Exception('مصطلح البحث مطلوب');
            }

            $term = $_GET['term'];
            $materials = $this->material->search($term);

            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                require_once __DIR__ . '/../views/materials/_table.php';
                exit;
            }

            require_once __DIR__ . '/../views/materials/index.php';
        } catch (Exception $e) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=material&action=search');
            exit;
        }
    }

    public function getByCode() {
        try {
            if (!isset($_GET['code'])) {
                throw new Exception('كود المادة مطلوب');
            }

            $code = $_GET['code'];
            $material = $this->material->getByCode($code);

            if (!$material) {
                throw new Exception('المادة غير موجودة');
            }

            echo json_encode(['status' => 'success', 'data' => $material]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function liveQuantity() {
        try {
            require_once __DIR__ . '/../views/materials/liveQuantity.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=material&action=index');
            exit;
        }
    }
} 