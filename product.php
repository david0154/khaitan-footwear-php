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
            <div class="bg-gray-200 rounded-lg mb-4 overflow-hidden">
                <?php if ($product['primary_image']): ?>
                <img src="uploads/products/<?= htmlspecialchars($product['primary_image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-96 object-cover">
                <?php else: ?>
                <div class="w-full h-96 flex items-center justify-center">
                    <span class="text-gray-400 text-xl">No Image</span>
                </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($images)): ?>
            <div class="grid grid-cols-4 gap-2">
                <?php foreach ($images as $img): ?>
                <img src="uploads/products/<?= htmlspecialchars($img) ?>" class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Product Details -->
        <div>
            <span class="text-sm text-orange-600 font-semibold"><?= htmlspecialchars($product['category_name']) ?></span>
            <h1 class="text-3xl font-bold mt-2 mb-4 text-gray-800"><?= htmlspecialchars($product['name']) ?></h1>
            <p class="text-gray-600 mb-6"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Article Code</p>
                        <p class="font-bold text-lg text-gray-800"><?= htmlspecialchars($product['article_code']) ?></p>
                    </div>
                    <?php if ($product['sizes']): ?>
                    <div>
                        <p class="text-gray-600 text-sm">Available Sizes</p>
                        <p class="font-bold text-gray-800"><?= htmlspecialchars($product['sizes']) ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if ($product['colors']): ?>
                    <div>
                        <p class="text-gray-600 text-sm">Available Colors</p>
                        <p class="font-bold text-gray-800"><?= htmlspecialchars($product['colors']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="space-y-3">
                <a href="contact.php" class="block w-full text-center bg-orange-600 text-white py-4 rounded-lg font-bold hover:bg-orange-700 transition">Inquire Now</a>
                <a href="products.php?category=<?= $product['category_slug'] ?>" class="block w-full text-center border-2 border-orange-600 text-orange-600 py-4 rounded-lg font-bold hover:bg-orange-50 transition">View Similar Products</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>