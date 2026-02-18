<?php
/**
 * Simple Update Script
 * Run this file to update your website from GitHub
 * Works from browser: http://yourdomain.com/update-now.php
 */

// Security: Change this password!
$UPDATE_PASSWORD = 'khaitan2024';

// Check password
if (!isset($_GET['password']) || $_GET['password'] !== $UPDATE_PASSWORD) {
    die('<h1>❌ Access Denied</h1><p>Add ?password=khaitan2024 to URL</p>');
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>🔄 Updating Website...</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h1 { color: #dc2626; }
        .success { background: #dcfce7; border-left: 4px solid #16a34a; padding: 15px; margin: 10px 0; }
        .error { background: #fee2e2; border-left: 4px solid #dc2626; padding: 15px; margin: 10px 0; }
        .info { background: #dbeafe; border-left: 4px solid #2563eb; padding: 15px; margin: 10px 0; }
        .command { background: #1f2937; color: #10b981; padding: 15px; border-radius: 5px; font-family: monospace; margin: 10px 0; }
        pre { background: #f3f4f6; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔄 Website Update Tool</h1>
        <p>This will pull the latest code from GitHub and update your website.</p>
    </div>

<?php

echo '<div class="box">';
echo '<h2>Step 1: Checking Current Status</h2>';

// Get current directory
$repoPath = realpath(__DIR__);
echo "<div class='info'><strong>Website Path:</strong> {$repoPath}</div>";

// Check if git exists
exec('which git 2>&1', $gitCheck, $gitReturn);
if ($gitReturn !== 0) {
    echo "<div class='error'>❌ Git is not installed or not in PATH</div>";
    echo "<p>Please install git or contact your hosting provider.</p>";
    die('</div></body></html>');
}
echo "<div class='success'>✅ Git found: " . $gitCheck[0] . "</div>";

// Check if .git directory exists
if (!is_dir($repoPath . '/.git')) {
    echo "<div class='error'>❌ Not a git repository!</div>";
    echo "<p>Please initialize git first:</p>";
    echo "<div class='command'>cd {$repoPath}<br>git init<br>git remote add origin https://github.com/david0154/khaitan-footwear-php.git<br>git fetch origin<br>git reset --hard origin/main</div>";
    die('</div></body></html>');
}
echo "<div class='success'>✅ Git repository found</div>";

echo '</div>'; // End Step 1

// Step 2: Create Backup
echo '<div class="box">';
echo '<h2>Step 2: Creating Backup</h2>';

$backupDir = $repoPath . '/backups';
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$backupName = 'backup_' . date('Y-m-d_H-i-s');
$backupPath = $backupDir . '/' . $backupName;

echo "<div class='info'>Creating backup: {$backupName}</div>";

exec("cp -r {$repoPath} {$backupPath} 2>&1", $backupOutput, $backupReturn);

if ($backupReturn === 0 && file_exists($backupPath)) {
    echo "<div class='success'>✅ Backup created successfully!</div>";
    echo "<div class='info'>Backup location: {$backupPath}</div>";
} else {
    echo "<div class='error'>⚠️ Backup failed, but continuing...</div>";
}

echo '</div>'; // End Step 2

// Step 3: Fetch Latest Changes
echo '<div class="box">';
echo '<h2>Step 3: Fetching Latest Code from GitHub</h2>';

chdir($repoPath);

// Get current branch
exec('git branch --show-current 2>&1', $branchOutput, $branchReturn);
$currentBranch = $branchOutput[0] ?? 'main';
echo "<div class='info'>Current branch: <strong>{$currentBranch}</strong></div>";

// Fetch from remote
echo "<p>Fetching latest changes from GitHub...</p>";
exec('git fetch origin 2>&1', $fetchOutput, $fetchReturn);

if ($fetchReturn === 0) {
    echo "<div class='success'>✅ Fetch successful</div>";
    if (!empty($fetchOutput)) {
        echo "<pre>" . htmlspecialchars(implode("\n", $fetchOutput)) . "</pre>";
    }
} else {
    echo "<div class='error'>❌ Fetch failed</div>";
    echo "<pre>" . htmlspecialchars(implode("\n", $fetchOutput)) . "</pre>";
}

echo '</div>'; // End Step 3

// Step 4: Reset and Pull
echo '<div class="box">';
echo '<h2>Step 4: Updating Files</h2>';

echo "<p>Resetting any local changes...</p>";
exec('git reset --hard HEAD 2>&1', $resetOutput, $resetReturn);

if ($resetReturn === 0) {
    echo "<div class='success'>✅ Reset successful</div>";
} else {
    echo "<div class='error'>⚠️ Reset warning</div>";
    echo "<pre>" . htmlspecialchars(implode("\n", $resetOutput)) . "</pre>";
}

echo "<p>Pulling latest code from origin/{$currentBranch}...</p>";
exec("git pull origin {$currentBranch} 2>&1", $pullOutput, $pullReturn);

if ($pullReturn === 0) {
    echo "<div class='success'>✅ Pull successful!</div>";
    echo "<pre>" . htmlspecialchars(implode("\n", $pullOutput)) . "</pre>";
} else {
    echo "<div class='error'>❌ Pull failed</div>";
    echo "<pre>" . htmlspecialchars(implode("\n", $pullOutput)) . "</pre>";
}

echo '</div>'; // End Step 4

// Step 5: Show Latest Commit
echo '<div class="box">';
echo '<h2>Step 5: Current Version</h2>';

exec('git log -1 --oneline 2>&1', $logOutput, $logReturn);

if ($logReturn === 0 && !empty($logOutput)) {
    echo "<div class='success'>✅ Latest commit: <strong>" . htmlspecialchars($logOutput[0]) . "</strong></div>";
    
    // Save to VERSION file
    $commitHash = substr($logOutput[0], 0, 7);
    file_put_contents($repoPath . '/VERSION', $commitHash);
    echo "<div class='info'>Version saved: {$commitHash}</div>";
} else {
    echo "<div class='error'>Could not get commit info</div>";
}

echo '</div>'; // End Step 5

// Step 6: Final Status
echo '<div class="box">';
echo '<h2>✅ Update Complete!</h2>';
echo "<div class='success'><strong>🎉 Your website has been updated successfully!</strong></div>";
echo "<p><strong>What to do next:</strong></p>";
echo "<ol>";
echo "<li>Clear your browser cache (Ctrl+F5 or Cmd+Shift+R)</li>";
echo "<li>Visit your website homepage</li>";
echo "<li>Check if logo and text both appear</li>";
echo "<li>Test featured products for blinking border</li>";
echo "<li>Check admin panel for new features</li>";
echo "</ol>";

echo "<p><strong>Quick Links:</strong></p>";
echo "<ul>";
echo "<li><a href='/'>Homepage</a></li>";
echo "<li><a href='/products.php'>Products Page</a></li>";
echo "<li><a href='/admin/'>Admin Panel</a></li>";
echo "<li><a href='/admin/updates.php'>Admin Updates Page</a></li>";
echo "</ul>";

echo "<p style='margin-top: 20px; padding: 15px; background: #fef3c7; border-radius: 5px;'>";
echo "<strong>⚠️ Security Note:</strong> For security, consider deleting this file after use or changing the password at the top of the file.";
echo "</p>";

echo '</div>'; // End Step 6

?>

</body>
</html>