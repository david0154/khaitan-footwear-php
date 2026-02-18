<?php
require_once 'includes/header.php';

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$_POST['delete_id']]);
        $success = 'Category deleted';
    } elseif (isset($_POST['edit_id'])) {
        $stmt = $pdo->prepare("UPDATE categories SET name=?, slug=?, description=?, status=? WHERE id=?");
        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $_POST['name']));
        $stmt->execute([$_POST['name'], $slug, $_POST['description'], $_POST['status'], $_POST['edit_id']]);
        $success = 'Category updated';
    } else {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $_POST['name']));
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description, status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $slug, $_POST['description'], $_POST['status']]);
        $success = 'Category created';
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY order_num, name")->fetchAll();
?>

<div class="flex justify-between items-center mb-6">
<h1 class="text-3xl font-bold text-gray-800">Categories</h1>
<button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-orange-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-700">+ Add Category</button>
</div>

<?php if ($success): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<?php foreach ($categories as $c): ?>
<div class="bg-white rounded-lg shadow p-6">
<div class="flex justify-between items-start mb-4">
<h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($c['name']) ?></h3>
<span class="px-2 py-1 text-xs font-semibold rounded-full <?= $c['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>"><?= ucfirst($c['status']) ?></span>
</div>
<p class="text-gray-600 mb-4"><?= htmlspecialchars($c['description']) ?></p>
<div class="flex space-x-2">
<button onclick="editCategory(<?= htmlspecialchars(json_encode($c)) ?>)" class="flex-1 bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Edit</button>
<form method="POST" class="flex-1" onsubmit="return confirm('Delete this category?')">
<input type="hidden" name="delete_id" value="<?= $c['id'] ?>">
<button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">Delete</button>
</form>
</div>
</div>
<?php endforeach; ?>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
<div class="bg-white rounded-lg p-8 max-w-md w-full">
<h2 class="text-2xl font-bold mb-4">Add Category</h2>
<form method="POST" class="space-y-4">
<div>
<label class="block font-medium mb-2">Name *</label>
<input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block font-medium mb-2">Description</label>
<textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg"></textarea>
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

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
<div class="bg-white rounded-lg p-8 max-w-md w-full">
<h2 class="text-2xl font-bold mb-4">Edit Category</h2>
<form method="POST" class="space-y-4">
<input type="hidden" name="edit_id" id="edit_id">
<div>
<label class="block font-medium mb-2">Name *</label>
<input type="text" name="name" id="edit_name" required class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block font-medium mb-2">Description</label>
<textarea name="description" id="edit_description" rows="3" class="w-full px-4 py-2 border rounded-lg"></textarea>
</div>
<div>
<label class="block font-medium mb-2">Status</label>
<select name="status" id="edit_status" class="w-full px-4 py-2 border rounded-lg">
<option value="active">Active</option>
<option value="inactive">Inactive</option>
</select>
</div>
<div class="flex space-x-2">
<button type="submit" class="flex-1 bg-orange-600 text-white py-3 rounded-lg font-bold hover:bg-orange-700">Update</button>
<button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="flex-1 bg-gray-300 text-gray-800 py-3 rounded-lg font-bold hover:bg-gray-400">Cancel</button>
</div>
</form>
</div>
</div>

<script>
function editCategory(cat) {
    document.getElementById('edit_id').value = cat.id;
    document.getElementById('edit_name').value = cat.name;
    document.getElementById('edit_description').value = cat.description;
    document.getElementById('edit_status').value = cat.status;
    document.getElementById('editModal').classList.remove('hidden');
}
</script>

<?php require_once 'includes/footer.php'; ?>