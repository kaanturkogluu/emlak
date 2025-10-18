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
                <h1>Kullanım Koşulları</h1>
                <p>Web sitemizi kullanım şartları ve koşulları</p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-text">
                    <h2>1. Genel Hükümler</h2>
                    <p>Bu kullanım koşulları, <?php echo $helper->getSiteName(); ?> web sitesini kullanan tüm kullanıcılar için geçerlidir. Web sitemizi kullanarak bu koşulları kabul etmiş sayılırsınız.</p>

                    <h2>2. Hizmet Tanımı</h2>
                    <p>Web sitemiz aşağıdaki hizmetleri sunmaktadır:</p>
                    <ul>
                        <li>Emlak ilanlarının görüntülenmesi</li>
                        <li>Emlak arama ve filtreleme</li>
                        <li>İlan detaylarının incelenmesi</li>
                        <li>İletişim formları aracılığıyla bilgi alışverişi</li>
                        <li>Emlak sektörü hakkında bilgilendirme</li>
                    </ul>

                    <h2>3. Kullanıcı Yükümlülükleri</h2>
                    <p>Web sitemizi kullanırken aşağıdaki kurallara uymanız gerekmektedir:</p>
                    
                    <h3>3.1. Genel Kurallar</h3>
                    <ul>
                        <li>Web sitesini yasalara uygun şekilde kullanmak</li>
                        <li>Başkalarının haklarını ihlal etmemek</li>
                        <li>Zararlı yazılım yüklememek</li>
                        <li>Web sitesinin güvenliğini tehdit edecek eylemlerde bulunmamak</li>
                        <li>Telif hakları ve fikri mülkiyet haklarına saygı göstermek</li>
                    </ul>

                    <h3>3.2. İletişim Kuralları</h3>
                    <ul>
                        <li>Doğru ve güncel bilgiler vermek</li>
                        <li>Hakaret, küfür veya tehdit içeren mesajlar göndermemek</li>
                        <li>Spam veya istenmeyen içerik göndermemek</li>
                        <li>Başkalarının kişisel bilgilerini izinsiz kullanmamak</li>
                    </ul>

                    <h2>4. Fikri Mülkiyet Hakları</h2>
                    <p>Web sitemizdeki tüm içerikler (metin, görsel, logo, tasarım vb.) telif hakları ile korunmaktadır. Bu içerikler:</p>
                    <ul>
                        <li>Kişisel kullanım için görüntülenebilir</li>
                        <li>İzinsiz kopyalanamaz, çoğaltılamaz</li>
                        <li>Ticari amaçlarla kullanılamaz</li>
                        <li>Değiştirilemez veya dağıtılamaz</li>
                    </ul>

                    <h2>5. İlan İçerikleri</h2>
                    <p>Web sitemizde yayınlanan emlak ilanları:</p>
                    <ul>
                        <li>Üçüncü taraflar tarafından sağlanmaktadır</li>
                        <li>Doğruluğu garanti edilmemektedir</li>
                        <li>Güncel olmayabilir</li>
                        <li>İlan sahiplerinin sorumluluğundadır</li>
                    </ul>

                    <h2>6. Sorumluluk Sınırlamaları</h2>
                    <p>Web sitemiz aşağıdaki konularda sorumluluk kabul etmemektedir:</p>
                    <ul>
                        <li>İlan içeriklerinin doğruluğu</li>
                        <li>Emlak işlemlerinin sonuçları</li>
                        <li>Üçüncü taraflarla yapılan anlaşmalar</li>
                        <li>Web sitesi erişim kesintileri</li>
                        <li>Teknik arızalar</li>
                        <li>Veri kayıpları</li>
                    </ul>

                    <h2>7. Hizmet Değişiklikleri</h2>
                    <p>Web sitemizde aşağıdaki değişiklikleri yapma hakkımız saklıdır:</p>
                    <ul>
                        <li>Hizmet içeriklerini güncelleme</li>
                        <li>Yeni özellikler ekleme</li>
                        <li>Mevcut özellikleri kaldırma</li>
                        <li>Web sitesi tasarımını değiştirme</li>
                        <li>Kullanım koşullarını güncelleme</li>
                    </ul>

                    <h2>8. Hesap Askıya Alma ve Sonlandırma</h2>
                    <p>Aşağıdaki durumlarda hesabınızı askıya alabilir veya sonlandırabiliriz:</p>
                    <ul>
                        <li>Kullanım koşullarını ihlal etmeniz</li>
                        <li>Yasalara aykırı davranışlarda bulunmanız</li>
                        <li>Başkalarının haklarını ihlal etmeniz</li>
                        <li>Web sitesinin güvenliğini tehdit etmeniz</li>
                        <li>Spam veya zararlı içerik paylaşmanız</li>
                    </ul>

                    <h2>9. Gizlilik</h2>
                    <p>Kişisel verilerinizin işlenmesi hakkında detaylı bilgi için <a href="<?php echo $helper->url('gizlilik-politikasi'); ?>">Gizlilik Politikası</a> ve <a href="<?php echo $helper->url('kvkk'); ?>">KVKK Aydınlatma Metni</a> sayfalarımızı inceleyebilirsiniz.</p>

                    <h2>10. Çerezler</h2>
                    <p>Web sitemizde kullanıcı deneyimini iyileştirmek için çerezler kullanılmaktadır. Çerez kullanımı hakkında detaylı bilgi için <a href="<?php echo $helper->url('gizlilik-politikasi'); ?>">Gizlilik Politikası</a> sayfamızı inceleyebilirsiniz.</p>

                    <h2>11. Uygulanacak Hukuk</h2>
                    <p>Bu kullanım koşulları Türk hukukuna tabidir. Herhangi bir uyuşmazlık durumunda Türkiye Cumhuriyeti mahkemeleri yetkilidir.</p>

                    <h2>12. İletişim</h2>
                    <p>Kullanım koşulları hakkında sorularınız için bizimle iletişime geçebilirsiniz:</p>
                    
                    <div class="contact-box">
                        <ul>
                            <li><strong>E-posta:</strong> <?php echo $helper->getSetting('contact_email', 'info@emlaksitesi.com'); ?></li>
                            <li><strong>Telefon:</strong> <?php echo $helper->getSetting('contact_phone', '+90 (212) 555 00 00'); ?></li>
                            <li><strong>Adres:</strong> <?php echo $helper->getSetting('contact_address', 'İstanbul, Türkiye'); ?></li>
                        </ul>
                    </div>

                    <h2>13. Değişiklikler</h2>
                    <p>Bu kullanım koşulları gerektiğinde güncellenebilir. Önemli değişiklikler web sitemizde duyurulacaktır. Değişiklikler yayınlandığı tarihten itibaren geçerli olacaktır.</p>

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

        .content-text h3 {
            color: #34495e;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 1.2rem;
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

        .content-text a {
            color: #3498db;
            text-decoration: none;
        }

        .content-text a:hover {
            text-decoration: underline;
        }

        .contact-box {
            background: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
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
