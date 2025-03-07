<?php

namespace app\models;

use PDO;
use PDOException;
use Exception;
use app\core\Database;

class Branch {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function getAllBranches() {
        try {
            $stmt = $this->pdo->query("
                SELECT * FROM branches 
                WHERE deleted_at IS NULL 
                ORDER BY name ASC
            ");
            $result = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }
            return $result;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("حدث خطأ أثناء استرجاع الفروع");
        }
    }

    public function getBranchById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM branches 
                WHERE id = :id AND deleted_at IS NULL
            ");
            $stmt->execute([':id' => (int)$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("حدث خطأ أثناء استرجاع بيانات الفرع");
        }
    }

    public function createBranch($data) {
        try {
            if (empty($data['name'])) {
                throw new Exception("اسم الفرع مطلوب");
            }

            // التحقق من عدم تكرار اسم الفرع
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM branches WHERE name = :name AND deleted_at IS NULL");
            $stmt->execute([':name' => trim($data['name'])]);
            if ($stmt->fetchColumn() > 0) {
                return [
                    'status' => 'error',
                    'message' => 'هذا الفرع موجود بالفعل'
                ];
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO branches (name, address, phone, email, manager_name, status, notes) 
                VALUES (:name, :address, :phone, :email, :manager_name, :status, :notes)
            ");

            $success = $stmt->execute([
                ':name' => trim($data['name']),
                ':address' => trim($data['address'] ?? ''),
                ':phone' => trim($data['phone'] ?? ''),
                ':email' => trim($data['email'] ?? ''),
                ':manager_name' => trim($data['manager_name'] ?? ''),
                ':status' => $data['status'] ?? 'active',
                ':notes' => trim($data['notes'] ?? '')
            ]);

            if (!$success) {
                throw new Exception("فشل في إضافة الفرع");
            }

            return [
                'status' => 'success',
                'message' => 'تم إضافة الفرع بنجاح'
            ];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateBranch($data) {
        try {
            if (empty($data['id']) || empty($data['name'])) {
                throw new Exception("معرف واسم الفرع مطلوبان");
            }

            // التحقق من وجود الفرع
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM branches WHERE id = :id AND deleted_at IS NULL");
            $stmt->execute([':id' => (int)$data['id']]);
            if ($stmt->fetchColumn() == 0) {
                throw new Exception("الفرع غير موجود");
            }

            // التحقق من عدم تكرار اسم الفرع
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM branches 
                WHERE name = :name AND id != :id AND deleted_at IS NULL
            ");
            $stmt->execute([
                ':name' => $data['name'],
                ':id' => (int)$data['id']
            ]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("هذا الاسم موجود بالفعل");
            }

            $sql = "UPDATE branches SET 
                    name = :name,
                    address = :address,
                    phone = :phone,
                    email = :email,
                    manager_name = :manager_name,
                    status = :status,
                    notes = :notes,
                    updated_at = NOW()
                    WHERE id = :id AND deleted_at IS NULL";

            $stmt = $this->pdo->prepare($sql);
            
            $success = $stmt->execute([
                ':id' => (int)$data['id'],
                ':name' => $data['name'],
                ':address' => $data['address'] ?? '',
                ':phone' => $data['phone'] ?? '',
                ':email' => $data['email'] ?? '',
                ':manager_name' => $data['manager_name'] ?? '',
                ':status' => $data['status'] ?? 'active',
                ':notes' => $data['notes'] ?? ''
            ]);

            if (!$success) {
                throw new Exception("فشل تحديث الفرع");
            }

            return [
                'status' => 'success',
                'message' => 'تم تحديث الفرع بنجاح'
            ];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteBranch($id) {
        try {
            if (empty($id)) {
                throw new Exception("معرف الفرع مطلوب");
            }

            // التحقق من وجود الفرع
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM branches WHERE id = :id AND deleted_at IS NULL");
            $stmt->execute([':id' => (int)$id]);
            if ($stmt->fetchColumn() == 0) {
                throw new Exception("الفرع غير موجود");
            }

            // التحقق من عدم وجود مواد مرتبطة بالفرع
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM materials WHERE branch_id = :id AND deleted_at IS NULL");
            $stmt->execute([':id' => (int)$id]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("لا يمكن حذف الفرع لوجود مواد مرتبطة به");
            }

            // التحقق من عدم وجود مستخدمين مرتبطين بالفرع
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE branch_id = :id AND deleted_at IS NULL");
            $stmt->execute([':id' => (int)$id]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("لا يمكن حذف الفرع لوجود مستخدمين مرتبطين به");
            }

            $stmt = $this->pdo->prepare("UPDATE branches SET deleted_at = NOW() WHERE id = :id");
            $success = $stmt->execute([':id' => (int)$id]);

            if (!$success) {
                throw new Exception("فشل حذف الفرع");
            }

            return [
                'status' => 'success',
                'message' => 'تم حذف الفرع بنجاح'
            ];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
} 