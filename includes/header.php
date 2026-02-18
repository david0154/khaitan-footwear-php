<?php
if (file_exists('config.php')) {
    require_once 'config.php';
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Database connection failed');
    }
    
    // Get settings
    $settings = [];
    $stmt = $pdo->query("SELECT * FROM settings");
    while ($row = $stmt->fetch()) {
        $settings[$row['key']] = $row['value'];
    }
} else {
    if (basename($_SERVER['PHP_SELF']) !== 'install.php') {
        header('Location: install.php');
        exit;
    }
}

$site_name = $settings['site_name'] ?? 'Khaitan Footwear';
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white">
    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="text-2xl font-bold text-orange-600">
                    <?= htmlspecialchars($site_name) ?>
                </a>
                <div class="hidden md:flex space-x-8">
                    <a href="index.php" class="<?= $current_page == 'index' ? 'text-orange-600 font-semibold' : 'text-gray-700' ?> hover:text-orange-600 transition">Home</a>
                    <a href="products.php" class="<?= $current_page == 'products' || $current_page == 'product' ? 'text-orange-600 font-semibold' : 'text-gray-700' ?> hover:text-orange-600 transition">Products</a>
                    <a href="about.php" class="<?= $current_page == 'about' ? 'text-orange-600 font-semibold' : 'text-gray-700' ?> hover:text-orange-600 transition">About</a>
                    <a href="contact.php" class="<?= $current_page == 'contact' ? 'text-orange-600 font-semibold' : 'text-gray-700' ?> hover:text-orange-600 transition">Contact</a>
                    <a href="admin/login.php" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">Admin Login</a>
                </div>
                <button id="mobile-menu-btn" class="md:hidden text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 space-y-2">
                <a href="index.php" class="block py-2 text-gray-700 hover:text-orange-600">Home</a>
                <a href="products.php" class="block py-2 text-gray-700 hover:text-orange-600">Products</a>
                <a href="about.php" class="block py-2 text-gray-700 hover:text-orange-600">About</a>
                <a href="contact.php" class="block py-2 text-gray-700 hover:text-orange-600">Contact</a>
                <a href="admin/login.php" class="block py-2 text-orange-600 font-semibold">Admin Login</a>
            </div>
        </nav>
    </header>
    
    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>