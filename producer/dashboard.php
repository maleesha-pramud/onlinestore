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
    if ($userData['user_type_id'] != 2) {
        header('Location: /index.php');
        exit();
    }
}

// Data fetching
$userId = $userData['id'];
$propertyCount = Database::search("SELECT * FROM properties WHERE users_id = '$userId'")->num_rows;
$bookingCount = Database::search("SELECT b.* FROM bookings b JOIN properties p ON b.properties_id = p.id WHERE p.users_id = '$userId'")->num_rows;
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
            <?php include '../components/ProducerSidebar.php'; ?>
        </aside>
        <main class="dashboard-main">
            <div class="page-header">
                <h1>Host Dashboard</h1>
                <p class="text-secondary">Welcome back, <?php echo $userData['fname']; ?>!</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card stat-card h-100">
                        <h3 class="stat-title">My Properties</h3>
                        <p class="stat-value"><?php echo $propertyCount; ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card stat-card h-100">
                        <h3 class="stat-title">Total Bookings Received</h3>
                        <p class="stat-value"><?php echo $bookingCount; ?></p>
                    </div>
                </div>
            </div>

             <div class="mt-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                     <h2 class="section-title mb-0">My Recent Listings</h2>
                     <a href="/listing/list.php" class="btn btn-primary">View All</a>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Property Name</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example Row -->
                            <tr>
                                <td>Waterside Apartment</td>
                                <td>$250.00 / night</td>
                                <td><span class="badge bg-success-soft text-success">Active</span></td>
                                <td class="action-links">
                                    <a href="#">Edit</a>
                                    <a href="#" class="text-danger">Delete</a>
                                </td>
                            </tr>
                             <tr>
                                <td>Ebony Suite</td>
                                <td>$400.00 / night</td>
                                <td><span class="badge bg-warning-soft text-warning">Pending Review</span></td>
                                <td class="action-links">
                                    <a href="#">Edit</a>
                                    <a href="#" class="text-danger">Delete</a>
                                </td>
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