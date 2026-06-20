<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(["status" => false, "message" => "Unauthorized access"]);
    exit();
}

$email = $_SESSION['email'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$mobile = $_POST['mobile'];

if (empty($fname) || empty($lname) || empty($mobile)) {
    echo json_encode(["status" => false, "message" => "All fields are required"]);
    exit();
}

if (strlen($fname) > 20) {
    echo json_encode(["status" => false, "message" => "First name is too long"]);
    exit();
} else if (!Validation::validateName($fname)) {
    echo json_encode(["status" => false, "message" => "First name must contain only letters"]);
    exit();
}

if (strlen($lname) > 20) {
    echo json_encode(["status" => false, "message" => "Last name is too long"]);
    exit();
} else if (!Validation::validateName($lname)) {
    echo json_encode(["status" => false, "message" => "Last name must contain only letters"]);
    exit();
}

if (!Validation::validateMobile($mobile)) {
    echo json_encode(["status" => false, "message" => "Mobile number is not valid"]);
    exit();
}

Database::iud("UPDATE `users` SET `fname` = '$fname', `lname` = '$lname', `mobile` = '$mobile' WHERE `email` = '$email'");

echo json_encode(["status" => true, "message" => "Profile updated successfully"]);
