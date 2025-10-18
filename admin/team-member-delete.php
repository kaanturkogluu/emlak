<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/AdminUser.php';
require_once __DIR__ . '/../classes/Helper.php';

$adminUser = new AdminUser();
if (!$adminUser->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$helper = Helper::getInstance();
$db = Database::getInstance()->getConnection();

// ID kontrolü
$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: team-members.php');
    exit;
}

// Ekip üyesi bilgilerini getir
$stmt = $db->prepare("SELECT * FROM about_us_team_members WHERE id = ?");
$stmt->execute([$id]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    header('Location: team-members.php');
    exit;
}

// Silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    try {
        $stmt = $db->prepare("DELETE FROM about_us_team_members WHERE id = ?");
        $stmt->execute([$id]);
        
        header('Location: team-members.php?success=deleted');
        exit;
        
    } catch (Exception $e) {
        $error = "Silme sırasında hata oluştu: " . $e->getMessage();
    }
}

$pageTitle = "Ekip Üyesi Silme";

require_once __DIR__ . '/layout/header.php';
?>

<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Ekip Üyesi Silme</h1>
                <p class="text-gray-600">Bu işlem geri alınamaz!</p>
            </div>
        </div>
    </div>

    <!-- Error Alert -->
    <?php if (isset($error)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Silinecek Ekip Üyesi Bilgileri -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-user text-red-500 mr-2"></i>
            Silinecek Ekip Üyesi
        </h2>
        
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center space-x-4">
                <img src="<?php echo htmlspecialchars($member['image_url'] ?: 'https://via.placeholder.com/80x80?text=No+Image'); ?>" 
                     alt="<?php echo htmlspecialchars($member['name']); ?>" 
                     class="w-16 h-16 rounded-full object-cover flex-shrink-0">
                
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($member['name']); ?></h3>
                    <p class="text-blue-600 font-medium"><?php echo htmlspecialchars($member['position']); ?></p>
                    
                    <?php if ($member['description']): ?>
                        <p class="text-gray-700 text-sm mt-2"><?php echo htmlspecialchars($member['description']); ?></p>
                    <?php endif; ?>
                    
                    <div class="flex items-center space-x-4 text-sm text-gray-500 mt-3">
                        <span>
                            <i class="fas fa-calendar mr-1"></i>
                            Eklenme: <?php echo date('d.m.Y H:i', strtotime($member['created_at'])); ?>
                        </span>
                        <span>
                            <i class="fas fa-sort mr-1"></i>
                            Sıra: <?php echo $member['sort_order']; ?>
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo $member['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                            <?php echo $member['is_active'] ? 'Aktif' : 'Pasif'; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Uyarı Mesajı -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Dikkat!</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>Bu ekip üyesini silmek istediğinizden emin misiniz? Bu işlem:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>Ekip üyesinin tüm bilgilerini kalıcı olarak silecektir</li>
                        <li>Bu işlem geri alınamaz</li>
                        <li>Hakkımızda sayfasından bu kişi kaldırılacaktır</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" class="space-y-4">
            <div class="flex items-center">
                <input type="checkbox" name="confirm_delete" id="confirm_delete" required 
                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                <label for="confirm_delete" class="ml-2 block text-sm text-gray-700">
                    Evet, <strong><?php echo htmlspecialchars($member['name']); ?></strong> adlı ekip üyesini silmek istediğimi onaylıyorum.
                </label>
            </div>
            
            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="team-members.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>İptal
                </a>
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>Ekip Üyesini Sil
                </button>
            </div>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/layout/footer.php';
?>
