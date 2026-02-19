<?php
require_once 'includes/header.php';

// Try to load simple mailer, if it fails, just save to database
$emailEnabled = false;
if (file_exists('includes/mailer-simple.php')) {
    require_once 'includes/mailer-simple.php';
    $emailEnabled = true;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $company = $_POST['company'] ?? '';
    $message = $_POST['message'] ?? '';
    
    try {
        // Save to database
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone, company, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $company, $message]);
        
        if ($emailEnabled) {
            // Prepare contact data
            $contactData = [
                'name' => htmlspecialchars($name),
                'email' => htmlspecialchars($email),
                'phone' => htmlspecialchars($phone),
                'company' => htmlspecialchars($company),
                'message' => nl2br(htmlspecialchars($message))
            ];
            
            // Send email notification to admin
            $adminNotified = notifyAdminNewContact($contactData);
            
            // Send confirmation email to customer  
            $customerNotified = sendContactConfirmation($contactData);
            
            if ($adminNotified && $customerNotified) {
                $success = '✅ Thank you! Your message has been sent successfully. We will contact you soon. Check your email for confirmation.';
            } elseif ($adminNotified) {
                $success = '✅ Thank you! Your message has been sent to our team. We will contact you soon.';
            } else {
                $success = '✅ Thank you! Your message has been saved. We will contact you soon.';
            }
        } else {
            $success = '✅ Thank you! Your message has been saved. We will contact you soon.';
        }
        
    } catch (Exception $e) {
        error_log('Contact form error: ' . $e->getMessage());
        $error = 'Sorry, there was an error sending your message. Please try again or call us directly.';
    }
}

$site_phone = $settings['site_phone'] ?? '+91 98765 43210';
$site_phone_2 = $settings['site_phone_2'] ?? '';
$site_email = $settings['site_email'] ?? 'info@khaitanfootwear.in';
$site_email_sales = $settings['site_email_sales'] ?? '';
$site_address = $settings['site_address'] ?? 'Industrial Area, Phase 2';
$site_city = $settings['site_city'] ?? 'New Delhi';
$site_state = $settings['site_state'] ?? 'Delhi';
$site_pincode = $settings['site_pincode'] ?? '110001';
$site_country = $settings['site_country'] ?? 'India';
?>

<div class="bg-gradient-to-r from-red-600 to-red-800 text-white py-16">
    <div class="container mx-auto px-6">
        <h1 class="text-5xl font-bold mb-4">Contact Us</h1>
        <p class="text-2xl text-red-100">Get in touch with us for any inquiries</p>
    </div>
</div>

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Phone -->
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-2xl transition">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Phone</h3>
                <p class="text-gray-600 text-lg mb-2"><?= htmlspecialchars($site_phone) ?></p>
                <?php if ($site_phone_2): ?>
                <p class="text-gray-600 text-lg"><?= htmlspecialchars($site_phone_2) ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Email -->
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-2xl transition">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Email</h3>
                <p class="text-gray-600 text-lg mb-2"><?= htmlspecialchars($site_email) ?></p>
                <?php if ($site_email_sales): ?>
                <p class="text-gray-600 text-lg"><?= htmlspecialchars($site_email_sales) ?></p>
                <p class="text-sm text-gray-500">(Sales Inquiries)</p>
                <?php endif; ?>
            </div>
            
            <!-- Address -->
            <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-2xl transition">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Address</h3>
                <p class="text-gray-600 text-lg leading-relaxed">
                    <?= nl2br(htmlspecialchars($site_address)) ?><br>
                    <?= htmlspecialchars($site_city) ?>, <?= htmlspecialchars($site_state) ?> - <?= htmlspecialchars($site_pincode) ?><br>
                    <?= htmlspecialchars($site_country) ?>
                </p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="bg-white rounded-xl shadow-xl p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                
                <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?= $success ?>
                </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    ❌ <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-2">Your Name *</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="John Doe">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="john@example.com">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="+91 98765 43210">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block font-semibold text-gray-700 mb-2">Company Name</label>
                        <input type="text" name="company" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Your Company">
                    </div>
                    
                    <div>
                        <label class="block font-semibold text-gray-700 mb-2">Message *</label>
                        <textarea name="message" required rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Tell us about your requirements..."></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-800 text-white py-4 rounded-lg text-lg font-bold hover:shadow-lg transition transform hover:scale-105">
                        📤 Send Message
                    </button>
                </form>
            </div>
            
            <!-- Business Hours & Map -->
            <div class="space-y-8">
                <div class="bg-white rounded-xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Business Hours</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700">Monday - Friday</span>
                            <span class="text-gray-600">9:00 AM - 6:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700">Saturday</span>
                            <span class="text-gray-600">9:00 AM - 2:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700">Sunday</span>
                            <span class="text-red-600 font-semibold">Closed</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Visit Our Factory</h3>
                    <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d224345.83923192776!2d77.06889754725782!3d28.52758200617607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd5b347eb62d%3A0x52c2b7494e204dce!2sNew%20Delhi%2C%20Delhi!5e0!3m2!1sen!2sin!4v1645000000000!5m2!1sen!2sin" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>