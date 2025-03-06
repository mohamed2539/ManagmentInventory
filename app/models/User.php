<?php

namespace app\models;

use PDO;
use PDOException;
use config\Database;

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($username, $password) {
        try {
            $stmt = $this->conn->prepare("
                SELECT u.*, b.name as branch_name 
                FROM {$this->table} u 
                LEFT JOIN branches b ON u.branch_id = b.id 
                WHERE u.username = ? AND u.is_deleted = 0
            ");
            
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Debug log
            error_log("Login attempt for username: " . $username);
            error_log("User found: " . ($user ? "Yes" : "No"));
            if ($user) {
                error_log("Stored password hash: " . $user['password']);
                error_log("Input password: " . $password);
                error_log("Password verify result: " . (password_verify($password, $user['password']) ? "True" : "False"));
            }

            if ($user && password_verify($password, $user['password'])) {
                unset($user['password']); // Remove password from session data
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            throw new \Exception("خطأ في تسجيل الدخول");
        }
    }

    public function create($data) {
        try {
            if (empty($data['username']) || empty($data['password']) || empty($data['full_name'])) {
                throw new \Exception("جميع الحقول مطلوبة");
            }

            // Check for duplicate username
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE username = ? AND deleted_at IS NULL");
            $stmt->execute([$data['username']]);
            if ($stmt->fetchColumn() > 0) {
                throw new \Exception("اسم المستخدم موجود بالفعل");
            }

            $stmt = $this->conn->prepare("
                INSERT INTO {$this->table} (username, password, full_name, email, role, branch_id) 
                VALUES (:username, :password, :full_name, :email, :role, :branch_id)
            ");

            return $stmt->execute([
                ':username' => $data['username'],
                ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
                ':full_name' => $data['full_name'],
                ':email' => $data['email'] ?? null,
                ':role' => $data['role'] ?? 'user',
                ':branch_id' => $data['branch_id'] ?? null
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("خطأ في إنشاء المستخدم");
        }
    }

    public function update($data) {
        try {
            if (empty($data['id']) || empty($data['username']) || empty($data['full_name'])) {
                throw new \Exception("البيانات الأساسية مطلوبة");
            }

            // Check for duplicate username
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) FROM {$this->table} 
                WHERE username = ? AND id != ? AND deleted_at IS NULL
            ");
            $stmt->execute([$data['username'], $data['id']]);
            if ($stmt->fetchColumn() > 0) {
                throw new \Exception("اسم المستخدم موجود بالفعل");
            }

            $sql = "UPDATE {$this->table} SET 
                    username = :username,
                    full_name = :full_name,
                    email = :email,
                    role = :role,
                    branch_id = :branch_id,
                    updated_at = NOW()";

            // Add password to update if provided
            if (!empty($data['password'])) {
                $sql .= ", password = :password";
            }

            $sql .= " WHERE id = :id AND deleted_at IS NULL";

            $stmt = $this->conn->prepare($sql);

            $params = [
                ':id' => $data['id'],
                ':username' => $data['username'],
                ':full_name' => $data['full_name'],
                ':email' => $data['email'] ?? null,
                ':role' => $data['role'] ?? 'user',
                ':branch_id' => $data['branch_id'] ?? null
            ];

            if (!empty($data['password'])) {
                $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("خطأ في تحديث المستخدم");
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("خطأ في حذف المستخدم");
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT u.*, b.name as branch_name 
                FROM {$this->table} u 
                LEFT JOIN branches b ON u.branch_id = b.id 
                WHERE u.id = ? AND u.deleted_at IS NULL
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("خطأ في استرجاع بيانات المستخدم");
        }
    }

    public function getAll() {
        try {
            $stmt = $this->conn->prepare("
                SELECT u.*, b.name as branch_name 
                FROM {$this->table} u 
                LEFT JOIN branches b ON u.branch_id = b.id 
                WHERE u.deleted_at IS NULL 
                ORDER BY u.username ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("خطأ في استرجاع المستخدمين");
        }
    }
} 