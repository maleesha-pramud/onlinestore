<?php
session_start();
include '../includes/connection.php';

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

// Fetch users with their types
$usersQuery = "SELECT u.*, ut.name as user_type_name FROM users u JOIN user_types ut ON u.user_type_id = ut.id ORDER BY u.id DESC";
$usersStmt = Database::search($usersQuery);

$userTypesStmt = Database::search("SELECT * FROM user_types");
$userTypes = [];
while ($ut = $userTypesStmt->fetch_assoc()) {
    $userTypes[] = $ut;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../components/head.php'; ?>
    <link rel="stylesheet" href="/assets/css/dashboard.css" />
</head>

<body>

    <div class="dashboard-layout">
        <aside class="dashboard-sidebar">
            <?php include '../components/AdminSidebar.php'; ?>
        </aside>

        <main class="dashboard-main">
            <header class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">User Management</h2>
                    <p class="text-muted">Manage system users, permissions, and accounts</p>
                </div>
            </header>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">User</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Type</th>
                                    <th scope="col" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($user = $usersStmt->fetch_assoc()) { 
                                    $isSelf = ($user['email'] == $_SESSION['email']);
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle bg-primary-soft text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fa-solid fa-user"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?php echo $user['fname'] . ' ' . $user['lname']; ?></div>
                                                    <div class="small text-muted"><?php echo $user['email']; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small text-muted"><i class="fa-solid fa-phone me-2"></i><?php echo $user['mobile']; ?></div>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm border-0 bg-light" style="width: 130px;" onchange="updateUserType(<?php echo $user['id']; ?>, this.value)" <?php echo $isSelf ? 'disabled' : ''; ?>>
                                                <?php foreach ($userTypes as $ut) { ?>
                                                    <option value="<?php echo $ut['id']; ?>" <?php echo ($user['user_type_id'] == $ut['id']) ? 'selected' : ''; ?>>
                                                        <?php echo $ut['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <?php if (!$isSelf) { ?>
                                                    <button onclick="deleteUser(<?php echo $user['id']; ?>)" class="btn btn-sm btn-outline-danger">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                <?php } else { ?>
                                                    <span class="badge bg-light text-dark">You</span>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php include '../components/script.php'; ?>
    <script>
        function updateUserType(userId, typeId) {
            const form = new FormData();
            form.append('userId', userId);
            form.append('typeId', typeId);

            PostRequest('/lib/update-user-type-process.php', form, function(response, error) {
                if (error) {
                    alert(error);
                    return;
                }
                if (response.status) {
                    // Success, maybe show a toast or just leave it
                } else {
                    alert(response.message);
                }
            });
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user? All their properties and bookings will also be removed.')) {
                GetRequest('/lib/delete-user-process.php', {id: id}, function(response, error) {
                    if (error) {
                        alert(error);
                        return;
                    }
                    if (response.status) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                });
            }
        }
    </script>
</body>

</html>