<?php
/**
 * Database Repair Script
 * Run this if you get "Column not found" errors
 * Visit: http://yourdomain.com/repair-database.php
 */

if (!file_exists('config.php')) {
    die('Please run install.php first');
}

require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $repairs = [];
    $errors = [];
    
    // Check and add missing columns
    $fixes = [
        // Categories table
        "ALTER TABLE categories ADD COLUMN IF NOT EXISTS order_num INT DEFAULT 0 AFTER status",
        "ALTER TABLE categories ADD INDEX IF NOT EXISTS idx_order (order_num)",
        
        // Banners table
        "ALTER TABLE banners ADD COLUMN IF NOT EXISTS order_num INT DEFAULT 0 AFTER status",
        "ALTER TABLE banners ADD INDEX IF NOT EXISTS idx_order (order_num)",
        
        // Products table - add updated_at if missing
        "ALTER TABLE products ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at",
        
        // Users table - add updated_at if missing
        "ALTER TABLE users ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at",
        
        // Add indexes for better performance
        "ALTER TABLE products ADD INDEX IF NOT EXISTS idx_featured (is_featured)",
        "ALTER TABLE products ADD INDEX IF NOT EXISTS idx_status (status)",
        "ALTER TABLE contacts ADD INDEX IF NOT EXISTS idx_status (status)",
        "ALTER TABLE categories ADD INDEX IF NOT EXISTS idx_status (status)",
        "ALTER TABLE banners ADD INDEX IF NOT EXISTS idx_status (status)",
    ];
    
    foreach ($fixes as $sql) {
        try {
            $pdo->exec($sql);
            $repairs[] = $sql;
        } catch (Exception $e) {
            // Column might already exist, that's okay
            if (strpos($e->getMessage(), 'Duplicate') === false) {
                $errors[] = $e->getMessage();
            }
        }
    }
    
    // Insert default settings if missing
    $settings_to_add = [
        ['site_tagline', 'Leading Manufacturer of Quality Footwear'],
        ['home_about', 'We are one of the leading footwear manufacturers in India, offering stylish, comfortable and durable products.'],
        ['logo_text', 'Khaitan Footwear'],
    ];
    
    foreach ($settings_to_add as $setting) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM settings WHERE `key` = ?");
            $stmt->execute([$setting[0]]);
            if (!$stmt->fetch()) {
                $pdo->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?)")->execute($setting);
                $repairs[] = "Added setting: {$setting[0]}";
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
    
    // Insert default categories if missing
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
    if ($stmt->fetchColumn() == 0) {
        $categories = [
            ['Gents Collection', 'gents-collection', 'Premium footwear for men', 1],
            ['Ladies Collection', 'ladies-collection', 'Stylish footwear for women', 2],
            ['Kids Collection', 'kids-collection', 'Comfortable shoes for kids', 3],
            ['Sports Shoes', 'sports-shoes', 'Athletic and sports footwear', 4],
        ];
        
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description, order_num, status) VALUES (?, ?, ?, ?, 'active')");
        foreach ($categories as $cat) {
            $stmt->execute($cat);
        }
        $repairs[] = "Added default categories";
    }
    
    // Insert default banner if missing
    $stmt = $pdo->query("SELECT COUNT(*) FROM banners");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO banners (title, subtitle, button_text, button_link, status, order_num) VALUES ('Welcome to Khaitan Footwear', 'Leading Manufacturer & Supplier of Quality Footwear', 'View Products', 'products.php', 'active', 1)");
        $repairs[] = "Added default banner";
    }
    
    $success = true;
    
} catch (Exception $e) {
    $success = false;
    $errors[] = $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Database Repair - Khaitan Footwear</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-xl p-8">
                <h1 class="text-3xl font-bold text-center mb-8">
                    <?php if ($success): ?>
                    <span class="text-green-600">✓ Database Repaired</span>
                    <?php else: ?>
                    <span class="text-red-600">⚠ Repair Issues</span>
                    <?php endif; ?>
                </h1>
                
                <?php if (!empty($repairs)): ?>
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4 text-green-700">✓ Repairs Applied:</h2>
                    <div class="bg-green-50 border border-green-200 rounded p-4">
                        <ul class="space-y-2">
                            <?php foreach ($repairs as $repair): ?>
                            <li class="text-sm text-green-800 font-mono"><?= htmlspecialchars($repair) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($errors)): ?>
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4 text-red-700">⚠ Errors (might be OK):</h2>
                    <div class="bg-red-50 border border-red-200 rounded p-4">
                        <ul class="space-y-2">
                            <?php foreach ($errors as $error): ?>
                            <li class="text-sm text-red-800"><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="bg-blue-50 border border-blue-200 rounded p-4 mb-6">
                    <p class="text-blue-800"><strong>Success!</strong> Your database has been repaired. You can now use the application normally.</p>
                </div>
                <?php endif; ?>
                
                <div class="space-y-3">
                    <a href="index.php" class="block w-full bg-gradient-to-r from-red-600 to-red-800 text-white text-center py-3 rounded-lg font-bold hover:shadow-lg transition">
                        🏠 Go to Homepage
                    </a>
                    <a href="admin/login.php" class="block w-full bg-gray-800 text-white text-center py-3 rounded-lg font-bold hover:bg-gray-900 transition">
                        🔐 Admin Login
                    </a>
                </div>
                
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-300 rounded">
                    <p class="text-yellow-800 text-sm">
                        <strong>Security Note:</strong> Delete this file (repair-database.php) after repair is complete.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>