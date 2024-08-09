<?php

include '../includes/connection.php';

// Retrieve form data
$checkIn = $_POST['checkIn'];
$checkOut = $_POST['checkOut'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$nic = $_POST['nic'];
$contact = $_POST['contact'];
$guests = $_POST['guests'];
$email = $_POST['email'];
$specialRequests = $_POST['specialRequests'];
$propertyId = $_POST['propertyId'];
$totalPrice = $_POST['totalPrice'];
$orderId = $_POST['orderId'];


// Insert the booking into the database
$BookingStmt = Database::iud("INSERT INTO `bookings` (`checkIn`,`checkOut`,`first_name`,`last_name`,`nic`,`contact`,`guests`,`email`,`special_requests`,`properties_id`,`total_price`,`order_id`) VALUES ('$checkIn','$checkOut','$firstName','$lastName','$nic','$contact','$guests','$email','$specialRequests','$propertyId','$totalPrice','$orderId')");


// Prepare the response 
$response = [
    'status' => true,
    'message' => 'Booked Successfully',
];


// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
