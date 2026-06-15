<?php
session_start();
include './includes/connection.php';

// Return to home page if id is undefined
if (isset($_GET['id'])) {
    $propertyId = $_GET['id'];
} else {
    header('location: /');
    exit();
}

$checkIn = $_GET['checkIn'] ?? '';
$checkOut = $_GET['checkOut'] ?? '';
$guests = $_GET['guests'] ?? '1';

// Calculate the date count
if ($checkIn && $checkOut) {
    $date1 = new DateTime($checkIn);
    $date2 = new DateTime($checkOut);
    $dateDifferenceObj = $date1->diff($date2);
    $dateCount = $dateDifferenceObj->days;
} else {
    $dateCount = 1; // Default to 1 night if dates are not set
}


// Fetching the property details
$propertyStmt = Database::search("SELECT * FROM `properties` WHERE `id` = '$propertyId'");
$propertyData = $propertyStmt->fetch_assoc();
 
// Convert value list string to array
$images = explode(',', $propertyData['images']);

// Calculate Total Price Based on date count and base price
$totalPrice = $propertyData['base_price'] * $dateCount;
$serviceFee = $totalPrice * 0.1; // Example 10% service fee
$grandTotal = $totalPrice + $serviceFee;

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
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="page-header">
                    <h1>Confirm and pay</h1>
                </div>
                <div class="card form-card">
                    <h3 class="mb-4">Your trip</h3>
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-bold mb-1">Dates</p>
                            <p class="text-secondary"><?php echo date("M d, Y", strtotime($checkIn)); ?> - <?php echo date("M d, Y", strtotime($checkOut)); ?></p>
                        </div>
                        <a href="#">Edit</a>
                    </div>
                     <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-bold mb-1">Guests</p>
                            <p class="text-secondary"><?php echo htmlspecialchars($guests); ?> guest(s)</p>
                        </div>
                        <a href="#">Edit</a>
                    </div>

                    <hr class="my-4">

                     <form onsubmit="checkoutPayment('<?php echo $checkIn ?>','<?php echo $checkOut ?>',<?php echo $propertyId ?>,<?php echo $grandTotal ?>); return false;">
                        <h3 class="mb-4">Pay with</h3>
                        <p class="text-secondary">Credit/Debit Card (via PayHere)</p>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="contact" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contact" name="contact" required>
                        </div>
                         <div class="form-group mt-3">
                            <label for="nic" class="form-label">NIC</label>
                            <input type="text" class="form-control" id="nic" name="nic" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="specialRequests" class="form-label">Special Requests (optional)</label>
                            <textarea class="form-control" id="specialRequests" name="specialRequests" rows="3"></textarea>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Confirm and Pay</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card booking-card" style="top: 100px;">
                    <div class="card-body">
                        <div class="d-flex gap-3">
                            <img src="./assets/images/properties/<?php echo $images[0]; ?>" class="img-fluid rounded" alt="Property Image" style="width: 120px; height: 100px; object-fit: cover;">
                            <div>
                                <h5 class="fw-bold mb-1"><?php echo $propertyData['title']; ?></h5>
                                <p class="text-secondary small"><?php echo $propertyData['address']; ?></p>
                            </div>
                        </div>
                        <hr class="my-4">
                        <h4 class="mb-4">Price details</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <p>LKR <?php echo number_format($propertyData['base_price'], 2); ?> x <?php echo $dateCount; ?> nights</p>
                            <p>LKR <?php echo number_format($totalPrice, 2); ?></p>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <p>Service fee</p>
                            <p>LKR <?php echo number_format($serviceFee, 2); ?></p>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-3">
                            <p>Total (LKR)</p>
                            <p>LKR <?php echo number_format($grandTotal, 2); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
    <?php include 'components/script.php'; ?>
</body>
</html>