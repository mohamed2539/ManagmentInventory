<?php

namespace app\controllers;

use app\core\Database;
use app\models\Transaction;
use Exception;

class TransactionController {
    private $db;
    private $transaction;
    private $material;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->transaction = new Transaction($this->db);
        $this->material = new Material($this->db);
    }

    public function index() {
        try {
            $transactions = $this->transaction->getAll();
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                require_once __DIR__ . '/../views/transactions/_table.php';
                exit;
            }

            require_once __DIR__ . '/../views/transactions/index.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = "حدث خطأ أثناء تحميل المعاملات: " . $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=home&action=index');
            exit;
        }
    }

    public function withdrawal() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('طريقة الطلب غير صحيحة');
            }

            $data = [
                'material_code' => $_POST['code'],
                'quantity' => $_POST['quantity'],
                'type' => 'withdrawal',
                'notes' => $_POST['notes'] ?? '',
                'user_id' => $_SESSION['user_id'] ?? null
            ];

            if ($this->transaction->create($data)) {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                    echo json_encode(['status' => 'success', 'message' => 'تم صرف الكمية بنجاح']);
                    exit;
                }
                $_SESSION['success_message'] = "تم صرف الكمية بنجاح";
            }
        } catch (Exception $e) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: /NMaterailManegmentT/public/index.php?controller=material&action=liveQuantity');
        exit;
    }

    public function addition() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('طريقة الطلب غير صحيحة');
            }

            $data = [
                'material_code' => $_POST['code'],
                'quantity' => $_POST['quantity'],
                'type' => 'addition',
                'notes' => $_POST['notes'] ?? '',
                'user_id' => $_SESSION['user_id'] ?? null
            ];

            if ($this->transaction->create($data)) {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                    echo json_encode(['status' => 'success', 'message' => 'تمت إضافة الكمية بنجاح']);
                    exit;
                }
                $_SESSION['success_message'] = "تمت إضافة الكمية بنجاح";
            }
        } catch (Exception $e) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
            $_SESSION['error_message'] = $e->getMessage();
        }

        header('Location: /NMaterailManegmentT/public/index.php?controller=material&action=liveQuantity');
        exit;
    }

    public function search() {
        try {
            if (!isset($_GET['term'])) {
                throw new Exception('مصطلح البحث مطلوب');
            }

            $term = $_GET['term'];
            $transactions = $this->transaction->search($term);

            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                require_once __DIR__ . '/../views/transactions/_table.php';
                exit;
            }

            require_once __DIR__ . '/../views/transactions/index.php';
        } catch (Exception $e) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=transaction&action=index');
            exit;
        }
    }

    public function getByMaterial() {
        try {
            if (!isset($_GET['material_id'])) {
                throw new Exception('معرف المادة مطلوب');
            }

            $materialId = $_GET['material_id'];
            $transactions = $this->transaction->getByMaterialId($materialId);

            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['status' => 'success', 'data' => $transactions]);
                exit;
            }

            require_once __DIR__ . '/../views/transactions/index.php';
        } catch (Exception $e) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /NMaterailManegmentT/public/index.php?controller=transaction&action=index');
            exit;
        }
    }
} 