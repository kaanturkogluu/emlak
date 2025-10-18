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

$pageTitle = "Ekip Üyesi Düzenleme";

$success = '';
$error = '';

// ID kontrolü
$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: team-members.php');
    exit;
}

// Ekip üyesi bilgilerini getir
$stmt = $db->prepare("SELECT * FROM about_us_team_members WHERE id = ?");
$stmt->execute([$id]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    header('Location: team-members.php');
    exit;
}

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
            UPDATE about_us_team_members 
            SET name = ?, position = ?, description = ?, image_url = ?, sort_order = ?, is_active = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $stmt->execute([$name, $position, $description, $image_url, $sort_order, $is_active, $id]);
        
        $success = "Ekip üyesi başarıyla güncellendi!";
        
        // Güncellenmiş bilgileri tekrar getir
        $stmt = $db->prepare("SELECT * FROM about_us_team_members WHERE id = ?");
        $stmt->execute([$id]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        $error = "Güncelleme sırasında hata oluştu: " . $e->getMessage();
    }
}

require_once __DIR__ . '/layout/header.php';
?>

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-edit text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Ekip Üyesi Düzenleme</h1>
                    <p class="text-gray-600"><?php echo htmlspecialchars($member['name']); ?> bilgilerini düzenleyebilirsiniz.</p>
                </div>
            </div>
            <a href="team-members.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Düzenleme Formu -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-edit text-blue-500 mr-2"></i>
                    Ekip Üyesi Bilgileri
                </h2>
                
                <form method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">İsim *</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pozisyon *</label>
                            <input type="text" name="position" value="<?php echo htmlspecialchars($member['position']); ?>" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                        <textarea name="description" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?php echo htmlspecialchars($member['description']); ?></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Resim</label>
                        <input type="hidden" name="image_url" value="<?php echo htmlspecialchars($member['image_url']); ?>" id="edit_image_url">
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="openImageUpload('edit_image_url')" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                <i class="fas fa-upload mr-2"></i>Resim Yükle
                            </button>
                            <?php if (!empty($member['image_url'])): ?>
                                <div class="flex items-center space-x-2">
                                    <img src="<?php echo htmlspecialchars($member['image_url']); ?>" 
                                         alt="Mevcut Resim" class="w-16 h-16 object-cover rounded-full border">
                                    <button type="button" onclick="removeEditImage()" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm transition-colors duration-200">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sıralama</label>
                            <input type="number" name="sort_order" value="<?php echo $member['sort_order']; ?>" min="0" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" <?php echo $member['is_active'] ? 'checked' : ''; ?> 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Aktif
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="team-members.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                            İptal
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Önizleme -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-eye text-green-500 mr-2"></i>
                    Önizleme
                </h2>
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="text-center">
                        <img src="<?php echo htmlspecialchars($member['image_url'] ?: 'https://via.placeholder.com/120x120?text=No+Image'); ?>" 
                             alt="<?php echo htmlspecialchars($member['name']); ?>" 
                             class="w-24 h-24 rounded-full object-cover mx-auto mb-3">
                        
                        <h3 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($member['name']); ?></h3>
                        <p class="text-blue-600 font-medium mb-3"><?php echo htmlspecialchars($member['position']); ?></p>
                        
                        <?php if ($member['description']): ?>
                            <p class="text-gray-700 text-sm"><?php echo htmlspecialchars($member['description']); ?></p>
                        <?php endif; ?>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Sıra: <?php echo $member['sort_order']; ?></span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo $member['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo $member['is_active'] ? 'Aktif' : 'Pasif'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

function removeEditImage() {
    if (confirm('Bu resmi silmek istediğinizden emin misiniz?')) {
        document.getElementById('edit_image_url').value = '';
        // Formu otomatik olarak submit et
        document.querySelector('form').submit();
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
            // Update the image URL field
            document.getElementById(currentImageField).value = data.url;
            
            // Show success message
            alert('Resim başarıyla yüklendi!');
            
            // Close modal
            closeImageUpload();
            
            // Formu otomatik olarak submit et
            document.querySelector('form').submit();
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

// Form alanları değiştiğinde önizlemeyi güncelle
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const preview = document.querySelector('.lg\\:col-span-1 .border');
    
    if (form && preview) {
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', updatePreview);
        });
    }
    
    function updatePreview() {
        const name = document.querySelector('input[name="name"]').value;
        const position = document.querySelector('input[name="position"]').value;
        const description = document.querySelector('textarea[name="description"]').value;
        const imageUrl = document.querySelector('input[name="image_url"]').value;
        const isActive = document.querySelector('input[name="is_active"]').checked;
        const sortOrder = document.querySelector('input[name="sort_order"]').value;
        
        // Önizleme güncelle
        const previewName = preview.querySelector('h3');
        const previewPosition = preview.querySelector('p.text-blue-600');
        const previewDescription = preview.querySelector('p.text-gray-700');
        const previewImage = preview.querySelector('img');
        const previewStatus = preview.querySelector('.inline-flex');
        const previewSort = preview.querySelector('span:first-child');
        
        if (previewName) previewName.textContent = name || 'İsim';
        if (previewPosition) previewPosition.textContent = position || 'Pozisyon';
        if (previewDescription) previewDescription.textContent = description || 'Açıklama yok';
        if (previewImage) previewImage.src = imageUrl || 'https://via.placeholder.com/120x120?text=No+Image';
        if (previewStatus) {
            previewStatus.textContent = isActive ? 'Aktif' : 'Pasif';
            previewStatus.className = `inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
        }
        if (previewSort) previewSort.textContent = `Sıra: ${sortOrder}`;
    }
});
</script>

<?php
require_once __DIR__ . '/layout/footer.php';
?>
