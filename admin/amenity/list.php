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
          <h2 class="mb-1">Amenity Management</h2>
          <p class="text-muted">Manage property amenities and icons</p>
        </div>
        <div class="d-flex gap-3 align-items-center">
          <a href="/admin/amenity/add.php" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>Add New Amenity
          </a>
        </div>
      </header>

      <?php
      $amenitiesStmt = Database::search("SELECT * FROM amenities");
      ?>

      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle datatable">
              <thead class="table-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Amenity Name</th>
                  <th scope="col">Icon</th>
                  <th scope="col">Icon Class</th>
                  <th scope="col" class="text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $count = 1;
                while ($amenity = $amenitiesStmt->fetch_assoc()) { ?>
                  <tr>
                    <th scope="row"><?php echo $count++; ?></th>
                    <td><?php echo $amenity['name'] ?></td>
                    <td><i class="<?php echo $amenity['icon_cls'] ?> fs-4 text-primary"></i></td>
                    <td><code class="small text-muted"><?php echo $amenity['icon_cls'] ?></code></td>
                    <td class="text-end">
                      <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/amenity/edit.php?id=<?php echo $amenity['id'] ?>" class="btn btn-sm btn-outline-info">
                          <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <button onclick="deleteAmenity(<?php echo $amenity['id'] ?>)" class="btn btn-sm btn-outline-danger">
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
  <script>
    function deleteAmenity(id) {
      if (confirm('Are you sure you want to delete this amenity?')) {
        var form = new FormData();
        form.append('id', id);

        GetRequest('/lib/delete-amenity-process.php', {id: id}, function (response, error) {
          if (error) {
            showToast(error, 'error');
            return;
          }

          if (response.status) {
            location.reload();
          } else {
            showToast(response.message, 'error');
          }
        });
      }
    }
  </script>
</body>

</html>