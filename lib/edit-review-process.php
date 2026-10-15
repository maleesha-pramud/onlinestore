<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => false, 'message' => 'Please sign in to edit your review.']);
    exit();
}

$email = Database::escape($_SESSION['email']);
$reviewId = $_POST['reviewId'] ?? '';
$rating = $_POST['rating'] ?? '';
$rawComment = trim($_POST['comment'] ?? '');

if (!Validation::validateId($reviewId)) {
    echo json_encode(['status' => false, 'message' => 'Invalid review.']);
    exit();
}

if (!Validation::validateRating($rating)) {
    echo json_encode(['status' => false, 'message' => 'Rating must be between 1 and 5.']);
    exit();
}

if ($rawComment === '') {
    echo json_encode(['status' => false, 'message' => 'Please enter a comment.']);
    exit();
}

if (!Validation::validateSafeText($rawComment, 1000)) {
    echo json_encode(['status' => false, 'message' => 'Comment contains invalid characters or is too long (max 1000 characters).']);
    exit();
}

// Only the author can edit a review
$ownerStmt = Database::search("SELECT r.`id` FROM `reviews` r JOIN `users` u ON r.`users_id` = u.`id` WHERE r.`id` = '$reviewId' AND u.`email` = '$email'");
if (!$ownerStmt || $ownerStmt->num_rows == 0) {
    echo json_encode(['status' => false, 'message' => 'You can only edit your own review.']);
    exit();
}

$comment = Database::escape($rawComment);
Database::iud("UPDATE `reviews` SET `rating` = '$rating', `comment` = '$comment' WHERE `id` = '$reviewId'");

echo json_encode(['status' => true, 'message' => 'Review updated successfully.']);
