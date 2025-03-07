<?php

namespace app\controllers;

use app\core\BaseController;

class ErrorController extends BaseController {
    
    public function notFound() {
        http_response_code(404);
        $this->view->setLayout('error');
        $this->view->render('error/404');
    }

    public function serverError($error = null) {
        http_response_code(500);
        $this->view->setLayout('error');
        $this->view->render('error/500', ['error' => $error]);
    }
} 