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

$amenitiesStmt = Database::search("
    SELECT a.*
    FROM amenities a
    JOIN properties_has_amenities pa ON a.id = pa.amenities_id
    WHERE pa.properties_id = '$propertyId'
");

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
        <h1 class="py-4 fw-bold fs-1"><?php echo $propertyData['title'] ?></h1>
        <div class="row single-listing-image-wrapper">
            <div class="col">
                <img src="./assets/images/properties/<?php echo $images[0] ?>" alt="" class="img-cover rounded-5">
            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        <img src="./assets/images/properties/<?php echo $images[1] ?>" alt="" class="img-cover rounded-5">
                    </div>
                    <div class="col">
                        <img src="./assets/images/properties/<?php echo $images[2] ?>" alt="" class="img-cover rounded-5">
                    </div>
                </div>
            </div>
        </div>
        <h5 class="mt-4"><?php echo $propertyData['address'] ?></h5>
        <h6 class="text-secondary">
            <?php echo $propertyData['guests'] ?> guests |
            <?php echo $propertyData['bedrooms'] ?> bedrooms |
            <?php echo $propertyData['beds'] ?> beds |
            <?php echo $propertyData['bathrooms'] ?> bath
        </h6>
    </section>
    <!-- Header End -->

    <!-- Property Details Start -->
    <section class="container mb-5">
        <div class="row">

            <!-- Details Start -->
            <div class="col-8">

                <!-- Amenities Start -->
                <div class="row">
                    <div class="col-10">
                        <h3 class="text-4xl fw-bold mt-4 mb-4">Amenities</h3>
                        <div class="row">

                            <?php while ($amenity = $amenitiesStmt->fetch_assoc()) { ?>
                                <div class="col-3">
                                    <i class="<?php echo $amenity['icon_cls'] ?>"></i>
                                    <p><?php echo $amenity['name'] ?></p>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                <!-- Amenities End -->

                <div class="">
                    <h3 class="text-4xl fw-bold mt-4 mb-3">Description</h3>
                    <div class="text-muted fs-5">
                        <?php echo $propertyData['description'] ?>
                    </div>
                </div>
            </div>
            <!-- Details End -->

            <!-- Booking Start -->
            <div class="col-4 position-relative">
                <div class="card border-0 booking-card">
                    <div class="card-body bg-light-gray rounded-5 d-flex flex-column align-items-center justify-content-center shadow py-4">
                        <h5 class="card-title">Check Availability</h5>
                        <div class="d-flex gap-3">
                            <div class="my-3">
                                <label class="form-label d-block">Check-in Date</label>
                                <input type="date" id="checkIn" class="px-3 py-2 border-1 border-secondary rounded-2" />
                            </div>
                            <div class="my-3">
                                <label class="form-label d-block">Check-out Date</label>
                                <input type="date" id="checkOut" class="px-3 py-2 border-1 border-secondary rounded-2" />
                            </div>
                        </div>
                        <button onclick="checkAvailability(<?php echo $propertyData['id'] ?>)" class="btn btn-info text-white shadow-sm">Book Now</button>
                    </div>
                </div>
            </div>
            <!-- Booking End -->
        </div>
    </section>
    <!-- Property Details End -->


    <?php include 'components/script.php'; ?>
    <script>

    </script>

</body>

</html>