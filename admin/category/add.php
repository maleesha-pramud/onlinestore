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
      <div class="mb-3">
        <div id="file-input-div" onclick="inputImage();">
          Select an Image
        </div>
        <img src='' id='filePreview' class='img-fluid mt-2 d-none' onclick="inputImage();" />
        <input type="file" class="form-control d-none" id="imageInput" />
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

    function addCategory() {
      var name = document.getElementById('name').value;
      var image = document.getElementById('imageInput').files[0];

      var form = new FormData();
      form.append('name', name);
      form.append('image', image);

      var req = new XMLHttpRequest();
      req.onreadystatechange = function() {
        if (req.readyState == 4 && req.status == 200) {
          var response = req.responseText;
          alert(response);
          // if (response == 'success') {
          //     window.location.href = '/onlinestore/';
          // }
        }
      }

      req.open('POST', '/onlinestore/lib/add-category-process.php', true);
      req.send(form);
    }
  </script>
</body>

</html>