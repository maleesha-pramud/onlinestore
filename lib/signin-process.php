<?php

session_start();

include '../includes/connection.php';

$email = $_POST['email'];
$password = $_POST['password'];
$rememberMe = $_POST['rememberMe'];

if (empty($email)) {
    echo ("Email is required");
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo ("Email is not valid");
} else if (empty($password)) {
    echo ("Password is required");
} else if (strlen($password) < 5) {
    echo ("Password is too short");
} else {

    $rs = Database::search("SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'");
    if ($rs->num_rows == 0) {
        echo ("User does not exist");
        return;
    }

    if ($rememberMe == "true") {
        setcookie("email", $email, time() + 60 * 60 * 24 * 30);
        setcookie("password", $password, time() + 60 * 60 * 24 * 30);
    } else {
        setcookie("email", $email, time() - 1);
        setcookie("password", $password, time() - 1);
    }

    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
    echo ("success");
}
