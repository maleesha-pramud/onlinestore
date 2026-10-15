<?php
session_start();
include './includes/connection.php';

if (!isset($_SESSION['email'])) {
    header('Location: /signin.php');
    exit();
}

$email = $_SESSION['email'];
$userStmt = Database::search("SELECT `id` FROM `users` WHERE `email` = '$email'");
$userData = $userStmt->fetch_assoc();
$userId = $userData['id'];
$grandTotal = 0;
$cartErrors = [];

// Fetch cart items
$cartStmt = Database::search("
    SELECT c.id AS cart_id, c.checkIn, c.checkOut, c.guests as cart_guests, p.*, AVG(r.rating) AS avg_rating, COUNT(r.id) AS review_count 
    FROM cart c 
    JOIN properties p ON c.properties_id = p.id 
    LEFT JOIN reviews r ON p.id = r.properties_id 
    WHERE c.users_id = '$userId' AND p.status_id = 1
    GROUP BY c.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include './components/head.php'; ?>
    <link rel="stylesheet" href="./assets/css/search.css" />
    <link rel="stylesheet" href="./assets/css/dashboard.css" />
</head>
<body class="bg-light">
    <?php include './components/NavigationBar.php'; ?>

    <main class="container py-5">
        <div class="mb-5">
            <h1 class="fw-bold">My Booking Cart</h1>
            <p class="text-secondary">Review your selected properties and proceed to multi-checkout.</p>
        </div>

        <div class="row g-4">
            <?php if ($cartStmt->num_rows > 0) {
                while ($property = $cartStmt->fetch_assoc()) { ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="property-card-modern card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 1.5rem;">
                            <a href="./single-property.php?id=<?php echo $property['id'] ?>" class="text-decoration-none">
                                <div class="position-relative overflow-hidden" style="height: 250px;">
                                    <?php
                                    $images = explode(',', $property['images']);
                                    $firstImage = !empty($images[0]) ? $images[0] : 'default.jpg';
                                    ?>
                                    <img src="assets/images/properties/<?php echo $firstImage; ?>" class="card-img-top h-100 w-100 object-fit-cover transition-all" alt="<?php echo $property['title']; ?>">
                                    <div class="position-absolute top-0 start-0 p-3">
                                        <span class="badge bg-white text-dark shadow-sm rounded-pill px-3 py-2 fw-bold small">
                                            <i class="bi bi-star-fill text-warning me-1"></i> 
                                            <?php echo ($property['review_count'] > 0) ? number_format($property['avg_rating'], 1) : "New"; ?>
                                        </span>
                                    </div>
                                    <div class="position-absolute top-0 end-0 p-3">
                                        <button class="btn btn-white btn-sm rounded-circle shadow-sm" style="width: 40px; height: 40px; padding: 0;" onclick="event.preventDefault(); removeFromCart(<?php echo $property['cart_id']; ?>)">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <h5 class="card-title text-dark fw-bold mb-2"><?php echo $property['title']; ?></h5>
                                    
                                    <div class="mb-3 small text-secondary">
                                        <div class="mb-1"><i class="bi bi-calendar-event me-2"></i><?php echo date("M d", strtotime($property['checkIn'])); ?> - <?php echo date("M d, Y", strtotime($property['checkOut'])); ?></div>
                                        <div><i class="bi bi-people me-2"></i><?php echo $property['cart_guests']; ?> Guests</div>
                                    </div>
                                    
                                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                        <p class="card-price mb-0 text-dark">
                                            <?php 
                                            $calc = Validation::calculateBookingTotal($property['id'], (string) $property['checkIn'], (string) $property['checkOut'], (string) $property['cart_guests']);
                                            if (isset($calc['error'])) {
                                                $total = 0;
                                                $cartErrors[] = $property['title'] . ': ' . $calc['error'];
                                            } else {
                                                $total = $calc['total'];
                                            }
                                            $grandTotal += $total;
                                            ?>
                                            <span class="fw-bold fs-5">LKR <?php echo number_format($total, 2); ?></span> <span class="text-secondary small">Total (incl. 10% service fee)</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php } ?>
                
                <div class="col-12 mt-5">
                    <div class="p-4 bg-white rounded-4 shadow-sm border">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="fw-bold mb-1">Total Summary</h3>
                                <p class="text-secondary mb-md-0">Ready to book all <?php echo $cartStmt->num_rows; ?> properties?</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <h2 class="fw-bold text-primary mb-3">LKR <?php echo number_format($grandTotal, 2); ?></h2>
                                <?php if (empty($cartErrors)) { ?>
                                    <button class="btn btn-primary btn-lg rounded-pill px-5" data-bs-toggle="modal" data-bs-target="#cartCheckoutModal">Checkout All</button>
                                <?php } else { ?>
                                    <button class="btn btn-primary btn-lg rounded-pill px-5" disabled>Checkout All</button>
                                    <p class="text-danger small mt-2 mb-0">Fix or remove these items first:<br><?php echo implode('<br>', array_map('htmlspecialchars', $cartErrors)); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php } else { ?>
                <div class="col-12 text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-cart-x display-1 text-muted"></i>
                    </div>
                    <h3 class="fw-bold">Your cart is empty</h3>
                    <p class="text-secondary">Start exploring and add properties to your cart!</p>
                    <a href="/" class="btn btn-primary rounded-pill px-5 mt-3 py-2">Explore Properties</a>
                </div>
            <?php } ?>
        </div>
    </main>


    <?php if ($cartStmt->num_rows > 0) {
        $profileStmt = Database::search("SELECT `fname`, `lname`, `mobile` FROM `users` WHERE `id` = '$userId'");
        $profile = $profileStmt->fetch_assoc();
    ?>
    <div class="modal fade" id="cartCheckoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 1.5rem;">
                <form onsubmit="cartCheckout(); return false;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Confirm and pay</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-secondary">Total: <strong>LKR <?php echo number_format($grandTotal, 2); ?></strong> for <?php echo $cartStmt->num_rows; ?> properties</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" maxlength="20" value="<?php echo htmlspecialchars($profile['fname']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" maxlength="20" value="<?php echo htmlspecialchars($profile['lname']); ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contact" value="<?php echo htmlspecialchars($profile['mobile']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nic" class="form-label">NIC</label>
                            <input type="text" class="form-control" id="nic" required>
                        </div>
                        <div class="mb-3">
                            <label for="specialRequests" class="form-label">Special Requests (optional)</label>
                            <textarea class="form-control" id="specialRequests" rows="3" maxlength="500"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary rounded-pill px-5">Confirm and Pay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>

    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
    <?php include './components/Footer.php'; ?>
    <?php include './components/script.php'; ?>
</body>
</html>
