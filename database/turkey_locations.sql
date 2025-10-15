-- Türkiye İller, İlçeler ve Beldeler Veritabanı Yapısı

-- İller tablosu (mevcut cities tablosunu güncelle)
ALTER TABLE cities ADD COLUMN IF NOT EXISTS plate_code VARCHAR(2);
ALTER TABLE cities ADD COLUMN IF NOT EXISTS region VARCHAR(50);
ALTER TABLE cities ADD COLUMN IF NOT EXISTS population INT;
ALTER TABLE cities ADD COLUMN IF NOT EXISTS area DECIMAL(10,2);

-- İlçeler tablosu (mevcut districts tablosunu güncelle)
ALTER TABLE districts ADD COLUMN IF NOT EXISTS population INT;
ALTER TABLE districts ADD COLUMN IF NOT EXISTS area DECIMAL(10,2);

-- Beldeler tablosu
CREATE TABLE IF NOT EXISTS neighborhoods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    district_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    population INT DEFAULT 0,
    area DECIMAL(10,2) DEFAULT 0,
    postal_code VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (district_id) REFERENCES districts(id) ON DELETE CASCADE,
    INDEX idx_district_id (district_id),
    INDEX idx_name (name)
);

-- Mahalleler tablosu (opsiyonel - daha detaylı için)
CREATE TABLE IF NOT EXISTS quarters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    neighborhood_id INT,
    district_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    population INT DEFAULT 0,
    postal_code VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (neighborhood_id) REFERENCES neighborhoods(id) ON DELETE CASCADE,
    FOREIGN KEY (district_id) REFERENCES districts(id) ON DELETE CASCADE,
    INDEX idx_neighborhood_id (neighborhood_id),
    INDEX idx_district_id (district_id),
    INDEX idx_name (name)
);
