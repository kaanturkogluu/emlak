<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Helper.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Slider.php';

$helper = Helper::getInstance();
$adminUser = new AdminUser();
$slider = new Slider();

// Admin giriş kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Kullanıcı bilgilerini al
$currentUser = $adminUser->getById($_SESSION['admin_user_id']);
if (!$currentUser) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$sliderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sliderData = null;
$success = '';
$error = '';
$pageTitle = $sliderId > 0 ? 'Slider Düzenle' : 'Yeni Slider Ekle';

// Slider verilerini getir
if ($sliderId > 0) {
    $sliderData = $slider->getById($sliderId);
    if (!$sliderData) {
        $error = 'Slider bulunamadı!';
    }
}

// Form işleme
if ($_POST) {
    $data = [
        'title' => $_POST['title'] ?? '',
        'subtitle' => $_POST['subtitle'] ?? '',
        'button_text' => $_POST['button_text'] ?? '',
        'button_url' => $_POST['button_url'] ?? '',
        'image' => '',
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
        'status' => $_POST['status'] ?? 'active'
    ];
    
    // Resim yükleme işlemi (zorunlu)
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/sliders/';
        
        // Upload klasörünü oluştur
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileExtension = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $fileName = 'slider_' . time() . '_' . uniqid() . '.' . $fileExtension;
            $filePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $filePath)) {
                $data['image'] = $helper->getBaseUrl() . '/uploads/sliders/' . $fileName;
            } else {
                $error = 'Resim yüklenirken hata oluştu!';
            }
        } else {
            $error = 'Geçersiz dosya formatı! Sadece JPG, PNG, GIF ve WebP dosyaları kabul edilir.';
        }
    } else {
        // Yeni slider ekleme durumunda resim zorunlu
        if ($sliderId == 0) {
            $error = 'Lütfen bir resim dosyası seçin!';
        } else {
            // Düzenleme modunda mevcut resmi koru
            $data['image'] = $sliderData['image'];
        }
    }
    
    if (empty($error)) {
        if ($sliderId > 0) {
            // Güncelleme
            if ($slider->update($sliderId, $data)) {
                $success = 'Slider başarıyla güncellendi.';
                $sliderData = $slider->getById($sliderId); // Güncel veriyi al
            } else {
                $error = 'Slider güncellenirken hata oluştu.';
            }
        } else {
            // Yeni oluşturma
            if ($slider->create($data)) {
                $success = 'Slider başarıyla oluşturuldu.';
                header('Location: sliders.php');
                exit;
            } else {
                $error = 'Slider oluşturulurken hata oluştu.';
            }
        }
    }
}

// Layout header'ı dahil et
require_once __DIR__ . '/layout/header.php';
?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #2c3e50;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-header h2 {
            font-size: 1.2rem;
            font-weight: 700;
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .sidebar-header h2 {
            opacity: 0;
        }
        
        .toggle-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        
        .toggle-btn:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover,
        .menu-item.active {
            background: rgba(255,255,255,0.1);
            border-left-color: white;
        }
        
        .menu-item i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }
        
        .sidebar.collapsed .menu-item span {
            opacity: 0;
        }
        
        .sidebar.collapsed .menu-item {
            text-align: center;
            padding: 15px 10px;
        }
        
        .sidebar.collapsed .menu-item i {
            margin-right: 0;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }
        
        /* Top Bar */
        .top-bar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .top-bar h1 {
            color: #2c3e50;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s ease;
        }
        
        .logout-btn:hover {
            background: #c0392b;
        }
        
        /* Content Area */
        .content {
            padding: 30px;
        }
        
        /* Alerts */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
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
        
        /* Form Container */
        .form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8f9fa;
        }
        
        .form-header h2 {
            color: #2c3e50;
            font-size: 1.5rem;
        }
        
        .back-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .back-btn:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        /* Form */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .form-group label.required::after {
            content: ' *';
            color: #dc3545;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-control[type="number"] {
            width: 120px;
        }
        
        select.form-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
            appearance: none;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        
        /* File Upload */
        .file-upload-container {
            border: 2px dashed #e9ecef;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: border-color 0.3s ease;
            cursor: pointer;
        }
        
        .file-upload-container:hover {
            border-color: #667eea;
        }
        
        .file-upload-container.dragover {
            border-color: #667eea;
            background: #f8f9ff;
        }
        
        .file-upload-icon {
            font-size: 2rem;
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .file-upload-text {
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .file-upload-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .file-upload-btn:hover {
            background: #5a6fd8;
        }
        
        .file-info {
            margin-top: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        /* Image Preview */
        .image-preview {
            margin-top: 15px;
            text-align: center;
        }
        
        .image-preview img {
            max-width: 300px;
            max-height: 200px;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f8f9fa;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .top-bar {
                padding: 15px 20px;
            }
            
            .content {
                padding: 20px;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .mobile-menu-btn {
                display: block;
                background: none;
                border: none;
                font-size: 1.2rem;
                color: #2c3e50;
                cursor: pointer;
            }
        }
        
        .mobile-menu-btn {
            display: none;
        }
        
        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
            <button class="toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <nav class="sidebar-menu">
            <a href="index.php" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="properties.php" class="menu-item">
                <i class="fas fa-home"></i>
                <span>İlanlar</span>
            </a>
            <a href="sliders.php" class="menu-item active">
                <i class="fas fa-images"></i>
                <span>Slider</span>
            </a>
            <a href="users.php" class="menu-item">
                <i class="fas fa-users"></i>
                <span>Kullanıcılar</span>
            </a>
            <a href="cities.php" class="menu-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>Şehirler</span>
            </a>
            <a href="settings.php" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Ayarlar</span>
            </a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Bar -->
        <div class="top-bar">
            <div>
                <button class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1><?php echo $sliderId > 0 ? 'Slider Düzenle' : 'Yeni Slider Ekle'; ?></h1>
            </div>
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?>
                </div>
                <span><?php echo $helper->e($currentUser['full_name']); ?></span>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Çıkış
                </a>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $helper->e($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $helper->e($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="form-container">
                <div class="form-header">
                    <h2><?php echo $sliderId > 0 ? 'Slider Düzenle' : 'Yeni Slider Ekle'; ?></h2>
                    <a href="sliders.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Geri Dön
                    </a>
                </div>
                
                <form method="POST" enctype="multipart/form-data" id="sliderForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title" class="required">Başlık</label>
                            <input type="text" id="title" name="title" class="form-control" 
                                   value="<?php echo $sliderData ? $helper->e($sliderData['title']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="sort_order">Sıra</label>
                            <input type="number" id="sort_order" name="sort_order" class="form-control" 
                                   value="<?php echo $sliderData ? $sliderData['sort_order'] : '0'; ?>" min="0">
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="subtitle">Alt Başlık</label>
                        <textarea id="subtitle" name="subtitle" class="form-control" rows="3"><?php echo $sliderData ? $helper->e($sliderData['subtitle']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="button_text">Buton Metni</label>
                            <input type="text" id="button_text" name="button_text" class="form-control" 
                                   value="<?php echo $sliderData ? $helper->e($sliderData['button_text']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="button_url">Buton Sayfası</label>
                            <select id="button_url" name="button_url" class="form-control">
                                <option value="">Sayfa Seçin</option>
                                <option value="<?php echo $helper->url(''); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('')) ? 'selected' : ''; ?>>Ana Sayfa</option>
                                <option value="<?php echo $helper->url('hakkimizda'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('hakkimizda')) ? 'selected' : ''; ?>>Hakkımızda</option>
                                <option value="<?php echo $helper->url('iletisim'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('iletisim')) ? 'selected' : ''; ?>>İletişim</option>
                                <option value="<?php echo $helper->url('satilik'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('satilik')) ? 'selected' : ''; ?>>Satılık İlanlar</option>
                                <option value="<?php echo $helper->url('kiralik'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('kiralik')) ? 'selected' : ''; ?>>Kiralık İlanlar</option>
                                <option value="<?php echo $helper->url('satilik-daire'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('satilik-daire')) ? 'selected' : ''; ?>>Satılık Daire</option>
                                <option value="<?php echo $helper->url('satilik-ev'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('satilik-ev')) ? 'selected' : ''; ?>>Satılık Ev</option>
                                <option value="<?php echo $helper->url('satilik-villa'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('satilik-villa')) ? 'selected' : ''; ?>>Satılık Villa</option>
                                <option value="<?php echo $helper->url('satilik-isyeri'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('satilik-isyeri')) ? 'selected' : ''; ?>>Satılık İşyeri</option>
                                <option value="<?php echo $helper->url('satilik-arsa'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('satilik-arsa')) ? 'selected' : ''; ?>>Satılık Arsa</option>
                                <option value="<?php echo $helper->url('kiralik-daire'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('kiralik-daire')) ? 'selected' : ''; ?>>Kiralık Daire</option>
                                <option value="<?php echo $helper->url('kiralik-ev'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('kiralik-ev')) ? 'selected' : ''; ?>>Kiralık Ev</option>
                                <option value="<?php echo $helper->url('kiralik-villa'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('kiralik-villa')) ? 'selected' : ''; ?>>Kiralık Villa</option>
                                <option value="<?php echo $helper->url('kiralik-isyeri'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('kiralik-isyeri')) ? 'selected' : ''; ?>>Kiralık İşyeri</option>
                                <option value="<?php echo $helper->url('kiralik-arsa'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('kiralik-arsa')) ? 'selected' : ''; ?>>Kiralık Arsa</option>
                                <option value="<?php echo $helper->url('arama'); ?>" <?php echo ($sliderData && $sliderData['button_url'] === $helper->url('arama')) ? 'selected' : ''; ?>>Arama</option>
                                <option value="custom" <?php echo ($sliderData && !in_array($sliderData['button_url'], [
                                    $helper->url(''), $helper->url('hakkimizda'), $helper->url('iletisim'),
                                    $helper->url('satilik'), $helper->url('kiralik'), $helper->url('satilik-daire'),
                                    $helper->url('satilik-ev'), $helper->url('satilik-villa'), $helper->url('satilik-isyeri'),
                                    $helper->url('satilik-arsa'), $helper->url('kiralik-daire'), $helper->url('kiralik-ev'),
                                    $helper->url('kiralik-villa'), $helper->url('kiralik-isyeri'), $helper->url('kiralik-arsa'),
                                    $helper->url('arama')
                                ]) && !empty($sliderData['button_url'])) ? 'selected' : ''; ?>>Özel URL Gir</option>
                            </select>
                            
                            <!-- Özel URL Input (gizli) -->
                            <input type="url" id="custom_url" name="custom_url" class="form-control" 
                                   style="display: none; margin-top: 10px;" 
                                   value="<?php echo ($sliderData && !in_array($sliderData['button_url'], [
                                       $helper->url(''), $helper->url('hakkimizda'), $helper->url('iletisim'),
                                       $helper->url('satilik'), $helper->url('kiralik'), $helper->url('satilik-daire'),
                                       $helper->url('satilik-ev'), $helper->url('satilik-villa'), $helper->url('satilik-isyeri'),
                                       $helper->url('satilik-arsa'), $helper->url('kiralik-daire'), $helper->url('kiralik-ev'),
                                       $helper->url('kiralik-villa'), $helper->url('kiralik-isyeri'), $helper->url('kiralik-arsa'),
                                       $helper->url('arama')
                                   ]) && !empty($sliderData['button_url'])) ? $helper->e($sliderData['button_url']) : ''; ?>"
                                   placeholder="https://example.com">
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="image" <?php echo $sliderId == 0 ? 'class="required"' : ''; ?>>Resim</label>
                        
                        <!-- Resim Yükleme Alanı -->
                        <div class="file-upload-container" onclick="document.getElementById('image_file').click()">
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="file-upload-text">
                                <?php if ($sliderId > 0): ?>
                                    Yeni resim dosyasını seçin veya buraya sürükleyin (isteğe bağlı)
                                <?php else: ?>
                                    Resim dosyasını seçin veya buraya sürükleyin (zorunlu)
                                <?php endif; ?>
                            </div>
                            <button type="button" class="file-upload-btn">
                                <i class="fas fa-folder-open"></i> Dosya Seç
                            </button>
                            <div class="file-info">
                                Desteklenen formatlar: JPG, PNG, GIF, WebP (Max: 5MB)
                            </div>
                        </div>
                        
                        <input type="file" id="image_file" name="image_file" accept="image/*" style="display: none;" onchange="handleFileSelect(this)">
                        
                        
                        <!-- Mevcut Resim (Düzenleme modunda) -->
                        <?php if ($sliderData && $sliderData['image']): ?>
                        <div class="current-image" style="margin-top: 15px;">
                            <label>Mevcut Resim:</label>
                            <div class="image-preview">
                                <img src="<?php echo $helper->e($sliderData['image']); ?>" alt="Mevcut Resim" style="max-width: 300px; max-height: 200px; border-radius: 8px; border: 2px solid #e9ecef; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                            </div>
                            <p style="color: #6c757d; font-size: 0.9rem; margin-top: 5px;">
                                Yeni resim yüklemek için yukarıdaki alanı kullanın.
                            </p>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Yeni Resim Önizleme -->
                        <div id="imagePreview" class="image-preview" style="display: none;">
                            <img id="previewImg" src="" alt="Önizleme">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Durum</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active" <?php echo ($sliderData && $sliderData['status'] === 'active') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="inactive" <?php echo ($sliderData && $sliderData['status'] === 'inactive') ? 'selected' : ''; ?>>Pasif</option>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <a href="sliders.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $sliderId > 0 ? 'Güncelle' : 'Kaydet'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
        
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        }
        
        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            toggleMobileSidebar();
        });
        
        // Auto-hide sidebar on mobile when clicking menu item
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    toggleMobileSidebar();
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.getElementById('sidebar').classList.remove('mobile-open');
                document.getElementById('sidebarOverlay').classList.remove('active');
            }
        });
        
        // File upload handling
        function handleFileSelect(input) {
            const file = input.files[0];
            if (file) {
                // Dosya boyutu kontrolü (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Dosya boyutu 5MB\'dan büyük olamaz!');
                    input.value = '';
                    return;
                }
                
                // Dosya tipi kontrolü
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Geçersiz dosya formatı! Sadece JPG, PNG, GIF ve WebP dosyaları kabul edilir.');
                    input.value = '';
                    return;
                }
                
                // Önizleme göster
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage(e.target.result);
                };
                reader.readAsDataURL(file);
                
                // URL alanını temizle
                document.getElementById('image').value = '';
            }
        }
        
        // Resim önizleme fonksiyonu
        function previewImage(url) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            
            if (url && url.trim() !== '') {
                previewImg.src = url;
                preview.style.display = 'block';
                
                // Resim yüklenme hatası kontrolü
                previewImg.onerror = function() {
                    preview.innerHTML = '<div style="color: #dc3545; padding: 20px; background: #f8d7da; border-radius: 8px; font-size: 0.9rem;">Resim yüklenemedi</div>';
                };
                
                previewImg.onload = function() {
                    preview.innerHTML = '<img id="previewImg" src="' + url + '" alt="Önizleme" style="max-width: 300px; max-height: 200px; border-radius: 8px; border: 2px solid #e9ecef; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">';
                };
            } else {
                preview.style.display = 'none';
            }
        }
        
        // Drag and drop functionality
        const fileUploadContainer = document.querySelector('.file-upload-container');
        
        fileUploadContainer.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        
        fileUploadContainer.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });
        
        fileUploadContainer.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('image_file').files = files;
                handleFileSelect(document.getElementById('image_file'));
            }
        });
        
        // Buton URL dropdown kontrolü
        function toggleCustomUrl() {
            const buttonUrlSelect = document.getElementById('button_url');
            const customUrlInput = document.getElementById('custom_url');
            
            if (buttonUrlSelect.value === 'custom') {
                customUrlInput.style.display = 'block';
                customUrlInput.required = true;
            } else {
                customUrlInput.style.display = 'none';
                customUrlInput.required = false;
                if (buttonUrlSelect.value !== 'custom') {
                    customUrlInput.value = '';
                }
            }
        }
        
        document.getElementById('button_url').addEventListener('change', toggleCustomUrl);
        
        // Sayfa yüklendiğinde kontrol et
        document.addEventListener('DOMContentLoaded', function() {
            toggleCustomUrl();
        });
        
        // Form validation
        document.getElementById('sliderForm').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const imageFile = document.getElementById('image_file').files[0];
            const buttonUrl = document.getElementById('button_url').value;
            const customUrl = document.getElementById('custom_url').value.trim();
            const isEditMode = <?php echo $sliderId > 0 ? 'true' : 'false'; ?>;
            
            if (!title) {
                alert('Başlık alanı zorunludur!');
                document.getElementById('title').focus();
                e.preventDefault();
                return;
            }
            
            // Yeni slider ekleme durumunda resim zorunlu
            if (!isEditMode && !imageFile) {
                alert('Lütfen bir resim dosyası seçin!');
                e.preventDefault();
                return;
            }
            
            // Özel URL kontrolü
            if (buttonUrl === 'custom' && !customUrl) {
                alert('Özel URL alanı zorunludur!');
                document.getElementById('custom_url').focus();
                e.preventDefault();
                return;
            }
            
            // Özel URL'yi ana input'a kopyala
            if (buttonUrl === 'custom' && customUrl) {
                document.getElementById('button_url').value = customUrl;
            }
        });
        
    </script>
</body>
</html>
