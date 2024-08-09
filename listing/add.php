<?php

$RootPath = '/onlinestore';

include '../includes/connection.php';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $userStmt = Database::search("SELECT * FROM `users` WHERE `email` = '$email'");
    $userData = $userStmt->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../components/head.php'; ?>
    <link rel="stylesheet" href="../assets/libraries/RichTextEditor/richtext.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
</head>

<body>

    <!-- Navigation Bar Start -->
    <?php include '../components/NavigationBar.php'; ?>
    <!-- Navigation Bar End -->

    <?php
    if (!isset($userData['email'])) {
        header('Location: /onlinestore/signin.php');
    }
    ?>

    <section class="d-flex content-wrapper">


        <?php
        if ($userData['user_type_id'] == 1) {
            include '../components/AdminSidebar.php';
        } else {
            include '../components/ProducerSidebar.php';
        }
        ?>

        <!-- Hero section Start  -->
        <section class="mt-3 px-3 px-lg-5 col-10">
            <div class="mb-3">
                <label for="images" class="form-label">Images</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple>
                <div class="text-warning mt-2">You can only upload up to 3 images.</div>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title">
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category">
                    <?php
                    $categoriesStmt = Database::search("SELECT * FROM `categories`");
                    while ($category = $categoriesStmt->fetch_assoc()) {
                    ?>
                        <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="description" name="description" id="description"></textarea>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address">
            </div>
            <div class="mb-3 d-flex gap-3 flex-wrap">
                <div class="col-12 col-md d-flex gap-3 flex-wrap">
                    <div class="col mb-3">
                        <label for="guests" class="form-label">Guests</label>
                        <input type="number" class="form-control" id="guests">
                    </div>
                    <div class="col mb-3">
                        <label for="bedrooms" class="form-label">Bed Rooms</label>
                        <input type="number" class="form-control" id="bedrooms">
                    </div>
                </div>
                <div class="col-12 col-md mb-3 d-flex gap-3 flex-wrap">
                    <div class="col mb-3">
                        <label for="beds" class="form-label">Beds</label>
                        <input type="number" class="form-control" id="beds">
                    </div>
                    <div class="col mb-3">
                        <label for="bathrooms" class="form-label">Bath Rooms</label>
                        <input type="number" class="form-control" id="bathrooms">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="amenities" class="form-label">Amenities</label>
                <div class="d-flex flex-wrap">
                    <?php
                    $amenitiesStmt = Database::search("SELECT * FROM `amenities`");
                    while ($amenity = $amenitiesStmt->fetch_assoc()) {
                    ?>
                        <div class="col-6 col-md-2">
                            <input type="checkbox" id="<?php echo $amenity['id'] ?>" name='amenities' class="form-check-input">
                            <label for="<?php echo $amenity['id'] ?>" class="form-check-label"><?php echo $amenity['name'] ?></label>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="basePrice" class="form-label">Base Price &#40;LKR&#41;</label>
                <input type="number" class="form-control" id="basePrice">
            </div>

            <button onclick="addListing();" class="btn btn-info text-white float-end px-4 mb-5">Submit</button>
        </section>
        <!-- Hero section End  -->
    </section>

    <?php include '../components/script.php'; ?>
    <script src="../assets/libraries/RichTextEditor/jquery.richtext.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>


    <script>
        $(document).ready(function() {
            $(".hero-slider").owlCarousel({
                items: 1,
                loop: true,
                dots: false
            });
        });

        tinymce.init({
            selector: '.description',
            plugins: 'advlist autolink lists link charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            height: 500
        });

        $(".property-carousel").owlCarousel({
            items: 1,
            loop: true,
            dots: true
        });
    </script>
</body>

</html>