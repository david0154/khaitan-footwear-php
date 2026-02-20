<?php
if (!isset($pdo)) {
    require_once 'config.php';
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
}

// Load settings
$stmt = $pdo->query("SELECT * FROM settings");
$settings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $settings[$row['key']] = $row['value'];
}

$site_logo = $settings['site_logo'] ?? '';
$site_favicon = $settings['site_favicon'] ?? '';
$site_name = $settings['site_name'] ?? 'Khaitan Footwear';
$site_tagline = $settings['site_tagline'] ?? '';
$show_social = !empty($settings['show_social_media']);

// Split site name into first word and rest
$name_parts = explode(' ', $site_name, 2);
$first_word = $name_parts[0];
$rest_words = isset($name_parts[1]) ? ' ' . $name_parts[1] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name) ?><?= $site_tagline ? ' - ' . htmlspecialchars($site_tagline) : '' ?></title>
    <?php if ($site_favicon): ?>
    <link rel="icon" type="image/x-icon" href="/uploads/<?= htmlspecialchars($site_favicon) ?>">
    <link rel="shortcut icon" type="image/x-icon" href="/uploads/<?= htmlspecialchars($site_favicon) ?>">
    <?php endif; ?>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/css/featured-blink.css">
    
    <style>
        .khaitan-svg-logo {
            height: 28px;
            width: auto;
            display: inline-block;
            vertical-align: middle;
        }
    </style>
</head>
<body class="bg-gray-50">
<!-- Top Bar -->
<div class="bg-gradient-to-r from-red-600 to-red-700 text-white py-2">
    <div class="max-w-7xl mx-auto px-4 flex flex-wrap justify-between items-center text-sm">
        <div class="flex items-center space-x-4">
            <span>📞 <?= htmlspecialchars($settings['site_phone'] ?? '+91 98765 43210') ?></span>
            <span>✉️ <?= htmlspecialchars($settings['site_email'] ?? 'info@khaitanfootwear.in') ?></span>
        </div>
        
        <?php if ($show_social): ?>
        <div class="flex items-center space-x-3">
            <?php if (!empty($settings['facebook_url'])): ?>
            <a href="<?= htmlspecialchars($settings['facebook_url']) ?>" target="_blank" class="hover:text-red-200 transition" title="Facebook">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <?php endif; ?>
            
            <?php if (!empty($settings['instagram_url'])): ?>
            <a href="<?= htmlspecialchars($settings['instagram_url']) ?>" target="_blank" class="hover:text-red-200 transition" title="Instagram">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            </a>
            <?php endif; ?>
            
            <?php if (!empty($settings['twitter_url'])): ?>
            <a href="<?= htmlspecialchars($settings['twitter_url']) ?>" target="_blank" class="hover:text-red-200 transition" title="Twitter / X">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <?php endif; ?>
            
            <?php if (!empty($settings['linkedin_url'])): ?>
            <a href="<?= htmlspecialchars($settings['linkedin_url']) ?>" target="_blank" class="hover:text-red-200 transition" title="LinkedIn">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            </a>
            <?php endif; ?>
            
            <?php if (!empty($settings['youtube_url'])): ?>
            <a href="<?= htmlspecialchars($settings['youtube_url']) ?>" target="_blank" class="hover:text-red-200 transition" title="YouTube">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            </a>
            <?php endif; ?>
            
            <?php if (!empty($settings['whatsapp_number'])): ?>
            <a href="https://wa.me/<?= htmlspecialchars($settings['whatsapp_number']) ?>" target="_blank" class="hover:text-red-200 transition" title="WhatsApp">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Navigation -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-3">
            <!-- Logo + Company Name + Tagline -->
            <div class="flex items-center">
                <a href="index.php" class="flex items-center space-x-3">
                    <?php if ($site_logo): ?>
                    <!-- Logo Image -->
                    <img src="/uploads/<?= htmlspecialchars($site_logo) ?>" alt="<?= htmlspecialchars($site_name) ?> Logo" class="h-14 w-auto object-contain">
                    <?php endif; ?>
                    
                    <!-- Company Name + Tagline (SVG Logo + Text) -->
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold text-red-600 leading-tight flex items-center gap-1">
                            <img src="/assets/khaitan.svg" alt="<?= htmlspecialchars($first_word) ?>" class="khaitan-svg-logo"><?= htmlspecialchars($rest_words) ?>
                        </span>
                        <?php if ($site_tagline): ?>
                        <span class="text-xs text-gray-600 italic uppercase tracking-wider"><?= htmlspecialchars($site_tagline) ?></span>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
            
            <!-- Desktop Navigation Menu -->
            <div class="hidden md:flex space-x-8">
                <a href="index.php" class="text-gray-700 hover:text-red-600 font-medium transition">Home</a>
                <a href="about.php" class="text-gray-700 hover:text-red-600 font-medium transition">About</a>
                <a href="products.php" class="text-gray-700 hover:text-red-600 font-medium transition">Products</a>
                <a href="contact.php" class="text-gray-700 hover:text-red-600 font-medium transition">Contact</a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button class="md:hidden p-2 rounded-lg hover:bg-gray-100" onclick="toggleMobileMenu()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden pb-4">
            <a href="index.php" class="block py-2 px-4 hover:bg-gray-100 rounded">Home</a>
            <a href="about.php" class="block py-2 px-4 hover:bg-gray-100 rounded">About</a>
            <a href="products.php" class="block py-2 px-4 hover:bg-gray-100 rounded">Products</a>
            <a href="contact.php" class="block py-2 px-4 hover:bg-gray-100 rounded">Contact</a>
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('hidden');
}
</script>