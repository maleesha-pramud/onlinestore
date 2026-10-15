<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => false, 'message' => 'Please sign in first']);
    exit();
}

$id = Database::escape($_POST['id'] ?? '');
$fname = trim($_POST['fname'] ?? '');
$lname = trim($_POST['lname'] ?? '');
$nic = trim($_POST['nic'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$rawRequests = trim($_POST['specialRequests'] ?? '');

if (!Validation::validateId($id) || $fname === '' || $lname === '' || $nic === '' || $contact === '') {
    echo json_encode(['status' => false, 'message' => 'Please fill all required fields']);
    exit();
}

if (strlen($fname) > 20 || strlen($lname) > 20 || !Validation::validateName($fname) || !Validation::validateName($lname)) {
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

if (mb_strlen($rawRequests) > 500) {
    echo json_encode(['status' => false, 'message' => 'Special requests are too long']);
    exit();
}

$fname = Database::escape($fname);
$lname = Database::escape($lname);
$nic = Database::escape($nic);
$contact = Database::escape($contact);
$specialRequests = Database::escape($rawRequests);

// Security check: Ensure this booking belongs to the logged-in user
$email = $_SESSION['email'];
$checkStmt = Database::search("SELECT * FROM `bookings` WHERE `id` = '$id' AND `email` = '$email'");

if ($checkStmt->num_rows == 0) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized access']);
    exit();
}

Database::iud("UPDATE `bookings` SET `first_name` = '$fname', `last_name` = '$lname', `nic` = '$nic', `contact` = '$contact', `special_requests` = '$specialRequests' WHERE `id` = '$id'");

echo json_encode(['status' => true, 'message' => 'Success']);
