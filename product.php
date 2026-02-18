<?php
require_once 'includes/header.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) {
    header('Location: products.php');
    exit;
}

$stmt = $pdo->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.slug = ? AND p.status = 'active'");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit;
}

$images = $product['images'] ? json_decode($product['images'], true) : [];
?>

<div class="bg-gray-100 py-6">
    <div class="container mx-auto px-6">
        <nav class="text-sm text-gray-600">
            <a href="index.php" class="hover:text-orange-600">Home</a> / 
            <a href="products.php" class="hover:text-orange-600">Products</a> / 
            <a href="products.php?category=<?= $product['category_slug'] ?>" class="hover:text-orange-600"><?= htmlspecialchars($product['category_name']) ?></a> / 
            <span class="text-gray-800"><?= htmlspecialchars($product['name']) ?></span>
        </nav>
    </div>
</div>

<div class="container mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Product Images -->
        <div>
            <div class="bg-white rounded-2xl shadow-lg mb-4 p-4 border border-gray-200">
                <?php if ($product['primary_image']): ?>
                <img src="uploads/products/<?= htmlspecialchars($product['primary_image']) ?>" 
                     alt="<?= htmlspecialchars($product['name']) ?>" 
                     class="w-full h-[500px] object-contain">
                <?php else: ?>
                <div class="w-full h-[500px] flex items-center justify-center bg-gray-100 rounded">
                    <span class="text-gray-400 text-xl">No Image</span>
                </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($images)): ?>
            <div class="grid grid-cols-4 gap-2">
                <?php foreach ($images as $img): ?>
                <div class="bg-white rounded-lg shadow p-2 border border-gray-200">
                    <img src="uploads/products/<?= htmlspecialchars($img) ?>" 
                         class="w-full h-20 object-contain rounded cursor-pointer hover:opacity-75">
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Product Details -->
        <div>
            <span class="text-sm text-orange-600 font-semibold uppercase"><?= htmlspecialchars($product['category_name']) ?></span>
            <h1 class="text-4xl font-bold mt-2 mb-4 text-gray-800"><?= htmlspecialchars($product['article_code']) ?></h1>
            
            <?php if ($product['description']): ?>
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Description</h3>
                <p class="text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            </div>
            <?php endif; ?>
            
            <div class="bg-gradient-to-r from-orange-50 to-red-50 p-6 rounded-2xl mb-6 border border-orange-200">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Product Specifications</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <span class="text-orange-600 font-semibold w-36">Article Code:</span>
                        <span class="font-bold text-gray-800"><?= htmlspecialchars($product['article_code']) ?></span>
                    </div>
                    <?php if ($product['sizes']): ?>
                    <div class="flex items-start">
                        <span class="text-orange-600 font-semibold w-36">Available Sizes:</span>
                        <span class="font-bold text-gray-800"><?= htmlspecialchars($product['sizes']) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($product['colors']): ?>
                    <div class="flex items-start">
                        <span class="text-orange-600 font-semibold w-36">Available Colors:</span>
                        <span class="font-bold text-gray-800"><?= htmlspecialchars($product['colors']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="space-y-3">
                <a href="contact.php" class="block w-full text-center bg-gradient-to-r from-orange-600 to-red-600 text-white py-4 rounded-lg font-bold hover:shadow-xl transition transform hover:-translate-y-1">
                    📞 Inquire Now
                </a>
                <a href="products.php?category=<?= $product['category_slug'] ?>" class="block w-full text-center border-2 border-orange-600 text-orange-600 py-4 rounded-lg font-bold hover:bg-orange-50 transition">
                    👁️ View Similar Products
                </a>
            </div>
            
            <!-- Additional Info -->
            <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>✨ Note:</strong> Product specifications and availability may vary. Please contact us for current stock and detailed information.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Related Products Section -->
    <?php
    $relatedStmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? AND status = 'active' LIMIT 4");
    $relatedStmt->execute([$product['category_id'], $product['id']]);
    $relatedProducts = $relatedStmt->fetchAll();
    ?>
    
    <?php if (!empty($relatedProducts)): ?>
    <div class="mt-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Related Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($relatedProducts as $rp): ?>
            <a href="product.php?slug=<?= $rp['slug'] ?>" class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
                <div class="aspect-square bg-gray-100 flex items-center justify-center p-4">
                    <?php if ($rp['primary_image']): ?>
                    <img src="uploads/products/<?= htmlspecialchars($rp['primary_image']) ?>" 
                         alt="Art. <?= htmlspecialchars($rp['article_code']) ?>" 
                         class="w-full h-full object-contain">
                    <?php else: ?>
                    <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <?php endif; ?>
                </div>
                <div class="p-4">
                    <span class="text-xs font-semibold text-orange-600 uppercase"><?= htmlspecialchars($product['category_name']) ?></span>
                    <h3 class="text-lg font-bold text-gray-800 mt-1">Art. <?= htmlspecialchars($rp['article_code']) ?></h3>
                    <?php if ($rp['sizes']): ?>
                    <p class="text-sm text-gray-600 mt-2">👟 <?= htmlspecialchars($rp['sizes']) ?></p>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>