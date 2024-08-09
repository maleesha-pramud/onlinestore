<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

$id = $_GET['id'];

if (isset($_GET['id'])) {
  $deleteListingFromAmenitiesStmt = Database::iud("DELETE FROM properties_has_amenities WHERE properties_id = '$id'");
  $deleteListingStmt = Database::iud("DELETE FROM properties WHERE id = '$id'");
  $response = [
    'message' => 'success',
    'status' => true
  ];
} else {
  $response = [
    'message' => 'Please provide a valid ID',
    'status' => false
  ];
}


// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
