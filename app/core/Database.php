<?php

namespace app\core;

use PDO;
use PDOException;
use Exception;

/** @var \PDO $pdo */

class Database {
    private $host = "localhost";
    private $db_name = "nmaterialmanegment";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            error_log("Attempting to connect to database with DSN: " . $dsn);
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8mb4");
            
            error_log("Database connection successful");
        } catch(PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            error_log("Error Code: " . $e->getCode());
            error_log("Stack Trace: " . $e->getTraceAsString());
            throw new Exception("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
        }

        return $this->conn;
    }
} 