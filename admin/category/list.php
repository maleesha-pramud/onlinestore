<?php

$RootPath = '/onlinestore';

include '../../includes/connection.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../../components/head.php'; ?>
  <link rel="stylesheet" href="../../assets/libraries/RichTextEditor/richtext.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
</head>

<body>

  <!-- Navigation Bar Start -->
  <?php include '../../components/NavigationBar.php'; ?>
  <!-- Navigation Bar End -->

  <?php
  if (!isset($userData['email'])) {
    header('Location: /onlinestore/signin.php');
  }

  $categoriesStmt = Database::search("SELECT * FROM categories");

  ?>

  <section class="d-flex content-wrapper">

    <?php include '../../components/AdminSidebar.php'; ?>

    <!-- Hero section Start  -->
    <section class="mt-3 px-5 col-10">

      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Category Name</th>
            <th scope="col">Category ID</th>
            <th scope="col" class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($category = $categoriesStmt->fetch_assoc()) { ?>
            <tr>
              <th scope="row">1</th>
              <td><?php echo $category['name'] ?></td>
              <td><?php echo $category['id'] ?></td>
              <td class="text-end">
                <a href="/onlinestore/admin/category/edit.php?id=<?php echo $category['id'] ?>" class="btn btn-info text-white">Edit</a>
                <div onclick="deleteCategory(<?php echo $category['id'] ?>)" class="btn btn-danger text-white">Delete</div>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

    </section>
    <!-- Hero section End  -->
  </section>

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