<?php
/**
 * Database Cleanup Script
 * Use this to completely wipe the database before reinstalling
 * Visit: http://yourdomain.com/cleanup-database.php
 */

if (!file_exists('config.php')) {
    die('<h1>Error: config.php not found</h1><p>Please run install.php first or create config.php manually.</p>');
}

require_once 'config.php';

$confirmed = isset($_POST['confirm']) && $_POST['confirm'] === 'yes';

if ($confirmed) {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Disable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        
        // Get all tables
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        
        $dropped = [];
        foreach ($tables as $table) {
            $pdo->exec("DROP TABLE IF EXISTS `$table`");
            $dropped[] = $table;
        }
        
        // Re-enable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
        
        // Delete config and lock files
        if (file_exists('config.php')) {
            unlink('config.php');
        }
        if (file_exists('installed.lock')) {
            unlink('installed.lock');
        }
        
        $success = true;
        
    } catch (Exception $e) {
        $success = false;
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Database Cleanup - Khaitan Footwear</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-xl mx-auto">
            <div class="bg-white rounded-lg shadow-xl p-8">
                
                <?php if ($confirmed && isset($success)): ?>
                    <?php if ($success): ?>
                        <div class="text-center">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-green-600 mb-4">✅ Database Cleaned!</h1>
                            
                            <div class="bg-green-50 border border-green-200 rounded p-4 mb-6">
                                <p class="font-bold mb-2">Tables Dropped:</p>
                                <ul class="text-sm text-left space-y-1">
                                    <?php foreach ($dropped as $table): ?>
                                    <li>✓ <?= htmlspecialchars($table) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            
                            <div class="bg-blue-50 border border-blue-300 rounded p-4 mb-6">
                                <p class="text-blue-800"><strong>Next Step:</strong> Run the installer to set up a fresh database.</p>
                            </div>
                            
                            <a href="install.php" class="block w-full bg-gradient-to-r from-red-600 to-orange-600 text-white py-3 rounded-lg font-bold hover:shadow-lg transition">
                                🚀 Run Installer Now
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <h1 class="text-3xl font-bold text-red-600 mb-4">❌ Error</h1>
                            <div class="bg-red-50 border border-red-300 rounded p-4 mb-6">
                                <p class="text-red-800"><?= htmlspecialchars($error) ?></p>
                            </div>
                            <a href="cleanup-database.php" class="block w-full bg-gray-800 text-white py-3 rounded-lg font-bold hover:bg-gray-900 transition">
                                ← Try Again
                            </a>
                        </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        
                        <h1 class="text-3xl font-bold text-red-600 mb-4">⚠️ Warning!</h1>
                        
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 text-left">
                            <p class="font-bold text-yellow-800 mb-2">This will:</p>
                            <ul class="list-disc list-inside space-y-1 text-yellow-800">
                                <li>Delete ALL database tables</li>
                                <li>Delete ALL data (products, categories, users, etc.)</li>
                                <li>Remove config.php and installed.lock files</li>
                                <li>Require fresh installation</li>
                            </ul>
                        </div>
                        
                        <div class="bg-red-50 border border-red-300 rounded p-4 mb-6">
                            <p class="text-red-800 font-bold">⚠️ THIS ACTION CANNOT BE UNDONE!</p>
                        </div>
                        
                        <form method="POST" onsubmit="return confirm('Are you ABSOLUTELY sure? All data will be lost!')">
                            <input type="hidden" name="confirm" value="yes">
                            <button type="submit" class="w-full bg-red-600 text-white py-4 rounded-lg font-bold hover:bg-red-700 transition mb-3">
                                🗑️ Yes, Delete Everything
                            </button>
                        </form>
                        
                        <a href="index.php" class="block w-full bg-gray-300 text-gray-700 py-3 rounded-lg font-bold hover:bg-gray-400 transition">
                            ← Cancel, Go Back
                        </a>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</body>
</html>