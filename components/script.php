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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="/assets/js/script.js"></script>
<script src="/assets/js/ui-script.js"></script> 

<script>
    $(document).ready(function() {
        if ($('.datatable').length > 0) {
            $('.datatable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
                "dom": '<"d-flex justify-content-between align-items-center mb-3"l f>rt<"d-flex justify-content-between align-items-center mt-3"i p>',
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search records...",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ records",
                    "paginate": {
                        "first": '<i class="fa-solid fa-angles-left"></i>',
                        "last": '<i class="fa-solid fa-angles-right"></i>',
                        "next": '<i class="fa-solid fa-angle-right"></i>',
                        "previous": '<i class="fa-solid fa-angle-left"></i>'
                    }
                }
            });
        }
    });
</script>

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