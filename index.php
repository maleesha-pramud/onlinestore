<?php
session_start();
include './includes/connection.php';

$propertiesStmt = Database::search("SELECT * FROM properties");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include './components/head.php'; ?>
</head>

<body>
    <?php include './components/NavigationBar.php'; ?>

    <main>
        <!-- Hero Section Start -->
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">Find your next stay</h1>
                    <p class="hero-subtitle">Search deals on hotels, homes, and much more, at the best prices.</p>
                </div>
            </div>
        </section>
        <!-- Hero Section End -->

        <!-- Properties Section Start -->
        <section class="container py-5">
            <h2 class="section-title">Featured Properties</h2>
            <div class="row g-4">
                <?php while ($property = $propertiesStmt->fetch_assoc()) { ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="property-card-modern card h-100">
                            <a href="./single-property.php?id=<?php echo $property['id'] ?>">
                                <?php
                                $images = explode(',', $property['images']);
                                $firstImage = !empty($images) ? $images[0] : 'default.jpg';
                                ?>
                                <img src="assets/images/properties/<?php echo $firstImage; ?>" class="card-img-top" alt="<?php echo $property['title']; ?>">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo $property['title']; ?></h5>
                                    <p class="card-text text-secondary mb-3"><?php echo $property['address']; ?></p>
                                    
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <p class="card-price mb-0">
                                            <span class="fw-bold fs-5">$<?php echo number_format($property['base_price']); ?></span> / night
                                        </p>
                                        <span class="badge bg-primary-soft text-primary">
                                            <?php
                                            $categoryId = $property['categories_id'];
                                            $categoryStmt = Database::search('SELECT * FROM categories WHERE id = ' . $categoryId);
                                            $category = $categoryStmt->fetch_assoc();
                                            echo $category['name'];
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
        <!-- Properties Section End -->

        <!-- CTA Section Start -->
        <section class="cta-section bg-light py-5 mt-4">
            <div class="container text-center">
                <h2 class="section-title">Become a Host</h2>
                <p class="fs-5 text-secondary my-3">Join our community of hosts and start earning from your extra space.</p>
                <a href="#" class="btn btn-primary">Learn More</a>
            </div>
        </section>
        <!-- CTA Section End -->
    </main>

    <?php include './components/script.php'; ?>
</body>

</html>