<?php
include '../includes/connection.php';

$name = $_POST['name'];
$icon_cls = $_POST['icon_cls'];

if (empty($name)) {
    echo json_encode(["status" => false, "message" => "Amenity name is required"]);
} elseif (empty($icon_cls)) {
    echo json_encode(["status" => false, "message" => "Icon is required"]);
} else {
    Database::iud("INSERT INTO `amenities` (`name`, `icon_cls`) VALUES ('$name', '$icon_cls')");
    echo json_encode(["status" => true, "message" => "Amenity added successfully"]);
}
