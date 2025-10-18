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

$pageTitle = "Hakkımızda Sayfası Düzenleme";

$success = '';
$error = '';

// Form işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db->beginTransaction();
        
        // Ana içerik bölümleri
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
                'image_url' => $_POST['main_image_url'] ?? ''
            ],
            'mission' => [
                'title' => $_POST['mission_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['mission_content'] ?? '',
                'image_url' => ''
            ],
            'vision' => [
                'title' => $_POST['vision_title'] ?? '',
                'subtitle' => '',
                'content' => $_POST['vision_content'] ?? '',
                'image_url' => ''
            ],
            'team' => [
                'title' => $_POST['team_title'] ?? '',
                'subtitle' => $_POST['team_subtitle'] ?? '',
                'content' => '',
                'image_url' => ''
            ]
        ];
        
        // Her bölüm için veritabanını güncelle
        foreach ($sections as $section_type => $data) {
            $stmt = $db->prepare("
                INSERT INTO page_contents (page_type, section_type, title, subtitle, content, image_url) 
                VALUES ('hakkimizda', ?, ?, ?, ?, ?)
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
        $success = "Hakkımızda sayfası başarıyla güncellendi!";
        
    } catch (Exception $e) {
        $db->rollBack();
        $error = "Güncelleme sırasında hata oluştu: " . $e->getMessage();
    }
}

// Mevcut içerikleri getir
$contents = [];
$stmt = $db->prepare("SELECT section_type, title, subtitle, content, image_url FROM page_contents WHERE page_type = 'hakkimizda'");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $contents[$row['section_type']] = $row;
}

// Ekip üyelerini getir
$teamMembers = [];
$stmt = $db->prepare("SELECT * FROM about_us_team_members WHERE is_active = 1 ORDER BY sort_order ASC");
$stmt->execute();
$teamMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/layout/header.php';
?>

<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Hakkımızda Sayfası Düzenleme</h1>
                    <p class="text-gray-600">Hakkımızda sayfasının tüm içeriklerini düzenleyebilirsiniz.</p>
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
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Resim</label>
                    <input type="hidden" name="main_image_url" value="<?php echo htmlspecialchars($contents['main_content']['image_url'] ?? ''); ?>" id="main_image_url">
                    <div class="flex items-center space-x-4">
                        <button type="button" onclick="openImageUpload('main_image_url')" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            <i class="fas fa-upload mr-2"></i>Resim Yükle
                        </button>
                        <div id="main_image_preview" class="<?php echo !empty($contents['main_content']['image_url']) ? 'flex' : 'hidden'; ?> items-center space-x-2">
                            <?php if (!empty($contents['main_content']['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($contents['main_content']['image_url']); ?>" 
                                     alt="Ana İçerik Resim" class="w-16 h-12 object-cover rounded border" id="main_preview_img">
                                <button type="button" onclick="removeImage('main_image_url')" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm transition-colors duration-200">
                                    <i class="fas fa-times"></i>
                                </button>
                            <?php else: ?>
                                <img src="" alt="Önizleme" class="w-16 h-12 object-cover rounded border" id="main_preview_img">
                                <button type="button" onclick="removeImage('main_image_url')" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm transition-colors duration-200">
                                    <i class="fas fa-times"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Misyon -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-bullseye text-green-500 mr-2"></i>
                Misyonumuz
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="mission_title" value="<?php echo htmlspecialchars($contents['mission']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="mission_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['mission']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Vizyon -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-eye text-purple-500 mr-2"></i>
                Vizyonumuz
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="vision_title" value="<?php echo htmlspecialchars($contents['vision']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="vision_content" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($contents['vision']['content'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Ekip Bölümü -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-users text-orange-500 mr-2"></i>
                    Ekibimiz
                </h2>
                <a href="team-members.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Ekip Üyesi Ekle
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık</label>
                    <input type="text" name="team_title" value="<?php echo htmlspecialchars($contents['team']['title'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alt Başlık</label>
                    <input type="text" name="team_subtitle" value="<?php echo htmlspecialchars($contents['team']['subtitle'] ?? ''); ?>" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            
            <!-- Mevcut Ekip Üyeleri -->
            <?php if (!empty($teamMembers)): ?>
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Mevcut Ekip Üyeleri</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($teamMembers as $member): ?>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center space-x-3 mb-3">
                                    <img src="<?php echo htmlspecialchars($member['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($member['name']); ?>" 
                                         class="w-12 h-12 rounded-full object-cover">
                                    <div>
                                        <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($member['name']); ?></h4>
                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($member['position']); ?></p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-700 mb-3"><?php echo htmlspecialchars($member['description']); ?></p>
                                <div class="flex space-x-2">
                                    <a href="team-member-edit.php?id=<?php echo $member['id']; ?>" 
                                       class="text-blue-500 hover:text-blue-700 text-sm">
                                        <i class="fas fa-edit mr-1"></i>Düzenle
                                    </a>
                                    <a href="team-member-delete.php?id=<?php echo $member['id']; ?>" 
                                       class="text-red-500 hover:text-red-700 text-sm"
                                       onclick="return confirm('Bu ekip üyesini silmek istediğinizden emin misiniz?')">
                                        <i class="fas fa-trash mr-1"></i>Sil
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Kaydet Butonu -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex justify-end space-x-4">
                <a href="page-contents.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                    İptal
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Kaydet
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Image Upload Modal -->
<div id="imageUploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Resim Yükle</h3>
            <form id="imageUploadForm" enctype="multipart/form-data">
                <div class="mb-4">
                    <input type="file" id="imageFile" name="image" accept="image/*" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeImageUpload()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        İptal
                    </button>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-upload mr-2"></i>Yükle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentImageField = '';

function openImageUpload(fieldId) {
    currentImageField = fieldId;
    document.getElementById('imageUploadModal').classList.remove('hidden');
}

function closeImageUpload() {
    document.getElementById('imageUploadModal').classList.add('hidden');
    document.getElementById('imageUploadForm').reset();
    currentImageField = '';
}

function removeImage(fieldId) {
    if (confirm('Bu resmi silmek istediğinizden emin misiniz?')) {
        document.getElementById(fieldId).value = '';
        
        // Hide preview for main image
        if (fieldId === 'main_image_url') {
            document.getElementById('main_image_preview').classList.add('hidden');
            document.getElementById('main_image_preview').classList.remove('flex');
        }
        
        // For other images, reload the page
        if (fieldId !== 'main_image_url') {
            location.reload();
        }
    }
}

// Image upload form submission
document.getElementById('imageUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const fileInput = document.getElementById('imageFile');
    
    if (fileInput.files.length === 0) {
        alert('Lütfen bir dosya seçin.');
        return;
    }
    
    formData.append('image', fileInput.files[0]);
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Yükleniyor...';
    submitBtn.disabled = true;
    
    fetch('upload-image.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Debug bilgilerini konsola yazdır
            if (data.debug) {
                console.log('Upload Debug:', data.debug);
            }
            
            // Update the image URL field
            document.getElementById(currentImageField).value = data.url;
            
            // Show preview for main image
            if (currentImageField === 'main_image_url') {
                document.getElementById('main_preview_img').src = data.url;
                document.getElementById('main_image_preview').classList.remove('hidden');
                document.getElementById('main_image_preview').classList.add('flex');
            }
            
            // Show success message with URL
            alert('Resim başarıyla yüklendi!\nURL: ' + data.url);
            
            // Close modal
            closeImageUpload();
            
            // Auto-save the form to update database
            setTimeout(() => {
                document.querySelector('form').submit();
            }, 1000);
        } else {
            alert('Hata: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Resim yüklenirken bir hata oluştu.');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Close modal when clicking outside
document.getElementById('imageUploadModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageUpload();
    }
});
</script>

<?php
require_once __DIR__ . '/layout/footer.php';
?>
