   <!-- Footer -->
   <footer>
        <div class="footer-top">
            <!-- Brand Section -->
            <div class="footer-brand">
                <h2><?php echo $helper->getSiteName(); ?></h2>
                <p><?php echo $helper->getSetting('site_description', 'Türkiye\'nin en güvenilir ve modern emlak platformu. Hayalinizdeki evi bulmak için yanınızdayız.'); ?></p>
                <div class="footer-social">
                    <a href="<?php echo $helper->getSetting('facebook_url', '#'); ?>" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="<?php echo $helper->getSetting('twitter_url', '#'); ?>" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="<?php echo $helper->getSetting('instagram_url', '#'); ?>" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="<?php echo $helper->getSetting('linkedin_url', '#'); ?>" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="<?php echo $helper->getSetting('youtube_url', '#'); ?>" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h3>Hızlı Erişim</h3>
                <ul>
                    <li><a href="<?php echo $helper->getBaseUrl(); ?>">Ana Sayfa</a></li>
                    <li><a href="<?php echo $helper->url('ilanlar'); ?>">İlanlar</a></li>
                    <li><a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'satilik']); ?>">Satılık</a></li>
                    <li><a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'kiralik']); ?>">Kiralık</a></li>
                    <li><a href="<?php echo $helper->url('hakkimizda'); ?>">Hakkımızda</a></li>
                    <li><a href="<?php echo $helper->url('iletisim'); ?>">İletişim</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="footer-section">
                <h3>Hizmetlerimiz</h3>
                <ul>
                    <li><a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'satilik']); ?>">Satılık İlanlar</a></li>
                    <li><a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'kiralik']); ?>">Kiralık İlanlar</a></li>
                    <li><a href="<?php echo $helper->url('ilanlar', ['transaction_type' => 'gunluk-kiralik']); ?>">Günlük Kiralık</a></li>
                    <li><a href="<?php echo $helper->url('arama'); ?>">İlan Ara</a></li>
                    <li><a href="<?php echo $helper->url('hakkimizda'); ?>">Hakkımızda</a></li>
                    <li><a href="<?php echo $helper->url('iletisim'); ?>">İletişim</a></li>
                </ul>
            </div>

            <!-- Contact & Newsletter -->
            <div class="footer-section">
                <h3>İletişim</h3>
                <ul class="footer-contact-info">
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span><?php echo $helper->getSetting('contact_phone', '+90 (212) 555 00 00'); ?></span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span><?php echo $helper->getSetting('contact_email', 'info@emlaksitesi.com'); ?></span>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo $helper->getSetting('contact_address', 'İstanbul, Türkiye'); ?></span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-left">
                <p>&copy; 2025 1class.com - Tüm hakları saklıdır.</p>
            </div>
            <div class="footer-bottom-right">
                <a href="<?php echo $helper->url('gizlilik-politikasi'); ?>">Gizlilik Politikası</a>
                <a href="<?php echo $helper->url('kullanim-kosullari'); ?>">Kullanım Koşulları</a>
                <a href="<?php echo $helper->url('kvkk'); ?>">KVKK</a>
            </div>
        </div>
    </footer>

    <script src="<?php echo $helper->asset('js/main.js'); ?>"></script>
 
</body>
</html>

