<?php
session_start();
include '../includes/connection.php';

// Auth check
if (!isset($_SESSION['email'])) {
    header('Location: /signin.php');
    exit();
} else {
    $email = $_SESSION['email'];
    $userStmt = Database::search("SELECT * FROM `users` WHERE `email` = '$email'");
    $userData = $userStmt->fetch_assoc();
    if ($userData['user_type_id'] != 1) {
        header('Location: /index.php');
        exit();
    }
}

// Data fetching
$propertyCount = Database::search("SELECT * FROM properties")->num_rows;
$userCount = Database::search("SELECT * FROM users WHERE user_type_id = 3")->num_rows;
$producerCount = Database::search("SELECT * FROM users WHERE user_type_id = 2")->num_rows;
$bookingCount = Database::search("SELECT * FROM bookings")->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../components/head.php'; ?>
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
</head>
<body>
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar">
            <?php include '../components/AdminSidebar.php'; ?>
        </aside>
        <main class="dashboard-main">
            <div class="page-header">
                <h1>Admin Dashboard</h1>
                <p class="text-secondary">Welcome back, <?php echo $userData['fname']; ?>!</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card h-100">
                        <h3 class="stat-title">Total Properties</h3>
                        <p class="stat-value"><?php echo $propertyCount; ?></p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card h-100">
                        <h3 class="stat-title">Total Bookings</h3>
                        <p class="stat-value"><?php echo $bookingCount; ?></p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card h-100">
                        <h3 class="stat-title">Registered Users</h3>
                        <p class="stat-value"><?php echo $userCount; ?></p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card h-100">
                        <h3 class="stat-title">Property Hosts</h3>
                        <p class="stat-value"><?php echo $producerCount; ?></p>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                     <h2 class="section-title mb-0">Recent Bookings</h2>
                     <a href="#" class="btn btn-primary">View All</a>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Property ID</th>
                                <th>Total Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example Row -->
                            <tr>
                                <td>#12345</td>
                                <td>John Doe</td>
                                <td>PROP56</td>
                                <td>$350.00</td>
                                <td><span class="badge bg-success-soft text-success">Confirmed</span></td>
                            </tr>
                             <tr>
                                <td>#12346</td>
                                <td>Jane Smith</td>
                                <td>PROP78</td>
                                <td>$500.00</td>
                                <td><span class="badge bg-warning-soft text-warning">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
    <?php include '../components/script.php'; ?>
</body>
</html>