-- Site ayarları tablosu
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'boolean', 'json') DEFAULT 'text',
    category VARCHAR(50) DEFAULT 'general',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Varsayılan ayarları ekle
INSERT INTO site_settings (setting_key, setting_value, setting_type, category, description) VALUES
-- Genel Ayarlar
('site_name', 'Emlak Sitesi', 'text', 'general', 'Site adı'),
('site_description', 'Türkiye\'nin en güvenilir emlak platformu', 'text', 'general', 'Site açıklaması'),
('site_keywords', 'emlak, satılık, kiralık, daire, villa, arsa', 'text', 'general', 'Site anahtar kelimeleri'),
('items_per_page', '12', 'number', 'general', 'Sayfa başına ilan sayısı'),

-- İletişim Bilgileri
('contact_phone', '+90 (212) 555 00 00', 'text', 'contact', 'İletişim telefonu'),
('contact_email', 'info@emlaksitesi.com', 'text', 'contact', 'İletişim e-postası'),
('contact_address', 'Merkez Mahallesi, Emlak Caddesi No:123, Beşiktaş/İstanbul', 'text', 'contact', 'İletişim adresi'),
('working_hours', 'Pazartesi - Cuma: 09:00 - 18:00, Cumartesi: 09:00 - 16:00', 'text', 'contact', 'Çalışma saatleri'),

-- Sosyal Medya
('facebook_url', '', 'text', 'social', 'Facebook sayfası URL'),
('twitter_url', '', 'text', 'social', 'Twitter sayfası URL'),
('instagram_url', '', 'text', 'social', 'Instagram sayfası URL'),
('linkedin_url', '', 'text', 'social', 'LinkedIn sayfası URL'),
('youtube_url', '', 'text', 'social', 'YouTube kanalı URL'),

-- SEO
('google_analytics', '', 'text', 'seo', 'Google Analytics kodu'),
('google_maps_api', '', 'text', 'seo', 'Google Maps API anahtarı'),

-- Sistem
('maintenance_mode', '0', 'boolean', 'system', 'Bakım modu'),
('enable_comments', '1', 'boolean', 'system', 'Yorum sistemi'),
('enable_newsletter', '0', 'boolean', 'system', 'Bülten sistemi'),
('two_factor_auth', '0', 'boolean', 'system', 'İki faktörlü kimlik doğrulama'),
('backup_frequency', 'weekly', 'text', 'system', 'Yedekleme sıklığı'),
('admin_email', 'admin@emlaksitesi.com', 'text', 'system', 'Admin e-posta adresi');
