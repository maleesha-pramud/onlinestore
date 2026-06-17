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
}

$userId = $userData['id'];
$userType = $userData['user_type_id'];

$propertiesStmt = null;
if ($userType == 1) { // Admin
    $propertiesStmt = Database::search("SELECT * FROM properties");
} else { // Producer
    $propertiesStmt = Database::search("SELECT * FROM properties WHERE `users_id` = $userId");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../components/head.php'; ?>
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
</head>
<body>
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar">
            <?php 
            if ($userType == 1) {
                include '../components/AdminSidebar.php';
            } else {
                include '../components/ProducerSidebar.php';
            }
            ?>
        </aside>
        <main class="dashboard-main">
            <div class="page-header d-flex justify-content-between align-items-center">
                <h1>My Properties</h1>
                <a href="/listing/add.php" class="btn btn-primary">Add New Property</a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable align-middle">
                            <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($property = $propertiesStmt->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $property['title']; ?></td>
                                    <td>
                                        <?php
                                        $categoryId = $property['categories_id'];
                                        $categoryStmt = Database::search('SELECT name FROM categories WHERE id = ' . $categoryId);
                                        $category = $categoryStmt->fetch_assoc();
                                        echo $category['name'];
                                        ?>
                                    </td>
                                    <td>LKR <?php echo number_format($property['base_price']); ?> / night</td>
                                    <td class="action-links">
                                        <a href="/listing/edit.php?id=<?php echo $property['id']; ?>">Edit</a>
                                        <a href="#" onclick="deleteListing(<?php echo $property['id']; ?>)" class="text-danger">Delete</a>
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
</body>
</html>