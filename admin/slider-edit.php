<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Helper.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Slider.php';

// JSON response header
header('Content-Type: application/json');

$helper = Helper::getInstance();
$adminUser = new AdminUser();
$slider = new Slider();

// Admin giriş kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Kullanıcı bilgilerini al
$currentUser = $adminUser->getById($_SESSION['admin_user_id']);
if (!$currentUser) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

// GET request - slider verilerini getir
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sliderData = $slider->getById($id);
    
    if ($sliderData) {
        echo json_encode([
            'success' => true,
            'slider' => $sliderData
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Slider bulunamadı'
        ]);
    }
    exit;
}

// POST request - slider güncelle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $data = [
        'title' => $_POST['title'] ?? '',
        'subtitle' => $_POST['subtitle'] ?? '',
        'button_text' => $_POST['button_text'] ?? '',
        'button_url' => $_POST['button_url'] ?? '',
        'image' => $_POST['image'] ?? '',
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
        'status' => $_POST['status'] ?? 'active'
    ];
    
    // Validation
    if (empty($data['title'])) {
        echo json_encode(['success' => false, 'message' => 'Başlık zorunludur']);
        exit;
    }
    
    if (empty($data['image'])) {
        echo json_encode(['success' => false, 'message' => 'Resim URL zorunludur']);
        exit;
    }
    
    if ($slider->update($id, $data)) {
        echo json_encode(['success' => true, 'message' => 'Slider başarıyla güncellendi']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Slider güncellenirken hata oluştu']);
    }
    exit;
}

// Invalid request
echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
?>
