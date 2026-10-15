<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

header('Content-Type: application/json');

$userId = Database::escape($_POST['userId'] ?? '');
$title = trim($_POST['title'] ?? '');
$category = $_POST['category'] ?? '';
$description = trim($_POST['description'] ?? '');
$address = trim($_POST['address'] ?? '');
$guests = $_POST['guests'] ?? '';
$bedrooms = $_POST['bedrooms'] ?? '';
$beds = $_POST['beds'] ?? '';
$bathrooms = $_POST['bathrooms'] ?? '';
$images = $_FILES['images'] ?? null;
$amenities = isset($_POST['amenities']) && is_array($_POST['amenities']) ? $_POST['amenities'] : [];
$basePrice = $_POST['basePrice'] ?? '';

// Validate input fields
function fail($message) {
  echo json_encode(["status" => false, "message" => $message]);
  exit();
}

if (!Validation::validateText($title, 100)) {
  fail("Title is required (max 100 characters)");
} elseif (!Validation::validateId($category)) {
  fail("Category is required");
} elseif (!Validation::validateRichText($description, 5000)) {
  fail("Description is required and must not contain special characters (max 5000 characters)");
} elseif (!Validation::validateText($address, 255)) {
  fail("Address is required (max 255 characters)");
} elseif (!Validation::validatePositiveInt($guests, 100)) {
  fail("Number of guests must be a whole number between 1 and 100");
} elseif (!Validation::validatePositiveInt($bedrooms, 100)) {
  fail("Number of bedrooms must be a whole number between 1 and 100");
} elseif (!Validation::validatePositiveInt($beds, 100)) {
  fail("Number of beds must be a whole number between 1 and 100");
} elseif (!Validation::validatePositiveInt($bathrooms, 100)) {
  fail("Number of bathrooms must be a whole number between 1 and 100");
} elseif (!Validation::validatePrice($basePrice)) {
  fail("Base price must be a number greater than 0");
} elseif (!$images || empty($images['tmp_name']) || !is_array($images['tmp_name'])) {
  fail("At least one image is required");
}

foreach ($amenities as $amenity) {
  if (!Validation::validateId($amenity)) {
    fail("Invalid amenity selected");
  }
}

foreach ($images['tmp_name'] as $key => $tmp_name) {
  if ($images['error'][$key] !== UPLOAD_ERR_OK || !Validation::validateImage($tmp_name)) {
    fail("Only valid image files (JPG, PNG, WEBP, GIF) are allowed");
  }
}

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

if (empty($imagePaths)) {
  fail("At least one image is required");
}

$title = Database::escape($title);
$description = Database::escape(Validation::sanitizeRichText($description));
$address = Database::escape($address);

// Insert property into the database
Database::iud("INSERT INTO `properties` (`title`, `categories_id`, `description`, `address`, `guests`, `bedrooms`, `beds`, `bathrooms`, `images`, `base_price`, `users_id`) VALUES ('$title', '$category', '$description', '$address', '$guests', '$bedrooms', '$beds', '$bathrooms', '$imagePathsString', '$basePrice', '$userId')");

$propertyId = Database::getInsertedId();

// Insert amenities if they exist
foreach ($amenities as $amenity) {
  Database::iud("INSERT INTO `properties_has_amenities` (`properties_id`, `amenities_id`) VALUES ('$propertyId', '$amenity')");
}

echo json_encode(["status" => true, "message" => "Property added successfully"]);
