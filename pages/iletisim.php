<?php 
require_once __DIR__."/../includes/header.php";
require_once __DIR__."/../classes/Helper.php";

$helper = Helper::getInstance();
?>
    
    <style>
        .contact-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 50px;
            padding: 40px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
        }
        
        .page-title {
            font-size: 3rem;
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin-bottom: 50px;
        }
        
        .contact-info {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .contact-info h3 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .contact-item:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }
        
        .contact-item i {
            font-size: 1.5rem;
            color: #3498db;
            margin-right: 20px;
            width: 30px;
            text-align: center;
        }
        
        .contact-item-content h4 {
            color: #2c3e50;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .contact-item-content p {
            color: #7f8c8d;
            margin: 0;
        }
        
        
        .map-section {
            margin-top: 50px;
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .map-section h3 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 1.8rem;
            font-weight: 600;
            text-align: center;
        }
        
        .map-container {
            width: 100%;
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            font-size: 1.1rem;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: #3498db;
            color: white;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .social-link:hover {
            background: #2980b9;
            transform: translateY(-3px);
        }
        
        
        @media (max-width: 768px) {
            .contact-page {
                padding: 10px;
            }
            
            .page-header {
                padding: 30px 20px;
                margin-bottom: 30px;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .contact-content {
                grid-template-columns: 1fr;
                gap: 30px;
                margin-bottom: 30px;
            }
            
            .contact-info {
                padding: 25px 20px;
            }
            
            .contact-info h3 {
                font-size: 1.5rem;
                margin-bottom: 20px;
            }
            
            .contact-item {
                padding: 15px;
                margin-bottom: 15px;
            }
            
            .contact-item i {
                font-size: 1.2rem;
                margin-right: 15px;
                width: 25px;
            }
            
            .contact-item-content h4 {
                font-size: 1rem;
            }
            
            .contact-item-content p {
                font-size: 0.9rem;
            }
            
            .social-links {
                justify-content: center;
                gap: 10px;
            }
            
            .social-link {
                width: 45px;
                height: 45px;
            }
            
            .map-section {
                margin-top: 30px;
                padding: 25px 20px;
            }
            
            .map-section h3 {
                font-size: 1.5rem;
                margin-bottom: 20px;
            }
            
            .map-container {
                height: 300px;
            }
        }
        
        @media (max-width: 480px) {
            .contact-page {
                padding: 5px;
            }
            
            .page-header {
                padding: 20px 15px;
                margin-bottom: 20px;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .page-subtitle {
                font-size: 0.9rem;
            }
            
            .contact-info {
                padding: 20px 15px;
            }
            
            .contact-info h3 {
                font-size: 1.3rem;
            }
            
            .contact-item {
                padding: 12px;
                margin-bottom: 12px;
            }
            
            .contact-item i {
                font-size: 1.1rem;
                margin-right: 12px;
                width: 20px;
            }
            
            .contact-item-content h4 {
                font-size: 0.95rem;
            }
            
            .contact-item-content p {
                font-size: 0.85rem;
            }
            
            .social-link {
                width: 40px;
                height: 40px;
            }
            
            .map-section {
                padding: 20px 15px;
            }
            
            .map-section h3 {
                font-size: 1.3rem;
            }
            
            .map-container {
                height: 250px;
            }
        }
    </style>
</head>

<body>
    <div class="contact-page">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">İletişim</h1>
            <p class="page-subtitle">Bizimle iletişime geçin, size yardımcı olmaktan mutluluk duyarız</p>
        </div>
        
        <!-- Contact Content -->
        <div class="contact-content">
            <!-- Contact Info -->
            <div class="contact-info">
                <h3>İletişim Bilgileri</h3>
                
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="contact-item-content">
                        <h4>Adres</h4>
                        <p><?php echo nl2br($helper->getSetting('contact_address', 'Merkez Mahallesi, Emlak Caddesi No:123\nBeşiktaş/İstanbul')); ?></p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div class="contact-item-content">
                        <h4>Telefon</h4>
                        <p><?php echo $helper->getSetting('contact_phone', '+90 (212) 555 00 00'); ?></p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div class="contact-item-content">
                        <h4>E-posta</h4>
                        <p><?php echo $helper->getSetting('contact_email', 'info@emlaksitesi.com'); ?></p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <div class="contact-item-content">
                        <h4>Çalışma Saatleri</h4>
                        <p><?php echo nl2br($helper->getSetting('working_hours', 'Pazartesi - Cuma: 09:00 - 18:00\nCumartesi: 09:00 - 16:00')); ?></p>
                    </div>
                </div>
                
                <div class="social-links">
                    <a href="<?php echo $helper->getSetting('facebook_url', '#'); ?>" class="social-link" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="<?php echo $helper->getSetting('twitter_url', '#'); ?>" class="social-link" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="<?php echo $helper->getSetting('instagram_url', '#'); ?>" class="social-link" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="<?php echo $helper->getSetting('linkedin_url', '#'); ?>" class="social-link" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="<?php echo $helper->getSetting('youtube_url', '#'); ?>" class="social-link" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
            
            <!-- Additional Info -->
            <div class="contact-info">
                <h3>Hizmetlerimiz</h3>
                
                <div class="contact-item">
                    <i class="fas fa-home"></i>
                    <div class="contact-item-content">
                        <h4>Emlak Danışmanlığı</h4>
                        <p>Profesyonel emlak danışmanlığı hizmeti</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-chart-line"></i>
                    <div class="contact-item-content">
                        <h4>Değerleme</h4>
                        <p>Gayrimenkul değerleme hizmetleri</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-handshake"></i>
                    <div class="contact-item-content">
                        <h4>Satış & Kiralama</h4>
                        <p>Güvenilir satış ve kiralama hizmetleri</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-shield-alt"></i>
                    <div class="contact-item-content">
                        <h4>Güvenlik</h4>
                        <p>%100 güvenli işlem garantisi</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Map Section -->
        <div class="map-section">
            <?php 
            $mapsIframeUrl = $helper->getSetting('google_maps_iframe', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3008.9633698326!2d29.0082!3d41.0431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14cab5bd657bd40f%3A0x8c9b90f577531f9b!2sBe%C5%9Fikta%C5%9F%2C%20%C4%B0stanbul!5e0!3m2!1str!2str!4v1640000000000!5m2!1str!2str');
            $mapsLocationTitle = $helper->getSetting('maps_location_title', 'Konumumuz');
            ?>
            <h3><?php echo $helper->e($mapsLocationTitle); ?></h3>
            <div class="map-container">
                <iframe 
                    src="<?php echo $helper->e($mapsIframeUrl); ?>" 
                    width="100%" 
                    height="400" 
                    style="border:0; border-radius: 10px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/../includes/footer.php'; ?>
