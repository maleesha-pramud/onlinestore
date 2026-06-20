<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

$merchant_id = '1227844';
$order_id = uniqid();
$currency = 'LKR';
$merchant_secret = 'MzI2MTAyMzMwODExMjkxMDI3OTU0MTE5MDQ5NjE5MTMzNzgxNjAzMg==';

// Retrieve form data
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$contact = $_POST['contact'];
$guests = $_POST['guests'];
$email = $_POST['email'];
$amount = $_POST['totalPrice'];

if (empty($firstName) || empty($lastName) || empty($contact) || empty($guests) || empty($email) || empty($amount)) {
    echo json_encode(["status" => false, "message" => "All fields are required"]);
    exit();
}

if (!Validation::validateName($firstName)) {
    echo json_encode(["status" => false, "message" => "First name must contain only letters"]);
    exit();
}

if (!Validation::validateName($lastName)) {
    echo json_encode(["status" => false, "message" => "Last name must contain only letters"]);
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

// Generate the hash
$hash = strtoupper(
  md5(
    $merchant_id .
      $order_id .
      number_format($amount, 2, '.', '') .
      $currency .
      strtoupper(md5($merchant_secret))
  )
);

// Prepare the response
$response = [
  'status' => true,
  'message' => 'success',
  'success' => true,
  'merchant_id' => $merchant_id,
  'order_id' => $order_id,
  'items' => 'Booking for ' . $guests . ' guests',
  'amount' => $amount,
  'hash' => $hash,
  'first_name' => $firstName,
  'last_name' => $lastName,
  'email' => $email,
  'phone' => $contact,
  'address' => 'N/A', // Add address if available
  'city' => 'N/A', // Add city if available
  'country' => 'Sri Lanka', // Add country if available
  'delivery_address' => 'N/A', // Add delivery address if available
  'delivery_city' => 'N/A', // Add delivery city if available
  'delivery_country' => 'Sri Lanka' // Add delivery country if available
];

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
