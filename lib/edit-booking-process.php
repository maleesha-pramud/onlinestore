<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => false, 'message' => 'Please sign in first']);
    exit();
}

$id = Database::escape($_POST['id']);
$fname = Database::escape($_POST['fname']);
$lname = Database::escape($_POST['lname']);
$nic = Database::escape($_POST['nic']);
$contact = Database::escape($_POST['contact']);
$specialRequests = Database::escape($_POST['specialRequests']);

// Security check: Ensure this booking belongs to the logged-in user
$email = $_SESSION['email'];
$checkStmt = Database::search("SELECT * FROM `bookings` WHERE `id` = '$id' AND `email` = '$email'");

if ($checkStmt->num_rows == 0) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized access']);
    exit();
}

Database::iud("UPDATE `bookings` SET `first_name` = '$fname', `last_name` = '$lname', `nic` = '$nic', `contact` = '$contact', `special_requests` = '$specialRequests' WHERE `id` = '$id'");

echo json_encode(['status' => true, 'message' => 'Success']);
