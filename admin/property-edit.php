<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Helper.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Property.php';
require_once __DIR__ . '/../classes/Database.php';

$helper = Helper::getInstance();
$property = new Property();
$db = Database::getInstance()->getConnection();

$pageTitle = 'İlan Düzenle';
$success = '';
$error = '';

// İlan ID kontrolü
$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: /emlak/admin/ilanlar');
    exit;
}

// Mevcut ilan bilgilerini getir
$currentProperty = $property->getById($id);
if (!$currentProperty) {
    header('Location: /emlak/admin/ilanlar');
    exit;
}

// Mevcut resimleri getir (JSON'dan)
$existingImages = [];
if (!empty($currentProperty['images'])) {
    $existingImages = json_decode($currentProperty['images'], true) ?: [];
}

// Şehir ve ilçe listeleri
$cities = $db->query("SELECT * FROM cities ORDER BY name")->fetchAll();
$districts = $db->prepare("SELECT * FROM districts WHERE city_id = ? ORDER BY name");
$districts->execute([$currentProperty['city_id']]);
$districts = $districts->fetchAll();

// Resim silme işlemi
if (isset($_POST['delete_image_index'])) {
    $deleteIndex = (int)$_POST['delete_image_index'];
    if ($deleteIndex >= 0 && $deleteIndex < count($existingImages)) {
        // Resmi diziden kaldır
        unset($existingImages[$deleteIndex]);
        $existingImages = array_values($existingImages); // Index'leri yeniden düzenle
        
        // Ana resim silinmişse, yeni ana resim belirle
        $newMainImage = !empty($existingImages) ? $existingImages[0] : null;
        
        // Veritabanını güncelle
        $updateData = [
            'images' => json_encode($existingImages),
            'main_image' => $newMainImage
        ];
        
        if ($property->update($id, $updateData)) {
            $success = 'Resim başarıyla silindi!';
            $currentProperty = $property->getById($id);
        } else {
            $error = 'Resim silinirken bir hata oluştu!';
        }
    }
}

// Form işleme
if ($_POST && !isset($_POST['delete_image_index'])) {
    // Önce resim yükleme işlemini yap
    $uploadedImages = [];
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = __DIR__ . '/../uploads/properties/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        foreach ($_FILES['images']['name'] as $key => $filename) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFilename = 'property_' . time() . '_' . $key . '_' . uniqid() . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $newFilename;
                    
                    if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $uploadPath)) {
                        $uploadedImages[] = $helper->getBaseUrl() . '/uploads/properties/' . $newFilename;
                    }
                }
            }
        }
    }
    
    // Form verilerini al
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
    
    // Validation - sadece kritik alanlar
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
    }
    // district_id kontrolünü kaldırdık - opsiyonel olabilir
    
    // Resimleri birleştir
    $allImages = $existingImages;
    if (!empty($uploadedImages)) {
        $allImages = array_merge($allImages, $uploadedImages);
        $data['main_image'] = $uploadedImages[0]; // Yeni ana resim
    }
    
    // Resimleri JSON olarak kaydet
    $data['images'] = json_encode($allImages);
    
    // İlan güncelleme
    if ($property->update($id, $data)) {
        $success = 'İlan başarıyla güncellendi!';
        // Güncellenmiş bilgileri tekrar getir
        $currentProperty = $property->getById($id);
        // Mevcut resimleri güncelle
        $existingImages = $allImages;
    } else {
        $error = 'İlan güncellenirken bir hata oluştu!';
    }
}

// Admin layout header
require_once __DIR__ . '/layout/header.php';
?>

<div class="admin-content">
    <div class="content-header">
        <h1>İlan Düzenle</h1>
        <div class="breadcrumb">
            <a href="/emlak/admin">Ana Sayfa</a> / 
            <a href="/emlak/admin/ilanlar">İlanlar</a> / 
            <span>Düzenle</span>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $helper->e($success); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $helper->e($error); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="" enctype="multipart/form-data" class="property-form">
            <div class="form-section">
                <h3>Genel Bilgiler</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">İlan Başlığı *</label>
                        <input type="text" id="title" name="title" value="<?php echo $helper->e($currentProperty['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Açıklama</label>
                        <textarea id="description" name="description" rows="4"><?php echo $helper->e($currentProperty['description']); ?></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="property_type">Emlak Tipi *</label>
                        <select id="property_type" name="property_type" required>
                            <option value="">Seçiniz</option>
                            <option value="daire" <?php echo $currentProperty['property_type'] === 'daire' ? 'selected' : ''; ?>>Daire</option>
                            <option value="villa" <?php echo $currentProperty['property_type'] === 'villa' ? 'selected' : ''; ?>>Villa</option>
                            <option value="arsa" <?php echo $currentProperty['property_type'] === 'arsa' ? 'selected' : ''; ?>>Arsa</option>
                            <option value="isyeri" <?php echo $currentProperty['property_type'] === 'isyeri' ? 'selected' : ''; ?>>İşyeri</option>
                            <option value="ofis" <?php echo $currentProperty['property_type'] === 'ofis' ? 'selected' : ''; ?>>Ofis</option>
                            <option value="depo" <?php echo $currentProperty['property_type'] === 'depo' ? 'selected' : ''; ?>>Depo</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="transaction_type">İşlem Tipi *</label>
                        <select id="transaction_type" name="transaction_type" required>
                            <option value="">Seçiniz</option>
                            <option value="satilik" <?php echo $currentProperty['transaction_type'] === 'satilik' ? 'selected' : ''; ?>>Satılık</option>
                            <option value="kiralik" <?php echo $currentProperty['transaction_type'] === 'kiralik' ? 'selected' : ''; ?>>Kiralık</option>
                            <option value="gunluk-kiralik" <?php echo $currentProperty['transaction_type'] === 'gunluk-kiralik' ? 'selected' : ''; ?>>Günlük Kiralık</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Fiyat (TL) *</label>
                        <input type="number" id="price" name="price" value="<?php echo $currentProperty['price']; ?>" min="0" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="area">Alan (m²)</label>
                        <input type="number" id="area" name="area" value="<?php echo $currentProperty['area']; ?>" min="0" step="0.01">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Özellikler</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="room_count">Oda Sayısı</label>
                        <select id="room_count" name="room_count">
                            <option value="">Seçiniz</option>
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo $currentProperty['room_count'] == $i ? 'selected' : ''; ?>><?php echo $i; ?> Oda</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="living_room_count">Salon Sayısı</label>
                        <select id="living_room_count" name="living_room_count">
                            <option value="">Seçiniz</option>
                            <?php for ($i = 0; $i <= 3; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo $currentProperty['living_room_count'] == $i ? 'selected' : ''; ?>><?php echo $i; ?> Salon</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bathroom_count">Banyo Sayısı</label>
                        <select id="bathroom_count" name="bathroom_count">
                            <option value="">Seçiniz</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo $currentProperty['bathroom_count'] == $i ? 'selected' : ''; ?>><?php echo $i; ?> Banyo</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="floor">Kat</label>
                        <input type="number" id="floor" name="floor" value="<?php echo $currentProperty['floor']; ?>" min="-5" max="100">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="building_age">Bina Yaşı</label>
                        <select id="building_age" name="building_age">
                            <option value="">Seçiniz</option>
                            <option value="0" <?php echo $currentProperty['building_age'] == 0 ? 'selected' : ''; ?>>Sıfır</option>
                            <option value="1" <?php echo $currentProperty['building_age'] == 1 ? 'selected' : ''; ?>>1-5 Yıl</option>
                            <option value="6" <?php echo $currentProperty['building_age'] == 6 ? 'selected' : ''; ?>>6-10 Yıl</option>
                            <option value="11" <?php echo $currentProperty['building_age'] == 11 ? 'selected' : ''; ?>>11-20 Yıl</option>
                            <option value="21" <?php echo $currentProperty['building_age'] == 21 ? 'selected' : ''; ?>>20+ Yıl</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="heating_type">Isıtma Tipi</label>
                        <select id="heating_type" name="heating_type">
                            <option value="">Seçiniz</option>
                            <option value="dogalgaz" <?php echo $currentProperty['heating_type'] === 'dogalgaz' ? 'selected' : ''; ?>>Doğalgaz</option>
                            <option value="kombi" <?php echo $currentProperty['heating_type'] === 'kombi' ? 'selected' : ''; ?>>Kombi</option>
                            <option value="klima" <?php echo $currentProperty['heating_type'] === 'klima' ? 'selected' : ''; ?>>Klima</option>
                            <option value="soba" <?php echo $currentProperty['heating_type'] === 'soba' ? 'selected' : ''; ?>>Soba</option>
                            <option value="yok" <?php echo $currentProperty['heating_type'] === 'yok' ? 'selected' : ''; ?>>Yok</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Konum Bilgileri</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="city_id">Şehir *</label>
                        <select id="city_id" name="city_id" required onchange="loadDistricts(this.value)">
                            <option value="">Seçiniz</option>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?php echo $city['id']; ?>" <?php echo $currentProperty['city_id'] == $city['id'] ? 'selected' : ''; ?>><?php echo $helper->e($city['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="district_id">İlçe *</label>
                        <select id="district_id" name="district_id" required>
                            <option value="">Önce şehir seçiniz</option>
                            <?php foreach ($districts as $district): ?>
                                <option value="<?php echo $district['id']; ?>" <?php echo $currentProperty['district_id'] == $district['id'] ? 'selected' : ''; ?>><?php echo $helper->e($district['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Adres</label>
                    <textarea id="address" name="address" rows="2"><?php echo $helper->e($currentProperty['address']); ?></textarea>
                </div>
            </div>

            <div class="form-section">
                <h3>İletişim Bilgileri</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_name">İletişim Adı</label>
                        <input type="text" id="contact_name" name="contact_name" value="<?php echo $helper->e($currentProperty['contact_name']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_phone">Telefon</label>
                        <input type="tel" id="contact_phone" name="contact_phone" value="<?php echo $helper->e($currentProperty['contact_phone']); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="contact_email">E-posta</label>
                    <input type="email" id="contact_email" name="contact_email" value="<?php echo $helper->e($currentProperty['contact_email']); ?>">
                </div>
            </div>

            <div class="form-section">
                <h3>Resimler</h3>
                
                <?php if (!empty($existingImages)): ?>
                    <div class="existing-images">
                        <h4>Mevcut Resimler</h4>
                        <div class="image-grid">
                            <?php foreach ($existingImages as $index => $imageUrl): ?>
                                <div class="image-item">
                                    <img src="<?php echo $helper->e($imageUrl); ?>" alt="İlan Resmi">
                                    <div class="image-actions">
                                        <button type="button" class="btn-delete" onclick="deleteImage(<?php echo $index; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="images">Yeni Resimler Ekle</label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*">
                    <small>Birden fazla resim seçebilirsiniz. Desteklenen formatlar: JPG, PNG, GIF</small>
                </div>
            </div>

            <div class="form-section">
                <h3>Diğer Ayarlar</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Durum</label>
                        <select id="status" name="status">
                            <option value="active" <?php echo $currentProperty['status'] === 'active' ? 'selected' : ''; ?>>Aktif</option>
                            <option value="inactive" <?php echo $currentProperty['status'] === 'inactive' ? 'selected' : ''; ?>>Pasif</option>
                            <option value="sold" <?php echo $currentProperty['status'] === 'sold' ? 'selected' : ''; ?>>Satıldı</option>
                            <option value="rented" <?php echo $currentProperty['status'] === 'rented' ? 'selected' : ''; ?>>Kiralandı</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group checkbox-group">
                        <label>
                            <input type="checkbox" name="featured" value="1" <?php echo $currentProperty['featured'] ? 'checked' : ''; ?>>
                            <span class="checkmark"></span>
                            Öne Çıkan İlan
                        </label>
                    </div>
                    
                    <div class="form-group checkbox-group">
                        <label>
                            <input type="checkbox" name="urgent" value="1" <?php echo $currentProperty['urgent'] ? 'checked' : ''; ?>>
                            <span class="checkmark"></span>
                            Acil İlan
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    İlanı Güncelle
                </button>
                <a href="/emlak/admin/ilanlar" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Geri Dön
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function loadDistricts(cityId) {
    if (!cityId) {
        document.getElementById('district_id').innerHTML = '<option value="">Önce şehir seçiniz</option>';
        return;
    }
    
    fetch(`/emlak/api/districts.php?city_id=${cityId}`)
        .then(response => response.json())
        .then(data => {
            const districtSelect = document.getElementById('district_id');
            districtSelect.innerHTML = '<option value="">İlçe Seçiniz</option>';
            
            data.forEach(district => {
                const option = document.createElement('option');
                option.value = district.id;
                option.textContent = district.name;
                districtSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('İlçeler yüklenirken hata:', error);
        });
}

function deleteImage(imageIndex) {
    if (confirm('Bu resmi silmek istediğinizden emin misiniz?')) {
        // Mevcut resimlerden belirtilen index'i kaldır
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_image_index';
        input.value = imageIndex;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<style>
/* Property Edit Sayfası Özel CSS */
.form-container {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px;
    margin: 20px 0;
}

.property-form {
    max-width: 100%;
}

.form-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e9ecef;
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.form-section h3 {
    color: #495057;
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #007bff;
    display: inline-block;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #495057;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #6c757d;
    font-size: 12px;
}

.checkbox-group {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.checkbox-group label {
    display: flex;
    align-items: center;
    cursor: pointer;
    margin-bottom: 0;
}

.checkbox-group input[type="checkbox"] {
    width: auto;
    margin-right: 10px;
    transform: scale(1.2);
}

/* Resim Yönetimi */
.existing-images {
    margin-bottom: 30px;
}

.existing-images h4 {
    color: #495057;
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.image-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.image-item:hover {
    transform: translateY(-2px);
}

.image-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    display: block;
}

.image-actions {
    position: absolute;
    top: 5px;
    right: 5px;
}

.btn-delete {
    background: rgba(220, 53, 69, 0.9);
    border: none;
    color: white;
    padding: 5px 8px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    transition: background 0.3s ease;
}

.btn-delete:hover {
    background: rgba(220, 53, 69, 1);
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-start;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
    transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .image-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
    }
    
    .form-container {
        padding: 20px;
        margin: 10px 0;
    }
}

@media (max-width: 480px) {
    .form-container {
        padding: 15px;
    }
    
    .form-section h3 {
        font-size: 1.1rem;
    }
    
    .image-grid {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    }
}
</style>

<?php
// Admin layout footer
require_once __DIR__ . '/layout/footer.php';
?>
