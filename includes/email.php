<?php
// SMTP Email Configuration
class EmailService {
    private $smtp_host;
    private $smtp_port;
    private $smtp_user;
    private $smtp_pass;
    private $from_email;
    private $from_name;
    
    public function __construct() {
        $pdo = $GLOBALS['pdo'];
        $settings = $pdo->query("SELECT * FROM settings WHERE `key` LIKE 'smtp_%' OR `key` LIKE 'email_%'")->fetchAll();
        foreach ($settings as $s) {
            $this->{$s['key']} = $s['value'];
        }
    }
    
    public function sendEmail($to, $subject, $body, $html = true) {
        $headers = "From: {$this->from_name} <{$this->from_email}>\r\n";
        $headers .= "Reply-To: {$this->from_email}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        if ($html) {
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        }
        
        // Use PHP mail() for simplicity, can be upgraded to PHPMailer
        return mail($to, $subject, $body, $headers);
    }
    
    public function sendContactNotification($contact) {
        $subject = "New Contact Inquiry from {$contact['name']}";
        $body = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <h2 style='color: #ea580c;'>New Contact Inquiry</h2>
            <p><strong>Name:</strong> {$contact['name']}</p>
            <p><strong>Email:</strong> {$contact['email']}</p>
            <p><strong>Phone:</strong> {$contact['phone']}</p>
            <p><strong>Company:</strong> {$contact['company']}</p>
            <p><strong>Message:</strong></p>
            <p style='background: #f5f5f5; padding: 15px; border-left: 4px solid #ea580c;'>{$contact['message']}</p>
            <p style='color: #666; font-size: 12px; margin-top: 30px;'>This is an automated message from Khaitan Footwear website.</p>
        </body>
        </html>
        ";
        
        return $this->sendEmail($this->smtp_user, $subject, $body);
    }
    
    public function sendContactConfirmation($contact) {
        $subject = "Thank you for contacting Khaitan Footwear";
        $body = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <h2 style='color: #ea580c;'>Thank You for Your Inquiry!</h2>
            <p>Dear {$contact['name']},</p>
            <p>We have received your message and will get back to you shortly.</p>
            <p><strong>Your Message:</strong></p>
            <p style='background: #f5f5f5; padding: 15px; border-left: 4px solid #ea580c;'>{$contact['message']}</p>
            <p>Best regards,<br><strong>Khaitan Footwear Team</strong></p>
        </body>
        </html>
        ";
        
        return $this->sendEmail($contact['email'], $subject, $body);
    }
}
?>