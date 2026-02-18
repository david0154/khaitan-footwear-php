<?php
/**
 * Auto-Update Cron Script
 * Add to crontab: * * * * * php /path/to/auto-update.php
 * Runs every minute to check for updates
 */

require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    
    // Check if auto-update is enabled
    $stmt = $pdo->query("SELECT value FROM settings WHERE `key` = 'auto_update_enabled'");
    $autoUpdateEnabled = $stmt->fetchColumn();
    
    if ($autoUpdateEnabled !== '1') {
        exit('Auto-update is disabled');
    }
    
    // Check last update time (don't update more than once per hour)
    $stmt = $pdo->query("SELECT value FROM settings WHERE `key` = 'last_update_check'");
    $lastCheck = $stmt->fetchColumn();
    
    if ($lastCheck && (time() - strtotime($lastCheck)) < 3600) {
        exit('Already checked recently');
    }
    
    // Update last check time
    $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('last_update_check', ?) ON DUPLICATE KEY UPDATE value = ?");
    $now = date('Y-m-d H:i:s');
    $stmt->execute([$now, $now]);
    
    // Check for updates
    $githubOwner = 'david0154';
    $githubRepo = 'khaitan-footwear-php';
    $githubBranch = 'main';
    
    $url = "https://api.github.com/repos/{$githubOwner}/{$githubRepo}/commits/{$githubBranch}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Khaitan-Footwear-Updater');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    if (!$response) {
        exit('Failed to check GitHub');
    }
    
    $latestCommit = json_decode($response, true);
    $latestVersion = substr($latestCommit['sha'], 0, 7);
    
    // Get current version
    $currentVersion = file_exists('VERSION') ? trim(file_get_contents('VERSION')) : '1.0.0';
    
    if ($currentVersion === $latestVersion) {
        exit('Already up to date');
    }
    
    // Create backup
    $backupDir = 'backups/backup_' . date('Y-m-d_H-i-s');
    if (!file_exists('backups')) {
        mkdir('backups', 0755, true);
    }
    
    exec("cp -r . {$backupDir}", $output, $return_code);
    
    if ($return_code !== 0) {
        exit('Backup failed');
    }
    
    // Pull updates
    $repoPath = realpath('.');
    chdir($repoPath);
    
    exec('git reset --hard HEAD 2>&1', $output1, $return1);
    exec('git pull origin main 2>&1', $output2, $return2);
    
    if ($return2 === 0) {
        // Update version
        file_put_contents('VERSION', $latestVersion);
        
        // Log update
        $stmt = $pdo->prepare("INSERT INTO settings (`key`, value) VALUES ('last_update', ?) ON DUPLICATE KEY UPDATE value = ?");
        $updateTime = date('Y-m-d H:i:s');
        $stmt->execute([$updateTime, $updateTime]);
        
        echo "Updated successfully to {$latestVersion}\n";
    } else {
        echo "Update failed: " . implode("\n", $output2) . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
