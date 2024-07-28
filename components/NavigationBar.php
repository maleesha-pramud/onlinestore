<?php
session_start();

$RootPath = dirname(__FILE__) . '/..';

include($RootPath . '/includes/connection.php');

if (isset($_SESSION['email'])) {
  $email = $_SESSION['email'];
  $userStmt = Database::search("SELECT * FROM `users` WHERE `email` = '$email'");
  $userData = $userStmt->fetch_assoc();
}

?>

<nav class="navbar navbar-expand-lg navbar-light justify-content-between align-items-center px-5">
  <a class="navbar-brand" href="#">STAY LANKA</a>
  <ul class="list-unstyled d-flex gap-5 mb-0">
    <li class="navbar-item">Board Room</li>
    <li class="navbar-item">Hotel Room</li>
    <li class="navbar-item">Guest House</li>
    <li class="navbar-item">Apartment</li>
    <li onclick="navbarMenuToggle()" class="py-1 px-2 rounded-3 bg-secondary bg-opacity-10 cursor-pointer position-relative">
      <i class="fa-regular fa-user"></i>
      <i class="fa-solid fa-bars"></i>
      <!-- Popup Start -->
      <div class="popup-menu position-absolute end-0 popup-box mt-2 rounded-4" style="display: none;">
        <?php
        if (isset($userData['user_type_id'])) {
          if ($userData['user_type_id'] == 1) {
            echo '<a href="/onlinestore/admin/listing/add.php" class="d-block py-2 text-center text-dark text-decoration-none fs-7">Add Listing</a>';
          }
        } else {
          echo '<a href="/onlinestore/signin.php" class="d-block py-2 px-5 text-dark text-decoration-none fs-7">Log In</a>';
        }
        ?>
        <a href="#" class="d-block py-2 px-5 text-dark text-decoration-none fs-7">Settings</a>
        <a href="#" class="d-block py-2 px-5 text-dark text-decoration-none fs-7">Account</a>
      </div>
      <!-- Popup End -->
    </li>
  </ul>
</nav>