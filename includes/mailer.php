<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendEmail($to, $toName, $subject, $body, $isHtml = true) {
    global $pdo;
    
    // Load SMTP settings from database
    $stmt = $pdo->query("SELECT * FROM settings WHERE `key` LIKE 'smtp_%' OR `key` LIKE 'email_%'");
    $settings = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $settings[$row['key']] = $row['value'];
    }
    
    // Check if SMTP is configured
    if (empty($settings['smtp_host']) || empty($settings['smtp_user']) || empty($settings['smtp_pass'])) {
        error_log('SMTP not configured');
        return false;
    }
    
    try {
        $mail = new PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $settings['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $settings['smtp_user'];
        $mail->Password   = $settings['smtp_pass'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $settings['smtp_port'] ?? 587;
        
        // Recipients
        $mail->setFrom(
            $settings['email_from_email'] ?? $settings['smtp_user'], 
            $settings['email_from_name'] ?? 'Khaitan Footwear'
        );
        $mail->addAddress($to, $toName);
        $mail->addReplyTo(
            $settings['email_from_email'] ?? $settings['smtp_user'], 
            $settings['email_from_name'] ?? 'Khaitan Footwear'
        );
        
        // Content
        $mail->isHTML($isHtml);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Email Error: ' . $mail->ErrorInfo);
        return false;
    }
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
            .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1 style='margin: 0;'>🔔 New Contact Request</h1>
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
                    <strong>⚡ Action Required:</strong> Please respond to this inquiry as soon as possible.
                </p>
            </div>
            <div class='footer'>
                <p>This is an automated notification from {$siteName} website.<br>
                Visit admin panel to manage inquiries.</p>
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
            .footer { text-align: center; margin-top: 20px; padding: 20px; background: #f5f5f5; border-radius: 10px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1 style='margin: 0; font-size: 28px;'>✅ Message Received</h1>
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
            <div class='footer'>
                <p style='margin: 0; color: #666;'>This is an automated confirmation email.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($contactData['email'], $contactData['name'], $subject, $body);
}
?>