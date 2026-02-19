<?php
require_once 'includes/header.php';

// Try to load simple mailer
$emailEnabled = false;
if (file_exists('../includes/mailer-simple.php')) {
    require_once '../includes/mailer-simple.php';
    $emailEnabled = true;
}

$success = '';
$error = '';

if (isset($_POST['update_status'])) {
    $pdo->prepare("UPDATE contacts SET status = ? WHERE id = ?")->execute([$_POST['status'], $_POST['contact_id']]);
    $success = 'Status updated';
}

if (isset($_POST['send_reply']) && $emailEnabled) {
    $contact_id = $_POST['contact_id'];
    $reply_message = $_POST['reply_message'];
    $subject = $_POST['subject'];
    
    // Get contact details
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = ?");
    $stmt->execute([$contact_id]);
    $contact = $stmt->fetch();
    
    if ($contact) {
        $siteName = $settings['site_name'] ?? 'Khaitan Footwear';
        $sitePhone = $settings['site_phone'] ?? '';
        $siteEmail = $settings['site_email'] ?? '';
        
        // Create HTML email
        $emailBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
                .header { background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: white; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .message-box { background: #f0f9ff; padding: 20px; border-left: 4px solid #0ea5e9; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1 style='margin: 0; font-size: 28px;'>{$siteName}</h1>
                    <p style='margin: 10px 0 0 0;'>Response to Your Inquiry</p>
                </div>
                <div class='content'>
                    <p style='font-size: 18px;'><strong>Dear {$contact['name']},</strong></p>
                    
                    <p>Thank you for reaching out to us. Here's our response to your inquiry:</p>
                    
                    <div class='message-box'>
                        " . nl2br(htmlspecialchars($reply_message)) . "
                    </div>
                    
                    <p>If you have any further questions, please don't hesitate to contact us:</p>
                    <ul>
                        <li><strong>Email:</strong> {$siteEmail}</li>
                        <li><strong>Phone:</strong> {$sitePhone}</li>
                    </ul>
                    
                    <p style='margin-top: 30px;'>Best regards,<br>
                    <strong>{$siteName} Team</strong></p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Send email
        if (sendEmail($contact['email'], $contact['name'], $subject, $emailBody)) {
            // Update status to replied
            $pdo->prepare("UPDATE contacts SET status = 'replied' WHERE id = ?")->execute([$contact_id]);
            $success = '✅ Reply sent successfully to ' . htmlspecialchars($contact['email']);
        } else {
            $error = '❌ Failed to send email. Please check your server mail configuration.';
        }
    }
}

$contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">📬 Contact Inquiries</h1>

<?php if ($success): ?>
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
    <?= $success ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
    <?= $error ?>
</div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow overflow-hidden">
<table class="w-full">
<thead class="bg-gray-50">
<tr>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-200">
<?php foreach ($contacts as $c): ?>
<tr>
<td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($c['name']) ?></td>
<td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($c['email']) ?></td>
<td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($c['phone']) ?></td>
<td class="px-6 py-4 text-gray-600"><?= date('d M Y', strtotime($c['created_at'])) ?></td>
<td class="px-6 py-4">
<form method="POST" class="inline">
<input type="hidden" name="contact_id" value="<?= $c['id'] ?>">
<select name="status" onchange="this.form.submit()" class="px-2 py-1 text-xs font-semibold rounded-full border-0 <?= $c['status'] == 'new' ? 'bg-blue-100 text-blue-800' : ($c['status'] == 'read' ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800') ?>">
<option value="new" <?= $c['status'] == 'new' ? 'selected' : '' ?>>New</option>
<option value="read" <?= $c['status'] == 'read' ? 'selected' : '' ?>>Read</option>
<option value="replied" <?= $c['status'] == 'replied' ? 'selected' : '' ?>>Replied</option>
</select>
<input type="hidden" name="update_status" value="1">
</form>
</td>
<td class="px-6 py-4 space-x-2">
<button onclick="viewMessage(<?= htmlspecialchars(json_encode($c)) ?>)" class="text-blue-600 hover:text-blue-800 font-semibold">👁️ View</button>
<?php if ($emailEnabled): ?>
<button onclick="showReplyForm(<?= htmlspecialchars(json_encode($c)) ?>)" class="text-green-600 hover:text-green-800 font-semibold">✉️ Reply</button>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<!-- View Message Modal -->
<div id="messageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="if(event.target === this) this.classList.add('hidden')">
<div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4">
<h2 class="text-2xl font-bold mb-4" id="modal_name"></h2>
<div class="space-y-2 mb-4">
<p><strong>Email:</strong> <a href="#" id="modal_email" class="text-blue-600"></a></p>
<p><strong>Phone:</strong> <span id="modal_phone"></span></p>
<p><strong>Company:</strong> <span id="modal_company"></span></p>
<p><strong>Date:</strong> <span id="modal_date"></span></p>
</div>
<div class="bg-gray-50 p-4 rounded mb-4">
<strong>Message:</strong>
<p id="modal_message" class="mt-2 whitespace-pre-wrap"></p>
</div>
<button onclick="document.getElementById('messageModal').classList.add('hidden')" class="bg-orange-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-700">Close</button>
</div>
</div>

<?php if ($emailEnabled): ?>
<!-- Reply Modal -->
<div id="replyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="if(event.target === this) this.classList.add('hidden')">
<div class="bg-white rounded-lg p-8 max-w-3xl w-full mx-4 max-h-screen overflow-y-auto">
<h2 class="text-2xl font-bold mb-4">✉️ Reply to Inquiry</h2>

<div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
<p><strong>To:</strong> <span id="reply_to_name"></span> (<span id="reply_to_email"></span>)</p>
<p class="text-sm text-gray-600 mt-2"><strong>Original Message:</strong></p>
<p id="reply_original_message" class="text-sm text-gray-700 mt-1 italic"></p>
</div>

<form method="POST" class="space-y-4">
<input type="hidden" name="contact_id" id="reply_contact_id">

<div>
<label class="block font-medium mb-2">Email Subject</label>
<input type="text" name="subject" id="reply_subject" required class="w-full px-4 py-3 border rounded-lg" placeholder="Re: Your inquiry about...">
</div>

<div>
<label class="block font-medium mb-2">Your Reply Message</label>
<textarea name="reply_message" required rows="8" class="w-full px-4 py-3 border rounded-lg" placeholder="Type your reply here..."></textarea>
<p class="text-sm text-gray-500 mt-1">This will be sent via email to the customer</p>
</div>

<div class="flex gap-4">
<button type="submit" name="send_reply" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700">
📤 Send Reply Email
</button>
<button type="button" onclick="document.getElementById('replyModal').classList.add('hidden')" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300">
Cancel
</button>
</div>
</form>
</div>
</div>
<?php endif; ?>

<script>
function viewMessage(contact) {
    document.getElementById('modal_name').textContent = contact.name;
    document.getElementById('modal_email').textContent = contact.email;
    document.getElementById('modal_email').href = 'mailto:' + contact.email;
    document.getElementById('modal_phone').textContent = contact.phone || 'N/A';
    document.getElementById('modal_company').textContent = contact.company || 'N/A';
    document.getElementById('modal_date').textContent = new Date(contact.created_at).toLocaleDateString();
    document.getElementById('modal_message').textContent = contact.message;
    document.getElementById('messageModal').classList.remove('hidden');
}

<?php if ($emailEnabled): ?>
function showReplyForm(contact) {
    document.getElementById('reply_contact_id').value = contact.id;
    document.getElementById('reply_to_name').textContent = contact.name;
    document.getElementById('reply_to_email').textContent = contact.email;
    document.getElementById('reply_original_message').textContent = contact.message;
    document.getElementById('reply_subject').value = 'Re: Your inquiry at Khaitan Footwear';
    document.getElementById('replyModal').classList.remove('hidden');
}
<?php endif; ?>
</script>

<?php require_once 'includes/footer.php'; ?>