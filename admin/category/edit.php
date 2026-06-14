<?php
session_start();
include '../../includes/connection.php';

// Auth check
if (!isset($_SESSION['email'])) {
  header('Location: /signin.php');
  exit();
} else {
  $email = $_SESSION['email'];
  $userStmt = Database::search("SELECT * FROM `users` WHERE `email` = '$email'");
  $userData = $userStmt->fetch_assoc();
  if ($userData['user_type_id'] != 1) {
    header('Location: /index.php');
    exit();
  }
}

$RootPath = '/';

$categoryId = $_GET['id'];
$categoryQuery = "SELECT * FROM categories WHERE id = $categoryId";
$category = Database::search($categoryQuery)->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../../components/head.php'; ?>
  <link rel="stylesheet" href="/assets/css/dashboard.css" />
</head>

<body>

  <div class="dashboard-layout">
    <aside class="dashboard-sidebar">
      <?php include '../../components/AdminSidebar.php'; ?>
    </aside>

    <main class="dashboard-main">
      <header class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h2 class="mb-1">Edit Category</h2>
          <p class="text-muted">Modify category details and image</p>
        </div>
        <div class="d-flex gap-3 align-items-center">
          <a href="/admin/category/list.php" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to List
          </a>
        </div>
      </header>

      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <div class="mb-4">
            <label for="name" class="form-label fw-semibold">Category Name</label>
            <input type="text" class="form-control" id="name" value="<?php echo $category['name']; ?>" placeholder="Category name">
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Category Image</label>
            <?php if (!empty($category['image'])): ?>
              <div class="position-relative">
                <img src="../../assets/images/categories/<?php echo $category['image']; ?>" id="filePreview" class="img-fluid mt-2 rounded" onclick="inputImage();" style="max-height: 300px; width: 100%; object-fit: cover; cursor: pointer;" />
                <div class="position-absolute bottom-0 end-0 p-3">
                  <button class="btn btn-sm btn-dark opacity-75" onclick="inputImage();">Change Image</button>
                </div>
              </div>
            <?php else: ?>
              <div id="file-input-div" class="border rounded p-5 text-center bg-light cursor-pointer" onclick="inputImage();" style="border-style: dashed !important;">
                <i class="fa-solid fa-cloud-arrow-up fs-1 text-muted mb-2"></i>
                <p class="mb-0 text-muted">Click to select an image</p>
              </div>
              <img src='' id='filePreview' class='img-fluid mt-2 d-none rounded' onclick="inputImage();" style="max-height: 300px; width: 100%; object-fit: cover;" />
            <?php endif; ?>
            <input type="file" class="form-control d-none" id="imageInput" accept="image/*" />
          </div>

          <input type="hidden" class="d-none" id="id" value="<?php echo $category['id']; ?>">

          <div class="text-end">
            <button onclick="editCategory();" class="btn btn-primary px-5">
              <i class="fa-solid fa-save me-2"></i>Update Category
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>

  <?php include '../../components/script.php'; ?>
  <script src="../../assets/libraries/RichTextEditor/jquery.richtext.min.js"></script>
  <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>


  <script>
    $(document).ready(function() {
      $(".hero-slider").owlCarousel({
        items: 1,
        loop: true,
        dots: false
      });


      $('.description').richText();
    });

    $(".property-carousel").owlCarousel({
      items: 1,
      loop: true,
      dots: true
    });

    document.getElementById('imageInput').addEventListener('change', function() {
      var file = this.files[0];
      var reader = new FileReader();
      reader.onload = function() {
        document.getElementById('filePreview').src = reader.result;
        document.getElementById('filePreview').classList.remove('d-none');
        document.getElementById('file-input-div').classList.add('d-none');
      }
      reader.readAsDataURL(file);
    })
  </script>
</body>

</html>