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

$pageTitle = "Ekip Üyeleri Yönetimi";

$success = '';
$error = '';

// Form işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = trim($_POST['name'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $image_url = trim($_POST['image_url'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($name) || empty($position)) {
            throw new Exception("İsim ve pozisyon alanları zorunludur.");
        }
        
        $stmt = $db->prepare("
            INSERT INTO about_us_team_members (name, position, description, image_url, sort_order, is_active) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $position, $description, $image_url, $sort_order, $is_active]);
        
        $success = "Ekip üyesi başarıyla eklendi!";
        
    } catch (Exception $e) {
        $error = "Ekleme sırasında hata oluştu: " . $e->getMessage();
    }
}

// Ekip üyelerini getir
$teamMembers = [];
$stmt = $db->prepare("SELECT * FROM about_us_team_members ORDER BY sort_order ASC, name ASC");
$stmt->execute();
$teamMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/layout/header.php';
?>

<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 md:p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-white"></i>
                </div>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-800">Ekip Üyeleri Yönetimi</h1>
                    <p class="text-sm md:text-base text-gray-600">Hakkımızda sayfasındaki ekip üyelerini yönetebilirsiniz.</p>
                </div>
            </div>
            <a href="page-edit-hakkimizda.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 text-center md:text-left">
                <i class="fas fa-arrow-left mr-2"></i>Hakkımızda Sayfası
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- Ekip Üyesi Ekleme Formu -->
        <div class="lg:col-span-1 order-2 lg:order-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 md:p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-plus text-green-500 mr-2"></i>
                    Yeni Ekip Üyesi Ekle
                </h2>
                
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">İsim *</label>
                        <input type="text" name="name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pozisyon *</label>
                        <input type="text" name="position" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Resim</label>
                        <input type="hidden" name="image_url" id="team_image_url">
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="openImageUpload('team_image_url')" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                <i class="fas fa-upload mr-2"></i>Resim Yükle
                            </button>
                            <div id="team_image_preview" class="hidden flex items-center space-x-2">
                                <img id="team_preview_img" src="" alt="Önizleme" class="w-16 h-16 object-cover rounded-full border">
                                <button type="button" onclick="removeTeamImage()" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm transition-colors duration-200">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sıralama</label>
                        <input type="number" name="sort_order" value="0" min="0" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" checked 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Aktif
                        </label>
                    </div>
                    
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Ekip Üyesi Ekle
                    </button>
                </form>
            </div>
        </div>

        <!-- Mevcut Ekip Üyeleri -->
        <div class="lg:col-span-2 order-1 lg:order-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 md:p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-list text-blue-500 mr-2"></i>
                    Mevcut Ekip Üyeleri (<?php echo count($teamMembers); ?>)
                </h2>
                
                <?php if (empty($teamMembers)): ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-users text-4xl mb-4"></i>
                        <p>Henüz ekip üyesi eklenmemiş.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($teamMembers as $member): ?>
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                <div class="flex flex-col md:flex-row md:items-start space-y-4 md:space-y-0 md:space-x-4">
                                    <img src="<?php echo htmlspecialchars($member['image_url'] ?: 'https://via.placeholder.com/80x80?text=No+Image'); ?>" 
                                         alt="<?php echo htmlspecialchars($member['name']); ?>" 
                                         class="w-16 h-16 rounded-full object-cover flex-shrink-0 mx-auto md:mx-0">
                                    
                                    <div class="flex-1 text-center md:text-left">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-2 space-y-2 md:space-y-0">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($member['name']); ?></h3>
                                                <p class="text-blue-600 font-medium"><?php echo htmlspecialchars($member['position']); ?></p>
                                            </div>
                                            <div class="flex items-center justify-center md:justify-end space-x-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $member['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                    <?php echo $member['is_active'] ? 'Aktif' : 'Pasif'; ?>
                                                </span>
                                                <span class="text-xs text-gray-500">Sıra: <?php echo $member['sort_order']; ?></span>
                                            </div>
                                        </div>
                                        
                                        <?php if ($member['description']): ?>
                                            <p class="text-gray-700 text-sm mb-3"><?php echo htmlspecialchars($member['description']); ?></p>
                                        <?php endif; ?>
                                        
                                        <div class="flex flex-col md:flex-row md:items-center space-y-1 md:space-y-0 md:space-x-4 text-sm">
                                            <span class="text-gray-500">
                                                <i class="fas fa-calendar mr-1"></i>
                                                Eklenme: <?php echo date('d.m.Y H:i', strtotime($member['created_at'])); ?>
                                            </span>
                                            <?php if ($member['updated_at'] !== $member['created_at']): ?>
                                                <span class="text-gray-500">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Güncelleme: <?php echo date('d.m.Y H:i', strtotime($member['updated_at'])); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-row md:flex-col space-x-2 md:space-x-0 md:space-y-2 justify-center md:justify-start">
                                        <a href="team-member-edit.php?id=<?php echo $member['id']; ?>" 
                                           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors duration-200 text-center">
                                            <i class="fas fa-edit mr-1"></i>Düzenle
                                        </a>
                                        <a href="team-member-delete.php?id=<?php echo $member['id']; ?>" 
                                           class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition-colors duration-200 text-center"
                                           onclick="return confirm('Bu ekip üyesini silmek istediğinizden emin misiniz?')">
                                            <i class="fas fa-trash mr-1"></i>Sil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Image Upload Modal -->
<div id="imageUploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 md:top-20 mx-auto p-4 md:p-5 border w-11/12 md:w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Resim Yükle</h3>
            <form id="imageUploadForm" enctype="multipart/form-data">
                <div class="mb-4">
                    <input type="file" id="imageFile" name="image" accept="image/*" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3">
                    <button type="button" onclick="closeImageUpload()" 
                            class="w-full md:w-auto bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        İptal
                    </button>
                    <button type="submit" 
                            class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
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

function removeTeamImage() {
    if (confirm('Bu resmi silmek istediğinizden emin misiniz?')) {
        document.getElementById('team_image_url').value = '';
        document.getElementById('team_image_preview').classList.add('hidden');
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
            
            // Show preview for team image
            if (currentImageField === 'team_image_url') {
                document.getElementById('team_preview_img').src = data.url;
                document.getElementById('team_image_preview').classList.remove('hidden');
            }
            
            // Show success message with URL
            alert('Resim başarıyla yüklendi!\nURL: ' + data.url);
            
            // Close modal
            closeImageUpload();
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
