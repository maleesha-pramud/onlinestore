<?php

include '../includes/connection.php';

$password = $_POST['password'];
$cPassword = $_POST['cPassword'];
$vcode = $_POST['vcode'];

header('Content-Type: application/json');

if (empty($password) || empty($cPassword)) {
  echo json_encode([
    "message" => "Password can't be empty",
    "status" => false,
  ]);
} else if ($password !== $cPassword) {
  echo json_encode([
    "message" => "Passwords do not match",
    "status" => false,
  ]);
} else if (empty($vcode)) {
  echo json_encode([
    "message" => "Please resend a forgot password request",
    "status" => false,
  ]); 
} else {
  $rs = Database::search("SELECT * FROM `users` WHERE `vcode` = '$vcode'");
  if ($rs->num_rows == 0) {
    echo json_encode([
      "message" => "Invalid verification code",
      "status" => false,
    ]);
  } else {
    $row = $rs->fetch_assoc();
    Database::iud("UPDATE `users` SET `password` = '$password', `vcode` = Null WHERE `id` = '" . $row['id'] . "'");
    echo json_encode([
      "message" => "Password reset successfully",
      "status" => true,
    ]);
  }
}
