<?php

include '../includes/connection.php';

header('Content-Type: application/json');

// Retrieve and escape form data
$checkIn = Database::escape($_POST['checkIn'] ?? '');
$checkOut = Database::escape($_POST['checkOut'] ?? '');
$firstName = Database::escape($_POST['firstName'] ?? '');
$lastName = Database::escape($_POST['lastName'] ?? '');
$nic = Database::escape($_POST['nic'] ?? '');
$contact = Database::escape($_POST['contact'] ?? '');
$guests = Database::escape($_POST['guests'] ?? '');
$email = Database::escape($_POST['email'] ?? '');
$specialRequests = Database::escape(trim($_POST['specialRequests'] ?? ''));
$propertyId = Database::escape($_POST['propertyId'] ?? '');
$orderId = Database::escape($_POST['orderId'] ?? '');

if (empty($checkIn) || empty($checkOut) || empty($firstName) || empty($lastName) || empty($nic) || empty($contact) || empty($guests) || empty($email) || empty($propertyId) || empty($orderId)) {
    echo json_encode(['status' => false, 'message' => 'All booking fields are required']);
    exit();
}

if (strlen($firstName) > 20 || strlen($lastName) > 20) {
    echo json_encode(['status' => false, 'message' => 'Name is too long']);
    exit();
}

if (mb_strlen($specialRequests) > 500) {
    echo json_encode(['status' => false, 'message' => 'Special requests are too long']);
    exit();
}

// Total price is always calculated on the server
$calc = Validation::calculateBookingTotal($propertyId, $checkIn, $checkOut, $guests);
if (isset($calc['error'])) {
    echo json_encode(['status' => false, 'message' => $calc['error']]);
    exit();
}
$totalPrice = $calc['total'];

if (!Validation::validateName($firstName)) {
    echo json_encode(['status' => false, 'message' => 'First name must contain only letters']);
    exit();
}

if (!Validation::validateName($lastName)) {
    echo json_encode(['status' => false, 'message' => 'Last name must contain only letters']);
    exit();
}

if (!Validation::validateNIC($nic)) {
    echo json_encode(['status' => false, 'message' => 'NIC is not valid']);
    exit();
}

if (!Validation::validateMobile($contact)) {
    echo json_encode(['status' => false, 'message' => 'Contact number is not valid']);
    exit();
}

if (!Validation::validateEmail($email)) {
    echo json_encode(['status' => false, 'message' => 'Email is not valid']);
    exit();
}


// Make sure the dates are still free
$overlap = Database::search("SELECT `id` FROM `bookings` WHERE `properties_id` = '$propertyId' AND `checkIn` < '$checkOut' AND `checkOut` > '$checkIn'");
if ($overlap && $overlap->num_rows > 0) {
    echo json_encode(['status' => false, 'message' => 'This property is not available for the selected dates']);
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
