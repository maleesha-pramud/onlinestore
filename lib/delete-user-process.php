<?php
include '../includes/connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Delete associated data first (to maintain integrity, although cascading or manual logic might be needed)
    // Delete bookings by this user (if any, though bookings are usually by customers)
    Database::iud("DELETE FROM `bookings` WHERE `email` = (SELECT email FROM users WHERE id = '$id')");
    
    // Delete properties by this user (if they are a host)
    // We should also delete property images, but for simplicity of process we'll focus on DB
    $propsStmt = Database::search("SELECT id FROM properties WHERE users_id = '$id'");
    while($p = $propsStmt->fetch_assoc()) {
        $pid = $p['id'];
        Database::iud("DELETE FROM `properties_has_amenities` WHERE `properties_id` = '$pid'");
    }
    Database::iud("DELETE FROM `properties` WHERE `users_id` = '$id'");

    // 2. Delete the user
    Database::iud("DELETE FROM `users` WHERE `id` = '$id'");
    
    echo json_encode(["status" => true, "message" => "User deleted successfully"]);
} else {
    echo json_encode(["status" => false, "message" => "Invalid ID"]);
}
