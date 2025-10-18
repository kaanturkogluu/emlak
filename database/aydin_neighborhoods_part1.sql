-- Aydın İli Mahalleleri - Part 1
-- İlçeler: BOZDOĞAN, BUHARKENT, ÇİNE, DİDİM

-- BOZDOĞAN İlçesi Mahalleleri (district_id: 758)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(758, 'Adnan Menderes', 'active'),
(758, 'Akçaköy', 'active'),
(758, 'Aydoğdu', 'active'),
(758, 'Bayır', 'active'),
(758, 'Çakırbeyli', 'active'),
(758, 'Çamköy', 'active'),
(758, 'Dedeler', 'active'),
(758, 'Dereköy', 'active'),
(758, 'Efeler', 'active'),
(758, 'Gölcük', 'active'),
(758, 'Karacahisar', 'active'),
(758, 'Karacaköy', 'active'),
(758, 'Kavaklı', 'active'),
(758, 'Kızılcaköy', 'active'),
(758, 'Meşelik', 'active'),
(758, 'Sarıcaali', 'active'),
(758, 'Yazıkent', 'active'),
(758, 'Yeniköy', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- BUHARKENT İlçesi Mahalleleri (district_id: 766)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(766, 'Akçaova', 'active'),
(766, 'Aşağıkuyucak', 'active'),
(766, 'Bahçeköy', 'active'),
(766, 'Bozköy', 'active'),
(766, 'Cumhuriyet', 'active'),
(766, 'Danamandıra', 'active'),
(766, 'Dereköy', 'active'),
(766, 'Düzağaç', 'active'),
(766, 'Gedikler', 'active'),
(766, 'Gölcük', 'active'),
(766, 'Güllüce', 'active'),
(766, 'Kayadibi', 'active'),
(766, 'Kılıç', 'active'),
(766, 'Mehmetler', 'active'),
(766, 'Muratdağı', 'active'),
(766, 'Sazak', 'active'),
(766, 'Yakaköy', 'active'),
(766, 'Yeniköy', 'active'),
(766, 'Yukarıkuyucak', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- ÇİNE İlçesi Mahalleleri (district_id: 767)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(767, 'Aliağa', 'active'),
(767, 'Atatürk', 'active'),
(767, 'Aydınlar', 'active'),
(767, 'Bağarcık', 'active'),
(767, 'Camili', 'active'),
(767, 'Cumhuriyet', 'active'),
(767, 'Çavdar', 'active'),
(767, 'Çobanisa', 'active'),
(767, 'Değirmenli', 'active'),
(767, 'Gazi', 'active'),
(767, 'Gökçen', 'active'),
(767, 'Güre', 'active'),
(767, 'Hacıveliler', 'active'),
(767, 'İsmetpaşa', 'active'),
(767, 'Kalehan', 'active'),
(767, 'Kocagür', 'active'),
(767, 'Kuyucak', 'active'),
(767, 'Mersinbeleni', 'active'),
(767, 'Nalıncılar', 'active'),
(767, 'Ortaköy', 'active'),
(767, 'Paşa', 'active'),
(767, 'Pelitköy', 'active'),
(767, 'Sütlaç', 'active'),
(767, 'Tepecik', 'active'),
(767, 'Yaka', 'active'),
(767, 'Yeniköy', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- DİDİM İlçesi Mahalleleri (district_id: 768)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(768, 'Akbük', 'active'),
(768, 'Altınkum', 'active'),
(768, 'Avcılar', 'active'),
(768, 'Balat', 'active'),
(768, 'Bayındır', 'active'),
(768, 'Cumhuriyet', 'active'),
(768, 'Çamlık', 'active'),
(768, 'Efeler', 'active'),
(768, 'Ekinambarı', 'active'),
(768, 'Fevzipaşa', 'active'),
(768, 'Gümüşkent', 'active'),
(768, 'Hisar', 'active'),
(768, 'Mavisehir', 'active'),
(768, 'Sarıkemer', 'active'),
(768, 'Şahinler', 'active'),
(768, 'Tekmen', 'active'),
(768, 'Yenihisar', 'active'),
(768, 'Yeşilköy', 'active'),
(768, 'Yoran', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

