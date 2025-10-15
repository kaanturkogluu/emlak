<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Helper.php';

// Admin giriş kontrolü
$adminUser = new AdminUser();
if (!$adminUser->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$helper = Helper::getInstance();
$db = Database::getInstance()->getConnection();

// İşlemler
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_city':
            $name = trim($_POST['name'] ?? '');
            $plate_code = trim($_POST['plate_code'] ?? '');
            $region = trim($_POST['region'] ?? '');
            
            if ($name && $plate_code) {
                try {
                    $stmt = $db->prepare("INSERT INTO cities (name, plate_code, region, status) VALUES (?, ?, ?, 'active')");
                    $stmt->execute([$name, $plate_code, $region]);
                    $message = 'Şehir başarıyla eklendi!';
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = 'Şehir eklenirken hata oluştu: ' . $e->getMessage();
                    $messageType = 'error';
                }
            } else {
                $message = 'Şehir adı ve plaka kodu zorunludur!';
                $messageType = 'error';
            }
            break;
            
        case 'add_district':
            $city_id = (int)($_POST['city_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            
            if ($city_id && $name) {
                try {
                    $stmt = $db->prepare("INSERT INTO districts (city_id, name, status) VALUES (?, ?, 'active')");
                    $stmt->execute([$city_id, $name]);
                    $message = 'İlçe başarıyla eklendi!';
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = 'İlçe eklenirken hata oluştu: ' . $e->getMessage();
                    $messageType = 'error';
                }
            } else {
                $message = 'Şehir seçimi ve ilçe adı zorunludur!';
                $messageType = 'error';
            }
            break;
            
        case 'add_neighborhood':
            $district_id = (int)($_POST['district_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            
            if ($district_id && $name) {
                try {
                    $stmt = $db->prepare("INSERT INTO neighborhoods (district_id, name, status) VALUES (?, ?, 'active')");
                    $stmt->execute([$district_id, $name]);
                    $message = 'Mahalle başarıyla eklendi!';
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = 'Mahalle eklenirken hata oluştu: ' . $e->getMessage();
                    $messageType = 'error';
                }
            } else {
                $message = 'İlçe seçimi ve mahalle adı zorunludur!';
                $messageType = 'error';
            }
            break;
            
        case 'delete_city':
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                try {
                    $stmt = $db->prepare("UPDATE cities SET status = 'deleted' WHERE id = ?");
                    $stmt->execute([$id]);
                    $message = 'Şehir başarıyla silindi!';
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = 'Şehir silinirken hata oluştu: ' . $e->getMessage();
                    $messageType = 'error';
                }
            }
            break;
            
        case 'delete_district':
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                try {
                    $stmt = $db->prepare("UPDATE districts SET status = 'deleted' WHERE id = ?");
                    $stmt->execute([$id]);
                    $message = 'İlçe başarıyla silindi!';
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = 'İlçe silinirken hata oluştu: ' . $e->getMessage();
                    $messageType = 'error';
                }
            }
            break;
            
        case 'delete_neighborhood':
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                try {
                    $stmt = $db->prepare("UPDATE neighborhoods SET status = 'deleted' WHERE id = ?");
                    $stmt->execute([$id]);
                    $message = 'Mahalle başarıyla silindi!';
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = 'Mahalle silinirken hata oluştu: ' . $e->getMessage();
                    $messageType = 'error';
                }
            }
            break;
    }
}

// Şehirleri getir
$stmt = $db->prepare("SELECT * FROM cities WHERE status = 'active' ORDER BY name");
$stmt->execute();
$cities = $stmt->fetchAll();

// İlçeleri getir
$stmt = $db->prepare("
    SELECT d.*, c.name as city_name 
    FROM districts d 
    LEFT JOIN cities c ON d.city_id = c.id 
    WHERE d.status = 'active' 
    ORDER BY c.name, d.name
");
$stmt->execute();
$districts = $stmt->fetchAll();

// Mahalleleri getir
$stmt = $db->prepare("
    SELECT n.*, d.name as district_name, c.name as city_name 
    FROM neighborhoods n 
    LEFT JOIN districts d ON n.district_id = d.id 
    LEFT JOIN cities c ON d.city_id = c.id 
    WHERE n.status = 'active' 
    ORDER BY c.name, d.name, n.name
");
$stmt->execute();
$neighborhoods = $stmt->fetchAll();
?>

<!-- Header -->
<?php include __DIR__ . '/layout/header.php'; ?>

<style>
    .cities-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 30px;
        margin-bottom: 20px;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #2c3e50;
    }
    
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }
    
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background: #3498db;
        color: white;
    }
    
    .btn-primary:hover {
        background: #2980b9;
    }
    
    .btn-danger {
        background: #e74c3c;
        color: white;
    }
    
    .btn-danger:hover {
        background: #c0392b;
    }
    
    .btn-success {
        background: #27ae60;
        color: white;
    }
    
    .btn-success:hover {
        background: #229954;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .table th,
    .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    
    .table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .table tr:hover {
        background: #f8f9fa;
    }
    
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        font-weight: 500;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .tabs {
        display: flex;
        margin-bottom: 20px;
        border-bottom: 1px solid #ddd;
    }
    
    .tab {
        padding: 10px 20px;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        font-weight: 500;
        color: #666;
    }
    
    .tab.active {
        color: #3498db;
        border-bottom-color: #3498db;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
</style>

<div class="main-content">
    <div class="cities-container">
        <h1 class="section-title">Şehir, İlçe ve Mahalle Yönetimi</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo $helper->e($message); ?>
            </div>
        <?php endif; ?>
        
        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" onclick="showTab('cities')">Şehirler</div>
            <div class="tab" onclick="showTab('districts')">İlçeler</div>
            <div class="tab" onclick="showTab('neighborhoods')">Mahalleler</div>
        </div>
        
        <!-- Şehirler Tab -->
        <div id="cities" class="tab-content active">
            <h2>Şehir Ekle</h2>
            <form method="POST" class="form-grid">
                <input type="hidden" name="action" value="add_city">
                <div class="form-group">
                    <label for="name">Şehir Adı</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="plate_code">Plaka Kodu</label>
                    <input type="text" id="plate_code" name="plate_code" required>
                </div>
                <div class="form-group">
                    <label for="region">Bölge</label>
                    <input type="text" id="region" name="region">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Şehir Ekle</button>
                </div>
            </form>
            
            <h2>Mevcut Şehirler</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Şehir Adı</th>
                        <th>Plaka</th>
                        <th>Bölge</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cities as $city): ?>
                        <tr>
                            <td><?php echo $city['id']; ?></td>
                            <td><?php echo $helper->e($city['name']); ?></td>
                            <td><?php echo $helper->e($city['plate_code']); ?></td>
                            <td><?php echo $helper->e($city['region']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Bu şehri silmek istediğinizden emin misiniz?')">
                                    <input type="hidden" name="action" value="delete_city">
                                    <input type="hidden" name="id" value="<?php echo $city['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- İlçeler Tab -->
        <div id="districts" class="tab-content">
            <h2>İlçe Ekle</h2>
            <form method="POST" class="form-grid">
                <input type="hidden" name="action" value="add_district">
                <div class="form-group">
                    <label for="city_id">Şehir</label>
                    <select id="city_id" name="city_id" required>
                        <option value="">Şehir Seçin</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?php echo $city['id']; ?>"><?php echo $helper->e($city['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="district_name">İlçe Adı</label>
                    <input type="text" id="district_name" name="name" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">İlçe Ekle</button>
                </div>
            </form>
            
            <h2>Mevcut İlçeler</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>İlçe Adı</th>
                        <th>Şehir</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($districts as $district): ?>
                        <tr>
                            <td><?php echo $district['id']; ?></td>
                            <td><?php echo $helper->e($district['name']); ?></td>
                            <td><?php echo $helper->e($district['city_name']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Bu ilçeyi silmek istediğinizden emin misiniz?')">
                                    <input type="hidden" name="action" value="delete_district">
                                    <input type="hidden" name="id" value="<?php echo $district['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Mahalleler Tab -->
        <div id="neighborhoods" class="tab-content">
            <h2>Mahalle Ekle</h2>
            <form method="POST" class="form-grid">
                <input type="hidden" name="action" value="add_neighborhood">
                <div class="form-group">
                    <label for="district_id">İlçe</label>
                    <select id="district_id" name="district_id" required>
                        <option value="">İlçe Seçin</option>
                        <?php foreach ($districts as $district): ?>
                            <option value="<?php echo $district['id']; ?>"><?php echo $helper->e($district['name'] . ' - ' . $district['city_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="neighborhood_name">Mahalle Adı</label>
                    <input type="text" id="neighborhood_name" name="name" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Mahalle Ekle</button>
                </div>
            </form>
            
            <h2>Mevcut Mahalleler</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mahalle Adı</th>
                        <th>İlçe</th>
                        <th>Şehir</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($neighborhoods as $neighborhood): ?>
                        <tr>
                            <td><?php echo $neighborhood['id']; ?></td>
                            <td><?php echo $helper->e($neighborhood['name']); ?></td>
                            <td><?php echo $helper->e($neighborhood['district_name']); ?></td>
                            <td><?php echo $helper->e($neighborhood['city_name']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Bu mahalleyi silmek istediğinizden emin misiniz?')">
                                    <input type="hidden" name="action" value="delete_neighborhood">
                                    <input type="hidden" name="id" value="<?php echo $neighborhood['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function showTab(tabName) {
        // Tüm tab'ları gizle
        const tabs = document.querySelectorAll('.tab-content');
        tabs.forEach(tab => tab.classList.remove('active'));
        
        // Tüm tab butonlarını pasif yap
        const tabButtons = document.querySelectorAll('.tab');
        tabButtons.forEach(button => button.classList.remove('active'));
        
        // Seçilen tab'ı göster
        document.getElementById(tabName).classList.add('active');
        
        // Seçilen tab butonunu aktif yap
        event.target.classList.add('active');
    }
</script>

<!-- Footer -->
<?php include __DIR__ . '/layout/footer.php'; ?>
