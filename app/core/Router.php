<?php

namespace app\core;

class Router {
    private static $instance = null;
    private $routes = [];
    private $controller = 'home';
    private $action = 'index';
    private $params = [];

    private function __construct() {
        $this->parseUrl();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function parseUrl() {
        if (isset($_GET['controller'])) {
            $this->controller = strtolower($_GET['controller']);
        }
        
        if (isset($_GET['action'])) {
            $this->action = strtolower($_GET['action']);
        }
        
        // Remove controller and action from $_GET
        unset($_GET['controller'], $_GET['action']);
        
        // Store remaining parameters
        $this->params = $_GET;
    }

    public function dispatch() {
        // Format controller name
        $controllerName = ucfirst($this->controller) . 'Controller';
        $controllerClass = "app\\controllers\\{$controllerName}";
        
        // Check if controller exists
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerName} not found");
        }
        
        // Create controller instance
        $controller = new $controllerClass();
        
        // Check if action exists
        if (!method_exists($controller, $this->action)) {
            throw new \Exception("Action {$this->action} not found in controller {$controllerName}");
        }
        
        // Call the action with parameters
        return call_user_func_array([$controller, $this->action], [$this->params]);
    }

    public function getController() {
        return $this->controller;
    }

    public function getAction() {
        return $this->action;
    }

    public function getParams() {
        return $this->params;
    }
} 