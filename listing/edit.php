<?php
session_start();
include '../includes/connection.php';

// Auth check
if (!isset($_SESSION['email'])) {
    header('Location: /signin.php');
    exit();
} else {
    $email = $_SESSION['email'];
    $userStmt = Database::search("SELECT * FROM `users` WHERE `email` = '$email'");
    $userData = $userStmt->fetch_assoc();
}

$userType = $userData['user_type_id'];

// Property data
$propertyId = $_GET['id'];
$propertyQuery = "SELECT * FROM properties WHERE id = $propertyId";
$property = Database::search($propertyQuery)->fetch_assoc();

$amenitiesWithProperties = Database::search("
    SELECT a.id FROM amenities a
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
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
</head>
<body>
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar">
             <?php 
            if ($userType == 1) {
                include '../components/AdminSidebar.php';
            } else {
                include '../components/ProducerSidebar.php';
            }
            ?>
        </aside>
        <main class="dashboard-main">
            <div class="page-header">
                <h1>Edit Property</h1>
            </div>

            <div class="card form-card">
                <form id="edit-listing-form" onsubmit="editListing(<?php echo $property['id']; ?>); return false;">
                    <div class="row">
                        <div class="col-lg-8">
                             <div class="form-group">
                                <label for="title" class="form-label">Property Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo $property['title']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="10"><?php echo htmlspecialchars($property['description']); ?></textarea>
                            </div>
                             <div class="form-group">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo $property['address']; ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                           <div class="form-group">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-control" id="category" name="category" required>
                                    <?php
                                    $categoriesStmt = Database::search("SELECT * FROM categories");
                                    while ($category = $categoriesStmt->fetch_assoc()) {
                                        $selected = ($category['id'] == $property['categories_id']) ? 'selected' : '';
                                        echo "<option value='{$category['id']}' {$selected}>{$category['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                             <div class="form-group">
                                <label for="basePrice" class="form-label">Price per night ($)</label>
                                <input type="number" class="form-control" id="basePrice" name="basePrice" value="<?php echo $property['base_price']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Current Images</label>
                                <div id="current-images-container" class="d-flex flex-wrap gap-2 mb-3">
                                     <?php foreach ($images as $index => $image) { 
                                         if(empty($image)) continue;
                                     ?>
                                        <div class="position-relative current-image-wrapper" data-image-name="<?php echo $image; ?>">
                                            <img src="/assets/images/properties/<?php echo $image; ?>" alt="Property Image" style="height: 100px; width: 100px; object-fit: cover; border-radius: var(--border-radius);">
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle" onclick="removeExistingImage(this)" style="padding: 2px 6px;">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    <?php } ?>
                                </div>
                                <input type="hidden" id="kept-images" name="kept_images" value="<?php echo $property['images']; ?>">
                                
                                <label for="images" class="form-label">Upload New Images</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple>
                                <div class="form-text">Choose additional images to add to this property.</div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="guests" class="form-label">Guests</label>
                            <input type="number" class="form-control" id="guests" name="guests" value="<?php echo $property['guests']; ?>" required>
                        </div>
                         <div class="col-md-3 form-group">
                            <label for="bedrooms" class="form-label">Bedrooms</label>
                            <input type="number" class="form-control" id="bedrooms" name="bedrooms" value="<?php echo $property['bedrooms']; ?>" required>
                        </div>
                         <div class="col-md-3 form-group">
                            <label for="beds" class="form-label">Beds</label>
                            <input type="number" class="form-control" id="beds" name="beds" value="<?php echo $property['beds']; ?>" required>
                        </div>
                         <div class="col-md-3 form-group">
                            <label for="bathrooms" class="form-label">Bathrooms</label>
                            <input type="number" class="form-control" id="bathrooms" name="bathrooms" value="<?php echo $property['bathrooms']; ?>" required>
                        </div>
                    </div>

                    <hr class="my-4">

                     <div class="form-group">
                        <label class="form-label">Amenities</label>
                        <div class="row">
                             <?php
                            $amenitiesStmt = Database::search("SELECT * FROM `amenities`");
                            while ($amenity = $amenitiesStmt->fetch_assoc()) {
                                $checked = in_array($amenity['id'], $amenitiesWithProperty) ? 'checked' : '';
                            ?>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="<?php echo $amenity['id'] ?>" id="amenity-<?php echo $amenity['id'] ?>" name="amenities[]" <?php echo $checked; ?>>
                                        <label class="form-check-label" for="amenity-<?php echo $amenity['id'] ?>" style="cursor: pointer;">
                                            <?php echo $amenity['name'] ?>
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">Update Property</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <?php include '../components/script.php'; ?>
    <script>
        function removeExistingImage(btn) {
            const wrapper = btn.closest('.current-image-wrapper');
            const imageName = wrapper.getAttribute('data-image-name');
            const keptImagesInput = document.getElementById('kept-images');
            
            let images = keptImagesInput.value.split(',');
            images = images.filter(img => img !== imageName && img !== "");
            
            keptImagesInput.value = images.join(',');
            wrapper.remove();
        }

        tinymce.init({
            selector: '#description',
            plugins: 'advlist autolink lists link charmap preview anchor',
            toolbar_mode: 'floating',
            height: 300
        });
    </script>
</body>
</html>