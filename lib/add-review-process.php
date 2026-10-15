<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => false, 'message' => 'Please sign in to leave a review.']);
    exit();
}

$email = $_SESSION['email'];
$propertyId = Database::escape($_POST['propertyId'] ?? '');
$rating = $_POST['rating'] ?? '';
$rawComment = trim($_POST['comment'] ?? '');

if (!Validation::validateId($propertyId)) {
    echo json_encode(['status' => false, 'message' => 'Invalid property.']);
    exit();
}

if (empty($rating) || $rawComment === '') {
    echo json_encode(['status' => false, 'message' => 'Please provide both rating and comment.']);
    exit();
}

if (!Validation::validateRating($rating)) {
    echo json_encode(['status' => false, 'message' => 'Rating must be between 1 and 5.']);
    exit();
}

if (!Validation::validateSafeText($rawComment, 1000)) {
    echo json_encode(['status' => false, 'message' => 'Comment contains invalid characters or is too long (max 1000 characters).']);
    exit();
}

$comment = Database::escape($rawComment);

// Get user ID
$userStmt = Database::search("SELECT `id` FROM `users` WHERE `email` = '$email'");
if ($userStmt->num_rows == 0) {
    echo json_encode(['status' => false, 'message' => 'User not found.']);
    exit();
}
$userData = $userStmt->fetch_assoc();
$userId = $userData['id'];

// Check if user has booked this property
$bookingStmt = Database::search("SELECT `id` FROM `bookings` WHERE `email` = '$email' AND `properties_id` = '$propertyId'");
if ($bookingStmt->num_rows == 0) {
    echo json_encode(['status' => false, 'message' => 'You can only review properties you have booked.']);
    exit();
}

// Optional: Check if already reviewed (to prevent multiple reviews for the same property by the same user)
$reviewStmt = Database::search("SELECT `id` FROM `reviews` WHERE `users_id` = '$userId' AND `properties_id` = '$propertyId'");
if ($reviewStmt->num_rows > 0) {
    echo json_encode(['status' => false, 'message' => 'You have already reviewed this property.']);
    exit();
}

// Insert review
Database::iud("INSERT INTO `reviews` (`properties_id`, `users_id`, `rating`, `comment`) VALUES ('$propertyId', '$userId', '$rating', '$comment')");

echo json_encode(['status' => true, 'message' => 'Review submitted successfully.']);
