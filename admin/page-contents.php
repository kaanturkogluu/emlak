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

$pageTitle = "Sayfa İçerikleri Yönetimi";

$success = '';
$error = '';

// Sayfa türleri ve bilgileri
$pageTypes = [
    'hakkimizda' => [
        'name' => 'Hakkımızda',
        'description' => 'Şirket bilgileri, misyon, vizyon ve değerler',
        'icon' => 'fas fa-info-circle',
        'color' => 'blue',
        'url' => 'page-edit-hakkimizda.php'
    ],
    'kvkk' => [
        'name' => 'KVKK',
        'description' => 'Kişisel Verilerin Korunması Kanunu',
        'icon' => 'fas fa-shield-alt',
        'color' => 'red',
        'url' => 'page-edit-kvkk.php'
    ],
    'kullanim-kosullari' => [
        'name' => 'Kullanım Koşulları',
        'description' => 'Site kullanım şartları ve kuralları',
        'icon' => 'fas fa-file-contract',
        'color' => 'orange',
        'url' => 'page-edit-kullanim-kosullari.php'
    ],
    'gizlilik-politikasi' => [
        'name' => 'Gizlilik Politikası',
        'description' => 'Gizlilik ve veri koruma politikaları',
        'icon' => 'fas fa-user-secret',
        'color' => 'purple',
        'url' => 'page-edit-gizlilik-politikasi.php'
    ]
];

// Her sayfa için içerik sayısını hesapla
$pageStats = [];
foreach ($pageTypes as $type => $info) {
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM page_contents WHERE page_type = ?");
    $stmt->execute([$type]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $pageStats[$type] = $result['count'];
}

require_once __DIR__ . '/layout/header.php';
?>

<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-alt text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Sayfa İçerikleri Yönetimi</h1>
                <p class="text-gray-600">Site sayfalarının içeriklerini düzenleyebilirsiniz.</p>
            </div>
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

    <!-- Page Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($pageTypes as $type => $info): ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300 cursor-pointer group" 
                 onclick="window.location.href='<?php echo $info['url']; ?>'">
                
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-<?php echo $info['color']; ?>-500 to-<?php echo $info['color']; ?>-600 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="<?php echo $info['icon']; ?> text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white"><?php echo $info['name']; ?></h3>
                                <p class="text-<?php echo $info['color']; ?>-100 text-sm"><?php echo $info['description']; ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="bg-white bg-opacity-20 rounded-full px-3 py-1">
                                <span class="text-white font-semibold text-sm"><?php echo $pageStats[$type]; ?> İçerik</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Status Badge -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Durum:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Aktif
                            </span>
                        </div>

                        <!-- Last Updated -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Son Güncelleme:</span>
                            <span class="text-sm text-gray-900">
                                <?php
                                $stmt = $db->prepare("SELECT MAX(updated_at) as last_update FROM page_contents WHERE page_type = ?");
                                $stmt->execute([$type]);
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                if ($result['last_update']) {
                                    echo date('d.m.Y H:i', strtotime($result['last_update']));
                                } else {
                                    echo 'Henüz güncellenmedi';
                                }
                                ?>
                            </span>
                        </div>

                        <!-- Content Preview -->
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">İçerik Önizleme:</span>
                                <span class="text-xs text-gray-500">
                                    <?php echo $pageStats[$type] > 0 ? 'İçerik mevcut' : 'İçerik yok'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Düzenlemek için tıklayın</span>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-<?php echo $info['color']; ?>-500 transition-colors duration-300"></i>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<style>
/* Custom hover effects */
.group:hover .group-hover\:text-blue-500 {
    color: #3b82f6;
}
.group:hover .group-hover\:text-green-500 {
    color: #10b981;
}
.group:hover .group-hover\:text-red-500 {
    color: #ef4444;
}
.group:hover .group-hover\:text-orange-500 {
    color: #f59e0b;
}
.group:hover .group-hover\:text-purple-500 {
    color: #8b5cf6;
}

/* Card hover animation */
.group:hover {
    transform: translateY(-2px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
require_once __DIR__ . '/layout/footer.php';
?>