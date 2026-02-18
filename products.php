<?php
require_once 'includes/header.php';

$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'active'";

if ($category) {
    $sql .= " AND c.slug = :category";
}
if ($search) {
    $sql .= " AND (p.name LIKE :search OR p.article_code LIKE :search)";
}

$sql .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
if ($category) $stmt->bindValue(':category', $category);
if ($search) $stmt->bindValue(':search', "%$search%");
$stmt->execute();
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories WHERE status = 'active' ORDER BY order_num")->fetchAll();
?>

<div class="bg-gray-100 py-8">
    <div class="container mx-auto px-6">
        <h1 class="text-4xl font-bold text-gray-800">Our Products</h1>
        <p class="text-gray-600 mt-2">Browse our extensive collection of quality footwear</p>
    </div>
</div>

<div class="container mx-auto px-6 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Filters -->
        <div class="lg:w-1/4">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-lg mb-4 text-gray-800">Categories</h3>
                <ul class="space-y-2">
                    <li><a href="products.php" class="text-gray-600 hover:text-orange-600 <?= !$category ? 'font-bold text-orange-600' : '' ?>">All Products</a></li>
                    <?php foreach ($categories as $cat): ?>
                    <li><a href="products.php?category=<?= $cat['slug'] ?>" class="text-gray-600 hover:text-orange-600 <?= $category == $cat['slug'] ? 'font-bold text-orange-600' : '' ?>"><?= htmlspecialchars($cat['name']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
                
                <h3 class="font-bold text-lg mb-4 mt-6 text-gray-800">Search</h3>
                <form method="GET" class="space-y-2">
                    <?php if ($category): ?><input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>"><?php endif; ?>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search products..." class="w-full px-4 py-2 border rounded">
                    <button type="submit" class="w-full bg-orange-600 text-white py-2 rounded hover:bg-orange-700">Search</button>
                </form>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="lg:w-3/4">
            <?php if (empty($products)): ?>
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No products found</p>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>