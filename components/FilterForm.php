<form action="search.php" method="GET">
    <?php if (!empty($searchTerm)): ?>
        <input type="hidden" name="q" value="<?php echo htmlspecialchars($searchTerm); ?>">
    <?php endif; ?>
    <?php if ($categoryId): ?>
        <input type="hidden" name="id" value="<?php echo $categoryId; ?>">
    <?php endif; ?>

    <div class="card filter-card border-0 shadow-sm">
        <div class="filter-group">
            <h3 class="filter-title">Filter by Price</h3>
            <div class="mb-3">
                <label for="priceRange" class="form-label text-secondary small">Max Price per night</label>
                <input type="range" name="max_price" class="form-range price-range-input" min="0" max="<?php echo $absoluteMaxPrice; ?>" step="10" value="<?php echo $maxPrice; ?>">
                <div class="d-flex justify-content-between text-secondary small mt-2">
                    <span>LKR 0</span>
                    <span class="fw-bold text-primary price-range-value">LKR <?php echo $maxPrice; ?></span>
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
                    <a href="/search.php?id=<?php echo $cat['id']; ?><?php echo !empty($searchTerm) ? '&q='.urlencode($searchTerm) : ''; ?>" class="text-decoration-none <?php echo $activeClass; ?> small">
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
                $checked = in_array($amenity['id'], $selectedAmenities) ? 'checked' : '';
                $uniqueId = ($filterPrefix ?? 'default') . "-amenity-filter-" . $amenity['id'];
            ?>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="amenities[]" value="<?php echo $amenity['id'] ?>" id="<?php echo $uniqueId; ?>" <?php echo $checked; ?>>
                    <label class="form-check-label small text-secondary" for="<?php echo $uniqueId; ?>" style="cursor: pointer;">
                        <?php echo $amenity['name'] ?>
                    </label>
                </div>
            <?php } ?>
        </div>
        <button type="submit" class="btn btn-primary w-100 shadow-sm">Apply Filters</button>
        <button type="button" class="btn btn-link w-100 mt-2 text-secondary small" onclick="window.location.href='/search.php'">Clear All</button>
    </div>
</form>