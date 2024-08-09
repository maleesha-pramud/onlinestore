<?php

$RootPath = '/onlinestore';

include '../includes/connection.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../components/head.php'; ?>
</head>

<body>

  <!-- Navigation Bar Start -->
  <?php include '../components/NavigationBar.php'; ?>
  <!-- Navigation Bar End -->

  <?php
  if (!isset($userData['email'])) {
    header('Location: /onlinestore/signin.php');
  }

  $userId = $userData['id'];

  $propertiesStmt = null;
  if ($userId == 1) {
    $propertiesStmt = Database::search("SELECT * FROM properties");
  } else {
    $propertiesStmt = Database::search("SELECT * FROM properties WHERE `users_id` = $userId");
  }

  ?>

  <section class="d-flex content-wrapper">

    <?php
    if ($userId == 1) {
      include '../components/AdminSidebar.php';
    } else {
      include '../components/ProducerSidebar.php';
    }
    ?>

    <!-- Hero section Start  -->
    <section class="mt-3 px-3 px-lg-5 col-10">

      <div class="table-responsive">
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
                  <button onclick="deleteListing(<?php echo $property['id']; ?>)" class="btn btn-danger text-white">Delete</button>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

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