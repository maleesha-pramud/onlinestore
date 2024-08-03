<?php

include './includes/connection.php';

if (isset($_GET['id'])) {
    $propertyId = $_GET['id'];
} else {
    header('location: /onlinestore');
    exit();
}

$propertyStmt = Database::search("SELECT * FROM `properties` WHERE `id` = '$propertyId'");
$propertyData = $propertyStmt->fetch_assoc();
$images = explode(',', $propertyData['images']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include './components/head.php'; ?>
</head>

<body>
    <!-- Navigation Bar Start -->
    <?php include './components/NavigationBar.php'; ?>
    <!-- Navigation Bar End -->

    <!-- Header Start -->
    <section class="container">
        <h1 class="py-4 fw-bold fs-1">Booking Confirm</h1>
        <div class="card shadow-sm p-4">
            <div class="row">
                <div class="col-md-6">
                    <img src="./assets/images/properties/<?php echo $images[0]; ?>" class="img-fluid" alt="Property Image">
                </div>
                <div class="col-md-6">
                    <h3 class="fw-bold"><?php echo $propertyData['title']; ?></h3>
                    <p class="text-muted"><?php echo $propertyData['address']; ?></p>
                    <p class="text-muted"><?php echo $propertyData['description']; ?></p>
                    <p class="text-muted fw-bold fs-3">Base Price: <?php echo $propertyData['base_price']; ?> LKR</p>
                </div>
            </div>
        </div>
        <div class="card shadow-sm p-4">
            <form action="booking-process.php" method="POST">
                <div class="d-flex gap-3 w-100">
                    <div class="mb-3 col">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                    </div>
                    <div class="mb-3 col">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" required>
                    </div>
                </div>
                <div class="d-flex gap-3 w-100">
                    <div class="mb-3 col">
                        <label for="nic" class="form-label">NIC</label>
                        <input type="text" class="form-control" id="nic" name="nic" required>
                    </div>
                    <div class="mb-3 col">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="text" class="form-control" id="contact" name="contact" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="guests" class="form-label">Guests Count</label>
                    <input type="number" class="form-control" id="guests" name="guests" min='0' max='<?php echo $propertyData['guests'] ?>' required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="note" class="form-label">Note</label>
                    <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </section>
    <!-- Header End -->

    <?php include 'components/script.php'; ?>

</body>

</html>