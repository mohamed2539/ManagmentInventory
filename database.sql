-- Update branches table structure
ALTER TABLE branches
    ADD COLUMN email VARCHAR(100) AFTER phone,
    ADD COLUMN manager_name VARCHAR(100) AFTER email,
    ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active' AFTER manager_name,
    ADD COLUMN notes TEXT AFTER status,
    ADD COLUMN deleted_at TIMESTAMP NULL AFTER updated_at,
    MODIFY COLUMN is_deleted TIMESTAMP NULL;

-- Update existing records to have default values
UPDATE branches SET status = 'active' WHERE status IS NULL;
UPDATE branches SET is_deleted = NULL WHERE is_deleted IS NULL; 