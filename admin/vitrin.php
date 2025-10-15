<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Helper.php';
require_once __DIR__ . '/../classes/Property.php';

// Admin giriş kontrolü
$adminUser = new AdminUser();
if (!$adminUser->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$helper = Helper::getInstance();
$property = new Property();
$db = Database::getInstance();

$pageTitle = 'Vitrin İlanları';

// Vitrin ilanlarını güncelle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_vitrin') {
        $vitrinIds = $_POST['vitrin_ids'] ?? [];
        
        // Önce tüm ilanları vitrin'den çıkar
        $stmt = $db->getConnection()->prepare("UPDATE properties SET featured = 0");
        $stmt->execute();
        
        // Seçilen ilanları vitrin'e ekle
        if (!empty($vitrinIds)) {
            $placeholders = str_repeat('?,', count($vitrinIds) - 1) . '?';
            $stmt = $db->getConnection()->prepare("UPDATE properties SET featured = 1 WHERE id IN ($placeholders)");
            $stmt->execute($vitrinIds);
        }
        
        $success = 'Vitrin ilanları başarıyla güncellendi!';
    }
}

// Tüm ilanları al
$allProperties = $property->getAll(['status' => 'active']);
$vitrinProperties = $property->getAll(['status' => 'active', 'featured' => 1]);

require_once __DIR__ . '/layout/header.php';
?>

<div class="action-bar">
    <h3><i class="fas fa-star"></i> Vitrin İlanları Yönetimi</h3>
    <div>
        <span class="badge badge-info">Toplam İlan: <?php echo count($allProperties); ?></span>
        <span class="badge badge-warning">Vitrin İlanı: <?php echo count($vitrinProperties); ?></span>
    </div>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo $helper->e($success); ?>
    </div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="">
        <input type="hidden" name="action" value="update_vitrin">
        
        <div class="form-group">
            <label>Vitrin İlanları Seçin</label>
            <p class="help-text">Ana sayfada vitrin bölümünde görüntülenecek ilanları seçin. Maksimum 9 ilan seçebilirsiniz.</p>
        </div>
        
        <div class="properties-grid">
            <?php foreach ($allProperties as $prop): ?>
                <div class="property-card <?php echo $prop['featured'] ? 'featured' : ''; ?>">
                    <div class="property-image">
                        <?php if (!empty($prop['main_image'])): ?>
                            <img src="<?php echo $helper->e($prop['main_image']); ?>" alt="<?php echo $helper->e($prop['title']); ?>">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-home"></i>
                                <span>Resim Yok</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="property-badges">
                            <?php if ($prop['featured']): ?>
                                <span class="badge badge-warning">Vitrin</span>
                            <?php endif; ?>
                            <?php if ($prop['urgent']): ?>
                                <span class="badge badge-danger">Acil</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="property-info">
                        <h4><?php echo $helper->e($prop['title']); ?></h4>
                        <p class="property-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo $helper->e($prop['city_name'] ?? 'Şehir Belirtilmemiş'); ?>
                            <?php if (!empty($prop['district_name'])): ?>
                                / <?php echo $helper->e($prop['district_name']); ?>
                            <?php endif; ?>
                        </p>
                        <p class="property-price">₺<?php echo number_format($prop['price'], 0, ',', '.'); ?></p>
                        <p class="property-type">
                            <i class="fas fa-tag"></i>
                            <?php echo ucfirst($helper->e($prop['property_type'])); ?> - 
                            <?php echo ucfirst($helper->e($prop['transaction_type'])); ?>
                        </p>
                        
                        <div class="property-actions">
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       name="vitrin_ids[]" 
                                       value="<?php echo $prop['id']; ?>"
                                       <?php echo $prop['featured'] ? 'checked' : ''; ?>
                                       onchange="updateVitrinCount(this)">
                                <span class="checkmark"></span>
                                Vitrin'e Ekle
                            </label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Vitrin İlanlarını Güncelle
            </button>
            <a href="properties.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                İlanlara Dön
            </a>
        </div>
    </form>
</div>

<style>
.properties-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.property-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.property-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.property-card.featured {
    border-color: #f39c12;
    box-shadow: 0 2px 15px rgba(243, 156, 18, 0.3);
}

.property-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.property-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    color: #6c757d;
}

.no-image i {
    font-size: 2rem;
    margin-bottom: 10px;
}

.property-badges {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
}

.badge-warning {
    background: #f39c12;
}

.badge-danger {
    background: #e74c3c;
}

.badge-info {
    background: #3498db;
}

.property-info {
    padding: 20px;
}

.property-info h4 {
    color: #2c3e50;
    margin-bottom: 10px;
    font-size: 1.1rem;
    line-height: 1.4;
}

.property-location {
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.property-location i {
    margin-right: 5px;
}

.property-price {
    color: #27ae60;
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.property-type {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 15px;
}

.property-type i {
    margin-right: 5px;
}

.property-actions {
    border-top: 1px solid #ecf0f1;
    padding-top: 15px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-weight: 600;
    color: #2c3e50;
}

.checkbox-label input[type="checkbox"] {
    margin-right: 10px;
    transform: scale(1.2);
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #ecf0f1;
    display: flex;
    gap: 15px;
    justify-content: center;
}

@media (max-width: 768px) {
    .properties-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
function updateVitrinCount(clickedCheckbox) {
    // Sadece checkbox seçildiğinde limit kontrolü yap
    if (clickedCheckbox.checked) {
        const checkboxes = document.querySelectorAll('input[name="vitrin_ids[]"]:checked');
        const count = checkboxes.length;
        
        if (count > 9) {
            alert('Maksimum 9 ilan seçebilirsiniz!');
            clickedCheckbox.checked = false;
            return;
        }
    }
    
    // Seçilen ilanları vurgula
    document.querySelectorAll('.property-card').forEach(card => {
        const checkbox = card.querySelector('input[name="vitrin_ids[]"]');
        if (checkbox.checked) {
            card.classList.add('featured');
        } else {
            card.classList.remove('featured');
        }
    });
}

// Sayfa yüklendiğinde sadece vurgulamayı yap, uyarı verme
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.property-card').forEach(card => {
        const checkbox = card.querySelector('input[name="vitrin_ids[]"]');
        if (checkbox.checked) {
            card.classList.add('featured');
        } else {
            card.classList.remove('featured');
        }
    });
});
</script>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
