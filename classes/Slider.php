<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Database.php';

class Slider {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Tüm slider'ları getir
     * @return array
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("
                SELECT * FROM sliders 
                ORDER BY sort_order ASC, created_at DESC
            ");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Aktif slider'ları getir
     * @return array
     */
    public function getActive() {
        try {
            $stmt = $this->db->query("
                SELECT * FROM sliders 
                WHERE status = 'active' 
                ORDER BY sort_order ASC, created_at DESC
            ");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * ID'ye göre slider getir
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM sliders WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Yeni slider oluştur
     * @param array $data
     * @return bool
     */
    public function create($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO sliders (title, subtitle, button_text, button_url, image, sort_order, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            return $stmt->execute([
                $data['title'],
                $data['subtitle'] ?? '',
                $data['button_text'] ?? '',
                $data['button_url'] ?? '',
                $data['image'],
                $data['sort_order'] ?? 0,
                $data['status'] ?? 'active'
            ]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Slider güncelle
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        try {
            // Eğer image alanı gönderilmişse güncelle, yoksa mevcut image'ı koru
            if (isset($data['image']) && !empty($data['image'])) {
                $stmt = $this->db->prepare("
                    UPDATE sliders 
                    SET title = ?, subtitle = ?, button_text = ?, button_url = ?, image = ?, sort_order = ?, status = ? 
                    WHERE id = ?
                ");
                
                return $stmt->execute([
                    $data['title'],
                    $data['subtitle'] ?? '',
                    $data['button_text'] ?? '',
                    $data['button_url'] ?? '',
                    $data['image'],
                    $data['sort_order'] ?? 0,
                    $data['status'] ?? 'active',
                    $id
                ]);
            } else {
                // Image alanını güncelleme, sadece diğer alanları güncelle
                $stmt = $this->db->prepare("
                    UPDATE sliders 
                    SET title = ?, subtitle = ?, button_text = ?, button_url = ?, sort_order = ?, status = ? 
                    WHERE id = ?
                ");
                
                return $stmt->execute([
                    $data['title'],
                    $data['subtitle'] ?? '',
                    $data['button_text'] ?? '',
                    $data['button_url'] ?? '',
                    $data['sort_order'] ?? 0,
                    $data['status'] ?? 'active',
                    $id
                ]);
            }
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Slider sil
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM sliders WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Slider durumunu değiştir
     * @param int $id
     * @return bool
     */
    public function toggleStatus($id) {
        try {
            $stmt = $this->db->prepare("
                UPDATE sliders 
                SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END 
                WHERE id = ?
            ");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Slider sıralamasını güncelle
     * @param array $sortData - [['id' => 1, 'sort_order' => 1], ...]
     * @return bool
     */
    public function updateSortOrder($sortData) {
        try {
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare("UPDATE sliders SET sort_order = ? WHERE id = ?");
            
            foreach ($sortData as $item) {
                $stmt->execute([$item['sort_order'], $item['id']]);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    /**
     * Toplam slider sayısını getir
     * @return int
     */
    public function getTotalCount() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM sliders");
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Aktif slider sayısını getir
     * @return int
     */
    public function getActiveCount() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM sliders WHERE status = 'active'");
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
}
?>
