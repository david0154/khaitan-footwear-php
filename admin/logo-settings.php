<?php
require_once 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $filename = 'logo.' . $ext;
            $destination = '../uploads/' . $filename;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $destination)) {
                $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('site_logo', ?) ON DUPLICATE KEY UPDATE value = ?");
                $stmt->execute([$filename, $filename]);
                $success = 'Logo uploaded successfully!';
            } else {
                $error = 'Failed to upload logo';
            }
        } else {
            $error = 'Invalid file type. Allowed: JPG, PNG, GIF, SVG, WEBP';
        }
    }
    
    // Favicon upload
    if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === 0) {
        $allowed = ['ico', 'png', 'jpg', 'jpeg', 'gif'];
        $ext = strtolower(pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $filename = 'favicon.' . $ext;
            $destination = '../uploads/' . $filename;
            
            if (move_uploaded_file($_FILES['favicon']['tmp_name'], $destination)) {
                $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('site_favicon', ?) ON DUPLICATE KEY UPDATE value = ?");
                $stmt->execute([$filename, $filename]);
                $success = 'Favicon uploaded successfully!';
            } else {
                $error = 'Failed to upload favicon';
            }
        } else {
            $error = 'Invalid file type for favicon';
        }
    }
    
    // Hero Banner Image upload
    if (isset($_FILES['hero_banner']) && $_FILES['hero_banner']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['hero_banner']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $filename = 'hero_banner_' . time() . '.' . $ext;
            $destination = '../uploads/' . $filename;
            
            if (move_uploaded_file($_FILES['hero_banner']['tmp_name'], $destination)) {
                $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('hero_banner_image', ?) ON DUPLICATE KEY UPDATE value = ?");
                $stmt->execute([$filename, $filename]);
                $success = 'Banner image uploaded successfully!';
            } else {
                $error = 'Failed to upload banner';
            }
        } else {
            $error = 'Invalid file type for banner';
        }
    }
    
    // Save text settings
    $textSettings = ['logo_text', 'hero_title', 'hero_subtitle', 'hero_button_text', 'hero_button_link'];
    foreach ($textSettings as $key) {
        if (isset($_POST[$key])) {
            $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->execute([$key, $_POST[$key], $_POST[$key]]);
        }
    }
    
    // Social media settings
    if (isset($_POST['show_social_media'])) {
        $value = isset($_POST['show_social_media']) ? '1' : '0';
        $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('show_social_media', ?) ON DUPLICATE KEY UPDATE value = ?");
        $stmt->execute([$value, $value]);
    }
    
    $socialKeys = ['facebook_url', 'instagram_url', 'twitter_url', 'linkedin_url', 'youtube_url', 'whatsapp_number'];
    foreach ($socialKeys as $key) {
        if (isset($_POST[$key])) {
            $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->execute([$key, $_POST[$key], $_POST[$key]]);
        }
    }
    
    if (empty($error) && empty($success)) {
        $success = 'Settings saved successfully!';
    }
}

// Get all settings
$stmt = $pdo->query("SELECT * FROM settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['key']] = $row['value'];
}
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">🎨 Logo, Favicon & Banner</h1>
    <p class="text-gray-600">Manage your branding, favicon, homepage banner and social media</p>
</div>

<?php if ($success): ?>
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
    ✓ <?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
    ✗ <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="space-y-6">

<!-- Logo & Favicon Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Logo Upload -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">📷 Company Logo</h2>
        
        <?php if (!empty($settings['site_logo'])): ?>
        <div class="mb-4 p-4 bg-gray-50 rounded-lg text-center">
            <img src="../uploads/<?= htmlspecialchars($settings['site_logo']) ?>" alt="Logo" class="max-h-24 mx-auto">
            <p class="text-xs text-gray-500 mt-2">Current Logo</p>
        </div>
        <?php endif; ?>
        
        <input type="file" name="logo" accept="image/*" class="w-full px-4 py-3 border rounded-lg">
        <p class="text-sm text-gray-500 mt-2">Recommended: PNG 200x60px</p>
        
        <div class="mt-4">
            <label class="block font-medium mb-2">Text Logo (if no image)</label>
            <input type="text" name="logo_text" value="<?= htmlspecialchars($settings['logo_text'] ?? 'Khaitan Footwear') ?>" class="w-full px-4 py-3 border rounded-lg">
        </div>
    </div>
    
    <!-- Favicon Upload -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">⭐ Favicon (Browser Icon)</h2>
        
        <?php if (!empty($settings['site_favicon'])): ?>
        <div class="mb-4 p-4 bg-gray-50 rounded-lg text-center">
            <img src="../uploads/<?= htmlspecialchars($settings['site_favicon']) ?>" alt="Favicon" class="h-8 w-8 mx-auto">
            <p class="text-xs text-gray-500 mt-2">Current Favicon</p>
        </div>
        <?php endif; ?>
        
        <input type="file" name="favicon" accept=".ico,.png,.jpg,.jpeg,.gif" class="w-full px-4 py-3 border rounded-lg">
        <p class="text-sm text-gray-500 mt-2">Recommended: ICO or PNG 32x32px</p>
        
        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-800">💡 <strong>Tip:</strong> Favicon shows in browser tabs and bookmarks</p>
        </div>
    </div>
</div>

<!-- Hero Banner Section -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">🌅 Homepage Hero Banner</h2>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <label class="block font-medium mb-2">Background Image</label>
            <?php if (!empty($settings['hero_banner_image'])): ?>
            <div class="mb-4">
                <img src="../uploads/<?= htmlspecialchars($settings['hero_banner_image']) ?>" class="w-full h-32 object-cover rounded-lg">
                <p class="text-xs text-gray-500 mt-1">Current Banner</p>
            </div>
            <?php endif; ?>
            <input type="file" name="hero_banner" accept="image/*" class="w-full px-4 py-3 border rounded-lg">
            <p class="text-sm text-gray-500 mt-1">Recommended: 1920x600px</p>
        </div>
        
        <div class="space-y-4">
            <div>
                <label class="block font-medium mb-2">Banner Title</label>
                <input type="text" name="hero_title" value="<?= htmlspecialchars($settings['hero_title'] ?? 'Welcome to Khaitan Footwear') ?>" class="w-full px-4 py-3 border rounded-lg">
            </div>
            
            <div>
                <label class="block font-medium mb-2">Banner Subtitle</label>
                <input type="text" name="hero_subtitle" value="<?= htmlspecialchars($settings['hero_subtitle'] ?? 'Leading manufacturer and supplier of quality footwear') ?>" class="w-full px-4 py-3 border rounded-lg">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-2">Button Text</label>
                    <input type="text" name="hero_button_text" value="<?= htmlspecialchars($settings['hero_button_text'] ?? 'View Products') ?>" class="w-full px-4 py-3 border rounded-lg">
                </div>
                <div>
                    <label class="block font-medium mb-2">Button Link</label>
                    <input type="text" name="hero_button_link" value="<?= htmlspecialchars($settings['hero_button_link'] ?? 'products.php') ?>" class="w-full px-4 py-3 border rounded-lg">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Social Media Section -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">📱 Social Media</h2>
    
    <div class="mb-6">
        <label class="flex items-center space-x-3 cursor-pointer">
            <input type="checkbox" name="show_social_media" value="1" <?= !empty($settings['show_social_media']) ? 'checked' : '' ?> class="w-5 h-5 text-red-600 rounded">
            <span class="font-medium">Show Social Media Icons on Website</span>
        </label>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block font-medium mb-2">📘 Facebook URL</label>
            <input type="url" name="facebook_url" value="<?= htmlspecialchars($settings['facebook_url'] ?? '') ?>" placeholder="https://facebook.com/yourpage" class="w-full px-4 py-3 border rounded-lg">
        </div>
        
        <div>
            <label class="block font-medium mb-2">📸 Instagram URL</label>
            <input type="url" name="instagram_url" value="<?= htmlspecialchars($settings['instagram_url'] ?? '') ?>" placeholder="https://instagram.com/yourprofile" class="w-full px-4 py-3 border rounded-lg">
        </div>
        
        <div>
            <label class="block font-medium mb-2">🐦 Twitter/X URL</label>
            <input type="url" name="twitter_url" value="<?= htmlspecialchars($settings['twitter_url'] ?? '') ?>" placeholder="https://twitter.com/yourprofile" class="w-full px-4 py-3 border rounded-lg">
        </div>
        
        <div>
            <label class="block font-medium mb-2">💼 LinkedIn URL</label>
            <input type="url" name="linkedin_url" value="<?= htmlspecialchars($settings['linkedin_url'] ?? '') ?>" placeholder="https://linkedin.com/company/yourcompany" class="w-full px-4 py-3 border rounded-lg">
        </div>
        
        <div>
            <label class="block font-medium mb-2">📹 YouTube URL</label>
            <input type="url" name="youtube_url" value="<?= htmlspecialchars($settings['youtube_url'] ?? '') ?>" placeholder="https://youtube.com/@yourchannel" class="w-full px-4 py-3 border rounded-lg">
        </div>
        
        <div>
            <label class="block font-medium mb-2">💬 WhatsApp Number</label>
            <input type="tel" name="whatsapp_number" value="<?= htmlspecialchars($settings['whatsapp_number'] ?? '') ?>" placeholder="919876543210" class="w-full px-4 py-3 border rounded-lg">
            <p class="text-xs text-gray-500 mt-1">Format: Country code + number (e.g. 919876543210)</p>
        </div>
    </div>
</div>

<!-- Save Button -->
<div class="flex gap-4">
    <button type="submit" class="flex-1 bg-gradient-to-r from-red-600 to-orange-600 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition">
        💾 Save All Settings
    </button>
    <a href="../index.php" target="_blank" class="px-8 py-4 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition">
        👁️ Preview Website
    </a>
</div>

</form>

<?php require_once 'includes/footer.php'; ?>