<?php
require_once 'includes/header.php';

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings_to_save = [
        'site_tagline' => $_POST['site_tagline'],
        'home_about' => $_POST['home_about'],
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
    $success = 'Tagline & content updated successfully';
}

$content_settings = [];
$stmt = $pdo->query("SELECT * FROM settings WHERE `key` IN ('site_tagline', 'home_about')");
while ($row = $stmt->fetch()) {
    $content_settings[$row['key']] = $row['value'];
}
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">📝 Tagline & Content</h1>
    <p class="text-gray-600">Manage homepage tagline and about text</p>
</div>

<?php if ($success): ?>
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
    ✓ <?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-xl p-8">
    <form method="POST" class="space-y-8">
        <div>
            <label class="block text-xl font-bold mb-4 text-gray-800">Site Tagline</label>
            <input type="text" name="site_tagline" value="<?= htmlspecialchars($content_settings['site_tagline'] ?? 'Leading Manufacturer of Quality Footwear') ?>" class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
            <p class="text-sm text-gray-500 mt-2">This appears below the company name on homepage</p>
            
            <!-- Preview -->
            <div class="mt-6 bg-gradient-to-r from-red-600 to-red-800 text-white p-8 rounded-xl text-center">
                <h2 class="text-4xl font-black mb-4">Khaitan Footwear</h2>
                <p class="text-2xl font-light text-red-100"><?= htmlspecialchars($content_settings['site_tagline'] ?? 'Leading Manufacturer of Quality Footwear') ?></p>
            </div>
        </div>
        
        <div class="border-t pt-8">
            <label class="block text-xl font-bold mb-4 text-gray-800">About Us Text (Homepage)</label>
            <textarea name="home_about" rows="6" class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition resize-none"><?= htmlspecialchars($content_settings['home_about'] ?? 'We are one of the leading footwear manufacturers in India, offering stylish, comfortable and durable products.') ?></textarea>
            <p class="text-sm text-gray-500 mt-2">Short description that appears in About section on homepage</p>
        </div>
        
        <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-800 text-white py-4 rounded-xl font-bold text-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition">
            💾 Save Changes
        </button>
    </form>
</div>

<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
        <h3 class="font-bold text-blue-900 mb-2">💡 Tagline Tips:</h3>
        <ul class="list-disc list-inside text-blue-800 space-y-1">
            <li>Keep it short and memorable</li>
            <li>Highlight your unique value</li>
            <li>Use action words</li>
            <li>Max 10-12 words recommended</li>
        </ul>
    </div>
    
    <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
        <h3 class="font-bold text-green-900 mb-2">✍️ About Text Tips:</h3>
        <ul class="list-disc list-inside text-green-800 space-y-1">
            <li>Focus on key strengths</li>
            <li>Keep it concise (2-3 sentences)</li>
            <li>Mention your experience</li>
            <li>Build trust with customers</li>
        </ul>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>