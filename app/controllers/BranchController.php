<?php

namespace app\controllers;

use app\core\BaseController;
use app\models\Branch;
use Exception;

class BranchController extends BaseController {
    private $branchModel;

    public function __construct() {
        $this->branchModel = new Branch();
    }

    public function index() {
        try {
            $branches = $this->branchModel->getAll();
            $this->renderView('branches/index', ['branches' => $branches]);
        } catch (Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create() {
        $this->renderView('branches/create');
    }

    public function store() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('طريقة طلب غير صحيحة');
            }

            $result = $this->branchModel->create($_POST);
            
            if ($result) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'تم إنشاء الفرع بنجاح'
                ]);
            } else {
                throw new Exception('فشل في إنشاء الفرع');
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

            if (!isset($_POST['id'])) {
                throw new Exception('معرف الفرع مطلوب');
            }

            $result = $this->branchModel->update($_POST);
            
            if ($result) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'تم تحديث الفرع بنجاح'
                ]);
            } else {
                throw new Exception('فشل في تحديث الفرع');
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
            if (!isset($_GET['id'])) {
                throw new Exception('معرف الفرع مطلوب');
            }

            $result = $this->branchModel->delete($_GET['id']);
            
            if ($result) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'تم حذف الفرع بنجاح'
                ]);
            } else {
                throw new Exception('فشل في حذف الفرع');
            }
        } catch (Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getBranches() {
        try {
            $branches = $this->branchModel->getAll();
            $this->jsonResponse([
                'status' => 'success',
                'data' => $branches
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getLast20Branches() {
        try {
            $branches = $this->branchModel->getLast20Branches();
            $this->jsonResponse([
                'status' => 'success',
                'data' => $branches
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function edit() {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('معرف الفرع مطلوب');
            }

            $branch = $this->branchModel->getById($_GET['id']);
            if (!$branch) {
                throw new Exception('الفرع غير موجود');
            }

            $this->jsonResponse([
                'status' => 'success',
                'branch' => $branch
            ]);
        } catch (Exception $e) {
            error_log("Branch Edit Error: " . $e->getMessage());
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 