<?php
if (!isset($pdo)) {
    require_once 'config.php';
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
}

if (!isset($settings)) {
    $stmt = $pdo->query("SELECT * FROM settings");
    $settings = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $settings[$row['key']] = $row['value'];
    }
}

$site_name = $settings['site_name'] ?? 'Khaitan Footwear';
$show_social = !empty($settings['show_social_media']);
?>

<!-- Footer -->
<footer class="bg-gray-900 text-white pt-12 pb-6">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- Company Info -->
            <div>
                <h3 class="text-xl font-bold mb-4 text-red-500"><?= htmlspecialchars($site_name) ?></h3>
                <?php if (!empty($settings['site_tagline'])): ?>
                <p class="text-gray-400 mb-4"><?= htmlspecialchars($settings['site_tagline']) ?></p>
                <?php endif; ?>
                <p class="text-gray-400">Quality footwear for every occasion.</p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-bold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="index.php" class="text-gray-400 hover:text-red-500 transition">Home</a></li>
                    <li><a href="about.php" class="text-gray-400 hover:text-red-500 transition">About Us</a></li>
                    <li><a href="products.php" class="text-gray-400 hover:text-red-500 transition">Products</a></li>
                    <li><a href="contact.php" class="text-gray-400 hover:text-red-500 transition">Contact</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-bold mb-4">📞 Contact Us</h4>
                <ul class="space-y-3 text-gray-400">
                    <?php if (!empty($settings['site_phone'])): ?>
                    <li><a href="tel:<?= htmlspecialchars($settings['site_phone']) ?>" class="hover:text-red-500"><?= htmlspecialchars($settings['site_phone']) ?></a></li>
                    <?php endif; ?>
                    <?php if (!empty($settings['site_email'])): ?>
                    <li><a href="mailto:<?= htmlspecialchars($settings['site_email']) ?>" class="hover:text-red-500 break-all"><?= htmlspecialchars($settings['site_email']) ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <!-- Address & Social -->
            <div>
                <h4 class="text-lg font-bold mb-4">📍 Location</h4>
                <?php if (!empty($settings['site_address'])): ?>
                <address class="text-gray-400 not-italic mb-4">
                    <?= htmlspecialchars($settings['site_address']) ?><br>
                    <?php if (!empty($settings['site_city'])): ?>
                    <?= htmlspecialchars($settings['site_city']) ?>, <?= htmlspecialchars($settings['site_state'] ?? '') ?> - <?= htmlspecialchars($settings['site_pincode'] ?? '') ?><br>
                    <?php endif; ?>
                    <?= htmlspecialchars($settings['site_country'] ?? 'India') ?>
                </address>
                <?php endif; ?>
                
                <?php if ($show_social): ?>
                <div class="mt-4">
                    <h4 class="text-sm font-bold mb-2">🔗 Follow Us</h4>
                    <div class="flex gap-2">
                        <?php if (!empty($settings['facebook_url'])): ?>
                        <a href="<?= htmlspecialchars($settings['facebook_url']) ?>" target="_blank" class="w-8 h-8 bg-gray-800 hover:bg-blue-600 rounded-full flex items-center justify-center transition"><span>🔵</span></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['instagram_url'])): ?>
                        <a href="<?= htmlspecialchars($settings['instagram_url']) ?>" target="_blank" class="w-8 h-8 bg-gray-800 hover:bg-pink-600 rounded-full flex items-center justify-center transition"><span>🟣</span></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['whatsapp_number'])): ?>
                        <a href="https://wa.me/<?= htmlspecialchars($settings['whatsapp_number']) ?>" target="_blank" class="w-8 h-8 bg-gray-800 hover:bg-green-600 rounded-full flex items-center justify-center transition"><span>🟢</span></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="border-t border-gray-800 pt-6 text-center text-gray-400">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($site_name) ?>. All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>