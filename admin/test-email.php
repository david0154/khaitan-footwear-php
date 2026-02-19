<?php
require_once 'includes/header.php';
require_once '../includes/mailer.php';

$success = '';
$error = '';
$details = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $testEmail = $_POST['test_email'] ?? '';
    
    if (empty($testEmail)) {
        $error = 'Please enter an email address';
    } else {
        $subject = 'Test Email from Khaitan Footwear';
        $body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
                .header { background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: white; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .success-box { background: #d1fae5; padding: 20px; border-left: 4px solid #10b981; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1 style='margin: 0; font-size: 32px;'>✅ Test Email</h1>
                </div>
                <div class='content'>
                    <div class='success-box'>
                        <h2 style='color: #059669; margin-top: 0;'>🎉 Congratulations!</h2>
                        <p><strong>Your SMTP email configuration is working perfectly!</strong></p>
                    </div>
                    
                    <p>This is a test email from your Khaitan Footwear website.</p>
                    
                    <p>If you're seeing this email, it means:</p>
                    <ul>
                        <li>✅ SMTP settings are configured correctly</li>
                        <li>✅ PHPMailer is installed and working</li>
                        <li>✅ Email delivery is functioning</li>
                    </ul>
                    
                    <p>You can now send:</p>
                    <ul>
                        <li>📧 Contact form notifications to admin</li>
                        <li>👋 Welcome emails to customers</li>
                        <li>💬 Reply emails from admin panel</li>
                    </ul>
                    
                    <p style='margin-top: 30px; padding: 15px; background: #fef3c7; border-left: 4px solid #f59e0b;'>
                        <strong>Test Details:</strong><br>
                        Sent at: " . date('Y-m-d H:i:s') . "<br>
                        From: Khaitan Footwear Email System
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        try {
            if (sendEmail($testEmail, 'Test User', $subject, $body)) {
                $success = '✅ Test email sent successfully to ' . htmlspecialchars($testEmail) . '! Check your inbox (and spam folder).';
                
                // Get SMTP settings for display
                $stmt = $pdo->query("SELECT * FROM settings WHERE `key` LIKE 'smtp_%' OR `key` LIKE 'email_%'");
                $smtp_settings = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $smtp_settings[$row['key']] = $row['value'];
                }
                
                $details = '<div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-4">';
                $details .= '<h3 class="font-bold text-blue-800 mb-2">🔧 SMTP Configuration Used:</h3>';
                $details .= '<ul class="text-sm text-blue-700 space-y-1">';
                $details .= '<li><strong>Host:</strong> ' . htmlspecialchars($smtp_settings['smtp_host'] ?? 'Not set') . '</li>';
                $details .= '<li><strong>Port:</strong> ' . htmlspecialchars($smtp_settings['smtp_port'] ?? 'Not set') . '</li>';
                $details .= '<li><strong>Username:</strong> ' . htmlspecialchars($smtp_settings['smtp_user'] ?? 'Not set') . '</li>';
                $details .= '<li><strong>From Name:</strong> ' . htmlspecialchars($smtp_settings['email_from_name'] ?? 'Not set') . '</li>';
                $details .= '<li><strong>From Email:</strong> ' . htmlspecialchars($smtp_settings['email_from_email'] ?? 'Not set') . '</li>';
                $details .= '</ul></div>';
            } else {
                $error = '❌ Failed to send test email. Please check your SMTP settings.';
            }
        } catch (Exception $e) {
            $error = '❌ Error: ' . $e->getMessage();
        }
    }
}

// Get current SMTP settings
$stmt = $pdo->query("SELECT * FROM settings WHERE `key` LIKE 'smtp_%' OR `key` LIKE 'email_%'");
$currentSettings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $currentSettings[$row['key']] = $row['value'];
}

$isConfigured = !empty($currentSettings['smtp_host']) && !empty($currentSettings['smtp_user']) && !empty($currentSettings['smtp_pass']);
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">🧪 Test Email Configuration</h1>
    <p class="text-gray-600">Verify your SMTP settings by sending a test email</p>
</div>

<?php if ($success): ?>
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
    <?= $success ?>
    <?= $details ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
    <?= $error ?>
    <p class="mt-2 text-sm">Common issues:</p>
    <ul class="text-sm list-disc ml-5 mt-1">
        <li>SMTP credentials are incorrect</li>
        <li>Port is blocked by firewall</li>
        <li>Gmail: Need to enable "Less secure apps" or use App Password</li>
        <li>Check if vendor/autoload.php exists (PHPMailer installed)</li>
    </ul>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Test Form -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6">📨 Send Test Email</h2>
        
        <?php if (!$isConfigured): ?>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
            <p class="font-bold">⚠️ SMTP Not Configured</p>
            <p class="text-sm mt-1">Please configure SMTP settings first.</p>
            <a href="email-settings.php" class="text-blue-600 hover:underline text-sm">Go to Email Settings →</a>
        </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-4">
            <div>
                <label class="block font-medium mb-2">Send Test Email To:</label>
                <input type="email" name="test_email" required placeholder="your-email@example.com" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Enter your email address to receive the test email</p>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition" <?= !$isConfigured ? 'disabled' : '' ?>>
                🚀 Send Test Email
            </button>
        </form>
    </div>
    
    <!-- Current Settings Display -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6">⚙️ Current SMTP Settings</h2>
        
        <?php if ($isConfigured): ?>
        <div class="space-y-3">
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Host:</span>
                <span class="font-semibold"><?= htmlspecialchars($currentSettings['smtp_host'] ?? 'Not set') ?></span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Port:</span>
                <span class="font-semibold"><?= htmlspecialchars($currentSettings['smtp_port'] ?? 'Not set') ?></span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Username:</span>
                <span class="font-semibold text-sm"><?= htmlspecialchars($currentSettings['smtp_user'] ?? 'Not set') ?></span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">From Name:</span>
                <span class="font-semibold"><?= htmlspecialchars($currentSettings['email_from_name'] ?? 'Not set') ?></span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">From Email:</span>
                <span class="font-semibold text-sm"><?= htmlspecialchars($currentSettings['email_from_email'] ?? 'Not set') ?></span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-600">Password:</span>
                <span class="font-semibold text-green-600">✅ Set (Hidden)</span>
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-green-50 rounded-lg">
            <p class="text-sm text-green-800">✅ <strong>SMTP is configured</strong></p>
            <p class="text-xs text-green-700 mt-1">Ready to send emails</p>
        </div>
        <?php else: ?>
        <div class="p-6 bg-red-50 rounded-lg text-center">
            <p class="text-red-800 font-semibold">❌ SMTP Not Configured</p>
            <p class="text-sm text-red-600 mt-2">Please set up your email settings first</p>
        </div>
        <?php endif; ?>
        
        <a href="email-settings.php" class="block mt-6 text-center bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-300 transition">
            ⚙️ Edit Email Settings
        </a>
    </div>
</div>

<!-- How to Configure Guide -->
<div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">📘 How to Configure Gmail SMTP</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-bold text-lg mb-2">Step 1: Enable App Password</h3>
            <ol class="list-decimal ml-5 space-y-1 text-sm text-gray-700">
                <li>Go to Google Account settings</li>
                <li>Security → 2-Step Verification (enable it)</li>
                <li>App passwords → Generate new password</li>
                <li>Copy the 16-character password</li>
            </ol>
        </div>
        <div>
            <h3 class="font-bold text-lg mb-2">Step 2: Configure Settings</h3>
            <ul class="list-disc ml-5 space-y-1 text-sm text-gray-700">
                <li><strong>Host:</strong> smtp.gmail.com</li>
                <li><strong>Port:</strong> 587</li>
                <li><strong>Username:</strong> your-email@gmail.com</li>
                <li><strong>Password:</strong> App password (16 chars)</li>
            </ul>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>