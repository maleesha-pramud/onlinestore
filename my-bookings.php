<?php
session_start();
include './includes/connection.php';

// Auth check
if (!isset($_SESSION['email'])) {
    header('Location: /signin.php');
    exit();
}

$email = $_SESSION['email'];

// Fetch bookings for this user
$bookingsStmt = Database::search("SELECT b.*, p.title as property_title, p.images, p.base_price 
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
        <div class="page-header mb-5">
            <h1 class="fw-bold">My Bookings</h1>
            <p class="text-secondary">Manage your upcoming and past trips.</p>
        </div>

        <div class="row g-4">
            <?php if ($bookingsStmt->num_rows > 0) {
                while ($booking = $bookingsStmt->fetch_assoc()) { 
                    $images = explode(',', $booking['images']);
                    $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                    ?>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm overflow-hidden">
                            <div class="row g-0">
                                <div class="col-md-3">
                                    <img src="assets/images/properties/<?php echo $firstImage; ?>" class="img-fluid h-100" alt="..." style="object-fit: cover; min-height: 200px;">
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h5 class="card-title fs-4 fw-bold mb-1"><?php echo $booking['property_title']; ?></h5>
                                                <p class="text-secondary small mb-0">Order ID: <span class="text-dark fw-medium"><?php echo $booking['order_id']; ?></span></p>
                                            </div>
                                            <span class="badge bg-success-subtle text-success px-3 py-2">Confirmed</span>
                                        </div>
                                        
                                        <div class="row g-3 mb-4">
                                            <div class="col-sm-6 col-md-3">
                                                <p class="text-secondary small mb-1">Check-in</p>
                                                <p class="fw-semibold mb-0"><?php echo date("M d, Y", strtotime($booking['checkIn'])); ?></p>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <p class="text-secondary small mb-1">Check-out</p>
                                                <p class="fw-semibold mb-0"><?php echo date("M d, Y", strtotime($booking['checkOut'])); ?></p>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <p class="text-secondary small mb-1">Guests</p>
                                                <p class="fw-semibold mb-0"><?php echo $booking['guests']; ?> guest(s)</p>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <p class="text-secondary small mb-1">Total Paid</p>
                                                <p class="fw-bold text-primary mb-0">LKR <?php echo number_format($booking['total_price'], 2); ?></p>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 border-top pt-3">
                                            <a href="/single-property.php?id=<?php echo $booking['properties_id']; ?>" class="btn btn-light btn-sm px-3">View Property</a>
                                            <button class="btn btn-outline-primary btn-sm px-3" onclick="openEditBookingModal(<?php echo htmlspecialchars(json_encode($booking)); ?>)">Edit Details</button>
                                            <button class="btn btn-outline-danger btn-sm px-3" onclick="deleteBooking(<?php echo $booking['id']; ?>)">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <div class="col-12">
                    <div class="card border-0 shadow-sm py-5 text-center">
                        <div class="card-body">
                            <i class="fa-solid fa-calendar-times fs-1 text-secondary mb-3 opacity-25"></i>
                            <h4 class="fw-bold">No bookings found</h4>
                            <p class="text-secondary mb-4">You haven't made any bookings yet.</p>
                            <a href="/index.php" class="btn btn-primary px-4">Start Exploring</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

    <!-- Edit Booking Modal -->
    <div class="modal fade" id="editBookingModal" tabindex="-1" aria-labelledby="editBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" id="editBookingModalLabel">Edit Booking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <form id="editBookingForm">
                        <input type="hidden" id="editBookingId">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" id="editFirstName" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="editLastName" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">NIC</label>
                                <input type="text" class="form-control" id="editNIC" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="editContact" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Special Requests</label>
                                <textarea class="form-control" id="editSpecialRequests" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary px-4" onclick="updateBooking()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <?php include './components/script.php'; ?>
    <script>
        function openEditBookingModal(booking) {
            document.getElementById('editBookingId').value = booking.id;
            document.getElementById('editFirstName').value = booking.first_name;
            document.getElementById('editLastName').value = booking.last_name;
            document.getElementById('editNIC').value = booking.nic;
            document.getElementById('editContact').value = booking.contact;
            document.getElementById('editSpecialRequests').value = booking.special_requests;
            
            var modal = new bootstrap.Modal(document.getElementById('editBookingModal'));
            modal.show();
        }
    </script>
</body>
</html>
