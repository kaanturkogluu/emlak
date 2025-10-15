<?php 

class Helper {
    private static $instance = null;
    
    private function __construct() {
        // Private constructor to prevent direct instantiation
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    

    public function getBaseUrl() {
        return defined('BASE_URL') ? BASE_URL : 'http://localhost/emlak';
    }
    
    /**
     * Site adını getirir
     * @return string
     */
    public function getSiteName() {
        return $this->getSetting('site_name', 'Emlak Sitesi');
    }
    
    /**
     * Site ayarını getirir
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSetting($key, $default = null) {
        static $siteSettings = null;
        
        if ($siteSettings === null) {
            require_once __DIR__ . '/SiteSettings.php';
            $siteSettings = new SiteSettings();
        }
        
        return $siteSettings->get($key, $default);
    }
    
    /**
     * Site ayarını set eder
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function setSetting($key, $value) {
        static $siteSettings = null;
        
        if ($siteSettings === null) {
            require_once __DIR__ . '/SiteSettings.php';
            $siteSettings = new SiteSettings();
        }
        
        return $siteSettings->set($key, $value);
    }
    
    /**
     * Tüm site ayarlarını getirir
     * @return array
     */
    public function getAllSettings() {
        static $siteSettings = null;
        
        if ($siteSettings === null) {
            require_once __DIR__ . '/SiteSettings.php';
            $siteSettings = new SiteSettings();
        }
        
        return $siteSettings->getAll();
    }
    
    /**
     * SEO dostu URL oluşturur
     * @param string $page - Sayfa adı (örn: 'ilanlar', 'hakkimizda')
     * @param array $params - URL parametreleri
     * @return string
     */
    public function url($page = '', $params = []) {
        $baseUrl = rtrim($this->getBaseUrl(), '/');
        
        if (empty($page)) {
            return $baseUrl . '/';
        }
        
        $url = $baseUrl . '/' . $page;
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }
    
    /**
     * Kategori URL'i oluşturur (örn: satilik-daire, kiralik-villa)
     * @param string $type - İlan tipi (satilik, kiralik, gunluk-kiralik)
     * @param string $category - Kategori (daire, villa, arsa, isyeri)
     * @param array $params - Ek parametreler
     * @return string
     */
    public function categoryUrl($type, $category = '', $params = []) {
        $url = $this->url($type);
        
        if (!empty($category)) {
            $url = rtrim($url, '/') . '-' . $category;
        }
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }
    
    /**
     * İlan detay URL'i oluşturur
     * @param string $slug - İlan slug'ı
     * @return string
     */
    public function propertyUrl($slug) {
        return $this->url('ilan') . '/' . $slug;
    }
    
    /**
     * Şehir URL'i oluşturur
     * @param string $citySlug - Şehir slug'ı
     * @param array $params - Ek parametreler
     * @return string
     */
    public function cityUrl($citySlug, $params = []) {
        $url = $this->url('sehir') . '/' . $citySlug;
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }
    
    /**
     * İlçe URL'i oluşturur
     * @param string $citySlug - Şehir slug'ı
     * @param string $districtSlug - İlçe slug'ı
     * @param array $params - Ek parametreler
     * @return string
     */
    public function districtUrl($citySlug, $districtSlug, $params = []) {
        $url = $this->url('sehir') . '/' . $citySlug . '/' . $districtSlug;
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }
    
    /**
     * Asset URL'i oluşturur (CSS, JS, resim dosyaları için)
     * @param string $path - Dosya yolu
     * @return string
     */
    public function asset($path) {
        $baseUrl = rtrim($this->getBaseUrl(), '/');
        return $baseUrl . '/assets/' . ltrim($path, '/');
    }
    
    /**
     * SEO dostu slug oluşturur
     * @param string $text - Dönüştürülecek metin
     * @return string
     */
    public function createSlug($text) {
        // Türkçe karakterleri değiştir
        $turkish = ['ç', 'ğ', 'ı', 'ö', 'ş', 'ü', 'Ç', 'Ğ', 'I', 'İ', 'Ö', 'Ş', 'Ü'];
        $english = ['c', 'g', 'i', 'o', 's', 'u', 'c', 'g', 'i', 'i', 'o', 's', 'u'];
        $text = str_replace($turkish, $english, $text);
        
        // Küçük harfe çevir
        $text = strtolower($text);
        
        // Özel karakterleri kaldır ve tire ile değiştir
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        
        // Başındaki ve sonundaki tireleri kaldır
        return trim($text, '-');
    }
    
    /**
     * HTML escape fonksiyonu
     * @param string $text
     * @return string
     */
    public function e($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Fiyat formatlama
     * @param float $price
     * @return string
     */
    public function formatPrice($price) {
        return number_format($price, 0, ',', '.') . ' ₺';
    }
    // Prevent cloning
    private function __clone() {}
    
    // Prevent unserialization
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
?>