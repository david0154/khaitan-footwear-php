<?php
require_once 'includes/header.php';

$category_slug = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'active'";
$params = [];

if ($category_slug) {
    $sql .= " AND c.slug = ?";
    $params[] = $category_slug;
}

if ($search) {
    $sql .= " AND (p.article_code LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY p.is_featured DESC, p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories WHERE status = 'active' ORDER BY name")->fetchAll();
?>

<!-- Page Header -->
<section class="bg-gradient-to-r from-red-600 to-red-800 text-white py-16">
    <div class="container mx-auto px-6">
        <h1 class="text-5xl font-bold mb-4">Our Products</h1>
        <p class="text-xl text-red-100">Browse our complete collection of quality footwear</p>
    </div>
</section>

<!-- Filters & Search -->
<section class="bg-white shadow-md py-6">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <!-- Search -->
            <form method="GET" class="flex-1">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="🔍 Search by article code or description..." class="w-full px-4 py-3 border rounded-lg">
            </form>
            
            <!-- Category Filter -->
            <select onchange="location.href='products.php?category='+this.value" class="px-4 py-3 border rounded-lg">
                <option value="">All Categories</option>
                <?php foreach ($categories as $c): ?>
                <option value="<?= htmlspecialchars($c['slug']) ?>" <?= $category_slug == $c['slug'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-6">
        <?php if (empty($products)): ?>
        <div class="text-center py-20">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">No products found</h3>
            <p class="text-gray-600 mb-6">Try adjusting your search or filters</p>
            <a href="products.php" class="inline-block bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition">View All Products</a>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($products as $p): ?>
            <div class="<?= $p['is_featured'] ? 'featured-product' : '' ?> bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
                <div class="aspect-square bg-gray-100 overflow-hidden flex items-center justify-center">
                    <?php if ($p['primary_image']): ?>
                    <img src="uploads/products/<?= htmlspecialchars($p['primary_image']) ?>" alt="Art. <?= htmlspecialchars($p['article_code']) ?>" class="w-full h-full object-contain group-hover:scale-110 transition duration-500">
                    <?php else: ?>
                    <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <?php if ($p['category_name']): ?>
                    <span class="text-sm font-semibold text-red-600 uppercase"><?= htmlspecialchars($p['category_name']) ?></span>
                    <?php endif; ?>
                    <h3 class="text-xl font-bold text-gray-800 mt-2 mb-2">Art. <?= htmlspecialchars($p['article_code']) ?></h3>
                    <?php if ($p['description']): ?>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?= htmlspecialchars($p['description']) ?></p>
                    <?php endif; ?>
                    <div class="flex gap-2 mb-4">
                        <?php if ($p['sizes']): ?>
                        <span class="text-xs bg-gray-100 px-2 py-1 rounded">👟 <?= htmlspecialchars($p['sizes']) ?></span>
                        <?php endif; ?>
                        <?php if ($p['colors']): ?>
                        <span class="text-xs bg-gray-100 px-2 py-1 rounded">🎨 <?= htmlspecialchars($p['colors']) ?></span>
                        <?php endif; ?>
                    </div>
                    <a href="product.php?slug=<?= $p['slug'] ?>" class="inline-block bg-red-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-red-700 transition">
                        View Details
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>