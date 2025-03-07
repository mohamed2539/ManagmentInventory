<?php

namespace app\models;

use PDO;
use Exception;

class Category {
    private $db;
    private $table = 'categories';

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAll() {
        try {
            $query = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL ORDER BY name ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("خطأ في جلب الأقسام: " . $e->getMessage());
        }
    }

    public function create($data) {
        try {
            if (empty($data['name'])) {
                throw new Exception("اسم القسم مطلوب");
            }

            // التحقق من عدم وجود قسم بنفس الاسم
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE name = :name AND deleted_at IS NULL");
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("يوجد قسم بنفس الاسم");
            }

            $stmt = $this->db->prepare("
                INSERT INTO {$this->table} (name, description) 
                VALUES (:name, :description)
            ");

            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'] ?? '', PDO::PARAM_STR);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("خطأ في إضافة القسم: " . $e->getMessage());
        }
    }

    public function update($data) {
        try {
            if (empty($data['id']) || empty($data['name'])) {
                throw new Exception("معرف القسم واسمه مطلوبان");
            }

            // التحقق من وجود القسم
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE id = :id AND deleted_at IS NULL");
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                throw new Exception("القسم غير موجود");
            }

            // التحقق من عدم وجود قسم آخر بنفس الاسم
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM {$this->table} 
                WHERE name = :name AND id != :id AND deleted_at IS NULL
            ");
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("يوجد قسم آخر بنفس الاسم");
            }

            $stmt = $this->db->prepare("
                UPDATE {$this->table} SET 
                name = :name,
                description = :description,
                updated_at = NOW()
                WHERE id = :id AND deleted_at IS NULL
            ");

            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'] ?? '', PDO::PARAM_STR);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("خطأ في تحديث القسم: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            // التحقق من وجود القسم
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE id = :id AND deleted_at IS NULL");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                throw new Exception("القسم غير موجود");
            }

            // التحقق من عدم وجود مواد مرتبطة بالقسم
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM materials WHERE category_id = :id AND deleted_at IS NULL");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("لا يمكن حذف القسم لوجود مواد مرتبطة به");
            }

            $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("خطأ في حذف القسم: " . $e->getMessage());
        }
    }

    public function getById($id) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE id = :id AND deleted_at IS NULL";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("خطأ في جلب القسم: " . $e->getMessage());
        }
    }
} 