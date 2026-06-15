<?php
session_start();
include './includes/connection.php';

// Auth check
if (!isset($_SESSION['email'])) {
    header('Location: /signin.php');
    exit();
}

$email = $_SESSION['email'];
$userStmt = Database::search("SELECT * FROM `users` WHERE `email` = '$email'");
$userData = $userStmt->fetch_assoc();
$userType = $userData['user_type_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include './components/head.php'; ?>
    <link rel="stylesheet" href="./assets/css/dashboard.css" />
</head>
<body>
    <?php 
    // Show navigation bar for everyone
    include './components/NavigationBar.php'; 
    ?>

    <div class="<?php echo ($userType == 1 || $userType == 2) ? 'dashboard-layout' : 'container py-5'; ?>">
        
        <?php if ($userType == 1): ?>
            <aside class="dashboard-sidebar">
                <?php include './components/AdminSidebar.php'; ?>
            </aside>
        <?php elseif ($userType == 2): ?>
            <aside class="dashboard-sidebar">
                <?php include './components/ProducerSidebar.php'; ?>
            </aside>
        <?php endif; ?>

        <main class="<?php echo ($userType == 1 || $userType == 2) ? 'dashboard-main' : ''; ?>">
            <div class="page-header mb-4">
                <h1>Account Settings</h1>
                <p class="text-muted">Manage your personal information and security</p>
            </div>

            <div class="row g-4">
                <!-- Profile Settings -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h3 class="fs-5 fw-bold mb-4"><i class="fa-solid fa-user-gear me-2 text-primary"></i>Profile Information</h3>
                            
                            <div class="mb-3">
                                <label for="fname" class="form-label small fw-semibold">First Name</label>
                                <input type="text" class="form-control" id="fname" value="<?php echo $userData['fname']; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="lname" class="form-label small fw-semibold">Last Name</label>
                                <input type="text" class="form-control" id="lname" value="<?php echo $userData['lname']; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="mobile" class="form-label small fw-semibold">Mobile Number</label>
                                <input type="text" class="form-control" id="mobile" value="<?php echo $userData['mobile']; ?>">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label small fw-semibold">Email Address</label>
                                <input type="text" class="form-control bg-light" value="<?php echo $userData['email']; ?>" readonly>
                                <div class="form-text">Email cannot be changed.</div>
                            </div>

                            <button onclick="updateProfile();" class="btn btn-primary w-100">Save Changes</button>
                        </div>
                    </div>
                </div>

                <!-- Password Settings -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h3 class="fs-5 fw-bold mb-4"><i class="fa-solid fa-lock me-2 text-primary"></i>Security</h3>
                            
                            <div class="mb-3">
                                <label for="currPassword" class="form-label small fw-semibold">Current Password</label>
                                <input type="password" class="form-control" id="currPassword" placeholder="Enter current password">
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="mb-3">
                                <label for="newPassword" class="form-label small fw-semibold">New Password</label>
                                <input type="password" class="form-control" id="newPassword" placeholder="Minimum 8 characters">
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirmPassword" class="form-label small fw-semibold">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirmPassword" placeholder="Repeat new password">
                            </div>

                            <button onclick="changePassword();" class="btn btn-outline-primary w-100">Update Password</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php include './components/script.php'; ?>
    <script>
        function updateProfile() {
            const fname = document.getElementById('fname').value;
            const lname = document.getElementById('lname').value;
            const mobile = document.getElementById('mobile').value;

            if (!fname || !lname || !mobile) {
                alert('All fields are required');
                return;
            }

            const form = new FormData();
            form.append('fname', fname);
            form.append('lname', lname);
            form.append('mobile', mobile);

            PostRequest('/lib/update-profile-process.php', form, function(response, error) {
                if (error) {
                    alert(error);
                    return;
                }
                if (response.status) {
                    alert('Profile updated successfully');
                    location.reload();
                } else {
                    alert(response.message);
                }
            });
        }

        function changePassword() {
            const currPassword = document.getElementById('currPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (!currPassword || !newPassword || !confirmPassword) {
                alert('All password fields are required');
                return;
            }

            if (newPassword !== confirmPassword) {
                alert('New passwords do not match');
                return;
            }

            if (newPassword.length < 8) {
                alert('New password must be at least 8 characters long');
                return;
            }

            const form = new FormData();
            form.append('currPassword', currPassword);
            form.append('newPassword', newPassword);

            PostRequest('/lib/change-password-process.php', form, function(response, error) {
                if (error) {
                    alert(error);
                    return;
                }
                if (response.status) {
                    alert('Password updated successfully');
                    document.getElementById('currPassword').value = '';
                    document.getElementById('newPassword').value = '';
                    document.getElementById('confirmPassword').value = '';
                } else {
                    alert(response.message);
                }
            });
        }
    </script>
</body>
</html>