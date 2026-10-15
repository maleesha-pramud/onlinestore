<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once dirname(__FILE__) . '/../mail/PHPMailer.php';
require_once dirname(__FILE__) . '/../mail/SMTP.php';
require_once dirname(__FILE__) . '/../mail/Exception.php';

class Mailer {

    // Site address used in e-mail links (falls back to localhost)
    public static function baseUrl() {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        if (!preg_match('/^[A-Za-z0-9.\-]+(:[0-9]{1,5})?$/', $host)) {
            $host = 'localhost';
        }
        return 'http://' . $host;
    }

    // Sends one HTML e-mail wrapped in the site template. Never throws: returns true / false
    // $button is optional: ['text' => 'Open', 'url' => 'http://...']
    public static function send($to, $subject, $title, $contentHtml, $button = null) {
        if (!Validation::validateEmail($to)) return false;

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sadunkalhara119@gmail.com';
            $mail->Password = 'tfoihqmkbudqmitc';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('sadunkalhara119@gmail.com', '2nd Home');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body = self::template($title, $contentHtml, $button);
            $mail->AltBody = trim(strip_tags(str_replace(['<br>', '</p>', '</tr>'], "\n", $contentHtml)));

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mailer error: ' . $mail->ErrorInfo);
            return false;
        }
    }

    // Escapes a value before it is placed inside an e-mail
    public static function e($text) {
        return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
    }

    // One "label: value" row for booking details
    public static function row($label, $value) {
        return '<tr><td style="padding: 6px 0; color: #64748b; font-size: 14px;">' . self::e($label) . '</td><td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600; text-align: right;">' . self::e($value) . '</td></tr>';
    }

    private static function template($title, $contentHtml, $button) {
        $buttonHtml = '';
        if ($button) {
            $buttonHtml = '
                                            <div style="text-align: center; margin: 32px 0;">
                                                <a href="' . self::e($button['url']) . '" style="display: inline-block; background-color: #0284c7; color: #ffffff; border-radius: 8px; padding: 14px 30px; text-decoration: none; font-weight: 600; font-size: 15px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);">
                                                    ' . self::e($button['text']) . '
                                                </a>
                                            </div>
                                            <p style="font-size: 12px; line-height: 1.5; color: #94a3b8; margin: 0; text-align: left; word-break: break-all;">
                                                If you\'re having trouble clicking the button, copy and paste this URL into your browser: <br>
                                                <a href="' . self::e($button['url']) . '" style="color: #0284c7; text-decoration: none;">' . self::e($button['url']) . '</a>
                                            </p>';
        }

        return '
            <table style="width: 100%; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; padding: 40px 20px; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td align="center">
                            <table style="max-width: 540px; width: 100%; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05); border: 1px solid #e2e8f0; border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="padding: 32px 32px 20px 32px; text-align: center; border-bottom: 1px solid #f1f5f9;">
                                            <a href="' . self::baseUrl() . '" style="font-size: 26px; color: #0f172a; font-weight: 800; text-decoration: none; letter-spacing: -0.5px;">2nd <span style="color: #0284c7;">Home</span></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 40px 32px 32px 32px;">
                                            <h1 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 16px; text-align: left;">' . self::e($title) . '</h1>
                                            ' . $contentHtml . $buttonHtml . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 24px 32px 32px 32px; background-color: #f8fafc; border-top: 1px solid #f1f5f9; text-align: center;">
                                            <p style="font-size: 12px; color: #94a3b8; margin: 0;">&copy; ' . date('Y') . ' 2nd Home. All rights reserved.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            ';
    }

    // ---- Ready-made e-mails ----

    public static function sendWelcome($to, $name, $isHost) {
        $p = 'font-size: 15px; line-height: 1.6; color: #334155; margin: 0 0 24px 0; text-align: left;';
        $text = $isHost ? 'Your host account is ready. You can now list your properties and start receiving bookings.' : 'Your guest account is ready. You can now browse properties and book your next stay.';
        return self::send($to, 'Welcome to 2nd Home', 'Welcome, ' . $name . '!', '<p style="' . $p . '">' . self::e($text) . '</p>', ['text' => 'Sign In', 'url' => self::baseUrl() . '/signin.php']);
    }

    public static function sendPasswordReset($to, $vcode) {
        $p = 'font-size: 15px; line-height: 1.6; color: #334155; margin: 0 0 24px 0; text-align: left;';
        $note = '<p style="font-size: 13px; line-height: 1.6; color: #64748b; margin: 24px 0 16px 0; text-align: left;">If you didn\'t request a password reset, you can safely ignore this email. Your password will remain secure and unchanged.</p>';
        return self::send($to, 'Reset your account password', 'Reset Your Password', '<p style="' . $p . '">We received a request to reset your password for your 2nd Home account. Click the button below to proceed.</p>' . $note, ['text' => 'Reset Password', 'url' => self::baseUrl() . '/reset-password.php?code=' . urlencode($vcode)]);
    }

    public static function sendPasswordChanged($to) {
        $p = 'font-size: 15px; line-height: 1.6; color: #334155; margin: 0 0 24px 0; text-align: left;';
        return self::send($to, 'Your password was changed', 'Password Changed', '<p style="' . $p . '">The password for your 2nd Home account was just changed. If this was not you, please reset your password immediately.</p>', ['text' => 'Reset Password', 'url' => self::baseUrl() . '/forgot-password.php']);
    }

    // $items: [['title','checkIn','checkOut','guests','total'], ...]
    public static function sendBookingConfirmation($to, $name, $orderId, $items) {
        $p = 'font-size: 15px; line-height: 1.6; color: #334155; margin: 0 0 24px 0; text-align: left;';
        $html = '<p style="' . $p . '">Hi ' . self::e($name) . ', your booking is confirmed. Order reference: <strong>' . self::e($orderId) . '</strong></p>';
        $grand = 0;
        foreach ($items as $item) {
            $grand += $item['total'];
            $html .= '<table style="width: 100%; border-collapse: collapse; margin-bottom: 16px; border-top: 1px solid #f1f5f9;"><tbody>'
                . self::row('Property', $item['title'])
                . self::row('Check-in', $item['checkIn'])
                . self::row('Check-out', $item['checkOut'])
                . self::row('Guests', $item['guests'])
                . self::row('Total (incl. 10% service fee)', 'LKR ' . number_format($item['total'], 2))
                . '</tbody></table>';
        }
        if (count($items) > 1) {
            $html .= '<p style="' . $p . ' font-weight: 700;">Grand total: LKR ' . number_format($grand, 2) . '</p>';
        }
        return self::send($to, 'Your booking is confirmed', 'Booking Confirmed', $html, ['text' => 'View My Bookings', 'url' => self::baseUrl() . '/my-bookings.php']);
    }

    public static function sendBookingCancelled($to, $name, $title, $checkIn, $checkOut) {
        $p = 'font-size: 15px; line-height: 1.6; color: #334155; margin: 0 0 24px 0; text-align: left;';
        $html = '<p style="' . $p . '">Hi ' . self::e($name) . ', your booking has been cancelled.</p>'
            . '<table style="width: 100%; border-collapse: collapse; border-top: 1px solid #f1f5f9;"><tbody>'
            . self::row('Property', $title) . self::row('Check-in', $checkIn) . self::row('Check-out', $checkOut)
            . '</tbody></table>';
        return self::send($to, 'Your booking was cancelled', 'Booking Cancelled', $html, ['text' => 'Browse Properties', 'url' => self::baseUrl() . '/search.php']);
    }

    // Tells a host that one of their properties was booked
    public static function sendHostNewBooking($to, $hostName, $title, $guestName, $checkIn, $checkOut, $guests) {
        $p = 'font-size: 15px; line-height: 1.6; color: #334155; margin: 0 0 24px 0; text-align: left;';
        $html = '<p style="' . $p . '">Hi ' . self::e($hostName) . ', one of your properties has a new booking.</p>'
            . '<table style="width: 100%; border-collapse: collapse; border-top: 1px solid #f1f5f9;"><tbody>'
            . self::row('Property', $title) . self::row('Guest', $guestName) . self::row('Check-in', $checkIn) . self::row('Check-out', $checkOut) . self::row('Guests', $guests)
            . '</tbody></table>';
        return self::send($to, 'New booking for ' . $title, 'New Booking Received', $html);
    }

    // Sends the guest confirmation and a notice to each host. $items need properties_id, checkIn, checkOut, guests, total
    public static function notifyBooking($to, $name, $orderId, $items) {
        $mailItems = [];
        foreach ($items as $item) {
            $rs = Database::search("SELECT p.`title`, u.`email`, u.`fname` FROM `properties` p JOIN `users` u ON p.`users_id` = u.`id` WHERE p.`id` = '" . $item['properties_id'] . "'");
            $property = $rs ? $rs->fetch_assoc() : null;
            if (!$property) continue;

            $item['title'] = $property['title'];
            $mailItems[] = $item;
            self::sendHostNewBooking($property['email'], $property['fname'], $property['title'], $name, $item['checkIn'], $item['checkOut'], $item['guests']);
        }
        if (!empty($mailItems)) {
            self::sendBookingConfirmation($to, $name, $orderId, $mailItems);
        }
    }
}
