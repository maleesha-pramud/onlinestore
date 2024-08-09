<?php
session_start();

if (isset($_SESSION['email'])) {
  $email = $_SESSION['email'];
  $userStmt = Database::search("SELECT * FROM `users` WHERE `email` = '$email'");
  $userData = $userStmt->fetch_assoc();
}

$categoriesStmt = Database::search("SELECT * FROM `categories`");

?>

<nav class="navbar bg-midGray navbar-expand-lg navbar-light justify-content-between align-items-center px-5">
  <a class="navbar-brand" href="/onlinestore/index.php">ApeBordima.LK</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
      <?php while ($category = $categoriesStmt->fetch_assoc()) { ?>
        <li class="nav-item">
          <a class="nav-link" href="/onlinestore/search.php?id=<?php echo $category['id'] ?>"><?php echo $category['name'] ?></a>
        </li>
      <?php } ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa-regular fa-user"></i>
          <i class="fa-solid fa-bars"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
          <?php
          if (isset($userData['user_type_id'])) {
            if ($userData['user_type_id'] != 3) {
              if ($userData['user_type_id'] == 1) {
                echo '<li><a class="dropdown-item" href="/onlinestore/admin/dashboard.php">Dashboard</a></li>';
              } else {
                echo '<li><a class="dropdown-item" href="/onlinestore/producer/dashboard.php">Dashboard</a></li>';
              }
            }
          ?>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Account</a></li>
            <li><a class="dropdown-item" href="/onlinestore/logout.php">Logout</a></li>
          <?php
          } else {
            echo '<li><a class="dropdown-item" href="/onlinestore/signin.php">Log In</a></li>';
          }
          ?>
        </ul>
      </li>
    </ul>
  </div>
</nav>