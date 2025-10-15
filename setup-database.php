<?php
require_once 'config/config.php';
require_once 'classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    echo "<h2>Veritabanı Kurulumu</h2>";
    
    // Admin kullanıcıları tablosu oluştur
    $sql = "
    CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        full_name VARCHAR(100) NOT NULL,
        role ENUM('admin', 'moderator', 'editor') DEFAULT 'admin',
        status ENUM('active', 'inactive') DEFAULT 'active',
        last_login DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $db->exec($sql);
    echo "✅ Admin kullanıcıları tablosu oluşturuldu.<br>";
    
    // Varsayılan admin kullanıcısı ekle
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $moderatorPassword = password_hash('moderator123', PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("
        INSERT IGNORE INTO admin_users (username, password, email, full_name, role, status) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    // Admin kullanıcısı
    $stmt->execute([
        'admin',
        $adminPassword,
        'admin@er.com',
        'Sistem Yöneticisi',
        'admin',
        'active'
    ]);
    echo "✅ Admin kullanıcısı oluşturuldu (admin/admin123)<br>";
    
    // Moderator kullanıcısı
    $stmt->execute([
        'moderator',
        $moderatorPassword,
        'moderator@er.com',
        'Moderatör',
        'moderator',
        'active'
    ]);
    echo "✅ Moderator kullanıcısı oluşturuldu (moderator/moderator123)<br>";
    
    // Properties tablosu oluştur
    $sql = "
    CREATE TABLE IF NOT EXISTS properties (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        description TEXT,
        price DECIMAL(15,2) NOT NULL,
        property_type ENUM('daire', 'villa', 'arsa', 'isyeri', 'ofis') NOT NULL,
        transaction_type ENUM('satilik', 'kiralik', 'gunluk_kiralik') NOT NULL,
        city_id INT,
        district_id INT,
        address TEXT,
        area DECIMAL(10,2),
        room_count INT,
        living_room_count INT,
        bathroom_count INT,
        floor INT,
        building_age INT,
        heating_type VARCHAR(100),
        main_image VARCHAR(255),
        images TEXT,
        features TEXT,
        contact_name VARCHAR(100),
        contact_phone VARCHAR(20),
        contact_email VARCHAR(100),
        featured BOOLEAN DEFAULT FALSE,
        urgent BOOLEAN DEFAULT FALSE,
        status ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
        views INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $db->exec($sql);
    echo "✅ Properties tablosu oluşturuldu.<br>";
    
    // Cities tablosu oluştur
    $sql = "
    CREATE TABLE IF NOT EXISTS cities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(100) NOT NULL UNIQUE,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $db->exec($sql);
    echo "✅ Cities tablosu oluşturuldu.<br>";
    
    // Districts tablosu oluştur
    $sql = "
    CREATE TABLE IF NOT EXISTS districts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        city_id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(100) NOT NULL,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE,
        UNIQUE KEY unique_district (city_id, slug)
    )";
    
    $db->exec($sql);
    echo "✅ Districts tablosu oluşturuldu.<br>";
    
    // Users tablosu oluştur
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $db->exec($sql);
    echo "✅ Users tablosu oluşturuldu.<br>";
    
    // Örnek şehirler ekle
    $cities = [
        ['İstanbul', 'istanbul'],
        ['Ankara', 'ankara'],
        ['İzmir', 'izmir'],
        ['Bursa', 'bursa'],
        ['Antalya', 'antalya']
    ];
    
    $stmt = $db->prepare("INSERT IGNORE INTO cities (name, slug) VALUES (?, ?)");
    foreach ($cities as $city) {
        $stmt->execute($city);
    }
    echo "✅ Örnek şehirler eklendi.<br>";
    
    // Örnek ilçeler ekle
    $districts = [
        [1, 'Beşiktaş', 'besiktas'],
        [1, 'Kadıköy', 'kadikoy'],
        [1, 'Şişli', 'sisli'],
        [2, 'Çankaya', 'cankaya'],
        [2, 'Keçiören', 'kecioren'],
        [3, 'Konak', 'konak'],
        [3, 'Karşıyaka', 'karsiyaka']
    ];
    
    $stmt = $db->prepare("INSERT IGNORE INTO districts (city_id, name, slug) VALUES (?, ?, ?)");
    foreach ($districts as $district) {
        $stmt->execute($district);
    }
    echo "✅ Örnek ilçeler eklendi.<br>";
    
    // Slider tablosu oluştur
    $sql = "
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
    )";
    
    $db->exec($sql);
    echo "✅ Slider tablosu oluşturuldu.<br>";
    
    // Örnek slider verileri ekle
    $sliders = [
        ['Hayalinizdeki Evi Bulun', 'Binlerce konut, villa ve arsa arasından size en uygun olanı keşfedin', 'İlanları İncele', '/ilanlar', 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1920&h=1080&fit=crop', 1, 'active'],
        ['Lüks Villalar', 'Deniz manzaralı, özel havuzlu villalar şimdi sizleri bekliyor', 'Detaylı İncele', '/satilik-villa', 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1920&h=1080&fit=crop', 2, 'active'],
        ['Modern Yaşam Alanları', 'Şehrin kalbinde, konforlu ve modern daireler', 'Hemen Görüntüle', '/satilik-daire', 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1920&h=1080&fit=crop', 3, 'active'],
        ['Yatırım Fırsatları', 'Değer kazanacak arsalar ve ticari gayrimenkuller', 'Fırsatları Gör', '/satilik-arsa', 'https://images.unsplash.com/photo-1558036117-15d82a90b9b1?w=1920&h=1080&fit=crop', 4, 'active']
    ];
    
    $stmt = $db->prepare("INSERT IGNORE INTO sliders (title, subtitle, button_text, button_url, image, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($sliders as $slider) {
        $stmt->execute($slider);
    }
    echo "✅ Örnek slider verileri eklendi.<br>";
    
    // Properties tablosunu oluştur
    $propertiesSql = file_get_contents(__DIR__ . '/database/properties.sql');
    $statements = explode(';', $propertiesSql);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $db->exec($statement);
        }
    }
    echo "✅ Properties tablosu oluşturuldu ve örnek veriler eklendi.<br>";
    
    // Türkiye konum verilerini ekle
    echo "<h3>📍 Türkiye Konum Verileri Ekleniyor...</h3>";
    
    // Veritabanı yapısını güncelle
    $locationsSql = file_get_contents(__DIR__ . '/database/turkey_locations.sql');
    $statements = explode(';', $locationsSql);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $db->exec($statement);
            } catch (Exception $e) {
                // Hata mesajını görmezden gel (zaten mevcut olabilir)
            }
        }
    }
    echo "✅ Veritabanı yapısı güncellendi.<br>";
    
    // İlleri ekle
    $citiesSql = file_get_contents(__DIR__ . '/database/turkey_cities.sql');
    $statements = explode(';', $citiesSql);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $db->exec($statement);
            } catch (Exception $e) {
                // Hata mesajını görmezden gel
            }
        }
    }
    echo "✅ Türkiye iller listesi eklendi.<br>";
    
    // İlçeleri ekle
    $districtsSql = file_get_contents(__DIR__ . '/database/turkey_districts.sql');
    $statements = explode(';', $districtsSql);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $db->exec($statement);
            } catch (Exception $e) {
                // Hata mesajını görmezden gel
            }
        }
    }
    echo "✅ İlçeler listesi eklendi.<br>";
    
    // Beldeleri ekle
    $neighborhoodsSql = file_get_contents(__DIR__ . '/database/turkey_neighborhoods.sql');
    $statements = explode(';', $neighborhoodsSql);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $db->exec($statement);
            } catch (Exception $e) {
                // Hata mesajını görmezden gel
            }
        }
    }
    echo "✅ Beldeler listesi eklendi.<br>";
    
    echo "<br><h3>🎉 Veritabanı kurulumu tamamlandı!</h3>";
    echo "<p><strong>Admin Giriş Bilgileri:</strong></p>";
    echo "<ul>";
    echo "<li>Kullanıcı Adı: <strong>admin</strong> | Şifre: <strong>admin123</strong></li>";
    echo "<li>Kullanıcı Adı: <strong>moderator</strong> | Şifre: <strong>moderator123</strong></li>";
    echo "</ul>";
    echo "<p><a href='admin/login.php'>Admin Paneline Git</a></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Hata:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
