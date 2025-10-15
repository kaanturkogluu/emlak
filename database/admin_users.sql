-- Admin kullanıcıları tablosu
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'moderator', 'editor') DEFAULT 'admin',
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Varsayılan admin kullanıcısı ekle
INSERT INTO admin_users (username, password, email, full_name, role, status) 
VALUES (
    'admin', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    'admin@er.com', 
    'Sistem Yöneticisi', 
    'admin', 
    'active'
) ON DUPLICATE KEY UPDATE username = username;

-- Alternatif admin kullanıcısı
INSERT INTO admin_users (username, password, email, full_name, role, status) 
VALUES (
    'moderator', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    'moderator@er.com', 
    'Moderatör', 
    'moderator', 
    'active'
) ON DUPLICATE KEY UPDATE username = username;
