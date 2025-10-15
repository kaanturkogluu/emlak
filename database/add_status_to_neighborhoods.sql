-- neighborhoods tablosuna status sütunu ekle
ALTER TABLE neighborhoods ADD COLUMN status ENUM('active', 'deleted') DEFAULT 'active' AFTER name;

-- Mevcut kayıtları active yap
UPDATE neighborhoods SET status = 'active' WHERE status IS NULL;
