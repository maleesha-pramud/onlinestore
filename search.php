<?php
session_start();
include './includes/connection.php';

$query = "";
$categoryId = null;
$searchTerm = "";

if (isset($_GET['q'])) {
    $searchTerm = $_GET['q'];
    $query = "SELECT * FROM properties WHERE title LIKE '%$searchTerm%' OR address LIKE '%$searchTerm%'";
} elseif (isset($_GET['id'])) {
    $categoryId = $_GET['id'];
    $query = "SELECT * FROM properties WHERE categories_id = $categoryId";
} else {
    $query = "SELECT * FROM properties";
}

$propertiesStmt = Database::search($query);
$propertiesExist = $propertiesStmt->num_rows > 0;

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
        <div class="row">
            <div class="col-12 mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Search</li>
                    </ol>
                </nav>
                <h2 class="section-title mb-1"><?php echo $categoryName; ?></h2>
                <p class="text-secondary"><?php echo $propertiesStmt->num_rows; ?> properties found</p>
            </div>
        </div>

        <div class="search-layout">
            <aside class="filter-sidebar d-none d-lg-block">
                <div class="card filter-card border-0 shadow-sm">
                    <div class="filter-group">
                        <h3 class="filter-title">Filter by Price</h3>
                        <div class="mb-3">
                            <label for="priceRange" class="form-label text-secondary small">Max Price per night</label>
                            <input type="range" class="form-range" min="0" max="1000" step="10" id="priceRange" value="1000">
                            <div class="d-flex justify-content-between text-secondary small mt-2">
                                <span>$0</span>
                                <span id="priceValue" class="fw-bold text-primary">$1000</span>
                            </div>
                        </div>
                    </div>

                    <div class="filter-group">
                        <h3 class="filter-title">Categories</h3>
                        <?php
                        $allCats = Database::search("SELECT * FROM categories");
                        while ($cat = $allCats->fetch_assoc()) {
                            $activeClass = ($categoryId == $cat['id']) ? 'fw-bold text-primary' : 'text-secondary';
                        ?>
                            <div class="mb-2">
                                <a href="/search.php?id=<?php echo $cat['id']; ?>" class="text-decoration-none <?php echo $activeClass; ?> small">
                                    <?php echo $cat['name']; ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="filter-group">
                        <h3 class="filter-title">Amenities</h3>
                        <?php
                        $amenitiesStmt = Database::search("SELECT * FROM `amenities` LIMIT 8");
                        while ($amenity = $amenitiesStmt->fetch_assoc()) {
                        ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="<?php echo $amenity['id'] ?>" id="amenity-filter-<?php echo $amenity['id'] ?>">
                                <label class="form-check-label small text-secondary" for="amenity-filter-<?php echo $amenity['id'] ?>">
                                    <?php echo $amenity['name'] ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                    <button class="btn btn-primary w-100 shadow-sm">Apply Filters</button>
                    <button class="btn btn-link w-100 mt-2 text-secondary small" onclick="window.location.href='/search.php'">Clear All</button>
                </div>
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
                                                <button class="btn btn-white btn-sm rounded-circle shadow-sm">
                                                    <i class="fa-regular fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title mb-0"><?php echo $property['title']; ?></h5>
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-star text-warning me-1 small"></i>
                                                    <span class="small fw-bold">4.8</span>
                                                </div>
                                            </div>
                                            <p class="card-text text-secondary small mb-3">
                                                <i class="fa-solid fa-location-dot me-1"></i> <?php echo $property['address']; ?>
                                            </p>
                                            
                                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                                <p class="card-price mb-0">
                                                    <span class="fw-bold fs-5 text-primary">$<?php echo number_format($property['base_price']); ?></span> <span class="text-secondary small">/ night</span>
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

    <?php include './components/script.php'; ?>
    <script>
        const priceRange = document.getElementById('priceRange');
        const priceValue = document.getElementById('priceValue');
        if(priceRange) {
            priceRange.addEventListener('input', function() {
                priceValue.textContent = '$' + this.value;
            });
        }
    </script>
</body>
</html>