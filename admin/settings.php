<?php
require_once 'includes/header.php';

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if ($key != 'submit') {
            $stmt = $pdo->prepare("UPDATE settings SET value = ? WHERE `key` = ?");
            if (!$stmt->execute([$value, $key])) {
                $pdo->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?)")->execute([$key, $value]);
            }
        }
    }
    $success = 'Settings updated successfully';
}

$settings_data = [];
$stmt = $pdo->query("SELECT * FROM settings");
while ($row = $stmt->fetch()) {
    $settings_data[$row['key']] = $row['value'];
}
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Settings</h1>

<?php if ($success): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow p-6">
<form method="POST" class="space-y-6">
<div>
<h2 class="text-xl font-bold mb-4">Site Information</h2>
<div class="space-y-4">
<div>
<label class="block font-medium mb-2">Site Name</label>
<input type="text" name="site_name" value="<?= htmlspecialchars($settings_data['site_name'] ?? 'Khaitan Footwear') ?>" class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block font-medium mb-2">Contact Email</label>
<input type="email" name="site_email" value="<?= htmlspecialchars($settings_data['site_email'] ?? '') ?>" class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block font-medium mb-2">Contact Phone</label>
<input type="text" name="site_phone" value="<?= htmlspecialchars($settings_data['site_phone'] ?? '') ?>" class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block font-medium mb-2">Address</label>
<textarea name="site_address" rows="3" class="w-full px-4 py-2 border rounded-lg"><?= htmlspecialchars($settings_data['site_address'] ?? '') ?></textarea>
</div>
</div>
</div>

<button type="submit" name="submit" class="bg-orange-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-700">Save Settings</button>
</form>
</div>

<?php require_once 'includes/footer.php'; ?>