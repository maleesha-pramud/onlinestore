<?php

include '../includes/connection.php';

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$mobile = $_POST['mobile'];
$email = $_POST['email'];
$password = $_POST['password'];

if (empty($fname)) {
    echo ("First name is required");
} else if (strlen($fname) > 20) {
    echo ("First name is too long");
} else if (empty($lname)) {
    echo ("Last name is required");
} else if (strlen($lname) > 20) {
    echo ("Last name is too long");
} else if (empty($mobile)) {
    echo ("Mobile is required");
} else if (!preg_match('/07[0,1,2,4,5,6,7,8]{1}[0-9]{7}/', $mobile)) {
    echo ("Mobile is not valid");
} else if (empty($email)) {
    echo ("Email is required");
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo ("Email is not valid");
} else if (empty($password)) {
    echo ("Password is required");
} else if (strlen($password) < 5) {
    echo ("Password is too short");
} else {

    $rs = Database::search("SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'");
    if ($rs->num_rows > 0) {
        echo ("User already exists");
        return;
    }

    $query = "INSERT INTO users (`fname`, `lname`, `mobile`, `email`, `password`, `user_type_id`) VALUES ('$fname', '$lname', '$mobile', '$email', '$password', '2')";
    Database::iud($query);
    echo ("success");
}
