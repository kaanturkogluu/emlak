-- Ekip üyeleri tablosu
CREATE TABLE IF NOT EXISTS `about_us_team_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan ekip üyeleri
INSERT INTO `about_us_team_members` (`name`, `position`, `description`, `image_url`, `sort_order`, `is_active`) VALUES
('Ahmet Yılmaz', 'Genel Müdür', '15 yıllık emlak deneyimi ile sektörde öncü isimlerden biri. Müşteri memnuniyetini ön planda tutarak, şirketin stratejik yönetimini gerçekleştiriyor.', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face', 1, 1),

('Ayşe Demir', 'Satış Müdürü', 'Müşteri ilişkileri ve satış stratejileri konusunda uzman. Ekip yönetimi ve müşteri memnuniyeti alanlarında 10 yıllık deneyime sahip.', 'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=150&h=150&fit=crop&crop=face', 2, 1),

('Mehmet Kaya', 'Teknik Müdür', 'Teknoloji altyapısı ve dijital çözümler konusunda uzman. Sistem güvenliği ve performans optimizasyonu alanlarında çalışıyor.', 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face', 3, 1);
