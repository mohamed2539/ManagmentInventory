<?php

namespace app\models;

use PDO;
use PDOException;
use config\Database;

class Branch {
    private $conn;
    private $table = 'branches';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        try {
            $stmt = $this->conn->prepare("
                SELECT * FROM {$this->table} 
                WHERE is_deleted IS NULL
                ORDER BY name ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("خطأ في استرجاع الفروع");
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT * FROM {$this->table} 
                WHERE id = ? AND is_deleted IS NULL
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("خطأ في استرجاع بيانات الفرع");
        }
    }

    public function create($data) {
        try {
            if (empty($data['name'])) {
                throw new \Exception("اسم الفرع مطلوب");
            }

            $stmt = $this->conn->prepare("
                INSERT INTO {$this->table} (name, address, phone, email, manager_name, status, notes, created_at, updated_at) 
                VALUES (:name, :address, :phone, :email, :manager_name, :status, :notes, NOW(), NOW())
            ");

            return $stmt->execute([
                ':name' => $data['name'],
                ':address' => $data['address'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':email' => $data['email'] ?? null,
                ':manager_name' => $data['manager_name'] ?? null,
                ':status' => $data['status'] ?? '1',
                ':notes' => $data['notes'] ?? null
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("خطأ في إنشاء الفرع");
        }
    }

    public function update($data) {
        try {
            if (empty($data['id']) || empty($data['name'])) {
                throw new \Exception("البيانات الأساسية مطلوبة");
            }

            $stmt = $this->conn->prepare("
                UPDATE {$this->table} SET 
                name = :name,
                address = :address,
                phone = :phone,
                email = :email,
                manager_name = :manager_name,
                status = :status,
                notes = :notes,
                updated_at = NOW()
                WHERE id = :id AND is_deleted IS NULL
            ");

            return $stmt->execute([
                ':id' => $data['id'],
                ':name' => $data['name'],
                ':address' => $data['address'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':email' => $data['email'] ?? null,
                ':manager_name' => $data['manager_name'] ?? null,
                ':status' => $data['status'] ?? '1',
                ':notes' => $data['notes'] ?? null
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("خطأ في تحديث الفرع");
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE {$this->table} 
                SET is_deleted = 1, deleted_at = NOW()
                WHERE id = ?
            ");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("خطأ في حذف الفرع");
        }
    }
} 