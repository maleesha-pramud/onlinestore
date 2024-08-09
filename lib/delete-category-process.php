<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

$id = $_GET['id'];

if (isset($_GET['id'])) {
  $deleteStmt = Database::iud("DELETE FROM categories WHERE id = '$id'");
  $response = [
    'message' => 'success',
    'status' => true
  ];
} else {
  $response = [
    'message' => 'Please provide a valid ID',
    'status' => false
  ];
}


// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
