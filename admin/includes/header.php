<?php require_once 'auth.php'; ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel - Khaitan Footwear</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
body { font-family: 'Inter', sans-serif; }
.sidebar { width: 250px; }
.main-content { margin-left: 250px; }
@media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); transition: transform 0.3s; }
    .sidebar.open { transform: translateX(0); }
    .main-content { margin-left: 0; }
}
</style>
</head>
<body class="bg-gray-100">
<!-- Sidebar -->
<aside class="sidebar fixed left-0 top-0 h-full bg-gray-900 text-white z-40 overflow-y-auto">
<div class="p-6">
<div class="flex items-center mb-8">
<div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-full flex items-center justify-center text-xl font-bold">K</div>
<span class="ml-3 text-xl font-bold">Khaitan Admin</span>
</div>

<nav class="space-y-2">
<a href="dashboard.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-gradient-to-r from-orange-600 to-red-600' : '' ?>">
<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
Dashboard
</a>

<a href="analytics.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition <?= basename($_SERVER['PHP_SELF']) == 'analytics.php' ? 'bg-gradient-to-r from-orange-600 to-red-600' : '' ?>">
<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
Analytics
</a>

<a href="products.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition <?= basename($_SERVER['PHP_SELF']) == 'products.php' || basename($_SERVER['PHP_SELF']) == 'product-edit.php' ? 'bg-gradient-to-r from-orange-600 to-red-600' : '' ?>">
<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
Products
</a>

<a href="categories.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition <?= basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'bg-gradient-to-r from-orange-600 to-red-600' : '' ?>">
<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
Categories
</a>

<a href="banners.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition <?= basename($_SERVER['PHP_SELF']) == 'banners.php' ? 'bg-gradient-to-r from-orange-600 to-red-600' : '' ?>">
<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
Banners
</a>

<a href="contacts.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition <?= basename($_SERVER['PHP_SELF']) == 'contacts.php' ? 'bg-gradient-to-r from-orange-600 to-red-600' : '' ?>">
<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
Contacts
</a>

<a href="users.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition <?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'bg-gradient-to-r from-orange-600 to-red-600' : '' ?>">
<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
Users
</a>

<div class="border-t border-gray-700 my-4"></div>

<a href="email-settings.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition <?= basename($_SERVER['PHP_SELF']) == 'email-settings.php' ? 'bg-gradient-to-r from-orange-600 to-red-600' : '' ?>">
<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
Email Settings
</a>

<a href="settings.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition <?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'bg-gradient-to-r from-orange-600 to-red-600' : '' ?>">
<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
Settings
</a>
</nav>
</div>
</aside>

<!-- Main Content -->
<div class="main-content">
<!-- Top Bar -->
<header class="bg-white shadow-sm">
<div class="flex justify-between items-center px-6 py-4">
<button id="mobile-menu-btn" class="md:hidden text-gray-700">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
</button>
<div class="flex items-center space-x-4">
<a href="../index.php" target="_blank" class="text-gray-600 hover:text-orange-600 flex items-center">
<svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
View Site
</a>
<div class="flex items-center bg-gray-100 px-4 py-2 rounded-lg">
<span class="text-gray-700 mr-3 font-medium"><?= htmlspecialchars($admin_name) ?></span>
<a href="logout.php" class="text-red-600 hover:text-red-700 font-medium">Logout</a>
</div>
</div>
</div>
</header>

<main class="p-6">
<script>
document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('open');
});
</script>