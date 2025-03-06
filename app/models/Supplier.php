<?php

namespace app\models;

use app\core\Database;
use PDO;
use Exception;

class Supplier {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        try {
            $query = "SELECT * FROM suppliers WHERE is_deleted = 0 ORDER BY name";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('خطأ في جلب بيانات الموردين: ' . $e->getMessage());
        }
    }

    public function getById($id) {
        try {
            $query = "SELECT * FROM suppliers WHERE id = :id AND is_deleted = 0";
            $stmt = $this->db->query($query, ['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('خطأ في جلب بيانات المورد: ' . $e->getMessage());
        }
    }

    public function create($data) {
        try {
            // التحقق من عدم وجود مورد بنفس الاسم
            $existingSupplier = $this->getByName($data['name']);
            if ($existingSupplier) {
                throw new Exception('يوجد مورد بنفس الاسم بالفعل');
            }

            $query = "INSERT INTO suppliers (name, phone, email, address, contact_person) 
                     VALUES (:name, :phone, :email, :address, :contact_person)";
            
            return $this->db->query($query, [
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'address' => $data['address'] ?? null,
                'contact_person' => $data['contact_person'] ?? null
            ]);
        } catch (Exception $e) {
            throw new Exception('خطأ في إضافة المورد: ' . $e->getMessage());
        }
    }

    public function update($data) {
        try {
            // التحقق من وجود المورد
            $supplier = $this->getById($data['id']);
            if (!$supplier) {
                throw new Exception('المورد غير موجود');
            }

            // التحقق من عدم وجود مورد آخر بنفس الاسم
            $existingSupplier = $this->getByName($data['name']);
            if ($existingSupplier && $existingSupplier['id'] != $data['id']) {
                throw new Exception('يوجد مورد آخر بنفس الاسم');
            }

            $query = "UPDATE suppliers 
                     SET name = :name, 
                         phone = :phone, 
                         email = :email, 
                         address = :address, 
                         contact_person = :contact_person 
                     WHERE id = :id";
            
            return $this->db->query($query, [
                'id' => $data['id'],
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'address' => $data['address'] ?? null,
                'contact_person' => $data['contact_person'] ?? null
            ]);
        } catch (Exception $e) {
            throw new Exception('خطأ في تحديث بيانات المورد: ' . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            // التحقق من وجود المورد
            $supplier = $this->getById($id);
            if (!$supplier) {
                throw new Exception('المورد غير موجود');
            }

            // Soft delete
            $query = "UPDATE suppliers SET is_deleted = 1 WHERE id = :id";
            return $this->db->query($query, ['id' => $id]);
        } catch (Exception $e) {
            throw new Exception('خطأ في حذف المورد: ' . $e->getMessage());
        }
    }

    private function getByName($name) {
        try {
            $query = "SELECT * FROM suppliers WHERE name = :name AND is_deleted = 0";
            $stmt = $this->db->query($query, ['name' => $name]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('خطأ في البحث عن المورد: ' . $e->getMessage());
        }
    }

    public function getLast20Suppliers() {
        try {
            $query = "SELECT * FROM suppliers WHERE is_deleted = 0 ORDER BY id DESC LIMIT 20";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('خطأ في جلب بيانات الموردين: ' . $e->getMessage());
        }
    }
} 