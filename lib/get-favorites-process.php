<?php
include '../includes/connection.php';

if (!isset($_POST['ids'])) {
    echo json_encode(['status' => false, 'message' => 'No IDs provided.']);
    exit();
}

$idsArray = JSON_decode($_POST['ids']);
if (empty($idsArray)) {
    echo json_encode(['status' => true, 'data' => []]);
    exit();
}

// Ensure IDs are integers to prevent injection
$ids = array_map('intval', $idsArray);
$idsString = implode(',', $ids);

$query = "
    SELECT p.*, AVG(r.rating) AS avg_rating, COUNT(r.id) AS review_count 
    FROM properties p 
    LEFT JOIN reviews r ON p.id = r.properties_id 
    WHERE p.id IN ($idsString)
    GROUP BY p.id
";

$result = Database::search($query);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['status' => true, 'data' => $data]);
