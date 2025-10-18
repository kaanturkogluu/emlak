<?php
// Session başlat - en başta olmalı
session_start();

require_once __DIR__."/../includes/header.php";
require_once __DIR__."/../classes/Property.php";
require_once __DIR__."/../classes/Helper.php";

$helper = Helper::getInstance();
$property = new Property();

// Arama parametreleri
$searchParams = [
    'keyword' => $_GET['keyword'] ?? '',
    'transaction_type' => $_GET['transaction_type'] ?? '',
    'property_type' => $_GET['property_type'] ?? '',
    'city' => $_GET['city'] ?? '',
    'district' => $_GET['district'] ?? '',
    'min_price' => $_GET['min_price'] ?? '',
    'max_price' => $_GET['max_price'] ?? '',
    'min_area' => $_GET['min_area'] ?? '',
    'max_area' => $_GET['max_area'] ?? '',
    'rooms' => $_GET['rooms'] ?? '',
    'bathrooms' => $_GET['bathrooms'] ?? '',
    'floor' => $_GET['floor'] ?? '',
    'heating_type' => $_GET['heating_type'] ?? '',
    'building_age' => $_GET['building_age'] ?? '',
    'status' => 'active'
];

// Sayfalama
$page = (int)($_GET['page'] ?? 1);
$perPage = 12;
$offset = ($page - 1) * $perPage;

$searchParams['limit'] = $perPage;
$searchParams['offset'] = $offset;

// Sıralama
$searchParams['order_by'] = $_GET['order_by'] ?? 'created_at';
$searchParams['order_dir'] = $_GET['order_dir'] ?? 'DESC';

// Arama sonuçları
if (!empty($searchParams['keyword']) || !empty($searchParams['transaction_type']) || !empty($searchParams['property_type']) || !empty($searchParams['city'])) {
    // Arama kriterleri varsa arama yap
    $properties = $property->search($searchParams);
    $totalProperties = $property->getSearchCount($searchParams);
    $isSearchResult = true;
} else {
    // Arama kriterleri yoksa son 10 ilanı göster
    $allProperties = $property->getAll(['status' => 'active', 'order_by' => 'created_at', 'order_dir' => 'DESC']);
    $totalProperties = count($allProperties);
    
    if ($totalProperties > 0) {
        // 10'dan az varsa hepsini, 10'dan fazla varsa 10'unu göster
        $limit = min(10, $totalProperties);
        $properties = array_slice($allProperties, 0, $limit);
    } else {
        $properties = [];
    }
    $isSearchResult = false;
}
$totalPages = ceil($totalProperties / $perPage);

// Şehir ve ilçe listeleri
$cities = $property->getCities();
$districts = $property->getDistricts($searchParams['city']);

// Kategori listesi
$categories = [
    'daire' => 'Daire',
    'villa' => 'Villa',
    'arsa' => 'Arsa',
    'isyeri' => 'İşyeri',
    'ofis' => 'Ofis'
];

// Oda sayısı listesi
$rooms = [1, 2, 3, 4, 5, 6];

// Banyo sayısı listesi
$bathrooms = [1, 2, 3, 4, 5];

// Isıtma türleri
$heatingTypes = [
    'dogalgaz' => 'Doğalgaz',
    'kombi' => 'Kombi',
    'merkezi' => 'Merkezi Isıtma',
    'soba' => 'Soba',
    'elektrik' => 'Elektrik',
    'klima' => 'Klima'
];

// Bina yaşı
$buildingAges = [
    '0-5' => '0-5 Yıl',
    '5-10' => '5-10 Yıl',
    '10-20' => '10-20 Yıl',
    '20+' => '20+ Yıl'
];

// Sıralama seçenekleri
$sortOptions = [
    'created_at' => 'En Yeni',
    'price' => 'Fiyat (Düşük-Yüksek)',
    'price_desc' => 'Fiyat (Yüksek-Düşük)',
    'area' => 'Alan (Küçük-Büyük)',
    'area_desc' => 'Alan (Büyük-Küçük)',
    'rooms' => 'Oda Sayısı'
];
?>
 
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1>İlan Ara</h1>
                <p>Hayalinizdeki emlakı bulun</p>
            </div>
        </div>
    </section>

    <!-- Search Form Section -->
    <section class="search-form-section">
        <div class="container">
            <form method="GET" class="advanced-search-form">
                <div class="search-tabs">
                    <div class="tab active" data-tab="basic">
                        <i class="fas fa-search"></i>
                        <span>Hızlı Arama</span>
                    </div>
                    <div class="tab" data-tab="advanced">
                        <i class="fas fa-cog"></i>
                        <span>Detaylı Arama</span>
                    </div>
                </div>

                <!-- Hızlı Arama -->
                <div class="tab-content active" id="basic-search">
                    <div class="search-row">
                        <div class="search-group">
                            <label for="keyword">Anahtar Kelime</label>
                            <input type="text" id="keyword" name="keyword" value="<?php echo htmlspecialchars($searchParams['keyword'] ?? ''); ?>" placeholder="Daire, villa, arsa...">
                        </div>
                        
                        <div class="search-group">
                            <label for="transaction_type">İlan Tipi</label>
                            <select id="transaction_type" name="transaction_type">
                                <option value="">Tümü</option>
                                <option value="satilik" <?php echo $searchParams['transaction_type'] === 'satilik' ? 'selected' : ''; ?>>Satılık</option>
                                <option value="kiralik" <?php echo $searchParams['transaction_type'] === 'kiralik' ? 'selected' : ''; ?>>Kiralık</option>
                                <option value="gunluk-kiralik" <?php echo $searchParams['transaction_type'] === 'gunluk-kiralik' ? 'selected' : ''; ?>>Günlük Kiralık</option>
                            </select>
                        </div>
                        
                        <div class="search-group">
                            <label for="property_type">Kategori</label>
                            <select id="property_type" name="property_type">
                                <option value="">Tümü</option>
                                <?php foreach ($categories as $key => $label): ?>
                                    <option value="<?php echo $key; ?>" <?php echo $searchParams['property_type'] === $key ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="search-group">
                            <label for="city">Şehir</label>
                            <select id="city" name="city" onchange="loadDistricts(this.value)">
                                <option value="">Tümü</option>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?php echo $city; ?>" <?php echo $searchParams['city'] === $city ? 'selected' : ''; ?>><?php echo $city; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="search-group">
                            <label for="district">İlçe</label>
                            <select id="district" name="district">
                                <option value="">Tümü</option>
                                <?php foreach ($districts as $district): ?>
                                    <option value="<?php echo $district; ?>" <?php echo $searchParams['district'] === $district ? 'selected' : ''; ?>><?php echo $district; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="search-group">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                                Ara
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Detaylı Arama -->
                <div class="tab-content" id="advanced-search">
                    <div class="advanced-filters">
                        <div class="filter-section">
                            <h3>Fiyat Aralığı</h3>
                            <div class="price-range">
                                <div class="search-group">
                                    <label for="min_price">Min. Fiyat (₺)</label>
                                    <input type="number" id="min_price" name="min_price" value="<?php echo $searchParams['min_price']; ?>" placeholder="0">
                                </div>
                                <div class="search-group">
                                    <label for="max_price">Max. Fiyat (₺)</label>
                                    <input type="number" id="max_price" name="max_price" value="<?php echo $searchParams['max_price']; ?>" placeholder="1000000">
                                </div>
                            </div>
                        </div>

                        <div class="filter-section">
                            <h3>Alan Bilgileri</h3>
                            <div class="area-range">
                                <div class="search-group">
                                    <label for="min_area">Min. Alan (m²)</label>
                                    <input type="number" id="min_area" name="min_area" value="<?php echo $searchParams['min_area']; ?>" placeholder="0">
                                </div>
                                <div class="search-group">
                                    <label for="max_area">Max. Alan (m²)</label>
                                    <input type="number" id="max_area" name="max_area" value="<?php echo $searchParams['max_area']; ?>" placeholder="500">
                                </div>
                            </div>
                        </div>

                        <div class="filter-section">
                            <h3>Oda Bilgileri</h3>
                            <div class="room-info">
                                <div class="search-group">
                                    <label for="rooms">Oda Sayısı</label>
                                    <select id="rooms" name="rooms">
                                        <option value="">Tümü</option>
                                        <?php foreach ($rooms as $room): ?>
                                            <option value="<?php echo $room; ?>" <?php echo $searchParams['rooms'] == $room ? 'selected' : ''; ?>><?php echo $room; ?> Oda</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="search-group">
                                    <label for="bathrooms">Banyo Sayısı</label>
                                    <select id="bathrooms" name="bathrooms">
                                        <option value="">Tümü</option>
                                        <?php foreach ($bathrooms as $bathroom): ?>
                                            <option value="<?php echo $bathroom; ?>" <?php echo $searchParams['bathrooms'] == $bathroom ? 'selected' : ''; ?>><?php echo $bathroom; ?> Banyo</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="filter-section">
                            <h3>Bina Bilgileri</h3>
                            <div class="building-info">
                                <div class="search-group">
                                    <label for="floor">Kat</label>
                                    <input type="number" id="floor" name="floor" value="<?php echo $searchParams['floor']; ?>" placeholder="Kat numarası">
                                </div>
                                <div class="search-group">
                                    <label for="heating_type">Isıtma Türü</label>
                                    <select id="heating_type" name="heating_type">
                                        <option value="">Tümü</option>
                                        <?php foreach ($heatingTypes as $key => $label): ?>
                                            <option value="<?php echo $key; ?>" <?php echo $searchParams['heating_type'] === $key ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="search-group">
                                    <label for="building_age">Bina Yaşı</label>
                                    <select id="building_age" name="building_age">
                                        <option value="">Tümü</option>
                                        <?php foreach ($buildingAges as $key => $label): ?>
                                            <option value="<?php echo $key; ?>" <?php echo $searchParams['building_age'] === $key ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="filter-section">
                            <h3>Sıralama</h3>
                            <div class="search-group">
                                <label for="order_by">Sıralama</label>
                                <select id="order_by" name="order_by">
                                    <?php foreach ($sortOptions as $key => $label): ?>
                                        <option value="<?php echo $key; ?>" <?php echo $searchParams['order_by'] === $key ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="search-actions">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                            Detaylı Ara
                        </button>
                        <button type="button" class="clear-btn" onclick="clearForm()">
                            <i class="fas fa-times"></i>
                            Temizle
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Search Results -->
    <section class="search-results">
        <div class="container">
            <div class="results-header">
                <?php if ($isSearchResult): ?>
                    <h2>Arama Sonuçları</h2>
                    <p><?php echo $totalProperties; ?> ilan bulundu</p>
                <?php else: ?>
                    <h2>Son Eklenen İlanlar</h2>
                    <?php if ($totalProperties > 0): ?>
                        <p>En güncel <?php echo count($properties); ?> ilan gösteriliyor (Toplam <?php echo $totalProperties; ?> ilan)</p>
                    <?php else: ?>
                        <p>Şu anda ilan bulunmuyor</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if (!empty($properties)): ?>
                <div class="properties-grid">
                    <?php foreach ($properties as $prop): ?>
                        <div class="property-card">
                            <div class="property-image">
                                <?php if (!empty($prop['main_image']) && filter_var($prop['main_image'], FILTER_VALIDATE_URL)): ?>
                                    <img src="<?php echo $helper->e($prop['main_image']); ?>" alt="<?php echo $helper->e($prop['title']); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="no-image" style="display: none;">
                                        <i class="fas fa-home"></i>
                                        <span>Resim Yok</span>
                                    </div>
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="fas fa-home"></i>
                                        <span>Resim Yok</span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($prop['featured']): ?>
                                    <div class="featured-badge">Öne Çıkan</div>
                                <?php endif; ?>
                                
                                <?php if ($prop['urgent']): ?>
                                    <div class="urgent-badge">Acil</div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="property-content">
                                <div class="property-type">
                                    <span class="transaction-type <?php echo $prop['transaction_type']; ?>">
                                        <?php echo ucfirst($prop['transaction_type']); ?>
                                    </span>
                                    <span class="property-category">
                                        <?php echo ucfirst($prop['property_type']); ?>
                                    </span>
                                </div>
                                
                                <h3 class="property-title">
                                    <a href="<?php echo $helper->url('ilan/' . $prop['slug']); ?>">
                                        <?php echo $helper->e($prop['title']); ?>
                                    </a>
                                </h3>
                                
                                <div class="property-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo $helper->e($prop['city_name']); ?>, <?php echo $helper->e($prop['district_name']); ?></span>
                                </div>
                                
                                <div class="property-details">
                                    <?php if ($prop['room_count']): ?>
                                        <span><i class="fas fa-bed"></i> <?php echo $prop['room_count']; ?> Oda</span>
                                    <?php endif; ?>
                                    
                                    <?php if ($prop['area']): ?>
                                        <span><i class="fas fa-ruler-combined"></i> <?php echo number_format($prop['area']); ?> m²</span>
                                    <?php endif; ?>
                                    
                                    <?php if ($prop['floor']): ?>
                                        <span><i class="fas fa-building"></i> <?php echo $prop['floor']; ?>. Kat</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($isSearchResult && $totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="page-btn">
                                <i class="fas fa-chevron-left"></i>
                                Önceki
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" 
                               class="page-btn <?php echo $i === $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="page-btn">
                                Sonraki
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-results">
                    <?php if ($isSearchResult): ?>
                        <i class="fas fa-search"></i>
                        <h3>Arama kriterlerinize uygun ilan bulunamadı</h3>
                        <p>Arama kriterlerinizi değiştirerek tekrar deneyebilirsiniz.</p>
                    <?php else: ?>
                        <i class="fas fa-home"></i>
                        <h3>Şu anda ilan bulunmuyor</h3>
                        <p>Yeni ilanlar eklendiğinde burada görünecektir.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <style>
        .search-form-section {
            padding: 40px 0;
            background: #f8f9fa;
        }

        .search-form-section .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .advanced-search-form {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 100%;
        }

        .search-tabs {
            display: flex;
            border-bottom: 1px solid #eee;
        }

        .tab {
            flex: 1;
            padding: 15px 20px;
            text-align: center;
            cursor: pointer;
            background: #f8f9fa;
            border: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .tab.active {
            background: white;
            color: #3498db;
            border-bottom: 2px solid #3498db;
        }

        .tab:hover {
            background: #e9ecef;
        }

        .tab-content {
            display: none;
            padding: 30px;
        }

        .tab-content.active {
            display: block;
        }

        .search-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
            gap: 15px;
            align-items: end;
            max-width: 100%;
            overflow-x: auto;
        }

        .search-group {
            display: flex;
            flex-direction: column;
        }

        .search-group label {
            margin-bottom: 5px;
            font-weight: 600;
            color: #2c3e50;
        }

        .search-group input,
        .search-group select {
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .search-group input:focus,
        .search-group select:focus {
            outline: none;
            border-color: #3498db;
        }

        .search-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }

        .search-btn:hover {
            background: #2980b9;
        }

        .advanced-filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            max-width: 100%;
        }

        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .filter-section h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.1rem;
            border-bottom: 2px solid #3498db;
            padding-bottom: 8px;
            margin-top: 0;
        }

        .price-range,
        .area-range,
        .room-info,
        .building-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .search-actions {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .clear-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }

        .clear-btn:hover {
            background: #5a6268;
        }

        .search-results {
            padding: 40px 0;
        }

        .search-results .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .results-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .results-header h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
            max-width: 100%;
        }

        .property-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .property-card:hover {
            transform: translateY(-5px);
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
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }

        .no-image i {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .featured-badge,
        .urgent-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .featured-badge {
            background: #f39c12;
            color: white;
        }

        .urgent-badge {
            background: #e74c3c;
            color: white;
        }

        .property-content {
            padding: 20px;
        }

        .property-type {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .transaction-type {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .transaction-type.satilik {
            background: #d4edda;
            color: #155724;
        }

        .transaction-type.kiralik {
            background: #cce5ff;
            color: #004085;
        }

        .transaction-type.gunluk-kiralik {
            background: #fff3cd;
            color: #856404;
        }

        .property-category {
            padding: 4px 8px;
            background: #e9ecef;
            color: #495057;
            border-radius: 4px;
            font-size: 0.8rem;
        }

        .property-title {
            margin-bottom: 10px;
        }

        .property-title a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: 600;
        }

        .property-title a:hover {
            color: #3498db;
        }

        .property-location {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #6c757d;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .property-details {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .property-details span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .property-price {
            display: flex;
            align-items: baseline;
            gap: 5px;
        }

        .price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #27ae60;
        }

        .price-unit {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
        }

        .page-btn {
            padding: 10px 15px;
            border: 1px solid #dee2e6;
            background: white;
            color: #495057;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .page-btn:hover,
        .page-btn.active {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .no-results i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }

        .no-results h3 {
            margin-bottom: 10px;
            color: #495057;
        }

        @media (max-width: 768px) {
            .search-form-section .container,
            .search-results .container {
                padding: 0 15px;
            }

            .search-row {
                grid-template-columns: 1fr;
                gap: 15px;
                overflow-x: visible;
            }

            .advanced-filters {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .filter-section {
                padding: 15px;
            }

            .price-range,
            .area-range,
            .room-info,
            .building-info {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .properties-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .search-actions {
                flex-direction: column;
                gap: 10px;
            }

            .tab-content {
                padding: 20px;
            }

            .search-tabs {
                flex-direction: column;
            }

            .tab {
                padding: 12px 15px;
            }
        }

        @media (max-width: 480px) {
            .search-form-section {
                padding: 20px 0;
            }

            .search-form-section .container,
            .search-results .container {
                padding: 0 10px;
            }

            .tab-content {
                padding: 15px;
            }

            .filter-section {
                padding: 12px;
            }

            .search-group input,
            .search-group select {
                padding: 10px;
                font-size: 14px;
            }

            .search-btn,
            .clear-btn {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
    </style>

    <script>
        // Tab switching
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.dataset.tab;
                
                // Remove active class from all tabs and contents
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                document.getElementById(tabId + '-search').classList.add('active');
            });
        });

        // Load districts when city changes
        function loadDistricts(cityId) {
            if (!cityId) {
                document.getElementById('district').innerHTML = '<option value="">Tümü</option>';
                return;
            }

            fetch(`/emlak/api/districts.php?city_id=${cityId}`)
                .then(response => response.json())
                .then(data => {
                    const districtSelect = document.getElementById('district');
                    districtSelect.innerHTML = '<option value="">Tümü</option>';
                    
                    data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.name;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading districts:', error);
                });
        }

        // Clear form
        function clearForm() {
            document.querySelector('.advanced-search-form').reset();
            document.getElementById('district').innerHTML = '<option value="">Tümü</option>';
        }
    </script>

<?php require_once __DIR__."/../includes/footer.php"; ?>
