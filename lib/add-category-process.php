<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

$name = $_POST['name'];
$image = $_FILES['image'];

// Save image to the category images folder and get the paths, then convert that to a string to save in the MySQL database
$imagePath = '';
if (!empty($image['tmp_name'])) {
  $imageName = uniqid() . ".jpg";
  $path = $RootFilePath . "/../assets/images/categories/" . $imageName;
  if (move_uploaded_file($image['tmp_name'], $path)) {
    $imagePath = $imageName;
  }
}


if (empty($name)) {
  echo ("Name is required");
} else {

  Database::iud("INSERT INTO `categories` (`name`, `image`) VALUES ('$name', '$imagePath')");

  echo ("success");
}
