<?php
session_start();
include './includes/connection.php';

// Auth check
if (!isset($_SESSION['email'])) {
    header('Location: /signin.php');
    exit();
}

$email = $_SESSION['email'];
$userStmt = Database::search("SELECT * FROM `users` WHERE `email` = '$email'");
$userData = $userStmt->fetch_assoc();

// Fetch bookings for this user
$bookingsStmt = Database::search("SELECT b.*, p.title as property_title, p.images 
                                 FROM bookings b 
                                 JOIN properties p ON b.properties_id = p.id 
                                 WHERE b.email = '$email' 
                                 ORDER BY b.checkIn DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include './components/head.php'; ?>
    <link rel="stylesheet" href="./assets/css/dashboard.css" />
</head>
<body>
    <?php include './components/NavigationBar.php'; ?>

    <main class="container py-5">
        <div class="page-header">
            <h1>My Bookings</h1>
            <p class="text-secondary">Manage your upcoming and past trips.</p>
        </div>

        <div class="row g-4">
            <?php if ($bookingsStmt->num_rows > 0) {
                while ($booking = $bookingsStmt->fetch_assoc()) { 
                    $images = explode(',', $booking['images']);
                    $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                    ?>
                    <div class="col-12">
                        <div class="card h-100">
                            <div class="row g-0">
                                <div class="col-md-3">
                                    <img src="assets/images/properties/<?php echo $firstImage; ?>" class="img-fluid rounded-start h-100" alt="..." style="object-fit: cover; min-height: 200px;">
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 class="card-title fs-4 fw-bold"><?php echo $booking['property_title']; ?></h5>
                                                <p class="text-secondary mb-2">Order ID: <?php echo $booking['order_id']; ?></p>
                                            </div>
                                            <span class="badge bg-primary-soft text-primary">Confirmed</span>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <p class="mb-1 fw-semibold">Dates</p>
                                                <p class="text-secondary"><?php echo date("M d", strtotime($booking['checkIn'])); ?> - <?php echo date("M d, Y", strtotime($booking['checkOut'])); ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-1 fw-semibold">Guests</p>
                                                <p class="text-secondary"><?php echo $booking['guests']; ?> guest(s)</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-1 fw-semibold">Total Paid</p>
                                                <p class="text-secondary fw-bold">$<?php echo number_format($booking['total_price'], 2); ?></p>
                                            </div>
                                        </div>

                                        <div class="mt-3 text-end">
                                            <a href="/single-property.php?id=<?php echo $booking['properties_id']; ?>" class="btn btn-outline-primary btn-sm me-2">View Property</a>
                                            <button class="btn btn-outline-danger btn-sm">Cancel Booking</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fa-solid fa-calendar-times fs-1 text-secondary mb-3"></i>
                        <h4>No bookings found.</h4>
                        <p class="text-secondary">You haven't made any bookings yet.</p>
                        <a href="/index.php" class="btn btn-primary mt-3">Start Exploring</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

    <?php include './components/script.php'; ?>
</body>
</html>
