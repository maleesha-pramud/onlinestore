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
            <div>
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
                <button onclick="checkoutPayment()" type="submit" class="btn btn-md btn-primary">Procceed to Pay</button>
            </div>
        </div>
    </section>
    <!-- Header End -->

    <?php include 'components/script.php'; ?>
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
    <script>
        function checkoutPayment() {
            var fname = document.getElementById('firstName');
            var lname = document.getElementById('lastName');
            var nic = document.getElementById('nic');
            var contact = document.getElementById('contact');
            var guests = document.getElementById('guests');
            var email = document.getElementById('email');
            var note = document.getElementById('note');

            var formData = new FormData();
            formData.append('firstName', fname.value);
            formData.append('lastName', lname.value);
            formData.append('nic', nic.value);
            formData.append('contact', contact.value);
            formData.append('guests', guests.value);
            formData.append('email', email.value);
            formData.append('note', note.value);


            var req = new XMLHttpRequest();
            req.onreadystatechange = function() {
                if (req.readyState == 4 && req.status == 200) {
                    var response = JSON.parse(req.responseText);
                    if (response.success) {
                        var payment = {
                            "sandbox": true,
                            "merchant_id": "1227844", // Replace your Merchant ID
                            "return_url": undefined, // Important
                            "cancel_url": undefined, // Important
                            "notify_url": "http://sample.com/notify",
                            "order_id": response.order_id,
                            "items": response.items,
                            "amount": response.amount,
                            "currency": "LKR",
                            "hash": response.hash,
                            "first_name": response.first_name,
                            "last_name": response.last_name,
                            "email": response.email,
                            "phone": response.phone,
                            "address": response.address,
                            "city": response.city,
                            "country": response.country,
                            "delivery_address": response.delivery_address,
                            "delivery_city": response.delivery_city,
                            "delivery_country": response.delivery_country,
                            "custom_1": "",
                            "custom_2": ""
                        };

                        payhere.startPayment(payment);

                        payhere.onCompleted = function onCompleted(orderId) {
                            console.log("Payment completed. OrderID:" + orderId);
                            // Note: validate the payment and show success or failure page to the customer
                        };

                        payhere.onDismissed = function onDismissed() {
                            // Note: Prompt user to pay again or show an error page
                            console.log("Payment dismissed");
                        };

                        payhere.onError = function onError(error) {
                            // Note: show an error page
                            console.log("Error:" + error);
                        };
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            };

            req.open('POST', '/onlinestore/lib/checkout-process.php', true);
            req.send(formData);
        }
    </script>

</body>

</html>