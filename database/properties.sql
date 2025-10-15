-- Properties tablosu oluşturma
CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    property_type ENUM('daire', 'villa', 'arsa', 'isyeri', 'ofis') NOT NULL,
    transaction_type ENUM('satilik', 'kiralik', 'gunluk_kiralik') NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    room_count INT,
    living_room_count INT,
    bathroom_count INT,
    floor INT,
    building_age INT,
    heating_type VARCHAR(100),
    city_id INT,
    district_id INT,
    address TEXT,
    area DECIMAL(10,2),
    main_image VARCHAR(255),
    images TEXT,
    features TEXT,
    contact_name VARCHAR(100),
    contact_phone VARCHAR(20),
    contact_email VARCHAR(100),
    featured TINYINT(1) DEFAULT 0,
    urgent TINYINT(1) DEFAULT 0,
    status ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
    views INT DEFAULT 0,
    slug VARCHAR(255) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (city_id) REFERENCES cities(id),
    FOREIGN KEY (district_id) REFERENCES districts(id)
);

-- Örnek property verileri
INSERT IGNORE INTO properties (title, description, property_type, transaction_type, price, room_count, bathroom_count, area, city_id, district_id, address, features, images, status, slug) VALUES
('Modern 3+1 Daire', 'Şehrin merkezinde, modern yaşam alanları ile donatılmış 3+1 daire. Metroya 5 dakika yürüme mesafesi.', 'daire', 'satilik', 2500000.00, 3, 2, 120.00, (SELECT id FROM cities WHERE name = 'İstanbul'), (SELECT id FROM districts WHERE name = 'Kadıköy'), 'Moda Mahallesi, Kadıköy/İstanbul', '["Asansör", "Güvenlik", "Otopark", "Balkon", "Eşyalı"]', '["https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=600&fit=crop", "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&h=600&fit=crop"]', 'active', 'modern-3-1-daire-kadikoy'),
('Lüks Villa', 'Deniz manzaralı, bahçeli lüks villa. Özel havuz ve geniş bahçe ile.', 'villa', 'satilik', 8500000.00, 5, 4, 350.00, (SELECT id FROM cities WHERE name = 'İstanbul'), (SELECT id FROM districts WHERE name = 'Beykoz'), 'Çubuklu Mahallesi, Beykoz/İstanbul', '["Havuz", "Bahçe", "Güvenlik", "Otopark", "Deniz Manzarası"]', '["https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&h=600&fit=crop", "https://images.unsplash.com/photo-1600607687644-c7171b42498b?w=800&h=600&fit=crop"]', 'active', 'luks-villa-beykoz'),
('Ticari İşyeri', 'Ana cadde üzerinde, yüksek geçişli ticari işyeri. Mağaza veya ofis olarak kullanılabilir.', 'isyeri', 'kiralik', 15000.00, 0, 1, 80.00, (SELECT id FROM cities WHERE name = 'İstanbul'), (SELECT id FROM districts WHERE name = 'Beşiktaş'), 'Ortaköy Mahallesi, Beşiktaş/İstanbul', '["Ana Cadde", "Yüksek Tavan", "Klima", "Güvenlik"]', '["https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&h=600&fit=crop"]', 'active', 'ticari-isyeri-besiktas'),
('2+1 Kiralık Daire', 'Merkezi konumda, ulaşım imkanlarına yakın 2+1 kiralık daire.', 'daire', 'kiralik', 8000.00, 2, 1, 85.00, (SELECT id FROM cities WHERE name = 'Ankara'), (SELECT id FROM districts WHERE name = 'Çankaya'), 'Kızılay Mahallesi, Çankaya/Ankara', '["Merkezi Konum", "Ulaşım", "Market Yakın"]', '["https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&h=600&fit=crop"]', 'active', '2-1-kiralik-daire-cankaya'),
('Bahçeli Ev', 'Geniş bahçeli, 4+1 bahçeli ev. Çocuklu aileler için ideal.', 'daire', 'satilik', 1800000.00, 4, 2, 200.00, (SELECT id FROM cities WHERE name = 'İzmir'), (SELECT id FROM districts WHERE name = 'Konak'), 'Alsancak Mahallesi, Konak/İzmir', '["Bahçe", "Çocuk Parkı", "Otopark", "Güvenlik"]', '["https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=800&h=600&fit=crop"]', 'active', 'bahceli-ev-konak'),
('Ofis Dairesi', 'Modern ofis binasında, 2+1 ofis dairesi. İş merkezi konumunda.', 'ofis', 'kiralik', 12000.00, 2, 1, 75.00, (SELECT id FROM cities WHERE name = 'İstanbul'), (SELECT id FROM districts WHERE name = 'Şişli'), 'Mecidiyeköy Mahallesi, Şişli/İstanbul', '["İş Merkezi", "Metro Yakın", "Klima", "Güvenlik"]', '["https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800&h=600&fit=crop"]', 'active', 'ofis-dairesi-sisli'),
('Arsa', 'İmar planı uygun, yatırım için ideal arsa.', 'arsa', 'satilik', 450000.00, 0, 0, 500.00, (SELECT id FROM cities WHERE name = 'Bursa'), (SELECT id FROM districts WHERE name = 'Nilüfer'), 'Görükle Mahallesi, Nilüfer/Bursa', '["İmar Uygun", "Yatırım", "Ulaşım"]', '["https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop"]', 'active', 'arsa-nilufer');