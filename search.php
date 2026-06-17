<?php
session_start();
include './includes/connection.php';

$categoryId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$searchTerm = isset($_GET['q']) ? $_GET['q'] : "";

// Fetch the absolute maximum price from properties for the filter range
$maxPriceQuery = Database::search("SELECT MAX(base_price) AS max_p FROM properties");
$maxPriceData = $maxPriceQuery->fetch_assoc();
$absoluteMaxPrice = $maxPriceData['max_p'] ? (float)$maxPriceData['max_p'] : 1000;

$maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : $absoluteMaxPrice;
$selectedAmenities = isset($_GET['amenities']) ? $_GET['amenities'] : [];

$conditions = [];

if (!empty($searchTerm)) {
    $escapedSearch = Database::escape($searchTerm);
    $conditions[] = "(title LIKE '%$escapedSearch%' OR address LIKE '%$escapedSearch%')";
}

if ($categoryId) {
    $conditions[] = "categories_id = $categoryId";
}

if (isset($_GET['max_price'])) {
    $conditions[] = "base_price <= $maxPrice";
}

if (!empty($selectedAmenities)) {
    $amenityIds = array_map('intval', $selectedAmenities);
    $amenityList = implode(',', $amenityIds);
    $amenityCount = count($amenityIds);
    $conditions[] = "id IN (SELECT properties_id FROM properties_has_amenities WHERE amenities_id IN ($amenityList) GROUP BY properties_id HAVING COUNT(DISTINCT amenities_id) = $amenityCount)";
}

$query = "
    SELECT p.*, AVG(r.rating) AS avg_rating, COUNT(r.id) AS review_count 
    FROM properties p 
    LEFT JOIN reviews r ON p.id = r.properties_id";
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}
$query .= " GROUP BY p.id";

$propertiesStmt = Database::search($query);
$propertiesExist = $propertiesStmt && $propertiesStmt->num_rows > 0;

$categoryName = "All Properties";
if ($categoryId) {
    $catStmt = Database::search("SELECT name FROM categories WHERE id = $categoryId");
    if ($catData = $catStmt->fetch_assoc()) {
        $categoryName = $catData['name'];
    }
} elseif (!empty($searchTerm)) {
    $categoryName = "Search results for '" . htmlspecialchars($searchTerm) . "'";
}
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
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Search</li>
                    </ol>
                </nav>
                <h2 class="section-title mb-1"><?php echo $categoryName; ?></h2>
                <p class="text-secondary mb-0"><?php echo $propertiesStmt ? $propertiesStmt->num_rows : 0; ?> properties found</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0 d-lg-none">
                <button class="btn btn-outline-primary rounded-pill px-4" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilters" aria-controls="offcanvasFilters">
                    <i class="fa-solid fa-sliders me-2"></i> Filters
                </button>
            </div>
        </div>

        <div class="search-layout">
            <aside class="filter-sidebar d-none d-lg-block">
                <?php 
                $filterPrefix = "desktop";
                include './components/FilterForm.php'; 
                ?>
            </aside>

            <div class="search-results">
                <div class="row g-4">
                    <?php if ($propertiesExist) {
                        while ($property = $propertiesStmt->fetch_assoc()) { ?>
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="property-card-modern card h-100 border-0 shadow-sm">
                                    <a href="./single-property.php?id=<?php echo $property['id'] ?>">
                                        <?php
                                        $images = explode(',', $property['images']);
                                        $firstImage = !empty($images[0]) ? $images[0] : 'default.jpg';
                                        ?>
                                        <div class="position-relative">
                                            <img src="assets/images/properties/<?php echo $firstImage; ?>" class="card-img-top rounded-top" alt="<?php echo $property['title']; ?>" style="height: 200px; object-fit: cover;">
                                            <div class="position-absolute top-0 end-0 p-2">
                                                <button class="btn btn-white btn-sm rounded-circle shadow-sm" data-favorite-id="<?php echo $property['id']; ?>" onclick="event.preventDefault(); toggleFavorite(<?php echo $property['id']; ?>, this)">
                                                    <i class="fa-regular fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title mb-0"><?php echo $property['title']; ?></h5>
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-star text-warning me-1 small"></i>
                                                    <span class="small fw-bold"><?php echo ($property['review_count'] > 0) ? number_format($property['avg_rating'], 1) : "New"; ?></span>
                                                </div>
                                            </div>
                                            <p class="card-text text-secondary small mb-3">
                                                <i class="fa-solid fa-location-dot me-1"></i> <?php echo $property['address']; ?>
                                            </p>
                                            
                                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                                <p class="card-price mb-0">
                                                    <span class="fw-bold fs-5 text-primary">LKR <?php echo number_format($property['base_price']); ?></span> <span class="text-secondary small">/ night</span>
                                                </p>
                                                <span class="badge bg-primary-soft text-primary small">Instant Book</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="col-12">
                            <div class="card border-0 shadow-sm py-5 text-center">
                                <div class="card-body">
                                    <i class="fa-solid fa-house-circle-exclamation fs-1 text-muted mb-3"></i>
                                    <h4 class="fw-bold">No properties found</h4>
                                    <p class="text-secondary">Try adjusting your filters or search terms to find what you're looking for.</p>
                                    <a href="/search.php" class="btn btn-primary mt-3">View All Stays</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Mobile Filters Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasFilters" aria-labelledby="offcanvasFiltersLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold" id="offcanvasFiltersLabel">Filters</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="p-3">
                <?php 
                $filterPrefix = "mobile";
                include './components/FilterForm.php'; 
                ?>
            </div>
        </div>
    </div>

    <?php include './components/script.php'; ?>
    <script>
        document.querySelectorAll('.price-range-input').forEach(input => {
            input.addEventListener('input', function() {
                const valueSpan = this.parentElement.querySelector('.price-range-value');
                if (valueSpan) {
                    valueSpan.textContent = 'LKR ' + this.value;
                }
            });
        });
    </script>
</body>
</html>