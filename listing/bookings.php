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

  $bookingsStmt = null;
  if ($userId == 1) {
    $bookingsStmt = Database::search("SELECT * FROM bookings");
  } else {
    $bookingsStmt = Database::search("SELECT b.*, p.users_id FROM bookings b, properties p WHERE b.properties_id = p.id AND p.users_id = $userId");
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
              <th scope="col">User Name</th>
              <th scope="col">Check-In</th>
              <th scope="col">Check-Out</th>
              <th scope="col">Guests</th>
              <th scope="col">Contact</th>
              <th scope="col">Total Price</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($booking = $bookingsStmt->fetch_assoc()) { ?>
              <tr>
                <th scope="row">1</th>
                <td><?php echo ($booking['first_name'] . ' ' . $booking['last_name']) ?></td>
                <td><?php echo $booking['checkIn'] ?></td>
                <td><?php echo $booking['checkOut'] ?></td>
                <td><?php echo $booking['guests'] ?></td>
                <td><?php echo $booking['contact'] ?></td>
                <td><?php echo number_format($booking['total_price'], 2) ?></td>
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