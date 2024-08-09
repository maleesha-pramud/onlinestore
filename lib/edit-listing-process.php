<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

$propertyId = $_POST['propertyId']; // Assuming property ID is provided in the POST request
$title = $_POST['title'];
$category = $_POST['category'];
$description = $_POST['description'];
$address = $_POST['address'];
$guests = $_POST['guests'];
$bedrooms = $_POST['bedrooms'];
$beds = $_POST['beds'];
$bathrooms = $_POST['bathrooms'];
$amenities = isset($_POST['amenities']) ? $_POST['amenities'] : [];
$basePrice = $_POST['basePrice'];

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
} else {
  // Update property in the database
  Database::iud("UPDATE `properties` SET `title` = '$title', `categories_id` = '$category', `description` = '$description', `address` = '$address', `guests` = '$guests', `bedrooms` = '$bedrooms', `beds` = '$beds', `bathrooms` = '$bathrooms', `base_price` = '$basePrice' WHERE `id` = '$propertyId'");

  // Insert amenities if they exist
  if (!empty($amenities)) {
    // Fetch existing amenities for the property
    $existingAmenities = Database::search("SELECT `amenities_id` FROM `properties_has_amenities` WHERE `properties_id` = '$propertyId'");
    $existingAmenitiesIds = [];
    foreach ($existingAmenities as $row) {
      $existingAmenitiesIds[] = $row['amenities_id'];
    }

    // Delete amenities that are not in the provided list
    foreach ($existingAmenitiesIds as $existingAmenity) {
      if (!in_array($existingAmenity, $amenities)) {
        Database::iud("DELETE FROM `properties_has_amenities` WHERE `properties_id` = '$propertyId' AND `amenities_id` = '$existingAmenity'");
      }
    }

    // Insert the provided amenities if they do not exist
    foreach ($amenities as $amenity) {
      if (!in_array($amenity, $existingAmenitiesIds)) {
        Database::iud("INSERT INTO `properties_has_amenities` (`properties_id`, `amenities_id`) VALUES ('$propertyId', '$amenity')");
      }
    }
  } else {
    // If no amenities are provided, delete all existing amenities for the property
    Database::iud("DELETE FROM `properties_has_amenities` WHERE `properties_id` = '$propertyId'");
  }

  echo json_encode(["status" => true, "message" => "Property added successfully"]);
}
