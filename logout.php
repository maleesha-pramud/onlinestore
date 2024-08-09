<?php

session_start();
session_destroy();

// Remove cookies
if (isset($_COOKIE['email'])) {
  setcookie('email', '', time() - 3600, '/', '', false, true);
}
if (isset($_COOKIE['password'])) {
  setcookie('password', '', time() - 3600, '/', '', false, true);
}

// Redirect to another page
header('Location: /onlinestore');
exit;
