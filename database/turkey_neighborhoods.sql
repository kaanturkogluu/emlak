-- Türkiye Beldeler Listesi (Büyük ilçeler için)

-- İstanbul Kadıköy Beldeleri
INSERT INTO neighborhoods (district_id, name, population, area, postal_code) VALUES
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Kadıköy'), 'Acıbadem', 25000, 2.5, '34710'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Kadıköy'), 'Bostancı', 45000, 3.2, '34744'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Kadıköy'), 'Caddebostan', 35000, 2.8, '34728'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Kadıköy'), 'Erenköy', 28000, 2.1, '34738'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Kadıköy'), 'Fenerbahçe', 22000, 1.8, '34726'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Kadıköy'), 'Göztepe', 32000, 2.3, '34730'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Kadıköy'), 'Koşuyolu', 18000, 1.5, '34718'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Kadıköy'), 'Moda', 15000, 1.2, '34710'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Kadıköy'), 'Suadiye', 25000, 2.0, '34740');

-- İstanbul Beşiktaş Beldeleri
INSERT INTO neighborhoods (district_id, name, population, area, postal_code) VALUES
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Beşiktaş'), 'Arnavutköy', 12000, 1.8, '34345'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Beşiktaş'), 'Bebek', 8000, 1.2, '34342'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Beşiktaş'), 'Etiler', 15000, 1.5, '34337'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Beşiktaş'), 'Levent', 20000, 2.1, '34330'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Beşiktaş'), 'Ortaköy', 10000, 1.0, '34347'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Beşiktaş'), 'Ulus', 12000, 1.3, '34340');

-- İstanbul Şişli Beldeleri
INSERT INTO neighborhoods (district_id, name, population, area, postal_code) VALUES
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Şişli'), 'Bomonti', 18000, 1.8, '34381'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Şişli'), 'Cumhuriyet', 15000, 1.5, '34380'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Şişli'), 'Esentepe', 12000, 1.2, '34394'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Şişli'), 'Harbiye', 10000, 1.0, '34367'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Şişli'), 'Mecidiyeköy', 25000, 2.5, '34387'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İstanbul' AND d.name = 'Şişli'), 'Nişantaşı', 8000, 0.8, '34365');

-- Ankara Çankaya Beldeleri
INSERT INTO neighborhoods (district_id, name, population, area, postal_code) VALUES
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Ankara' AND d.name = 'Çankaya'), 'Çukurambar', 35000, 3.5, '06520'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Ankara' AND d.name = 'Çankaya'), 'Kızılay', 25000, 2.5, '06420'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Ankara' AND d.name = 'Çankaya'), 'Kocatepe', 20000, 2.0, '06420'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Ankara' AND d.name = 'Çankaya'), 'Kızılcahamam', 15000, 1.5, '06420'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Ankara' AND d.name = 'Çankaya'), 'Tunalı', 18000, 1.8, '06420'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Ankara' AND d.name = 'Çankaya'), 'Ümitköy', 30000, 3.0, '06810');

-- Ankara Keçiören Beldeleri
INSERT INTO neighborhoods (district_id, name, population, area, postal_code) VALUES
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Ankara' AND d.name = 'Keçiören'), 'Etlik', 40000, 4.0, '06010'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Ankara' AND d.name = 'Keçiören'), 'Ovacık', 25000, 2.5, '06280'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Ankara' AND d.name = 'Keçiören'), 'Şenlik', 30000, 3.0, '06280'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Ankara' AND d.name = 'Keçiören'), 'Yenidoğan', 35000, 3.5, '06280');

-- İzmir Konak Beldeleri
INSERT INTO neighborhoods (district_id, name, population, area, postal_code) VALUES
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İzmir' AND d.name = 'Konak'), 'Alsancak', 20000, 2.0, '35220'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İzmir' AND d.name = 'Konak'), 'Basmane', 15000, 1.5, '35240'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İzmir' AND d.name = 'Konak'), 'Çankaya', 12000, 1.2, '35220'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İzmir' AND d.name = 'Konak'), 'Güzelyalı', 18000, 1.8, '35290'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İzmir' AND d.name = 'Konak'), 'Kemeraltı', 10000, 1.0, '35250'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İzmir' AND d.name = 'Konak'), 'Pasaport', 8000, 0.8, '35220');

-- İzmir Karşıyaka Beldeleri
INSERT INTO neighborhoods (district_id, name, population, area, postal_code) VALUES
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İzmir' AND d.name = 'Karşıyaka'), 'Alaybey', 15000, 1.5, '35580'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İzmir' AND d.name = 'Karşıyaka'), 'Bostanlı', 25000, 2.5, '35590'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İzmir' AND d.name = 'Karşıyaka'), 'Çiğli', 20000, 2.0, '35620'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İzmir' AND d.name = 'Karşıyaka'), 'Mavişehir', 30000, 3.0, '35590'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'İzmir' AND d.name = 'Karşıyaka'), 'Mersinli', 18000, 1.8, '35580');

-- Bursa Osmangazi Beldeleri
INSERT INTO neighborhoods (district_id, name, population, area, postal_code) VALUES
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Bursa' AND d.name = 'Osmangazi'), 'Çekirge', 25000, 2.5, '16020'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Bursa' AND d.name = 'Osmangazi'), 'Emir Sultan', 20000, 2.0, '16330'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Bursa' AND d.name = 'Osmangazi'), 'Hamitler', 30000, 3.0, '16120'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Bursa' AND d.name = 'Osmangazi'), 'Soğanlı', 35000, 3.5, '16120'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Bursa' AND d.name = 'Osmangazi'), 'Yıldırım', 40000, 4.0, '16330');

-- Antalya Muratpaşa Beldeleri
INSERT INTO neighborhoods (district_id, name, population, area, postal_code) VALUES
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Antalya' AND d.name = 'Muratpaşa'), 'Altındağ', 30000, 3.0, '07100'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Antalya' AND d.name = 'Muratpaşa'), 'Çağlayan', 25000, 2.5, '07200'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Antalya' AND d.name = 'Muratpaşa'), 'Fener', 20000, 2.0, '07100'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Antalya' AND d.name = 'Muratpaşa'), 'Lara', 40000, 4.0, '07110'),
((SELECT d.id FROM districts d JOIN cities c ON d.city_id = c.id WHERE c.name = 'Antalya' AND d.name = 'Muratpaşa'), 'Meltem', 35000, 3.5, '07100');
