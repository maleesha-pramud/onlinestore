<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

$title = $_POST['title'];
$description = $_POST['description'];
$address = $_POST['address'];
$guests = $_POST['guests'];
$bedrooms = $_POST['bedrooms'];
$beds = $_POST['beds'];
$bathrooms = $_POST['bathrooms'];
$images = $_FILES['images'];
$amenities = $_POST['amenities'];


// save images to the images folder and get the paths. then convert that to string to save in the mysql database
$imagePaths = [];
foreach ($images['tmp_name'] as $key => $tmp_name) {
  $name = uniqid() . ".jpg";
  $path = $RootFilePath . "/../assets/images/properties/" . $name;
  if (move_uploaded_file($tmp_name, $path)) {
    $imagePaths[] = $name;
  }
}
$imagePathsString = implode(',', $imagePaths);






if (empty($title)) {
  echo ("Title is required");
} elseif (empty($description)) {
  echo ("Description is required");
} elseif (empty($address)) {
  echo ("Address is required");
} elseif (empty($guests)) {
  echo ("Number of guests is required");
} elseif (empty($bedrooms)) {
  echo ("Number of bedrooms is required");
} elseif (empty($beds)) {
  echo ("Number of beds is required");
} elseif (empty($bathrooms)) {
  echo ("Number of bathrooms is required");
} else {

  Database::iud("INSERT INTO `properties` (`title`, `description`, `address`, `guests`, `bedrooms`, `beds`, `bathrooms`, `images`) VALUES ('$title', '$description', '$address', '$guests', '$bedrooms', '$beds', '$bathrooms', '$imagePathsString')");

  $propertyId = Database::getInsertedId();

  if (!empty($amenities)) {
    foreach ($amenities as $amenity) {
      Database::iud("INSERT INTO `properties_has_amenities` (`properties_id`, `amenities_id`) VALUES ('$propertyId', '$amenity')");
    }
  }


  echo ("success");
}
