<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Helper.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Property.php';
require_once __DIR__ . '/../classes/Database.php';

// Admin giriş kontrolü
$adminUser = new AdminUser();
if (!$adminUser->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$helper = Helper::getInstance();
$property = new Property();

// JSON response
header('Content-Type: application/json');

// POST kontrolü
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Parametreleri al
$propertyId = (int)($_POST['property_id'] ?? 0);
$imageIndex = (int)($_POST['image_index'] ?? -1);

if (!$propertyId || $imageIndex < 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

try {
    // Mevcut ilan bilgilerini getir
    $currentProperty = $property->getById($propertyId);
    if (!$currentProperty) {
        echo json_encode(['success' => false, 'message' => 'Property not found']);
        exit;
    }
    
    // Mevcut resimleri getir
    $existingImages = [];
    if (!empty($currentProperty['images'])) {
        $existingImages = json_decode($currentProperty['images'], true) ?: [];
    }
    
    // Index kontrolü
    if ($imageIndex >= count($existingImages)) {
        echo json_encode(['success' => false, 'message' => 'Invalid image index']);
        exit;
    }
    
    // Resmi diziden kaldır
    unset($existingImages[$imageIndex]);
    $existingImages = array_values($existingImages); // Index'leri yeniden düzenle
    
    // Ana resim silinmişse, yeni ana resim belirle
    $newMainImage = null;
    if (!empty($existingImages)) {
        $newMainImage = $existingImages[0]; // İlk resmi ana resim yap
    }
    
    // Sadece resim alanlarını güncelle
    $updateData = [
        'images' => json_encode($existingImages),
        'main_image' => $newMainImage
    ];
    
    if ($property->updateImages($propertyId, $updateData)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Resim başarıyla silindi!',
            'images' => $existingImages,
            'main_image' => $newMainImage
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
