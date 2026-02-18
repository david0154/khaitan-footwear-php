<?php
require_once 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$_POST['delete_id']]);
        $success = 'Product deleted successfully';
    }
}

$products = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC")->fetchAll();
?>

<div class="flex justify-between items-center mb-6">
<h1 class="text-3xl font-bold text-gray-800">Products</h1>
<a href="product-edit.php" class="bg-orange-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-700">+ Add Product</a>
</div>

<?php if ($success): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow overflow-hidden">
<table class="w-full">
<thead class="bg-gray-50">
<tr>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Article Code</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-200">
<?php foreach ($products as $p): ?>
<tr>
<td class="px-6 py-4">
<?php if ($p['primary_image']): ?>
<img src="../uploads/products/<?= htmlspecialchars($p['primary_image']) ?>" class="w-12 h-12 object-cover rounded">
<?php else: ?>
<div class="w-12 h-12 bg-gray-200 rounded"></div>
<?php endif; ?>
</td>
<td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($p['name']) ?></td>
<td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($p['article_code']) ?></td>
<td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($p['category_name']) ?></td>
<td class="px-6 py-4">
<span class="px-2 py-1 text-xs font-semibold rounded-full <?= $p['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>"><?= ucfirst($p['status']) ?></span>
</td>
<td class="px-6 py-4">
<a href="product-edit.php?id=<?= $p['id'] ?>" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
<form method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
<input type="hidden" name="delete_id" value="<?= $p['id'] ?>">
<button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<?php require_once 'includes/footer.php'; ?>