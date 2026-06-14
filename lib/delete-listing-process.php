<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);
$uploadDir = $RootFilePath . "/../assets/images/properties/";

$id = $_GET['id'];

if (isset($_GET['id'])) {
  // 1. Fetch images to delete their files
  $property = Database::search("SELECT `images` FROM `properties` WHERE `id` = '$id'")->fetch_assoc();
  if ($property) {
    $images = explode(',', $property['images']);
    foreach ($images as $imageName) {
      if (!empty($imageName)) {
        $filePath = $uploadDir . $imageName;
        if (file_exists($filePath)) {
          unlink($filePath);
        }
      }
    }
  }

  // 2. Delete from database
  Database::iud("DELETE FROM properties_has_amenities WHERE properties_id = '$id'");
  Database::iud("DELETE FROM properties WHERE id = '$id'");

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
