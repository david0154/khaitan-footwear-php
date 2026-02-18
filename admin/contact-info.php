<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $contactSettings = [
            'site_phone' => $_POST['site_phone'] ?? '',
            'site_phone_2' => $_POST['site_phone_2'] ?? '',
            'site_email' => $_POST['site_email'] ?? '',
            'site_email_sales' => $_POST['site_email_sales'] ?? '',
            'site_address' => $_POST['site_address'] ?? '',
            'site_city' => $_POST['site_city'] ?? '',
            'site_state' => $_POST['site_state'] ?? '',
            'site_pincode' => $_POST['site_pincode'] ?? '',
            'site_country' => $_POST['site_country'] ?? 'India',
        ];
        
        foreach ($contactSettings as $key => $value) {
            $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->execute([$key, $value, $value]);
        }
        
        $success = 'Contact information updated successfully!';
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get current settings
$stmt = $pdo->query("SELECT * FROM settings");
$settings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $settings[$row['key']] = $row['value'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contact Information - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'sidebar.php'; ?>
        
        <main class="flex-1">
            <header class="bg-white shadow px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-900">📞 Contact Information</h1>
                <p class="text-gray-600">Manage company contact details displayed on website</p>
            </header>
            
            <div class="p-6">
                <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    ✅ <?= htmlspecialchars($success) ?>
                </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    ❌ <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" class="space-y-6">
                    <!-- Phone Numbers -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">📱 Phone Numbers</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 font-medium">Primary Phone *</label>
                                <input type="tel" name="site_phone" 
                                       value="<?= htmlspecialchars($settings['site_phone'] ?? '') ?>" 
                                       required
                                       placeholder="+91 98765 43210"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <p class="text-sm text-gray-600 mt-1">Displayed in header and contact page</p>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-medium">Secondary Phone</label>
                                <input type="tel" name="site_phone_2" 
                                       value="<?= htmlspecialchars($settings['site_phone_2'] ?? '') ?>" 
                                       placeholder="+91 98765 43211"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <p class="text-sm text-gray-600 mt-1">Optional alternate number</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Email Addresses -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">📧 Email Addresses</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 font-medium">Primary Email *</label>
                                <input type="email" name="site_email" 
                                       value="<?= htmlspecialchars($settings['site_email'] ?? '') ?>" 
                                       required
                                       placeholder="info@khaitanfootwear.in"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <p class="text-sm text-gray-600 mt-1">Main contact email</p>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-medium">Sales Email</label>
                                <input type="email" name="site_email_sales" 
                                       value="<?= htmlspecialchars($settings['site_email_sales'] ?? '') ?>" 
                                       placeholder="sales@khaitanfootwear.in"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <p class="text-sm text-gray-600 mt-1">For sales inquiries</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Physical Address -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">📍 Physical Address</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-2 font-medium">Street Address *</label>
                                <textarea name="site_address" rows="2" required
                                          placeholder="123, Industrial Area, Phase 2"
                                          class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"><?= htmlspecialchars($settings['site_address'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block mb-2 font-medium">City *</label>
                                    <input type="text" name="site_city" 
                                           value="<?= htmlspecialchars($settings['site_city'] ?? '') ?>" 
                                           required
                                           placeholder="New Delhi"
                                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>
                                
                                <div>
                                    <label class="block mb-2 font-medium">State *</label>
                                    <input type="text" name="site_state" 
                                           value="<?= htmlspecialchars($settings['site_state'] ?? '') ?>" 
                                           required
                                           placeholder="Delhi"
                                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>
                                
                                <div>
                                    <label class="block mb-2 font-medium">PIN Code *</label>
                                    <input type="text" name="site_pincode" 
                                           value="<?= htmlspecialchars($settings['site_pincode'] ?? '') ?>" 
                                           required
                                           placeholder="110001"
                                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>
                                
                                <div>
                                    <label class="block mb-2 font-medium">Country *</label>
                                    <input type="text" name="site_country" 
                                           value="<?= htmlspecialchars($settings['site_country'] ?? 'India') ?>" 
                                           required
                                           placeholder="India"
                                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preview -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-blue-900 mb-4">👁️ Address Preview</h3>
                        <div class="text-blue-800">
                            <p class="font-semibold"><?= htmlspecialchars($settings['site_address'] ?? '[Street Address]') ?></p>
                            <p><?= htmlspecialchars($settings['site_city'] ?? '[City]') ?>, <?= htmlspecialchars($settings['site_state'] ?? '[State]') ?> - <?= htmlspecialchars($settings['site_pincode'] ?? '[PIN]') ?></p>
                            <p><?= htmlspecialchars($settings['site_country'] ?? 'India') ?></p>
                            <p class="mt-3">📞 <?= htmlspecialchars($settings['site_phone'] ?? '[Phone]') ?></p>
                            <?php if (!empty($settings['site_phone_2'])): ?>
                            <p>📞 <?= htmlspecialchars($settings['site_phone_2']) ?></p>
                            <?php endif; ?>
                            <p>📧 <?= htmlspecialchars($settings['site_email'] ?? '[Email]') ?></p>
                            <?php if (!empty($settings['site_email_sales'])): ?>
                            <p>📧 <?= htmlspecialchars($settings['site_email_sales']) ?> (Sales)</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg font-bold hover:shadow-lg transition">
                            💾 Save Contact Information
                        </button>
                        <a href="../contact.php" target="_blank" class="px-8 py-3 bg-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-400 transition inline-block">
                            👁️ Preview Contact Page
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>