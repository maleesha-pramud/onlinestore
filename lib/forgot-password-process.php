<?php

include '../includes/connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "../mail/PHPMailer.php";
require "../mail/SMTP.php";
require "../mail/Exception.php";

$email = $_POST['email'];
 
if (empty($email)) {
    echo ("Email is required");
} else if (!Validation::validateEmail($email)) {
    echo ("Email is not valid");
} else {
    $rs = Database::search("SELECT * FROM `users` WHERE `email` = '$email'");
    if ($rs->num_rows == 0) {
        echo ("User not found!");
        return;
    } else {

        $row = $rs->fetch_assoc();
        $vcode = uniqid();

        Database::iud("UPDATE `users` SET `vcode`='$vcode' WHERE `id` = '" . $row['id'] . "'");

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'maleeshapramud2005@gmail.com';
            $mail->Password = 'fjzzgfykipipsabf';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('maleeshapramud9@gmail.com', 'Maleesha Pramud 9');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Reset your account password';
            $mail->Body = '
            <table style="width: 100%; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; padding: 40px 20px; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td align="center">
                            <table style="max-width: 540px; width: 100%; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05); border: 1px solid #e2e8f0; border-collapse: collapse;">
                                <tbody>
                                    <!-- Header -->
                                    <tr>
                                        <td style="padding: 32px 32px 20px 32px; text-align: center; border-bottom: 1px solid #f1f5f9;">
                                            <a href="#" style="font-size: 26px; color: #0f172a; font-weight: 800; text-decoration: none; letter-spacing: -0.5px;">2nd <span style="color: #0284c7;">Home</span></a>
                                        </td>
                                    </tr>
                                    <!-- Content -->
                                    <tr>
                                        <td style="padding: 40px 32px 32px 32px;">
                                            <h1 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 16px; text-align: left;">Reset Your Password</h1>
                                            <p style="font-size: 15px; line-height: 1.6; color: #334155; margin: 0 0 24px 0; text-align: left;">We received a request to reset your password for your 2nd Home account. Click the button below to proceed.</p>
                                            
                                            <!-- Button -->
                                            <div style="text-align: center; margin: 32px 0;">
                                                <a href="http://localhost/reset-password.php?code=' . $vcode . '" style="display: inline-block; background-color: #0284c7; color: #ffffff; border-radius: 8px; padding: 14px 30px; text-decoration: none; font-weight: 600; font-size: 15px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);">
                                                    Reset Password
                                                </a>
                                            </div>
                                            
                                            <p style="font-size: 13px; line-height: 1.6; color: #64748b; margin: 24px 0 16px 0; text-align: left;">If you didn\'t request a password reset, you can safely ignore this email. Your password will remain secure and unchanged.</p>
                                            
                                            <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 24px 0;">
                                            
                                            <!-- Fallback link -->
                                            <p style="font-size: 12px; line-height: 1.5; color: #94a3b8; margin: 0; text-align: left; word-break: break-all;">
                                                If you\'re having trouble clicking the button, copy and paste this URL into your browser: <br>
                                                <a href="http://localhost/reset-password.php?code=' . $vcode . '" style="color: #0284c7; text-decoration: none;">http://localhost/reset-password.php?code=' . $vcode . '</a>
                                            </p>
                                        </td>
                                    </tr>
                                    <!-- Footer -->
                                    <tr>
                                        <td style="padding: 24px 32px 32px 32px; background-color: #f8fafc; border-top: 1px solid #f1f5f9; text-align: center;">
                                            <p style="font-size: 12px; color: #94a3b8; margin: 0;">&copy; 2026 2nd Home. All rights reserved.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            ';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
