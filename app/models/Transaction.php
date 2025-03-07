<?php

namespace app\models;

use PDO;
use Exception;

class Transaction {
    private $db;
    private $table = 'transactions';

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function create($data) {
        try {
            // التحقق من وجود المادة وكميتها
            $material = $this->getMaterialByCode($data['material_code']);
            if (!$material) {
                throw new Exception("المادة غير موجودة");
            }

            if ($data['type'] === 'withdrawal' && $material['quantity'] < $data['quantity']) {
                throw new Exception("الكمية المتوفرة غير كافية");
            }

            $this->db->beginTransaction();

            // إنشاء المعاملة
            $stmt = $this->db->prepare("
                INSERT INTO transactions (material_code, quantity, type, notes, user_id, created_at)
                VALUES (:material_code, :quantity, :type, :notes, :user_id, NOW())
            ");

            $stmt->execute([
                'material_code' => $data['material_code'],
                'quantity' => $data['quantity'],
                'type' => $data['type'],
                'notes' => $data['notes'],
                'user_id' => $data['user_id']
            ]);

            // تحديث كمية المادة
            $newQuantity = $data['type'] === 'addition' 
                ? $material['quantity'] + $data['quantity']
                : $material['quantity'] - $data['quantity'];

            $stmt = $this->db->prepare("
                UPDATE materials 
                SET quantity = :quantity, updated_at = NOW()
                WHERE code = :code
            ");

            $stmt->execute([
                'quantity' => $newQuantity,
                'code' => $data['material_code']
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("خطأ في إنشاء المعاملة: " . $e->getMessage());
        }
    }

    public function getAll() {
        try {
            $stmt = $this->db->query("
                SELECT t.*, m.name as material_name, u.name as user_name 
                FROM transactions t 
                LEFT JOIN materials m ON t.material_code = m.code
                LEFT JOIN users u ON t.user_id = u.id 
                ORDER BY t.created_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("خطأ في جلب المعاملات: " . $e->getMessage());
        }
    }

    public function getById($id) {
        try {
            $sql = "SELECT t.*, m.name as material_name, m.code as material_code, 
                           u.name as user_name
                    FROM " . $this->table . " t
                    LEFT JOIN materials m ON t.material_id = m.id
                    LEFT JOIN users u ON t.user_id = u.id
                    WHERE t.id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting transaction by ID: " . $e->getMessage());
            return null;
        }
    }

    public function getByMaterialId($materialId) {
        try {
            $stmt = $this->db->prepare("
                SELECT t.*, m.name as material_name, u.name as user_name 
                FROM transactions t 
                LEFT JOIN materials m ON t.material_code = m.code
                LEFT JOIN users u ON t.user_id = u.id 
                WHERE m.id = :material_id
                ORDER BY t.created_at DESC
            ");

            $stmt->execute(['material_id' => $materialId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("خطأ في جلب معاملات المادة: " . $e->getMessage());
        }
    }

    public function getByUserId($user_id) {
        try {
            $sql = "SELECT t.*, m.name as material_name, m.code as material_code, 
                           u.name as user_name
                    FROM " . $this->table . " t
                    LEFT JOIN materials m ON t.material_id = m.id
                    LEFT JOIN users u ON t.user_id = u.id
                    WHERE t.user_id = :user_id
                    ORDER BY t.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting transactions by user ID: " . $e->getMessage());
            return [];
        }
    }

    public function search($term) {
        try {
            $stmt = $this->db->prepare("
                SELECT t.*, m.name as material_name, u.name as user_name 
                FROM transactions t 
                LEFT JOIN materials m ON t.material_code = m.code
                LEFT JOIN users u ON t.user_id = u.id 
                WHERE m.name LIKE :term 
                   OR m.code LIKE :term 
                   OR t.type LIKE :term 
                   OR u.name LIKE :term
                ORDER BY t.created_at DESC
            ");

            $term = "%$term%";
            $stmt->execute(['term' => $term]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("خطأ في البحث عن المعاملات: " . $e->getMessage());
        }
    }

    private function getMaterialByCode($code) {
        $stmt = $this->db->prepare("SELECT * FROM materials WHERE code = :code");
        $stmt->execute(['code' => $code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 