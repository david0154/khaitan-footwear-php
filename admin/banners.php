<?php
require_once 'includes/header.php';

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $pdo->prepare("DELETE FROM banners WHERE id = ?")->execute([$_POST['delete_id']]);
        $success = 'Banner deleted';
    } else {
        $stmt = $pdo->prepare("INSERT INTO banners (title, subtitle, button_text, button_link, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['title'], $_POST['subtitle'], $_POST['button_text'], $_POST['button_link'], $_POST['status']]);
        $success = 'Banner created';
    }
}

$banners = $pdo->query("SELECT * FROM banners ORDER BY order_num, created_at DESC")->fetchAll();
?>

<div class="flex justify-between items-center mb-6">
<h1 class="text-3xl font-bold text-gray-800">Hero Banners</h1>
<button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-orange-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-700">+ Add Banner</button>
</div>

<?php if ($success): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="grid grid-cols-1 gap-6">
<?php foreach ($banners as $b): ?>
<div class="bg-white rounded-lg shadow p-6">
<div class="flex justify-between items-start">
<div>
<h3 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($b['title']) ?></h3>
<p class="text-gray-600 mb-2"><?= htmlspecialchars($b['subtitle']) ?></p>
<?php if ($b['button_text']): ?>
<p class="text-sm text-gray-500">Button: <?= htmlspecialchars($b['button_text']) ?> → <?= htmlspecialchars($b['button_link']) ?></p>
<?php endif; ?>
</div>
<div class="flex items-center space-x-2">
<span class="px-2 py-1 text-xs font-semibold rounded-full <?= $b['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>"><?= ucfirst($b['status']) ?></span>
<form method="POST" onsubmit="return confirm('Delete this banner?')">
<input type="hidden" name="delete_id" value="<?= $b['id'] ?>">
<button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
</form>
</div>
</div>
</div>
<?php endforeach; ?>
</div>

<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
<div class="bg-white rounded-lg p-8 max-w-md w-full">
<h2 class="text-2xl font-bold mb-4">Add Banner</h2>
<form method="POST" class="space-y-4">
<div>
<label class="block font-medium mb-2">Title *</label>
<input type="text" name="title" required class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block font-medium mb-2">Subtitle</label>
<input type="text" name="subtitle" class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block font-medium mb-2">Button Text</label>
<input type="text" name="button_text" placeholder="View Products" class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block font-medium mb-2">Button Link</label>
<input type="text" name="button_link" placeholder="products.php" class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block font-medium mb-2">Status</label>
<select name="status" class="w-full px-4 py-2 border rounded-lg">
<option value="active">Active</option>
<option value="inactive">Inactive</option>
</select>
</div>
<div class="flex space-x-2">
<button type="submit" class="flex-1 bg-orange-600 text-white py-3 rounded-lg font-bold hover:bg-orange-700">Create</button>
<button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex-1 bg-gray-300 text-gray-800 py-3 rounded-lg font-bold hover:bg-gray-400">Cancel</button>
</div>
</form>
</div>
</div>

<?php require_once 'includes/footer.php'; ?>