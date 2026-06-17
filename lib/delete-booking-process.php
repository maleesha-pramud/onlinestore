<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => false, 'message' => 'Please sign in first']);
    exit();
}

$id = Database::escape($_POST['id']);

// Security check: Ensure this booking belongs to the logged-in user
$email = $_SESSION['email'];
$checkStmt = Database::search("SELECT * FROM `bookings` WHERE `id` = '$id' AND `email` = '$email'");

if ($checkStmt->num_rows == 0) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized access']);
    exit();
}

Database::iud("DELETE FROM `bookings` WHERE `id` = '$id'");

echo json_encode(['status' => true, 'message' => 'Success']);
