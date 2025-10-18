<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Helper.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/AdminUser.php';

$helper = Helper::getInstance();
$adminUser = new AdminUser();
$pageTitle = 'Dashboard';

// Veritabanı bağlantısı
$db = Database::getInstance()->getConnection();

// İstatistikleri çek
try {
    $totalProperties = $db->query("SELECT COUNT(*) FROM properties WHERE status = 'active'")->fetchColumn();
    $totalSliders = $db->query("SELECT COUNT(*) FROM sliders WHERE status = 'active'")->fetchColumn();
    $totalUsers = $db->query("SELECT COUNT(*) FROM admin_users")->fetchColumn();
    $totalCities = $db->query("SELECT COUNT(*) FROM cities")->fetchColumn();
    
    // Son eklenen ilanlar
    $recentProperties = $db->query("
        SELECT p.*, c.name as city_name, d.name as district_name 
        FROM properties p 
        LEFT JOIN cities c ON p.city_id = c.id 
        LEFT JOIN districts d ON p.district_id = d.id 
        ORDER BY p.created_at DESC 
        LIMIT 5
    ")->fetchAll();
    
    // Son eklenen sliderlar
    $recentSliders = $db->query("
        SELECT * FROM sliders 
        ORDER BY created_at DESC 
        LIMIT 5
    ")->fetchAll();
    
} catch (Exception $e) {
    $totalProperties = 0;
    $totalSliders = 0;
    $totalUsers = 0;
    $totalCities = 0;
    $recentProperties = [];
    $recentSliders = [];
}

// Layout header'ı dahil et
require_once __DIR__ . '/layout/header.php';
?>

<!-- Dashboard Stats -->
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="stat-card" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
        <div style="font-size: 2.5rem; color: #667eea; margin-bottom: 10px;">
            <i class="fas fa-home"></i>
        </div>
        <h3 style="color: #2c3e50; margin-bottom: 5px;">Toplam İlan</h3>
        <div style="font-size: 2rem; font-weight: 700; color: #27ae60;"><?php echo $totalProperties; ?></div>
    </div>
    
    <div class="stat-card" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
        <div style="font-size: 2.5rem; color: #f39c12; margin-bottom: 10px;">
            <i class="fas fa-images"></i>
        </div>
        <h3 style="color: #2c3e50; margin-bottom: 5px;">Aktif Slider</h3>
        <div style="font-size: 2rem; font-weight: 700; color: #27ae60;"><?php echo $totalSliders; ?></div>
    </div>
    
    <div class="stat-card" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
        <div style="font-size: 2.5rem; color: #e74c3c; margin-bottom: 10px;">
            <i class="fas fa-users"></i>
        </div>
        <h3 style="color: #2c3e50; margin-bottom: 5px;">Admin Kullanıcı</h3>
        <div style="font-size: 2rem; font-weight: 700; color: #27ae60;"><?php echo $totalUsers; ?></div>
    </div>
    
    <div class="stat-card" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
        <div style="font-size: 2.5rem; color: #9b59b6; margin-bottom: 10px;">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <h3 style="color: #2c3e50; margin-bottom: 5px;">Şehir Sayısı</h3>
        <div style="font-size: 2rem; font-weight: 700; color: #27ae60;"><?php echo $totalCities; ?></div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 30px;">
    <h3 style="color: #2c3e50; margin-bottom: 20px; font-size: 1.5rem;">Hızlı İşlemler</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <a href="property-add.php" class="btn btn-primary" style="text-align: center; padding: 20px;">
            <i class="fas fa-plus" style="display: block; font-size: 2rem; margin-bottom: 10px;"></i>
            Yeni İlan Ekle
        </a>
        <a href="slider-edit-page.php" class="btn btn-warning" style="text-align: center; padding: 20px;">
            <i class="fas fa-images" style="display: block; font-size: 2rem; margin-bottom: 10px;"></i>
            Slider Ekle
        </a>
        <a href="properties.php" class="btn btn-success" style="text-align: center; padding: 20px;">
            <i class="fas fa-list" style="display: block; font-size: 2rem; margin-bottom: 10px;"></i>
            İlanları Görüntüle
        </a>
        <a href="sliders.php" class="btn btn-secondary" style="text-align: center; padding: 20px;">
            <i class="fas fa-cog" style="display: block; font-size: 2rem; margin-bottom: 10px;"></i>
            Slider Yönetimi
        </a>
    </div>
</div>

<!-- Recent Content -->
<div class="recent-content" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <!-- Son İlanlar -->
    <div class="recent-properties" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; font-size: 1.3rem;">Son Eklenen İlanlar</h3>
            <a href="properties.php" class="btn btn-sm btn-primary">Tümünü Gör</a>
        </div>
        
        <?php if (!empty($recentProperties)): ?>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <?php foreach ($recentProperties as $prop): ?>
                    <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #f8f9fa; border-radius: 10px;">
                        <div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden; background: #e9ecef;">
                            <?php 
                            $images = json_decode($prop['images'] ?? '[]', true);
                            $mainImage = !empty($images) ? $images[0] : ($prop['main_image'] ?? 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=600&fit=crop');
                            ?>
                            <img src="<?php echo $helper->e($mainImage); ?>" alt="<?php echo $helper->e($prop['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div style="flex: 1;">
                            <h4 style="color: #2c3e50; margin-bottom: 5px; font-size: 1rem;"><?php echo $helper->e($prop['title']); ?></h4>
                            <p style="color: #6c757d; font-size: 0.9rem; margin-bottom: 5px;">
                                <?php echo $helper->e(($prop['city_name'] ?? '') . ', ' . ($prop['district_name'] ?? '')); ?>
                            </p>
                            <div style="color: #27ae60; font-weight: 600; font-size: 0.9rem;"><?php echo $helper->formatPrice($prop['price']); ?></div>
                        </div>
                        <div style="text-align: right;">
                            <div style="color: #6c757d; font-size: 0.8rem;"><?php echo date('d.m.Y', strtotime($prop['created_at'])); ?></div>
                            <span style="padding: 4px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; background: <?php echo $prop['status'] === 'active' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $prop['status'] === 'active' ? '#155724' : '#721c24'; ?>;">
                                <?php echo ucfirst($prop['status']); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; color: #6c757d;">
                <i class="fas fa-home" style="font-size: 3rem; margin-bottom: 15px;"></i>
                <p>Henüz ilan bulunmuyor</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Son Sliderlar -->
    <div class="recent-sliders" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; font-size: 1.3rem;">Son Eklenen Sliderlar</h3>
            <a href="sliders.php" class="btn btn-sm btn-primary">Tümünü Gör</a>
        </div>
        
        <?php if (!empty($recentSliders)): ?>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <?php foreach ($recentSliders as $slider): ?>
                    <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #f8f9fa; border-radius: 10px;">
                        <div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden; background: #e9ecef;">
                            <img src="<?php echo $helper->e($slider['image']); ?>" alt="<?php echo $helper->e($slider['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div style="flex: 1;">
                            <h4 style="color: #2c3e50; margin-bottom: 5px; font-size: 1rem;"><?php echo $helper->e($slider['title']); ?></h4>
                            <p style="color: #6c757d; font-size: 0.9rem; margin-bottom: 5px;"><?php echo $helper->e($slider['subtitle']); ?></p>
                            <div style="color: #6c757d; font-size: 0.8rem;">Sıra: <?php echo $slider['sort_order']; ?></div>
                        </div>
                        <div style="text-align: right;">
                            <div style="color: #6c757d; font-size: 0.8rem;"><?php echo date('d.m.Y', strtotime($slider['created_at'])); ?></div>
                            <span style="padding: 4px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; background: <?php echo $slider['status'] === 'active' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $slider['status'] === 'active' ? '#155724' : '#721c24'; ?>;">
                                <?php echo ucfirst($slider['status']); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; color: #6c757d;">
                <i class="fas fa-images" style="font-size: 3rem; margin-bottom: 15px;"></i>
                <p>Henüz slider bulunmuyor</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- System Info -->
<div class="system-info" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-top: 30px;">
    <h3 style="color: #2c3e50; margin-bottom: 20px; font-size: 1.3rem;">Sistem Bilgileri</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div>
            <strong>PHP Versiyonu:</strong> <?php echo PHP_VERSION; ?>
        </div>
        <div>
            <strong>Sunucu:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Bilinmiyor'; ?>
        </div>
        <div>
            <strong>Veritabanı:</strong> MySQL
        </div>
        <div>
            <strong>Son Güncelleme:</strong> <?php echo date('d.m.Y H:i'); ?>
        </div>
    </div>
</div>

<style>
/* Mobile Responsive Styles for Dashboard */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .stat-card {
        padding: 20px !important;
    }
    
    .stat-card h3 {
        font-size: 1rem !important;
    }
    
    .stat-card div:first-child {
        font-size: 2rem !important;
    }
    
    .stat-card div:last-child {
        font-size: 1.5rem !important;
    }
    
    .quick-actions div {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .quick-actions .btn {
        padding: 15px !important;
        font-size: 0.9rem;
    }
    
    .quick-actions .btn i {
        font-size: 1.5rem !important;
        margin-bottom: 8px !important;
    }
    
    .recent-content {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
        display: grid !important;
    }
    
    .recent-properties,
    .recent-sliders {
        padding: 20px !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    .recent-properties h3,
    .recent-sliders h3 {
        font-size: 1.1rem !important;
    }
    
    .recent-properties .btn,
    .recent-sliders .btn {
        font-size: 0.8rem;
        padding: 6px 12px;
    }
    
    .system-info {
        padding: 20px !important;
    }
    
    .system-info h3 {
        font-size: 1.1rem !important;
    }
    
    .system-info div {
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        gap: 12px;
    }
    
    .stat-card {
        padding: 15px !important;
    }
    
    .stat-card h3 {
        font-size: 0.9rem !important;
    }
    
    .stat-card div:first-child {
        font-size: 1.8rem !important;
    }
    
    .stat-card div:last-child {
        font-size: 1.3rem !important;
    }
    
    .quick-actions {
        padding: 20px !important;
    }
    
    .quick-actions h3 {
        font-size: 1.2rem !important;
    }
    
    .quick-actions .btn {
        padding: 12px !important;
        font-size: 0.85rem;
    }
    
    .quick-actions .btn i {
        font-size: 1.3rem !important;
        margin-bottom: 6px !important;
    }
    
    .recent-content {
        grid-template-columns: 1fr !important;
        gap: 15px !important;
        display: grid !important;
    }
    
    .recent-properties,
    .recent-sliders {
        padding: 15px !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    .recent-properties h3,
    .recent-sliders h3 {
        font-size: 1rem !important;
    }
    
    .system-info {
        padding: 15px !important;
    }
    
    .system-info h3 {
        font-size: 1rem !important;
    }
    
    .system-info div {
        font-size: 0.85rem;
    }
}
</style>

<?php
// Layout footer'ı dahil et
require_once __DIR__ . '/layout/footer.php';
?>