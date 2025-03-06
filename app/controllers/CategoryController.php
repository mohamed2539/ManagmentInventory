<?php

namespace app\controllers;

use app\core\BaseController;
use app\models\Category;
use Exception;

class CategoryController extends BaseController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new Category();
    }

    public function index() {
        try {
            $categories = $this->categoryModel->getAll();
            $this->renderView('categories/index', ['categories' => $categories]);
        } catch (Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create() {
        $this->renderView('categories/index');
    }

    public function store() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $result = $this->categoryModel->create($_POST);
            
            if ($result) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'Category created successfully'
                ]);
            } else {
                throw new Exception('Failed to create category');
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
                throw new Exception('Invalid request method');
            }

            if (!isset($_POST['id'])) {
                throw new Exception('Category ID is required');
            }

            $result = $this->categoryModel->update($_POST);
            
            if ($result) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'Category updated successfully'
                ]);
            } else {
                throw new Exception('Failed to update category');
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
                throw new Exception('Category ID is required');
            }

            $result = $this->categoryModel->delete($_GET['id']);
            
            if ($result) {
                $this->jsonResponse([
                    'status' => 'success',
                    'message' => 'Category deleted successfully'
                ]);
            } else {
                throw new Exception('Failed to delete category');
            }
        } catch (Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCategories() {
        try {
            $categories = $this->categoryModel->getAll();
            $this->jsonResponse([
                'status' => 'success',
                'data' => $categories
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 