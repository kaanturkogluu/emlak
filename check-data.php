<?php
require_once 'config/config.php';
require_once 'classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    echo "<h2>📊 Veritabanı Veri Kontrolü</h2>";
    
    // İller sayısı
    $stmt = $db->query("SELECT COUNT(*) as count FROM cities");
    $citiesCount = $stmt->fetch()['count'];
    echo "<p>✅ <strong>İller:</strong> {$citiesCount} adet</p>";
    
    // İlçeler sayısı
    $stmt = $db->query("SELECT COUNT(*) as count FROM districts");
    $districtsCount = $stmt->fetch()['count'];
    echo "<p>✅ <strong>İlçeler:</strong> {$districtsCount} adet</p>";
    
    // Beldeler sayısı
    $stmt = $db->query("SELECT COUNT(*) as count FROM neighborhoods");
    $neighborhoodsCount = $stmt->fetch()['count'];
    echo "<p>✅ <strong>Beldeler:</strong> {$neighborhoodsCount} adet</p>";
    
    // Properties sayısı
    $stmt = $db->query("SELECT COUNT(*) as count FROM properties");
    $propertiesCount = $stmt->fetch()['count'];
    echo "<p>✅ <strong>İlanlar:</strong> {$propertiesCount} adet</p>";
    
    // Sliders sayısı
    $stmt = $db->query("SELECT COUNT(*) as count FROM sliders");
    $slidersCount = $stmt->fetch()['count'];
    echo "<p>✅ <strong>Sliderlar:</strong> {$slidersCount} adet</p>";
    
    echo "<hr>";
    
    // Örnek iller
    echo "<h3>🏙️ Örnek İller:</h3>";
    $stmt = $db->query("SELECT name, plate_code, region, population FROM cities ORDER BY population DESC LIMIT 10");
    $cities = $stmt->fetchAll();
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>İl</th><th>Plaka</th><th>Bölge</th><th>Nüfus</th></tr>";
    foreach ($cities as $city) {
        echo "<tr>";
        echo "<td>" . $city['name'] . "</td>";
        echo "<td>" . $city['plate_code'] . "</td>";
        echo "<td>" . $city['region'] . "</td>";
        echo "<td>" . number_format($city['population']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    
    // Örnek ilçeler
    echo "<h3>🏘️ Örnek İlçeler (İstanbul):</h3>";
    $stmt = $db->query("
        SELECT d.name as district_name, d.population, c.name as city_name 
        FROM districts d 
        JOIN cities c ON d.city_id = c.id 
        WHERE c.name = 'İstanbul' 
        ORDER BY d.population DESC 
        LIMIT 10
    ");
    $districts = $stmt->fetchAll();
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>İlçe</th><th>İl</th><th>Nüfus</th></tr>";
    foreach ($districts as $district) {
        echo "<tr>";
        echo "<td>" . $district['district_name'] . "</td>";
        echo "<td>" . $district['city_name'] . "</td>";
        echo "<td>" . number_format($district['population']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    
    // Örnek beldeler
    echo "<h3>🏠 Örnek Beldeler (Kadıköy):</h3>";
    $stmt = $db->query("
        SELECT n.name as neighborhood_name, n.population, n.postal_code, d.name as district_name 
        FROM neighborhoods n 
        JOIN districts d ON n.district_id = d.id 
        WHERE d.name = 'Kadıköy' 
        ORDER BY n.population DESC 
        LIMIT 10
    ");
    $neighborhoods = $stmt->fetchAll();
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>Belde</th><th>İlçe</th><th>Nüfus</th><th>Posta Kodu</th></tr>";
    foreach ($neighborhoods as $neighborhood) {
        echo "<tr>";
        echo "<td>" . $neighborhood['neighborhood_name'] . "</td>";
        echo "<td>" . $neighborhood['district_name'] . "</td>";
        echo "<td>" . number_format($neighborhood['population']) . "</td>";
        echo "<td>" . $neighborhood['postal_code'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
?>
