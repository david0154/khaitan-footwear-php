<?php
session_start();
$step = $_GET['step'] ?? 1;
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step == 1) {
        // Test database connection
        try {
            $pdo = new PDO(
                "mysql:host={$_POST['db_host']}",
                $_POST['db_user'],
                $_POST['db_pass']
            );
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$_POST['db_name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $_SESSION['install'] = $_POST;
            header('Location: install.php?step=2');
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } elseif ($step == 2) {
        $_SESSION['install'] = array_merge($_SESSION['install'], $_POST);
        header('Location: install.php?step=3');
        exit;
    } elseif ($step == 3) {
        try {
            $data = $_SESSION['install'];
            $pdo = new PDO(
                "mysql:host={$data['db_host']};dbname={$data['db_name']}",
                $data['db_user'],
                $data['db_pass']
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create tables
            $sql = file_get_contents('database.sql');
            $pdo->exec($sql);
            
            // Create admin user
            $hash = password_hash($_POST['admin_pass'], PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, 'admin', NOW())")
                ->execute(['Admin', $_POST['admin_email'], $hash]);
            
            // Save settings
            $settings = [
                ['site_name', $data['site_name']],
                ['site_email', $_POST['admin_email']],
                ['site_phone', '+91 98765 43210'],
            ];
            $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?)");
            foreach ($settings as $s) $stmt->execute($s);
            
            // Create config file
            $config = "<?php\ndefine('DB_HOST', '{$data['db_host']}');\ndefine('DB_NAME', '{$data['db_name']}');\ndefine('DB_USER', '{$data['db_user']}');\ndefine('DB_PASS', '{$data['db_pass']}');\n";
            file_put_contents('config.php', $config);
            file_put_contents('installed.lock', date('Y-m-d H:i:s'));
            
            $success = true;
            unset($_SESSION['install']);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Install - Khaitan Footwear</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
<div class="container mx-auto px-4 py-12">
<div class="max-w-xl mx-auto">
<div class="text-center mb-8">
<h1 class="text-4xl font-bold text-orange-600 mb-2">🚀 Khaitan Footwear</h1>
<p class="text-gray-600">One-Click Installation</p>
</div>

<div class="bg-white rounded-lg shadow-xl p-8">
<?php if ($error): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="text-center">
<div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
<svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
</div>
<h2 class="text-3xl font-bold mb-4">✅ Installation Complete!</h2>
<div class="bg-blue-50 p-4 rounded mb-6">
<p class="font-bold mb-2">Admin Login:</p>
<p>Email: <code class="bg-blue-100 px-2 py-1 rounded"><?= htmlspecialchars($_POST['admin_email']) ?></code></p>
<p>Password: <code class="bg-blue-100 px-2 py-1 rounded"><?= htmlspecialchars($_POST['admin_pass']) ?></code></p>
</div>
<div class="space-y-3">
<a href="index.php" class="block w-full bg-orange-600 text-white py-3 rounded-lg font-bold hover:bg-orange-700">🏠 View Website</a>
<a href="admin/login.php" class="block w-full bg-gray-800 text-white py-3 rounded-lg font-bold hover:bg-gray-900">🔐 Admin Panel</a>
</div>
<div class="mt-6 p-4 bg-red-50 border border-red-300 rounded">
<p class="text-red-800 font-bold">🔒 Delete install.php now!</p>
</div>
</div>

<?php elseif ($step == 1): ?>
<h2 class="text-2xl font-bold mb-6">📦 Database Setup</h2>
<form method="POST">
<div class="space-y-4">
<div>
<label class="block font-medium mb-2">Host</label>
<input type="text" name="db_host" value="localhost" required class="w-full px-4 py-2 border rounded">
</div>
<div>
<label class="block font-medium mb-2">Database Name</label>
<input type="text" name="db_name" value="khaitan_footwear" required class="w-full px-4 py-2 border rounded">
</div>
<div>
<label class="block font-medium mb-2">Username</label>
<input type="text" name="db_user" value="root" required class="w-full px-4 py-2 border rounded">
</div>
<div>
<label class="block font-medium mb-2">Password</label>
<input type="password" name="db_pass" class="w-full px-4 py-2 border rounded">
</div>
<button type="submit" class="w-full bg-orange-600 text-white py-3 rounded-lg font-bold hover:bg-orange-700">Continue →</button>
</div>
</form>

<?php elseif ($step == 2): ?>
<h2 class="text-2xl font-bold mb-6">🏢 Site Information</h2>
<form method="POST">
<div class="space-y-4">
<div>
<label class="block font-medium mb-2">Company Name</label>
<input type="text" name="site_name" value="Khaitan Footwear" required class="w-full px-4 py-2 border rounded">
</div>
<button type="submit" class="w-full bg-orange-600 text-white py-3 rounded-lg font-bold hover:bg-orange-700">Continue →</button>
</div>
</form>

<?php elseif ($step == 3): ?>
<h2 class="text-2xl font-bold mb-6">👤 Admin Account</h2>
<form method="POST">
<div class="space-y-4">
<div>
<label class="block font-medium mb-2">Email</label>
<input type="email" name="admin_email" value="admin@khaitanfootwear.in" required class="w-full px-4 py-2 border rounded">
</div>
<div>
<label class="block font-medium mb-2">Password</label>
<input type="password" name="admin_pass" value="admin123" required minlength="6" class="w-full px-4 py-2 border rounded">
</div>
<button type="submit" class="w-full bg-orange-600 text-white py-3 rounded-lg font-bold hover:bg-orange-700">🚀 Install Now</button>
</div>
</form>
<?php endif; ?>
</div>
</div>
</div>
</body>
</html>