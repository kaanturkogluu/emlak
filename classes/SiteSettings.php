<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Database.php';

class SiteSettings {
    private $db;
    private $settings = [];
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->loadSettings();
    }
    
    /**
     * Tüm ayarları veritabanından yükle
     */
    private function loadSettings() {
        try {
            $stmt = $this->db->prepare("SELECT setting_key, setting_value, setting_type FROM site_settings");
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            foreach ($results as $row) {
                $value = $row['setting_value'];
                
                // Tip dönüşümü
                switch ($row['setting_type']) {
                    case 'number':
                        $value = is_numeric($value) ? (float)$value : 0;
                        break;
                    case 'boolean':
                        $value = (bool)$value;
                        break;
                    case 'json':
                        $value = json_decode($value, true);
                        break;
                }
                
                $this->settings[$row['setting_key']] = $value;
            }
        } catch (Exception $e) {
            error_log("SiteSettings load error: " . $e->getMessage());
        }
    }
    
    /**
     * Ayar değerini getir
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null) {
        return $this->settings[$key] ?? $default;
    }
    
    /**
     * Ayar değerini set et
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function set($key, $value) {
        try {
            // Tip belirleme
            $type = 'text';
            if (is_bool($value)) {
                $type = 'boolean';
                $value = $value ? '1' : '0';
            } elseif (is_numeric($value)) {
                $type = 'number';
            } elseif (is_array($value) || is_object($value)) {
                $type = 'json';
                $value = json_encode($value);
            }
            
            // Veritabanında güncelle veya ekle
            $stmt = $this->db->prepare("
                INSERT INTO site_settings (setting_key, setting_value, setting_type) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                setting_value = VALUES(setting_value), 
                setting_type = VALUES(setting_type),
                updated_at = CURRENT_TIMESTAMP
            ");
            
            $result = $stmt->execute([$key, $value, $type]);
            
            if ($result) {
                // Cache'i güncelle
                $this->settings[$key] = $value;
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("SiteSettings set error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Birden fazla ayarı set et
     * @param array $settings
     * @return bool
     */
    public function setMultiple($settings) {
        try {
            $this->db->beginTransaction();
            
            foreach ($settings as $key => $value) {
                if (!$this->set($key, $value)) {
                    $this->db->rollBack();
                    return false;
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("SiteSettings setMultiple error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tüm ayarları getir
     * @return array
     */
    public function getAll() {
        return $this->settings;
    }
    
    /**
     * Kategoriye göre ayarları getir
     * @param string $category
     * @return array
     */
    public function getByCategory($category) {
        try {
            $stmt = $this->db->prepare("
                SELECT setting_key, setting_value, setting_type, description 
                FROM site_settings 
                WHERE category = ? 
                ORDER BY setting_key
            ");
            $stmt->execute([$category]);
            $results = $stmt->fetchAll();
            
            $settings = [];
            foreach ($results as $row) {
                $value = $row['setting_value'];
                
                // Tip dönüşümü
                switch ($row['setting_type']) {
                    case 'number':
                        $value = is_numeric($value) ? (float)$value : 0;
                        break;
                    case 'boolean':
                        $value = (bool)$value;
                        break;
                    case 'json':
                        $value = json_decode($value, true);
                        break;
                }
                
                $settings[$row['setting_key']] = [
                    'value' => $value,
                    'description' => $row['description']
                ];
            }
            
            return $settings;
        } catch (Exception $e) {
            error_log("SiteSettings getByCategory error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Ayarı sil
     * @param string $key
     * @return bool
     */
    public function delete($key) {
        try {
            $stmt = $this->db->prepare("DELETE FROM site_settings WHERE setting_key = ?");
            $result = $stmt->execute([$key]);
            
            if ($result) {
                unset($this->settings[$key]);
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("SiteSettings delete error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cache'i temizle ve yeniden yükle
     */
    public function refresh() {
        $this->settings = [];
        $this->loadSettings();
    }
}
?>
