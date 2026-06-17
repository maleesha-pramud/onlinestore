<?php
if (isset($_SESSION['email'])) {
  $email = $_SESSION['email'];
  $userStmt = Database::search("SELECT * FROM `users` WHERE `email` = '$email'");
  $userData = $userStmt->fetch_assoc();
}
?>

<header class="main-header">
    <div class="container">
        <nav class="main-nav">
            <a class="nav-brand" href="/index.php">
                <img src="/assets/images/logo/logo-only-text.png" alt="2nd Home" style="height: 40px;">
            </a>

            <form action="/search.php" method="GET" class="nav-search">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="q" placeholder="Search destinations, properties..." class="search-input" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                <button type="submit" class="search-btn">Search</button>
            </form>

            <div class="nav-user-menu">
                <?php if (!isset($userData['user_type_id']) || $userData['user_type_id'] == 3) { ?>
                    <a href="/signup.php" class="nav-link-host">Become a host</a>
                <?php } ?>
                <div class="nav-dropdown">
                    <button class="dropdown-toggle-btn">
                        <i class="fa-solid fa-bars"></i>
                        <i class="fa-solid fa-user-circle"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <?php
                        if (isset($userData['user_type_id'])) {
                            if ($userData['user_type_id'] == 1) {
                                echo '<li><a class="dropdown-item bold" href="/admin/dashboard.php">Admin Dashboard</a></li>';
                            } else if ($userData['user_type_id'] == 2) {
                                echo '<li><a class="dropdown-item bold" href="/producer/dashboard.php">Host Dashboard</a></li>';
                            }
                            
                            echo '<li><a class="dropdown-item" href="/my-bookings.php">My Bookings</a></li>';
                            
                            if ($userData['user_type_id'] == 1 || $userData['user_type_id'] == 2) {
                                echo '<li><a class="dropdown-item" href="/listing/bookings.php">Manage Bookings</a></li>';
                            }

                            echo '<li><a class="dropdown-item" href="/account-settings.php">Account Settings</a></li>';
                            echo '<div class="dropdown-divider"></div>';
                            echo '<li><a class="dropdown-item" href="/logout.php">Log Out</a></li>';
                        } else {
                            echo '<li><a class="dropdown-item bold" href="/signin.php">Log In</a></li>';
                            echo '<li><a class="dropdown-item" href="/signup.php">Sign Up</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>