<?php
require_once 'includes/header.php';

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$_POST['delete_id']]);
        $success = 'User deleted';
    } else {
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $_POST['email'], $hash, $_POST['role'], $_POST['status']]);
        $success = 'User created';
    }
}

$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>

<div class="flex justify-between items-center mb-6">
<h1 class="text-3xl font-bold text-gray-800">Users</h1>
<button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-orange-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-700">+ Add User</button>
</div>

<?php if ($success): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow overflow-hidden">
<table class="w-full">
<thead class="bg-gray-50">
<tr>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-200">
<?php foreach ($users as $u): ?>
<tr>
<td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($u['name']) ?></td>
<td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($u['email']) ?></td>
<td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800"><?= ucfirst($u['role']) ?></span></td>
<td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full <?= $u['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>"><?= ucfirst($u['status']) ?></span></td>
<td class="px-6 py-4">
<?php if ($u['id'] != $_SESSION['admin_id']): ?>
<form method="POST" class="inline" onsubmit="return confirm('Delete this user?')">
<input type="hidden" name="delete_id" value="<?= $u['id'] ?>">
<button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
</form>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
<div class="bg-white rounded-lg p-8 max-w-md w-full">
<h2 class="text-2xl font-bold mb-4">Add User</h2>
<form method="POST" class="space-y-4">
<div>
<label class="block font-medium mb-2">Name *</label>
<input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block font-medium mb-2">Email *</label>
<input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block font-medium mb-2">Password *</label>
<input type="password" name="password" required minlength="6" class="w-full px-4 py-2 border rounded-lg">
</div>
<div>
<label class="block font-medium mb-2">Role</label>
<select name="role" class="w-full px-4 py-2 border rounded-lg">
<option value="admin">Admin</option>
<option value="manager">Manager</option>
<option value="staff">Staff</option>
</select>
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