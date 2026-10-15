<?php
session_start();
include '../includes/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => false, 'message' => 'Please sign in to checkout']);
    exit();
}

$firstName = trim($_POST['firstName'] ?? '');
$lastName = trim($_POST['lastName'] ?? '');
$nic = trim($_POST['nic'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$email = trim($_POST['email'] ?? '');
$specialRequests = trim($_POST['specialRequests'] ?? '');
$orderId = trim($_POST['orderId'] ?? '');

if ($firstName === '' || $lastName === '' || $nic === '' || $contact === '' || $email === '' || $orderId === '') {
    echo json_encode(['status' => false, 'message' => 'All booking fields are required']);
    exit();
}

if (strlen($firstName) > 20 || strlen($lastName) > 20 || !Validation::validateName($firstName) || !Validation::validateName($lastName)) {
    echo json_encode(['status' => false, 'message' => 'Names must contain only letters (max 20 characters)']);
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

if (mb_strlen($specialRequests) > 500 || strlen($orderId) > 100) {
    echo json_encode(['status' => false, 'message' => 'Invalid booking details']);
    exit();
}

$cart = Validation::getCartCheckout($_SESSION['email']);
if (isset($cart['error'])) {
    echo json_encode(['status' => false, 'message' => $cart['error']]);
    exit();
}

$firstName = Database::escape($firstName);
$lastName = Database::escape($lastName);
$nic = Database::escape($nic);
$contact = Database::escape($contact);
$email = Database::escape($email);
$specialRequests = Database::escape($specialRequests);
$orderId = Database::escape($orderId);

// One booking row per property, all sharing the same payment order id
foreach ($cart['items'] as $item) {
    Database::iud("INSERT INTO `bookings` (`checkIn`,`checkOut`,`first_name`,`last_name`,`nic`,`contact`,`guests`,`email`,`special_requests`,`properties_id`,`total_price`,`order_id`) VALUES ('" . $item['checkIn'] . "','" . $item['checkOut'] . "','$firstName','$lastName','$nic','$contact','" . $item['guests'] . "','$email','$specialRequests','" . $item['properties_id'] . "','" . $item['total'] . "','$orderId')");
    Database::iud("DELETE FROM `cart` WHERE `id` = '" . $item['cart_id'] . "'");
}

echo json_encode(['status' => true, 'message' => 'Booked Successfully']);
