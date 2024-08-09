<?php

$RootPath = '/onlinestore';

include '../includes/connection.php';

$propertyId = $_GET['id'];
$propertyQuery = "SELECT * FROM properties WHERE id = $propertyId";
$property = Database::search($propertyQuery)->fetch_assoc();

$amenitiesWithProperties = Database::search("
    SELECT a.*
    FROM amenities a
    JOIN properties_has_amenities pa ON a.id = pa.amenities_id
    WHERE pa.properties_id = '$propertyId'
");
$amenitiesWithProperty = [];
while ($amenity = $amenitiesWithProperties->fetch_assoc()) {
    $amenitiesWithProperty[] = $amenity['id'];
}

$images = explode(',', $property['images']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../components/head.php'; ?>
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

        <?php include '../components/AdminSidebar.php'; ?>

        <!-- Hero section Start  -->
        <section class="mt-3 px-3 px-lg-5 col-10">
            <div class="image-container">
                <?php foreach ($images as $image) { ?>
                    <img src="<?php echo $RootPath . '/assets/images/properties/' . $image; ?>" alt="Property Image" class="property-image">
                <?php } ?>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" value="<?php echo $property['title']; ?>">
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" value="<?php echo $property['categories_id']; ?>">
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
                <textarea class="description" name="description" id="description"><?php echo htmlspecialchars($property['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" value="<?php echo $property['address']; ?>">
            </div>
            <div class="mb-3 d-flex gap-3">
                <div class="col d-flex gap-3">
                    <div class="col mb-3">
                        <label for="guests" class="form-label">Guests</label>
                        <input type="number" class="form-control" id="guests" value="<?php echo $property['guests']; ?>">
                    </div>
                    <div class="col mb-3">
                        <label for="bedrooms" class="form-label">Bed Rooms</label>
                        <input type="number" class="form-control" id="bedrooms" value="<?php echo $property['bedrooms']; ?>">
                    </div>
                </div>
                <div class="col mb-3 d-flex gap-3">
                    <div class="col mb-3">
                        <label for="beds" class="form-label">Beds</label>
                        <input type="number" class="form-control" id="beds" value="<?php echo $property['beds']; ?>">
                    </div>
                    <div class="col mb-3">
                        <label for="bathrooms" class="form-label">Bath Rooms</label>
                        <input type="number" class="form-control" id="bathrooms" value="<?php echo $property['bathrooms']; ?>">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="amenities" class="form-label">Amenities</label>
                <div class="d-flex">
                    <?php
                    $amenitiesStmt = Database::search("SELECT * FROM `amenities`");
                    while ($amenity = $amenitiesStmt->fetch_assoc()) {
                        $checked = in_array($amenity['id'], $amenitiesWithProperty) ? 'checked' : '';
                    ?>
                        <div class="col-2">
                            <input type="checkbox" id="<?php echo $amenity['id'] ?>" name='amenities' class="form-check-input" <?php echo $checked; ?>>
                            <label for="<?php echo $amenity['id'] ?>" class="form-check-label"><?php echo $amenity['name'] ?></label>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="basePrice" class="form-label">Base Price &#40;LKR&#41;</label>
                <input type="number" class="form-control" id="basePrice" value="<?php echo $property['base_price']; ?>">
            </div>

            <button onclick="editListing(<?php echo $property['id']; ?>);" class="btn btn-info text-white float-end px-4 mb-5">Submit</button>
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