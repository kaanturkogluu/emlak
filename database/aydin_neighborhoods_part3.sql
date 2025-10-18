-- Aydın İli Mahalleleri - Part 3
-- İlçeler: KARPUZLU, KOÇARLI, KÖŞK, KUŞADASI

-- KARPUZLU İlçesi Mahalleleri (district_id: 773)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(773, 'Atatürk', 'active'),
(773, 'Cumhuriyet', 'active'),
(773, 'Işıklar', 'active'),
(773, 'Karpuzlu', 'active'),
(773, 'Kaşıkçı', 'active'),
(773, 'Kurtuluş', 'active'),
(773, 'Pınarlı', 'active'),
(773, 'Yeşilköy', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- KOÇARLI İlçesi Mahalleleri (district_id: 774)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(774, 'Atatürk', 'active'),
(774, 'Ballıca', 'active'),
(774, 'Bayındır', 'active'),
(774, 'Bozkurt', 'active'),
(774, 'Büyükköy', 'active'),
(774, 'Camikebir', 'active'),
(774, 'Cumhuriyet', 'active'),
(774, 'Çaybaşı', 'active'),
(774, 'Çobanlar', 'active'),
(774, 'Eskiçine', 'active'),
(774, 'Fevzipaşa', 'active'),
(774, 'Gazi', 'active'),
(774, 'Güre', 'active'),
(774, 'Hamidiye', 'active'),
(774, 'İsabeyli', 'active'),
(774, 'Kapıkaya', 'active'),
(774, 'Kazıklı', 'active'),
(774, 'Kurtuluş', 'active'),
(774, 'Kuzguncuk', 'active'),
(774, 'Pınarbaşı', 'active'),
(774, 'Salihler', 'active'),
(774, 'Sancaklı', 'active'),
(774, 'Süller', 'active'),
(774, 'Tepecik', 'active'),
(774, 'Yeşilyurt', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- KÖŞK İlçesi Mahalleleri (district_id: 775)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(775, 'Atatürk', 'active'),
(775, 'Avdan', 'active'),
(775, 'Cumhuriyet', 'active'),
(775, 'Çalış', 'active'),
(775, 'Çatalhöyük', 'active'),
(775, 'Değirmendere', 'active'),
(775, 'Gazi', 'active'),
(775, 'Güvendik', 'active'),
(775, 'Kavaklıdere', 'active'),
(775, 'Kurtuluş', 'active'),
(775, 'Ortaklar', 'active'),
(775, 'Yağcılı', 'active'),
(775, 'Yenice', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- KUŞADASI İlçesi Mahalleleri (district_id: 776)
INSERT INTO neighborhoods (district_id, name, status) VALUES
(776, 'Akıncılar', 'active'),
(776, 'Atatürk', 'active'),
(776, 'Camiatik', 'active'),
(776, 'Camikebir', 'active'),
(776, 'Çamlık', 'active'),
(776, 'Davutlar', 'active'),
(776, 'Güzelçamlı', 'active'),
(776, 'Hacıfeyzullah', 'active'),
(776, 'Kadinlar Denizi', 'active'),
(776, 'Soğucak', 'active'),
(776, 'Türkmen', 'active')
ON DUPLICATE KEY UPDATE name = VALUES(name);

