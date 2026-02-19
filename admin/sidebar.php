<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="bg-gray-800 text-white w-64 min-h-screen">
    <div class="p-6">
        <h2 class="text-2xl font-bold">Admin Panel</h2>
        <p class="text-gray-400 text-sm">Khaitan Footwear</p>
    </div>
    
    <nav class="px-4 space-y-2">
        <a href="index.php" class="<?= $current_page == 'index.php' ? 'bg-red-600' : 'hover:bg-gray-700' ?> block px-4 py-3 rounded transition">
            🏠 Dashboard
        </a>
        
        <div class="pt-4 pb-2 text-gray-400 text-xs font-semibold uppercase">Content</div>
        
        <a href="hero-banner.php" class="<?= $current_page == 'hero-banner.php' ? 'bg-red-600' : 'hover:bg-gray-700' ?> block px-4 py-3 rounded transition">
            🎨 Hero Banner
        </a>
        
        <a href="categories.php" class="<?= $current_page == 'categories.php' ? 'bg-red-600' : 'hover:bg-gray-700' ?> block px-4 py-3 rounded transition">
            📁 Categories
        </a>
        
        <a href="products.php" class="<?= $current_page == 'products.php' ? 'bg-red-600' : 'hover:bg-gray-700' ?> block px-4 py-3 rounded transition">
            👟 Products
        </a>
        
        <a href="product-upload.php" class="<?= $current_page == 'product-upload.php' ? 'bg-red-600' : 'hover:bg-gray-700' ?> block px-4 py-3 rounded transition">
            📤 Upload Products
        </a>
        
        <div class="pt-4 pb-2 text-gray-400 text-xs font-semibold uppercase">Settings</div>
        
        <a href="branding-settings.php" class="<?= $current_page == 'branding-settings.php' ? 'bg-red-600' : 'hover:bg-gray-700' ?> block px-4 py-3 rounded transition">
            🎨 Branding & Social
        </a>
        
        <a href="contact-info.php" class="<?= $current_page == 'contact-info.php' ? 'bg-red-600' : 'hover:bg-gray-700' ?> block px-4 py-3 rounded transition">
            📞 Contact Info
        </a>
        
        <a href="github-settings.php" class="<?= $current_page == 'github-settings.php' ? 'bg-red-600' : 'hover:bg-gray-700' ?> block px-4 py-3 rounded transition">
            🔧 GitHub Settings
        </a>
        
        <a href="updates.php" class="<?= $current_page == 'updates.php' ? 'bg-red-600' : 'hover:bg-gray-700' ?> block px-4 py-3 rounded transition">
            🔄 Updates
        </a>
        
        <div class="pt-4 pb-2 text-gray-400 text-xs font-semibold uppercase">Account</div>
        
        <a href="logout.php" class="hover:bg-red-600 block px-4 py-3 rounded transition">
            🚪 Logout
        </a>
    </nav>
</aside>