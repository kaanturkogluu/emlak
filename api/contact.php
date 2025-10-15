<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Sadece POST metodu kabul edilir']);
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'contact') {
        // İlan iletişim formu
        $propertyId = $_POST['property_id'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        // Validasyon
        if (empty($name) || empty($phone)) {
            echo json_encode(['success' => false, 'message' => 'Ad ve telefon alanları zorunludur']);
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Geçerli bir e-posta adresi giriniz']);
            exit;
        }
        
        // İlanın var olup olmadığını kontrol et
        $stmt = $db->prepare("SELECT id, title FROM properties WHERE id = ? AND status = 'active'");
        $stmt->execute([$propertyId]);
        $property = $stmt->fetch();
        
        if (!$property) {
            echo json_encode(['success' => false, 'message' => 'İlan bulunamadı']);
            exit;
        }
        
        // İletişim kaydını veritabanına ekle
        $stmt = $db->prepare("
            INSERT INTO property_contacts 
            (property_id, name, phone, email, message, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        
        $result = $stmt->execute([$propertyId, $name, $phone, $email, $message]);
        
        if ($result) {
            // E-posta gönderimi (opsiyonel)
            // Burada e-posta gönderme kodu eklenebilir
            
            echo json_encode([
                'success' => true, 
                'message' => 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Mesaj gönderilirken bir hata oluştu']);
        }
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Geçersiz işlem']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Sunucu hatası: ' . $e->getMessage()]);
}
?>
