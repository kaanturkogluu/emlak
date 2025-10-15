<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    $cityId = $_GET['city_id'] ?? null;
    
    if (!$cityId) {
        http_response_code(400);
        echo json_encode(['error' => 'city_id parametresi gerekli']);
        exit;
    }
    
    $stmt = $db->prepare("
        SELECT d.*, c.name as city_name 
        FROM districts d 
        JOIN cities c ON d.city_id = c.id 
        WHERE d.city_id = ? 
        ORDER BY d.name ASC
    ");
    
    $stmt->execute([$cityId]);
    $districts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Eğer ilçe yoksa, şehir adını al
    if (empty($districts)) {
        $stmt = $db->prepare("SELECT name FROM cities WHERE id = ?");
        $stmt->execute([$cityId]);
        $city = $stmt->fetch();
        
        if ($city) {
            // Bu şehir için ilçe verisi yok, merkez ilçe olarak şehir adını ekle
            $districts = [[
                'id' => $cityId,
                'city_id' => $cityId,
                'name' => $city['name'] . ' Merkez',
                'slug' => strtolower($city['name']) . '-merkez',
                'status' => 'active',
                'population' => 0,
                'area' => 0,
                'city_name' => $city['name']
            ]];
        }
    }
    
    echo json_encode($districts);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Sunucu hatası: ' . $e->getMessage()]);
}
?>
