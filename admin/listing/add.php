<?php

$RootPath = '/onlinestore';

echo dirname(__FILE__);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../components/head.php'; ?>
    <link rel="stylesheet" href="../../assets/libraries/RichTextEditor/richtext.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
</head>

<body>
    

    <!-- Hero Slider Start  -->
    <section class="mt-3 container">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="description" name="description" id="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address">
        </div>
        <div class="mb-3 row">
            <div class="col row">
                <div class="col mb-3">
                    <label for="guests" class="form-label">Guests</label>
                    <input type="number" class="form-control" id="guests">
                </div>
                <div class="col mb-3">
                    <label for="bedrooms" class="form-label">Bed Rooms</label>
                    <input type="number" class="form-control" id="bedrooms">
                </div>
            </div>
            <div class="col mb-3 row">
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

    </section>
    <!-- Hero Slider End  -->

    <?php include '../../components/script.php'; ?>
    <script src="../../assets/libraries/RichTextEditor/jquery.richtext.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>


    <script>
        $(document).ready(function() {
            $(".hero-slider").owlCarousel({
                items: 1, // Set the number of items to show at once
                loop: true, // Enable infinite loop
                dots: false // Hide pagination dots
            });


            $('.description').richText();
        });

        $(".property-carousel").owlCarousel({
            items: 1, // Adjust this as needed for the second carousel
            loop: true, // Enable infinite loop
            dots: true // Show dots for the second carousel, adjust as needed
        });
    </script>
</body>

</html>