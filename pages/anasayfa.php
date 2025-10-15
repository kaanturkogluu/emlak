<?php 

require_once __DIR__."/../includes/header.php";
require_once __DIR__."/../classes/Slider.php";
require_once __DIR__."/../classes/Database.php";
require_once __DIR__."/../classes/Property.php";

$slider = new Slider();
$sliders = $slider->getActive();

$property = new Property();
$featuredProperties = $property->getFeatured(9);
$highlightedProperties = $property->getHighlighted(12);

// Şehirleri veritabanından al
$db = Database::getInstance();
$stmt = $db->getConnection()->prepare("SELECT * FROM cities WHERE status = 'active' ORDER BY name ASC");
$stmt->execute();
$cities = $stmt->fetchAll();

?>

    <!-- Floating Phone Button -->
    <a href="tel:<?php echo $helper->getSetting('contact_phone', '+90 (212) 555 00 00'); ?>" class="floating-phone" aria-label="Hemen Ara">
        <i class="fas fa-phone-alt"></i>
    </a>

    <!-- Hero Slider -->
    <section class="hero-slider" id="home">
        <?php if (!empty($sliders)): ?>
            <?php foreach ($sliders as $index => $sliderItem): ?>
                <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo $helper->e($sliderItem['image']); ?>');">
                    <div class="slide-overlay">
                        <div class="slide-content">
                            <?php if (!empty($sliderItem['title'])): ?>
                                <h1><?php echo $helper->e($sliderItem['title']); ?></h1>
                            <?php endif; ?>
                            
                            <?php if (!empty($sliderItem['subtitle'])): ?>
                                <p><?php echo $helper->e($sliderItem['subtitle']); ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($sliderItem['button_text']) && !empty($sliderItem['button_url'])): ?>
                                <a href="<?php echo $helper->e($sliderItem['button_url']); ?>" class="btn"><?php echo $helper->e($sliderItem['button_text']); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Varsayılan slider - veri yoksa gösterilecek -->
            <div class="slide active" style="background-image: url('<?php echo $helper->asset('images/default-slider.jpg'); ?>');">
                <div class="slide-overlay">
                    <div class="slide-content">
                        <h1>Emlak Sitesi</h1>
                        <p>Hayalinizdeki evi bulun</p>
                        <a href="<?php echo $helper->url('ilanlar'); ?>" class="btn">İlanları İncele</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (count($sliders) > 1): ?>
            <div class="slider-arrow prev" onclick="changeSlide(-1)">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="slider-arrow next" onclick="changeSlide(1)">
                <i class="fas fa-chevron-right"></i>
            </div>

            <div class="slider-controls">
                <?php foreach ($sliders as $index => $sliderItem): ?>
                    <span class="slider-dot <?php echo $index === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $index; ?>)"></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Search Section -->
    <div class="search-section">
        <div class="search-tabs">
            <button type="button" class="search-tab active" data-type="satilik">Satılık</button>
            <button type="button" class="search-tab" data-type="kiralik">Kiralık</button>
            <button type="button" class="search-tab" data-type="gunluk-kiralik">Günlük Kiralık</button>
        </div>
        <form class="search-form" action="<?php echo $helper->url('ilanlar'); ?>" method="GET">
            <input type="hidden" name="transaction_type" id="transaction_type" value="satilik">
            
            <select name="city" class="search-input">
                <option value="">Tüm İl</option>
                <?php foreach ($cities as $city): ?>
                    <option value="<?php echo $helper->e($city['name']); ?>"><?php echo $helper->e($city['name']); ?></option>
                <?php endforeach; ?>
            </select>
            
            <select name="property_type" class="search-input">
                <option value="">Emlak Tipi</option>
                <option value="daire">Daire</option>
                <option value="villa">Villa</option>
                <option value="arsa">Arsa</option>
                <option value="isyeri">İşyeri</option>
                <option value="ofis">Ofis</option>
                <option value="depo">Depo</option>
            </select>
            
            <input type="number" name="min_price" class="search-input" placeholder="Min Fiyat">
            <input type="number" name="max_price" class="search-input" placeholder="Max Fiyat">
            
            <button type="submit" class="btn" style="width: 100%;">
                <i class="fas fa-search"></i> Ara
            </button>
        </form>
    </div>

    <!-- Vitrin İlanlarımız Section -->
    <section class="vitrin-section">
        <div class="vitrin-header">
            <h2>Vitrin İlanlarımız</h2>
            <p>Popüler ve Öne Çıkan İlanlarımız Aşağıda Listelenmiştir</p>
        </div>

        <?php if (!empty($featuredProperties)): ?>
        <div class="vitrin-slider-container">
            <div class="vitrin-slider" id="vitrinSlider">
                <?php 
                $propertiesPerGroup = 3;
                $groupCount = ceil(count($featuredProperties) / $propertiesPerGroup);
                for ($i = 0; $i < $groupCount; $i++): 
                ?>
                    <div class="vitrin-group">
                        <?php 
                        $startIndex = $i * $propertiesPerGroup;
                        $endIndex = min($startIndex + $propertiesPerGroup, count($featuredProperties));
                        for ($j = $startIndex; $j < $endIndex; $j++):
                            $prop = $featuredProperties[$j];
                            $mainImage = !empty($prop['main_image']) ? $prop['main_image'] : $helper->asset('images/no-image.svg');
                            $images = !empty($prop['images']) ? json_decode($prop['images'], true) : [];
                            if (!empty($mainImage) && !in_array($mainImage, $images)) {
                                array_unshift($images, $mainImage);
                            }
                            $images = array_filter($images);
                        ?>
                            <div class="vitrin-card">
                                <div class="vitrin-image-container">
                                    <div class="vitrin-image-slider" data-ilan="<?php echo $prop['id']; ?>">
                                        <?php if (!empty($images)): ?>
                                            <?php foreach ($images as $imgIndex => $imageUrl): ?>
                                                <div class="vitrin-image <?php echo $imgIndex === 0 ? 'active' : ''; ?>">
                                                    <img src="<?php echo $helper->e($imageUrl); ?>" alt="<?php echo $helper->e($prop['title']); ?>">
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="vitrin-image active">
                                                <img src="<?php echo $helper->asset('images/no-image.svg'); ?>" alt="Resim Yok">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="vitrin-location"><?php echo $helper->e($prop['city_name']); ?> / <?php echo $helper->e($prop['district_name']); ?></div>
                                    <div class="vitrin-type-badge"><?php echo strtoupper($helper->e($prop['property_type'])); ?></div>
                                </div>
                                <div class="vitrin-details">
                                    <div class="vitrin-title">
                                        <a href="<?php echo $helper->propertyUrl($prop['slug']); ?>"><?php echo $helper->e($prop['title']); ?></a>
                                        <?php if ($prop['featured']): ?><i class="fas fa-check-circle"></i><?php endif; ?>
                                    </div>
                                    <div class="vitrin-price">₺<?php echo number_format($prop['price'], 0, ',', '.'); ?></div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endfor; ?>
            </div>

            <div class="vitrin-dots">
                <?php for ($i = 0; $i < $groupCount; $i++): ?>
                    <div class="vitrin-dot <?php echo $i === 0 ? 'active' : ''; ?>" onclick="goToVitrinGroup(<?php echo $i; ?>)"></div>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <!-- Properties Section -->
    <section class="properties-section" id="properties">
        <div class="section-header">
            <h2>Öne Çıkan İlanlar</h2>
            <p>En yeni ve en popüler gayrimenkul ilanlarımız</p>
        </div>

        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">Tümü</button>
            <button class="filter-btn" data-filter="daire">Daire</button>
            <button class="filter-btn" data-filter="villa">Villa</button>
            <button class="filter-btn" data-filter="arsa">Arsa</button>
            <button class="filter-btn" data-filter="isyeri">İşyeri</button>
        </div>

        <?php if (!empty($highlightedProperties)): ?>
        <div class="properties-grid">
            <?php foreach ($highlightedProperties as $prop): ?>
                <div class="property-card" data-category="<?php echo $helper->e($prop['property_type']); ?>">
                    <div class="property-image">
                        <a href="<?php echo $helper->propertyUrl($prop['slug']); ?>">
                            <img src="<?php echo $helper->e($prop['main_image'] ?? $helper->asset('images/no-image.svg')); ?>" alt="<?php echo $helper->e($prop['title']); ?>">
                        </a>
                        <div class="property-badge featured">Öne Çıkan</div>
                        <div class="property-price">₺<?php echo number_format($prop['price'], 0, ',', '.'); ?></div>
                    </div>
                    <div class="property-details">
                        <h3 class="property-title">
                            <a href="<?php echo $helper->propertyUrl($prop['slug']); ?>"><?php echo $helper->e($prop['title']); ?></a>
                        </h3>
                        <div class="property-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo $helper->e($prop['city_name']); ?><?php if (!empty($prop['district_name'])): ?>, <?php echo $helper->e($prop['district_name']); ?><?php endif; ?></span>
                        </div>
                        <div class="property-features">
                            <?php if (!empty($prop['room_count'])): ?>
                            <div class="feature">
                                <i class="fas fa-bed"></i>
                                <span><?php echo $prop['room_count']; ?>+<?php echo $prop['living_room_count'] ?? 0; ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($prop['bathroom_count'])): ?>
                            <div class="feature">
                                <i class="fas fa-bath"></i>
                                <span><?php echo $prop['bathroom_count']; ?> Banyo</span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($prop['area'])): ?>
                            <div class="feature">
                                <i class="fas fa-ruler-combined"></i>
                                <span><?php echo number_format($prop['area'], 0, ',', '.'); ?> m²</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="property-footer">
                            <a href="<?php echo $helper->propertyUrl($prop['slug']); ?>" class="contact-btn">
                                <i class="fas fa-eye"></i> Detay
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="no-properties">
            <div class="no-properties-content">
                <i class="fas fa-fire"></i>
                <h3>Henüz Öne Çıkan İlan Bulunmuyor</h3>
                <p>Admin panelinden öne çıkan ilanlar seçebilirsiniz.</p>
                <a href="<?php echo $helper->getBaseUrl(); ?>/admin/featured-properties.php" class="btn btn-primary">Öne Çıkan İlanları Yönet</a>
            </div>
        </div>
        <?php endif; ?>
    </section>

  

    <!-- Slider Configuration Script -->
    <script>
        // Slider sayısını JavaScript'e aktar
        window.sliderCount = <?php echo count($sliders); ?>;
        
        // Vitrin slider sayısını JavaScript'e aktar
        window.vitrinSliderCount = <?php echo isset($groupCount) ? $groupCount : 0; ?>;
        
        // Ana slider fonksiyonları
        let currentSlideIndex = 0;
        let slides = document.querySelectorAll('.slide');
        let dots = document.querySelectorAll('.slider-dot');
        let isTransitioning = false;
        
        function changeSlide(direction) {
            if (!isTransitioning && slides.length > 1) {
                currentSlideIndex += direction;
                if (currentSlideIndex >= slides.length) currentSlideIndex = 0;
                if (currentSlideIndex < 0) currentSlideIndex = slides.length - 1;
                showSlide(currentSlideIndex);
            }
        }
        
        function currentSlide(index) {
            if (!isTransitioning && slides.length > 1) {
                currentSlideIndex = index;
                showSlide(currentSlideIndex);
            }
        }
        
        function showSlide(index) {
            if (isTransitioning) return;
            
            isTransitioning = true;
            
            // Tüm slide'ları gizle
            slides.forEach((slide, i) => {
                slide.classList.remove('active');
                if (i === index) {
                    slide.classList.add('active');
                }
            });
            
            // Dot'ları güncelle
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
            
            setTimeout(() => {
                isTransitioning = false;
            }, 1000);
        }
        
        // Vitrin slider fonksiyonları
        function goToVitrinGroup(groupIndex) {
            const slider = document.getElementById('vitrinSlider');
            if (slider) {
                const translateX = -groupIndex * 100;
                slider.style.transform = `translateX(${translateX}%)`;
                
                // Aktif dot'u güncelle
                document.querySelectorAll('.vitrin-dot').forEach((dot, index) => {
                    dot.classList.toggle('active', index === groupIndex);
                });
            }
        }
        
        // Vitrin slider otomatik geçiş
        let currentVitrinGroupIndex = 0;
        setInterval(() => {
            if (window.vitrinSliderCount > 1) {
                currentVitrinGroupIndex = (currentVitrinGroupIndex + 1) % window.vitrinSliderCount;
                goToVitrinGroup(currentVitrinGroupIndex);
            }
        }, 5000);
        
        // Filter butonları
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const propertyCards = document.querySelectorAll('.property-card');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Tüm butonlardan active class'ını kaldır
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Tıklanan butona active class'ını ekle
                    this.classList.add('active');
                    
                    const filter = this.getAttribute('data-filter');
                    
                    // Tüm property kartlarını göster
                    propertyCards.forEach(card => {
                        if (filter === 'all' || card.getAttribute('data-category') === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
    
    <style>
        /* Link altı çizgilerini kaldır */
        .property-card a,
        .vitrin-card a,
        .property-title a,
        .vitrin-title a {
            text-decoration: none !important;
        }
        
        .property-card a:hover,
        .vitrin-card a:hover,
        .property-title a:hover,
        .vitrin-title a:hover {
            text-decoration: none !important;
        }
        
        /* Resim linklerinin altı çizgisini kaldır */
        .property-image a,
        .vitrin-image-container a {
            text-decoration: none !important;
            border: none !important;
        }
        
        .property-image a:hover,
        .vitrin-image-container a:hover {
            text-decoration: none !important;
            border: none !important;
        }
        
        /* Detay butonunun altı çizgisini kaldır */
        .contact-btn {
            text-decoration: none !important;
        }
        
        .contact-btn:hover {
            text-decoration: none !important;
        }
    </style>
    
<?php require_once __DIR__."/../includes/footer.php"; ?>