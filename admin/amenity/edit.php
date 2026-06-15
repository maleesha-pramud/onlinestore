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

if (!isset($_GET['id'])) {
    header('Location: /admin/amenity/list.php');
    exit();
}

$id = $_GET['id'];
$amenity = Database::search("SELECT * FROM amenities WHERE id = $id")->fetch_assoc();

// List of common Bootstrap Icons for selection
$commonIcons = [
    'bi-wifi', 'bi-water', 'bi-snow', 'bi-cup-hot', 'bi-p-circle', 'bi-tv', 'bi-fan', 'bi-fire', 
    'bi-wind', 'bi-sun', 'bi-moon', 'bi-tree', 'bi-flower1', 'bi-cloud-sun', 'bi-house', 'bi-door-closed',
    'bi-key', 'bi-lock', 'bi-shield-check', 'bi-camera-video', 'bi-telephone', 'bi-envelope', 
    'bi-car-front', 'bi-bicycle', 'bi-bus-front', 'bi-train-front', 'bi-airplane', 'bi-plug',
    'bi-battery-full', 'bi-laptop', 'bi-pc-display', 'bi-speaker', 'bi-headphones', 'bi-smartwatch',
    'bi-watch', 'bi-alarm', 'bi-calendar-event', 'bi-tag', 'bi-cart', 'bi-bag', 'bi-credit-card',
    'bi-cash-stack', 'bi-gift', 'bi-trophy', 'bi-mortarboard', 'bi-book', 'bi-journal-text',
    'bi-pencil', 'bi-brush', 'bi-scissors', 'bi-hammer', 'bi-wrench', 'bi-screwdriver',
    'bi-bucket', 'bi-briefcase', 'bi-person', 'bi-people', 'bi-heart', 'bi-star', 'bi-hand-thumbs-up',
    'bi-chat', 'bi-info-circle', 'bi-question-circle', 'bi-exclamation-circle', 'bi-check-circle'
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../../components/head.php'; ?>
  <link rel="stylesheet" href="/assets/css/dashboard.css" />
  <style>
    .icon-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
      gap: 10px;
      max-height: 300px;
      overflow-y: auto;
      padding: 10px;
      border: 1px solid var(--border-color);
      border-radius: var(--border-radius);
    }
    .icon-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 10px;
      cursor: pointer;
      border-radius: 8px;
      transition: all 0.2s ease;
    }
    .icon-item:hover {
      background-color: var(--background-color);
    }
    .icon-item.selected {
      background-color: var(--primary-accent);
      color: white;
    }
    .icon-item i {
      font-size: 1.5rem;
    }
  </style>
</head>

<body>

  <div class="dashboard-layout">
    <aside class="dashboard-sidebar">
      <?php include '../../components/AdminSidebar.php'; ?>
    </aside>

    <main class="dashboard-main">
      <header class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h2 class="mb-1">Edit Amenity</h2>
          <p class="text-muted">Modify existing amenity details</p>
        </div>
        <div class="d-flex gap-3 align-items-center">
          <a href="/admin/amenity/list.php" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to List
          </a>
        </div>
      </header>

      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <input type="hidden" id="amenityId" value="<?php echo $amenity['id']; ?>">
          <div class="mb-4">
            <label for="name" class="form-label fw-semibold">Amenity Name</label>
            <input type="text" class="form-control" id="name" value="<?php echo $amenity['name']; ?>" placeholder="e.g. Free Wi-Fi, Private Pool">
          </div>
          
          <div class="mb-4">
            <label class="form-label fw-semibold">Select Icon</label>
            <div class="mb-3">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="iconSearch" placeholder="Search icons...">
              </div>
            </div>
            
            <div class="icon-grid" id="iconGrid">
              <?php foreach ($commonIcons as $icon): 
                $fullIconClass = 'bi ' . $icon;
                $isSelected = ($fullIconClass == $amenity['icon_cls']) ? 'selected' : '';
              ?>
                <div class="icon-item <?php echo $isSelected; ?>" onclick="selectIcon('<?php echo $icon; ?>', this)" data-icon="<?php echo $icon; ?>">
                  <i class="bi <?php echo $icon; ?>"></i>
                </div>
              <?php endforeach; ?>
            </div>
            <input type="hidden" id="icon_cls" value="<?php echo $amenity['icon_cls']; ?>">
          </div>

          <div class="selected-icon-preview mb-4" id="previewContainer">
            <p class="fw-semibold mb-2">Selected Preview:</p>
            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
              <i id="previewIcon" class="<?php echo $amenity['icon_cls']; ?> fs-2 text-primary"></i>
              <span id="previewText" class="text-muted small"><?php echo $amenity['icon_cls']; ?></span>
            </div>
          </div>

          <div class="text-end">
            <button onclick="editAmenity();" class="btn btn-primary px-5">
              <i class="fa-solid fa-save me-2"></i>Update Amenity
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>

  <?php include '../../components/script.php'; ?>
  <script>
    function selectIcon(iconCls, element) {
      document.getElementById('icon_cls').value = 'bi ' + iconCls;
      
      // Update UI
      document.querySelectorAll('.icon-item').forEach(item => item.classList.remove('selected'));
      element.classList.add('selected');
      
      // Update Preview
      document.getElementById('previewContainer').classList.remove('d-none');
      document.getElementById('previewIcon').className = 'bi ' + iconCls + ' fs-2 text-primary';
      document.getElementById('previewText').textContent = 'bi ' + iconCls;
    }

    // Icon Search
    document.getElementById('iconSearch').addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      document.querySelectorAll('.icon-item').forEach(item => {
        const iconName = item.getAttribute('data-icon').toLowerCase();
        if (iconName.includes(searchTerm)) {
          item.classList.remove('d-none');
        } else {
          item.classList.add('d-none');
        }
      });
    });

    function editAmenity() {
      const id = document.getElementById('amenityId').value;
      const name = document.getElementById('name').value;
      const icon_cls = document.getElementById('icon_cls').value;

      if (!name) {
        alert('Please enter amenity name');
        return;
      }
      if (!icon_cls) {
        alert('Please select an icon');
        return;
      }

      const form = new FormData();
      form.append('id', id);
      form.append('name', name);
      form.append('icon_cls', icon_cls);

      PostRequest('/lib/edit-amenity-process.php', form, function(response, error) {
        if (error) {
          alert(error);
          return;
        }

        if (response.status) {
          window.location.href = '/admin/amenity/list.php';
        } else {
          alert(response.message);
        }
      });
    }
  </script>
</body>

</html>