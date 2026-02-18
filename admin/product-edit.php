<?php
require_once 'includes/header.php';
require_once 'product-upload-handler.php';

$id = $_GET['id'] ?? 0;
$product = null;
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
}

$categories = $pdo->query("SELECT * FROM categories WHERE status = 'active' ORDER BY name")->fetchAll();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $article_code = $_POST['article_code'];
    $description = $_POST['description'];
    $sizes = $_POST['sizes'];
    $colors = $_POST['colors'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $status = $_POST['status'];
    
    // Product name is same as article code
    $name = $article_code;
    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $article_code));
    
    // Handle image upload with automatic resizing
    $primary_image = $product['primary_image'] ?? '';
    if (!empty($_FILES['primary_image']['name'])) {
        $uploaded = handleProductImageUpload($_FILES['primary_image']);
        if ($uploaded) {
            // Delete old image if exists
            if (!empty($product['primary_image']) && file_exists('../uploads/products/' . $product['primary_image'])) {
                unlink('../uploads/products/' . $product['primary_image']);
            }
            $primary_image = $uploaded;
        } else {
            $error = 'Failed to upload image';
        }
    }
    
    if (empty($error)) {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE products SET category_id=?, name=?, slug=?, article_code=?, description=?, sizes=?, colors=?, primary_image=?, is_featured=?, status=? WHERE id=?");
            $stmt->execute([$category_id, $name, $slug, $article_code, $description, $sizes, $colors, $primary_image, $is_featured, $status, $id]);
            $success = '✅ Product updated successfully! Image automatically resized to 800x800 (aspect ratio maintained)';
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (category_id, name, slug, article_code, description, sizes, colors, primary_image, is_featured, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$category_id, $name, $slug, $article_code, $description, $sizes, $colors, $primary_image, $is_featured, $status]);
            $success = '✅ Product created successfully! Image automatically resized to 800x800 (aspect ratio maintained)';
            header('Location: products.php');
            exit;
        }
    }
}
?>

<div class="mb-6">
<a href="products.php" class="text-orange-600 hover:text-orange-700">← Back to Products</a>
<h1 class="text-3xl font-bold text-gray-800 mt-2"><?= $id ? 'Edit' : 'Add' ?> Product</h1>
</div>

<?php if ($success): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow p-6">
<form method="POST" enctype="multipart/form-data" class="space-y-4">
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
<label class="block font-medium mb-2">Article Number / Product Code *</label>
<input type="text" name="article_code" value="<?= htmlspecialchars($product['article_code'] ?? '') ?>" required class="w-full px-4 py-2 border rounded-lg" placeholder="e.g., KH-2024-001">
<p class="text-sm text-gray-600 mt-1">This will be the product's unique identifier</p>
</div>
<div>
<label class="block font-medium mb-2">Category *</label>
<select name="category_id" required class="w-full px-4 py-2 border rounded-lg">
<option value="">Select Category</option>
<?php foreach ($categories as $c): ?>
<option value="<?= $c['id'] ?>" <?= ($product['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
<?php endforeach; ?>
</select>
</div>
</div>

<div>
<label class="block font-medium mb-2">Description</label>
<textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-lg" placeholder="Product details, material, features..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
<label class="block font-medium mb-2">Sizes (comma separated)</label>
<input type="text" name="sizes" value="<?= htmlspecialchars($product['sizes'] ?? '') ?>" placeholder="6, 7, 8, 9, 10" class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block font-medium mb-2">Colors (comma separated)</label>
<input type="text" name="colors" value="<?= htmlspecialchars($product['colors'] ?? '') ?>" placeholder="Black, Brown, White" class="w-full px-4 py-2 border rounded-lg">
</div>
</div>

<div>
<label class="block font-medium mb-2">🖼️ Product Image</label>
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-3">
<p class="text-sm text-blue-800 font-semibold">✨ Smart Image Upload:</p>
<ul class="text-sm text-blue-700 mt-2 space-y-1">
<li>✅ Upload any size image (small or large)</li>
<li>✅ Automatically resized to 800x800 pixels</li>
<li>✅ Maintains aspect ratio (no cropping!)</li>
<li>✅ Optimized for web display</li>
<li>✅ Supports: JPG, PNG, GIF, WebP</li>
</ul>
</div>
<input type="file" name="primary_image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
<?php if (!empty($product['primary_image'])): ?>
<div class="mt-4">
<p class="text-sm font-semibold text-gray-700 mb-2">Current Image:</p>
<img src="../uploads/products/<?= htmlspecialchars($product['primary_image']) ?>" class="w-48 h-48 object-contain border rounded-lg bg-gray-50">
</div>
<?php endif; ?>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
<label class="flex items-center">
<input type="checkbox" name="is_featured" value="1" <?= ($product['is_featured'] ?? 0) ? 'checked' : '' ?> class="mr-2">
<span class="font-medium">Featured Product</span>
</label>
</div>
<div>
<label class="block font-medium mb-2">Status</label>
<select name="status" class="w-full px-4 py-2 border rounded-lg">
<option value="active" <?= ($product['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>Active</option>
<option value="inactive" <?= ($product['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
</select>
</div>
</div>

<button type="submit" class="bg-orange-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-700"><?= $id ? 'Update' : 'Create' ?> Product</button>
</form>
</div>

<?php require_once 'includes/footer.php'; ?>