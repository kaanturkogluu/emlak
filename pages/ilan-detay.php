<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Property.php';
require_once __DIR__ . '/../classes/Helper.php';

$helper = Helper::getInstance();
$property = new Property();

// Slug parametresini al
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

// İlanı slug ile bul
$ilan = $property->getBySlug($slug);

if (!$ilan) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

// İlan görüntülenme sayısını artır
$property->incrementViews($ilan['id']);

// İlan resimlerini parse et
$images = json_decode($ilan['images'] ?? '[]', true);
$mainImage = $ilan['main_image'] ?? (!empty($images) ? $images[0] : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=600&fit=crop');

// Özellikleri parse et
$features = json_decode($ilan['features'] ?? '[]', true);

// Meta bilgileri
$pageTitle = $ilan['title'] . ' - ' . (defined('SITE_NAME') ? SITE_NAME : 'Emlak Sitesi');
$pageDescription = substr(strip_tags($ilan['description']), 0, 160);
$pageImage = $mainImage;

// Benzer ilanları getir
$similarProperties = $property->getSimilar($ilan['id'], $ilan['property_type'], $ilan['city_id'], 4);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $helper->e($pageTitle); ?></title>
    <meta name="description" content="<?php echo $helper->e($pageDescription); ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $helper->e($pageTitle); ?>">
    <meta property="og:description" content="<?php echo $helper->e($pageDescription); ?>">
    <meta property="og:image" content="<?php echo $helper->e($pageImage); ?>">
    <meta property="og:url" content="<?php echo $helper->getBaseUrl() . '/ilan/' . $ilan['slug']; ?>">
    <meta property="og:type" content="website">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $helper->e($pageTitle); ?>">
    <meta name="twitter:description" content="<?php echo $helper->e($pageDescription); ?>">
    <meta name="twitter:image" content="<?php echo $helper->e($pageImage); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $helper->getBaseUrl(); ?>/assets/images/favicon.ico">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $helper->getBaseUrl(); ?>/assets/css/style.css">
    
    <style>
        .property-detail {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .property-header {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .property-title {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .property-location {
            color: #7f8c8d;
            font-size: 1.1rem;
            margin-bottom: 20px;
        }
        
        .property-price {
            font-size: 2rem;
            color: #e74c3c;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .property-badges {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .badge-primary {
            background: #3498db;
            color: white;
        }
        
        .badge-success {
            background: #27ae60;
            color: white;
        }
        
        .badge-warning {
            background: #f39c12;
            color: white;
        }
        
        .property-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .property-gallery {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .main-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .image-thumbnails {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 10px;
        }
        
        .thumbnail {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        
        .thumbnail:hover {
            opacity: 0.8;
        }
        
        .property-info {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .info-section {
            margin-bottom: 30px;
        }
        
        .info-title {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .info-item i {
            color: #3498db;
            width: 20px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #e8f5e8;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .feature-item i {
            color: #27ae60;
        }
        
        
        .similar-properties {
            margin-top: 30px;
        }
        
        .similar-title {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .property-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
        }
        
        .property-card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .property-card-content {
            padding: 20px;
        }
        
        .property-card-title {
            font-size: 1.2rem;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .property-card-price {
            font-size: 1.3rem;
            color: #e74c3c;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .property-card-location {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .breadcrumb {
            background: #f8f9fa;
            padding: 15px 0;
            margin-bottom: 20px;
        }
        
        .breadcrumb a {
            color: #3498db;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .property-content {
                grid-template-columns: 1fr;
            }
            
            .property-title {
                font-size: 2rem;
            }
            
            .property-price {
                font-size: 1.5rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="property-detail">
            <a href="<?php echo $helper->getBaseUrl(); ?>">Ana Sayfa</a> / 
            <a href="<?php echo $helper->getBaseUrl(); ?>/ilanlar">İlanlar</a> / 
            <span><?php echo $helper->e($ilan['title']); ?></span>
        </div>
    </div>
    
    <div class="property-detail">
        <!-- Property Header -->
        <div class="property-header">
            <h1 class="property-title"><?php echo $helper->e($ilan['title']); ?></h1>
            <div class="property-location">
                <i class="fas fa-map-marker-alt"></i>
                <?php echo $helper->e($ilan['city_name'] . ', ' . $ilan['district_name']); ?>
                <?php if (!empty($ilan['address'])): ?>
                    - <?php echo $helper->e($ilan['address']); ?>
                <?php endif; ?>
            </div>
            <div class="property-price"><?php echo $helper->formatPrice($ilan['price']); ?></div>
            <div class="property-badges">
                <span class="badge badge-primary"><?php echo ucfirst($helper->e($ilan['transaction_type'])); ?></span>
                <span class="badge badge-success"><?php echo ucfirst($helper->e($ilan['property_type'])); ?></span>
                <?php if ($ilan['featured']): ?>
                    <span class="badge badge-warning">Öne Çıkan</span>
                <?php endif; ?>
                <?php if ($ilan['urgent']): ?>
                    <span class="badge badge-warning">Acil</span>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Property Content -->
        <div class="property-content">
            <!-- Gallery -->
            <div class="property-gallery">
                <img src="<?php echo $helper->e($mainImage); ?>" alt="<?php echo $helper->e($ilan['title']); ?>" class="main-image" id="mainImage">
                <?php if (!empty($images) && count($images) > 1): ?>
                    <div class="image-thumbnails">
                        <?php foreach ($images as $index => $image): ?>
                            <img src="<?php echo $helper->e($image); ?>" alt="Resim <?php echo $index + 1; ?>" class="thumbnail" onclick="changeMainImage('<?php echo $helper->e($image); ?>')">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Property Info -->
            <div class="property-info">
                <!-- Temel Bilgiler -->
                <div class="info-section">
                    <h3 class="info-title">Temel Bilgiler</h3>
                    <div class="info-grid">
                        <?php if ($ilan['room_count'] > 0): ?>
                            <div class="info-item">
                                <i class="fas fa-bed"></i>
                                <span><?php echo $ilan['room_count']; ?>+0</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($ilan['bathroom_count'] > 0): ?>
                            <div class="info-item">
                                <i class="fas fa-bath"></i>
                                <span><?php echo $ilan['bathroom_count']; ?> Banyo</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($ilan['area'] > 0): ?>
                            <div class="info-item">
                                <i class="fas fa-ruler-combined"></i>
                                <span><?php echo $ilan['area']; ?> m²</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($ilan['floor'] > 0): ?>
                            <div class="info-item">
                                <i class="fas fa-building"></i>
                                <span><?php echo $ilan['floor']; ?>. Kat</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($ilan['building_age'] > 0): ?>
                            <div class="info-item">
                                <i class="fas fa-calendar"></i>
                                <span><?php echo $ilan['building_age']; ?> Yaşında</span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($ilan['heating_type'])): ?>
                            <div class="info-item">
                                <i class="fas fa-fire"></i>
                                <span><?php echo $helper->e($ilan['heating_type']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Özellikler -->
                <?php if (!empty($features)): ?>
                    <div class="info-section">
                        <h3 class="info-title">Özellikler</h3>
                        <div class="features-grid">
                            <?php foreach ($features as $feature): ?>
                                <div class="feature-item">
                                    <i class="fas fa-check"></i>
                                    <span><?php echo $helper->e($feature); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Açıklama -->
                <?php if (!empty($ilan['description'])): ?>
                    <div class="info-section">
                        <h3 class="info-title">Açıklama</h3>
                        <p><?php echo nl2br($helper->e($ilan['description'])); ?></p>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
        
        <!-- Similar Properties -->
        <?php if (!empty($similarProperties)): ?>
            <div class="similar-properties">
                <h2 class="similar-title">Benzer İlanlar</h2>
                <div class="properties-grid">
                    <?php foreach ($similarProperties as $similar): ?>
                        <div class="property-card">
                            <a href="<?php echo $helper->propertyUrl($similar['slug']); ?>">
                                <?php 
                                $similarImages = json_decode($similar['images'] ?? '[]', true);
                                $similarMainImage = !empty($similarImages) ? $similarImages[0] : ($similar['main_image'] ?? 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=600&fit=crop');
                                ?>
                                <img src="<?php echo $helper->e($similarMainImage); ?>" alt="<?php echo $helper->e($similar['title']); ?>" class="property-card-image">
                                <div class="property-card-content">
                                    <h3 class="property-card-title"><?php echo $helper->e($similar['title']); ?></h3>
                                    <div class="property-card-price"><?php echo $helper->formatPrice($similar['price']); ?></div>
                                    <div class="property-card-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php echo $helper->e($similar['city_name'] . ', ' . $similar['district_name']); ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Footer -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>
    
    <script>
        // Resim değiştirme
        function changeMainImage(imageSrc) {
            document.getElementById('mainImage').src = imageSrc;
        }
        
    </script>
</body>
</html>
