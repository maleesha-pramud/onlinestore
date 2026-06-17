<?php
  if(!isset($RootPath)){
    $RootPath = "";
  }
?>

<script src="/assets/js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="/assets/js/owl.carousel.min.js"></script>
<script src="https://kit.fontawesome.com/58cb4002a7.js" crossorigin="anonymous"></script>
<script src="/assets/js/script.js"></script>
<script src="/assets/js/ui-script.js"></script> 

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-4">
  <div id="liveToast" class="toast modern-toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body">
      <div class="toast-icon-wrapper" id="toastIcon">
        <i class="fa-solid fa-circle-info"></i>
      </div>
      <div class="toast-content">
        <p class="toast-title" id="toastTitle">Notification</p>
        <p class="toast-message" id="toastMessage">Hello, world!</p>
      </div>
      <button type="button" class="toast-close-btn" data-bs-dismiss="toast">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
  </div>
</div>