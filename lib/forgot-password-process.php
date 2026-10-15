<?php

include '../includes/connection.php';

$email = $_POST['email'] ?? '';

if (empty($email)) {
    echo ("Email is required");
} else if (!Validation::validateEmail($email)) {
    echo ("Email is not valid");
} else {
    $email = Database::escape($email);
    $rs = Database::search("SELECT * FROM `users` WHERE `email` = '$email'");
    if ($rs->num_rows == 0) {
        echo ("User not found!");
        return;
    } else {

        $row = $rs->fetch_assoc();
        $vcode = uniqid();

        Database::iud("UPDATE `users` SET `vcode`='$vcode' WHERE `id` = '" . $row['id'] . "'");

        if (Mailer::sendPasswordReset($row['email'], $vcode)) {
            echo 'Message has been sent';
        } else {
            echo "Message could not be sent. Please try again later";
        }
    }
}
