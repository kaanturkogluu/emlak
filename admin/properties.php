<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Helper.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Property.php';

$helper = Helper::getInstance();
$adminUser = new AdminUser();
$property = new Property();

$pageTitle = 'İlan Yönetimi';
$success = '';
$error = '';

// İlan silme işlemi
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($property->delete($id)) {
        $success = 'İlan başarıyla silindi.';
    } else {
        $error = 'İlan silinirken hata oluştu.';
    }
}

// İlan durumu değiştirme
if (isset($_GET['action']) && $_GET['action'] === 'toggle_status' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $currentProperty = $property->getById($id);
    if ($currentProperty) {
        $newStatus = $currentProperty['status'] === 'active' ? 'inactive' : 'active';
        $data = ['status' => $newStatus];
        if ($property->update($id, $data)) {
            $success = 'İlan durumu güncellendi.';
        } else {
            $error = 'İlan durumu güncellenirken hata oluştu.';
        }
    }
}

// İlanları getir
$properties = $property->getAll(['status' => '']); // Tüm durumlar

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

<!-- Action Bar -->
<div class="action-bar">
    <h3>İlan Yönetimi</h3>
    <a href="property-add.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Yeni İlan Ekle
    </a>
</div>

<!-- Properties Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Resim</th>
                <th>İlan Bilgileri</th>
                <th>Fiyat</th>
                <th>Durum</th>
                <th>Tarih</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($properties)): ?>
                <?php foreach ($properties as $prop): ?>
                    <tr>
                        <td>
                            <?php 
                            $images = json_decode($prop['images'] ?? '[]', true);
                            $mainImage = !empty($images) ? $images[0] : ($prop['main_image'] ?? 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=600&fit=crop');
                            ?>
                            <img src="<?php echo $helper->e($mainImage); ?>" alt="<?php echo $helper->e($prop['title']); ?>" style="width: 80px; height: 60px; border-radius: 8px; object-fit: cover;">
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;"><?php echo $helper->e($prop['title']); ?></div>
                            <div style="color: #6c757d; font-size: 0.9rem;">
                                <?php echo $helper->e(($prop['city_name'] ?? '') . ', ' . ($prop['district_name'] ?? '')); ?>
                            </div>
                            <div style="color: #6c757d; font-size: 0.9rem;">
                                <?php echo ucfirst($helper->e($prop['transaction_type'])); ?> - <?php echo ucfirst($helper->e($prop['property_type'])); ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #27ae60; font-size: 1.1rem;"><?php echo $helper->formatPrice($prop['price']); ?></div>
                        </td>
                        <td>
                            <span style="padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; background: <?php echo $prop['status'] === 'active' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $prop['status'] === 'active' ? '#155724' : '#721c24'; ?>;">
                                <?php echo ucfirst($prop['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo date('d.m.Y', strtotime($prop['created_at'])); ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="property-edit.php?id=<?php echo $prop['id']; ?>" class="btn btn-warning btn-sm" title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?action=toggle_status&id=<?php echo $prop['id']; ?>" 
                                   class="btn btn-success btn-sm"
                                   onclick="return confirm('İlan durumunu değiştirmek istediğinizden emin misiniz?')"
                                   title="Durum Değiştir">
                                    <i class="fas fa-toggle-<?php echo $prop['status'] === 'active' ? 'on' : 'off'; ?>"></i>
                                </a>
                                <a href="?action=delete&id=<?php echo $prop['id']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Bu ilanı silmek istediğinizden emin misiniz?')"
                                   title="Sil">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px;">
                        <i class="fas fa-home" style="font-size: 3rem; color: #6c757d; margin-bottom: 1rem;"></i>
                        <h3>Henüz ilan bulunmuyor</h3>
                        <p>İlk ilanınızı eklemek için yukarıdaki "Yeni İlan Ekle" butonunu kullanın.</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
// Layout footer'ı dahil et
require_once __DIR__ . '/layout/footer.php';
?>