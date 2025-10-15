<?php 
require_once __DIR__."/../includes/header.php";
require_once __DIR__."/../classes/Property.php";

$property = new Property();

// Filtreler
$filters = [
    'type' => $_GET['type'] ?? '',
    'category' => $_GET['category'] ?? '',
    'city' => $_GET['city'] ?? '',
    'district' => $_GET['district'] ?? '',
    'min_price' => $_GET['min_price'] ?? '',
    'max_price' => $_GET['max_price'] ?? '',
    'rooms' => $_GET['rooms'] ?? '',
    'status' => 'active'
];

// Sayfalama
$page = (int)($_GET['page'] ?? 1);
$perPage = 12;
$offset = ($page - 1) * $perPage;

$filters['limit'] = $perPage;
$filters['offset'] = $offset;

// Sıralama
$filters['order_by'] = $_GET['order_by'] ?? 'created_at';
$filters['order_dir'] = $_GET['order_dir'] ?? 'DESC';

// İlanları getir
$properties = $property->getAll($filters);
$totalProperties = $property->getCount($filters);
$totalPages = ceil($totalProperties / $perPage);

// Şehir ve ilçe listeleri
$cities = $property->getCities();
$districts = $property->getDistricts($filters['city']);

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

// Sıralama seçenekleri
$sortOptions = [
    'created_at' => 'En Yeni',
    'price' => 'Fiyat',
    'area' => 'Alan',
    'rooms' => 'Oda Sayısı'
];
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1>İlanlar</h1>
                <p><?php echo $totalProperties; ?> ilan bulundu</p>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="filters-section">
        <div class="container">
            <form method="GET" class="filters-form">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="type">İlan Tipi</label>
                        <select id="type" name="type" class="filter-select">
                            <option value="">Tümü</option>
                            <option value="satilik" <?php echo $filters['type'] === 'satilik' ? 'selected' : ''; ?>>Satılık</option>
                            <option value="kiralik" <?php echo $filters['type'] === 'kiralik' ? 'selected' : ''; ?>>Kiralık</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="category">Kategori</label>
                        <select id="category" name="category" class="filter-select">
                            <option value="">Tümü</option>
                            <?php foreach ($categories as $key => $label): ?>
                                <option value="<?php echo $key; ?>" <?php echo $filters['category'] === $key ? 'selected' : ''; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="city">Şehir</label>
                        <select id="city" name="city" class="filter-select">
                            <option value="">Tümü</option>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?php echo $helper->e($city); ?>" <?php echo $filters['city'] === $city ? 'selected' : ''; ?>><?php echo $helper->e($city); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="district">İlçe</label>
                        <select id="district" name="district" class="filter-select">
                            <option value="">Tümü</option>
                            <?php foreach ($districts as $district): ?>
                                <option value="<?php echo $helper->e($district); ?>" <?php echo $filters['district'] === $district ? 'selected' : ''; ?>><?php echo $helper->e($district); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="min_price">Min Fiyat</label>
                        <input type="number" id="min_price" name="min_price" class="filter-input" 
                               value="<?php echo $helper->e($filters['min_price']); ?>" placeholder="Min fiyat">
                    </div>
                    
                    <div class="filter-group">
                        <label for="max_price">Max Fiyat</label>
                        <input type="number" id="max_price" name="max_price" class="filter-input" 
                               value="<?php echo $helper->e($filters['max_price']); ?>" placeholder="Max fiyat">
                    </div>
                    
                    <div class="filter-group">
                        <label for="rooms">Oda Sayısı</label>
                        <select id="rooms" name="rooms" class="filter-select">
                            <option value="">Tümü</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?php echo $room; ?>" <?php echo $filters['rooms'] == $room ? 'selected' : ''; ?>><?php echo $room; ?>+0</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="order_by">Sıralama</label>
                        <select id="order_by" name="order_by" class="filter-select">
                            <?php foreach ($sortOptions as $key => $label): ?>
                                <option value="<?php echo $key; ?>" <?php echo $filters['order_by'] === $key ? 'selected' : ''; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrele
                    </button>
                    <a href="<?php echo $helper->url('ilanlar'); ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Temizle
                    </a>
                </div>
            </form>
        </div>
    </section>

    <!-- Properties Grid -->
    <section class="properties-section">
        <div class="container">
            <?php if (!empty($properties)): ?>
                <div class="properties-grid">
                    <?php foreach ($properties as $prop): ?>
                        <div class="property-card" data-category="<?php echo $helper->e($prop['property_type']); ?>">
                            <div class="property-image">
                                <?php 
                                $images = json_decode($prop['images'] ?? '[]', true);
                                $mainImage = !empty($images) ? $images[0] : ($prop['main_image'] ?? 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=600&fit=crop');
                                ?>
                                <img src="<?php echo $helper->e($mainImage); ?>" alt="<?php echo $helper->e($prop['title']); ?>">
                                <div class="property-badge"><?php echo ucfirst($helper->e($prop['transaction_type'])); ?></div>
                                <div class="property-price"><?php echo $helper->formatPrice($prop['price']); ?></div>
                            </div>
                            <div class="property-details">
                                <h3 class="property-title"><?php echo $helper->e($prop['title']); ?></h3>
                                <div class="property-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo $helper->e(($prop['city_name'] ?? '') . ', ' . ($prop['district_name'] ?? '')); ?></span>
                                </div>
                                <div class="property-features">
                                    <?php if ($prop['room_count'] > 0): ?>
                                        <div class="feature">
                                            <i class="fas fa-bed"></i>
                                            <span><?php echo $prop['room_count']; ?>+0</span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($prop['bathroom_count'] > 0): ?>
                                        <div class="feature">
                                            <i class="fas fa-bath"></i>
                                            <span><?php echo $prop['bathroom_count']; ?> Banyo</span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($prop['area'] > 0): ?>
                                        <div class="feature">
                                            <i class="fas fa-ruler-combined"></i>
                                            <span><?php echo $prop['area']; ?> m²</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="property-footer">
                                    <div class="property-category">
                                        <i class="fas fa-tag"></i>
                                        <span><?php echo isset($categories[$prop['property_type']]) ? $categories[$prop['property_type']] : ucfirst($prop['property_type']); ?></span>
                                    </div>
                                    <a href="<?php echo $helper->propertyUrl($prop['slug']); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Detay
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="<?php echo $helper->url('ilanlar', array_merge($_GET, ['page' => $page - 1])); ?>" class="pagination-btn">
                                <i class="fas fa-chevron-left"></i> Önceki
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <a href="<?php echo $helper->url('ilanlar', array_merge($_GET, ['page' => $i])); ?>" 
                               class="pagination-btn <?php echo $i === $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="<?php echo $helper->url('ilanlar', array_merge($_GET, ['page' => $page + 1])); ?>" class="pagination-btn">
                                Sonraki <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>İlan Bulunamadı</h3>
                    <p>Aradığınız kriterlere uygun ilan bulunamadı. Filtreleri değiştirerek tekrar deneyin.</p>
                    <a href="<?php echo $helper->url('ilanlar'); ?>" class="btn btn-primary">
                        <i class="fas fa-refresh"></i> Filtreleri Temizle
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <style>
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0 60px;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .page-header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        /* Filters Section */
        .filters-section {
            background: white;
            padding: 2rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: -30px;
            position: relative;
            z-index: 10;
        }
        
        .filters-form {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }
        
        .filter-select,
        .filter-input {
            padding: 0.75rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .filter-select:focus,
        .filter-input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .filter-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }
        
        /* Properties Grid */
        .properties-section {
            padding: 3rem 0;
            background: #f8f9fa;
        }
        
        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .property-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .property-image {
            position: relative;
            height: 250px;
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
        
        .property-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .property-price {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 700;
        }
        
        .property-details {
            padding: 1.5rem;
        }
        
        .property-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }
        
        .property-location {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }
        
        .property-features {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .feature {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .property-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }
        
        .property-category {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 3rem;
        }
        
        .pagination-btn {
            padding: 0.75rem 1rem;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .pagination-btn:hover,
        .pagination-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        /* No Results */
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
        }
        
        .no-results-icon {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }
        
        .no-results h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }
        
        .no-results p {
            color: #6c757d;
            margin-bottom: 2rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .properties-grid {
                grid-template-columns: 1fr;
                padding: 0 1rem;
            }
            
            .filter-actions {
                flex-direction: column;
            }
            
            .pagination {
                flex-wrap: wrap;
            }
        }
    </style>

    <script>
        // Şehir değiştiğinde ilçeleri güncelle
        document.getElementById('city').addEventListener('change', function() {
            const city = this.value;
            const districtSelect = document.getElementById('district');
            
            if (city) {
                // AJAX ile ilçeleri getir
                fetch(`<?php echo $helper->url('api/districts'); ?>?city=${encodeURIComponent(city)}`)
                    .then(response => response.json())
                    .then(data => {
                        districtSelect.innerHTML = '<option value="">Tümü</option>';
                        data.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district;
                            option.textContent = district;
                            districtSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                districtSelect.innerHTML = '<option value="">Tümü</option>';
            }
        });
    </script>

<?php require_once __DIR__."/../includes/footer.php"; ?>
