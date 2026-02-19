<?php
/**
 * Simple Email Helper - No PHPMailer Required
 * Uses PHP's built-in mail() function with SMTP settings
 */

function sendEmail($to, $toName, $subject, $body, $isHtml = true) {
    global $pdo;
    
    // Load SMTP settings from database
    $stmt = $pdo->query("SELECT * FROM settings WHERE `key` LIKE 'smtp_%' OR `key` LIKE 'email_%'");
    $settings = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $settings[$row['key']] = $row['value'];
    }
    
    $fromEmail = $settings['email_from_email'] ?? $settings['smtp_user'] ?? 'noreply@khaitanfootwear.in';
    $fromName = $settings['email_from_name'] ?? 'Khaitan Footwear';
    
    // Email headers
    $headers = [];
    $headers[] = "From: {$fromName} <{$fromEmail}>";
    $headers[] = "Reply-To: {$fromEmail}";
    $headers[] = "X-Mailer: PHP/" . phpversion();
    
    if ($isHtml) {
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
    }
    
    // Send email
    return mail($to, $subject, $body, implode("\r\n", $headers));
}

function notifyAdminNewContact($contactData) {
    global $pdo, $settings;
    
    $adminEmail = $settings['site_email'] ?? 'info@khaitanfootwear.in';
    $siteName = $settings['site_name'] ?? 'Khaitan Footwear';
    
    $subject = "New Contact Form Submission - {$siteName}";
    
    $body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
            .header { background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: white; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .field { margin-bottom: 15px; padding: 10px; background: #f5f5f5; border-left: 4px solid #dc2626; }
            .field strong { color: #dc2626; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1 style='margin: 0;'>New Contact Request</h1>
            </div>
            <div class='content'>
                <p>You have received a new contact form submission:</p>
                
                <div class='field'>
                    <strong>Name:</strong> {$contactData['name']}
                </div>
                
                <div class='field'>
                    <strong>Email:</strong> <a href='mailto:{$contactData['email']}'>{$contactData['email']}</a>
                </div>
                
                <div class='field'>
                    <strong>Phone:</strong> {$contactData['phone']}
                </div>
                
                <div class='field'>
                    <strong>Company:</strong> {$contactData['company']}
                </div>
                
                <div class='field'>
                    <strong>Message:</strong><br>
                    {$contactData['message']}
                </div>
                
                <p style='margin-top: 30px; padding: 15px; background: #fef3c7; border-left: 4px solid #f59e0b;'>
                    <strong>Action Required:</strong> Please respond to this inquiry as soon as possible.
                </p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($adminEmail, 'Admin', $subject, $body);
}

function sendContactConfirmation($contactData) {
    global $settings;
    
    $siteName = $settings['site_name'] ?? 'Khaitan Footwear';
    $sitePhone = $settings['site_phone'] ?? '';
    
    $subject = "Thank you for contacting {$siteName}";
    
    $body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
            .header { background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: white; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1 style='margin: 0; font-size: 28px;'>Message Received</h1>
            </div>
            <div class='content'>
                <p style='font-size: 18px;'><strong>Dear {$contactData['name']},</strong></p>
                
                <p>Thank you for reaching out to <strong>{$siteName}</strong>!</p>
                
                <p>We have received your inquiry and our team will review it shortly. One of our representatives will get back to you within 24-48 hours.</p>
                
                <div style='background: #f0f9ff; padding: 20px; border-left: 4px solid #0ea5e9; margin: 20px 0;'>
                    <strong>Your message:</strong><br>
                    <em>{$contactData['message']}</em>
                </div>
                
                <p>If you need immediate assistance, please feel free to call us at <strong>{$sitePhone}</strong>.</p>
                
                <p style='margin-top: 30px;'>Best regards,<br>
                <strong>{$siteName} Team</strong></p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($contactData['email'], $contactData['name'], $subject, $body);
}
?>