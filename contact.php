<?php
require_once 'includes/header.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $company = $_POST['company'] ?? '';
    $message = $_POST['message'] ?? '';
    
    if ($name && $email && $message) {
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone, company, message) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $phone, $company, $message])) {
            $success = true;
        } else {
            $error = 'Failed to send message. Please try again.';
        }
    } else {
        $error = 'Please fill all required fields.';
    }
}
?>

<div class="bg-gradient-to-r from-orange-600 to-red-600 text-white py-12">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl font-bold">Contact Us</h1>
        <p class="text-xl mt-2">Get in touch with us for bulk orders and inquiries</p>
    </div>
</div>

<div class="container mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Contact Form -->
        <div>
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Send us a Message</h2>
            
            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                Thank you! Your message has been sent successfully.
            </div>
            <?php elseif ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block font-medium mb-2 text-gray-700">Name *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block font-medium mb-2 text-gray-700">Email *</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block font-medium mb-2 text-gray-700">Phone</label>
                    <input type="text" name="phone" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block font-medium mb-2 text-gray-700">Company</label>
                    <input type="text" name="company" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block font-medium mb-2 text-gray-700">Message *</label>
                    <textarea name="message" rows="5" required class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-orange-500"></textarea>
                </div>
                <button type="submit" class="bg-orange-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-orange-700">Send Message</button>
            </form>
        </div>
        
        <!-- Contact Info -->
        <div>
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Contact Information</h2>
            <div class="bg-white p-6 rounded-lg shadow space-y-4">
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Email</h3>
                        <p class="text-gray-600"><?= htmlspecialchars($settings['site_email'] ?? 'info@khaitanfootwear.in') ?></p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Phone</h3>
                        <p class="text-gray-600"><?= htmlspecialchars($settings['site_phone'] ?? '+91 98765 43210') ?></p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Address</h3>
                        <p class="text-gray-600">New Delhi, India</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>