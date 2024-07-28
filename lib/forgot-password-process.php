<?php

include 'connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "mail/PHPMailer.php";
require "mail/SMTP.php";
require "mail/Exception.php";

$email = $_POST['email'];

if (empty($email)) {
    echo ("Email is required");
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
            <table style="width: 100%; font-family: sans-serif;">
                <tbody>
                    <tr>
                    <td align="center">
                        <table style="max-width: 600px;">
                        <tr>
                            <td align="center">
                            <a href="#" style="font-size: 35px; color: black; font-weight: bold; text-decoration: none;">Online Store</a>
                            <hr>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <h1 style="font-size: 25px; font-weight: bold; line-height: 45px;">Reset Your Password</h1>
                            <p style="margin-bottom: 25px;">You can reset your password by clicking this button</p>
                            <div>
                                <a href="http://localhost/onlinestore/reset-password.php?code='.$vcode.'" style="display: inline-block; background-color: blue; color: white; border-radius: 8px; padding: 15px; text-decoration: none;">
                                    <span>Reset Password</span>
                                </a>
                            </div>
                            <p>If you didn\'t requested to reset password, please ignore this email </p>
                            <hr>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <p style="font-size: 15px; color: gray;">&copy; 2024 Online Store. All rights reserved.</p>
                            </td>
                        </tr>
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
