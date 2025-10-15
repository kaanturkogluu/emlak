<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Helper.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Slider.php';

$helper = Helper::getInstance();
$adminUser = new AdminUser();
$slider = new Slider();

$sliderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sliderData = null;
$success = '';
$error = '';
$pageTitle = $sliderId > 0 ? 'Slider Düzenle' : 'Yeni Slider Ekle';

// Slider verilerini getir
if ($sliderId > 0) {
    $sliderData = $slider->getById($sliderId);
    if (!$sliderData) {
        header('Location: sliders.php');
        exit;
    }
}

// Form işleme
if ($_POST) {
    $data = [
        'title' => $_POST['title'] ?? '',
        'subtitle' => $_POST['subtitle'] ?? '',
        'button_text' => $_POST['button_text'] ?? '',
        'button_url' => $_POST['button_url'] ?? '',
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
        'status' => $_POST['status'] ?? 'active'
    ];
    
    // Custom URL kontrolü
    if ($data['button_url'] === 'custom' && !empty($_POST['custom_url'])) {
        $data['button_url'] = $_POST['custom_url'];
    }
    
    // Validation
    if (empty($data['title'])) {
        $error = 'Başlık alanı zorunludur!';
    } else {
        // Resim yükleme işlemi
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/sliders/';
            
            // Upload klasörünü oluştur
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExtension = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = 'slider_' . time() . '_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $filePath)) {
                    $data['image'] = $helper->getBaseUrl() . '/uploads/sliders/' . $fileName;
                }
            }
        } elseif ($sliderId == 0) {
            $error = 'Resim yüklemesi zorunludur!';
        }
        
        if (empty($error)) {
            if ($sliderId > 0) {
                // Güncelleme - sadece yeni resim yüklendiğinde image alanını gönder
                if (isset($data['image'])) {
                    // Yeni resim yüklendi, image alanı ile güncelle
                    if ($slider->update($sliderId, $data)) {
                        $success = 'Slider başarıyla güncellendi.';
                        $sliderData = $slider->getById($sliderId); // Güncel veriyi al
                    } else {
                        $error = 'Slider güncellenirken hata oluştu.';
                    }
                } else {
                    // Yeni resim yüklenmedi, image alanı olmadan güncelle
                    unset($data['image']); // Image alanını kaldır
                    if ($slider->update($sliderId, $data)) {
                        $success = 'Slider başarıyla güncellendi.';
                        $sliderData = $slider->getById($sliderId); // Güncel veriyi al
                    } else {
                        $error = 'Slider güncellenirken hata oluştu.';
                    }
                }
            } else {
                // Yeni oluşturma
                if ($slider->create($data)) {
                    $success = 'Slider başarıyla oluşturuldu.';
                    // Formu temizle
                    $_POST = [];
                } else {
                    $error = 'Slider oluşturulurken hata oluştu.';
                }
            }
        }
    }
}

// Layout header'ı dahil et
require_once __DIR__ . '/layout/header.php';
?>

<?php if ($success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo $helper->e($success); ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <?php echo $helper->e($error); ?>
    </div>
<?php endif; ?>

<div class="form-container">
    <div class="form-header">
        <h2><?php echo $sliderId > 0 ? 'Slider Düzenle' : 'Yeni Slider Ekle'; ?></h2>
        <a href="sliders.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
    
    <form method="POST" enctype="multipart/form-data" id="sliderForm">
        <div class="form-group full-width">
            <label for="title" class="required">Başlık</label>
            <input type="text" id="title" name="title" class="form-control" 
                   value="<?php echo $sliderData ? $helper->e($sliderData['title']) : (isset($_POST['title']) ? $helper->e($_POST['title']) : ''); ?>" required>
        </div>
        
        <div class="form-group full-width">
            <label for="subtitle">Alt Başlık</label>
            <textarea id="subtitle" name="subtitle" class="form-control" rows="3"><?php echo $sliderData ? $helper->e($sliderData['subtitle']) : (isset($_POST['subtitle']) ? $helper->e($_POST['subtitle']) : ''); ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="button_text">Buton Metni</label>
                <input type="text" id="button_text" name="button_text" class="form-control" 
                       value="<?php echo $sliderData ? $helper->e($sliderData['button_text']) : (isset($_POST['button_text']) ? $helper->e($_POST['button_text']) : ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="button_url">Buton Sayfası</label>
                <select id="button_url" name="button_url" class="form-control">
                    <option value="">Sayfa Seçin</option>
                    <option value="<?php echo $helper->url(''); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('')) ? 'selected' : ''; ?>>Ana Sayfa</option>
                    <option value="<?php echo $helper->url('hakkimizda'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('hakkimizda')) ? 'selected' : ''; ?>>Hakkımızda</option>
                    <option value="<?php echo $helper->url('iletisim'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('iletisim')) ? 'selected' : ''; ?>>İletişim</option>
                    <option value="<?php echo $helper->url('ilanlar'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('ilanlar')) ? 'selected' : ''; ?>>İlanlar</option>
                    <option value="<?php echo $helper->url('satilik'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('satilik')) ? 'selected' : ''; ?>>Satılık</option>
                    <option value="<?php echo $helper->url('kiralik'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('kiralik')) ? 'selected' : ''; ?>>Kiralık</option>
                    <option value="custom" <?php echo ($sliderData && !in_array($sliderData['button_url'], [$helper->url(''), $helper->url('hakkimizda'), $helper->url('iletisim'), $helper->url('ilanlar'), $helper->url('satilik'), $helper->url('kiralik')]) && !empty($sliderData['button_url'])) ? 'selected' : ''; ?>>Özel URL Gir</option>
                </select>
                <input type="url" id="custom_url" name="custom_url" class="form-control" 
                       style="display: none; margin-top: 10px;" 
                       value="<?php echo ($sliderData && !in_array($sliderData['button_url'], [$helper->url(''), $helper->url('hakkimizda'), $helper->url('iletisim'), $helper->url('ilanlar'), $helper->url('satilik'), $helper->url('kiralik')]) && !empty($sliderData['button_url'])) ? $helper->e($sliderData['button_url']) : ''; ?>"
                       placeholder="https://example.com">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="sort_order">Sıra</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" 
                       value="<?php echo $sliderData ? $sliderData['sort_order'] : (isset($_POST['sort_order']) ? $_POST['sort_order'] : '0'); ?>" 
                       min="0">
            </div>
            
            <div class="form-group">
                <label for="status">Durum</label>
                <select id="status" name="status" class="form-control">
                    <option value="active" <?php echo ($sliderData && $sliderData['status'] === 'active') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="inactive" <?php echo ($sliderData && $sliderData['status'] === 'inactive') ? 'selected' : ''; ?>>Pasif</option>
                </select>
            </div>
        </div>
        
        <div class="form-group full-width">
            <label for="image" <?php echo $sliderId == 0 ? 'class="required"' : ''; ?>>Resim</label>
            <div class="file-upload-container" onclick="document.getElementById('image_file').click()">
                <div class="file-upload-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <div class="file-upload-text">
                    <?php if ($sliderId > 0): ?>
                        Yeni resim dosyasını seçin veya buraya sürükleyin (isteğe bağlı)
                    <?php else: ?>
                        Resim dosyasını seçin veya buraya sürükleyin (zorunlu)
                    <?php endif; ?>
                </div>
                <button type="button" class="file-upload-btn">
                    <i class="fas fa-folder-open"></i> Dosya Seç
                </button>
                <div class="file-info">
                    Desteklenen formatlar: JPG, PNG, GIF, WebP (Max: 5MB)
                </div>
            </div>
            <input type="file" id="image_file" name="image_file" accept="image/*" style="display: none;" onchange="handleFileSelect(this)">
            
            <?php if ($sliderData && $sliderData['image']): ?>
            <div class="current-image" style="margin-top: 15px;">
                <label>Mevcut Resim:</label>
                <div class="image-preview">
                    <img src="<?php echo $helper->e($sliderData['image']); ?>" alt="Mevcut Resim" style="max-width: 300px; max-height: 200px; border-radius: 8px; border: 2px solid #e9ecef; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                </div>
                <p style="color: #6c757d; font-size: 0.9rem; margin-top: 5px;">
                    Yeni resim yüklemek için yukarıdaki alanı kullanın.
                </p>
            </div>
            <?php endif; ?>
            
            <div id="imagePreview" class="image-preview" style="display: none;">
                <img id="previewImg" src="" alt="Önizleme">
            </div>
        </div>
        
        <div class="form-actions">
            <a href="sliders.php" class="btn btn-secondary">
                <i class="fas fa-times"></i> İptal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> <?php echo $sliderId > 0 ? 'Güncelle' : 'Kaydet'; ?>
            </button>
        </div>
    </form>
</div>

<style>
/* File Upload Styles */
.file-upload-container {
    border: 2px dashed #e9ecef;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: border-color 0.3s ease;
    cursor: pointer;
}

.file-upload-container:hover {
    border-color: #667eea;
}

.file-upload-container.dragover {
    border-color: #667eea;
    background: #f8f9ff;
}

.file-upload-icon {
    font-size: 2rem;
    color: #6c757d;
    margin-bottom: 10px;
}

.file-upload-text {
    color: #6c757d;
    margin-bottom: 10px;
}

.file-upload-btn {
    background: #667eea;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9rem;
}

.file-upload-btn:hover {
    background: #5a6fd8;
}

.file-info {
    margin-top: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
    font-size: 0.9rem;
    color: #6c757d;
}

/* Image Preview */
.image-preview {
    margin-top: 15px;
    text-align: center;
}

.image-preview img {
    max-width: 300px;
    max-height: 200px;
    border-radius: 8px;
    border: 2px solid #e9ecef;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.current-image {
    margin-top: 15px;
}

.current-image label {
    display: block;
    margin-bottom: 10px;
    color: #2c3e50;
    font-weight: 600;
}
</style>

<script>
// Custom URL field toggle
document.getElementById('button_url').addEventListener('change', function() {
    const customUrlField = document.getElementById('custom_url');
    if (this.value === 'custom') {
        customUrlField.style.display = 'block';
        customUrlField.required = true;
    } else {
        customUrlField.style.display = 'none';
        customUrlField.required = false;
    }
});

// Initialize custom URL field
document.addEventListener('DOMContentLoaded', function() {
    const buttonUrlSelect = document.getElementById('button_url');
    const customUrlField = document.getElementById('custom_url');
    
    if (buttonUrlSelect.value === 'custom') {
        customUrlField.style.display = 'block';
        customUrlField.required = true;
    }
});

// File select handler
function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Drag and drop
const fileUploadContainer = document.querySelector('.file-upload-container');

fileUploadContainer.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});

fileUploadContainer.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
});

fileUploadContainer.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('image_file').files = files;
        handleFileSelect(document.getElementById('image_file'));
    }
});
</script>

<?php
// Layout footer'ı dahil et
require_once __DIR__ . '/layout/footer.php';
?>
