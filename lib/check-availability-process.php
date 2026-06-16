<?php

include '../includes/connection.php';

$listingId = Database::escape($_POST['listingId']);
$checkIn = Database::escape($_POST['checkIn']);
$checkOut = Database::escape($_POST['checkOut']);

$bookingsStmt = Database::search("SELECT * FROM `bookings` WHERE `properties_id` = '$listingId'");
$bookings = [];
while ($row = $bookingsStmt->fetch_assoc()) {
  $bookings[] = $row;
}

$isAvailable = true;

foreach ($bookings as $booking) { 
  $existingCheckIn = $booking['checkIn'];
  $existingCheckOut = $booking['checkOut'];

  // Check if the new booking dates overlap with any existing booking dates
  if (($checkIn >= $existingCheckIn && $checkIn <= $existingCheckOut) ||
    ($checkOut >= $existingCheckIn && $checkOut <= $existingCheckOut) ||
    ($checkIn <= $existingCheckIn && $checkOut >= $existingCheckOut)
  ) {
    $isAvailable = false;
    break;
  }
}

if ($isAvailable) {
  echo "success";
} else {
  echo "This property is not available for the selected dates";
}
