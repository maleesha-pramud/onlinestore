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
                     <a href="/listing/bookings.php" class="btn btn-primary">View All</a>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table datatable align-middle mb-0">
                                <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Property</th>
                                <th>Total Price</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recentBookings = Database::search("SELECT b.*, p.title as property_title FROM bookings b JOIN properties p ON b.properties_id = p.id ORDER BY b.id DESC LIMIT 5");
                            while ($booking = $recentBookings->fetch_assoc()) {
                            ?>
                            <tr>
                                <td>#<?php echo $booking['order_id']; ?></td>
                                <td><?php echo $booking['first_name'] . ' ' . $booking['last_name']; ?></td>
                                <td><?php echo $booking['property_title']; ?></td>
                                <td>LKR <?php echo number_format($booking['total_price'], 2); ?></td>
                                <td><?php echo date("M d, Y", strtotime($booking['checkIn'])); ?></td>
                            </tr>
                            <?php 
                                }
                            ?>
                        </tbody>
                    </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
    <?php include '../components/script.php'; ?>
</body>
</html>