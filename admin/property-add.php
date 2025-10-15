<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Helper.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Property.php';
require_once __DIR__ . '/../classes/Database.php';

$helper = Helper::getInstance();
$adminUser = new AdminUser();
$property = new Property();
$db = Database::getInstance()->getConnection();

$pageTitle = 'Yeni İlan Ekle';
$success = '';
$error = '';

// Şehir ve ilçe listeleri
$cities = $db->query("SELECT * FROM cities ORDER BY name")->fetchAll();
$districts = [];

// Form işleme
if ($_POST) {
    $data = [
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'property_type' => $_POST['property_type'] ?? '',
        'transaction_type' => $_POST['transaction_type'] ?? '',
        'price' => (float)($_POST['price'] ?? 0),
        'room_count' => (int)($_POST['room_count'] ?? 0),
        'living_room_count' => (int)($_POST['living_room_count'] ?? 0),
        'bathroom_count' => (int)($_POST['bathroom_count'] ?? 0),
        'floor' => (int)($_POST['floor'] ?? 0),
        'building_age' => (int)($_POST['building_age'] ?? 0),
        'heating_type' => $_POST['heating_type'] ?? '',
        'city_id' => (int)($_POST['city_id'] ?? 0),
        'district_id' => (int)($_POST['district_id'] ?? 0),
        'address' => $_POST['address'] ?? '',
        'area' => (float)($_POST['area'] ?? 0),
        'contact_name' => $_POST['contact_name'] ?? '',
        'contact_phone' => $_POST['contact_phone'] ?? '',
        'contact_email' => $_POST['contact_email'] ?? '',
        'featured' => isset($_POST['featured']) ? 1 : 0,
        'urgent' => isset($_POST['urgent']) ? 1 : 0,
        'status' => $_POST['status'] ?? 'active'
    ];
    
    // Validation
    if (empty($data['title'])) {
        $error = 'Başlık alanı zorunludur!';
    } elseif (empty($data['property_type'])) {
        $error = 'Emlak tipi seçimi zorunludur!';
    } elseif (empty($data['transaction_type'])) {
        $error = 'İşlem tipi seçimi zorunludur!';
    } elseif ($data['price'] <= 0) {
        $error = 'Geçerli bir fiyat giriniz!';
    } elseif ($data['city_id'] <= 0) {
        $error = 'Şehir seçimi zorunludur!';
    } elseif ($data['district_id'] <= 0) {
        $error = 'İlçe seçimi zorunludur!';
    } else {
        // Slug oluştur
        $data['slug'] = $property->createSlug($data['title']);
        
        // Resim yükleme işlemi
        $uploadedImages = [];
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../uploads/properties/';
            
            // Upload klasörünü oluştur
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $fileExtension = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    
                    if (in_array($fileExtension, $allowedExtensions)) {
                        $fileName = 'property_' . time() . '_' . $i . '_' . uniqid() . '.' . $fileExtension;
                        $filePath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $filePath)) {
                            $uploadedImages[] = $helper->getBaseUrl() . '/uploads/properties/' . $fileName;
                        }
                    }
                }
            }
        }
        
        // Ana resim seçimi
        if (!empty($uploadedImages)) {
            $data['main_image'] = $uploadedImages[0];
            $data['images'] = json_encode($uploadedImages);
        }
        
        // Özellikler
        $features = [];
        if (isset($_POST['features'])) {
            $features = $_POST['features'];
        }
        $data['features'] = json_encode($features);
        
        // İlan oluştur
        if ($property->create($data)) {
            $success = 'İlan başarıyla oluşturuldu.';
            // Formu temizle
            $_POST = [];
            $uploadedImages = [];
        } else {
            $error = 'İlan oluşturulurken hata oluştu.';
        }
    }
}

// Seçilen şehre göre ilçeleri getir
if (isset($_POST['city_id']) && $_POST['city_id'] > 0) {
    $districts = $db->prepare("SELECT * FROM districts WHERE city_id = ? ORDER BY name");
    $districts->execute([$_POST['city_id']]);
    $districts = $districts->fetchAll();
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
        <h2>Yeni İlan Ekle</h2>
        <a href="properties.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>
    
    <form method="POST" enctype="multipart/form-data" id="propertyForm">
        <!-- Temel Bilgiler -->
        <h3 style="margin-bottom: 20px; color: #2c3e50; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">Temel Bilgiler</h3>
        
        <div class="form-group full-width">
            <label for="title" class="required">İlan Başlığı</label>
            <input type="text" id="title" name="title" class="form-control" 
                   value="<?php echo isset($_POST['title']) ? $helper->e($_POST['title']) : ''; ?>" required>
        </div>
        
        <div class="form-group full-width">
            <label for="description">Açıklama</label>
            <textarea id="description" name="description" class="form-control" rows="4"><?php echo isset($_POST['description']) ? $helper->e($_POST['description']) : ''; ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="property_type" class="required">Emlak Tipi</label>
                <select id="property_type" name="property_type" class="form-control" required>
                    <option value="">Seçiniz</option>
                    <option value="daire" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] === 'daire') ? 'selected' : ''; ?>>Daire</option>
                    <option value="villa" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] === 'villa') ? 'selected' : ''; ?>>Villa</option>
                    <option value="arsa" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] === 'arsa') ? 'selected' : ''; ?>>Arsa</option>
                    <option value="isyeri" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] === 'isyeri') ? 'selected' : ''; ?>>İşyeri</option>
                    <option value="ofis" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] === 'ofis') ? 'selected' : ''; ?>>Ofis</option>
                    <option value="depo" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] === 'depo') ? 'selected' : ''; ?>>Depo</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="transaction_type" class="required">İşlem Tipi</label>
                <select id="transaction_type" name="transaction_type" class="form-control" required>
                    <option value="">Seçiniz</option>
                    <option value="satilik" <?php echo (isset($_POST['transaction_type']) && $_POST['transaction_type'] === 'satilik') ? 'selected' : ''; ?>>Satılık</option>
                    <option value="kiralik" <?php echo (isset($_POST['transaction_type']) && $_POST['transaction_type'] === 'kiralik') ? 'selected' : ''; ?>>Kiralık</option>
                    <option value="gunluk-kiralik" <?php echo (isset($_POST['transaction_type']) && $_POST['transaction_type'] === 'gunluk-kiralik') ? 'selected' : ''; ?>>Günlük Kiralık</option>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="price" class="required">Fiyat (₺)</label>
                <input type="number" id="price" name="price" class="form-control" 
                       value="<?php echo isset($_POST['price']) ? $helper->e($_POST['price']) : ''; ?>" 
                       min="0" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="area">Alan (m²)</label>
                <input type="number" id="area" name="area" class="form-control" 
                       value="<?php echo isset($_POST['area']) ? $helper->e($_POST['area']) : ''; ?>" 
                       min="0" step="0.01">
            </div>
        </div>
        
        <!-- Oda Bilgileri -->
        <h3 style="margin: 30px 0 20px; color: #2c3e50; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">Oda Bilgileri</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="room_count">Oda Sayısı</label>
                <input type="number" id="room_count" name="room_count" class="form-control" 
                       value="<?php echo isset($_POST['room_count']) ? $helper->e($_POST['room_count']) : '0'; ?>" 
                       min="0">
            </div>
            
            <div class="form-group">
                <label for="living_room_count">Salon Sayısı</label>
                <input type="number" id="living_room_count" name="living_room_count" class="form-control" 
                       value="<?php echo isset($_POST['living_room_count']) ? $helper->e($_POST['living_room_count']) : '0'; ?>" 
                       min="0">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="bathroom_count">Banyo Sayısı</label>
                <input type="number" id="bathroom_count" name="bathroom_count" class="form-control" 
                       value="<?php echo isset($_POST['bathroom_count']) ? $helper->e($_POST['bathroom_count']) : '0'; ?>" 
                       min="0">
            </div>
            
            <div class="form-group">
                <label for="floor">Kat</label>
                <input type="number" id="floor" name="floor" class="form-control" 
                       value="<?php echo isset($_POST['floor']) ? $helper->e($_POST['floor']) : '0'; ?>" 
                       min="0">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="building_age">Bina Yaşı</label>
                <input type="number" id="building_age" name="building_age" class="form-control" 
                       value="<?php echo isset($_POST['building_age']) ? $helper->e($_POST['building_age']) : '0'; ?>" 
                       min="0">
            </div>
            
            <div class="form-group">
                <label for="heating_type">Isıtma Tipi</label>
                <select id="heating_type" name="heating_type" class="form-control">
                    <option value="">Seçiniz</option>
                    <option value="Doğalgaz" <?php echo (isset($_POST['heating_type']) && $_POST['heating_type'] === 'Doğalgaz') ? 'selected' : ''; ?>>Doğalgaz</option>
                    <option value="Kombi" <?php echo (isset($_POST['heating_type']) && $_POST['heating_type'] === 'Kombi') ? 'selected' : ''; ?>>Kombi</option>
                    <option value="Soba" <?php echo (isset($_POST['heating_type']) && $_POST['heating_type'] === 'Soba') ? 'selected' : ''; ?>>Soba</option>
                    <option value="Klima" <?php echo (isset($_POST['heating_type']) && $_POST['heating_type'] === 'Klima') ? 'selected' : ''; ?>>Klima</option>
                </select>
            </div>
        </div>
        
        <!-- Konum Bilgileri -->
        <h3 style="margin: 30px 0 20px; color: #2c3e50; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">Konum Bilgileri</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="city_id" class="required">Şehir</label>
                <select id="city_id" name="city_id" class="form-control" required onchange="loadDistricts(this.value);">
                    <option value="">Seçiniz</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?php echo $city['id']; ?>" <?php echo (isset($_POST['city_id']) && $_POST['city_id'] == $city['id']) ? 'selected' : ''; ?>><?php echo $helper->e($city['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="district_id" class="required">İlçe</label>
                <select id="district_id" name="district_id" class="form-control" required onchange="loadNeighborhoods(this.value)">
                    <option value="">Önce şehir seçiniz</option>
                    <?php foreach ($districts as $district): ?>
                        <option value="<?php echo $district['id']; ?>" <?php echo (isset($_POST['district_id']) && $_POST['district_id'] == $district['id']) ? 'selected' : ''; ?>><?php echo $helper->e($district['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="neighborhood_id">Belde</label>
                <select id="neighborhood_id" name="neighborhood_id" class="form-control">
                    <option value="">Önce ilçe seçiniz</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="address">Adres</label>
                <textarea id="address" name="address" class="form-control" rows="2"><?php echo isset($_POST['address']) ? $helper->e($_POST['address']) : ''; ?></textarea>
            </div>
        </div>
        
        <!-- Resim Yükleme -->
        <h3 style="margin: 30px 0 20px; color: #2c3e50; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">Resimler</h3>
        
        <div class="form-group full-width">
            <label for="images">İlan Resimleri</label>
            <div class="file-upload-container" onclick="document.getElementById('images').click()">
                <div class="file-upload-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <div class="file-upload-text">
                    Resim dosyalarını seçin veya buraya sürükleyin
                </div>
                <button type="button" class="file-upload-btn">
                    <i class="fas fa-folder-open"></i> Dosya Seç
                </button>
                <div class="file-info">
                    Desteklenen formatlar: JPG, PNG, GIF, WebP (Max: 5MB per file)
                </div>
            </div>
            
            <input type="file" id="images" name="images[]" accept="image/*" multiple style="display: none;" onchange="previewImages(this)">
            
            <div id="imagePreviewContainer" class="image-preview-container"></div>
        </div>
        
        <!-- Özellikler -->
        <h3 style="margin: 30px 0 20px; color: #2c3e50; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">Özellikler</h3>
        
        <div class="form-group full-width">
            <label>İlan Özellikleri</label>
            <div class="features-container">
                <?php 
                $features = [
                    'Asansör', 'Güvenlik', 'Otopark', 'Balkon', 'Eşyalı', 'Klima',
                    'Havuz', 'Bahçe', 'Deniz Manzarası', 'Şehir Manzarası', 'Ana Cadde',
                    'Metro Yakın', 'Market Yakın', 'Okul Yakın', 'Hastane Yakın'
                ];
                foreach ($features as $feature): ?>
                    <div class="feature-item">
                        <input type="checkbox" id="feature_<?php echo $feature; ?>" name="features[]" value="<?php echo $feature; ?>"
                               <?php echo (isset($_POST['features']) && in_array($feature, $_POST['features'])) ? 'checked' : ''; ?>>
                        <label for="feature_<?php echo $feature; ?>"><?php echo $feature; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- İletişim Bilgileri -->
        <h3 style="margin: 30px 0 20px; color: #2c3e50; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">İletişim Bilgileri</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="contact_name">İletişim Adı</label>
                <input type="text" id="contact_name" name="contact_name" class="form-control" 
                       value="<?php echo isset($_POST['contact_name']) ? $helper->e($_POST['contact_name']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="contact_phone">Telefon</label>
                <input type="tel" id="contact_phone" name="contact_phone" class="form-control" 
                       value="<?php echo isset($_POST['contact_phone']) ? $helper->e($_POST['contact_phone']) : ''; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="contact_email">E-posta</label>
            <input type="email" id="contact_email" name="contact_email" class="form-control" 
                   value="<?php echo isset($_POST['contact_email']) ? $helper->e($_POST['contact_email']) : ''; ?>">
        </div>
        
        <!-- Durum ve Seçenekler -->
        <h3 style="margin: 30px 0 20px; color: #2c3e50; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">Durum ve Seçenekler</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label for="status">Durum</label>
                <select id="status" name="status" class="form-control">
                    <option value="active" <?php echo (isset($_POST['status']) && $_POST['status'] === 'active') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="inactive" <?php echo (isset($_POST['status']) && $_POST['status'] === 'inactive') ? 'selected' : ''; ?>>Pasif</option>
                    <option value="pending" <?php echo (isset($_POST['status']) && $_POST['status'] === 'pending') ? 'selected' : ''; ?>>Beklemede</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="featured" value="1" <?php echo (isset($_POST['featured'])) ? 'checked' : ''; ?>>
                    Öne Çıkan İlan
                </label>
                <br>
                <label>
                    <input type="checkbox" name="urgent" value="1" <?php echo (isset($_POST['urgent'])) ? 'checked' : ''; ?>>
                    Acil İlan
                </label>
            </div>
        </div>
        
        <div class="form-actions">
            <a href="properties.php" class="btn btn-secondary">
                <i class="fas fa-times"></i> İptal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> İlanı Kaydet
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
.image-preview-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.image-preview {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.image-preview img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.image-preview .remove-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(231, 76, 60, 0.8);
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.image-preview .remove-btn:hover {
    background: rgba(231, 76, 60, 1);
}

/* Features */
.features-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
    margin-top: 10px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.feature-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #f8f9fa;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
}
</style>

<?php
// Layout footer'ı dahil et
require_once __DIR__ . '/layout/footer.php';
?>