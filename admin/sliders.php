<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Helper.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Slider.php';

$helper = Helper::getInstance();
$adminUser = new AdminUser();
$slider = new Slider();
$pageTitle = 'Slider Yönetimi';
$success = '';
$error = '';

// Slider silme işlemi
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($slider->delete($id)) {
        $success = 'Slider başarıyla silindi.';
    } else {
        $error = 'Slider silinirken hata oluştu.';
    }
}

// Slider durumu değiştirme
if (isset($_GET['action']) && $_GET['action'] === 'toggle_status' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $currentSlider = $slider->getById($id);
    if ($currentSlider) {
        $newStatus = $currentSlider['status'] === 'active' ? 'inactive' : 'active';
        if ($slider->updateStatus($id, $newStatus)) {
            $success = 'Slider durumu güncellendi.';
        } else {
            $error = 'Slider durumu güncellenirken hata oluştu.';
        }
    }
}

// Sliderları getir
$sliders = $slider->getAll();

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
    <h3>Slider Yönetimi</h3>
    <a href="slider-edit-page.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Yeni Slider Ekle
    </a>
</div>

<!-- Sliders Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Resim</th>
                <th>Başlık</th>
                <th>Alt Başlık</th>
                <th>Buton</th>
                <th>Sıra</th>
                <th>Durum</th>
                <th>Tarih</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($sliders)): ?>
                <?php foreach ($sliders as $sliderItem): ?>
                    <tr>
                        <td>
                            <img src="<?php echo $helper->e($sliderItem['image']); ?>" alt="<?php echo $helper->e($sliderItem['title']); ?>" style="width: 80px; height: 60px; border-radius: 8px; object-fit: cover;">
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;"><?php echo $helper->e($sliderItem['title']); ?></div>
                        </td>
                        <td>
                            <div style="color: #6c757d; font-size: 0.9rem;"><?php echo $helper->e($sliderItem['subtitle']); ?></div>
                        </td>
                        <td>
                            <?php if (!empty($sliderItem['button_text'])): ?>
                                <span style="padding: 4px 8px; background: #667eea; color: white; border-radius: 4px; font-size: 0.8rem;">
                                    <?php echo $helper->e($sliderItem['button_text']); ?>
                                </span>
                            <?php else: ?>
                                <span style="color: #6c757d; font-size: 0.9rem;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="padding: 4px 8px; background: #f8f9fa; border-radius: 4px; font-size: 0.9rem; font-weight: 600;">
                                <?php echo $sliderItem['sort_order']; ?>
                            </span>
                        </td>
                        <td>
                            <span style="padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; background: <?php echo $sliderItem['status'] === 'active' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $sliderItem['status'] === 'active' ? '#155724' : '#721c24'; ?>;">
                                <?php echo ucfirst($sliderItem['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo date('d.m.Y', strtotime($sliderItem['created_at'])); ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="slider-edit-page.php?id=<?php echo $sliderItem['id']; ?>" class="btn btn-warning btn-sm" title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?action=toggle_status&id=<?php echo $sliderItem['id']; ?>" 
                                   class="btn btn-success btn-sm"
                                   onclick="return confirm('Slider durumunu değiştirmek istediğinizden emin misiniz?')"
                                   title="Durum Değiştir">
                                    <i class="fas fa-toggle-<?php echo $sliderItem['status'] === 'active' ? 'on' : 'off'; ?>"></i>
                                </a>
                                <a href="?action=delete&id=<?php echo $sliderItem['id']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Bu sliderı silmek istediğinizden emin misiniz?')"
                                   title="Sil">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px;">
                        <i class="fas fa-images" style="font-size: 3rem; color: #6c757d; margin-bottom: 1rem;"></i>
                        <h3>Henüz slider bulunmuyor</h3>
                        <p>İlk sliderınızı eklemek için yukarıdaki "Yeni Slider Ekle" butonunu kullanın.</p>
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