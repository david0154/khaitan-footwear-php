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
$site_logo = $settings['site_logo'] ?? '';
$logo_text = $settings['logo_text'] ?? $site_name;
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">
    <!-- Header -->
    <header class="glass sticky top-0 z-50 border-b border-white border-opacity-10">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="index.php" class="flex items-center space-x-3 logo-glow">
                    <?php if ($site_logo && file_exists('uploads/' . $site_logo)): ?>
                    <img src="uploads/<?= htmlspecialchars($site_logo) ?>" alt="<?= htmlspecialchars($site_name) ?>" class="h-12 object-contain">
                    <?php else: ?>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center text-white text-2xl font-bold mr-3">
                            <?= strtoupper(substr($logo_text, 0, 1)) ?>
                        </div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-orange-500 to-red-500 text-transparent bg-clip-text neon-orange">
                            <?= htmlspecialchars($logo_text) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="nav-link <?= $current_page == 'index' ? 'active' : '' ?>">
                        <span class="text-white hover:text-orange-500 transition font-medium <?= $current_page == 'index' ? 'text-orange-500' : '' ?>">Home</span>
                    </a>
                    <a href="products.php" class="nav-link <?= $current_page == 'products' || $current_page == 'product' ? 'active' : '' ?>">
                        <span class="text-white hover:text-orange-500 transition font-medium <?= $current_page == 'products' || $current_page == 'product' ? 'text-orange-500' : '' ?>">Products</span>
                    </a>
                    <a href="about.php" class="nav-link <?= $current_page == 'about' ? 'active' : '' ?>">
                        <span class="text-white hover:text-orange-500 transition font-medium <?= $current_page == 'about' ? 'text-orange-500' : '' ?>">About</span>
                    </a>
                    <a href="contact.php" class="nav-link <?= $current_page == 'contact' ? 'active' : '' ?>">
                        <span class="text-white hover:text-orange-500 transition font-medium <?= $current_page == 'contact' ? 'text-orange-500' : '' ?>">Contact</span>
                    </a>
                    <a href="admin/login.php" class="btn-glow px-6 py-2 text-sm">
                        Admin
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-white neon-border p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 space-y-2 glass-strong rounded-xl p-4">
                <a href="index.php" class="block py-3 px-4 text-white hover:bg-orange-500 hover:bg-opacity-20 rounded-lg transition">Home</a>
                <a href="products.php" class="block py-3 px-4 text-white hover:bg-orange-500 hover:bg-opacity-20 rounded-lg transition">Products</a>
                <a href="about.php" class="block py-3 px-4 text-white hover:bg-orange-500 hover:bg-opacity-20 rounded-lg transition">About</a>
                <a href="contact.php" class="block py-3 px-4 text-white hover:bg-orange-500 hover:bg-opacity-20 rounded-lg transition">Contact</a>
                <a href="admin/login.php" class="block py-3 px-4 text-orange-500 font-semibold bg-orange-500 bg-opacity-10 rounded-lg">Admin Login</a>
            </div>
        </nav>
    </header>
    
    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
    <script src="assets/js/main.js"></script>