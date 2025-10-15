<?php
// Sidebar menü öğeleri
$menuItems = [
    [
        'url' => 'index.php',
        'icon' => 'fas fa-tachometer-alt',
        'title' => 'Dashboard',
        'active' => (basename($_SERVER['PHP_SELF']) === 'index.php')
    ],
    [
        'url' => 'properties.php',
        'icon' => 'fas fa-home',
        'title' => 'İlanlar',
        'active' => in_array(basename($_SERVER['PHP_SELF']), ['properties.php', 'property-add.php', 'property-edit.php'])
    ],
    [
        'url' => 'sliders.php',
        'icon' => 'fas fa-images',
        'title' => 'Slider',
        'active' => in_array(basename($_SERVER['PHP_SELF']), ['sliders.php', 'slider-edit-page.php'])
    ],
    [
        'url' => 'users.php',
        'icon' => 'fas fa-users',
        'title' => 'Kullanıcılar',
        'active' => (basename($_SERVER['PHP_SELF']) === 'users.php')
    ],
    [
        'url' => 'cities.php',
        'icon' => 'fas fa-map-marker-alt',
        'title' => 'Şehirler',
        'active' => (basename($_SERVER['PHP_SELF']) === 'cities.php')
    ],
    [
        'url' => 'settings.php',
        'icon' => 'fas fa-cog',
        'title' => 'Ayarlar',
        'active' => (basename($_SERVER['PHP_SELF']) === 'settings.php')
    ]
];
?>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>Admin Panel</h2>
        <button class="toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <nav class="sidebar-menu">
        <?php foreach ($menuItems as $item): ?>
            <a href="<?php echo $item['url']; ?>" class="menu-item <?php echo $item['active'] ? 'active' : ''; ?>">
                <i class="<?php echo $item['icon']; ?>"></i>
                <span><?php echo $item['title']; ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
</div>
