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

// Simple validation for mobile (just length check as per existing patterns)
if (strlen($mobile) < 10) {
    echo json_encode(["status" => false, "message" => "Invalid mobile number"]);
    exit();
}

Database::iud("UPDATE `users` SET `fname` = '$fname', `lname` = '$lname', `mobile` = '$mobile' WHERE `email` = '$email'");

echo json_encode(["status" => true, "message" => "Profile updated successfully"]);
