<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/AdminUser.php';

$adminUser = new AdminUser();
if (!$adminUser->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Dosya yüklenirken hata oluştu.']);
    exit;
}

$file = $_FILES['image'];
$uploadDir = __DIR__ . '/../uploads/about-us/';

// Upload dizinini oluştur
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Dosya türü kontrolü
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Sadece JPG, PNG, GIF ve WebP dosyaları kabul edilir.']);
    exit;
}

// Dosya boyutu kontrolü (5MB)
$maxSize = 5 * 1024 * 1024; // 5MB
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'Dosya boyutu 5MB\'dan küçük olmalıdır.']);
    exit;
}

// Güvenli dosya adı oluştur
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$fileName = 'about_us_' . time() . '_' . uniqid() . '.' . $extension;
$filePath = $uploadDir . $fileName;

// Dosyayı taşı
if (move_uploaded_file($file['tmp_name'], $filePath)) {
    // URL'yi oluştur
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    
    // Windows path'leri için düzeltme
    $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $filePathNormalized = str_replace('\\', '/', $filePath);
    $relativePath = str_replace($docRoot, '', $filePathNormalized);
    
    // Başlangıçtaki slash'ı düzelt
    if (strpos($relativePath, '/') !== 0) {
        $relativePath = '/' . $relativePath;
    }
    
    $imageUrl = $baseUrl . $relativePath;
    
    // Debug bilgileri (geliştirme aşamasında)
    $debug = [
        'doc_root' => $_SERVER['DOCUMENT_ROOT'],
        'file_path' => $filePath,
        'relative_path' => $relativePath,
        'base_url' => $baseUrl,
        'final_url' => $imageUrl
    ];
    
    echo json_encode([
        'success' => true,
        'message' => 'Resim başarıyla yüklendi.',
        'url' => $imageUrl,
        'filename' => $fileName,
        'debug' => $debug
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Dosya yüklenirken hata oluştu.']);
}
?>
