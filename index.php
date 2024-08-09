<?php
include './includes/connection.php';

$propertiesStmt = Database::search("SELECT * FROM properties");
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

    <!-- Hero Slider Start  -->
    <section class="mt-3">
        <div class="hero-slider container owl-carousel">
            <div class="hero-slider-slide" style="background-image: url('assets/images/slider/1.jpg');">
                <div class="position-relative h-100 w-100">
                    <div class="position-absolute bottom-0 z-2">
                        <span class="chip"><i class="fa-solid fa-person-shelter me-1"></i> House</span>
                        <span class="slider-title fs-1 d-block fw-bold">Grand Plaza</span>
                        <span class="d-block fs-5">Water Street, B3 1BJ</span>
                    </div>
                </div>
            </div>
            <div class="hero-slider-slide" style="background-image: url('assets/images/slider/2.jpeg');">
                <div class="position-relative h-100 w-100">
                    <div class="position-absolute bottom-0 z-2">
                        <span class="chip"><i class="fa-solid fa-person-booth me-1"></i> Bordim</span>
                        <span class="slider-title fs-1 d-block fw-bold">Ebony Suite</span>
                        <span class="d-block fs-5">Adamson Road, NW3 3HR</span>
                    </div>
                </div>
            </div>
            <div class="hero-slider-slide" style="background-image: url('assets/images/slider/3.jpg');">
                <div class="position-relative h-100 w-100">
                    <div class="position-absolute bottom-0 z-2">
                        <span class="chip"><i class="fa-solid fa-building me-1"></i> Apartment</span>
                        <span class="slider-title fs-1 d-block fw-bold">Waterside Apartment</span>
                        <span class="d-block fs-5">58 Water Street, B3 1BJ</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Slider End  -->

    <!-- Properties Start -->
    <section class="container mt-5">
        <div class="row g-4">
            <?php while ($property = $propertiesStmt->fetch_assoc()) { ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="./single-property.php?id=<?php echo $property['id'] ?>">
                        <div class="property-card rounded-4 position-relative">
                            <div class="owl-carousel property-carousel">
                                <?php
                                $images = explode(',', $property['images']);
                                foreach ($images as $image) {
                                ?>
                                    <div class="property-slider-slide" style="background-image: url('assets/images/properties/<?php echo $image ?>');"></div>
                                <?php } ?>
                            </div>
                            <div class="position-absolute top-0 left-0 h-100 w-100 p-3">
                                <div class="position-relative h-100 w-100">
                                    <div class="position-absolute bottom-0 z-2">
                                        <span class="chip-sm d-flex align-items-center gap-1">
                                            <?php
                                            $categoryId = $property['categories_id'];
                                            $categoryStmt = Database::search('SELECT * FROM categories WHERE id = ' . $categoryId);
                                            $category = $categoryStmt->fetch_assoc();
                                            ?>
                                            <img src="assets/images/categories/<?php echo $category['image'] ?>" height='16' width='16' />
                                            <?php echo $category['name']; ?>
                                        </span>
                                        <span class="slider-title fs-5 d-block fw-bold"><?php echo $property['title'] ?></span>
                                        <span class="d-block fs-6">58 Water Street, B3 1BJ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </section>
    <!-- Properties End -->


    <section class="mt-5 bg-accent">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex align-items-center justify-content-center text-white text-center">
                    <div class="pt-4 pt-lg-0">
                        <p class="fw-thin fs-2 m-0">FIND YOUR</p>
                        <p class="fw-bold fs-1 m-0">DREAM STAY</p>

                        <div class="row mt-5">
                            <!-- Phone Start -->
                            <div class="col-12 col-md-6 d-flex align-items-center gap-2 mb-3 mb-md-0">
                                <div class="circle-icon-container">
                                    <i class="fa-solid fa-phone text-accent"></i>
                                </div>
                                <div>
                                    <p class="m-0 fw-bold">+94-70-154-9092</p>
                                </div>
                            </div>
                            <!-- Phone End -->

                            <!-- Email Start -->
                            <div class="col-12 col-md-6 d-flex align-items-center gap-2">
                                <div class="circle-icon-container">
                                    <i class="fa-solid fa-envelope-open-text text-accent"></i>
                                </div>
                                <div>
                                    <p class="m-0 fw-bold">maleeshapramud9@gmail.com</p>
                                </div>
                            </div>
                            <!-- Email End -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 p-5">
                    <img src="./assets/images/banner/banner1.png" alt="" class="img-fluid banner1" />
                </div>
            </div>
        </div>
    </section>

    <?php include './components/script.php'; ?>

    <script>
        $(document).ready(function() {
            $(".hero-slider").owlCarousel({
                items: 1,
                loop: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 10000,
                autoplayHoverPause: true
            });
        });

        $(".property-carousel").owlCarousel({
            items: 1,
            loop: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true
        });
    </script>
</body>

</html>