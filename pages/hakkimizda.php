<?php
// Session başlat - en başta olmalı
session_start();

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../classes/Helper.php";

$pageTitle = 'Hakkımızda - er.com';
$pageDescription = 'er.com hakkında bilgi alın. Türkiye\'nin en güvenilir emlak platformu olarak hizmet veriyoruz.';
$pageKeywords = 'hakkımızda, er.com, emlak, güvenilir, platform';

require_once __DIR__ . "/../includes/header.php";
$helper = Helper::getInstance();
?>

<!-- Hakkımızda Hero Section -->
<section class="page-hero" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 6rem 2rem 4rem; color: white; text-align: center;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-size: 3rem; margin-bottom: 1rem; font-weight: 800;">Hakkımızda</h1>
        <p style="font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto;">
            Türkiye'nin en güvenilir emlak platformu olarak, hayalinizdeki evi bulmanız için yanınızdayız.
        </p>
    </div>
</section>

<!-- Hakkımızda İçerik -->
<section style="padding: 4rem 2rem; background: #f8f9fa;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; margin-bottom: 4rem;">
            <div>
                <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem; color: #2c3e50;">Kimiz Biz?</h2>
                <p style="font-size: 1.1rem; line-height: 1.8; color: #555; margin-bottom: 1.5rem;">
                    er.com olarak, 2020 yılından bu yana emlak sektöründe faaliyet gösteren, teknoloji odaklı bir platformuz. 
                    Müşteri memnuniyetini ön planda tutarak, güvenilir ve şeffaf bir emlak deneyimi sunmayı hedefliyoruz.
                </p>
                <p style="font-size: 1.1rem; line-height: 1.8; color: #555;">
                    Binlerce başarılı emlak işlemi gerçekleştirdik ve müşterilerimizin hayallerini gerçeğe dönüştürdük. 
                    Uzman ekibimiz ve gelişmiş teknoloji altyapımızla, emlak alım-satım sürecinizi kolaylaştırıyoruz.
                </p>
            </div>
            <div style="text-align: center;">
                <img src="<?php echo $helper->asset('images/about-us.jpg'); ?>" 
                     alt="Hakkımızda" 
                     style="width: 100%; max-width: 500px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);"
                     onerror="this.src='https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=500&h=400&fit=crop'">
            </div>
        </div>

        <!-- Misyonumuz -->
        <div style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
            <h3 style="font-size: 2rem; margin-bottom: 1.5rem; color: #2c3e50; text-align: center;">Misyonumuz</h3>
            <p style="font-size: 1.1rem; line-height: 1.8; color: #555; text-align: center; max-width: 800px; margin: 0 auto;">
                Emlak sektöründe güven, şeffaflık ve müşteri memnuniyetini ön planda tutarak, 
                teknoloji ile geleneksel emlak hizmetlerini harmanlayan, yenilikçi çözümler sunmak.
            </p>
        </div>

        <!-- Vizyonumuz -->
        <div style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 3rem;">
            <h3 style="font-size: 2rem; margin-bottom: 1.5rem; color: #2c3e50; text-align: center;">Vizyonumuz</h3>
            <p style="font-size: 1.1rem; line-height: 1.8; color: #555; text-align: center; max-width: 800px; margin: 0 auto;">
                Türkiye'nin en büyük ve en güvenilir emlak platformu olmak, 
                müşterilerimizin hayallerini gerçeğe dönüştüren bir marka haline gelmek.
            </p>
        </div>

        <!-- Değerlerimiz -->
        <div style="background: white; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <h3 style="font-size: 2rem; margin-bottom: 2rem; color: #2c3e50; text-align: center;">Değerlerimiz</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                <div style="text-align: center; padding: 1.5rem;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="fas fa-shield-alt" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h4 style="font-size: 1.3rem; margin-bottom: 1rem; color: #2c3e50;">Güvenilirlik</h4>
                    <p style="color: #555; line-height: 1.6;">Tüm işlemlerimizde şeffaflık ve güvenilirlik ilkesini benimseriz.</p>
                </div>
                <div style="text-align: center; padding: 1.5rem;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="fas fa-users" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h4 style="font-size: 1.3rem; margin-bottom: 1rem; color: #2c3e50;">Müşteri Odaklılık</h4>
                    <p style="color: #555; line-height: 1.6;">Müşteri memnuniyeti bizim için en önemli önceliktir.</p>
                </div>
                <div style="text-align: center; padding: 1.5rem;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="fas fa-lightbulb" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h4 style="font-size: 1.3rem; margin-bottom: 1rem; color: #2c3e50;">Yenilikçilik</h4>
                    <p style="color: #555; line-height: 1.6;">Sürekli gelişim ve yenilik anlayışıyla hizmet veriyoruz.</p>
                </div>
                <div style="text-align: center; padding: 1.5rem;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="fas fa-handshake" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h4 style="font-size: 1.3rem; margin-bottom: 1rem; color: #2c3e50;">Etik Değerler</h4>
                    <p style="color: #555; line-height: 1.6;">Tüm işlemlerimizde etik değerlere uygun davranırız.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- İstatistikler -->
<section style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 4rem 2rem; color: white;">
    <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
        <h2 style="font-size: 2.5rem; margin-bottom: 3rem; font-weight: 800;">Rakamlarla er.com</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
            <div>
                <div style="font-size: 3rem; font-weight: bold; margin-bottom: 1rem;">5000+</div>
                <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem;">Başarılı İşlem</h3>
                <p style="opacity: 0.9;">Müşteri memnuniyeti ile tamamlanan işlemler</p>
            </div>
            <div>
                <div style="font-size: 3rem; font-weight: bold; margin-bottom: 1rem;">50+</div>
                <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem;">Şehir</h3>
                <p style="opacity: 0.9;">Türkiye genelinde hizmet verdiğimiz şehirler</p>
            </div>
            <div>
                <div style="font-size: 3rem; font-weight: bold; margin-bottom: 1rem;">10000+</div>
                <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem;">Mutlu Müşteri</h3>
                <p style="opacity: 0.9;">Hayallerini gerçekleştiren müşterilerimiz</p>
            </div>
            <div>
                <div style="font-size: 3rem; font-weight: bold; margin-bottom: 1rem;">24/7</div>
                <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem;">Destek</h3>
                <p style="opacity: 0.9;">Kesintisiz müşteri hizmetleri</p>
            </div>
        </div>
    </div>
</section>

<!-- Ekibimiz -->
<section style="padding: 4rem 2rem; background: #f8f9fa;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: #2c3e50; text-align: center;">Ekibimiz</h2>
        <p style="font-size: 1.1rem; color: #555; text-align: center; margin-bottom: 3rem; max-width: 600px; margin-left: auto; margin-right: auto;">
            Deneyimli ve uzman ekibimizle, emlak sektöründe fark yaratıyoruz.
        </p>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face" 
                     alt="Ahmet Yılmaz" 
                     style="width: 120px; height: 120px; border-radius: 50%; margin-bottom: 1rem; object-fit: cover;">
                <h4 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: #2c3e50;">Ahmet Yılmaz</h4>
                <p style="color: #667eea; font-weight: 600; margin-bottom: 1rem;">Genel Müdür</p>
                <p style="color: #555; line-height: 1.6;">15 yıllık emlak deneyimi ile sektörde öncü isimlerden biri.</p>
            </div>
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
                <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=150&h=150&fit=crop&crop=face" 
                     alt="Ayşe Demir" 
                     style="width: 120px; height: 120px; border-radius: 50%; margin-bottom: 1rem; object-fit: cover;">
                <h4 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: #2c3e50;">Ayşe Demir</h4>
                <p style="color: #667eea; font-weight: 600; margin-bottom: 1rem;">Satış Müdürü</p>
                <p style="color: #555; line-height: 1.6;">Müşteri ilişkileri ve satış stratejileri konusunda uzman.</p>
            </div>
            <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center;">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face" 
                     alt="Mehmet Kaya" 
                     style="width: 120px; height: 120px; border-radius: 50%; margin-bottom: 1rem; object-fit: cover;">
                <h4 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: #2c3e50;">Mehmet Kaya</h4>
                <p style="color: #667eea; font-weight: 600; margin-bottom: 1rem;">Teknik Müdür</p>
                <p style="color: #555; line-height: 1.6;">Teknoloji altyapısı ve dijital çözümler konusunda uzman.</p>
            </div>
        </div>
    </div>
</section>

<!-- İletişim CTA -->
<section style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 4rem 2rem; color: white; text-align: center;">
    <div style="max-width: 800px; margin: 0 auto;">
        <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem; font-weight: 800;">Bizimle İletişime Geçin</h2>
        <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.9;">
            Emlak ihtiyaçlarınız için uzman ekibimizle görüşmek ister misiniz?
        </p>
        <a href="<?php echo $helper->url('iletisim'); ?>" 
           style="display: inline-block; background: white; color: #667eea; padding: 1rem 2rem; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease;">
            <i class="fas fa-phone" style="margin-right: 0.5rem;"></i>
            İletişime Geç
        </a>
    </div>
</section>

<style>
/* Mobil Uyumluluk */
@media (max-width: 768px) {
    .page-hero h1 {
        font-size: 2rem !important;
    }
    
    .page-hero p {
        font-size: 1rem !important;
    }
    
    /* Ana içerik bölümü - resim üstte, yazı altta */
    section > div > div:first-child {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
    
    /* Resim ve yazı sıralamasını değiştir */
    section > div > div:first-child {
        display: flex !important;
        flex-direction: column !important;
    }
    
    /* Resmi üste al */
    section > div > div:first-child > div:last-child {
        order: -1 !important;
    }
    
    /* Yazıyı alta al */
    section > div > div:first-child > div:first-child {
        order: 1 !important;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    
    .team-grid {
        grid-template-columns: 1fr !important;
    }
    
    .values-grid {
        grid-template-columns: 1fr !important;
    }
    
    /* Diğer grid'ler için de düzenleme */
    section > div {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
}
</style>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
