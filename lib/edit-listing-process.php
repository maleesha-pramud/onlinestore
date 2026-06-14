<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);
$uploadDir = $RootFilePath . "/../assets/images/properties/";

$propertyId = $_POST['propertyId'];
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
$keptImagesString = isset($_POST['keptImages']) ? $_POST['keptImages'] : "";

// Validate basic input fields
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
}

// 1. Handle Kept Images
$keptImages = [];
if (!empty($keptImagesString)) {
    $tempKept = explode(',', $keptImagesString);
    foreach ($tempKept as $img) {
        if (!empty($img)) {
            $keptImages[] = $img;
        }
    }
}

// 2. Handle File Deletions
$currentProperty = Database::search("SELECT `images` FROM `properties` WHERE `id` = '$propertyId'")->fetch_assoc();
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
