<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="w-64 bg-gray-900 text-white min-h-screen flex flex-col">
    <div class="p-6 border-b border-gray-800">
        <h2 class="text-2xl font-bold text-red-500">🔥 Admin Panel</h2>
        <p class="text-gray-400 text-sm">Khaitan Footwear</p>
    </div>
    
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <a href="dashboard.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'dashboard.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>📊</span>
            <span>Dashboard</span>
        </a>
        
        <div class="pt-4 pb-2 px-4">
            <p class="text-xs uppercase text-gray-500 font-semibold">Website Content</p>
        </div>
        
        <a href="branding-settings.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'branding-settings.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>🎨</span>
            <span>Branding & Social</span>
        </a>
        
        <a href="banner-settings.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'banner-settings.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>🌅</span>
            <span>Hero Banner</span>
        </a>
        
        <a href="contact-info.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'contact-info.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>📞</span>
            <span>Contact Info</span>
        </a>
        
        <a href="tagline-settings.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'tagline-settings.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>✏️</span>
            <span>Tagline & About</span>
        </a>
        
        <div class="pt-4 pb-2 px-4">
            <p class="text-xs uppercase text-gray-500 font-semibold">Products & Categories</p>
        </div>
        
        <a href="products.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'products.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>📦</span>
            <span>Products</span>
        </a>
        
        <a href="categories.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'categories.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>📂</span>
            <span>Categories</span>
        </a>
        
        <a href="banners.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'banners.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>🖼️</span>
            <span>Banners</span>
        </a>
        
        <div class="pt-4 pb-2 px-4">
            <p class="text-xs uppercase text-gray-500 font-semibold">Inquiries & Users</p>
        </div>
        
        <a href="contacts.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'contacts.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>📧</span>
            <span>Inquiries</span>
        </a>
        
        <a href="users.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'users.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>👥</span>
            <span>Users</span>
        </a>
        
        <div class="pt-4 pb-2 px-4">
            <p class="text-xs uppercase text-gray-500 font-semibold">System</p>
        </div>
        
        <a href="updates.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'updates.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>🔄</span>
            <span>Updates</span>
        </a>
        
        <a href="github-settings.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'github-settings.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>🔐</span>
            <span>GitHub Settings</span>
        </a>
        
        <a href="email-settings.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'email-settings.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>✉️</span>
            <span>Email Config</span>
        </a>
        
        <a href="settings.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= $current_page === 'settings.php' ? 'bg-red-600' : 'hover:bg-gray-800' ?> transition">
            <span>⚙️</span>
            <span>General Settings</span>
        </a>
    </nav>
    
    <div class="p-4 border-t border-gray-800">
        <div class="flex items-center space-x-3 mb-3">
            <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center font-bold">
                <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold truncate"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>
                <p class="text-xs text-gray-400 truncate"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></p>
            </div>
        </div>
        <a href="logout.php" class="flex items-center justify-center space-x-2 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg transition">
            <span>🚪</span>
            <span>Logout</span>
        </a>
    </div>
</aside>