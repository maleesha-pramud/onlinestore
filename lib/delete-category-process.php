<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);
$uploadDir = $RootFilePath . "/../assets/images/categories/";

$id = $_GET['id'];

if (isset($_GET['id'])) {
  // 1. Fetch category to delete its image file
  $category = Database::search("SELECT `image` FROM `categories` WHERE `id` = '$id'")->fetch_assoc();
  if ($category && !empty($category['image'])) {
    $filePath = $uploadDir . $category['image'];
    if (file_exists($filePath)) {
      unlink($filePath);
    }
  }

  // 2. Delete from database
  Database::iud("DELETE FROM categories WHERE id = '$id'");
  
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
