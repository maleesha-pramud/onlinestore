<?php

include 'connection.php';

$checkIn = $_POST['checkIn'];
$checkOut = $_POST['checkOut'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$nic = $_POST['nic'];
$contact = $_POST['contact'];
$guests = $_POST['guests'];
$email = $_POST['email'];
$note = $_POST['note'];


$rs = Database::search("SELECT * FROM `booking` WHERE `checkIn` = '$checkIn' AND `checkOut` = '$checkOut'");
if ($rs->num_rows > 0) {
    echo ("This booking already exists");
    return;
}

$query = "INSERT INTO users (`fname`, `lname`, `mobile`, `email`, `password`, `user_type_id`) VALUES ('$fname', '$lname', '$mobile', '$email', '$password', '2')";
Database::iud($query);
echo ("success");
