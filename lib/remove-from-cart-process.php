<?php
session_start();
include '../includes/connection.php';

if (!isset($_POST['id'])) {
    echo json_encode(['status' => false, 'message' => 'Invalid request.']);
    exit();
}

$id = Database::escape($_POST['id']);
Database::iud("DELETE FROM `cart` WHERE `id` = '$id'");

echo json_encode(['status' => true, 'message' => 'Item removed from cart.']);
