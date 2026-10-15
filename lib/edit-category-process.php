<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

header('Content-Type: application/json');

$id = $_POST['id'] ?? '';
$name = trim($_POST['name'] ?? '');
$imagePath = '';

if (!Validation::validateText($name, 50)) {
  echo json_encode(['status' => false, 'message' => 'Name is required (max 50 characters)']);
  exit();
}

if (!Validation::validateId($id)) {
  echo json_encode(['status' => false, 'message' => 'ID is required']);
  exit();
}

if (!empty($_FILES['image']['tmp_name']) && !Validation::validateImage($_FILES['image']['tmp_name'])) {
  echo json_encode(['status' => false, 'message' => 'Only valid image files are allowed']);
  exit();
}

$name = Database::escape($name);

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
