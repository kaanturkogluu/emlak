-- Aydın İli Mahalleleri - Part 4
-- İlçeler: KUYUCAK, NAZİLLİ, SÖKE, SULTANHİSAR, YENİPAZAR

-- KUYUCAK İlçesi Mahalleleri (district_id: 777)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(777, 'Akçeşme', 'active'),
(777, 'Akovalı', 'active'),
(777, 'Atatürk', 'active'),
(777, 'Bayındır', 'active'),
(777, 'Bozyurt', 'active'),
(777, 'Büyükköy', 'active'),
(777, 'Cumhuriyet', 'active'),
(777, 'Çatak', 'active'),
(777, 'Danişment', 'active'),
(777, 'Esentepe', 'active'),
(777, 'İmamlar', 'active'),
(777, 'Kafaca', 'active'),
(777, 'Kuyucak', 'active'),
(777, 'Sarıyahşi', 'active'),
(777, 'Tepecik', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- NAZİLLİ İlçesi Mahalleleri (district_id: 778)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(778, 'Acarlar', 'active'),
(778, 'Araphisar', 'active'),
(778, 'Ata', 'active'),
(778, 'Bahçelievler', 'active'),
(778, 'Bıçakçı', 'active'),
(778, 'Boğaziçi', 'active'),
(778, 'Cennetpınar', 'active'),
(778, 'Cumhuriyet', 'active'),
(778, 'Çağlayan', 'active'),
(778, 'Çalışkan', 'active'),
(778, 'Çiftlik', 'active'),
(778, 'Dedeköy', 'active'),
(778, 'Ege', 'active'),
(778, 'Fesleğen', 'active'),
(778, 'Gazi', 'active'),
(778, 'Gödrenli', 'active'),
(778, 'Hasanköy', 'active'),
(778, 'Kurtuluş', 'active'),
(778, 'Levent', 'active'),
(778, 'Meşelik', 'active'),
(778, 'Mutlukent', 'active'),
(778, 'Pınarcık', 'active'),
(778, 'Pirlibey', 'active'),
(778, 'Salkım', 'active'),
(778, 'Sarıcaova', 'active'),
(778, 'Sultaniye', 'active'),
(778, 'Şehit Şahin', 'active'),
(778, 'Tepeköy', 'active'),
(778, 'Turan', 'active'),
(778, 'Türkmen', 'active'),
(778, 'Yazıkent', 'active'),
(778, 'Yeşilköy', 'active'),
(778, 'Zafer', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- SÖKE İlçesi Mahalleleri (district_id: 779)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(779, 'Atburgazı', 'active'),
(779, 'Atatürk', 'active'),
(779, 'Balıklıova', 'active'),
(779, 'Bağarası', 'active'),
(779, 'Bayraktepe', 'active'),
(779, 'Cumhuriyet', 'active'),
(779, 'Çakmak', 'active'),
(779, 'Çaybaşı', 'active'),
(779, 'Doğanbey', 'active'),
(779, 'Gazi', 'active'),
(779, 'Güllübahçe', 'active'),
(779, 'Gümüldür', 'active'),
(779, 'İsabeyli', 'active'),
(779, 'Kazıklı', 'active'),
(779, 'Kurtuluş', 'active'),
(779, 'Sazlı', 'active'),
(779, 'Sökköy', 'active'),
(779, 'Tuzburgazı', 'active'),
(779, 'Yeniköy', 'active'),
(779, 'Yeniyurt', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- SULTANHİSAR İlçesi Mahalleleri (district_id: 780)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(780, 'Akçaavlu', 'active'),
(780, 'Atatürk', 'active'),
(780, 'Atça', 'active'),
(780, 'Beyobası', 'active'),
(780, 'Cumhuriyet', 'active'),
(780, 'Esenköy', 'active'),
(780, 'Esenpınar', 'active'),
(780, 'Fevzipaşa', 'active'),
(780, 'Güzelhisar', 'active'),
(780, 'Hıdırlık', 'active'),
(780, 'Karapınar', 'active'),
(780, 'Kavaklı', 'active'),
(780, 'Kızılyer', 'active'),
(780, 'Kurtuluş', 'active'),
(780, 'Sarıkemer', 'active'),
(780, 'Sultanhisar', 'active'),
(780, 'Şahin', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- YENİPAZAR İlçesi Mahalleleri (district_id: 781)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(781, 'Akıncılar', 'active'),
(781, 'Akkent', 'active'),
(781, 'Bekdemir', 'active'),
(781, 'Büyükçiftlik', 'active'),
(781, 'Cumhuriyet', 'active'),
(781, 'Çamlı', 'active'),
(781, 'Dereçiftlik', 'active'),
(781, 'Eski Yenipazar', 'active'),
(781, 'Fevzi Çakmak', 'active'),
(781, 'Gündoğan', 'active'),
(781, 'Işıklar', 'active'),
(781, 'Pınarkaya', 'active'),
(781, 'Söke', 'active'),
(781, 'Tekeler', 'active'),
(781, 'Yeşilköy', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

