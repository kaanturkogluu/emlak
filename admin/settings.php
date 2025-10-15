<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Helper.php';

// Admin giriş kontrolü
$adminUser = new AdminUser();
if (!$adminUser->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$helper = Helper::getInstance();

// Form gönderildi mi?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = $_POST['settings'] ?? [];
    
    $success = true;
    $errors = [];
    
    // Site ikonu yükleme
    if (isset($_FILES['site_icon']) && $_FILES['site_icon']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/images/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                $errors[] = 'Yükleme dizini oluşturulamadı: ' . $uploadDir;
                $success = false;
            }
        }
        
        if ($success) {
            $fileExtension = strtolower(pathinfo($_FILES['site_icon']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['png', 'jpg', 'jpeg', 'ico', 'gif'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = 'favicon.' . $fileExtension;
                $uploadPath = $uploadDir . $fileName;
                
                // Dosya boyutu kontrolü (max 2MB)
                if ($_FILES['site_icon']['size'] > 2 * 1024 * 1024) {
                    $errors[] = 'Dosya boyutu çok büyük. Maksimum 2MB olmalıdır.';
                    $success = false;
                } else {
                    if (move_uploaded_file($_FILES['site_icon']['tmp_name'], $uploadPath)) {
                        $settings['site_icon'] = $helper->getBaseUrl() . '/assets/images/' . $fileName;
                    } else {
                        $errors[] = 'Site ikonu yüklenemedi. Dosya yazma izni kontrol edin.';
                        $success = false;
                    }
                }
            } else {
                $errors[] = 'Desteklenmeyen dosya formatı. PNG, JPG, ICO, GIF formatları desteklenir.';
                $success = false;
            }
        }
    } elseif (isset($_FILES['site_icon']) && $_FILES['site_icon']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'Dosya boyutu çok büyük (php.ini limiti)',
            UPLOAD_ERR_FORM_SIZE => 'Dosya boyutu çok büyük (form limiti)',
            UPLOAD_ERR_PARTIAL => 'Dosya kısmen yüklendi',
            UPLOAD_ERR_NO_TMP_DIR => 'Geçici dizin bulunamadı',
            UPLOAD_ERR_CANT_WRITE => 'Dosya yazılamadı',
            UPLOAD_ERR_EXTENSION => 'Dosya yükleme uzantı tarafından durduruldu'
        ];
        $errors[] = 'Dosya yükleme hatası: ' . ($uploadErrors[$_FILES['site_icon']['error']] ?? 'Bilinmeyen hata');
        $success = false;
    }
    
    // Her ayarı tek tek güncelle
    $settingsToUpdate = [
        'site_name' => $settings['site_name'] ?? '',
        'site_description' => $settings['site_description'] ?? '',
        'site_keywords' => $settings['site_keywords'] ?? '',
        'items_per_page' => (int)($settings['items_per_page'] ?? 12),
        'contact_phone' => $settings['contact_phone'] ?? '',
        'contact_email' => $settings['contact_email'] ?? '',
        'contact_address' => $settings['contact_address'] ?? '',
        'working_hours' => $settings['working_hours'] ?? '',
        'facebook_url' => $settings['facebook_url'] ?? '',
        'twitter_url' => $settings['twitter_url'] ?? '',
        'instagram_url' => $settings['instagram_url'] ?? '',
        'linkedin_url' => $settings['linkedin_url'] ?? '',
        'youtube_url' => $settings['youtube_url'] ?? '',
        'google_analytics' => $settings['google_analytics'] ?? '',
        'google_maps_api' => $settings['google_maps_api'] ?? ''
    ];
    
    // Site ikonu varsa ekle
    if (isset($settings['site_icon'])) {
        $settingsToUpdate['site_icon'] = $settings['site_icon'];
    }
    
    foreach ($settingsToUpdate as $key => $value) {
        if (!$helper->setSetting($key, $value)) {
            $success = false;
            $errors[] = $key . ' güncellenemedi';
        }
    }
    
    if ($success) {
        $successMessage = "Ayarlar başarıyla güncellendi!";
    } else {
        $errorMessage = "Ayarlar güncellenirken hata oluştu: " . implode(', ', $errors);
    }
}

// Mevcut ayarları al
$currentSettings = $helper->getAllSettings();
?>

<!-- Header -->
<?php include __DIR__ . '/layout/header.php'; ?>

<style>
        .settings-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .settings-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
        }
        
        .settings-title {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .settings-subtitle {
            color: #7f8c8d;
            font-size: 1.1rem;
        }
        
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .settings-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            border-left: 4px solid #3498db;
        }
        
        .settings-card h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .settings-card h3 i {
            color: #3498db;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: #3498db;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: #27ae60;
            color: white;
        }
        
        .btn-success:hover {
            background: #229954;
        }
        
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        
        .btn-warning:hover {
            background: #e67e22;
        }
        
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .settings-actions {
            margin-top: 30px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
        }
        
        .help-text {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-top: 5px;
        }
        
    </style>

<div class="main-content">
        
        
        <!-- Settings Container -->
        <div class="settings-container">
            <div class="settings-header">
                <h2 class="settings-title">Site Yönetimi</h2>
                <p class="settings-subtitle">Sitenizin genel ayarlarını buradan yönetebilirsiniz</p>
            </div>
            
            <!-- Alerts -->
            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $helper->e($successMessage); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $helper->e($errorMessage); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" enctype="multipart/form-data">
            <div class="settings-grid">
                <!-- Genel Ayarlar -->
                <div class="settings-card">
                    <h3><i class="fas fa-cog"></i> Genel Ayarlar</h3>
                    
                    <div class="form-group">
                        <label for="site_name">Site Adı</label>
                        <input type="text" id="site_name" name="settings[site_name]" value="<?php echo $helper->e($currentSettings['site_name'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="site_description">Site Açıklaması</label>
                        <textarea id="site_description" name="settings[site_description]" placeholder="Site açıklamasını buraya yazın..."><?php echo $helper->e($currentSettings['site_description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="items_per_page">Sayfa Başına İlan Sayısı</label>
                        <select id="items_per_page" name="settings[items_per_page]">
                            <option value="6" <?php echo ($currentSettings['items_per_page'] ?? 12) == 6 ? 'selected' : ''; ?>>6 İlan</option>
                            <option value="12" <?php echo ($currentSettings['items_per_page'] ?? 12) == 12 ? 'selected' : ''; ?>>12 İlan</option>
                            <option value="24" <?php echo ($currentSettings['items_per_page'] ?? 12) == 24 ? 'selected' : ''; ?>>24 İlan</option>
                            <option value="48" <?php echo ($currentSettings['items_per_page'] ?? 12) == 48 ? 'selected' : ''; ?>>48 İlan</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="site_icon">Site İkonu (Favicon)</label>
                        <div class="file-upload-container">
                            <input type="file" id="site_icon" name="site_icon" accept="image/*" onchange="previewIcon(this)">
                            <div class="file-upload-info">
                                <p>Önerilen boyut: 32x32 px veya 16x16 px</p>
                                <p>Desteklenen formatlar: PNG, ICO, JPG, GIF</p>
                            </div>
                            <?php if (!empty($currentSettings['site_icon'])): ?>
                                <div class="current-icon" style="margin-top: 10px;">
                                    <p style="margin-bottom: 5px; font-weight: 600;">Mevcut İkon:</p>
                                    <img src="<?php echo $helper->e($currentSettings['site_icon']); ?>" alt="Mevcut Site İkonu" style="width: 32px; height: 32px; border: 1px solid #ddd; border-radius: 4px; display: block;">
                                </div>
                            <?php endif; ?>
                            <div id="icon-preview" style="display: none; margin-top: 10px;">
                                <p>Yeni İkon Önizleme:</p>
                                <img id="preview-img" src="" alt="Önizleme" style="width: 32px; height: 32px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- İletişim Bilgileri -->
                <div class="settings-card">
                    <h3><i class="fas fa-phone"></i> İletişim Bilgileri</h3>
                    
                    <div class="form-group">
                        <label for="contact_phone">Telefon</label>
                        <input type="tel" id="contact_phone" name="settings[contact_phone]" value="<?php echo $helper->e($currentSettings['contact_phone'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email">E-posta</label>
                        <input type="email" id="contact_email" name="settings[contact_email]" value="<?php echo $helper->e($currentSettings['contact_email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_address">Adres</label>
                        <textarea id="contact_address" name="settings[contact_address]" placeholder="İletişim adresini buraya yazın..."><?php echo $helper->e($currentSettings['contact_address'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <!-- Sosyal Medya -->
                <div class="settings-card">
                    <h3><i class="fab fa-facebook"></i> Sosyal Medya</h3>
                    
                    <div class="form-group">
                        <label for="facebook_url">Facebook</label>
                        <input type="url" id="facebook_url" name="settings[facebook_url]" value="<?php echo $helper->e($currentSettings['facebook_url'] ?? ''); ?>" placeholder="https://facebook.com/yourpage">
                    </div>
                    
                    <div class="form-group">
                        <label for="twitter_url">Twitter</label>
                        <input type="url" id="twitter_url" name="settings[twitter_url]" value="<?php echo $helper->e($currentSettings['twitter_url'] ?? ''); ?>" placeholder="https://twitter.com/yourpage">
                    </div>
                    
                    <div class="form-group">
                        <label for="instagram_url">Instagram</label>
                        <input type="url" id="instagram_url" name="settings[instagram_url]" value="<?php echo $helper->e($currentSettings['instagram_url'] ?? ''); ?>" placeholder="https://instagram.com/yourpage">
                    </div>
                    
                    <div class="form-group">
                        <label for="linkedin_url">LinkedIn</label>
                        <input type="url" id="linkedin_url" name="settings[linkedin_url]" value="<?php echo $helper->e($currentSettings['linkedin_url'] ?? ''); ?>" placeholder="https://linkedin.com/company/yourpage">
                    </div>
                </div>
                
                
                <!-- SEO Ayarları -->
                <div class="settings-card">
                    <h3><i class="fas fa-search"></i> SEO Ayarları</h3>
                    
                    <div class="form-group">
                        <label for="site_keywords">Anahtar Kelimeler</label>
                        <input type="text" id="site_keywords" name="settings[site_keywords]" value="<?php echo $helper->e($currentSettings['site_keywords'] ?? 'emlak, satılık, kiralık, daire, villa, arsa'); ?>" placeholder="Virgülle ayırarak anahtar kelimeleri yazın">
                    </div>
                    
                    <div class="form-group">
                        <label for="google_analytics">Google Analytics Kodu</label>
                        <textarea id="google_analytics" name="settings[google_analytics]" placeholder="GA-XXXXXXXXX-X veya GTM-XXXXXXX"><?php echo $helper->e($currentSettings['google_analytics'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="google_maps_api">Google Maps API Anahtarı</label>
                        <input type="text" id="google_maps_api" name="settings[google_maps_api]" value="<?php echo $helper->e($currentSettings['google_maps_api'] ?? ''); ?>" placeholder="AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX">
                    </div>
                </div>
                
            </div>
            
            </div>
            
            <!-- Actions -->
            <div class="settings-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Ayarları Kaydet
                </button>
                
                
            </div>
            </form>
        </div>
    </div>

<script>
        function saveSettings() {
            // Form verilerini topla
            const formData = new FormData();
            
            // Tüm input'ları topla
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    formData.append(input.name, input.checked ? '1' : '0');
                } else {
                    formData.append(input.name, input.value);
                }
            });
            
            // Loading state
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Kaydediliyor...';
            btn.disabled = true;
            
            // Simüle edilmiş kaydetme
            setTimeout(() => {
                showAlert('Ayarlar başarıyla kaydedildi!', 'success');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 2000);
        }
        
        function testConnection() {
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Test Ediliyor...';
            btn.disabled = true;
            
            setTimeout(() => {
                showAlert('Bağlantı testi başarılı!', 'success');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 1500);
        }
        
        function clearCache() {
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Temizleniyor...';
            btn.disabled = true;
            
            setTimeout(() => {
                showAlert('Önbellek başarıyla temizlendi!', 'success');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 1000);
        }
        
        
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                ${message}
            `;
            
            const container = document.querySelector('.settings-container');
            container.insertBefore(alertDiv, container.firstChild);
            
            setTimeout(() => {
                alertDiv.style.opacity = '0';
                setTimeout(() => {
                    alertDiv.remove();
                }, 300);
            }, 3000);
        }
        
        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
        
        function previewIcon(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/x-icon'];
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Desteklenmeyen dosya formatı. PNG, JPG, GIF, ICO formatları desteklenir.');
                    input.value = '';
                    return;
                }
                
                if (file.size > 2 * 1024 * 1024) {
                    alert('Dosya boyutu çok büyük. Maksimum 2MB olmalıdır.');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('icon-preview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('icon-preview').style.display = 'none';
            }
        }
    </script>

<!-- Footer -->
<?php include __DIR__ . '/layout/footer.php'; ?>
