<?php
if (file_exists('config.php')) {
    require_once 'config.php';
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Database connection failed');
    }
    
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
$site_logo = $settings['site_logo'] ?? '';
$logo_text = $settings['logo_text'] ?? $site_name;
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name) ?> - Leading Footwear Manufacturer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-link { position: relative; }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 3px;
            background: #dc2626;
            transition: width 0.3s;
        }
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="index.php" class="flex items-center space-x-3 hover:opacity-80 transition">
                    <?php if ($site_logo && file_exists('uploads/' . $site_logo)): ?>
                    <img src="uploads/<?= htmlspecialchars($site_logo) ?>" alt="<?= htmlspecialchars($site_name) ?>" class="h-12 object-contain">
                    <?php else: ?>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                            <?= strtoupper(substr($logo_text, 0, 1)) ?>
                        </div>
                        <span class="ml-3 text-2xl font-black text-gray-800">
                            <?= htmlspecialchars($logo_text) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-10">
                    <a href="index.php" class="nav-link <?= $current_page == 'index' ? 'active' : '' ?>">
                        <span class="text-gray-700 hover:text-red-600 transition font-semibold text-lg <?= $current_page == 'index' ? 'text-red-600' : '' ?>">Home</span>
                    </a>
                    <a href="products.php" class="nav-link <?= $current_page == 'products' || $current_page == 'product' ? 'active' : '' ?>">
                        <span class="text-gray-700 hover:text-red-600 transition font-semibold text-lg <?= $current_page == 'products' || $current_page == 'product' ? 'text-red-600' : '' ?>">Products</span>
                    </a>
                    <a href="about.php" class="nav-link <?= $current_page == 'about' ? 'active' : '' ?>">
                        <span class="text-gray-700 hover:text-red-600 transition font-semibold text-lg <?= $current_page == 'about' ? 'text-red-600' : '' ?>">About</span>
                    </a>
                    <a href="contact.php" class="nav-link <?= $current_page == 'contact' ? 'active' : '' ?>">
                        <span class="text-gray-700 hover:text-red-600 transition font-semibold text-lg <?= $current_page == 'contact' ? 'text-red-600' : '' ?>">Contact</span>
                    </a>
                    <a href="admin/login.php" class="bg-gradient-to-r from-red-600 to-red-800 text-white px-6 py-2 rounded-full font-bold hover:shadow-xl transform hover:scale-105 transition">
                        Admin
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-gray-700 border-2 border-red-600 p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 space-y-2 bg-gray-50 rounded-xl p-4 shadow-lg">
                <a href="index.php" class="block py-3 px-4 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition font-semibold">Home</a>
                <a href="products.php" class="block py-3 px-4 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition font-semibold">Products</a>
                <a href="about.php" class="block py-3 px-4 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition font-semibold">About</a>
                <a href="contact.php" class="block py-3 px-4 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition font-semibold">Contact</a>
                <a href="admin/login.php" class="block py-3 px-4 bg-red-600 text-white rounded-lg font-bold text-center hover:bg-red-700 transition">Admin Login</a>
            </div>
        </nav>
    </header>
    
    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>