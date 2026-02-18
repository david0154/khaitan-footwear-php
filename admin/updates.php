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

// Load GitHub settings
$stmt = $pdo->query("SELECT * FROM settings WHERE `key` IN ('github_token', 'github_owner', 'github_repo', 'github_branch')");
$githubSettings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $githubSettings[$row['key']] = $row['value'];
}

$githubOwner = $githubSettings['github_owner'] ?? 'david0154';
$githubRepo = $githubSettings['github_repo'] ?? 'khaitan-footwear-php';
$githubBranch = $githubSettings['github_branch'] ?? 'main';
$githubToken = $githubSettings['github_token'] ?? '';

// Get current version from file
if (file_exists('../VERSION')) {
    $currentVersion = trim(file_get_contents('../VERSION'));
} else {
    $currentVersion = '1.0.0';
    file_put_contents('../VERSION', $currentVersion);
}

// Function to check for updates with token support
function checkForUpdates($owner, $repo, $branch, $token = '') {
    $url = "https://api.github.com/repos/{$owner}/{$repo}/commits/{$branch}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Khaitan-Footwear-Updater');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    // Add token for private repos
    if ($token) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/vnd.github.v3+json'
        ]);
    }
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        return json_decode($response, true);
    }
    return null;
}

// Function to get recent commits with token support
function getRecentCommits($owner, $repo, $limit = 10, $token = '') {
    $url = "https://api.github.com/repos/{$owner}/{$repo}/commits?per_page={$limit}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Khaitan-Footwear-Updater');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    if ($token) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/vnd.github.v3+json'
        ]);
    }
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        return json_decode($response, true);
    }
    return [];
}

// Check for updates
$latestCommit = checkForUpdates($githubOwner, $githubRepo, $githubBranch, $githubToken);
if ($latestCommit && !isset($latestCommit['message'])) {
    $latestVersion = substr($latestCommit['sha'], 0, 7);
    $updateAvailable = ($currentVersion !== $latestVersion);
    $changelog = getRecentCommits($githubOwner, $githubRepo, 10, $githubToken);
} elseif (isset($latestCommit['message'])) {
    $error = '⚠️ GitHub API Error: ' . $latestCommit['message'] . ' - Please check GitHub settings.';
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    try {
        // Backup current files
        $backupDir = '../backups/backup_' . date('Y-m-d_H-i-s');
        if (!file_exists('../backups')) {
            mkdir('../backups', 0755, true);
        }
        
        exec("cp -r ../. {$backupDir}", $output, $return_code);
        
        if ($return_code !== 0) {
            throw new Exception('Failed to create backup');
        }
        
        $repoPath = realpath('..');
        chdir($repoPath);
        
        exec('git reset --hard HEAD 2>&1', $output1, $return1);
        exec('git pull origin ' . $githubBranch . ' 2>&1', $output2, $return2);
        
        if ($return2 === 0) {
            file_put_contents('../VERSION', $latestVersion);
            $success = '✅ Website updated successfully to version ' . $latestVersion . '!';
            $currentVersion = $latestVersion;
            $updateAvailable = false;
            
            $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('last_update', ?) ON DUPLICATE KEY UPDATE value = ?");
            $now = date('Y-m-d H:i:s');
            $stmt->execute([$now, $now]);
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
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">🔄 System Updates</h1>
                        <p class="text-gray-600">Auto-fetch from: github.com/<?= htmlspecialchars($githubOwner) ?>/<?= htmlspecialchars($githubRepo) ?></p>
                    </div>
                    <a href="github-settings.php" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                        🔐 GitHub Settings
                    </a>
                </div>
            </header>
            
            <div class="p-6">
                <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= $success ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= $error ?></div>
                <?php endif; ?>
                
                <!-- Status Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Current Version</p>
                                <p class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($currentVersion) ?></p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center"><span class="text-2xl">💻</span></div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Latest Version</p>
                                <p class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($latestVersion ?: 'Checking...') ?></p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center"><span class="text-2xl">🚀</span></div>
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
                
                <!-- Update Button -->
                <?php if ($updateAvailable): ?>
                <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-lg shadow-xl p-8 mb-6">
                    <h2 class="text-3xl font-bold mb-4">🎉 New Update Available!</h2>
                    <p class="text-xl mb-6">Auto-fetched from your GitHub repository. Update now!</p>
                    <form method="POST" onsubmit="return confirm('Create backup and update now?')">
                        <button type="submit" name="update" value="1" class="bg-white text-red-600 px-8 py-4 rounded-lg text-xl font-bold hover:bg-gray-100 transition shadow-lg">🔄 Update Now</button>
                    </form>
                </div>
                <?php else: ?>
                <div class="bg-green-100 border border-green-400 rounded-lg p-6 mb-6">
                    <div class="flex items-center"><span class="text-4xl mr-4">✅</span><div><h3 class="text-xl font-bold text-green-900">Up to date!</h3><p class="text-green-700">Auto-synced with GitHub repository.</p></div></div>
                </div>
                <?php endif; ?>
                
                <!-- Changelog -->
                <?php if (!empty($changelog)): ?>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold mb-4">📝 Recent Updates</h3>
                    <div class="space-y-4">
                        <?php foreach (array_slice($changelog, 0, 10) as $commit): ?>
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p class="font-semibold text-gray-900"><?= htmlspecialchars($commit['commit']['message']) ?></p>
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="font-mono bg-gray-100 px-2 py-1 rounded"><?= substr($commit['sha'], 0, 7) ?></span>
                                by <?= htmlspecialchars($commit['commit']['author']['name']) ?>
                            </p>
                            <p class="text-xs text-gray-500 mt-1"><?= date('M d, Y H:i', strtotime($commit['commit']['author']['date'])) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>