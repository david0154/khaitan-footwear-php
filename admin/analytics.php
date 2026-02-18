<?php
require_once 'includes/header.php';

// Get date range
$from = $_GET['from'] ?? date('Y-m-01');
$to = $_GET['to'] ?? date('Y-m-d');

// Analytics data
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$active_products = $pdo->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetchColumn();
$total_categories = $pdo->query("SELECT COUNT(*) FROM categories WHERE status = 'active'")->fetchColumn();
$total_contacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE created_at BETWEEN '$from' AND '$to'")->fetchColumn();
$new_contacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'new'")->fetchColumn();

// Products by category
$products_by_category = $pdo->query("
    SELECT c.name, COUNT(p.id) as count 
    FROM categories c 
    LEFT JOIN products p ON c.id = p.category_id AND p.status = 'active'
    WHERE c.status = 'active'
    GROUP BY c.id, c.name
")->fetchAll();

// Contacts trend (last 7 days)
$contacts_trend = $pdo->query("
    SELECT DATE(created_at) as date, COUNT(*) as count
    FROM contacts
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date
")->fetchAll();

// Top inquired products (mock data - you can track this with a views table)
$featured_products = $pdo->query("
    SELECT name, article_code, is_featured
    FROM products
    WHERE status = 'active'
    ORDER BY is_featured DESC, created_at DESC
    LIMIT 5
")->fetchAll();
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">📊 Sales Analytics</h1>
    <p class="text-gray-600">Business insights and performance metrics</p>
</div>

<!-- Date Filter -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <form method="GET" class="flex items-end gap-4">
        <div>
            <label class="block text-sm font-medium mb-2">From Date</label>
            <input type="date" name="from" value="<?= $from ?>" class="px-4 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">To Date</label>
            <input type="date" name="to" value="<?= $to ?>" class="px-4 py-2 border rounded-lg">
        </div>
        <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700">
            Filter
        </button>
    </form>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-orange-500 to-red-500 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-orange-100 text-sm">Total Products</p>
                <p class="text-4xl font-bold mt-2"><?= $total_products ?></p>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
        </div>
        <p class="text-orange-100 text-sm"><?= $active_products ?> Active</p>
    </div>
    
    <div class="bg-gradient-to-br from-blue-500 to-purple-500 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-blue-100 text-sm">Categories</p>
                <p class="text-4xl font-bold mt-2"><?= $total_categories ?></p>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
        </div>
        <p class="text-blue-100 text-sm">Active Collections</p>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-teal-500 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-green-100 text-sm">Total Inquiries</p>
                <p class="text-4xl font-bold mt-2"><?= $total_contacts ?></p>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>
        <p class="text-green-100 text-sm">Selected Period</p>
    </div>
    
    <div class="bg-gradient-to-br from-pink-500 to-rose-500 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-pink-100 text-sm">Pending Inquiries</p>
                <p class="text-4xl font-bold mt-2"><?= $new_contacts ?></p>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
        </div>
        <p class="text-pink-100 text-sm">Needs Response</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Products by Category -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-6 flex items-center">
            <span class="w-1 h-6 bg-orange-600 mr-3 rounded"></span>
            Products by Category
        </h2>
        <div class="space-y-4">
            <?php foreach ($products_by_category as $cat): ?>
            <div>
                <div class="flex justify-between mb-2">
                    <span class="font-medium"><?= htmlspecialchars($cat['name']) ?></span>
                    <span class="text-gray-600"><?= $cat['count'] ?> products</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <?php $percentage = $total_products > 0 ? ($cat['count'] / $total_products) * 100 : 0; ?>
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 h-3 rounded-full" style="width: <?= $percentage ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Inquiry Trend -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-6 flex items-center">
            <span class="w-1 h-6 bg-blue-600 mr-3 rounded"></span>
            Inquiry Trend (Last 7 Days)
        </h2>
        <div class="space-y-3">
            <?php foreach ($contacts_trend as $trend): ?>
            <div class="flex items-center justify-between">
                <span class="text-gray-600"><?= date('M d', strtotime($trend['date'])) ?></span>
                <div class="flex-1 mx-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: <?= $trend['count'] * 10 ?>%"></div>
                    </div>
                </div>
                <span class="font-bold text-blue-600"><?= $trend['count'] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Top Products -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-xl font-bold mb-6 flex items-center">
        <span class="w-1 h-6 bg-green-600 mr-3 rounded"></span>
        Featured Products
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <?php foreach ($featured_products as $p): ?>
        <div class="border rounded-lg p-4 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-sm"><?= htmlspecialchars($p['name']) ?></h3>
                <?php if ($p['is_featured']): ?>
                <span class="text-yellow-500">⭐</span>
                <?php endif; ?>
            </div>
            <p class="text-xs text-gray-500"><?= htmlspecialchars($p['article_code']) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>