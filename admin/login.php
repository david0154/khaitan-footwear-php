<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once '../config.php';
$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['name'];
        $_SESSION['admin_email'] = $user['email'];
        $_SESSION['admin_role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin Login - Khaitan Footwear</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-orange-50 to-red-50 min-h-screen flex items-center justify-center">
<div class="w-full max-w-md">
<div class="bg-white rounded-lg shadow-xl p-8">
<div class="text-center mb-8">
<div class="w-16 h-16 bg-orange-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">K</div>
<h1 class="text-2xl font-bold text-gray-800">Admin Login</h1>
<p class="text-gray-600">Khaitan Footwear</p>
</div>

<?php if ($error): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" class="space-y-4">
<div>
<label class="block font-medium mb-2 text-gray-700">Email</label>
<input type="email" name="email" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500">
</div>
<div>
<label class="block font-medium mb-2 text-gray-700">Password</label>
<input type="password" name="password" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500">
</div>
<button type="submit" class="w-full bg-orange-600 text-white py-3 rounded-lg font-bold hover:bg-orange-700 transition">Login</button>
</form>

<div class="text-center mt-6">
<a href="../index.php" class="text-orange-600 hover:text-orange-700">← Back to Website</a>
</div>
</div>
</div>
</body>
</html>