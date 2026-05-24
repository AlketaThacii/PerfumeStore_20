<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendVerificationCode($toEmail, $toName, $code)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

       
        $mail->Username = 'arjanitalestrani15@gmail.com';

$mail->Password = 'ntce uxqy cbgq cebf';

$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom('arjanitalestrani15@gmail.com', 'Maison De Parfum');

      
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = 'Perfume Store Email Verification';

        $safeName = htmlspecialchars($toName, ENT_QUOTES, 'UTF-8');

        $mail->Body = "
            <div style='max-width:500px;margin:auto;padding:30px;font-family:Arial,sans-serif;border:1px solid #d4af37;border-radius:12px;'>
                <h2 style='color:#d4af37;'>Perfume Store Verification</h2>
                <p>Hello <b>$safeName</b>,</p>
                <p>Your verification code is:</p>
                <div style='font-size:35px;font-weight:bold;letter-spacing:8px;color:#d4af37;margin:20px 0;'>
                    $code
                </div>
                <p>This code expires in 5 minutes.</p>
                <p>If this was not you, ignore this email.</p>
            </div>
        ";

        $mail->AltBody = "Your verification code is: $code";

        return $mail->send();

    } catch (Exception $e) {
        echo "Email Error: " . $mail->ErrorInfo;
        return false;
    }
}