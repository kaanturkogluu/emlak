<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Helper.php';

$adminUser = new AdminUser();
if (!$adminUser->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$helper = Helper::getInstance();
$db = Database::getInstance()->getConnection();

$pageTitle = "Gizlilik Politikası Sayfası Düzenleme";

$success = '';
$error = '';

// Form işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db->beginTransaction();
        
        // Gizlilik politikası içerik bölümleri
        $sections = [
            'hero' => [
                'title' => $_POST['hero_title'] ?? '',
                'subtitle' => $_POST['hero_subtitle'] ?? '',
                'content' => '',
                'image_url' => ''
            ],
            'main_content' => [
                'title' => $_POST['main_title'] ?? '',
                'subtitle' => $_POST['main_subtitle'] ?? '',
                'content' => $_POST['main_content'] ?? '',
                'image_url' => ''
            ],
            'data_collection' => [
                'title' => $_POST['data_collection_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['data_collection_content'] ?? '',
                'image_url' => ''
            ],
            'data_usage' => [
                'title' => $_POST['data_usage_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['data_usage_content'] ?? '',
                'image_url' => ''
            ],
            'data_sharing' => [
                'title' => $_POST['data_sharing_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['data_sharing_content'] ?? '',
                'image_url' => ''
            ],
            'data_security' => [
                'title' => $_POST['data_security_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['data_security_content'] ?? '',
                'image_url' => ''
            ],
            'cookies' => [
                'title' => $_POST['cookies_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['cookies_content'] ?? '',
                'image_url' => ''
            ],
            'user_rights' => [
                'title' => $_POST['user_rights_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['user_rights_content'] ?? '',
                'image_url' => ''
            ],
            'contact_info' => [
                'title' => $_POST['contact_info_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['contact_info_content'] ?? '',
                'image_url' => ''
            ]
        ];
        
        // Her bölüm için veritabanını güncelle
        foreach ($sections as $section_type => $data) {
            $stmt = $db->prepare("
                INSERT INTO page_contents (page_type, section_type, title, subtitle, content, image_url) 
                VALUES ('gizlilik-politikasi', ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                title = VALUES(title), 
                subtitle = VALUES(subtitle), 
                content = VALUES(content), 
                image_url = VALUES(image_url),
                updated_at = CURRENT_TIMESTAMP
            ");
            $stmt->execute([
                $section_type,
                $data['title'],
                $data['subtitle'],
                $data['content'],
                $data['image_url']
            ]);
        }
        
        $db->commit();
        $success = "Gizlilik Politikası sayfası başarıyla güncellendi!";
        
    } catch (Exception $e) {
        $db->rollBack();
        $error = "Güncelleme sırasında hata oluştu: " . $e->getMessage();
    }
}

// Mevcut içerikleri getir
$contents = [];
$stmt = $db->prepare("SELECT section_type, title, subtitle, content, image_url FROM page_contents WHERE page_type = 'gizlilik-politikasi'");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $contents[$row['section_type']] = $row;
}

require_once __DIR__ . '/layout/header.php';
?>

<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-secret text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Gizlilik Politikası Sayfası Düzenleme</h1>
                    <p class="text-gray-600">Gizlilik ve veri koruma politikaları sayfasının içeriklerini düzenleyebilirsiniz.</p>
                </div>
            </div>
            <a href="page-contents.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Geri Dön
            </a>
        </div>
    </div>

    <!-- Alerts -->
    <?php if ($success): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-8">
        <!-- Hero Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-star text-yellow-500 mr-2"></i>
                Hero Bölümü
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="hero_title" value="<?php echo htmlspecialchars($contents['hero']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alt Başlık</label>
                    <input type="text" name="hero_subtitle" value="<?php echo htmlspecialchars($contents['hero']['subtitle'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Ana İçerik -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                Ana İçerik Bölümü
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="main_title" value="<?php echo htmlspecialchars($contents['main_content']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alt Başlık</label>
                    <input type="text" name="main_subtitle" value="<?php echo htmlspecialchars($contents['main_content']['subtitle'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="main_content" rows="6" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['main_content']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Veri Toplama -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-database text-green-500 mr-2"></i>
                Veri Toplama
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="data_collection_title" value="<?php echo htmlspecialchars($contents['data_collection']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="data_collection_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['data_collection']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Veri Kullanımı -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-cogs text-blue-500 mr-2"></i>
                Veri Kullanımı
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="data_usage_title" value="<?php echo htmlspecialchars($contents['data_usage']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="data_usage_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['data_usage']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Veri Paylaşımı -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-share-alt text-purple-500 mr-2"></i>
                Veri Paylaşımı
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="data_sharing_title" value="<?php echo htmlspecialchars($contents['data_sharing']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="data_sharing_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['data_sharing']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Veri Güvenliği -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-lock text-red-500 mr-2"></i>
                Veri Güvenliği
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="data_security_title" value="<?php echo htmlspecialchars($contents['data_security']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="data_security_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['data_security']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Çerezler -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-cookie-bite text-orange-500 mr-2"></i>
                Çerezler
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="cookies_title" value="<?php echo htmlspecialchars($contents['cookies']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="cookies_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['cookies']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Kullanıcı Hakları -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-shield text-indigo-500 mr-2"></i>
                Kullanıcı Hakları
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="user_rights_title" value="<?php echo htmlspecialchars($contents['user_rights']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="user_rights_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['user_rights']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- İletişim Bilgileri -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-envelope text-teal-500 mr-2"></i>
                İletişim Bilgileri
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="contact_info_title" value="<?php echo htmlspecialchars($contents['contact_info']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="contact_info_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['contact_info']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Kaydet Butonu -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-end space-x-4">
                <a href="page-contents.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                    İptal
                </a>
                <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Kaydet
                </button>
            </div>
        </div>
    </form>
</div>

<?php
require_once __DIR__ . '/layout/footer.php';
?>
