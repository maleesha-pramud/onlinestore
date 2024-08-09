<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

header('Content-Type: application/json');

$id = $_POST['id'];
$name = $_POST['name'];
$imagePath = '';

if (!empty($_FILES['image'])) {
  $image = $_FILES['image'];

  // Save image to the category image folder and get the path, then convert that to a string to save in the MySQL database
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
}

if (empty($name)) {
  echo json_encode(['status' => false, 'message' => 'Name is required']);
} else if (empty($id)) {
  echo json_encode(['status' => false, 'message' => 'ID is required']);
} else {
  if ($imagePath) {
    Database::iud("UPDATE `categories` SET `name` = '$name', `image` = '$imagePath' WHERE `id` = $id");
  } else {
    Database::iud("UPDATE `categories` SET `name` = '$name' WHERE `id` = $id");
  }
  echo json_encode(['status' => true, 'message' => 'Category updated successfully']);
}
