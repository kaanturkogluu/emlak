<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    $districtId = $_GET['district_id'] ?? null;
    
    if (!$districtId) {
        http_response_code(400);
        echo json_encode(['error' => 'district_id parametresi gerekli']);
        exit;
    }
    
    $stmt = $db->prepare("
        SELECT n.*, d.name as district_name, c.name as city_name 
        FROM neighborhoods n 
        JOIN districts d ON n.district_id = d.id 
        JOIN cities c ON d.city_id = c.id 
        WHERE n.district_id = ? 
        ORDER BY n.name ASC
    ");
    
    $stmt->execute([$districtId]);
    $neighborhoods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($neighborhoods);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Sunucu hatası: ' . $e->getMessage()]);
}
?>
