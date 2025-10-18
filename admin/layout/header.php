<?php
// Admin giriş kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Kullanıcı bilgilerini al
$adminUser = new AdminUser();
$currentUser = $adminUser->getById($_SESSION['admin_user_id']);
if (!$currentUser) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$helper = Helper::getInstance();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Admin Panel</title>
    
    <!-- Favicon -->
    <?php $siteIcon = $helper->getSetting('site_icon', ''); ?>
    <?php if (!empty($siteIcon)): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo $helper->e($siteIcon); ?>">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $helper->e($siteIcon); ?>">
        <link rel="apple-touch-icon" href="<?php echo $helper->e($siteIcon); ?>">
        <meta name="msapplication-TileImage" content="<?php echo $helper->e($siteIcon); ?>">
    <?php endif; ?>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1e40af',
                        accent: '#06b6d4',
                        dark: '#1f2937',
                        light: '#f8fafc'
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
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
            position: relative;
        }
        
        .sidebar-header h2 {
            font-size: 1.2rem;
            font-weight: 700;
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .sidebar-header h2 {
            opacity: 0;
        }
        
        /* Daraltılmış menüde toggle butonunun görünür olması */
        .sidebar.collapsed .toggle-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar.collapsed .toggle-btn:hover {
            background: rgba(255,255,255,0.2);
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
            position: relative;
        }
        
        .menu-item:hover,
        .menu-item.active {
            background: rgba(255,255,255,0.1);
            border-left-color: white;
            transform: translateX(5px);
        }
        
        .menu-item i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .menu-item span {
            font-weight: 500;
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
        
        /* Toggle buton ikonu değişimi */
        .sidebar.collapsed .toggle-btn i {
            transform: rotate(180deg);
        }
        
        /* Main Content */
        .main-content, #mainContent {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }
        
        .main-content.expanded, #mainContent.expanded {
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
        
        /* Buttons */
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
        
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        
        .btn-warning:hover {
            background: #e67e22;
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
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        
        /* Form Elements */
        .form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            max-width: 1000px;
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
        
        /* Tables */
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th {
            background: #f8f9fa;
            padding: 20px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #e9ecef;
        }
        
        .table td {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }
        
        .table tr:hover {
            background: #f8f9fa;
        }
        
        /* Action Bar */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .action-bar h3 {
            color: #2c3e50;
            font-size: 1.5rem;
        }
        
        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #2c3e50;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.3s ease;
            margin-right: 15px;
        }
        
        .mobile-menu-btn:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
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
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 280px;
                box-shadow: 2px 0 20px rgba(0,0,0,0.3);
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .sidebar-header {
                padding: 15px 20px;
            }
            
            .sidebar-header h2 {
                font-size: 1.1rem;
            }
            
            .menu-item {
                padding: 18px 20px;
                font-size: 1rem;
            }
            
            .menu-item i {
                font-size: 1.2rem;
                width: 24px;
            }
            
            .main-content, #mainContent {
                margin-left: 0;
            }
            
            .top-bar {
                padding: 15px 20px;
                position: sticky;
                top: 0;
                z-index: 100;
            }
            
            .top-bar h1 {
                font-size: 1.2rem;
            }
            
            .content {
                padding: 15px;
            }
            
            .form-container {
                padding: 20px;
                margin: 0;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .action-bar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
                padding: 15px;
            }
            
            .action-bar h3 {
                font-size: 1.2rem;
            }
            
            .table {
                font-size: 0.85rem;
                overflow-x: auto;
                display: block;
                white-space: nowrap;
            }
            
            .table th,
            .table td {
                padding: 12px 8px;
                min-width: 120px;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .user-info {
                gap: 10px;
            }
            
            .user-info span {
                display: none;
            }
            
            .logout-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
            
            .btn {
                padding: 10px 16px;
                font-size: 0.9rem;
            }
            
            .btn-sm {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .top-bar {
                padding: 12px 15px;
            }
            
            .top-bar h1 {
                font-size: 1.1rem;
            }
            
            .content {
                padding: 10px;
            }
            
            .form-container {
                padding: 15px;
            }
            
            .action-bar {
                padding: 12px;
            }
            
            .action-bar h3 {
                font-size: 1.1rem;
            }
            
            .table th,
            .table td {
                padding: 10px 6px;
                font-size: 0.8rem;
            }
            
            .mobile-menu-btn {
                font-size: 1.3rem;
                padding: 6px;
            }
            
            .user-avatar {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }
            
            .logout-btn {
                padding: 5px 10px;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar Overlay for Mobile -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <div class="fixed left-0 top-0 h-full w-64 bg-white shadow-xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-50" id="sidebar">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-home text-white text-sm"></i>
                </div>
                <span class="text-xl font-bold text-gray-800">Admin Panel</span>
            </div>
            <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors lg:hidden" onclick="toggleMobileSidebar()">
                <i class="fas fa-times text-gray-600"></i>
            </button>
        </div>
        
        <!-- Navigation -->
        <nav class="p-4 space-y-2">
            <a href="index.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?php echo (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="properties.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?php echo (basename($_SERVER['PHP_SELF']) === 'properties.php' || basename($_SERVER['PHP_SELF']) === 'property-add.php' || basename($_SERVER['PHP_SELF']) === 'property-edit.php') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                <i class="fas fa-home w-5"></i>
                <span class="font-medium">İlanlar</span>
            </a>
            <a href="sliders.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?php echo (basename($_SERVER['PHP_SELF']) === 'sliders.php' || basename($_SERVER['PHP_SELF']) === 'slider-edit-page.php') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                <i class="fas fa-images w-5"></i>
                <span class="font-medium">Slider</span>
            </a>
            <a href="vitrin.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?php echo (basename($_SERVER['PHP_SELF']) === 'vitrin.php') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                <i class="fas fa-star w-5"></i>
                <span class="font-medium">Vitrin İlanları</span>
            </a>
            <a href="featured-properties.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?php echo (basename($_SERVER['PHP_SELF']) === 'featured-properties.php') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                <i class="fas fa-fire w-5"></i>
                <span class="font-medium">Öne Çıkan İlanlar</span>
            </a>
            <a href="page-contents.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?php echo (basename($_SERVER['PHP_SELF']) === 'page-contents.php') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                <i class="fas fa-file-alt w-5"></i>
                <span class="font-medium">Sayfa İçerikleri</span>
            </a>
            <a href="team-members.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?php echo (basename($_SERVER['PHP_SELF']) === 'team-members.php' || basename($_SERVER['PHP_SELF']) === 'team-member-edit.php') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                <i class="fas fa-users w-5"></i>
                <span class="font-medium">Ekip Üyeleri</span>
            </a>
            <a href="cities.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?php echo (basename($_SERVER['PHP_SELF']) === 'cities.php') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                <i class="fas fa-map-marker-alt w-5"></i>
                <span class="font-medium">Şehirler</span>
            </a>
            <a href="settings.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 <?php echo (basename($_SERVER['PHP_SELF']) === 'settings.php') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                <i class="fas fa-cog w-5"></i>
                <span class="font-medium">Ayarlar</span>
            </a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="lg:ml-64 transition-all duration-300 ease-in-out" id="mainContent">
        <!-- Top Bar -->
        <div class="bg-white shadow-sm border-b border-gray-200 px-4 lg:px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 lg:space-x-4">
                    <button class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors" onclick="toggleMobileSidebar()">
                        <i class="fas fa-bars text-gray-600"></i>
                    </button>
                    <h1 class="text-lg lg:text-2xl font-bold text-gray-800 truncate"><?php echo isset($pageTitle) ? $pageTitle : 'Admin Panel'; ?></h1>
                </div>
                <div class="flex items-center space-x-2 lg:space-x-4">
                    <!-- Desktop User Info -->
                    <div class="hidden lg:flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            <?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?>
                        </div>
                        <span class="text-gray-700 font-medium"><?php echo $helper->e($currentUser['full_name']); ?></span>
                    </div>
                    <!-- Mobile Logout Button -->
                    <a href="logout.php" class="flex items-center space-x-1 lg:space-x-2 px-3 lg:px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="font-medium hidden sm:inline">Çıkış</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-4 lg:p-6">
