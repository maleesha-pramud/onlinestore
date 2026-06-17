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
          <h2 class="mb-1">Category Management</h2>
          <p class="text-muted">Manage your property categories</p>
        </div>
        <div class="d-flex gap-3 align-items-center">
          <a href="/admin/category/add.php" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>Add New Category
          </a>
        </div>
      </header>

      <?php
      $categoriesStmt = Database::search("SELECT * FROM categories");
      ?>

      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle datatable">
              <thead class="table-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Category Name</th>
                  <th scope="col">Category ID</th>
                  <th scope="col" class="text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $count = 1;
                while ($category = $categoriesStmt->fetch_assoc()) { ?>
                  <tr>
                    <th scope="row"><?php echo $count++; ?></th>
                    <td><?php echo $category['name'] ?></td>
                    <td><span class="badge bg-light text-dark">#<?php echo $category['id'] ?></span></td>
                    <td class="text-end">
                      <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/category/edit.php?id=<?php echo $category['id'] ?>" class="btn btn-sm btn-outline-info">
                          <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <button onclick="deleteCategory(<?php echo $category['id'] ?>)" class="btn btn-sm btn-outline-danger">
                          <i class="fa-solid fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
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
  </script>
</body>

</html>