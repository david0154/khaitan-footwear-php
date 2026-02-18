<?php
require_once 'includes/header.php';

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings_to_save = [
        'smtp_host' => $_POST['smtp_host'],
        'smtp_port' => $_POST['smtp_port'],
        'smtp_user' => $_POST['smtp_user'],
        'smtp_pass' => $_POST['smtp_pass'],
        'email_from_name' => $_POST['email_from_name'],
        'email_from_email' => $_POST['email_from_email'],
    ];
    
    foreach ($settings_to_save as $key => $value) {
        $stmt = $pdo->prepare("SELECT id FROM settings WHERE `key` = ?");
        $stmt->execute([$key]);
        if ($stmt->fetch()) {
            $pdo->prepare("UPDATE settings SET value = ? WHERE `key` = ?")->execute([$value, $key]);
        } else {
            $pdo->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?)")->execute([$key, $value]);
        }
    }
    $success = 'Email settings saved successfully';
}

$email_settings = [];
$stmt = $pdo->query("SELECT * FROM settings WHERE `key` LIKE 'smtp_%' OR `key` LIKE 'email_%'");
while ($row = $stmt->fetch()) {
    $email_settings[$row['key']] = $row['value'];
}
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">✉️ Email Settings</h1>
    <p class="text-gray-600">Configure SMTP settings for email notifications</p>
</div>

<?php if ($success): ?>
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
    ✓ <?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-lg p-8">
    <form method="POST" class="space-y-6">
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <p class="text-blue-800">
                <strong>Note:</strong> Use Gmail SMTP or your hosting email server. For Gmail, enable "Less secure app access" or use App Password.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-medium mb-2">SMTP Host</label>
                <input type="text" name="smtp_host" value="<?= htmlspecialchars($email_settings['smtp_host'] ?? 'smtp.gmail.com') ?>" placeholder="smtp.gmail.com" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Gmail: smtp.gmail.com | Other: check your provider</p>
            </div>
            
            <div>
                <label class="block font-medium mb-2">SMTP Port</label>
                <input type="number" name="smtp_port" value="<?= htmlspecialchars($email_settings['smtp_port'] ?? '587') ?>" placeholder="587" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Common: 587 (TLS) or 465 (SSL)</p>
            </div>
        </div>
        
        <div>
            <label class="block font-medium mb-2">SMTP Username (Email)</label>
            <input type="email" name="smtp_user" value="<?= htmlspecialchars($email_settings['smtp_user'] ?? '') ?>" placeholder="your-email@gmail.com" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block font-medium mb-2">SMTP Password</label>
            <input type="password" name="smtp_pass" value="<?= htmlspecialchars($email_settings['smtp_pass'] ?? '') ?>" placeholder="Your email password or app password" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            <p class="text-sm text-gray-500 mt-1">For Gmail, use App Password (not your regular password)</p>
        </div>
        
        <div class="border-t pt-6">
            <h3 class="text-lg font-bold mb-4">From Email Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-medium mb-2">From Name</label>
                    <input type="text" name="email_from_name" value="<?= htmlspecialchars($email_settings['email_from_name'] ?? 'Khaitan Footwear') ?>" placeholder="Khaitan Footwear" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block font-medium mb-2">From Email</label>
                    <input type="email" name="email_from_email" value="<?= htmlspecialchars($email_settings['email_from_email'] ?? '') ?>" placeholder="noreply@khaitanfootwear.in" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
            </div>
        </div>
        
        <button type="submit" class="bg-gradient-to-r from-orange-600 to-red-600 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition">
            💾 Save Email Settings
        </button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>