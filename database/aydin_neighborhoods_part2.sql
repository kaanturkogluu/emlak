-- Aydın İli Mahalleleri - Part 2
-- İlçeler: EFELER, GERMENCİK, İNCİRLİOVA, KARACASU

-- EFELER İlçesi Mahalleleri (district_id: 769)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(769, 'Adnan Menderes', 'active'),
(769, 'Ata', 'active'),
(769, 'Cumhuriyet', 'active'),
(769, 'Danişment', 'active'),
(769, 'Devlet Bahçeli', 'active'),
(769, 'Esentepe', 'active'),
(769, 'Girne', 'active'),
(769, 'Hasanefendi', 'active'),
(769, 'Hürriyet', 'active'),
(769, 'İstiklal', 'active'),
(769, 'Kemer', 'active'),
(769, 'Kurtuluş', 'active'),
(769, 'Meşrutiyet', 'active'),
(769, 'Orta', 'active'),
(769, 'Paşa', 'active'),
(769, 'Ramazan', 'active'),
(769, 'Tepecik', 'active'),
(769, 'Umurlu', 'active'),
(769, 'Zafer', 'active'),
(769, 'Zeybek', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- GERMENCİK İlçesi Mahalleleri (district_id: 770)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(770, 'Akçaköy', 'active'),
(770, 'Alangüllü', 'active'),
(770, 'Araplar', 'active'),
(770, 'Arvalya', 'active'),
(770, 'Atça', 'active'),
(770, 'Bağarası', 'active'),
(770, 'Batıkent', 'active'),
(770, 'Beyazıtlı', 'active'),
(770, 'Cumhuriyet', 'active'),
(770, 'Çakırbeyli', 'active'),
(770, 'Dalama', 'active'),
(770, 'Doğancık', 'active'),
(770, 'Feslek', 'active'),
(770, 'Fevzipaşa', 'active'),
(770, 'Gölcük', 'active'),
(770, 'Güvendik', 'active'),
(770, 'Işıklar', 'active'),
(770, 'Kalınharman', 'active'),
(770, 'Kızılcagedik', 'active'),
(770, 'Kurtuluş', 'active'),
(770, 'Nargedik', 'active'),
(770, 'Ödemiş', 'active'),
(770, 'Sarıkemer', 'active'),
(770, 'Sazlı', 'active'),
(770, 'Umurlu', 'active'),
(770, 'Yeşilyurt', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- İNCİRLİOVA İlçesi Mahalleleri (district_id: 771)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(771, 'Akçaköy', 'active'),
(771, 'Cumhuriyet', 'active'),
(771, 'Çakırbeyli', 'active'),
(771, 'Çamtepe', 'active'),
(771, 'Dalama', 'active'),
(771, 'Esatpaşa', 'active'),
(771, 'Hacıahmetler', 'active'),
(771, 'Hacımemiş', 'active'),
(771, 'İnkur', 'active'),
(771, 'Kuşçular', 'active'),
(771, 'Ovakent', 'active'),
(771, 'Paşa', 'active'),
(771, 'Yamalak', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- KARACASU İlçesi Mahalleleri (district_id: 772)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(772, 'Ağaçhisar', 'active'),
(772, 'Akçaova', 'active'),
(772, 'Akıncılar', 'active'),
(772, 'Bölme', 'active'),
(772, 'Bucakköy', 'active'),
(772, 'Cevizli', 'active'),
(772, 'Cumhuriyet', 'active'),
(772, 'Danişment', 'active'),
(772, 'Dedebağı', 'active'),
(772, 'Derbent', 'active'),
(772, 'Esentepe', 'active'),
(772, 'Eskihisar', 'active'),
(772, 'Fevzipaşa', 'active'),
(772, 'Geyre', 'active'),
(772, 'Gökçeköy', 'active'),
(772, 'Göveçlik', 'active'),
(772, 'Hacımusalar', 'active'),
(772, 'Işıklar', 'active'),
(772, 'Karacasu', 'active'),
(772, 'Salavatlı', 'active'),
(772, 'Tekçam', 'active'),
(772, 'Yenicekent', 'active'),
(772, 'Yenipınar', 'active'),
(772, 'Yeşilyurt', 'active'),
(772, 'Yazır', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

