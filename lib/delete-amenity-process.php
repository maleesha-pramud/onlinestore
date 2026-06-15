<?php
include '../includes/connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Check if any properties are using this amenity
    $checkStmt = Database::search("SELECT * FROM `properties_has_amenities` WHERE `amenities_id` = '$id'");
    if ($checkStmt->num_rows > 0) {
        echo json_encode(["status" => false, "message" => "Cannot delete amenity as it is currently being used by properties"]);
    } else {
        Database::iud("DELETE FROM `amenities` WHERE `id` = '$id'");
        echo json_encode(["status" => true, "message" => "Amenity deleted successfully"]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Invalid ID"]);
}
