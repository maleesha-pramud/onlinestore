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
}

$userId = $userData['id'];
$userType = $userData['user_type_id'];

$bookingsStmt = null;
if ($userType == 1) { // Admin
    $bookingsStmt = Database::search("SELECT * FROM bookings ORDER BY checkIn DESC");
} else { // Producer
    $bookingsStmt = Database::search("SELECT b.* FROM bookings b JOIN properties p ON b.properties_id = p.id WHERE p.users_id = $userId ORDER BY b.checkIn DESC");
}
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
            <?php 
            if ($userType == 1) {
                include '../components/AdminSidebar.php';
            } else {
                include '../components/ProducerSidebar.php';
            }
            ?>
        </aside>
        <main class="dashboard-main">
            <div class="page-header">
                <h1>Manage Bookings</h1>
                <p class="text-secondary">View and manage reservations for your properties.</p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable align-middle">
                            <thead class="table-light">
                        <tr>
                            <th>Guest Name</th>
                            <th>Dates</th>
                            <th>Guests</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($booking = $bookingsStmt->fetch_assoc()) { ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></div>
                                        <div class="small text-secondary"><?php echo htmlspecialchars($booking['email']); ?></div>
                                    </td>
                                    <td>
                                        <div><?php echo date("M d", strtotime($booking['checkIn'])); ?> - <?php echo date("M d, Y", strtotime($booking['checkOut'])); ?></div>
                                    </td>
                                    <td><?php echo $booking['guests']; ?></td>
                                    <td class="fw-bold">LKR <?php echo number_format($booking['total_price'], 2); ?></td>
                                    <td><span class="badge bg-success-soft text-success">Confirmed</span></td>
                                </tr>
                            <?php } ?>
                    </tbody>
                </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php include '../components/script.php'; ?>
</body>
</html>