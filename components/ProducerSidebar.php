<?php
$currentPath = $_SERVER['REQUEST_URI'];
$targetPathProperty = '/onlinestore/listing/add.php';
$targetPathCategory = '/onlinestore/admin/category/add.php';
$isPropertyPath = ($currentPath === $targetPathProperty);
$isCategoryPath = ($currentPath === $targetPathCategory);
?>

<div class="col-2 bg-midGray position-relative">
  <div class="admin-sidebar">
    <div class="accordion accordion-flush" id="accordionFlushExample">
      <!-- Dashboard -->
      <a href='<?php echo $RootPath ?>/admin/dashboard.php' class="btn bg-info text-white rounded-0 w-100 py-4" type="button">
        Dashboard
      </a>
      <!-- Properties Management -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
          <button class="accordion-button bg-accent2 text-white <?php echo $isPropertyPath ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="<?php echo $isPropertyPath ? 'true' : 'false'; ?>" aria-controls="flush-collapseOne">
            Properties Management
          </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse <?php echo $isPropertyPath ? 'show' : ''; ?>" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <a href="<?php echo $RootPath; ?>/listing/add.php" class="text-decoration-none d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-house-add" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h4a.5.5 0 1 0 0-1h-4a.5.5 0 0 1-.5-.5V7.207l5-5 6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z" />
                <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 1 0 1 0v-1h1a.5.5 0 1 0 0-1h-1v-1a.5.5 0 0 0-.5-.5" />
              </svg>
              Add Property
            </a>
          </div>
        </div>
      </div>
      <!-- Category Management -->
      <!-- <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingTwo">
          <button class="accordion-button bg-accent2 text-white <?php echo $isCategoryPath ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="<?php echo $isCategoryPath ? 'true' : 'false'; ?>" aria-controls="flush-collapseTwo">
            Category Management
          </button>
        </h2>
        <div id="flush-collapseTwo" class="accordion-collapse collapse <?php echo $isCategoryPath ? 'show' : ''; ?>" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <a href="<?php echo $RootPath; ?>/admin/category/add.php" class="text-decoration-none d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-tags" viewBox="0 0 16 16">
                <path d="M3 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 0 1.414l-5.586 5.586a1 1 0 0 1-1.414 0L2.293 8.707a1 1 0 0 1-.293-.707V3a1 1 0 0 1 1-1zM1 3a2 2 0 0 1 2-2h4.586a2 2 0 0 1 1.414.586l5.414 5.414a2 2 0 0 1 0 2.828l-5.586 5.586a2 2 0 0 1-2.828 0L.586 8.707A2 2 0 0 1 0 7.293V3z" />
                <path d="M5.5 5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                <path d="M7.293 1.5a1 1 0 0 1 1.414 0l5.414 5.414a1 1 0 0 1 0 1.414l-5.586 5.586a1 1 0 0 1-1.414 0L2.293 8.707a1 1 0 0 1-.293-.707V3a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293z" />
              </svg>
              Add Category
            </a>
          </div>
          <div class="accordion-body">
            <a href="<?php echo $RootPath; ?>/admin/category/list.php" class="text-decoration-none d-flex align-items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-tags" viewBox="0 0 16 16">
                <path d="M3 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 0 1.414l-5.586 5.586a1 1 0 0 1-1.414 0L2.293 8.707a1 1 0 0 1-.293-.707V3a1 1 0 0 1 1-1zM1 3a2 2 0 0 1 2-2h4.586a2 2 0 0 1 1.414.586l5.414 5.414a2 2 0 0 1 0 2.828l-5.586 5.586a2 2 0 0 1-2.828 0L.586 8.707A2 2 0 0 1 0 7.293V3z" />
                <path d="M5.5 5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                <path d="M7.293 1.5a1 1 0 0 1 1.414 0l5.414 5.414a1 1 0 0 1 0 1.414l-5.586 5.586a1 1 0 0 1-1.414 0L2.293 8.707a1 1 0 0 1-.293-.707V3a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293z" />
              </svg>
              Category List
            </a>
          </div>
        </div>
      </div> -->

    </div>
  </div>
</div>