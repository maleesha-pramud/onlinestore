<?php

session_start();
session_destroy();

// Remove cookies
setcookie('email', '', time() - 3600, '/');
setcookie('password', '', time() - 3600, '/');


// Redirect to another page
header('Location: /onlinestore');
exit;