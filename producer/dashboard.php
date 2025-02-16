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
  if (!isset($userData['user_type_id']) || $userData['user_type_id'] != 2) {
    header('Location: /onlinestore/signin.php');
  }

  $propertyCount = Database::search("SELECT * FROM properties");
  $userCount = Database::search("SELECT * FROM users");
  ?>

  <section class="d-flex flex-column flex-md-row content-wrapper">

    <?php include '../components/ProducerSidebar.php'; ?>

    <div class="col container mt-4 flex-grow-1">
      <div class="row">
        <div class="col-12 mb-3">
          <div class="card border-0">
            <div class="d-flex flex-column flex-md-row gap-4 text-white">
              <div class="card-body bg-success">
                <h5 class="card-title">Active Properties</h5>
                <p class="card-text"><?php echo $propertyCount->num_rows; ?></p>
              </div>

              <div class="card-body bg-danger">
                <h5 class="card-title">Inactive Properties</h5>
                <p class="card-text"><?php echo $propertyCount->num_rows; ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 mb-3">
          <div class="card border-0">
            <div class="d-flex flex-column flex-md-row gap-4 text-white">
              <div class="card-body bg-secondary">
                <h5 class="card-title">Guests Count</h5>
                <p class="card-text"><?php echo $userCount->num_rows; ?></p>
              </div>

              <div class="card-body bg-primary">
                <h5 class="card-title">Suppliers Count</h5>
                <p class="card-text"><?php echo $userCount->num_rows; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

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