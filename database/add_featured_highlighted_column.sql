-- Properties tablosuna featured_highlighted alanı ekle
ALTER TABLE properties ADD COLUMN featured_highlighted TINYINT(1) DEFAULT 0 COMMENT 'Öne çıkan ilanlar için işaretleme';

-- Index ekle
ALTER TABLE properties ADD INDEX idx_featured_highlighted (featured_highlighted);
