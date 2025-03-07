<?php

class Transaction {
    private $db;
    private $table = 'transactions';

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($data) {
        try {
            $this->db->beginTransaction();

            // Insert transaction
            $sql = "INSERT INTO " . $this->table . " 
                    (material_id, user_id, quantity, type, notes, created_at) 
                    VALUES (:material_id, :user_id, :quantity, :type, :notes, NOW())";

            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':material_id', $data['material_id']);
            $stmt->bindParam(':user_id', $data['user_id']);
            $stmt->bindParam(':quantity', $data['quantity']);
            $stmt->bindParam(':type', $data['type']); // 'withdrawal' or 'addition'
            $stmt->bindParam(':notes', $data['notes']);

            $stmt->execute();

            // Update material quantity
            $quantityChange = ($data['type'] === 'withdrawal') ? -$data['quantity'] : $data['quantity'];
            
            $sql = "UPDATE materials 
                    SET quantity = quantity + :quantity,
                        updated_at = NOW()
                    WHERE id = :material_id 
                    AND (quantity + :check_quantity) >= 0"; // Prevent negative quantity

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':quantity', $quantityChange);
            $stmt->bindParam(':check_quantity', $quantityChange);
            $stmt->bindParam(':material_id', $data['material_id']);
            
            $result = $stmt->execute();
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("الكمية المتاحة غير كافية");
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error creating transaction: " . $e->getMessage());
            throw $e;
        }
    }

    public function getAll() {
        try {
            $sql = "SELECT t.*, m.name as material_name, m.code as material_code, 
                           u.name as user_name
                    FROM " . $this->table . " t
                    LEFT JOIN materials m ON t.material_id = m.id
                    LEFT JOIN users u ON t.user_id = u.id
                    ORDER BY t.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting transactions: " . $e->getMessage());
            return [];
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

    public function getByMaterialId($material_id) {
        try {
            $sql = "SELECT t.*, m.name as material_name, m.code as material_code, 
                           u.name as user_name
                    FROM " . $this->table . " t
                    LEFT JOIN materials m ON t.material_id = m.id
                    LEFT JOIN users u ON t.user_id = u.id
                    WHERE t.material_id = :material_id
                    ORDER BY t.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':material_id', $material_id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting transactions by material ID: " . $e->getMessage());
            return [];
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
            $term = "%$term%";
            $sql = "SELECT t.*, m.name as material_name, m.code as material_code, 
                           u.name as user_name
                    FROM " . $this->table . " t
                    LEFT JOIN materials m ON t.material_id = m.id
                    LEFT JOIN users u ON t.user_id = u.id
                    WHERE m.code LIKE :term 
                    OR m.name LIKE :term 
                    OR u.name LIKE :term
                    ORDER BY t.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':term', $term);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error searching transactions: " . $e->getMessage());
            return [];
        }
    }
} 