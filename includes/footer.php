    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-auto">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 text-orange-400"><?= htmlspecialchars($site_name) ?></h3>
                    <p class="text-gray-400">Leading manufacturer and supplier of quality footwear.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="text-gray-400 hover:text-orange-400">Home</a></li>
                        <li><a href="products.php" class="text-gray-400 hover:text-orange-400">Products</a></li>
                        <li><a href="about.php" class="text-gray-400 hover:text-orange-400">About Us</a></li>
                        <li><a href="contact.php" class="text-gray-400 hover:text-orange-400">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Categories</h4>
                    <ul class="space-y-2">
                        <li><a href="products.php?category=mens-collection" class="text-gray-400 hover:text-orange-400">Men's Collection</a></li>
                        <li><a href="products.php?category=womens-collection" class="text-gray-400 hover:text-orange-400">Women's Collection</a></li>
                        <li><a href="products.php?category=kids-collection" class="text-gray-400 hover:text-orange-400">Kids Collection</a></li>
                        <li><a href="products.php?category=sports-shoes" class="text-gray-400 hover:text-orange-400">Sports Shoes</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Contact Info</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><?= htmlspecialchars($settings['site_email'] ?? 'info@khaitanfootwear.in') ?></li>
                        <li><?= htmlspecialchars($settings['site_phone'] ?? '+91 98765 43210') ?></li>
                        <li>New Delhi, India</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($site_name) ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>