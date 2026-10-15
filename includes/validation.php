<?php

class Validation {
    const NAME_PATTERN = "/^[a-zA-Z\s]+$/";
    const MOBILE_PATTERN = '/^07[0,1,2,4,5,6,7,8]{1}[0-9]{7}$/';
    const EMAIL_PATTERN = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    const PASSWORD_MIN5_PATTERN = '/^.{5,}$/';
    const PASSWORD_MIN8_PATTERN = '/^.{8,}$/';
    const NIC_PATTERN = '/^(?:[0-9]{9}[vVxX]|[0-9]{12})$/';

    public static function validateName($name) {
        return (bool) preg_match(self::NAME_PATTERN, $name);
    }

    public static function validateMobile($mobile) {
        return (bool) preg_match(self::MOBILE_PATTERN, $mobile);
    }

    public static function validateEmail($email) {
        return (bool) preg_match(self::EMAIL_PATTERN, $email);
    }

    public static function validatePasswordMin5($password) {
        return (bool) preg_match(self::PASSWORD_MIN5_PATTERN, $password);
    }

    public static function validatePasswordMin8($password) {
        return (bool) preg_match(self::PASSWORD_MIN8_PATTERN, $password);
    }

    public static function validateNIC($nic) {
        return (bool) preg_match(self::NIC_PATTERN, $nic);
    }

    // Positive whole number (optionally capped), e.g. guests, beds
    public static function validatePositiveInt($value, $max = 1000) {
        return is_string($value) || is_int($value)
            ? (bool) preg_match('/^[1-9][0-9]{0,8}$/', (string) $value) && (int) $value <= $max
            : false;
    }

    // Positive price with up to 2 decimals, greater than zero
    public static function validatePrice($value, $max = 10000000) {
        if (!is_string($value) && !is_int($value) && !is_float($value)) return false;
        if (!preg_match('/^(?:[0-9]+)(?:\.[0-9]{1,2})?$/', (string) $value)) return false;
        return (float) $value > 0 && (float) $value <= $max;
    }

    public static function validateRating($value) {
        return is_string($value) && (bool) preg_match('/^[1-5]$/', $value);
    }

    public static function validateDate($date) {
        if (!is_string($date)) return false;
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    // Returns number of nights for a valid future stay, otherwise false
    public static function getNights($checkIn, $checkOut) {
        if (!self::validateDate($checkIn) || !self::validateDate($checkOut)) return false;
        $in = new DateTime($checkIn);
        $out = new DateTime($checkOut);
        if ($in < new DateTime('today') || $out <= $in) return false;
        $nights = (int) $in->diff($out)->days;
        return $nights <= 365 ? $nights : false;
    }

    public static function validateText($text, $max) {
        return is_string($text) && trim($text) !== '' && mb_strlen($text) <= $max;
    }

    public static function validateId($id) {
        return (is_string($id) || is_int($id)) && (bool) preg_match('/^[1-9][0-9]{0,9}$/', (string) $id);
    }

    public static function validateImage($tmpName) {
        if (!is_uploaded_file($tmpName)) return false;
        $info = @getimagesize($tmpName);
        return $info !== false && in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF]);
    }

    // Validates a stay request and returns ['total' => float] on success or ['error' => string]
    public static function calculateBookingTotal($propertyId, $checkIn, $checkOut, $guests) {
        if (!self::validateId($propertyId)) return ['error' => 'Invalid property'];
        $nights = self::getNights($checkIn, $checkOut);
        if ($nights === false) return ['error' => 'Please select valid future dates (check-out must be after check-in)'];

        $rs = Database::search("SELECT `base_price`, `guests` FROM `properties` WHERE `id` = '$propertyId'");
        $property = $rs ? $rs->fetch_assoc() : null;
        if (!$property) return ['error' => 'Property not found'];

        if (!self::validatePositiveInt($guests, (int) $property['guests'])) {
            return ['error' => 'Guests must be between 1 and ' . $property['guests']];
        }

        $subtotal = (float) $property['base_price'] * $nights;
        return ['total' => round($subtotal + $subtotal * 0.1, 2), 'nights' => $nights];
    }

    // Validates every cart item of a user and returns ['items' => [...], 'total' => float] or ['error' => string]
    public static function getCartCheckout($email) {
        $email = Database::escape($email);
        $rs = Database::search("SELECT `id` FROM `users` WHERE `email` = '$email'");
        $user = $rs ? $rs->fetch_assoc() : null;
        if (!$user) return ['error' => 'User not found'];

        $cart = Database::search("SELECT c.id AS cart_id, c.checkIn, c.checkOut, c.guests, c.properties_id, p.title FROM `cart` c JOIN `properties` p ON c.properties_id = p.id WHERE c.users_id = '" . $user['id'] . "' AND p.status_id = 1");
        $items = [];
        $total = 0;
        while ($row = $cart->fetch_assoc()) {
            $calc = self::calculateBookingTotal($row['properties_id'], (string) $row['checkIn'], (string) $row['checkOut'], (string) $row['guests']);
            if (isset($calc['error'])) return ['error' => $row['title'] . ': ' . $calc['error']];

            $overlap = Database::search("SELECT `id` FROM `bookings` WHERE `properties_id` = '" . $row['properties_id'] . "' AND `checkIn` < '" . $row['checkOut'] . "' AND `checkOut` > '" . $row['checkIn'] . "'");
            if ($overlap && $overlap->num_rows > 0) return ['error' => $row['title'] . ': not available for the selected dates'];

            $row['total'] = $calc['total'];
            $items[] = $row;
            $total += $calc['total'];
        }
        if (empty($items)) return ['error' => 'Your cart is empty'];
        return ['items' => $items, 'total' => round($total, 2)];
    }
}
