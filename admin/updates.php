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
$updateAvailable = false;
$currentVersion = '';
$latestVersion = '';
$changelog = [];

// GitHub repository details
$githubOwner = 'david0154';
$githubRepo = 'khaitan-footwear-php';
$githubBranch = 'main';

// Get current version from file
if (file_exists('../VERSION')) {
    $currentVersion = trim(file_get_contents('../VERSION'));
} else {
    $currentVersion = '1.0.0';
    file_put_contents('../VERSION', $currentVersion);
}

// Function to check for updates
function checkForUpdates($owner, $repo, $branch) {
    $url = "https://api.github.com/repos/{$owner}/{$repo}/commits/{$branch}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Khaitan-Footwear-Updater');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        return json_decode($response, true);
    }
    return null;
}

// Function to get recent commits
function getRecentCommits($owner, $repo, $limit = 10) {
    $url = "https://api.github.com/repos/{$owner}/{$repo}/commits?per_page={$limit}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Khaitan-Footwear-Updater');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        return json_decode($response, true);
    }
    return [];
}

// Check for updates
$latestCommit = checkForUpdates($githubOwner, $githubRepo, $githubBranch);
if ($latestCommit) {
    $latestVersion = substr($latestCommit['sha'], 0, 7);
    $updateAvailable = ($currentVersion !== $latestVersion);
    $changelog = getRecentCommits($githubOwner, $githubRepo, 10);
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    try {
        // Backup current files
        $backupDir = '../backups/backup_' . date('Y-m-d_H-i-s');
        if (!file_exists('../backups')) {
            mkdir('../backups', 0755, true);
        }
        
        // Create backup
        exec("cp -r ../. {$backupDir}", $output, $return_code);
        
        if ($return_code !== 0) {
            throw new Exception('Failed to create backup');
        }
        
        // Pull latest changes from GitHub
        $repoPath = realpath('..');
        chdir($repoPath);
        
        // Reset any local changes
        exec('git reset --hard HEAD 2>&1', $output1, $return1);
        
        // Pull latest
        exec('git pull origin main 2>&1', $output2, $return2);
        
        if ($return2 === 0) {
            // Update version file
            file_put_contents('../VERSION', $latestVersion);
            
            $success = '✅ Website updated successfully to version ' . $latestVersion . '!';
            $currentVersion = $latestVersion;
            $updateAvailable = false;
            
            // Log update
            $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('last_update', ?)");
            $stmt->execute([date('Y-m-d H:i:s')]);
        } else {
            throw new Exception('Git pull failed: ' . implode("\n", $output2));
        }
        
    } catch (Exception $e) {
        $error = '❌ Update failed: ' . $e->getMessage();
    }
}

// Auto-update setting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_auto'])) {
    $autoUpdate = isset($_POST['auto_update']) ? '1' : '0';
    $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('auto_update_enabled', ?) ON DUPLICATE KEY UPDATE value = ?");
    $stmt->execute([$autoUpdate, $autoUpdate]);
    $success = 'Auto-update settings saved!';
}

// Get auto-update setting
$stmt = $pdo->query("SELECT value FROM settings WHERE `key` = 'auto_update_enabled'");
$autoUpdateEnabled = $stmt->fetchColumn() ?: '0';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Updates - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'sidebar.php'; ?>
        
        <main class="flex-1">
            <header class="bg-white shadow px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-900">🔄 System Updates</h1>
                <p class="text-gray-600">Check and install updates from GitHub</p>
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
                
                <!-- Current Status -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Current Version</p>
                                <p class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($currentVersion) ?></p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-2xl">💻</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Latest Version</p>
                                <p class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($latestVersion) ?></p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-2xl">🚀</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Status</p>
                                <p class="text-2xl font-bold <?= $updateAvailable ? 'text-orange-600' : 'text-green-600' ?>">
                                    <?= $updateAvailable ? 'Update Available' : 'Up to Date' ?>
                                </p>
                            </div>
                            <div class="w-12 h-12 <?= $updateAvailable ? 'bg-orange-100' : 'bg-green-100' ?> rounded-full flex items-center justify-center">
                                <span class="text-2xl"><?= $updateAvailable ? '⚠️' : '✅' ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Update Action -->
                <?php if ($updateAvailable): ?>
                <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-lg shadow-xl p-8 mb-6">
                    <h2 class="text-3xl font-bold mb-4">🎉 New Update Available!</h2>
                    <p class="text-xl mb-6">A new version is available. Update now to get the latest features and bug fixes.</p>
                    
                    <form method="POST" onsubmit="return confirm('Create backup and update now? This will restart the website briefly.')">
                        <button type="submit" name="update" value="1" class="bg-white text-red-600 px-8 py-4 rounded-lg text-xl font-bold hover:bg-gray-100 transition shadow-lg">
                            🔄 Update Now
                        </button>
                    </form>
                    
                    <p class="text-sm mt-4 text-red-100">⚠️ A backup will be created automatically before updating</p>
                </div>
                <?php else: ?>
                <div class="bg-green-100 border border-green-400 rounded-lg p-6 mb-6">
                    <div class="flex items-center">
                        <span class="text-4xl mr-4">✅</span>
                        <div>
                            <h3 class="text-xl font-bold text-green-900">Your website is up to date!</h3>
                            <p class="text-green-700">You're running the latest version from GitHub.</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Auto-Update Settings -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4">⚙️ Auto-Update Settings</h3>
                    <form method="POST">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="auto_update" value="1" <?= $autoUpdateEnabled ? 'checked' : '' ?> class="w-5 h-5 text-red-600">
                            <span class="font-medium">Enable automatic updates (updates will be installed automatically when available)</span>
                        </label>
                        <button type="submit" name="toggle_auto" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Save Settings
                        </button>
                    </form>
                    <p class="text-sm text-gray-600 mt-3">⚠️ Auto-updates will create backups automatically. Not recommended for production sites.</p>
                </div>
                
                <!-- Changelog -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold mb-4">📝 Recent Updates</h3>
                    
                    <?php if (!empty($changelog)): ?>
                    <div class="space-y-4">
                        <?php foreach (array_slice($changelog, 0, 10) as $commit): ?>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900"><?= htmlspecialchars($commit['commit']['message']) ?></p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="font-mono bg-gray-100 px-2 py-1 rounded"><?= substr($commit['sha'], 0, 7) ?></span>
                                        by <?= htmlspecialchars($commit['commit']['author']['name']) ?>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1"><?= date('M d, Y H:i', strtotime($commit['commit']['author']['date'])) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-gray-600">Unable to fetch changelog from GitHub.</p>
                    <?php endif; ?>
                </div>
                
                <!-- Manual Commands -->
                <div class="bg-gray-50 border border-gray-300 rounded-lg p-6 mt-6">
                    <h3 class="text-lg font-bold mb-4">💻 Manual Update Commands</h3>
                    <p class="text-gray-700 mb-3">If automatic update fails, use these commands via SSH:</p>
                    <div class="bg-gray-900 text-green-400 p-4 rounded font-mono text-sm">
                        <p>cd /home/zfugpsef/khaitan</p>
                        <p>git pull origin main</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>