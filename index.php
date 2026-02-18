<?php
require_once 'includes/header.php';

// Get banners
$banners = $pdo->query("SELECT * FROM banners WHERE status = 'active' ORDER BY order_num LIMIT 1")->fetchAll();

// Get categories
$categories = $pdo->query("SELECT * FROM categories WHERE status = 'active' ORDER BY order_num")->fetchAll();

// Get featured products
$products = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_featured = 1 AND p.status = 'active' LIMIT 8")->fetchAll();
?>

<!-- Hero Banner -->
<?php if (!empty($banners)): $banner = $banners[0]; ?>
<section class="relative bg-gradient-to-r from-orange-600 to-red-600 text-white py-20">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-5xl font-bold mb-4"><?= htmlspecialchars($banner['title']) ?></h1>
        <p class="text-xl mb-8"><?= htmlspecialchars($banner['subtitle']) ?></p>
        <?php if ($banner['button_text']): ?>
        <a href="<?= htmlspecialchars($banner['button_link']) ?>" class="bg-white text-orange-600 px-8 py-3 rounded-lg text-lg font-bold hover:bg-gray-100 inline-block">
            <?= htmlspecialchars($banner['button_text']) ?>
        </a>
        <?php endif; ?>
    </div>
</section>
<?php else: ?>
<section class="relative bg-gradient-to-r from-orange-600 to-red-600 text-white py-20">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-5xl font-bold mb-4">Welcome to Khaitan Footwear</h1>
        <p class="text-xl mb-8">Leading Manufacturer & Supplier of Quality Footwear</p>
        <a href="products.php" class="bg-white text-orange-600 px-8 py-3 rounded-lg text-lg font-bold hover:bg-gray-100 inline-block">
            View Products
        </a>
    </div>
</section>
<?php endif; ?>

<!-- Categories -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Shop by Category</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($categories as $cat): ?>
            <a href="products.php?category=<?= $cat['slug'] ?>" class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800"><?= htmlspecialchars($cat['name']) ?></h3>
                    <p class="text-gray-600"><?= htmlspecialchars($cat['description']) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<?php if (!empty($products)): ?>
<section class="py-16">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Featured Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($products as $p): ?>
            <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition">
                <?php if ($p['primary_image']): ?>
                <img src="uploads/products/<?= htmlspecialchars($p['primary_image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="w-full h-48 object-cover">
                <?php else: ?>
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400">No Image</span>
                </div>
                <?php endif; ?>
                <div class="p-4">
                    <span class="text-sm text-orange-600 font-semibold"><?= htmlspecialchars($p['category_name']) ?></span>
                    <h3 class="font-bold text-lg mb-2 text-gray-800"><?= htmlspecialchars($p['name']) ?></h3>
                    <p class="text-gray-600 text-sm mb-2">Article: <?= htmlspecialchars($p['article_code']) ?></p>
                    <a href="product.php?slug=<?= $p['slug'] ?>" class="text-orange-600 font-semibold hover:text-orange-700">View Details →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-8">
            <a href="products.php" class="bg-orange-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-orange-700 inline-block">View All Products</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="bg-gradient-to-r from-orange-600 to-red-600 text-white py-16">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Order?</h2>
        <p class="text-xl mb-8">Contact us today for bulk orders and inquiries</p>
        <a href="contact.php" class="bg-white text-orange-600 px-8 py-3 rounded-lg text-lg font-bold hover:bg-gray-100 inline-block">Contact Us</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>