<?php
session_start();
include './includes/connection.php';

if (isset($_GET['id'])) {
    $propertyId = $_GET['id'];
} else {
    header('location: /');
    exit();
}

$propertyStmt = Database::search("SELECT * FROM `properties` WHERE `id` = '$propertyId'");
$propertyData = $propertyStmt->fetch_assoc();
$images = explode(',', $propertyData['images']);

$amenitiesStmt = Database::search("
    SELECT a.* FROM amenities a
    JOIN properties_has_amenities pa ON a.id = pa.amenities_id
    WHERE pa.properties_id = '$propertyId'
");

// Fetch reviews
$reviewsStmt = Database::search("
    SELECT r.*, u.fname, u.lname 
    FROM reviews r 
    JOIN users u ON r.users_id = u.id 
    WHERE r.properties_id = '$propertyId' 
    ORDER BY r.created_at DESC
");

$totalReviews = $reviewsStmt->num_rows;
$averageRating = 0;
$reviewsArray = [];
if ($totalReviews > 0) {
    $sumRating = 0;
    while ($row = $reviewsStmt->fetch_assoc()) {
        $sumRating += $row['rating'];
        $reviewsArray[] = $row;
    }
    $averageRating = number_format($sumRating / $totalReviews, 1);
}

$hasBooked = false;
$hasReviewed = false;
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $bookingCheck = Database::search("SELECT `id` FROM `bookings` WHERE `email` = '$email' AND `properties_id` = '$propertyId'");
    if ($bookingCheck->num_rows > 0) {
        $hasBooked = true;
    }

    $userStmt = Database::search("SELECT `id` FROM `users` WHERE `email` = '$email'");
    if ($userStmt->num_rows > 0) {
        $userId = $userStmt->fetch_assoc()['id'];
        $reviewCheck = Database::search("SELECT `id` FROM `reviews` WHERE `users_id` = '$userId' AND `properties_id` = '$propertyId'");
        if ($reviewCheck->num_rows > 0) {
            $hasReviewed = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include './components/head.php'; ?>
    <link rel="stylesheet" href="./assets/css/single-property.css" />
    <link rel="stylesheet" href="./assets/css/dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/photoswipe.min.css" />
</head>
<body>
    <?php include './components/NavigationBar.php'; ?>

    <main class="container py-5">
        <div class="property-header">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <div>
                    <h1><?php echo $propertyData['title']; ?></h1>
                    <div class="property-meta">
                        <span><i class="fa-solid fa-location-dot me-1"></i> <?php echo $propertyData['address']; ?></span>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <button class="btn btn-link text-dark text-decoration-underline fw-bold p-0"><i class="fa-solid fa-share-nodes me-2"></i>Share</button>
                    <button class="btn btn-link text-dark text-decoration-underline fw-bold p-0"><i class="fa-solid fa-heart me-2"></i>Save</button>
                </div>
            </div>
        </div>

        <div class="image-gallery-container position-relative">
            <div class="image-gallery" id="property-gallery">
                <?php 
                $displayCount = min(5, count($images));
                for ($i = 0; $i < $displayCount; $i++) { 
                    $imagePath = "./assets/images/properties/" . $images[$i];
                ?>
                    <a href="<?php echo $imagePath; ?>" 
                       class="gallery-item" 
                       data-pswp-width="1200" 
                       data-pswp-height="800" 
                       target="_blank">
                        <img src="<?php echo $imagePath; ?>" alt="Property Image <?php echo $i + 1; ?>">
                    </a>
                <?php } ?>
                
                <?php if (count($images) > 5) { 
                    // Hidden links for the remaining images so they appear in the lightbox
                    for ($i = 5; $i < count($images); $i++) {
                        $imagePath = "./assets/images/properties/" . $images[$i];
                ?>
                    <a href="<?php echo $imagePath; ?>" 
                       class="d-none" 
                       data-pswp-width="1200" 
                       data-pswp-height="800" 
                       target="_blank">
                    </a>
                <?php } } ?>
            </div>
            <button class="btn btn-light btn-sm position-absolute bottom-0 end-0 m-3 fw-bold border-dark shadow-sm" id="btn-show-all">
                <i class="fa-solid fa-grip-vertical me-2"></i>Show all photos
            </button>
        </div>

        <div class="row mt-5">
            <div class="col-lg-7">
                <div class="property-details">
                    <div class="pb-4 border-bottom">
                         <h2 class="section-title">Hosted by John Doe</h2>
                         <p class="text-secondary">Superhost &middot; 2 years hosting</p>
                    </div>
                   
                    <div class="py-4 border-bottom">
                         <h2 class="section-title">About this place</h2>
                         <div class="text-secondary rich-text-content">
                            <?php echo $propertyData['description']; ?>
                         </div>
                    </div>

                    <div class="py-4 border-bottom">
                        <h2 class="section-title">What this place offers</h2>
                        <div class="amenities-grid">
                            <?php while ($amenity = $amenitiesStmt->fetch_assoc()) { ?>
                                <div class="amenity-item">
                                    <i class="<?php echo $amenity['icon_cls']; ?> fs-4"></i>
                                    <span><?php echo $amenity['name']; ?></span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="py-4 border-top">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <h2 class="section-title mb-0"><i class="fa-solid fa-star me-2"></i><?php echo ($totalReviews > 0) ? $averageRating . " &middot; " . $totalReviews . " reviews" : "No reviews yet"; ?></h2>
                        </div>

                        <div class="row g-4">
                            <?php if ($totalReviews > 0) {
                                foreach ($reviewsArray as $review) { ?>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-3 mb-2">
                                            <div class="avatar bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                <i class="fa-solid fa-user text-secondary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold"><?php echo $review['fname'] . " " . $review['lname']; ?></h6>
                                                <p class="text-secondary small mb-0"><?php echo date("F Y", strtotime($review['created_at'])); ?></p>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                <i class="fa-solid fa-star <?php echo ($i <= $review['rating']) ? 'text-warning' : 'text-light'; ?> small"></i>
                                            <?php } ?>
                                        </div>
                                        <p class="text-secondary"><?php echo $review['comment']; ?></p>
                                    </div>
                                <?php }
                            } ?>
                        </div>

                        <?php if ($hasBooked && !$hasReviewed) { ?>
                            <div class="mt-5 p-4 bg-light rounded-4">
                                <h4 class="fw-bold mb-3">Leave a Review</h4>
                                <div class="mb-3">
                                    <label class="form-label d-block">Rating</label>
                                    <div class="rating-input d-flex gap-2">
                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                            <input type="radio" name="rating" value="<?php echo $i; ?>" id="rate-<?php echo $i; ?>" class="btn-check">
                                            <label class="btn btn-outline-warning btn-sm" for="rate-<?php echo $i; ?>"><?php echo $i; ?> <i class="fa-solid fa-star"></i></label>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="reviewComment" class="form-label">Your Comment</label>
                                    <textarea class="form-control" id="reviewComment" rows="3" placeholder="Share your experience..."></textarea>
                                </div>
                                <button class="btn btn-primary px-4" onclick="addReview(<?php echo $propertyId; ?>)">Submit Review</button>
                            </div>
                        <?php } else if ($hasBooked && $hasReviewed) { ?>
                            <div class="mt-5 p-3 border border-success-subtle bg-success-subtle text-success rounded-3">
                                <i class="fa-solid fa-circle-check me-2"></i>You have already reviewed this property. Thank you!
                            </div>
                        <?php } else if (isset($_SESSION['email'])) { ?>
                            <div class="mt-5 p-3 border bg-light text-secondary rounded-3">
                                <i class="fa-solid fa-circle-info me-2"></i>Only guests who have booked this property can leave a review.
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card booking-card">
                    <div class="card-body">
                        <h5 class="card-title fs-4 mb-4">Add dates for prices</h5>
                        <form onsubmit="checkAvailability(<?php echo $propertyId; ?>); return false;">
                            <div class="form-group">
                                <label for="checkIn" class="form-label">Check-in</label>
                                <input type="date" id="checkIn" name="checkIn" class="form-control" required>
                            </div>
                             <div class="form-group mt-3">
                                <label for="checkOut" class="form-label">Check-out</label>
                                <input type="date" id="checkOut" name="checkOut" class="form-control" required>
                            </div>
                             <div class="form-group mt-3">
                                <label for="guests" class="form-label">Number of guests</label>
                                <input type="number" id="guests" name="guests" class="form-control" min="1" max="<?php echo $propertyData['guests']; ?>" required>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Check Availability</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'components/script.php'; ?>
    <script type="module">
        import PhotoSwipeLightbox from 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/photoswipe-lightbox.esm.min.js';
        const lightbox = new PhotoSwipeLightbox({
            gallery: '#property-gallery',
            children: 'a',
            pswpModule: () => import('https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/photoswipe.esm.min.js')
        });

        // Add Share Button
        lightbox.on('uiRegister', function() {
            lightbox.pswp.ui.registerElement({
                name: 'share-button',
                ariaLabel: 'Share',
                order: 9,
                isButton: true,
                html: '<i class="fa-solid fa-share-nodes"></i>',
                onClick: (event, el, pswp) => {
                    const url = pswp.currSlide.data.src;
                    if (navigator.share) {
                        navigator.share({
                            title: '<?php echo $propertyData['title']; ?>',
                            url: window.location.href
                        }).catch(console.error);
                    } else {
                        navigator.clipboard.writeText(window.location.href);
                        showToast('Link copied to clipboard!', 'success');
                    }
                }
            });
        });

        lightbox.init();

        document.getElementById('btn-show-all').addEventListener('click', () => {
            lightbox.loadAndOpen(0); // Open the first image
        });
    </script>
</body>
</html>