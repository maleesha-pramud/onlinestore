<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(["status" => false, "message" => "Unauthorized access"]);
    exit();
}

$email = $_SESSION['email'];
$currPassword = $_POST['currPassword'];
$newPassword = $_POST['newPassword'];

if (empty($currPassword) || empty($newPassword)) {
    echo json_encode(["status" => false, "message" => "All fields are required"]);
    exit();
}

// Check if current password is correct
$userStmt = Database::search("SELECT `password` FROM `users` WHERE `email` = '$email'");
$userData = $userStmt->fetch_assoc();

// Note: Using direct comparison as per existing sign-in logic in this project
if ($userData['password'] !== $currPassword) {
    echo json_encode(["status" => false, "message" => "Current password is incorrect"]);
    exit();
}

// Update to new password
Database::iud("UPDATE `users` SET `password` = '$newPassword' WHERE `email` = '$email'");

echo json_encode(["status" => true, "message" => "Password updated successfully"]);
