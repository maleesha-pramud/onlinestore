<?php

session_start();

include '../includes/connection.php';

$email = $_POST['email'];
$password = $_POST['password'];
$rememberMe = $_POST['rememberMe'];

header('Content-Type: application/json');

if (empty($email)) {
    echo json_encode([
        "message" => "Email is required",
        "status" => false,
    ]);
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "message" => "Email is not valid",
        "status" => false,
    ]);
} else if (empty($password)) {
    echo json_encode([
        "message" => "Password is required",
        "status" => false,
    ]);
} else if (strlen($password) < 5) {
    echo json_encode([
        "message" => "Password is too short",
        "status" => false,
    ]);
} else {

    $rs = Database::search("SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'");
    if ($rs->num_rows == 0) {
        echo json_encode(["message" => "User does not exist"]);
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
    echo json_encode(["message" => "success", "status" => true]);
}
