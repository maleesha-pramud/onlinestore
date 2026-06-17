<?php
session_start();
include './includes/connection.php';

// Featured Properties (Limit 6)
$propertiesStmt = Database::search("
    SELECT p.*, AVG(r.rating) AS avg_rating, COUNT(r.id) AS review_count 
    FROM properties p 
    LEFT JOIN reviews r ON p.id = r.properties_id 
    GROUP BY p.id 
    ORDER BY RAND() 
    LIMIT 6
");
// Newest Properties (Limit 4)
$newestPropertiesStmt = Database::search("
    SELECT p.*, AVG(r.rating) AS avg_rating, COUNT(r.id) AS review_count 
    FROM properties p 
    LEFT JOIN reviews r ON p.id = r.properties_id 
    GROUP BY p.id 
    ORDER BY p.id DESC 
    LIMIT 4
");
// All Categories
$categoriesStmt = Database::search("SELECT * FROM categories");
// All Amenities
$amenitiesStmt = Database::search("SELECT * FROM amenities");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include './components/head.php'; ?>
    <style>
        .hero-section-modern {
            position: relative;
            height: 85vh;
            min-height: 600px;
            overflow: hidden;
            border-radius: 0 0 3rem 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-slider {
            position: absolute !important;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .hero-slider, 
        .hero-slider .owl-stage-outer, 
        .hero-slider .owl-stage, 
        .hero-slider .owl-item {
            height: 100%;
        }

        .hero-slider .item {
            height: 100%;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .hero-slider .item::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.6) 100%);
        }

        .hero-overlay {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 900px;
            text-align: center;
            color: white;
            padding: 0 1.5rem;
        }

        .search-box-modern {
            background: white;
            padding: 0.75rem;
            border-radius: 100px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            margin-top: 3.5rem;
            gap: 0.25rem;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .search-item {
            flex: 1;
            padding: 0.5rem 1.5rem;
            text-align: left;
            border-right: 1px solid #f0f0f0;
        }

        .search-item:last-child {
            border-right: none;
        }

        .search-item label {
            display: block;
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--primary-accent);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }

        .search-item input {
            border: none;
            width: 100%;
            outline: none;
            font-size: 0.95rem;
            color: #333;
            font-weight: 500;
            background: transparent;
        }

        .category-item {
            text-align: center;
            padding: 1.25rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            border-radius: 1.5rem;
            border: 1px solid transparent;
            text-decoration: none !important;
            display: block;
        }

        .category-item:hover {
            background-color: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            transform: translateY(-8px);
            border-color: var(--border-color);
        }

        .category-icon-wrapper {
            width: 80px;
            height: 80px;
            background-color: #f8f9fa;
            border-radius: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 6px 15px rgba(0,0,0,0.03);
            font-size: 1.75rem;
            color: var(--primary-accent);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .category-item:hover .category-icon-wrapper {
            background-color: var(--primary-accent);
            color: white;
        }

        .category-icon-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--primary-text-color);
            display: block;
        }

        .amenity-pill {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 100px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .amenity-pill:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            border-color: var(--primary-accent);
        }

        .amenity-pill i {
            font-size: 1.25rem;
            color: var(--primary-accent);
        }

        .property-details-mini {
            display: flex;
            gap: 1rem;
            color: var(--secondary-text-color);
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .property-details-mini span {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .section-badge {
            display: inline-block;
            background-color: rgba(68, 93, 123, 0.1);
            color: var(--primary-accent);
            padding: 0.4rem 1rem;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 991.98px) {
            .hero-section-modern {
                height: auto;
                min-height: 600px;
                padding-top: 80px; /* Space for fixed navbar */
            }

            .hero-slider .item {
                height: 100%;
            }
            
            .hero-overlay {
                padding: 4rem 1.5rem;
            }

            .hero-overlay h1 {
                font-size: 2.5rem;
            }

            .hero-overlay p {
                font-size: 1.1rem;
            }

            .search-box-modern {
                flex-direction: column;
                border-radius: 2rem;
                padding: 1.5rem;
                margin-top: 2rem;
                gap: 1rem;
            }

            .search-item {
                border-right: none;
                border-bottom: 1px solid #f0f0f0;
                padding: 0.5rem 0;
                width: 100%;
            }

            .search-item:last-child {
                border-bottom: none;
            }

            .search-item button {
                width: 100% !important;
                border-radius: 1rem !important;
                margin-top: 0.5rem;
            }
        }

        @media (max-width: 767.98px) {
            .hero-overlay h1 {
                font-size: 2rem;
            }
            
            .hero-section-modern {
                border-radius: 0 0 1.5rem 1.5rem;
            }

            .section-title {
                font-size: 1.75rem;
            }
            
            .featured-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 1rem;
            }

            .cta-section-wrapper {
                padding: 3rem 1.5rem !important;
                border-radius: 2rem !important;
            }

            .cta-section-wrapper h2 {
                font-size: 2.25rem !important;
            }

            .cta-section-wrapper p {
                font-size: 1.1rem !important;
            }
            
            .cta-section-wrapper a {
                width: 100%;
                padding: 1rem !important;
            }

            .amenity-pill {
                padding: 0.75rem 1.25rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <?php include './components/NavigationBar.php'; ?>

    <main>
        <!-- Hero Section Start -->
        <section class="hero-section-modern">
            <div class="owl-carousel hero-slider">
                <div class="item" style="background-image: url('assets/images/slider/1.jpg');"></div>
                <div class="item" style="background-image: url('assets/images/slider/2.jpeg');"></div>
                <div class="item" style="background-image: url('assets/images/slider/3.jpg');"></div>
            </div>
            
            <div class="hero-overlay">
                <h1 class="display-2 fw-bold mb-3">Live anywhere with 2nd Home</h1>
                <p class="fs-4 opacity-90">Experience the world's most unique homes, apartments, and villas.</p>
                
                <form action="search.php" method="GET" class="search-box-modern">
                    <div class="search-item">
                        <label>Where</label>
                        <input type="text" name="q" placeholder="Search destinations" required>
                    </div>
                    <div class="search-item">
                        <label>Check In</label>
                        <input type="date" name="checkin">
                    </div>
                    <div class="search-item">
                        <label>Check Out</label>
                        <input type="date" name="checkout">
                    </div>
                    <div class="search-item search-btn-container" style="flex: 0.4;">
                        <button type="submit" class="btn btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center mx-auto" style="width: 55px; height: 55px; font-size: 1.25rem;">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </section>
        <!-- Hero Section End -->

        <!-- Categories Section Start -->
        <section class="container py-5 mt-md-5">
            <div class="text-center mb-5">
                <span class="section-badge">Explore</span>
                <h2 class="section-title mb-1">Discover by category</h2>
                <p class="text-secondary">Find the perfect stay matching your lifestyle</p>
            </div>
            
            <div class="owl-carousel category-carousel">
                <?php while ($cat = $categoriesStmt->fetch_assoc()) { ?>
                    <a href="search.php?id=<?php echo $cat['id']; ?>" class="category-item">
                        <div class="category-icon-wrapper">
                            <img src="assets/images/categories/<?php echo $cat['image']; ?>" alt="<?php echo $cat['name']; ?>">
                        </div>
                        <span class="category-name text-truncate d-block"><?php echo $cat['name']; ?></span>
                    </a>
                <?php } ?>
            </div>
        </section>
        <!-- Categories Section End -->

        <!-- Featured Properties Section Start -->
        <section class="container py-5">
            <div class="d-flex justify-content-between align-items-end mb-4 featured-header">
                <div>
                    <span class="section-badge">Featured</span>
                    <h2 class="section-title mb-1">Top-rated stays</h2>
                    <p class="text-secondary">Highly recommended properties by our community</p>
                </div>
                <a href="search.php" class="btn btn-outline-primary rounded-pill px-4 mb-2">Explore All <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            
            <div class="row g-4">
                <?php while ($property = $propertiesStmt->fetch_assoc()) { ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="property-card-modern card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 1.5rem;">
                            <a href="./single-property.php?id=<?php echo $property['id'] ?>" class="text-decoration-none">
                                <div class="position-relative overflow-hidden" style="height: 280px;">
                                    <?php
                                    $images = explode(',', $property['images']);
                                    $firstImage = !empty($images[0]) ? $images[0] : 'default.jpg';
                                    ?>
                                    <img src="assets/images/properties/<?php echo $firstImage; ?>" class="card-img-top h-100 w-100 object-fit-cover transition-all" alt="<?php echo $property['title']; ?>">
                                    <div class="position-absolute top-0 start-0 p-3">
                                        <span class="badge bg-white text-dark shadow-sm rounded-pill px-3 py-2 fw-bold small">
                                            <i class="bi bi-star-fill text-warning me-1"></i> 
                                            <?php echo ($property['review_count'] > 0) ? number_format($property['avg_rating'], 1) : "New"; ?>
                                        </span>
                                    </div>
                                    <div class="position-absolute top-0 end-0 p-3">
                                        <button class="btn btn-white btn-sm rounded-circle shadow-sm" style="width: 40px; height: 40px; padding: 0;">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="mb-2">
                                        <span class="text-primary fw-bold small text-uppercase letter-spacing-1">
                                            <?php
                                            $categoryId = $property['categories_id'];
                                            $categoryStmt = Database::search('SELECT name FROM categories WHERE id = ' . $categoryId);
                                            $category = $categoryStmt->fetch_assoc();
                                            echo $category['name'];
                                            ?>
                                        </span>
                                        <h5 class="card-title text-dark fw-bold mt-1 mb-2"><?php echo $property['title']; ?></h5>
                                    </div>
                                    
                                    <p class="card-text text-secondary mb-3 small d-flex align-items-center">
                                        <i class="bi bi-geo-alt me-1 text-primary"></i> <?php echo $property['address']; ?>
                                    </p>

                                    <div class="property-details-mini mb-3">
                                        <span><i class="bi bi-people"></i> <?php echo $property['guests']; ?></span>
                                        <span><i class="bi bi-door-open"></i> <?php echo $property['bedrooms']; ?></span>
                                        <span><i class="bi bi-bed"></i> <?php echo $property['beds']; ?></span>
                                        <span><i class="bi bi-water"></i> <?php echo $property['bathrooms']; ?></span>
                                    </div>
                                    
                                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                        <p class="card-price mb-0 text-dark">
                                            <span class="fw-bold fs-4">LKR <?php echo number_format($property['base_price']); ?></span> <span class="text-secondary small">/ night</span>
                                        </p>
                                        <div class="text-primary small fw-bold"><i class="bi bi-lightning-fill"></i> Instant</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
        <!-- Featured Properties Section End -->

        <!-- Amenities Section Start -->
        <section class="bg-white py-5 mt-5">
            <div class="container py-4 text-center">
                <span class="section-badge">Comfort</span>
                <h2 class="section-title mb-5">Experience luxury with these amenities</h2>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <?php while ($amenity = $amenitiesStmt->fetch_assoc()) { ?>
                        <div class="amenity-pill">
                            <i class="<?php echo $amenity['icon_cls']; ?>"></i>
                            <span class="fw-semibold text-dark"><?php echo $amenity['name']; ?></span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <!-- Amenities Section End -->

        <!-- Newest Properties Section Start -->
        <section class="container py-5 mt-5">
            <div class="text-center mb-5">
                <span class="section-badge">Fresh</span>
                <h2 class="section-title mb-1">Just Added</h2>
                <p class="text-secondary">The latest unique homes on our platform</p>
            </div>
            <div class="row g-4">
                <?php while ($newProperty = $newestPropertiesStmt->fetch_assoc()) { ?>
                    <div class="col-12 col-md-6 col-lg-3">
                         <div class="property-card-modern card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 1rem;">
                            <a href="./single-property.php?id=<?php echo $newProperty['id'] ?>" class="text-decoration-none">
                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                    <?php
                                    $newImages = explode(',', $newProperty['images']);
                                    $newFirstImage = !empty($newImages[0]) ? $newImages[0] : 'default.jpg';
                                    ?>
                                    <img src="assets/images/properties/<?php echo $newFirstImage; ?>" class="card-img-top h-100 w-100 object-fit-cover transition-all" alt="<?php echo $newProperty['title']; ?>">
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-primary text-white rounded-pill px-2 py-1 small fw-bold">NEW</span>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="card-title text-dark fw-bold mb-1 text-truncate"><?php echo $newProperty['title']; ?></h6>
                                    <p class="text-secondary small mb-2 text-truncate"><?php echo $newProperty['address']; ?></p>
                                    <p class="mb-0 text-dark fw-bold">LKR <?php echo number_format($newProperty['base_price']); ?> <span class="text-secondary small fw-normal">/ night</span></p>
                                </div>
                            </a>
                         </div>
                    </div>
                <?php } ?>
            </div>
        </section>
        <!-- Newest Properties Section End -->

        <!-- Why Choose Us Section Start -->
        <section class="bg-light py-5 mt-5 border-top border-bottom">
            <div class="container py-4 text-center">
                <span class="section-badge">Safe & Secure</span>
                <h2 class="section-title mb-5">Why travelers trust 2nd Home</h2>
                <div class="row g-5">
                    <div class="col-md-4">
                        <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                            <i class="bi bi-shield-check feature-icon"></i>
                            <h4 class="fw-bold mb-3">Verified Listings</h4>
                            <p class="text-secondary">Every property on our platform goes through a rigorous verification process to ensure safety and quality.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                            <i class="bi bi-wallet2 feature-icon"></i>
                            <h4 class="fw-bold mb-3">Best Price Guarantee</h4>
                            <p class="text-secondary">We guarantee the best prices on the market. If you find a lower price elsewhere, we'll match it.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                            <i class="bi bi-headset feature-icon"></i>
                            <h4 class="fw-bold mb-3">24/7 Support</h4>
                            <p class="text-secondary">Our dedicated support team is available 24/7 to help you with any issues or questions you may have.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Why Choose Us Section End -->

        <!-- Testimonials Section Start -->
        <section class="container py-5 mt-5">
             <div class="text-center mb-5">
                <span class="section-badge">Feedback</span>
                <h2 class="section-title mb-1">What Our Guests Say</h2>
            </div>
            <div class="owl-carousel testimonial-carousel">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 50px; height: 50px; font-weight: bold;">JD</div>
                        <div>
                            <h6 class="mb-0 fw-bold">John Doe</h6>
                            <span class="text-secondary small">Traveler from USA</span>
                        </div>
                    </div>
                    <p class="text-secondary mb-0 italic">"Amazing experience! The property was exactly as described, and the host was very welcoming. Highly recommended for anyone looking for a unique stay."</p>
                </div>
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 50px; height: 50px; font-weight: bold;">AS</div>
                        <div>
                            <h6 class="mb-0 fw-bold">Anna Smith</h6>
                            <span class="text-secondary small">Solo Traveler from UK</span>
                        </div>
                    </div>
                    <p class="text-secondary mb-0">"The booking process was so smooth. I found the perfect apartment for my weekend trip. 2nd Home is definitely my go-to for travel bookings now."</p>
                </div>
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 50px; height: 50px; font-weight: bold;">MR</div>
                        <div>
                            <h6 class="mb-0 fw-bold">Michael Reed</h6>
                            <span class="text-secondary small">Family Traveler from Canada</span>
                        </div>
                    </div>
                    <p class="text-secondary mb-0">"We stayed at a beautiful villa booked through 2nd Home. It was perfect for our family vacation. The support team was also very helpful."</p>
                </div>
            </div>
        </section>
        <!-- Testimonials Section End -->

        <!-- CTA Section Start -->
        <section class="container py-5 mt-5 mb-5">
            <div class="bg-primary rounded-5 p-5 text-center text-white position-relative overflow-hidden shadow-lg cta-section-wrapper">
                <div class="position-relative z-index-10 py-4">
                    <h2 class="display-4 fw-bold mb-3">Ready to host your space?</h2>
                    <p class="fs-4 mb-5 opacity-80">Join 50,000+ hosts and start earning today.</p>
                    <a href="signup.php" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold text-primary shadow">Start Hosting Today</a>
                </div>
                <i class="bi bi-house-door position-absolute opacity-10" style="font-size: 20rem; bottom: -80px; right: -80px;"></i>
                <i class="bi bi-geo-fill position-absolute opacity-10" style="font-size: 10rem; top: -40px; left: -40px;"></i>
            </div>
        </section>
        <!-- CTA Section End -->
    </main>

    <?php include './components/Footer.php'; ?>
    <?php include './components/script.php'; ?>
    
    <script>
        $(document).ready(function(){
            $(".hero-slider").owlCarousel({
                items: 1,
                loop: true,
                autoplay: true,
                autoplayTimeout: 6000,
                animateOut: 'fadeOut',
                dots: false,
                nav: false
            });

            $(".category-carousel").owlCarousel({
                margin: 20,
                loop: false,
                dots: false,
                nav: false,
                responsive: {
                    0: { items: 2 },
                    600: { items: 4 },
                    1000: { items: 6 },
                    1200: { items: 7 }
                }
            });

            $(".testimonial-carousel").owlCarousel({
                margin: 30,
                loop: true,
                dots: true,
                nav: false,
                responsive: {
                    0: { items: 1 },
                    768: { items: 2 },
                    1024: { items: 3 }
                }
            });
        });
    </script>
</body>

</html>