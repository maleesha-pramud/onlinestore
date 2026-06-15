<?php
include '../includes/connection.php';

if (isset($_POST['userId']) && isset($_POST['typeId'])) {
    $userId = $_POST['userId'];
    $typeId = $_POST['typeId'];

    Database::iud("UPDATE `users` SET `user_type_id` = '$typeId' WHERE `id` = '$userId'");
    echo json_encode(["status" => true, "message" => "User type updated successfully"]);
} else {
    echo json_encode(["status" => false, "message" => "Invalid parameters"]);
}
