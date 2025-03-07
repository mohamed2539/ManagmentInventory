-- إضافة عمود supplier_id إذا لم يكن موجوداً
ALTER TABLE materials
ADD COLUMN IF NOT EXISTS supplier_id INT,
ADD FOREIGN KEY (supplier_id) REFERENCES suppliers(id);

-- إضافة عمود category_id إذا لم يكن موجوداً
ALTER TABLE materials
ADD COLUMN IF NOT EXISTS category_id INT,
ADD FOREIGN KEY (category_id) REFERENCES categories(id);

-- إضافة أعمدة التتبع إذا لم تكن موجودة
ALTER TABLE materials
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL DEFAULT NULL; 