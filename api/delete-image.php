<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Database.php';

header('Content-Type: application/json');

$adminUser = new AdminUser();

// Admin kontrolü
if (!$adminUser->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Yetkisiz erişim']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek metodu']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$imageId = (int)($input['image_id'] ?? 0);

if (!$imageId) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz resim ID']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Resim bilgilerini getir
    $stmt = $db->prepare("SELECT * FROM property_images WHERE id = ?");
    $stmt->execute([$imageId]);
    $image = $stmt->fetch();
    
    if (!$image) {
        echo json_encode(['success' => false, 'message' => 'Resim bulunamadı']);
        exit;
    }
    
    // Veritabanından sil
    $stmt = $db->prepare("DELETE FROM property_images WHERE id = ?");
    $stmt->execute([$imageId]);
    
    // Dosyayı sil (opsiyonel - dosya yoksa hata vermez)
    $imagePath = str_replace('http://localhost/emlak/', __DIR__ . '/../', $image['image_url']);
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
    
    echo json_encode(['success' => true, 'message' => 'Resim başarıyla silindi']);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Resim silinirken hata oluştu: ' . $e->getMessage()]);
}
?>
