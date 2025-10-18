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

$pageTitle = "Kullanım Koşulları Sayfası Düzenleme";

$success = '';
$error = '';

// Form işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db->beginTransaction();
        
        // Kullanım koşulları içerik bölümleri
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
            'general_terms' => [
                'title' => $_POST['general_terms_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['general_terms_content'] ?? '',
                'image_url' => ''
            ],
            'user_obligations' => [
                'title' => $_POST['user_obligations_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['user_obligations_content'] ?? '',
                'image_url' => ''
            ],
            'service_terms' => [
                'title' => $_POST['service_terms_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['service_terms_content'] ?? '',
                'image_url' => ''
            ],
            'liability' => [
                'title' => $_POST['liability_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['liability_content'] ?? '',
                'image_url' => ''
            ],
            'termination' => [
                'title' => $_POST['termination_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['termination_content'] ?? '',
                'image_url' => ''
            ],
            'changes' => [
                'title' => $_POST['changes_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['changes_content'] ?? '',
                'image_url' => ''
            ]
        ];
        
        // Her bölüm için veritabanını güncelle
        foreach ($sections as $section_type => $data) {
            $stmt = $db->prepare("
                INSERT INTO page_contents (page_type, section_type, title, subtitle, content, image_url) 
                VALUES ('kullanim-kosullari', ?, ?, ?, ?, ?)
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
        $success = "Kullanım Koşulları sayfası başarıyla güncellendi!";
        
    } catch (Exception $e) {
        $db->rollBack();
        $error = "Güncelleme sırasında hata oluştu: " . $e->getMessage();
    }
}

// Mevcut içerikleri getir
$contents = [];
$stmt = $db->prepare("SELECT section_type, title, subtitle, content, image_url FROM page_contents WHERE page_type = 'kullanim-kosullari'");
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
                <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-contract text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Kullanım Koşulları Sayfası Düzenleme</h1>
                    <p class="text-gray-600">Site kullanım şartları ve kuralları sayfasının içeriklerini düzenleyebilirsiniz.</p>
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

        <!-- Genel Koşullar -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-list text-green-500 mr-2"></i>
                Genel Koşullar
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="general_terms_title" value="<?php echo htmlspecialchars($contents['general_terms']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="general_terms_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['general_terms']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Kullanıcı Yükümlülükleri -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-check text-blue-500 mr-2"></i>
                Kullanıcı Yükümlülükleri
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="user_obligations_title" value="<?php echo htmlspecialchars($contents['user_obligations']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="user_obligations_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['user_obligations']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Hizmet Koşulları -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-cogs text-purple-500 mr-2"></i>
                Hizmet Koşulları
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="service_terms_title" value="<?php echo htmlspecialchars($contents['service_terms']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="service_terms_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['service_terms']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Sorumluluk -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                Sorumluluk
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="liability_title" value="<?php echo htmlspecialchars($contents['liability']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="liability_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['liability']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Sözleşmenin Sona Ermesi -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-times-circle text-orange-500 mr-2"></i>
                Sözleşmenin Sona Ermesi
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="termination_title" value="<?php echo htmlspecialchars($contents['termination']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="termination_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['termination']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Değişiklikler -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-edit text-indigo-500 mr-2"></i>
                Değişiklikler
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="changes_title" value="<?php echo htmlspecialchars($contents['changes']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="changes_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['changes']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Kaydet Butonu -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-end space-x-4">
                <a href="page-contents.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                    İptal
                </a>
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Kaydet
                </button>
            </div>
        </div>
    </form>
</div>

<?php
require_once __DIR__ . '/layout/footer.php';
?>
