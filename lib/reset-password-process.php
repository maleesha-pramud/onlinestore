<?php

$password = $_POST['password'];
$cPassword = $_POST['cPassword'];
$vcode = $_POST['vcode'];

if (empty($password) || empty($cPassword)) {
  echo ('Password can\'t be empty');
} else if ($password !== $cPassword) {
  echo ('Passwords do not match');
} else if (empty($vcode)) {
  echo ('Please resend a forgot password request');
} else {
  include 'connection.php';
  $rs = Database::search("SELECT * FROM `users` WHERE `vcode` = '$vcode'");
  if ($rs->num_rows == 0) {
    echo ('Invalid verification code');
  } else {
    $row = $rs->fetch_assoc();
    Database::iud("UPDATE `users` SET `password` = '$password', `vcode` = Null WHERE `id` = '" . $row['id'] . "'");
    echo ('Password reset successfully');
  }
}
