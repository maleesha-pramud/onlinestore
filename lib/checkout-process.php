<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

$merchant_id = '1227844';
$order_id = uniqid();
$amount = 110; // This should be dynamically set based on the form data or other logic
$currency = 'LKR';
$merchant_secret = 'MzI2MTAyMzMwODExMjkxMDI3OTU0MTE5MDQ5NjE5MTMzNzgxNjAzMg==';

// Retrieve form data
$first_name = $_POST['firstName'];
$last_name = $_POST['lastName'];
$nic = $_POST['nic'];
$contact = $_POST['contact'];
$guests = $_POST['guests'];
$email = $_POST['email'];
$note = $_POST['note'];

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
  'success' => true,
  'order_id' => $order_id,
  'items' => 'Booking for ' . $guests . ' guests',
  'amount' => $amount,
  'hash' => $hash,
  'first_name' => $first_name,
  'last_name' => $last_name,
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
