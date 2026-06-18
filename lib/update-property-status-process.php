<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized access.']);
    exit();
}

$id = Database::escape($_POST['id']);
$status = Database::escape($_POST['status']);

Database::iud("UPDATE `properties` SET `status_id` = '$status' WHERE `id` = '$id'");

echo json_encode(['status' => true, 'message' => 'Property status updated successfully.']);
