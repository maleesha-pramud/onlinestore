<?php
include '../includes/connection.php';

$id = $_POST['id'] ?? '';
$name = trim($_POST['name'] ?? '');
$icon_cls = trim($_POST['icon_cls'] ?? '');

if (!Validation::validateId($id)) {
    echo json_encode(["status" => false, "message" => "Invalid amenity"]);
} elseif (!Validation::validateText($name, 50)) {
    echo json_encode(["status" => false, "message" => "Amenity name is required (max 50 characters)"]);
} elseif (!Validation::validateText($icon_cls, 100) || !preg_match('/^[A-Za-z0-9 _-]+$/', $icon_cls)) {
    echo json_encode(["status" => false, "message" => "A valid icon is required"]);
} else {
    $name = Database::escape($name);
    $icon_cls = Database::escape($icon_cls);
    Database::iud("UPDATE `amenities` SET `name` = '$name', `icon_cls` = '$icon_cls' WHERE `id` = '$id'");
    echo json_encode(["status" => true, "message" => "Amenity updated successfully"]);
}
