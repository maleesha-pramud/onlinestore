<?php
include '../includes/connection.php';

$name = trim($_POST['name'] ?? '');
$icon_cls = trim($_POST['icon_cls'] ?? '');

if (!Validation::validateText($name, 50)) {
    echo json_encode(["status" => false, "message" => "Amenity name is required (max 50 characters)"]);
} elseif (!Validation::validateText($icon_cls, 100) || !preg_match('/^[A-Za-z0-9 _-]+$/', $icon_cls)) {
    echo json_encode(["status" => false, "message" => "A valid icon is required"]);
} else {
    $name = Database::escape($name);
    $icon_cls = Database::escape($icon_cls);
    Database::iud("INSERT INTO `amenities` (`name`, `icon_cls`) VALUES ('$name', '$icon_cls')");
    echo json_encode(["status" => true, "message" => "Amenity added successfully"]);
}
