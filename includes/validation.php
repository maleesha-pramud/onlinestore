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
}
