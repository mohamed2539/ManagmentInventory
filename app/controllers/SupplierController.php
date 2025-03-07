<?php

namespace app\controllers;

use app\core\Database;
use app\models\Supplier;
use Exception;

class SupplierController {
    private $db;
    private $supplier;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->supplier = new Supplier($this->db);
    }

    public function index() {
        try {
            $suppliers = $this->supplier->getAll();
            require_once __DIR__ . '/../views/suppliers/index.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = "حدث خطأ أثناء تحميل الموردين: " . $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=home&action=index');
            exit;
        }
    }

    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                require_once __DIR__ . '/../views/suppliers/create.php';
                exit;
            }

            $data = [
                'name' => $_POST['name'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'] ?? null,
                'address' => $_POST['address'] ?? null,
                'contact_person' => $_POST['contact_person'] ?? null
            ];

            if ($this->supplier->create($data)) {
                $_SESSION['success_message'] = "تم إضافة المورد بنجاح";
                header('Location: /NMaterailManegmentT/public/index.php?controller=supplier&action=index');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=supplier&action=create');
            exit;
        }
    }

    public function edit() {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('معرف المورد مطلوب');
            }

            $id = $_GET['id'];
            $supplier = $this->supplier->getById($id);

            if (!$supplier) {
                throw new Exception('المورد غير موجود');
            }

            require_once __DIR__ . '/../views/suppliers/edit.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=supplier&action=index');
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
                'phone' => $_POST['phone'],
                'email' => $_POST['email'] ?? null,
                'address' => $_POST['address'] ?? null,
                'contact_person' => $_POST['contact_person'] ?? null
            ];

            if ($this->supplier->update($data)) {
                $_SESSION['success_message'] = "تم تحديث المورد بنجاح";
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: /NMaterailManegmentT/public/index.php?controller=supplier&action=index');
        exit;
    }

    public function delete() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('طريقة الطلب غير صحيحة');
            }

            if (!isset($_POST['id'])) {
                throw new Exception('معرف المورد مطلوب');
            }

            $id = $_POST['id'];
            if ($this->supplier->delete($id)) {
                $_SESSION['success_message'] = "تم حذف المورد بنجاح";
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: /NMaterailManegmentT/public/index.php?controller=supplier&action=index');
        exit;
    }
} 