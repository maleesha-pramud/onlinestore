<?php

$RootPath = '/onlinestore';

include '../includes/connection.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../components/head.php'; ?>
  <link rel="stylesheet" href="../assets/libraries/RichTextEditor/richtext.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
</head>

<body>

  <!-- Navigation Bar Start -->
  <?php include '../components/NavigationBar.php'; ?>
  <!-- Navigation Bar End -->

  <?php
  if (!isset($userData['email'])) {
    header('Location: /onlinestore/signin.php');
  }

  $propertiesStmt = Database::search("SELECT * FROM properties");

  ?>

  <section class="d-flex content-wrapper">

    <?php include '../components/AdminSidebar.php'; ?>

    <!-- Hero section Start  -->
    <section class="mt-3 px-5 col-10">

      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Property title</th>
            <th scope="col">Guests</th>
            <th scope="col">Bedrooms</th>
            <th scope="col">Beds</th>
            <th scope="col">Bathrooms</th>
            <th scope="col">Base Price (LKR)</th>
            <th scope="col" class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($property = $propertiesStmt->fetch_assoc()) { ?>
            <tr>
              <th scope="row">1</th>
              <td><?php echo $property['title'] ?></td>
              <td><?php echo $property['guests'] ?></td>
              <td><?php echo $property['bedrooms'] ?></td>
              <td><?php echo $property['beds'] ?></td>
              <td><?php echo $property['bathrooms'] ?></td>
              <td><?php echo number_format($property['base_price'], 2) ?></td>
              <td class="text-end">
                <a href="/onlinestore/listing/edit.php?id=<?php echo $property['id'] ?>" class="btn btn-info text-white">Edit</a>
                <a href="/onlinestore/listing/delete.php?id=<?php echo $property['id'] ?>" class="btn btn-danger text-white">Delete</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

    </section>
    <!-- Hero section End  -->
  </section>

  <?php include '../components/script.php'; ?>
  <script src="../assets/libraries/RichTextEditor/jquery.richtext.min.js"></script>
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