<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);
$uploadDir = $RootFilePath . "/../assets/images/properties/";

header('Content-Type: application/json');

$propertyId = $_POST['propertyId'] ?? '';
$title = trim($_POST['title'] ?? '');
$category = $_POST['category'] ?? '';
$description = trim($_POST['description'] ?? '');
$address = trim($_POST['address'] ?? '');
$guests = $_POST['guests'] ?? '';
$bedrooms = $_POST['bedrooms'] ?? '';
$beds = $_POST['beds'] ?? '';
$bathrooms = $_POST['bathrooms'] ?? '';
$amenities = isset($_POST['amenities']) && is_array($_POST['amenities']) ? $_POST['amenities'] : [];
$basePrice = $_POST['basePrice'] ?? '';
$keptImagesString = isset($_POST['keptImages']) ? $_POST['keptImages'] : "";

function fail($message) {
    echo json_encode(["status" => false, "message" => $message]);
    exit();
}

// Validate basic input fields
if (!Validation::validateId($propertyId)) {
    fail("Invalid property");
} elseif (!Validation::validateText($title, 100)) {
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
}

foreach ($amenities as $amenity) {
    if (!Validation::validateId($amenity)) {
        fail("Invalid amenity selected");
    }
}

if (isset($_FILES['newImages']) && is_array($_FILES['newImages']['tmp_name'])) {
    foreach ($_FILES['newImages']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['newImages']['error'][$key] === UPLOAD_ERR_NO_FILE) continue;
        if ($_FILES['newImages']['error'][$key] !== UPLOAD_ERR_OK || !Validation::validateImage($tmp_name)) {
            fail("Only valid image files (JPG, PNG, WEBP, GIF) are allowed");
        }
    }
}

$title = Database::escape($title);
$description = Database::escape(Validation::sanitizeRichText($description));
$address = Database::escape($address);

// 1. Handle Kept Images
$keptImages = [];
if (!empty($keptImagesString)) {
    $tempKept = explode(',', $keptImagesString);
    foreach ($tempKept as $img) {
        if (!empty($img) && preg_match('/^[A-Za-z0-9_-]+\.[A-Za-z]{3,4}$/', $img)) {
            $keptImages[] = $img;
        }
    }
}

// 2. Handle File Deletions
$currentProperty = Database::search("SELECT `images` FROM `properties` WHERE `id` = '$propertyId'")->fetch_assoc();
if (!$currentProperty) {
    fail("Property not found");
}
$currentImages = explode(',', $currentProperty['images']);

foreach ($currentImages as $imageName) {
    if (!empty($imageName) && !in_array($imageName, $keptImages)) {
        $filePath = $uploadDir . $imageName;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}

// 3. Handle New Image Uploads
$newImagePaths = [];
if (isset($_FILES['newImages'])) {
    $newImages = $_FILES['newImages'];
    foreach ($newImages['tmp_name'] as $key => $tmp_name) {
        if ($newImages['error'][$key] === UPLOAD_ERR_OK) {
            $name = uniqid() . ".jpg";
            $path = $uploadDir . $name;
            if (move_uploaded_file($tmp_name, $path)) {
                $newImagePaths[] = $name;
            }
        }
    }
}

// 4. Combine and Validate
$finalImagesArray = array_merge($keptImages, $newImagePaths);

if (empty($finalImagesArray)) {
    echo json_encode(["status" => false, "message" => "At least one image is required"]);
    exit();
}

$finalImagesString = implode(',', $finalImagesArray);

// Update property
Database::iud("UPDATE `properties` SET 
  `title` = '$title', 
  `categories_id` = '$category', 
  `description` = '$description', 
  `address` = '$address', 
  `guests` = '$guests', 
  `bedrooms` = '$bedrooms', 
  `beds` = '$beds', 
  `bathrooms` = '$bathrooms', 
  `base_price` = '$basePrice',
  `images` = '$finalImagesString'
  WHERE `id` = '$propertyId'");

// Update amenities
Database::iud("DELETE FROM `properties_has_amenities` WHERE `properties_id` = '$propertyId'");
if (!empty($amenities)) {
    foreach ($amenities as $amenity) {
        Database::iud("INSERT INTO `properties_has_amenities` (`properties_id`, `amenities_id`) VALUES ('$propertyId', '$amenity')");
    }
}

echo json_encode(["status" => true, "message" => "Property updated successfully"]);
