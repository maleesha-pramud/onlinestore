<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => false, 'message' => 'Please sign in to add items to your cart.']);
    exit();
}

$email = $_SESSION['email'];
$propertyId = Database::escape($_POST['propertyId']);
$checkIn = Database::escape($_POST['checkIn']);
$checkOut = Database::escape($_POST['checkOut']);
$guests = Database::escape($_POST['guests']);

if (empty($checkIn) || empty($checkOut) || empty($guests)) {
    echo json_encode(['status' => false, 'message' => 'Please select dates and guests.']);
    exit();
}

// Get user ID
$userStmt = Database::search("SELECT `id` FROM `users` WHERE `email` = '$email'");
$userData = $userStmt->fetch_assoc();
$userId = $userData['id'];

// Check if already in cart with same dates (or just update)
$cartCheck = Database::search("SELECT `id` FROM `cart` WHERE `users_id` = '$userId' AND `properties_id` = '$propertyId'");

if ($cartCheck->num_rows > 0) {
    Database::iud("UPDATE `cart` SET `checkIn` = '$checkIn', `checkOut` = '$checkOut', `guests` = '$guests' WHERE `users_id` = '$userId' AND `properties_id` = '$propertyId'");
    echo json_encode(['status' => true, 'message' => 'Cart updated with new dates.']);
} else {
    Database::iud("INSERT INTO `cart` (`users_id`, `properties_id`, `checkIn`, `checkOut`, `guests`) VALUES ('$userId', '$propertyId', '$checkIn', '$checkOut', '$guests')");
    echo json_encode(['status' => true, 'message' => 'Added to cart successfully.']);
}
