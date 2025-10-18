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
                <h1>KVKK Aydınlatma Metni</h1>
                <p>Kişisel Verilerin Korunması Kanunu kapsamında bilgilendirme</p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="content-wrapper">
                <div class="content-text">
                    <h2>1. Veri Sorumlusu</h2>
                    <p>6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") kapsamında veri sorumlusu sıfatıyla, kişisel verileriniz aşağıda açıklanan kapsamda işlenmektedir.</p>
                    
                    <div class="info-box">
                        <h3>Veri Sorumlusu Bilgileri:</h3>
                        <ul>
                            <li><strong>Unvan:</strong> <?php echo $helper->getSiteName(); ?></li>
                            <li><strong>E-posta:</strong> <?php echo $helper->getSetting('contact_email', 'info@emlaksitesi.com'); ?></li>
                            <li><strong>Telefon:</strong> <?php echo $helper->getSetting('contact_phone', '+90 (212) 555 00 00'); ?></li>
                            <li><strong>Adres:</strong> <?php echo $helper->getSetting('contact_address', 'İstanbul, Türkiye'); ?></li>
                        </ul>
                    </div>

                    <h2>2. İşlenen Kişisel Veriler</h2>
                    <p>Web sitemizi kullanırken aşağıdaki kişisel verileriniz işlenmektedir:</p>
                    
                    <h3>2.1. Kimlik Verileri</h3>
                    <ul>
                        <li>Ad, soyad</li>
                        <li>E-posta adresi</li>
                        <li>Telefon numarası</li>
                    </ul>

                    <h3>2.2. İletişim Verileri</h3>
                    <ul>
                        <li>E-posta adresi</li>
                        <li>Telefon numarası</li>
                        <li>Posta adresi</li>
                    </ul>

                    <h3>2.3. İnternet Sitesi Kullanım Verileri</h3>
                    <ul>
                        <li>IP adresi</li>
                        <li>Çerez bilgileri</li>
                        <li>Tarayıcı bilgileri</li>
                        <li>Sayfa görüntüleme süreleri</li>
                        <li>Arama tercihleri</li>
                    </ul>

                    <h2>3. Kişisel Verilerin İşlenme Amaçları</h2>
                    <p>Kişisel verileriniz aşağıdaki amaçlarla işlenmektedir:</p>
                    <ul>
                        <li>Web sitesi hizmetlerinin sunulması</li>
                        <li>Emlak ilanlarının filtrelenmesi ve kişiselleştirilmesi</li>
                        <li>İletişim taleplerinin yanıtlanması</li>
                        <li>Müşteri memnuniyetinin artırılması</li>
                        <li>Web sitesi performansının iyileştirilmesi</li>
                        <li>Yasal yükümlülüklerin yerine getirilmesi</li>
                        <li>Güvenlik önlemlerinin alınması</li>
                    </ul>

                    <h2>4. Kişisel Verilerin İşlenme Hukuki Sebepleri</h2>
                    <p>Kişisel verileriniz KVKK'nın 5. maddesinde belirtilen aşağıdaki hukuki sebeplere dayanılarak işlenmektedir:</p>
                    <ul>
                        <li><strong>Açık rıza:</strong> İletişim formları aracılığıyla verdiğiniz açık rızanız</li>
                        <li><strong>Sözleşmenin ifası:</strong> Web sitesi hizmetlerinin sunulması</li>
                        <li><strong>Yasal yükümlülük:</strong> Yasal düzenlemelerden kaynaklanan yükümlülükler</li>
                        <li><strong>Meşru menfaat:</strong> Web sitesi güvenliği ve performansının sağlanması</li>
                    </ul>

                    <h2>5. Kişisel Verilerin Paylaşılması</h2>
                    <p>Kişisel verileriniz, yukarıda belirtilen amaçların gerçekleştirilmesi için gerekli olan durumlarda ve KVKK'nın 8. ve 9. maddelerinde öngörülen şartlar çerçevesinde aşağıdaki kişi ve kuruluşlarla paylaşılabilir:</p>
                    <ul>
                        <li>Yasal zorunluluklar çerçevesinde kamu kurum ve kuruluşları</li>
                        <li>Hizmet sağlayıcılarımız (hosting, e-posta servisleri vb.)</li>
                        <li>Mahkeme kararları doğrultusunda ilgili merciler</li>
                    </ul>

                    <h2>6. Kişisel Verilerin Saklanma Süresi</h2>
                    <p>Kişisel verileriniz, işlenme amacının gerektirdiği süre boyunca ve yasal saklama sürelerine uygun olarak saklanmaktadır:</p>
                    <ul>
                        <li><strong>İletişim verileri:</strong> 3 yıl</li>
                        <li><strong>Web sitesi kullanım verileri:</strong> 2 yıl</li>
                        <li><strong>Çerez verileri:</strong> Çerez türüne göre değişken</li>
                    </ul>

                    <h2>7. Veri Güvenliği</h2>
                    <p>Kişisel verilerinizin güvenliğini sağlamak için aşağıdaki teknik ve idari tedbirleri almaktayız:</p>
                    <ul>
                        <li>SSL şifreleme protokolü kullanımı</li>
                        <li>Güvenli sunucu altyapısı</li>
                        <li>Düzenli güvenlik güncellemeleri</li>
                        <li>Erişim yetkilendirme sistemleri</li>
                        <li>Veri yedekleme sistemleri</li>
                        <li>Personel eğitimleri</li>
                    </ul>

                    <h2>8. Veri Sahibinin Hakları</h2>
                    <p>KVKK'nın 11. maddesi uyarınca aşağıdaki haklara sahipsiniz:</p>
                    <ul>
                        <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme</li>
                        <li>İşlenen kişisel verileriniz hakkında bilgi talep etme</li>
                        <li>Kişisel verilerinizin işlenme amacını ve bunların amacına uygun kullanılıp kullanılmadığını öğrenme</li>
                        <li>Yurt içinde veya yurt dışında kişisel verilerinizin aktarıldığı üçüncü kişileri bilme</li>
                        <li>Kişisel verilerinizin eksik veya yanlış işlenmiş olması hâlinde bunların düzeltilmesini isteme</li>
                        <li>Belirli şartlar çerçevesinde kişisel verilerinizin silinmesini veya yok edilmesini isteme</li>
                        <li>Düzeltme, silme ve yok edilme işlemlerinin üçüncü kişilere bildirilmesini isteme</li>
                        <li>İşlenen verilerin münhasıran otomatik sistemler vasıtasıyla analiz edilmesi suretiyle kişinin aleyhine bir sonucun ortaya çıkmasına itiraz etme</li>
                        <li>Kişisel verilerin kanuna aykırı olarak işlenmesi sebebiyle zarara uğraması hâlinde zararın giderilmesini talep etme</li>
                    </ul>

                    <h2>9. Başvuru Yöntemleri</h2>
                    <p>Yukarıda belirtilen haklarınızı kullanmak için aşağıdaki yöntemlerle başvuruda bulunabilirsiniz:</p>
                    
                    <div class="contact-box">
                        <h3>İletişim Bilgileri:</h3>
                        <ul>
                            <li><strong>E-posta:</strong> <?php echo $helper->getSetting('contact_email', 'info@emlaksitesi.com'); ?></li>
                            <li><strong>Telefon:</strong> <?php echo $helper->getSetting('contact_phone', '+90 (212) 555 00 00'); ?></li>
                            <li><strong>Adres:</strong> <?php echo $helper->getSetting('contact_address', 'İstanbul, Türkiye'); ?></li>
                        </ul>
                    </div>

                    <h2>10. Çerezler (Cookies)</h2>
                    <p>Web sitemizde kullanıcı deneyimini iyileştirmek için çerezler kullanılmaktadır. Çerez kullanımı hakkında detaylı bilgi için <a href="<?php echo $helper->url('gizlilik-politikasi'); ?>">Gizlilik Politikası</a> sayfamızı inceleyebilirsiniz.</p>

                    <h2>11. Değişiklikler</h2>
                    <p>Bu aydınlatma metni gerektiğinde güncellenebilir. Önemli değişiklikler web sitemizde duyurulacaktır.</p>

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

        .info-box, .contact-box {
            background: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .info-box h3, .contact-box h3 {
            margin-top: 0;
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
