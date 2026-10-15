<?php
session_start();
include '../includes/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(["status" => false, "message" => "Please sign in to checkout"]);
    exit();
}

$merchant_id = '1227844';
$order_id = uniqid();
$currency = 'LKR';
$merchant_secret = 'MzI2MTAyMzMwODExMjkxMDI3OTU0MTE5MDQ5NjE5MTMzNzgxNjAzMg==';

$firstName = trim($_POST['firstName'] ?? '');
$lastName = trim($_POST['lastName'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$email = trim($_POST['email'] ?? '');
$nic = trim($_POST['nic'] ?? '');

if ($firstName === '' || $lastName === '' || $contact === '' || $email === '' || $nic === '') {
    echo json_encode(["status" => false, "message" => "All fields are required"]);
    exit();
}

if (strlen($firstName) > 20 || strlen($lastName) > 20) {
    echo json_encode(["status" => false, "message" => "Name is too long"]);
    exit();
}

if (!Validation::validateName($firstName) || !Validation::validateName($lastName)) {
    echo json_encode(["status" => false, "message" => "Names must contain only letters"]);
    exit();
}

if (!Validation::validateNIC($nic)) {
    echo json_encode(["status" => false, "message" => "NIC is not valid"]);
    exit();
}

if (!Validation::validateMobile($contact)) {
    echo json_encode(["status" => false, "message" => "Contact number is not valid"]);
    exit();
}

if (!Validation::validateEmail($email)) {
    echo json_encode(["status" => false, "message" => "Email is not valid"]);
    exit();
}

// The amount is always calculated from the saved cart, never from the browser
$cart = Validation::getCartCheckout($_SESSION['email']);
if (isset($cart['error'])) {
    echo json_encode(["status" => false, "message" => $cart['error']]);
    exit();
}

$amount = number_format($cart['total'], 2, '.', '');

$hash = strtoupper(
    md5(
        $merchant_id .
            $order_id .
            $amount .
            $currency .
            strtoupper(md5($merchant_secret))
    )
);

echo json_encode([
    'status' => true,
    'message' => 'success',
    'merchant_id' => $merchant_id,
    'order_id' => $order_id,
    'items' => 'Booking for ' . count($cart['items']) . ' properties',
    'amount' => $amount,
    'hash' => $hash,
    'first_name' => $firstName,
    'last_name' => $lastName,
    'email' => $email,
    'phone' => $contact,
    'address' => 'N/A',
    'city' => 'N/A',
    'country' => 'Sri Lanka',
    'delivery_address' => 'N/A',
    'delivery_city' => 'N/A',
    'delivery_country' => 'Sri Lanka'
]);
