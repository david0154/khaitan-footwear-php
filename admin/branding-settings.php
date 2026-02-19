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
        // Branding Settings
        $brandingSettings = [
            'site_name' => $_POST['site_name'] ?? 'Khaitan Footwear',
            'site_tagline' => $_POST['site_tagline'] ?? '',
            'show_social_media' => isset($_POST['show_social_media']) ? '1' : '0',
        ];
        
        // Social Media URLs
        $socialSettings = [
            'facebook_url' => $_POST['facebook_url'] ?? '',
            'instagram_url' => $_POST['instagram_url'] ?? '',
            'twitter_url' => $_POST['twitter_url'] ?? '',
            'linkedin_url' => $_POST['linkedin_url'] ?? '',
            'youtube_url' => $_POST['youtube_url'] ?? '',
            'whatsapp_number' => $_POST['whatsapp_number'] ?? '',
        ];
        
        $allSettings = array_merge($brandingSettings, $socialSettings);
        
        foreach ($allSettings as $key => $value) {
            $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->execute([$key, $value, $value]);
        }
        
        // Handle logo upload
        if (!empty($_FILES['site_logo']['name'])) {
            $ext = pathinfo($_FILES['site_logo']['name'], PATHINFO_EXTENSION);
            $filename = 'logo_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['site_logo']['tmp_name'], '../uploads/' . $filename)) {
                $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('site_logo', ?) ON DUPLICATE KEY UPDATE value = ?");
                $stmt->execute([$filename, $filename]);
            }
        }
        
        // Handle favicon upload
        if (!empty($_FILES['site_favicon']['name'])) {
            $ext = pathinfo($_FILES['site_favicon']['name'], PATHINFO_EXTENSION);
            $filename = 'favicon_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['site_favicon']['tmp_name'], '../uploads/' . $filename)) {
                $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('site_favicon', ?) ON DUPLICATE KEY UPDATE value = ?");
                $stmt->execute([$filename, $filename]);
            }
        }
        
        $success = '✅ Branding and social media settings updated successfully!';
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
    <title>Branding & Social - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'sidebar.php'; ?>
        
        <main class="flex-1">
            <header class="bg-white shadow px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-900">🎨 Branding & Social Media</h1>
                <p class="text-gray-600">Manage your brand identity, logo, favicon and social media</p>
            </header>
            
            <div class="p-6">
                <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($success) ?>
                </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    ❌ <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <!-- Brand Identity -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">🏪 Brand Identity</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-2 font-medium">Company Name *</label>
                                <input type="text" name="site_name" 
                                       value="<?= htmlspecialchars($settings['site_name'] ?? 'Khaitan Footwear') ?>" 
                                       required
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500">
                                <p class="text-sm text-gray-600 mt-1">Displayed in header and throughout the website</p>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-medium">Tagline / Slogan</label>
                                <input type="text" name="site_tagline" 
                                       value="<?= htmlspecialchars($settings['site_tagline'] ?? '') ?>" 
                                       placeholder="e.g., Quality Footwear Since 1990"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500">
                                <p class="text-sm text-gray-600 mt-1">Shows below company name in header</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Company Logo -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">🖼️ Company Logo</h2>
                        <p class="text-gray-600 mb-4">Upload your company logo (shows in website header next to company name)</p>
                        
                        <div>
                            <label class="block mb-2 font-medium">Upload Logo Image</label>
                            <input type="file" name="site_logo" accept="image/*" 
                                   class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500">
                            <p class="text-sm text-gray-600 mt-1">Recommended: PNG with transparent background, 200x80 pixels, max 500KB</p>
                            
                            <?php if (!empty($settings['site_logo'])): ?>
                            <div class="mt-4 p-4 bg-gray-50 rounded border">
                                <p class="text-sm font-semibold mb-3 text-gray-700">📌 Current Logo:</p>
                                <div class="bg-white p-4 rounded border-2 border-gray-200 inline-block">
                                    <img src="../uploads/<?= htmlspecialchars($settings['site_logo']) ?>" 
                                         alt="Company Logo" 
                                         class="h-20 w-auto object-contain">
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Upload a new image to replace this logo</p>
                            </div>
                            <?php else: ?>
                            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded">
                                <p class="text-sm text-yellow-800">⚠️ No logo uploaded yet. Upload one to display in header.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Favicon (Browser Icon) -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">⭐ Favicon (Browser Tab Icon)</h2>
                        <p class="text-gray-600 mb-4">Upload favicon - the small icon that appears in browser tabs and bookmarks</p>
                        
                        <div>
                            <label class="block mb-2 font-medium">Upload Favicon</label>
                            <input type="file" name="site_favicon" accept="image/*,.ico" 
                                   class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500">
                            <p class="text-sm text-gray-600 mt-1">Recommended: ICO or PNG format, 32x32 or 64x64 pixels, max 100KB</p>
                            
                            <?php if (!empty($settings['site_favicon'])): ?>
                            <div class="mt-4 p-4 bg-gray-50 rounded border">
                                <p class="text-sm font-semibold mb-3 text-gray-700">📌 Current Favicon:</p>
                                <div class="bg-white p-4 rounded border-2 border-gray-200 inline-block">
                                    <img src="../uploads/<?= htmlspecialchars($settings['site_favicon']) ?>" 
                                         alt="Favicon" 
                                         class="h-8 w-8 object-contain">
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Upload a new icon to replace this favicon</p>
                            </div>
                            <?php else: ?>
                            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded">
                                <p class="text-sm text-yellow-800">⚠️ No favicon uploaded yet. Upload one to display in browser tabs.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Social Media Toggle -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">🔗 Social Media Display</h2>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="show_social_media" value="1" 
                                   <?= !empty($settings['show_social_media']) ? 'checked' : '' ?>
                                   class="w-6 h-6 text-red-600 rounded focus:ring-2 focus:ring-red-500">
                            <span class="font-medium">Show Social Media Icons in Header & Footer</span>
                        </label>
                        <p class="text-sm text-gray-600 mt-2 ml-9">When enabled, social media icons will appear in website top bar and footer</p>
                    </div>
                    
                    <!-- Social Media Links -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">👥 Social Media Links</h2>
                        <p class="text-gray-600 mb-4">Enter your social media profile URLs (leave blank to hide)</p>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="flex items-center space-x-2 mb-2 font-medium">
                                    <span class="text-blue-600 text-xl">🔵</span>
                                    <span>Facebook Page URL</span>
                                </label>
                                <input type="url" name="facebook_url" 
                                       value="<?= htmlspecialchars($settings['facebook_url'] ?? '') ?>" 
                                       placeholder="https://facebook.com/yourpage"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="flex items-center space-x-2 mb-2 font-medium">
                                    <span class="text-pink-600 text-xl">🟣</span>
                                    <span>Instagram Profile URL</span>
                                </label>
                                <input type="url" name="instagram_url" 
                                       value="<?= htmlspecialchars($settings['instagram_url'] ?? '') ?>" 
                                       placeholder="https://instagram.com/yourprofile"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-pink-500">
                            </div>
                            
                            <div>
                                <label class="flex items-center space-x-2 mb-2 font-medium">
                                    <span class="text-blue-400 text-xl">🔷</span>
                                    <span>Twitter/X Profile URL</span>
                                </label>
                                <input type="url" name="twitter_url" 
                                       value="<?= htmlspecialchars($settings['twitter_url'] ?? '') ?>" 
                                       placeholder="https://twitter.com/yourprofile"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-400">
                            </div>
                            
                            <div>
                                <label class="flex items-center space-x-2 mb-2 font-medium">
                                    <span class="text-blue-700 text-xl">🔶</span>
                                    <span>LinkedIn Company URL</span>
                                </label>
                                <input type="url" name="linkedin_url" 
                                       value="<?= htmlspecialchars($settings['linkedin_url'] ?? '') ?>" 
                                       placeholder="https://linkedin.com/company/yourcompany"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-700">
                            </div>
                            
                            <div>
                                <label class="flex items-center space-x-2 mb-2 font-medium">
                                    <span class="text-red-600 text-xl">🔴</span>
                                    <span>YouTube Channel URL</span>
                                </label>
                                <input type="url" name="youtube_url" 
                                       value="<?= htmlspecialchars($settings['youtube_url'] ?? '') ?>" 
                                       placeholder="https://youtube.com/@yourchannel"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-600">
                            </div>
                            
                            <div>
                                <label class="flex items-center space-x-2 mb-2 font-medium">
                                    <span class="text-green-600 text-xl">🟢</span>
                                    <span>WhatsApp Business Number</span>
                                </label>
                                <input type="tel" name="whatsapp_number" 
                                       value="<?= htmlspecialchars($settings['whatsapp_number'] ?? '') ?>" 
                                       placeholder="919876543210 (country code + number, no spaces)"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-600">
                                <p class="text-sm text-gray-600 mt-1">Format: Country code + number (e.g., 919876543210 for India)</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preview -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-blue-900 mb-4">👁️ Header Preview</h3>
                        <div class="bg-white p-4 rounded border">
                            <div class="flex items-center space-x-3">
                                <?php if (!empty($settings['site_logo'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($settings['site_logo']) ?>" class="h-12 w-auto">
                                <?php endif; ?>
                                <div>
                                    <div class="text-xl font-bold text-red-600"><?= htmlspecialchars($settings['site_name'] ?? 'Khaitan Footwear') ?></div>
                                    <?php if (!empty($settings['site_tagline'])): ?>
                                    <div class="text-xs text-gray-600 italic"><?= htmlspecialchars($settings['site_tagline']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg font-bold hover:shadow-lg transition">
                            💾 Save All Settings
                        </button>
                        <a href="../" target="_blank" class="px-8 py-3 bg-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-400 transition inline-block">
                            👁️ Preview Website
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>