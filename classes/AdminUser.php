<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Database.php';

class AdminUser {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Admin kullanıcısı giriş kontrolü
     * @param string $username
     * @param string $password
     * @return array|false
     */
    public function login($username, $password) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, username, password, email, full_name, role, status, last_login 
                FROM admin_users 
                WHERE username = ? AND status = 'active'
            ");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Son giriş zamanını güncelle
                $this->updateLastLogin($user['id']);
                return $user;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Son giriş zamanını güncelle
     * @param int $userId
     */
    private function updateLastLogin($userId) {
        try {
            $stmt = $this->db->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$userId]);
        } catch (Exception $e) {
            // Hata logla
        }
    }
    
    /**
     * Yeni admin kullanıcısı oluştur
     * @param array $data
     * @return bool
     */
    public function create($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO admin_users (username, password, email, full_name, role, status) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            return $stmt->execute([
                $data['username'],
                $hashedPassword,
                $data['email'],
                $data['full_name'],
                $data['role'] ?? 'admin',
                $data['status'] ?? 'active'
            ]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Admin kullanıcısını güncelle
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        try {
            $sql = "UPDATE admin_users SET username = ?, email = ?, full_name = ?, role = ?, status = ?";
            $params = [$data['username'], $data['email'], $data['full_name'], $data['role'], $data['status']];
            
            // Şifre güncelleniyorsa
            if (!empty($data['password'])) {
                $sql .= ", password = ?";
                $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Admin kullanıcısını sil
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM admin_users WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Tüm admin kullanıcılarını getir
     * @return array
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("
                SELECT id, username, email, full_name, role, status, last_login, created_at 
                FROM admin_users 
                ORDER BY created_at DESC
            ");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * ID'ye göre admin kullanıcısını getir
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, username, email, full_name, role, status, last_login, created_at 
                FROM admin_users 
                WHERE id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Kullanıcı adına göre admin kullanıcısını getir
     * @param string $username
     * @return array|false
     */
    public function getByUsername($username) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, username, email, full_name, role, status, last_login, created_at 
                FROM admin_users 
                WHERE username = ?
            ");
            $stmt->execute([$username]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Şifre değiştir
     * @param int $userId
     * @param string $newPassword
     * @return bool
     */
    public function changePassword($userId, $newPassword) {
        try {
            $stmt = $this->db->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            return $stmt->execute([$hashedPassword, $userId]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Kullanıcı adı benzersizlik kontrolü
     * @param string $username
     * @param int $excludeId
     * @return bool
     */
    public function isUsernameUnique($username, $excludeId = null) {
        try {
            $sql = "SELECT COUNT(*) FROM admin_users WHERE username = ?";
            $params = [$username];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() == 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * E-posta benzersizlik kontrolü
     * @param string $email
     * @param int $excludeId
     * @return bool
     */
    public function isEmailUnique($email, $excludeId = null) {
        try {
            $sql = "SELECT COUNT(*) FROM admin_users WHERE email = ?";
            $params = [$email];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() == 0;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
