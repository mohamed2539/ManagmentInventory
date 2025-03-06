<?php

namespace app\models;

use PDO;
use PDOException;
use config\Database;

class Category {
    private $conn;
    private $table = 'categories';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        try {
            $query = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL ORDER BY name ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("Error retrieving categories");
        }
    }

    public function create($data) {
        try {
            if (empty($data['name'])) {
                throw new \Exception("Category name is required");
            }

            // Check for duplicate name
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE name = ? AND deleted_at IS NULL");
            $stmt->execute([$data['name']]);
            if ($stmt->fetchColumn() > 0) {
                throw new \Exception("Category already exists");
            }

            $stmt = $this->conn->prepare("
                INSERT INTO {$this->table} (name, description) 
                VALUES (:name, :description)
            ");

            return $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data['description'] ?? ''
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("Error creating category");
        }
    }

    public function update($data) {
        try {
            if (empty($data['id']) || empty($data['name'])) {
                throw new \Exception("Category ID and name are required");
            }

            // Check if category exists
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE id = ? AND deleted_at IS NULL");
            $stmt->execute([$data['id']]);
            if ($stmt->fetchColumn() == 0) {
                throw new \Exception("Category not found");
            }

            // Check for duplicate name
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) FROM {$this->table} 
                WHERE name = ? AND id != ? AND deleted_at IS NULL
            ");
            $stmt->execute([$data['name'], $data['id']]);
            if ($stmt->fetchColumn() > 0) {
                throw new \Exception("Category name already exists");
            }

            $stmt = $this->conn->prepare("
                UPDATE {$this->table} SET 
                name = :name,
                description = :description,
                updated_at = NOW()
                WHERE id = :id AND deleted_at IS NULL
            ");

            return $stmt->execute([
                ':id' => $data['id'],
                ':name' => $data['name'],
                ':description' => $data['description'] ?? ''
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("Error updating category");
        }
    }

    public function delete($id) {
        try {
            // Check if category exists
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE id = ? AND deleted_at IS NULL");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() == 0) {
                throw new \Exception("Category not found");
            }

            // Check if category has materials
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM materials WHERE category_id = ? AND deleted_at IS NULL");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() > 0) {
                throw new \Exception("Cannot delete category with associated materials");
            }

            $stmt = $this->conn->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("Error deleting category");
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ? AND deleted_at IS NULL");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("Error retrieving category");
        }
    }
} 