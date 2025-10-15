-- Slider tablosu
CREATE TABLE IF NOT EXISTS sliders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(500),
    button_text VARCHAR(100),
    button_url VARCHAR(255),
    image VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Örnek slider verileri
INSERT INTO sliders (title, subtitle, button_text, button_url, image, sort_order, status) VALUES
('Hayalinizdeki Evi Bulun', 'Binlerce konut, villa ve arsa arasından size en uygun olanı keşfedin', 'İlanları İncele', '/ilanlar', 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1920&h=1080&fit=crop', 1, 'active'),
('Lüks Villalar', 'Deniz manzaralı, özel havuzlu villalar şimdi sizleri bekliyor', 'Detaylı İncele', '/satilik-villa', 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1920&h=1080&fit=crop', 2, 'active'),
('Modern Yaşam Alanları', 'Şehrin kalbinde, konforlu ve modern daireler', 'Hemen Görüntüle', '/satilik-daire', 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1920&h=1080&fit=crop', 3, 'active'),
('Yatırım Fırsatları', 'Değer kazanacak arsalar ve ticari gayrimenkuller', 'Fırsatları Gör', '/satilik-arsa', 'https://images.unsplash.com/photo-1558036117-15d82a90b9b1?w=1920&h=1080&fit=crop', 4, 'active');
