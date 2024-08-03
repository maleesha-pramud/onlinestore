<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

$name = $_POST['name'];

if (empty($name)) {
  echo ("Name is required");
} else {

  Database::iud("INSERT INTO `categories` (`name`) VALUES ('$name')");

  echo ("success");
}
