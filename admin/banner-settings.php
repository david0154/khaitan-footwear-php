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
        // Handle Banner Image Upload
        if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {
            $ext = pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION);
            $filename = 'hero-banner-' . time() . '.' . $ext;
            $filepath = '../uploads/banners/' . $filename;
            
            if (!file_exists('../uploads/banners')) {
                mkdir('../uploads/banners', 0755, true);
            }
            
            if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $filepath)) {
                $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('hero_banner_image', ?) ON DUPLICATE KEY UPDATE value = ?");
                $stmt->execute(['banners/' . $filename, 'banners/' . $filename]);
            }
        }
        
        // Save Hero Text
        $heroSettings = [
            'hero_title' => $_POST['hero_title'] ?? '',
            'hero_subtitle' => $_POST['hero_subtitle'] ?? '',
            'hero_button_text' => $_POST['hero_button_text'] ?? '',
            'hero_button_link' => $_POST['hero_button_link'] ?? '',
        ];
        
        foreach ($heroSettings as $key => $value) {
            $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->execute([$key, $value, $value]);
        }
        
        $success = 'Hero banner updated successfully!';
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
    <title>Hero Banner Settings - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'sidebar.php'; ?>
        
        <main class="flex-1">
            <header class="bg-white shadow px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-900">🌅 Hero Banner Settings</h1>
                <p class="text-gray-600">Manage homepage hero section</p>
            </header>
            
            <div class="p-6">
                <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($success) ?>
                </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <h2 class="text-xl font-bold mb-4">🖼️ Background Image</h2>
                        
                        <?php if (!empty($settings['hero_banner_image'])): ?>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Current Hero Banner:</p>
                            <img src="../uploads/<?= htmlspecialchars($settings['hero_banner_image']) ?>" 
                                 alt="Hero Banner" class="w-full max-h-96 object-cover rounded">
                        </div>
                        <?php endif; ?>
                        
                        <label class="block mb-2 font-medium">Upload New Hero Image</label>
                        <input type="file" name="hero_image" accept="image/*" class="w-full px-4 py-3 border rounded">
                        <p class="text-sm text-gray-600 mt-2">Recommended: 1920x800px, JPG/PNG format</p>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">📝 Hero Text Content</h2>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block mb-2 font-medium">Hero Title</label>
                                <input type="text" name="hero_title" 
                                       value="<?= htmlspecialchars($settings['hero_title'] ?? 'Welcome to Khaitan Footwear') ?>" 
                                       class="w-full px-4 py-3 border rounded" 
                                       placeholder="Welcome to Khaitan Footwear">
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-medium">Hero Subtitle</label>
                                <textarea name="hero_subtitle" rows="3" class="w-full px-4 py-3 border rounded" 
                                          placeholder="Leading manufacturer and supplier of quality footwear"><?= htmlspecialchars($settings['hero_subtitle'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2 font-medium">Button Text</label>
                                    <input type="text" name="hero_button_text" 
                                           value="<?= htmlspecialchars($settings['hero_button_text'] ?? 'View Products') ?>" 
                                           class="w-full px-4 py-3 border rounded" 
                                           placeholder="View Products">
                                </div>
                                
                                <div>
                                    <label class="block mb-2 font-medium">Button Link</label>
                                    <input type="text" name="hero_button_link" 
                                           value="<?= htmlspecialchars($settings['hero_button_link'] ?? 'products.php') ?>" 
                                           class="w-full px-4 py-3 border rounded" 
                                           placeholder="products.php">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg font-bold hover:shadow-lg transition">
                            💾 Save Hero Banner
                        </button>
                        <a href="index.php" target="_blank" class="ml-4 px-8 py-3 bg-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-400 transition inline-block">
                            👁️ Preview Homepage
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
