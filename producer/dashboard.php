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
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table datatable align-middle mb-0">
                                <thead class="table-light">
                            <tr>
                                <th>Property Name</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recentProperties = Database::search("SELECT p.*, c.name as category_name FROM properties p JOIN categories c ON p.categories_id = c.id WHERE p.users_id = '$userId' ORDER BY p.id DESC LIMIT 5");
                            while ($property = $recentProperties->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo $property['title']; ?></td>
                                <td>LKR <?php echo number_format($property['base_price'], 2); ?> / night</td>
                                <td><span class="badge bg-primary-soft text-primary"><?php echo $property['category_name']; ?></span></td>
                                <td class="action-links">
                                    <a href="/listing/edit.php?id=<?php echo $property['id']; ?>">Edit</a>
                                    <a href="#" class="text-danger" onclick="deleteListing(<?php echo $property['id']; ?>)">Delete</a>
                                </td>
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