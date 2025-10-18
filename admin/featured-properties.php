<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Helper.php';
require_once __DIR__ . '/../classes/Property.php';

$adminUser = new AdminUser();
if (!$adminUser->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$helper = Helper::getInstance();
$property = new Property();

$pageTitle = "Öne Çıkan İlanlar Yönetimi";

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $highlightedIds = $_POST['highlighted_ids'] ?? [];
    
    if (count($highlightedIds) > 12) {
        $error = 'Maksimum 12 ilan seçebilirsiniz!';
    } else {
        try {
            // Tüm ilanların featured_highlighted durumunu sıfırla
            $db = Database::getInstance();
            $stmt = $db->getConnection()->prepare("UPDATE properties SET featured_highlighted = 0");
            $stmt->execute();

            // Seçilen ilanları öne çıkan yap
            if (!empty($highlightedIds)) {
                $placeholders = str_repeat('?,', count($highlightedIds) - 1) . '?';
                $stmt = $db->getConnection()->prepare("UPDATE properties SET featured_highlighted = 1 WHERE id IN ($placeholders)");
                $stmt->execute($highlightedIds);
            }
            $success = 'Öne çıkan ilanlar başarıyla güncellendi!';
        } catch (Exception $e) {
            $error = 'Öne çıkan ilanlar güncellenirken bir hata oluştu: ' . $e->getMessage();
        }
    }
}

// Tüm ilanları al - öne çıkan ilanlar önce gelsin
$db = Database::getInstance();
$stmt = $db->getConnection()->prepare("
    SELECT p.*, c.name as city_name, d.name as district_name 
    FROM properties p 
    LEFT JOIN cities c ON p.city_id = c.id 
    LEFT JOIN districts d ON p.district_id = d.id 
    WHERE p.status = 'active'
    ORDER BY p.featured_highlighted DESC, p.created_at DESC
");
$stmt->execute();
$allProperties = $stmt->fetchAll();

// Öne çıkan ilanların sayısını al
$stmt = $db->getConnection()->prepare("SELECT COUNT(*) FROM properties WHERE featured_highlighted = 1 AND status = 'active'");
$stmt->execute();
$highlightedCount = $stmt->fetchColumn();

require_once __DIR__ . '/layout/header.php';
?>

<div class="content-wrapper">
    <div class="action-bar">
        <h3><i class="fas fa-star"></i> Öne Çıkan İlanlar Yönetimi</h3>
        <div>
            <span class="badge badge-info">Toplam İlan: <?php echo count($allProperties); ?></span>
            <span class="badge badge-warning">Öne Çıkan İlan: <?php echo $highlightedCount; ?></span>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $success; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form id="featured-form" action="" method="POST">
        <div class="properties-grid">
            <?php if (!empty($allProperties)): ?>
                <?php foreach ($allProperties as $prop): ?>
                    <div class="property-card <?php echo $prop['featured_highlighted'] ? 'highlighted' : ''; ?>">
                        <div class="property-image">
                            <img src="<?php echo $helper->e($prop['main_image'] ?? $helper->asset('images/no-image.jpg')); ?>" alt="<?php echo $helper->e($prop['title']); ?>">
                            <div class="property-badges">
                                <?php if ($prop['featured_highlighted']): ?>
                                    <span class="badge badge-warning">Öne Çıkan</span>
                                <?php endif; ?>
                                <?php if ($prop['featured']): ?>
                                    <span class="badge badge-info">Vitrin</span>
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
                                           name="highlighted_ids[]" 
                                           value="<?php echo $prop['id']; ?>"
                                           <?php echo $prop['featured_highlighted'] ? 'checked' : ''; ?>
                                           onchange="updateHighlightedCount(this)">
                                    <span class="checkmark"></span>
                                    Öne Çıkan'a Ekle
                                </label>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Henüz hiç ilan bulunmuyor.</p>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Öne Çıkan İlanları Kaydet
            </button>
        </div>
    </form>
</div>

<!-- Fixed Save Button for Mobile -->
<div class="fixed-save-button">
    <button type="submit" form="featured-form" class="btn btn-primary btn-fixed">
        <i class="fas fa-save"></i> Öne Çıkanları Kaydet
    </button>
</div>

<style>
.content-wrapper {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.action-bar h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 24px;
}

.action-bar h3 i {
    color: #f39c12;
    margin-right: 10px;
}

.badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    margin-left: 10px;
}

.badge-info {
    background: #3498db;
    color: white;
}

.badge-warning {
    background: #f39c12;
    color: white;
}

.badge-danger {
    background: #e74c3c;
    color: white;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
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

.alert i {
    margin-right: 10px;
    font-size: 18px;
}

.properties-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.property-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.property-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.property-card.highlighted {
    border-color: #f39c12;
    box-shadow: 0 4px 20px rgba(243, 156, 18, 0.3);
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
    transition: transform 0.3s ease;
}

.property-card:hover .property-image img {
    transform: scale(1.05);
}

.property-badges {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.property-info {
    padding: 20px;
}

.property-info h4 {
    margin: 0 0 10px 0;
    color: #2c3e50;
    font-size: 18px;
    line-height: 1.4;
}

.property-location {
    color: #7f8c8d;
    font-size: 14px;
    margin: 8px 0;
    display: flex;
    align-items: center;
}

.property-location i {
    margin-right: 8px;
    color: #e74c3c;
}

.property-price {
    color: #27ae60;
    font-size: 20px;
    font-weight: bold;
    margin: 10px 0;
}

.property-type {
    color: #7f8c8d;
    font-size: 14px;
    margin: 8px 0;
    display: flex;
    align-items: center;
}

.property-type i {
    margin-right: 8px;
    color: #3498db;
}

.property-actions {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #ecf0f1;
}

.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 14px;
    color: #2c3e50;
    font-weight: 500;
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #bdc3c7;
    border-radius: 4px;
    margin-right: 10px;
    position: relative;
    transition: all 0.3s ease;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark {
    background: #f39c12;
    border-color: #f39c12;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.form-actions {
    text-align: center;
    padding: 30px 0;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
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
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

/* Fixed Save Button */
.fixed-save-button {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    padding: 15px 20px;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
    z-index: 999;
    display: none;
}

.btn-fixed {
    width: 100%;
    padding: 15px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

.btn-fixed:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
}

@media (max-width: 768px) {
    .action-bar {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .action-bar > div {
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: center;
    }
    
    .badge {
        margin-left: 0;
        margin-bottom: 5px;
    }
    
    .properties-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .property-card {
        margin: 0 10px;
    }
    
    /* Show fixed button on mobile */
    .fixed-save-button {
        display: block;
    }
    
    /* Hide original form actions on mobile */
    .form-actions {
        display: none;
    }
    
    /* Add bottom padding to content to prevent overlap */
    .content-wrapper {
        padding-bottom: 100px;
    }
}
</style>

<script>
function updateHighlightedCount(clickedCheckbox) {
    // Sadece checkbox seçildiğinde limit kontrolü yap
    if (clickedCheckbox.checked) {
        const checkboxes = document.querySelectorAll('input[name="highlighted_ids[]"]:checked');
        const count = checkboxes.length;
        
        if (count > 12) {
            alert('Maksimum 12 ilan seçebilirsiniz!');
            clickedCheckbox.checked = false;
            return;
        }
    }
    
    // Seçilen ilanları vurgula
    document.querySelectorAll('.property-card').forEach(card => {
        const checkbox = card.querySelector('input[name="highlighted_ids[]"]');
        if (checkbox.checked) {
            card.classList.add('highlighted');
        } else {
            card.classList.remove('highlighted');
        }
    });
}

// Sayfa yüklendiğinde sadece vurgulamayı yap, uyarı verme
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.property-card').forEach(card => {
        const checkbox = card.querySelector('input[name="highlighted_ids[]"]');
        if (checkbox.checked) {
            card.classList.add('highlighted');
        } else {
            card.classList.remove('highlighted');
        }
    });
});
</script>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
