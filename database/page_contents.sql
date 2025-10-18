-- Sayfa içerikleri tablosu
CREATE TABLE IF NOT EXISTS `page_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_type` varchar(50) NOT NULL,
  `section_type` varchar(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_section` (`page_type`, `section_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan sayfa içerikleri
INSERT INTO `page_contents` (`page_type`, `section_type`, `title`, `subtitle`, `content`, `image_url`) VALUES
('hakkimizda', 'hero', 'Hakkımızda', 'Türkiye\'nin en güvenilir emlak platformu olarak, hayalinizdeki evi bulmanız için yanınızdayız.', NULL, NULL),

('hakkimizda', 'main_content', 'Kimiz Biz?', NULL, 'er.com olarak, 2020 yılından bu yana emlak sektöründe faaliyet gösteren, teknoloji odaklı bir platformuz. Müşteri memnuniyetini ön planda tutarak, güvenilir ve şeffaf bir emlak deneyimi sunmayı hedefliyoruz.\n\nBinlerce başarılı emlak işlemi gerçekleştirdik ve müşterilerimizin hayallerini gerçeğe dönüştürdük. Uzman ekibimiz ve gelişmiş teknoloji altyapımızla, emlak alım-satım sürecinizi kolaylaştırıyoruz.', '/emlak/assets/images/about-us.jpg'),

('hakkimizda', 'mission', 'Misyonumuz', NULL, 'Emlak sektöründe güven, şeffaflık ve müşteri memnuniyetini ön planda tutarak, teknoloji ile geleneksel emlak hizmetlerini harmanlayan, yenilikçi çözümler sunmak.', NULL),

('hakkimizda', 'vision', 'Vizyonumuz', NULL, 'Türkiye\'nin en büyük ve en güvenilir emlak platformu olmak, müşterilerimizin hayallerini gerçeğe dönüştüren bir marka haline gelmek.', NULL),

('hakkimizda', 'values', 'Değerlerimiz', NULL, 'Güvenilirlik: Tüm işlemlerimizde şeffaflık ve güvenilirlik ilkesini benimseriz.\n\nMüşteri Odaklılık: Müşteri memnuniyeti bizim için en önemli önceliktir.\n\nYenilikçilik: Sürekli gelişim ve yenilik anlayışıyla hizmet veriyoruz.\n\nEtik Değerler: Tüm işlemlerimizde etik değerlere uygun davranırız.', NULL),

('hakkimizda', 'team', 'Ekibimiz', 'Deneyimli ve uzman ekibimizle, emlak sektöründe fark yaratıyoruz.', 'Ahmet Yılmaz - Genel Müdür: 15 yıllık emlak deneyimi ile sektörde öncü isimlerden biri.\n\nAyşe Demir - Satış Müdürü: Müşteri ilişkileri ve satış stratejileri konusunda uzman.\n\nMehmet Kaya - Teknik Müdür: Teknoloji altyapısı ve dijital çözümler konusunda uzman.', NULL)
ON DUPLICATE KEY UPDATE 
`title` = VALUES(`title`), 
`subtitle` = VALUES(`subtitle`), 
`content` = VALUES(`content`), 
`image_url` = VALUES(`image_url`);
