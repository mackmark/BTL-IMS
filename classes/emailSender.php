<?php
require '../../vendor/autoload.php'; // Adjust the path as needed
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class emailSender{

    public function sendEmail($username, $password, $setFromAddress, $subject, array $addresses, $htmlBody){
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Set to SMTP::DEBUG_SERVER for debugging
        $mail->isSMTP();
        $mail->Host = 'smtp-mail.outlook.com'; // SMTP server (replace with your server)
        $mail->SMTPAuth = true;
        $mail->Username = $username; // Your email address
        $mail->Password = $password; // Your email password or app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
        $mail->Port = 587; // TCP port to connect to (587 for TLS, 465 for SSL)
        
        $mail->setFrom($setFromAddress, 'BTL IMS NOTIFICATION');
        foreach($addresses as $addres){
            $mail->addAddress($addres, 'Recipient Name');
        }
        
        $mail->Subject = $subject;
        $mail->isHTML(true); // Set email format to HTML
        $mail->Body = $htmlBody;

        try {
            $mail->send();
            // echo 'Email sent successfully.';
        } catch (Exception $e) {
            // echo "Email sending failed: {$mail->ErrorInfo}";
        }
    }
}

?>