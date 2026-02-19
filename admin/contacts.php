<?php
require_once 'includes/header.php';

if (isset($_POST['update_status'])) {
    $pdo->prepare("UPDATE contacts SET status = ? WHERE id = ?")->execute([$_POST['status'], $_POST['contact_id']]);
}

$contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Contact Inquiries</h1>

<div class="bg-white rounded-lg shadow overflow-hidden">
<table class="w-full">
<thead class="bg-gray-50">
<tr>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-200">
<?php foreach ($contacts as $c): ?>
<tr>
<td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($c['name']) ?></td>
<td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($c['email']) ?></td>
<td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($c['phone']) ?></td>
<td class="px-6 py-4 text-gray-600"><?= date('d M Y', strtotime($c['created_at'])) ?></td>
<td class="px-6 py-4">
<form method="POST" class="inline">
<input type="hidden" name="contact_id" value="<?= $c['id'] ?>">
<select name="status" onchange="this.form.submit()" class="px-2 py-1 text-xs font-semibold rounded-full border-0 <?= $c['status'] == 'new' ? 'bg-blue-100 text-blue-800' : ($c['status'] == 'read' ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800') ?>">
<option value="new" <?= $c['status'] == 'new' ? 'selected' : '' ?>>New</option>
<option value="read" <?= $c['status'] == 'read' ? 'selected' : '' ?>>Read</option>
<option value="replied" <?= $c['status'] == 'replied' ? 'selected' : '' ?>>Replied</option>
</select>
<input type="hidden" name="update_status" value="1">
</form>
</td>
<td class="px-6 py-4">
<button onclick="viewMessage(<?= htmlspecialchars(json_encode($c)) ?>)" class="text-blue-600 hover:text-blue-800">View</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<div id="messageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
<div class="bg-white rounded-lg p-8 max-w-2xl w-full">
<h2 class="text-2xl font-bold mb-4" id="modal_name"></h2>
<div class="space-y-2 mb-4">
<p><strong>Email:</strong> <span id="modal_email"></span></p>
<p><strong>Phone:</strong> <span id="modal_phone"></span></p>
<p><strong>Company:</strong> <span id="modal_company"></span></p>
</div>
<div class="bg-gray-50 p-4 rounded mb-4">
<strong>Message:</strong>
<p id="modal_message" class="mt-2"></p>
</div>
<button onclick="document.getElementById('messageModal').classList.add('hidden')" class="bg-orange-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-700">Close</button>
</div>
</div>

<script>
function viewMessage(contact) {
    document.getElementById('modal_name').textContent = contact.name;
    document.getElementById('modal_email').textContent = contact.email;
    document.getElementById('modal_phone').textContent = contact.phone || 'N/A';
    document.getElementById('modal_company').textContent = contact.company || 'N/A';
    document.getElementById('modal_message').textContent = contact.message;
    document.getElementById('messageModal').classList.remove('hidden');
}
</script>

<?php require_once 'includes/footer.php'; ?>
