<?php
include '../includes/connection.php';

$id = $_POST['id'];
$name = $_POST['name'];
$icon_cls = $_POST['icon_cls'];

if (empty($name)) {
    echo json_encode(["status" => false, "message" => "Amenity name is required"]);
} elseif (empty($icon_cls)) {
    echo json_encode(["status" => false, "message" => "Icon is required"]);
} else {
    Database::iud("UPDATE `amenities` SET `name` = '$name', `icon_cls` = '$icon_cls' WHERE `id` = '$id'");
    echo json_encode(["status" => true, "message" => "Amenity updated successfully"]);
}
