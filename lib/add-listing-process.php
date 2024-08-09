<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

$userId = $_POST['userId'];
$title = $_POST['title'];
$category = $_POST['category'];
$description = $_POST['description'];
$address = $_POST['address'];
$guests = $_POST['guests'];
$bedrooms = $_POST['bedrooms'];
$beds = $_POST['beds'];
$bathrooms = $_POST['bathrooms'];
$images = $_FILES['images'];
$amenities = isset($_POST['amenities']) ? $_POST['amenities'] : [];
$basePrice = $_POST['basePrice'];

// Save images to the images folder and get the paths, then convert that to a string to save in the MySQL database
$imagePaths = [];
foreach ($images['tmp_name'] as $key => $tmp_name) {
  $name = uniqid() . ".jpg";
  $path = $RootFilePath . "/../assets/images/properties/" . $name;
  if (move_uploaded_file($tmp_name, $path)) {
    $imagePaths[] = $name;
  }
}
$imagePathsString = implode(',', $imagePaths);

// Validate input fields
if (empty($title)) {
  echo json_encode(["status" => false, "message" => "Title is required"]);
  exit();
} elseif (empty($category)) {
  echo json_encode(["status" => false, "message" => "Category is required"]);
  exit();
} elseif (empty($description)) {
  echo json_encode(["status" => false, "message" => "Description is required"]);
  exit();
} elseif (empty($address)) {
  echo json_encode(["status" => false, "message" => "Address is required"]);
  exit();
} elseif (empty($guests)) {
  echo json_encode(["status" => false, "message" => "Number of guests is required"]);
  exit();
} elseif (empty($bedrooms)) {
  echo json_encode(["status" => false, "message" => "Number of bedrooms is required"]);
  exit();
} elseif (empty($beds)) {
  echo json_encode(["status" => false, "message" => "Number of beds is required"]);
  exit();
} elseif (empty($bathrooms)) {
  echo json_encode(["status" => false, "message" => "Number of bathrooms is required"]);
  exit();
} elseif (empty($basePrice)) {
  echo json_encode(["status" => false, "message" => "Base price is required"]);
  exit();
} elseif (empty($imagePaths)) {
  echo json_encode(["status" => false, "message" => "At least one image is required"]);
  exit();
} else {
  // Insert property into the database
  Database::iud("INSERT INTO `properties` (`title`, `categories_id`, `description`, `address`, `guests`, `bedrooms`, `beds`, `bathrooms`, `images`, `base_price`, `users_id`) VALUES ('$title', '$category', '$description', '$address', '$guests', '$bedrooms', '$beds', '$bathrooms', '$imagePathsString', '$basePrice', '$userId')");

  $propertyId = Database::getInsertedId();

  // Insert amenities if they exist
  if (!empty($amenities)) {
    foreach ($amenities as $amenity) {
      Database::iud("INSERT INTO `properties_has_amenities` (`properties_id`, `amenities_id`) VALUES ('$propertyId', '$amenity')");
    }
  }

  echo json_encode(["status" => true, "message" => "Property added successfully"]);
}
