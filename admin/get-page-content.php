<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/AdminUser.php';

$adminUser = new AdminUser();
if (!$adminUser->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$pageType = $_GET['type'] ?? '';
$sectionType = $_GET['section'] ?? '';

if (empty($pageType) || empty($sectionType)) {
    http_response_code(400);
    echo json_encode(['error' => 'Page type and section type are required']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT title, subtitle, content, image_url FROM page_contents WHERE page_type = ? AND section_type = ?");
    $stmt->execute([$pageType, $sectionType]);
    $section = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'title' => $section['title'] ?? '',
        'subtitle' => $section['subtitle'] ?? '',
        'content' => $section['content'] ?? '',
        'image_url' => $section['image_url'] ?? ''
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?>
