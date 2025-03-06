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
            $this->renderView('branches/index', ['branches' => $branches]);
        } catch (\Exception $e) {
            $this->renderView('branches/index', [
                'branches' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $result = $this->branchModel->createBranch($_POST);
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            } catch (\Exception $e) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $branch = $this->branchModel->getBranchById($id);
                header('Content-Type: application/json');
                if ($branch) {
                    echo json_encode(['status' => 'success', 'data' => $branch]);
                } else {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => 'الفرع غير موجود']);
                }
                exit;
            } catch (\Exception $e) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $_POST['id'] = $id;
                $result = $this->branchModel->updateBranch($_POST);
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            } catch (\Exception $e) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $success = $this->branchModel->deleteBranch($id);
                header('Content-Type: application/json');
                if ($success) {
                    echo json_encode(['status' => 'success', 'message' => 'تم حذف الفرع بنجاح']);
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'فشل حذف الفرع']);
                }
                exit;
            } catch (\Exception $e) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                exit;
            }
        }
    }
} 