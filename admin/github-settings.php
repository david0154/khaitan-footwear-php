<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $github_token = $_POST['github_token'] ?? '';
    $github_owner = $_POST['github_owner'] ?? 'david0154';
    $github_repo = $_POST['github_repo'] ?? 'khaitan-footwear-php';
    $github_branch = $_POST['github_branch'] ?? 'main';
    
    // Save settings
    $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
    
    $stmt->execute(['github_token', $github_token, $github_token]);
    $stmt->execute(['github_owner', $github_owner, $github_owner]);
    $stmt->execute(['github_repo', $github_repo, $github_repo]);
    $stmt->execute(['github_branch', $github_branch, $github_branch]);
    
    $success = '✅ GitHub settings saved! Updates will now work with private repos.';
}

// Load current settings
$stmt = $pdo->query("SELECT * FROM settings WHERE `key` IN ('github_token', 'github_owner', 'github_repo', 'github_branch')");
$settings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $settings[$row['key']] = $row['value'];
}

$github_token = $settings['github_token'] ?? '';
$github_owner = $settings['github_owner'] ?? 'david0154';
$github_repo = $settings['github_repo'] ?? 'khaitan-footwear-php';
$github_branch = $settings['github_branch'] ?? 'main';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>GitHub Settings - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'sidebar.php'; ?>
        
        <main class="flex-1">
            <header class="bg-white shadow px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-900">🔐 GitHub Integration Settings</h1>
                <p class="text-gray-600">Configure GitHub access for auto-updates (works with private repos)</p>
            </header>
            
            <div class="p-6">
                <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($success) ?>
                </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                
                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-300 rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-blue-900 mb-4">💡 Why You Need This</h3>
                    <ul class="space-y-2 text-blue-800">
                        <li>✅ <strong>Private Repositories:</strong> Access your private GitHub repos for updates</li>
                        <li>✅ <strong>Secure Updates:</strong> Token-based authentication for security</li>
                        <li>✅ <strong>Auto-Updates:</strong> Fetch updates automatically without manual intervention</li>
                        <li>✅ <strong>No Public Exposure:</strong> Keep your code private while updating</li>
                    </ul>
                </div>
                
                <!-- Settings Form -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold mb-6">⚙️ Repository Configuration</h3>
                    
                    <form method="POST" class="space-y-6">
                        <div>
                            <label class="block font-semibold mb-2">GitHub Repository Owner</label>
                            <input type="text" name="github_owner" value="<?= htmlspecialchars($github_owner) ?>" class="w-full px-4 py-2 border rounded-lg" required>
                            <p class="text-sm text-gray-600 mt-1">Your GitHub username or organization name</p>
                        </div>
                        
                        <div>
                            <label class="block font-semibold mb-2">Repository Name</label>
                            <input type="text" name="github_repo" value="<?= htmlspecialchars($github_repo) ?>" class="w-full px-4 py-2 border rounded-lg" required>
                            <p class="text-sm text-gray-600 mt-1">Repository name (e.g., khaitan-footwear-php)</p>
                        </div>
                        
                        <div>
                            <label class="block font-semibold mb-2">Branch Name</label>
                            <input type="text" name="github_branch" value="<?= htmlspecialchars($github_branch) ?>" class="w-full px-4 py-2 border rounded-lg" required>
                            <p class="text-sm text-gray-600 mt-1">Branch to pull updates from (usually 'main' or 'master')</p>
                        </div>
                        
                        <div>
                            <label class="block font-semibold mb-2">🔑 GitHub Personal Access Token</label>
                            <input type="password" name="github_token" value="<?= htmlspecialchars($github_token) ?>" class="w-full px-4 py-2 border rounded-lg font-mono text-sm" placeholder="ghp_xxxxxxxxxxxxxxxxxxxx">
                            <p class="text-sm text-gray-600 mt-1">
                                <strong>Required for private repos.</strong> Leave empty for public repos.
                            </p>
                        </div>
                        
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                            💾 Save Settings
                        </button>
                    </form>
                </div>
                
                <!-- How to Create Token -->
                <div class="bg-gray-50 border border-gray-300 rounded-lg p-6 mt-6">
                    <h3 class="text-xl font-bold mb-4">📖 How to Create GitHub Personal Access Token</h3>
                    
                    <div class="space-y-3 text-gray-700">
                        <p class="font-semibold">Follow these steps:</p>
                        <ol class="list-decimal list-inside space-y-2 ml-4">
                            <li>Go to <a href="https://github.com/settings/tokens" target="_blank" class="text-blue-600 hover:underline">GitHub Settings → Developer settings → Personal access tokens → Tokens (classic)</a></li>
                            <li>Click <strong>"Generate new token (classic)"</strong></li>
                            <li>Give it a name: <code class="bg-gray-200 px-2 py-1 rounded">Khaitan Footwear Auto-Update</code></li>
                            <li>Set expiration: <strong>No expiration</strong> (recommended for auto-updates)</li>
                            <li>Select scopes:
                                <ul class="list-disc list-inside ml-6 mt-2">
                                    <li>✅ <code class="bg-gray-200 px-2 py-1 rounded">repo</code> (Full control of private repositories)</li>
                                </ul>
                            </li>
                            <li>Click <strong>"Generate token"</strong></li>
                            <li>Copy the token (starts with <code class="bg-gray-200 px-2 py-1 rounded">ghp_</code>)</li>
                            <li>Paste it in the field above</li>
                        </ol>
                        
                        <div class="bg-yellow-100 border border-yellow-400 rounded p-4 mt-4">
                            <p class="text-yellow-800 font-semibold">⚠️ Important:</p>
                            <ul class="text-yellow-700 text-sm mt-2 space-y-1">
                                <li>• Save the token immediately - GitHub shows it only once!</li>
                                <li>• Keep it secret - treat it like a password</li>
                                <li>• Don't share it publicly</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Current Configuration -->
                <div class="bg-white border border-gray-300 rounded-lg p-6 mt-6">
                    <h3 class="text-xl font-bold mb-4">📊 Current Configuration</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-sm text-gray-600">Repository URL</p>
                            <p class="font-mono text-sm font-bold text-gray-900 mt-1">
                                https://github.com/<?= htmlspecialchars($github_owner) ?>/<?= htmlspecialchars($github_repo) ?>
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-sm text-gray-600">Branch</p>
                            <p class="font-mono text-sm font-bold text-gray-900 mt-1"><?= htmlspecialchars($github_branch) ?></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-sm text-gray-600">Token Status</p>
                            <p class="font-bold text-gray-900 mt-1">
                                <?php if ($github_token): ?>
                                <span class="text-green-600">✅ Configured</span>
                                <?php else: ?>
                                <span class="text-orange-600">⚠️ Not Set (Public repos only)</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-sm text-gray-600">Repository Type</p>
                            <p class="font-bold text-gray-900 mt-1">
                                <?php if ($github_token): ?>
                                <span class="text-blue-600">🔒 Private/Public (Token Auth)</span>
                                <?php else: ?>
                                <span class="text-gray-600">🌐 Public Only</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>