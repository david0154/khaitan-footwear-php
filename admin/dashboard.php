<?php
require_once 'includes/header.php';

// Get stats
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$total_contacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'new'")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Recent products
$recent_products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Recent contacts
$recent_contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard</h1>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
<div class="bg-white p-6 rounded-lg shadow">
<div class="flex items-center justify-between">
<div>
<p class="text-gray-600 text-sm">Total Products</p>
<p class="text-3xl font-bold text-gray-800"><?= $total_products ?></p>
</div>
<div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
</div>
</div>
</div>

<div class="bg-white p-6 rounded-lg shadow">
<div class="flex items-center justify-between">
<div>
<p class="text-gray-600 text-sm">Categories</p>
<p class="text-3xl font-bold text-gray-800"><?= $total_categories ?></p>
</div>
<div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
</div>
</div>
</div>

<div class="bg-white p-6 rounded-lg shadow">
<div class="flex items-center justify-between">
<div>
<p class="text-gray-600 text-sm">New Contacts</p>
<p class="text-3xl font-bold text-gray-800"><?= $total_contacts ?></p>
</div>
<div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
</div>
</div>
</div>

<div class="bg-white p-6 rounded-lg shadow">
<div class="flex items-center justify-between">
<div>
<p class="text-gray-600 text-sm">Total Users</p>
<p class="text-3xl font-bold text-gray-800"><?= $total_users ?></p>
</div>
<div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
</div>
</div>
</div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<!-- Recent Products -->
<div class="bg-white rounded-lg shadow">
<div class="p-6 border-b">
<h2 class="text-xl font-bold text-gray-800">Recent Products</h2>
</div>
<div class="p-6">
<?php if (empty($recent_products)): ?>
<p class="text-gray-500 text-center py-4">No products yet</p>
<?php else: ?>
<div class="space-y-4">
<?php foreach ($recent_products as $p): ?>
<div class="flex items-center justify-between">
<div>
<p class="font-semibold text-gray-800"><?= htmlspecialchars($p['name']) ?></p>
<p class="text-sm text-gray-600"><?= htmlspecialchars($p['article_code']) ?></p>
</div>
<span class="px-3 py-1 text-xs font-semibold rounded-full <?= $p['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>"><?= ucfirst($p['status']) ?></span>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<div class="mt-4">
<a href="products.php" class="text-orange-600 hover:text-orange-700 font-semibold">View all →</a>
</div>
</div>
</div>

<!-- Recent Contacts -->
<div class="bg-white rounded-lg shadow">
<div class="p-6 border-b">
<h2 class="text-xl font-bold text-gray-800">Recent Contacts</h2>
</div>
<div class="p-6">
<?php if (empty($recent_contacts)): ?>
<p class="text-gray-500 text-center py-4">No contacts yet</p>
<?php else: ?>
<div class="space-y-4">
<?php foreach ($recent_contacts as $c): ?>
<div class="flex items-center justify-between">
<div>
<p class="font-semibold text-gray-800"><?= htmlspecialchars($c['name']) ?></p>
<p class="text-sm text-gray-600"><?= htmlspecialchars($c['email']) ?></p>
</div>
<span class="px-3 py-1 text-xs font-semibold rounded-full <?= $c['status'] == 'new' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' ?>"><?= ucfirst($c['status']) ?></span>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<div class="mt-4">
<a href="contacts.php" class="text-orange-600 hover:text-orange-700 font-semibold">View all →</a>
</div>
</div>
</div>
</div>

<?php require_once 'includes/footer.php'; ?>