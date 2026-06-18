<?php
session_start();
include './includes/connection.php';

// This page relies on localStorage, but we need a container to render the properties
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
        <div class="mb-5">
            <h1 class="fw-bold">My Favorites</h1>
            <p class="text-secondary">Properties you've saved to your favorites list.</p>
        </div>

        <div class="row g-4" id="favorites-container">
            <div class="col-12 text-center py-5" id="empty-favorites" style="display: none;">
                <div class="mb-4">
                    <i class="bi bi-heart display-1 text-muted"></i>
                </div>
                <h3 class="fw-bold">Your favorites list is empty</h3>
                <p class="text-secondary">Explore our properties and click the heart icon to save them here!</p>
                <a href="/" class="btn btn-primary rounded-pill px-5 mt-3 py-2">Explore Properties</a>
            </div>
            <!-- Favorites will be loaded here via JS -->
        </div>
    </main>

    <?php include './components/Footer.php'; ?>
    <?php include './components/script.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            const container = document.getElementById('favorites-container');
            const emptyState = document.getElementById('empty-favorites');

            if (favorites.length === 0) {
                emptyState.style.display = 'block';
                return;
            }

            // Fetch property details for the favorited IDs
            const formData = new FormData();
            formData.append('ids', JSON.stringify(favorites));

            // We'll create a simple process file to fetch these properties
            PostRequest('/lib/get-favorites-process.php', formData, function(response, error) {
                if (error) {
                    showToast(error, 'error');
                    return;
                }

                if (response.status && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(property => {
                        const firstImage = property.images.split(',')[0] || 'default.jpg';
                        const rating = property.review_count > 0 ? parseFloat(property.avg_rating).toFixed(1) : "New";
                        
                        html += `
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="property-card-modern card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 1.5rem;">
                                    <a href="./single-property.php?id=${property.id}" class="text-decoration-none">
                                        <div class="position-relative overflow-hidden" style="height: 250px;">
                                            <img src="assets/images/properties/${firstImage}" class="card-img-top h-100 w-100 object-fit-cover transition-all" alt="${property.title}">
                                            <div class="position-absolute top-0 start-0 p-3">
                                                <span class="badge bg-white text-dark shadow-sm rounded-pill px-3 py-2 fw-bold small">
                                                    <i class="bi bi-star-fill text-warning me-1"></i> ${rating}
                                                </span>
                                            </div>
                                            <div class="position-absolute top-0 end-0 p-3">
                                                <button class="btn btn-white btn-sm rounded-circle shadow-sm" style="width: 40px; height: 40px; padding: 0;" data-favorite-id="${property.id}" onclick="event.preventDefault(); toggleFavorite(${property.id}, this); setTimeout(() => location.reload(), 500);">
                                                    <i class="bi bi-heart-fill text-danger"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-4 d-flex flex-column">
                                            <h5 class="card-title text-dark fw-bold mb-2">${property.title}</h5>
                                            <p class="card-text text-secondary mb-3 small d-flex align-items-center">
                                                <i class="bi bi-geo-alt me-1 text-primary"></i> ${property.address}
                                            </p>
                                            <div class="mt-auto pt-3 border-top">
                                                <p class="card-price mb-0 text-dark">
                                                    <span class="fw-bold fs-5">LKR ${new Intl.NumberFormat().format(property.base_price)}</span> <span class="text-secondary small">/ night</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    emptyState.style.display = 'block';
                }
            });
        });
    </script>
</body>
</html>
