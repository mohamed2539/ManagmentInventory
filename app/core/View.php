<?php

namespace app\core;

class View {
    private $layout = 'layout';
    private $viewPath;
    private $data = [];

    public function __construct() {
        $this->viewPath = ROOT_PATH . '/app/views/';
    }

    public function render($view, $data = []) {
        $this->data = array_merge($this->data, $data);
        $viewFile = $this->viewPath . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }

        ob_start();
        extract($this->data);
        require $viewFile;
        $content = ob_get_clean();

        if ($this->layout) {
            $layoutFile = $this->viewPath . 'layouts/' . $this->layout . '.php';
            if (!file_exists($layoutFile)) {
                throw new \Exception("Layout file not found: {$layoutFile}");
            }
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    public function url($path = '') {
        $path = ltrim($path, '/');
        return "/NMaterailManegmentT/public/" . $path;
    }

    public function asset($path) {
        return "/NMaterailManegmentT/public/assets/" . ltrim($path, '/');
    }

    public function csrf() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
    }

    public function partial($partial, $data = []) {
        $partialFile = $this->viewPath . 'partials/' . $partial . '.php';
        if (!file_exists($partialFile)) {
            throw new \Exception("Partial not found: {$partialFile}");
        }
        extract(array_merge($this->data, $data));
        require $partialFile;
    }
} 