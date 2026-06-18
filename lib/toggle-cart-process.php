<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => false, 'message' => 'Please sign in to add items to your cart.']);
    exit();
}

$email = $_SESSION['email'];
$propertyId = Database::escape($_POST['propertyId']);

// Get user ID
$userStmt = Database::search("SELECT `id` FROM `users` WHERE `email` = '$email'");
$userData = $userStmt->fetch_assoc();
$userId = $userData['id'];

// Check if already in cart
$cartCheck = Database::search("SELECT `id` FROM `cart` WHERE `users_id` = '$userId' AND `properties_id` = '$propertyId'");

if ($cartCheck->num_rows > 0) {
    // Remove if already exists (toggle behavior)
    Database::iud("DELETE FROM `cart` WHERE `users_id` = '$userId' AND `properties_id` = '$propertyId'");
    echo json_encode(['status' => true, 'action' => 'removed', 'message' => 'Removed from cart.']);
} else {
    // Add to cart
    Database::iud("INSERT INTO `cart` (`users_id`, `properties_id`) VALUES ('$userId', '$propertyId')");
    echo json_encode(['status' => true, 'action' => 'added', 'message' => 'Added to cart.']);
}
