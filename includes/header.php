<?php 
require_once __DIR__. "/../classes/helper.php";
$helper = Helper::getInstance();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $helper->getSiteName(); ?></title>
    <meta name="description" content="<?php echo $helper->getSetting('site_description', 'Türkiye\'nin en güvenilir emlak platformu'); ?>">
    <meta name="keywords" content="<?php echo $helper->getSetting('site_keywords', 'emlak, satılık, kiralık, daire, villa, arsa'); ?>">
    
    <!-- Favicon -->
    <?php $siteIcon = $helper->getSetting('site_icon', ''); ?>
    <?php if (!empty($siteIcon)): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo $helper->e($siteIcon); ?>">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $helper->e($siteIcon); ?>">
        <link rel="apple-touch-icon" href="<?php echo $helper->e($siteIcon); ?>">
        <meta name="msapplication-TileImage" content="<?php echo $helper->e($siteIcon); ?>">
    <?php endif; ?>
    
    <link rel="stylesheet" href="<?php echo $helper->asset('css/main.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="top-bar-content">
            <div class="top-bar-left">
                <div class="top-bar-item">
                    <i class="fas fa-phone-alt"></i>
                    <span><?php echo $helper->getSetting('contact_phone', '+90 (212) 555 00 00'); ?></span>
                </div>
                <div class="top-bar-item">
                    <i class="far fa-envelope"></i>
                    <span><?php echo $helper->getSetting('contact_email', 'info@emlaksitesi.com'); ?></span>
                </div>
            </div>
            <div class="top-bar-right">
                <a href="<?php echo $helper->getSetting('facebook_url', '#'); ?>" class="social-icon" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="<?php echo $helper->getSetting('twitter_url', '#'); ?>" class="social-icon" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="<?php echo $helper->getSetting('instagram_url', '#'); ?>" class="social-icon" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="<?php echo $helper->getSetting('linkedin_url', '#'); ?>" class="social-icon" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="<?php echo $helper->getSetting('youtube_url', '#'); ?>" class="social-icon" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header>
        <div class="main-nav">
            <nav>
                <div class="logo">
                    <a href="<?php echo $helper->getBaseUrl(); ?>">
                        <img src="https://placehold.co/200x60/3b6cb6/white?text=1class.com&font=roboto" alt="1class.com Logo">
                    </a>
                </div>

                <div class="nav-center">
                    <ul class="nav-links">
                        <li class="dropdown">
                            <span>
                                İLANLAR
                                <i class="fas fa-chevron-down"></i>
                            </span>
                            <div class="dropdown-menu">
                                <a href="<?php echo $helper->url('ilanlar'); ?>"><i class="fas fa-list"></i> Tüm İlanlar</a>
                                <a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'satilik']); ?>"><i class="fas fa-home"></i> Satılık İlanlar</a>
                                <a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'kiralik']); ?>"><i class="fas fa-key"></i> Kiralık İlanlar</a>
                                <a href="<?php echo $helper->url('arama'); ?>"><i class="fas fa-search"></i> İlan Ara</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <span>
                                SATILIK
                                <i class="fas fa-chevron-down"></i>
                            </span>
                            <div class="dropdown-menu">
                                <a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'satilik', 'property_type' => 'daire']); ?>"><i class="fas fa-home"></i> Satılık Daire</a>
                                <a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'satilik', 'property_type' => 'villa']); ?>"><i class="fas fa-home-lg-alt"></i> Satılık Villa</a>
                                <a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'satilik', 'property_type' => 'arsa']); ?>"><i class="fas fa-mountain"></i> Satılık Arsa</a>
                                <a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'satilik', 'property_type' => 'isyeri']); ?>"><i class="fas fa-store"></i> Satılık İşyeri</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <span>
                                KİRALIK
                                <i class="fas fa-chevron-down"></i>
                            </span>
                            <div class="dropdown-menu">
                                <a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'kiralik', 'property_type' => 'daire']); ?>"><i class="fas fa-home"></i> Kiralık Daire</a>
                                <a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'kiralik', 'property_type' => 'villa']); ?>"><i class="fas fa-home-lg-alt"></i> Kiralık Villa</a>
                                <a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'kiralik', 'property_type' => 'isyeri']); ?>"><i class="fas fa-store"></i> Kiralık İşyeri</a>
                                <a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'kiralik', 'property_type' => 'ofis']); ?>"><i class="fas fa-building"></i> Kiralık Ofis</a>
                            </div>
                        </li>
                        <li>
                            <a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'gunluk-kiralik']); ?>">GÜNLÜK KİRALIK</a>
                        </li>
                        <li class="dropdown">
                            <span>
                                KURUMSAL
                                <i class="fas fa-chevron-down"></i>
                            </span>
                            <div class="dropdown-menu">
                                <a href="<?php echo $helper->url('hakkimizda'); ?>"><i class="fas fa-info-circle"></i> Hakkımızda</a>
                                <a href="<?php echo $helper->url('iletisim'); ?>"><i class="fas fa-envelope"></i> İletişim</a>
                                <a href="<?php echo $helper->url('gizlilik-politikasi'); ?>"><i class="fas fa-shield-alt"></i> Gizlilik Politikası</a>
                                <a href="<?php echo $helper->url('kullanim-kosullari'); ?>"><i class="fas fa-file-contract"></i> Kullanım Koşulları</a>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="nav-right">
                    <div class="mobile-menu">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </nav>
        </div>
    </header>