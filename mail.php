<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer-7.1.1/src/Exception.php';
require_once __DIR__ . '/../PHPMailer-7.1.1/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-7.1.1/src/SMTP.php';
require_once __DIR__ . '/constants.php';



// SMTP CONFIG (Better: move to .env later)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'helpdesk0699@gmail.com');
define('SMTP_PASS', 'jrui cacs undf edcg'); // DO NOT expose in GitHub

define('SMTP_FROM', 'helpdesk0699@gmail.com'); // must match Gmail
define('SMTP_FROM_NAME', 'VendorBridge ERP');

function sendEmail($to, $subject, $body, $isHTML = true) {

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        // IMPORTANT (fix encoding issues)
        $mail->CharSet = 'UTF-8';

        // Recipients
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($to);

        // Content
        $mail->isHTML($isHTML);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        $mail->send();

        return [
            'success' => true,
            'message' => 'Email sent successfully'
        ];

    } catch (Exception $e) {

        error_log("Email failed: " . $mail->ErrorInfo);

        return [
            'success' => false,
            'message' => $mail->ErrorInfo
        ];
    }
}

function sendOTPEmail($email, $name, $otp) {

    $subject = "Verify Your " . APP_NAME . " Account";

    $body = '
    <html>
    <body>
        <h2>' . APP_NAME . '</h2>
        <p>Dear ' . htmlspecialchars($name) . ',</p>

        <p>Your OTP is:</p>

        <h1 style="color:#667eea;">' . $otp . '</h1>

        <p>This OTP is valid for 10 minutes.</p>

        <p>If this was not you, ignore this email.</p>

        <br>
        <small>© ' . date('Y') . ' ' . APP_NAME . '</small>
    </body>
    </html>';

    return sendEmail($email, $subject, $body, true);
}
?>