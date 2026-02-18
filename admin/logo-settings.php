<?php
require_once 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $filename = 'logo.' . $ext;
            $destination = '../uploads/' . $filename;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $destination)) {
                // Save to settings
                $stmt = $pdo->prepare("SELECT id FROM settings WHERE `key` = 'site_logo'");
                $stmt->execute();
                if ($stmt->fetch()) {
                    $pdo->prepare("UPDATE settings SET value = ? WHERE `key` = 'site_logo'")->execute([$filename]);
                } else {
                    $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('site_logo', ?)")->execute([$filename]);
                }
                
                $success = 'Logo uploaded successfully!';
            } else {
                $error = 'Failed to upload logo';
            }
        } else {
            $error = 'Invalid file type. Allowed: JPG, PNG, GIF, SVG, WEBP';
        }
    }
    
    // Save logo text
    if (isset($_POST['logo_text'])) {
        $stmt = $pdo->prepare("SELECT id FROM settings WHERE `key` = 'logo_text'");
        $stmt->execute();
        if ($stmt->fetch()) {
            $pdo->prepare("UPDATE settings SET value = ? WHERE `key` = 'logo_text'")->execute([$_POST['logo_text']]);
        } else {
            $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('logo_text', ?)")->execute([$_POST['logo_text']]);
        }
        $success = 'Logo text saved!';
    }
}

// Get current logo
$stmt = $pdo->query("SELECT * FROM settings WHERE `key` IN ('site_logo', 'logo_text')");
$logo_settings = [];
while ($row = $stmt->fetch()) {
    $logo_settings[$row['key']] = $row['value'];
}
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">🎨 Logo & Branding</h1>
    <p class="text-gray-600">Upload your logo and customize branding</p>
</div>

<?php if ($success): ?>
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 animate-slide-in">
    ✓ <?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
    ✗ <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Logo Upload -->
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold mb-6 flex items-center">
            <span class="w-2 h-8 bg-gradient-to-b from-orange-600 to-red-600 mr-3 rounded"></span>
            Logo Image
        </h2>
        
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="border-4 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-orange-500 transition">
                <?php if (!empty($logo_settings['site_logo'])): ?>
                <div class="mb-4">
                    <img src="../uploads/<?= htmlspecialchars($logo_settings['site_logo']) ?>" alt="Current Logo" class="max-h-32 mx-auto logo-glow">
                    <p class="text-sm text-gray-500 mt-2">Current Logo</p>
                </div>
                <?php endif; ?>
                
                <input type="file" name="logo" accept="image/*" class="hidden" id="logo-upload">
                <label for="logo-upload" class="cursor-pointer">
                    <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold mb-2">Click to Upload Logo</p>
                    <p class="text-sm text-gray-500">PNG, JPG, GIF, SVG, WEBP up to 10MB</p>
                </label>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition">
                💾 Upload Logo
            </button>
        </form>
    </div>
    
    <!-- Logo Text -->
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold mb-6 flex items-center">
            <span class="w-2 h-8 bg-gradient-to-b from-blue-600 to-purple-600 mr-3 rounded"></span>
            Text Logo
        </h2>
        
        <form method="POST" class="space-y-6">
            <div>
                <label class="block font-medium mb-3 text-gray-700">Company Name (if no logo image)</label>
                <input type="text" name="logo_text" value="<?= htmlspecialchars($logo_settings['logo_text'] ?? 'Khaitan Footwear') ?>" class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                <p class="text-sm text-gray-500 mt-2">This will be used if no logo image is uploaded</p>
            </div>
            
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 p-8 rounded-xl">
                <p class="text-white text-center text-2xl font-bold neon-orange">
                    <?= htmlspecialchars($logo_settings['logo_text'] ?? 'Khaitan Footwear') ?>
                </p>
                <p class="text-gray-400 text-sm text-center mt-2">Preview with Neon Effect</p>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition">
                💾 Save Text Logo
            </button>
        </form>
    </div>
</div>

<div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
    <h3 class="font-bold text-blue-900 mb-2">💡 Tips for Best Results:</h3>
    <ul class="list-disc list-inside text-blue-800 space-y-1">
        <li>Use transparent PNG for best quality</li>
        <li>Recommended size: 200x60 pixels</li>
        <li>SVG format for perfect scaling</li>
        <li>Keep file size under 500KB</li>
    </ul>
</div>

<style>
@keyframes slide-in {
    from { opacity: 0; transform: translateX(-20px); }
    to { opacity: 1; transform: translateX(0); }
}
.animate-slide-in { animation: slide-in 0.5s ease-out; }
</style>

<?php require_once 'includes/footer.php'; ?>