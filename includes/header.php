<?php 
require_once __DIR__. "/../classes/helper.php";
$helper = Helper::getInstance();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>er.com - Hayalinizdeki Ev Burada</title>
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
                    <span>0 (212) 555 00 00</span>
                </div>
                <div class="top-bar-item">
                    <i class="far fa-envelope"></i>
                    <span>info@1class.com</span>
                </div>
            </div>
            <div class="top-bar-right">
                <a href="#" class="social-icon" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="social-icon" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header>
        <div class="main-nav">
            <nav>
                <div class="logo">
                    <img src="https://placehold.co/200x60/3b6cb6/white?text=1class.com&font=roboto" alt="1class.com Logo">
                </div>

                <div class="nav-center">
                    <ul class="nav-links">
                        <li class="dropdown">
                            <span>
                                İLANLAR
                                <i class="fas fa-chevron-down"></i>
                            </span>
                            <div class="dropdown-menu">
                                <a href="#"><i class="fas fa-home"></i> Konut</a>
                                <a href="#"><i class="fas fa-store"></i> İşyeri</a>
                                <a href="#"><i class="fas fa-mountain"></i> Arsa</a>
                                <a href="#"><i class="fas fa-building"></i> Bina</a>
                                <a href="#"><i class="fas fa-warehouse"></i> Turistik Tesis</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <span>
                                SATILIK
                                <i class="fas fa-chevron-down"></i>
                            </span>
                            <div class="dropdown-menu">
                                <a href="#"><i class="fas fa-home"></i> Satılık Daire</a>
                                <a href="#"><i class="fas fa-home-lg-alt"></i> Satılık Villa</a>
                                <a href="#"><i class="fas fa-mountain"></i> Satılık Arsa</a>
                                <a href="#"><i class="fas fa-store"></i> Satılık İşyeri</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <span>
                                KİRALIK
                                <i class="fas fa-chevron-down"></i>
                            </span>
                            <div class="dropdown-menu">
                                <a href="#"><i class="fas fa-home"></i> Kiralık Daire</a>
                                <a href="#"><i class="fas fa-home-lg-alt"></i> Kiralık Villa</a>
                                <a href="#"><i class="fas fa-store"></i> Kiralık İşyeri</a>
                                <a href="#"><i class="fas fa-building"></i> Kiralık Ofis</a>
                            </div>
                        </li>
                        <li>
                            <a href="#gunluk">GÜNLÜK KİRALIK</a>
                        </li>
                        <li class="dropdown">
                            <span>
                                PROJELER
                                <i class="fas fa-chevron-down"></i>
                            </span>
                            <div class="dropdown-menu">
                                <a href="#"><i class="fas fa-building"></i> Konut Projeleri</a>
                                <a href="#"><i class="fas fa-city"></i> Ticari Projeler</a>
                                <a href="#"><i class="fas fa-hotel"></i> Karma Projeler</a>
                            </div>
                        </li>
                        <li class="dropdown">
                            <span>
                                KURUMSAL
                                <i class="fas fa-chevron-down"></i>
                            </span>
                            <div class="dropdown-menu">
                                <a href="#"><i class="fas fa-info-circle"></i> Hakkımızda</a>
                                <a href="#"><i class="fas fa-bullseye"></i> Misyonumuz</a>
                                <a href="#"><i class="fas fa-eye"></i> Vizyonumuz</a>
                                <a href="#"><i class="fas fa-users"></i> Ekibimiz</a>
                                <a href="#"><i class="fas fa-briefcase"></i> Kariyer</a>
                                <a href="#"><i class="fas fa-file-alt"></i> Basın</a>
                            </div>
                        </li>
                        <li>
                            <a href="#iletisim">İLETİŞİM</a>
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