<?php

include '../includes/connection.php';

// Retrieve and escape form data
$checkIn = Database::escape($_POST['checkIn']);
$checkOut = Database::escape($_POST['checkOut']);
$firstName = Database::escape($_POST['firstName']);
$lastName = Database::escape($_POST['lastName']);
$nic = Database::escape($_POST['nic']);
$contact = Database::escape($_POST['contact']);
$guests = Database::escape($_POST['guests']);
$email = Database::escape($_POST['email']);
$specialRequests = Database::escape($_POST['specialRequests']);
$propertyId = Database::escape($_POST['propertyId']);
$totalPrice = Database::escape($_POST['totalPrice']);
$orderId = Database::escape($_POST['orderId']);

if (empty($checkIn) || empty($checkOut) || empty($firstName) || empty($lastName) || empty($nic) || empty($contact) || empty($guests) || empty($email) || empty($propertyId) || empty($totalPrice) || empty($orderId)) {
    header('Content-Type: application/json');
    echo json_encode(['status' => false, 'message' => 'All booking fields are required']);
    exit();
}

if (!Validation::validateName($firstName)) {
    header('Content-Type: application/json');
    echo json_encode(['status' => false, 'message' => 'First name must contain only letters']);
    exit();
}

if (!Validation::validateName($lastName)) {
    header('Content-Type: application/json');
    echo json_encode(['status' => false, 'message' => 'Last name must contain only letters']);
    exit();
}

if (!Validation::validateNIC($nic)) {
    header('Content-Type: application/json');
    echo json_encode(['status' => false, 'message' => 'NIC is not valid']);
    exit();
}

if (!Validation::validateMobile($contact)) {
    header('Content-Type: application/json');
    echo json_encode(['status' => false, 'message' => 'Contact number is not valid']);
    exit();
}

if (!Validation::validateEmail($email)) {
    header('Content-Type: application/json');
    echo json_encode(['status' => false, 'message' => 'Email is not valid']);
    exit();
}


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
