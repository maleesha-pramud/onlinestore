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
                <h1>Add New Property</h1>
            </div>

            <div class="card form-card">
                <form id="add-listing-form" onsubmit="addListing(<?php echo $userData['id']; ?>); return false;">
                    <div class="row">
                        <div class="col-lg-8">
                             <div class="form-group">
                                <label for="title" class="form-label">Property Title</label>
                                <input type="text" class="form-control" id="title" name="title" maxlength="100" required>
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="10"></textarea>
                            </div>
                             <div class="form-group">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" maxlength="255" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                           <div class="form-group">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="">Select a category</option>
                                    <?php
                                    $categoriesStmt = Database::search("SELECT * FROM categories");
                                    while ($category = $categoriesStmt->fetch_assoc()) {
                                        echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                             <div class="form-group">
                                <label for="basePrice" class="form-label">Price per night ($)</label>
                                <input type="number" class="form-control" id="basePrice" min="1" step="0.01" max="10000000" name="basePrice" required>
                            </div>
                             <div class="form-group">
                                <label for="images" class="form-label">Images</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple required>
                                <div class="form-text">You can upload multiple images.</div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="guests" class="form-label">Guests</label>
                            <input type="number" class="form-control" id="guests" min="1" max="100" step="1" name="guests" required>
                        </div>
                         <div class="col-md-3 form-group">
                            <label for="bedrooms" class="form-label">Bedrooms</label>
                            <input type="number" class="form-control" id="bedrooms" min="1" max="100" step="1" name="bedrooms" required>
                        </div>
                         <div class="col-md-3 form-group">
                            <label for="beds" class="form-label">Beds</label>
                            <input type="number" class="form-control" id="beds" min="1" max="100" step="1" name="beds" required>
                        </div>
                         <div class="col-md-3 form-group">
                            <label for="bathrooms" class="form-label">Bathrooms</label>
                            <input type="number" class="form-control" id="bathrooms" min="1" max="100" step="1" name="bathrooms" required>
                        </div>
                    </div>

                    <hr class="my-4">

                     <div class="form-group">
                        <label class="form-label">Amenities</label>
                        <div class="row">
                             <?php
                            $amenitiesStmt = Database::search("SELECT * FROM `amenities`");
                            while ($amenity = $amenitiesStmt->fetch_assoc()) {
                            ?>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="<?php echo $amenity['id'] ?>" id="amenity-<?php echo $amenity['id'] ?>" name="amenities[]">
                                        <label class="form-check-label" for="amenity-<?php echo $amenity['id'] ?>" style="cursor: pointer;">
                                            <?php echo $amenity['name'] ?>
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">Save Property</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <?php include '../components/script.php'; ?>
    <script>
        // TinyMCE init if needed, or remove if not
        tinymce.init({
            selector: '#description',
            plugins: 'advlist autolink lists link charmap preview anchor',
            toolbar_mode: 'floating',
            height: 300
        });
    </script>
</body>
</html>