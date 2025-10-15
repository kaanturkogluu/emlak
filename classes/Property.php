<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Database.php';

class Property {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Tüm ilanları getir
     * @param array $filters
     * @return array
     */
    public function getAll($filters = []) {
        try {
            $sql = "SELECT p.*, c.name as city_name, d.name as district_name 
                    FROM properties p 
                    LEFT JOIN cities c ON p.city_id = c.id 
                    LEFT JOIN districts d ON p.district_id = d.id 
                    WHERE 1=1";
            $params = [];
            
            // Filtreler
            if (!empty($filters['transaction_type'])) {
                $sql .= " AND p.transaction_type = :transaction_type";
                $params['transaction_type'] = $filters['transaction_type'];
            } elseif (!empty($filters['type'])) {
                $sql .= " AND p.transaction_type = :type";
                $params['type'] = $filters['type'];
            }
            
            if (!empty($filters['property_type'])) {
                $sql .= " AND p.property_type = :property_type";
                $params['property_type'] = $filters['property_type'];
            } elseif (!empty($filters['category'])) {
                $sql .= " AND p.property_type = :category";
                $params['category'] = $filters['category'];
            }
            
            if (!empty($filters['city'])) {
                $sql .= " AND c.name = :city";
                $params['city'] = $filters['city'];
            }
            
            if (!empty($filters['district'])) {
                $sql .= " AND d.name = :district";
                $params['district'] = $filters['district'];
            }
            
            if (!empty($filters['min_price'])) {
                $sql .= " AND p.price >= :min_price";
                $params['min_price'] = $filters['min_price'];
            }
            
            if (!empty($filters['max_price'])) {
                $sql .= " AND p.price <= :max_price";
                $params['max_price'] = $filters['max_price'];
            }
            
            if (!empty($filters['rooms'])) {
                $sql .= " AND p.room_count = :rooms";
                $params['rooms'] = $filters['rooms'];
            }
            
            if (!empty($filters['status'])) {
                $sql .= " AND p.status = :status";
                $params['status'] = $filters['status'];
            }
            
            // Sıralama
            $orderBy = $filters['order_by'] ?? 'created_at';
            $orderDir = $filters['order_dir'] ?? 'DESC';
            $sql .= " ORDER BY p.{$orderBy} {$orderDir}";
            
            // Sayfalama
            if (!empty($filters['limit'])) {
                $sql .= " LIMIT :limit";
                $params['limit'] = (int)$filters['limit'];
                
                if (!empty($filters['offset'])) {
                    $sql .= " OFFSET :offset";
                    $params['offset'] = (int)$filters['offset'];
                }
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * ID'ye göre ilan getir
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM properties WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    }
    
    
    /**
     * Toplam ilan sayısını getir
     * @param array $filters
     * @return int
     */
    public function getCount($filters = []) {
        try {
            $sql = "SELECT COUNT(*) FROM properties p 
                    LEFT JOIN cities c ON p.city_id = c.id 
                    LEFT JOIN districts d ON p.district_id = d.id 
                    WHERE 1=1";
            $params = [];
            
            // Filtreler (getAll ile aynı)
            if (!empty($filters['transaction_type'])) {
                $sql .= " AND p.transaction_type = :transaction_type";
                $params['transaction_type'] = $filters['transaction_type'];
            } elseif (!empty($filters['type'])) {
                $sql .= " AND p.transaction_type = :type";
                $params['type'] = $filters['type'];
            }
            
            if (!empty($filters['property_type'])) {
                $sql .= " AND p.property_type = :property_type";
                $params['property_type'] = $filters['property_type'];
            } elseif (!empty($filters['category'])) {
                $sql .= " AND p.property_type = :category";
                $params['category'] = $filters['category'];
            }
            
            if (!empty($filters['city'])) {
                $sql .= " AND c.name = :city";
                $params['city'] = $filters['city'];
            }
            
            if (!empty($filters['district'])) {
                $sql .= " AND d.name = :district";
                $params['district'] = $filters['district'];
            }
            
            if (!empty($filters['min_price'])) {
                $sql .= " AND p.price >= :min_price";
                $params['min_price'] = $filters['min_price'];
            }
            
            if (!empty($filters['max_price'])) {
                $sql .= " AND p.price <= :max_price";
                $params['max_price'] = $filters['max_price'];
            }
            
            if (!empty($filters['rooms'])) {
                $sql .= " AND p.room_count = :rooms";
                $params['rooms'] = $filters['rooms'];
            }
            
            if (!empty($filters['status'])) {
                $sql .= " AND p.status = :status";
                $params['status'] = $filters['status'];
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Şehir listesini getir
     * @return array
     */
    public function getCities() {
        try {
            $stmt = $this->db->query("SELECT DISTINCT c.name FROM cities c INNER JOIN properties p ON c.id = p.city_id ORDER BY c.name");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * İlçe listesini getir
     * @param string $city
     * @return array
     */
    public function getDistricts($city = null) {
        try {
            if ($city) {
                $stmt = $this->db->prepare("SELECT DISTINCT d.name FROM districts d 
                                          INNER JOIN properties p ON d.id = p.district_id 
                                          INNER JOIN cities c ON d.city_id = c.id 
                                          WHERE c.name = :city ORDER BY d.name");
                $stmt->execute(['city' => $city]);
            } else {
                $stmt = $this->db->query("SELECT DISTINCT d.name FROM districts d 
                                        INNER JOIN properties p ON d.id = p.district_id 
                                        ORDER BY d.name");
            }
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Slug oluştur
     * @param string $title
     * @param int $id
     * @return string
     */
    public function createSlug($title, $id = null) {
        $helper = Helper::getInstance();
        $baseSlug = $helper->createSlug($title);
        $slug = $baseSlug;
        $counter = 1;
        
        while (true) {
            $sql = "SELECT id FROM properties WHERE slug = :slug";
            $params = ['slug' => $slug];
            
            if ($id) {
                $sql .= " AND id != :id";
                $params['id'] = $id;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            if (!$stmt->fetch()) {
                break;
            }
            
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Yeni ilan oluştur
     * @param array $data
     * @return bool
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO properties (
                title, description, property_type, transaction_type, price,
                room_count, living_room_count, bathroom_count, floor, building_age,
                heating_type, city_id, district_id, address, area,
                contact_name, contact_phone, contact_email, features, images,
                main_image, featured, urgent, status, slug, created_at
            ) VALUES (
                :title, :description, :property_type, :transaction_type, :price,
                :room_count, :living_room_count, :bathroom_count, :floor, :building_age,
                :heating_type, :city_id, :district_id, :address, :area,
                :contact_name, :contact_phone, :contact_email, :features, :images,
                :main_image, :featured, :urgent, :status, :slug, NOW()
            )";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'title' => $data['title'],
                'description' => $data['description'],
                'property_type' => $data['property_type'],
                'transaction_type' => $data['transaction_type'],
                'price' => $data['price'],
                'room_count' => $data['room_count'],
                'living_room_count' => $data['living_room_count'],
                'bathroom_count' => $data['bathroom_count'],
                'floor' => $data['floor'],
                'building_age' => $data['building_age'],
                'heating_type' => $data['heating_type'],
                'city_id' => $data['city_id'],
                'district_id' => $data['district_id'],
                'address' => $data['address'],
                'area' => $data['area'],
                'contact_name' => $data['contact_name'],
                'contact_phone' => $data['contact_phone'],
                'contact_email' => $data['contact_email'],
                'features' => $data['features'],
                'images' => $data['images'] ?? null,
                'main_image' => $data['main_image'] ?? null,
                'featured' => $data['featured'],
                'urgent' => $data['urgent'],
                'status' => $data['status'],
                'slug' => $data['slug']
            ]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * İlan güncelle
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        try {
            $sql = "UPDATE properties SET 
                title = :title,
                description = :description,
                property_type = :property_type,
                transaction_type = :transaction_type,
                price = :price,
                room_count = :room_count,
                living_room_count = :living_room_count,
                bathroom_count = :bathroom_count,
                floor = :floor,
                building_age = :building_age,
                heating_type = :heating_type,
                city_id = :city_id,
                district_id = :district_id,
                address = :address,
                area = :area,
                contact_name = :contact_name,
                contact_phone = :contact_phone,
                contact_email = :contact_email,
                features = :features,
                images = :images,
                main_image = :main_image,
                featured = :featured,
                urgent = :urgent,
                status = :status,
                updated_at = NOW()
                WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'title' => $data['title'],
                'description' => $data['description'],
                'property_type' => $data['property_type'],
                'transaction_type' => $data['transaction_type'],
                'price' => $data['price'],
                'room_count' => $data['room_count'],
                'living_room_count' => $data['living_room_count'],
                'bathroom_count' => $data['bathroom_count'],
                'floor' => $data['floor'],
                'building_age' => $data['building_age'],
                'heating_type' => $data['heating_type'],
                'city_id' => $data['city_id'],
                'district_id' => $data['district_id'],
                'address' => $data['address'],
                'area' => $data['area'],
                'contact_name' => $data['contact_name'],
                'contact_phone' => $data['contact_phone'],
                'contact_email' => $data['contact_email'],
                'features' => $data['features'],
                'images' => $data['images'] ?? null,
                'main_image' => $data['main_image'] ?? null,
                'featured' => $data['featured'],
                'urgent' => $data['urgent'],
                'status' => $data['status']
            ]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * İlan sil
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM properties WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Beldeler listesini getir
     * @param int $districtId
     * @return array
     */
    public function getNeighborhoods($districtId = null) {
        try {
            if ($districtId) {
                $stmt = $this->db->prepare("
                    SELECT n.*, d.name as district_name, c.name as city_name 
                    FROM neighborhoods n 
                    JOIN districts d ON n.district_id = d.id 
                    JOIN cities c ON d.city_id = c.id 
                    WHERE n.district_id = ? 
                    ORDER BY n.name ASC
                ");
                $stmt->execute([$districtId]);
            } else {
                $stmt = $this->db->query("
                    SELECT n.*, d.name as district_name, c.name as city_name 
                    FROM neighborhoods n 
                    JOIN districts d ON n.district_id = d.id 
                    JOIN cities c ON d.city_id = c.id 
                    ORDER BY c.name ASC, d.name ASC, n.name ASC
                ");
            }
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Bölge listesini getir
     * @return array
     */
    public function getRegions() {
        try {
            $stmt = $this->db->query("
                SELECT DISTINCT region 
                FROM cities 
                WHERE region IS NOT NULL 
                ORDER BY region ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Plaka koduna göre şehir getir
     * @param string $plateCode
     * @return array|null
     */
    public function getCityByPlateCode($plateCode) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM cities 
                WHERE plate_code = ? 
                LIMIT 1
            ");
            $stmt->execute([$plateCode]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Bölgeye göre şehirleri getir
     * @param string $region
     * @return array
     */
    public function getCitiesByRegion($region) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM cities 
                WHERE region = ? 
                ORDER BY name ASC
            ");
            $stmt->execute([$region]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Slug ile ilan getir
     * @param string $slug
     * @return array|null
     */
    public function getBySlug($slug) {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, c.name as city_name, d.name as district_name 
                FROM properties p 
                LEFT JOIN cities c ON p.city_id = c.id 
                LEFT JOIN districts d ON p.district_id = d.id 
                WHERE p.slug = ? AND p.status = 'active'
            ");
            $stmt->execute([$slug]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Görüntülenme sayısını artır
     * @param int $id
     * @return bool
     */
    public function incrementViews($id) {
        try {
            $stmt = $this->db->prepare("UPDATE properties SET views = views + 1 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Benzer ilanları getir
     * @param int $excludeId
     * @param string $propertyType
     * @param int $cityId
     * @param int $limit
     * @return array
     */
    public function getSimilar($excludeId, $propertyType, $cityId, $limit = 4) {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, c.name as city_name, d.name as district_name 
                FROM properties p 
                LEFT JOIN cities c ON p.city_id = c.id 
                LEFT JOIN districts d ON p.district_id = d.id 
                WHERE p.id != ? 
                AND p.status = 'active' 
                AND (p.property_type = ? OR p.city_id = ?)
                ORDER BY p.featured DESC, p.created_at DESC 
                LIMIT ?
            ");
            $stmt->execute([$excludeId, $propertyType, $cityId, $limit]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Gelişmiş arama
     * @param array $filters
     * @return array
     */
    public function search($filters = []) {
        try {
            $sql = "SELECT p.*, c.name as city_name, d.name as district_name 
                    FROM properties p 
                    LEFT JOIN cities c ON p.city_id = c.id 
                    LEFT JOIN districts d ON p.district_id = d.id 
                    WHERE 1=1";
            $params = [];
            
            // Anahtar kelime araması
            if (!empty($filters['keyword'])) {
                $sql .= " AND (p.title LIKE :keyword OR p.description LIKE :keyword OR p.address LIKE :keyword)";
                $params['keyword'] = '%' . $filters['keyword'] . '%';
            }
            
            // İlan tipi
            if (!empty($filters['transaction_type'])) {
                $sql .= " AND p.transaction_type = :transaction_type";
                $params['transaction_type'] = $filters['transaction_type'];
            }
            
            // Emlak tipi
            if (!empty($filters['property_type'])) {
                $sql .= " AND p.property_type = :property_type";
                $params['property_type'] = $filters['property_type'];
            }
            
            // Şehir
            if (!empty($filters['city'])) {
                $sql .= " AND c.name = :city";
                $params['city'] = $filters['city'];
            }
            
            // İlçe
            if (!empty($filters['district'])) {
                $sql .= " AND d.name = :district";
                $params['district'] = $filters['district'];
            }
            
            // Fiyat aralığı
            if (!empty($filters['min_price'])) {
                $sql .= " AND p.price >= :min_price";
                $params['min_price'] = $filters['min_price'];
            }
            
            if (!empty($filters['max_price'])) {
                $sql .= " AND p.price <= :max_price";
                $params['max_price'] = $filters['max_price'];
            }
            
            // Alan aralığı
            if (!empty($filters['min_area'])) {
                $sql .= " AND p.area >= :min_area";
                $params['min_area'] = $filters['min_area'];
            }
            
            if (!empty($filters['max_area'])) {
                $sql .= " AND p.area <= :max_area";
                $params['max_area'] = $filters['max_area'];
            }
            
            // Oda sayısı
            if (!empty($filters['rooms'])) {
                $sql .= " AND p.room_count = :rooms";
                $params['rooms'] = $filters['rooms'];
            }
            
            // Banyo sayısı
            if (!empty($filters['bathrooms'])) {
                $sql .= " AND p.bathroom_count = :bathrooms";
                $params['bathrooms'] = $filters['bathrooms'];
            }
            
            // Kat
            if (!empty($filters['floor'])) {
                $sql .= " AND p.floor = :floor";
                $params['floor'] = $filters['floor'];
            }
            
            // Isıtma türü
            if (!empty($filters['heating_type'])) {
                $sql .= " AND p.heating_type = :heating_type";
                $params['heating_type'] = $filters['heating_type'];
            }
            
            // Bina yaşı
            if (!empty($filters['building_age'])) {
                switch ($filters['building_age']) {
                    case '0-5':
                        $sql .= " AND p.building_age <= 5";
                        break;
                    case '5-10':
                        $sql .= " AND p.building_age > 5 AND p.building_age <= 10";
                        break;
                    case '10-20':
                        $sql .= " AND p.building_age > 10 AND p.building_age <= 20";
                        break;
                    case '20+':
                        $sql .= " AND p.building_age > 20";
                        break;
                }
            }
            
            // Durum
            if (!empty($filters['status'])) {
                $sql .= " AND p.status = :status";
                $params['status'] = $filters['status'];
            }
            
            // Sıralama
            $orderBy = $filters['order_by'] ?? 'created_at';
            $orderDir = $filters['order_dir'] ?? 'DESC';
            
            if ($orderBy === 'price_desc') {
                $sql .= " ORDER BY p.price DESC";
            } elseif ($orderBy === 'area_desc') {
                $sql .= " ORDER BY p.area DESC";
            } else {
                $sql .= " ORDER BY p.{$orderBy} {$orderDir}";
            }
            
            // Sayfalama
            if (!empty($filters['limit'])) {
                $sql .= " LIMIT :limit";
                $params['limit'] = (int)$filters['limit'];
                
                if (!empty($filters['offset'])) {
                    $sql .= " OFFSET :offset";
                    $params['offset'] = (int)$filters['offset'];
                }
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Arama sonuç sayısını getir
     * @param array $filters
     * @return int
     */
    public function getSearchCount($filters = []) {
        try {
            $sql = "SELECT COUNT(*) FROM properties p 
                    LEFT JOIN cities c ON p.city_id = c.id 
                    LEFT JOIN districts d ON p.district_id = d.id 
                    WHERE 1=1";
            $params = [];
            
            // Anahtar kelime araması
            if (!empty($filters['keyword'])) {
                $sql .= " AND (p.title LIKE :keyword OR p.description LIKE :keyword OR p.address LIKE :keyword)";
                $params['keyword'] = '%' . $filters['keyword'] . '%';
            }
            
            // İlan tipi
            if (!empty($filters['transaction_type'])) {
                $sql .= " AND p.transaction_type = :transaction_type";
                $params['transaction_type'] = $filters['transaction_type'];
            }
            
            // Emlak tipi
            if (!empty($filters['property_type'])) {
                $sql .= " AND p.property_type = :property_type";
                $params['property_type'] = $filters['property_type'];
            }
            
            // Şehir
            if (!empty($filters['city'])) {
                $sql .= " AND c.name = :city";
                $params['city'] = $filters['city'];
            }
            
            // İlçe
            if (!empty($filters['district'])) {
                $sql .= " AND d.name = :district";
                $params['district'] = $filters['district'];
            }
            
            // Fiyat aralığı
            if (!empty($filters['min_price'])) {
                $sql .= " AND p.price >= :min_price";
                $params['min_price'] = $filters['min_price'];
            }
            
            if (!empty($filters['max_price'])) {
                $sql .= " AND p.price <= :max_price";
                $params['max_price'] = $filters['max_price'];
            }
            
            // Alan aralığı
            if (!empty($filters['min_area'])) {
                $sql .= " AND p.area >= :min_area";
                $params['min_area'] = $filters['min_area'];
            }
            
            if (!empty($filters['max_area'])) {
                $sql .= " AND p.area <= :max_area";
                $params['max_area'] = $filters['max_area'];
            }
            
            // Oda sayısı
            if (!empty($filters['rooms'])) {
                $sql .= " AND p.room_count = :rooms";
                $params['rooms'] = $filters['rooms'];
            }
            
            // Banyo sayısı
            if (!empty($filters['bathrooms'])) {
                $sql .= " AND p.bathroom_count = :bathrooms";
                $params['bathrooms'] = $filters['bathrooms'];
            }
            
            // Kat
            if (!empty($filters['floor'])) {
                $sql .= " AND p.floor = :floor";
                $params['floor'] = $filters['floor'];
            }
            
            // Isıtma türü
            if (!empty($filters['heating_type'])) {
                $sql .= " AND p.heating_type = :heating_type";
                $params['heating_type'] = $filters['heating_type'];
            }
            
            // Bina yaşı
            if (!empty($filters['building_age'])) {
                switch ($filters['building_age']) {
                    case '0-5':
                        $sql .= " AND p.building_age <= 5";
                        break;
                    case '5-10':
                        $sql .= " AND p.building_age > 5 AND p.building_age <= 10";
                        break;
                    case '10-20':
                        $sql .= " AND p.building_age > 10 AND p.building_age <= 20";
                        break;
                    case '20+':
                        $sql .= " AND p.building_age > 20";
                        break;
                }
            }
            
            // Durum
            if (!empty($filters['status'])) {
                $sql .= " AND p.status = :status";
                $params['status'] = $filters['status'];
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
}
?>
