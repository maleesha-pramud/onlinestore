<?php

include '../includes/connection.php';

$RootFilePath = dirname(__FILE__);

$id = $_POST['id'];
$name = $_POST['name'];

if (empty($name)) {
  echo ("Name is required");
} else if (empty($id)) {
  echo ("ID is required");
} else {

  Database::iud("UPDATE `categories` SET `name` = '$name' WHERE `id` = $id");

  echo ("success");
}
