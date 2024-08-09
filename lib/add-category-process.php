<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

header('Content-Type: application/json');

$name = $_POST['name'];
$image = $_FILES['image'];


// Save image to the category image folder and get the path, then convert that to a string to save in the MySQL database
$imagePath = '';
if (!empty($image['tmp_name'])) {
  $imageName = uniqid() . ".jpg";
  $path = $RootFilePath . "/../assets/images/categories/" . $imageName;
  if (move_uploaded_file($image['tmp_name'], $path)) {
    $imagePath = $imageName;
  } else {
    echo json_encode(['status' => false, 'message' => 'Failed to upload image']);
    exit();
  }
}

if (empty($name)) {
  echo json_encode(['status' => false, 'message' => 'Name is required']);
} else {
  Database::iud("INSERT INTO `categories` (`name`, `image`) VALUES ('$name', '$imagePath')");
  echo json_encode(['status' => true, 'message' => 'Category added successfully']);
}
