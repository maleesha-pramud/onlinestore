<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => false, 'message' => 'Please sign in to remove your review.']);
    exit();
}

$email = Database::escape($_SESSION['email']);
$reviewId = $_POST['reviewId'] ?? '';

if (!Validation::validateId($reviewId)) {
    echo json_encode(['status' => false, 'message' => 'Invalid review.']);
    exit();
}

// Only the author can remove a review
$ownerStmt = Database::search("SELECT r.`id` FROM `reviews` r JOIN `users` u ON r.`users_id` = u.`id` WHERE r.`id` = '$reviewId' AND u.`email` = '$email'");
if (!$ownerStmt || $ownerStmt->num_rows == 0) {
    echo json_encode(['status' => false, 'message' => 'You can only remove your own review.']);
    exit();
}

Database::iud("DELETE FROM `reviews` WHERE `id` = '$reviewId'");

echo json_encode(['status' => true, 'message' => 'Review removed successfully.']);
