<?php
require_once 'includes/header.php';
require_once 'includes/email.php';

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
            // Send emails
            $emailService = new EmailService();
            $contact_data = compact('name', 'email', 'phone', 'company', 'message');
            $emailService->sendContactNotification($contact_data);
            $emailService->sendContactConfirmation($contact_data);
            
            $success = true;
        } else {
            $error = 'Failed to send message. Please try again.';
        }
    } else {
        $error = 'Please fill all required fields.';
    }
}
?>

<div class="relative bg-gradient-to-r from-orange-600 to-red-600 text-white py-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-white rounded-full blur-3xl"></div>
    </div>
    <div class="container mx-auto px-6 text-center relative z-10">
        <h1 class="text-5xl font-bold mb-4 animate-fade-in">Contact Us</h1>
        <p class="text-xl">Get in touch with us for bulk orders and inquiries</p>
    </div>
</div>

<div class="container mx-auto px-6 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Contact Form -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 transform hover:scale-105 transition duration-300">
            <h2 class="text-3xl font-bold mb-6 text-gray-800 flex items-center">
                <span class="w-2 h-8 bg-orange-600 mr-3 rounded"></span>
                Send Message
            </h2>
            
            <?php if ($success): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 animate-slide-in">
                <p class="font-bold">✓ Message Sent Successfully!</p>
                <p class="text-sm">We'll get back to you within 24 hours.</p>
            </div>
            <?php elseif ($error): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-5">
                <div class="relative">
                    <input type="text" name="name" required placeholder="Your Name *" class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </span>
                </div>
                <div class="relative">
                    <input type="email" name="email" required placeholder="Email Address *" class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="phone" placeholder="Phone Number" class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                    <input type="text" name="company" placeholder="Company Name" class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                </div>
                <div>
                    <textarea name="message" rows="6" required placeholder="Your Message *" class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition resize-none"></textarea>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition duration-300">
                    Send Message →
                </button>
            </form>
        </div>
        
        <!-- Contact Info Cards -->
        <div class="space-y-6">
            <h2 class="text-3xl font-bold mb-6 text-gray-800">Get In Touch</h2>
            
            <div class="bg-gradient-to-br from-orange-500 to-red-500 text-white p-8 rounded-2xl shadow-xl transform hover:scale-105 transition duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Email Us</h3>
                        <p class="text-orange-100"><?= htmlspecialchars($settings['site_email'] ?? 'info@khaitanfootwear.in') ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-500 to-purple-500 text-white p-8 rounded-2xl shadow-xl transform hover:scale-105 transition duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Call Us</h3>
                        <p class="text-blue-100"><?= htmlspecialchars($settings['site_phone'] ?? '+91 98765 43210') ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-teal-500 text-white p-8 rounded-2xl shadow-xl transform hover:scale-105 transition duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Visit Us</h3>
                        <p class="text-green-100">New Delhi, India</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-8 rounded-2xl shadow-xl">
                <h3 class="font-bold text-xl mb-4 text-gray-800">Business Hours</h3>
                <div class="space-y-2 text-gray-600">
                    <p class="flex justify-between"><span>Monday - Friday:</span> <span class="font-semibold">9:00 AM - 6:00 PM</span></p>
                    <p class="flex justify-between"><span>Saturday:</span> <span class="font-semibold">9:00 AM - 2:00 PM</span></p>
                    <p class="flex justify-between"><span>Sunday:</span> <span class="font-semibold text-red-600">Closed</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes slide-in {
    from { opacity: 0; transform: translateX(-20px); }
    to { opacity: 1; transform: translateX(0); }
}
.animate-fade-in { animation: fade-in 0.6s ease-out; }
.animate-slide-in { animation: slide-in 0.5s ease-out; }
</style>

<?php require_once 'includes/footer.php'; ?>