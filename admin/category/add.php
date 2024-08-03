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
  ?>

  <section class="d-flex content-wrapper">

    <?php include '../../components/AdminSidebar.php'; ?>

    <!-- Hero section Start  -->
    <section class="mt-3 px-5 col-10">

      <div class="mb-3">
        <label for="name" class="form-label">Category Name</label>
        <input type="text" class="form-control" id="name">
      </div>

      <button onclick="addCategory();" class="btn btn-info text-white float-end px-4 mb-5">Submit</button>
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