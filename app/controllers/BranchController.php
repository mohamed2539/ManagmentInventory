<?php

namespace app\controllers;

use app\core\BaseController;
use app\models\Branch;

class BranchController extends BaseController {
    private $branchModel;

    public function __construct() {
        $this->branchModel = new Branch();
    }

    public function index() {
        try {
            $branches = $this->branchModel->getAllBranches();
            
            // إذا كان الطلب AJAX، أعد فقط محتوى الجدول
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                $this->renderView('branches/_table', ['branches' => $branches]);
                exit;
            }
            
            $this->renderView('branches/index', ['branches' => $branches]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->renderView('branches/index', [
                'error' => $e->getMessage(),
                'branches' => []
            ]);
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'address' => $_POST['address'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'manager_name' => $_POST['manager_name'] ?? '',
                    'status' => $_POST['status'] ?? 'active',
                    'notes' => $_POST['notes'] ?? ''
                ];

                $result = $this->branchModel->createBranch($data);
                
                header('Content-Type: application/json');
                if ($result['status'] === 'success') {
                    echo json_encode([
                        'status' => 'success',
                        'message' => $result['message']
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => $result['message']
                    ]);
                }
                exit;
            } catch (\Exception $e) {
                error_log($e->getMessage());
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'حدث خطأ أثناء إضافة الفرع'
                ]);
                exit;
            }
        }
    }

    public function edit() {
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                throw new \Exception('معرف الفرع مطلوب');
            }

            $branch = $this->branchModel->getBranchById($id);
            if (!$branch) {
                throw new \Exception('الفرع غير موجود');
            }

            $this->renderView('branches/edit', ['branch' => $branch]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = $e->getMessage();
            $this->redirect('branch', 'index');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'id' => $_POST['id'] ?? null,
                    'name' => $_POST['name'] ?? '',
                    'address' => $_POST['address'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'manager_name' => $_POST['manager_name'] ?? '',
                    'status' => $_POST['status'] ?? 'active',
                    'notes' => $_POST['notes'] ?? ''
                ];

                if (!$data['id']) {
                    throw new \Exception('معرف الفرع مطلوب');
                }

                $result = $this->branchModel->updateBranch($data);
                
                if ($result['status'] === 'success') {
                    $_SESSION['success_message'] = $result['message'];
                } else {
                    $_SESSION['error_message'] = $result['message'];
                }
                
                $this->redirect('branch', 'index');
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $_SESSION['error_message'] = 'حدث خطأ أثناء تحديث الفرع';
                $this->redirect('branch', 'index');
            }
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'] ?? null;
                if (!$id) {
                    throw new \Exception('معرف الفرع مطلوب');
                }

                $result = $this->branchModel->deleteBranch($id);
                
                header('Content-Type: application/json');
                if ($result['status'] === 'success') {
                    echo json_encode([
                        'status' => 'success',
                        'message' => $result['message']
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => $result['message']
                    ]);
                }
                exit;
            } catch (\Exception $e) {
                error_log($e->getMessage());
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'حدث خطأ أثناء حذف الفرع'
                ]);
                exit;
            }
        }
    }
} 