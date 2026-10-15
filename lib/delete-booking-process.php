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
$checkStmt = Database::search("SELECT b.*, p.`title` FROM `bookings` b JOIN `properties` p ON b.`properties_id` = p.`id` WHERE b.`id` = '$id' AND b.`email` = '$email'");

if ($checkStmt->num_rows == 0) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized access']);
    exit();
}
$booking = $checkStmt->fetch_assoc();

Database::iud("DELETE FROM `bookings` WHERE `id` = '$id'");

Mailer::sendBookingCancelled($booking['email'], $booking['first_name'], $booking['title'], $booking['checkIn'], $booking['checkOut']);

echo json_encode(['status' => true, 'message' => 'Success']);
