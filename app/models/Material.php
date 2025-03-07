<?php

namespace app\models;

use PDO;
use Exception;

class Material {
    private $db;
    private $table = 'materials';

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function create($data) {
        try {
            $sql = "INSERT INTO " . $this->table . " 
                    (code, name, description, quantity, unit, category_id, supplier_id, created_at) 
                    VALUES (:code, :name, :description, :quantity, :unit, :category_id, :supplier_id, NOW())";

            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':code', $data['code']);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':quantity', $data['quantity']);
            $stmt->bindParam(':unit', $data['unit']);
            $stmt->bindParam(':category_id', $data['category_id']);
            $stmt->bindParam(':supplier_id', $data['supplier_id']);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("خطأ في إضافة المادة: " . $e->getMessage());
        }
    }

    public function getAll() {
        try {
            $sql = "SELECT m.*, c.name as category_name, s.name as supplier_name 
                    FROM " . $this->table . " m
                    LEFT JOIN categories c ON m.category_id = c.id
                    LEFT JOIN suppliers s ON m.supplier_id = s.id
                    ORDER BY m.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("خطأ في جلب المواد: " . $e->getMessage());
        }
    }

    public function getById($id) {
        try {
            $sql = "SELECT m.*, c.name as category_name, s.name as supplier_name 
                    FROM " . $this->table . " m
                    LEFT JOIN categories c ON m.category_id = c.id
                    LEFT JOIN suppliers s ON m.supplier_id = s.id
                    WHERE m.id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("خطأ في جلب المادة: " . $e->getMessage());
        }
    }

    public function getByCode($code) {
        try {
            $sql = "SELECT m.*, c.name as category_name, s.name as supplier_name 
                    FROM " . $this->table . " m
                    LEFT JOIN categories c ON m.category_id = c.id
                    LEFT JOIN suppliers s ON m.supplier_id = s.id
                    WHERE m.code = :code";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':code', $code, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("خطأ في جلب المادة: " . $e->getMessage());
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE " . $this->table . " 
                    SET code = :code, 
                        name = :name, 
                        description = :description, 
                        quantity = :quantity, 
                        unit = :unit, 
                        category_id = :category_id, 
                        supplier_id = :supplier_id, 
                        updated_at = NOW()
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':code', $data['code'], PDO::PARAM_STR);
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_STR);
            $stmt->bindParam(':unit', $data['unit'], PDO::PARAM_STR);
            $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
            $stmt->bindParam(':supplier_id', $data['supplier_id'], PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("خطأ في تحديث المادة: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("خطأ في حذف المادة: " . $e->getMessage());
        }
    }

    public function updateQuantity($id, $quantity, $type = 'addition') {
        try {
            $sql = "UPDATE " . $this->table . " 
                    SET quantity = quantity " . ($type === 'addition' ? '+' : '-') . " :quantity,
                        updated_at = NOW()
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("خطأ في تحديث الكمية: " . $e->getMessage());
        }
    }

    public function search($term) {
        try {
            $term = "%$term%";
            $sql = "SELECT m.*, c.name as category_name, s.name as supplier_name 
                    FROM " . $this->table . " m
                    LEFT JOIN categories c ON m.category_id = c.id
                    LEFT JOIN suppliers s ON m.supplier_id = s.id
                    WHERE m.code LIKE :term 
                    OR m.name LIKE :term 
                    OR m.description LIKE :term
                    ORDER BY m.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':term', $term, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("خطأ في البحث عن المواد: " . $e->getMessage());
        }
    }
} 