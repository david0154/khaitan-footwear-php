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
        // Hero Banner Settings
        $heroSettings = [
            'hero_title' => $_POST['hero_title'] ?? 'Welcome to Khaitan Footwear',
            'hero_subtitle' => $_POST['hero_subtitle'] ?? 'Leading manufacturer and supplier of quality footwear',
            'hero_button_text' => $_POST['hero_button_text'] ?? 'View Products',
            'hero_button_link' => $_POST['hero_button_link'] ?? 'products.php',
        ];
        
        foreach ($heroSettings as $key => $value) {
            $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->execute([$key, $value, $value]);
        }
        
        // Handle hero banner image upload
        if (!empty($_FILES['hero_banner_image']['name'])) {
            $ext = pathinfo($_FILES['hero_banner_image']['name'], PATHINFO_EXTENSION);
            $filename = 'hero_' . time() . '.' . $ext;
            $uploadPath = '../uploads/' . $filename;
            
            if (move_uploaded_file($_FILES['hero_banner_image']['tmp_name'], $uploadPath)) {
                $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('hero_banner_image', ?) ON DUPLICATE KEY UPDATE value = ?");
                $stmt->execute([$filename, $filename]);
            }
        }
        
        $success = '✅ Hero banner settings updated successfully!';
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
    <title>Hero Banner - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'sidebar.php'; ?>
        
        <main class="flex-1">
            <header class="bg-white shadow px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-900">🎨 Hero Banner Settings</h1>
                <p class="text-gray-600">Customize the main banner on homepage</p>
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
                    <!-- Banner Text -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">📝 Banner Text Content</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-2 font-medium">Main Title *</label>
                                <input type="text" name="hero_title" 
                                       value="<?= htmlspecialchars($settings['hero_title'] ?? 'Welcome to Khaitan Footwear') ?>" 
                                       required
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500">
                                <p class="text-sm text-gray-600 mt-1">Large heading text (e.g., "Welcome to Khaitan Footwear")</p>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-medium">Subtitle *</label>
                                <input type="text" name="hero_subtitle" 
                                       value="<?= htmlspecialchars($settings['hero_subtitle'] ?? 'Leading manufacturer and supplier of quality footwear') ?>" 
                                       required
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500">
                                <p class="text-sm text-gray-600 mt-1">Subtitle below main title</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Banner Button -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">🔘 Call-to-Action Button</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 font-medium">Button Text *</label>
                                <input type="text" name="hero_button_text" 
                                       value="<?= htmlspecialchars($settings['hero_button_text'] ?? 'View Products') ?>" 
                                       required
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500">
                                <p class="text-sm text-gray-600 mt-1">Text shown on button</p>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-medium">Button Link *</label>
                                <input type="text" name="hero_button_link" 
                                       value="<?= htmlspecialchars($settings['hero_button_link'] ?? 'products.php') ?>" 
                                       required
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500">
                                <p class="text-sm text-gray-600 mt-1">Where button goes (e.g., products.php, about.php)</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Background Image -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">🖼️ Background Image</h2>
                        
                        <div>
                            <label class="block mb-2 font-medium">Upload Hero Banner Image</label>
                            <input type="file" name="hero_banner_image" accept="image/*" class="w-full px-4 py-3 border rounded-lg">
                            <p class="text-sm text-gray-600 mt-1">Recommended: 1920x600 pixels, JPG/PNG, max 2MB. Leave empty to use default gradient.</p>
                            
                            <?php if (!empty($settings['hero_banner_image'])): ?>
                            <div class="mt-4 p-4 bg-gray-50 rounded border">
                                <p class="text-sm font-semibold mb-2">Current Banner Image:</p>
                                <img src="../uploads/<?= htmlspecialchars($settings['hero_banner_image']) ?>" class="w-full h-48 object-cover rounded">
                                <p class="text-xs text-gray-600 mt-2">Upload new image to replace</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Preview -->
                    <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-red-900 mb-4">👁️ Live Preview</h3>
                        <div class="relative bg-gradient-to-r from-red-600 to-red-800 text-white rounded-lg p-12 text-center">
                            <?php if (!empty($settings['hero_banner_image'])): ?>
                            <div class="absolute inset-0 rounded-lg overflow-hidden">
                                <img src="../uploads/<?= htmlspecialchars($settings['hero_banner_image']) ?>" class="w-full h-full object-cover opacity-50">
                            </div>
                            <?php endif; ?>
                            <div class="relative z-10">
                                <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($settings['hero_title'] ?? 'Welcome to Khaitan Footwear') ?></h1>
                                <p class="text-xl mb-6 text-red-100"><?= htmlspecialchars($settings['hero_subtitle'] ?? 'Leading manufacturer and supplier of quality footwear') ?></p>
                                <button class="bg-white text-red-600 px-8 py-3 rounded-full font-bold">
                                    <?= htmlspecialchars($settings['hero_button_text'] ?? 'View Products') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg font-bold hover:shadow-lg transition">
                            💾 Save Banner Settings
                        </button>
                        <a href="../" target="_blank" class="px-8 py-3 bg-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-400 transition inline-block">
                            👁️ Preview Homepage
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>