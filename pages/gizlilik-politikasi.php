<?php
// Session başlat - en başta olmalı
session_start();

require_once __DIR__."/../includes/header.php";
require_once __DIR__."/../classes/Helper.php";

$helper = Helper::getInstance();
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1>Gizlilik Politikası</h1>
                <p>Kişisel verilerinizin korunması bizim için önemlidir</p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-text">
                    <h2>1. Giriş</h2>
                    <p>Bu gizlilik politikası, <?php echo $helper->getSiteName(); ?> web sitesini ziyaret ettiğinizde kişisel bilgilerinizin nasıl toplandığını, kullanıldığını ve korunduğunu açıklamaktadır.</p>

                    <h2>2. Toplanan Bilgiler</h2>
                    <p>Web sitemizi kullanırken aşağıdaki bilgileri toplayabiliriz:</p>
                    <ul>
                        <li>İletişim formları aracılığıyla sağladığınız ad, e-posta, telefon numarası</li>
                        <li>Web sitesi kullanım verileri (IP adresi, tarayıcı türü, sayfa görüntüleme süreleri)</li>
                        <li>Çerezler (cookies) aracılığıyla toplanan bilgiler</li>
                        <li>Emlak ilanları ile ilgili arama tercihleriniz</li>
                    </ul>

                    <h2>3. Bilgilerin Kullanımı</h2>
                    <p>Topladığımız bilgileri aşağıdaki amaçlarla kullanırız:</p>
                    <ul>
                        <li>Size daha iyi hizmet sunmak</li>
                        <li>Emlak ilanlarını size uygun şekilde filtrelemek</li>
                        <li>İletişim taleplerinizi yanıtlamak</li>
                        <li>Web sitesi performansını iyileştirmek</li>
                        <li>Yasal yükümlülüklerimizi yerine getirmek</li>
                    </ul>

                    <h2>4. Bilgi Paylaşımı</h2>
                    <p>Kişisel bilgilerinizi üçüncü taraflarla paylaşmayız, ancak aşağıdaki durumlar hariç:</p>
                    <ul>
                        <li>Yasal zorunluluklar</li>
                        <li>Mahkeme kararları</li>
                        <li>Kamu güvenliği gereksinimleri</li>
                        <li>Hizmet sağlayıcılarımız (sadece gerekli olduğunda)</li>
                    </ul>

                    <h2>5. Veri Güvenliği</h2>
                    <p>Kişisel bilgilerinizi korumak için uygun teknik ve organizasyonel önlemler alırız:</p>
                    <ul>
                        <li>SSL şifreleme kullanımı</li>
                        <li>Güvenli sunucu altyapısı</li>
                        <li>Düzenli güvenlik güncellemeleri</li>
                        <li>Erişim kontrolleri</li>
                    </ul>

                    <h2>6. Çerezler (Cookies)</h2>
                    <p>Web sitemizde kullanıcı deneyimini iyileştirmek için çerezler kullanırız. Çerezler, web sitesinin daha iyi çalışmasını sağlar ve size kişiselleştirilmiş içerik sunar.</p>

                    <h2>7. Kullanıcı Hakları</h2>
                    <p>KVKK kapsamında aşağıdaki haklara sahipsiniz:</p>
                    <ul>
                        <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme</li>
                        <li>İşlenen kişisel verileriniz hakkında bilgi talep etme</li>
                        <li>Kişisel verilerinizin işlenme amacını ve bunların amacına uygun kullanılıp kullanılmadığını öğrenme</li>
                        <li>Yurt içinde veya yurt dışında kişisel verilerinizin aktarıldığı üçüncü kişileri bilme</li>
                        <li>Kişisel verilerinizin eksik veya yanlış işlenmiş olması hâlinde bunların düzeltilmesini isteme</li>
                        <li>Belirli şartlar çerçevesinde kişisel verilerinizin silinmesini veya yok edilmesini isteme</li>
                    </ul>

                    <h2>8. İletişim</h2>
                    <p>Gizlilik politikamız hakkında sorularınız için bizimle iletişime geçebilirsiniz:</p>
                    <ul>
                        <li>E-posta: <?php echo $helper->getSetting('contact_email', 'info@emlaksitesi.com'); ?></li>
                        <li>Telefon: <?php echo $helper->getSetting('contact_phone', '+90 (212) 555 00 00'); ?></li>
                        <li>Adres: <?php echo $helper->getSetting('contact_address', 'İstanbul, Türkiye'); ?></li>
                    </ul>

                    <h2>9. Politika Güncellemeleri</h2>
                    <p>Bu gizlilik politikası gerektiğinde güncellenebilir. Önemli değişiklikler web sitemizde duyurulacaktır.</p>

                    <p><strong>Son güncelleme tarihi:</strong> <?php echo date('d.m.Y'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <style>
        .content-section {
            padding: 60px 0;
            background: #f8f9fa;
        }

        .content-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .content-text h2 {
            color: #2c3e50;
            margin-top: 30px;
            margin-bottom: 15px;
            font-size: 1.5rem;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .content-text h2:first-child {
            margin-top: 0;
        }

        .content-text p {
            line-height: 1.8;
            margin-bottom: 15px;
            color: #555;
        }

        .content-text ul {
            margin: 15px 0;
            padding-left: 20px;
        }

        .content-text li {
            margin-bottom: 8px;
            line-height: 1.6;
            color: #555;
        }

        .content-text strong {
            color: #2c3e50;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 20px;
                margin: 0 15px;
            }
            
            .content-text h2 {
                font-size: 1.3rem;
            }
        }
    </style>

<?php require_once __DIR__."/../includes/footer.php"; ?>
