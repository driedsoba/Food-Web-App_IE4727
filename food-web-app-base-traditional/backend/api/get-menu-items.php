<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../../includes/config.php';
require_once '../../includes/db.php';

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;

// Build SQL query
$sql = "SELECT id, name, description, price, category, image_url, rating FROM menu_items WHERE 1=1";

if ($category && $category !== 'All') {
    $sql .= " AND category = ?";
}

if ($search) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
}

$sql .= " ORDER BY name ASC";

// Prepare and execute
$stmt = $conn->prepare($sql);

if ($category && $category !== 'All' && $search) {
    $searchParam = "%$search%";
    $stmt->bind_param('sss', $category, $searchParam, $searchParam);
} elseif ($category && $category !== 'All') {
    $stmt->bind_param('s', $category);
} elseif ($search) {
    $searchParam = "%$search%";
    $stmt->bind_param('ss', $searchParam, $searchParam);
}

$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode($items);
?>
