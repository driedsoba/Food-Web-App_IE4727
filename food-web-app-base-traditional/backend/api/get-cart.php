<?php
session_start();
header('Content-Type: application/json');

require_once '../../includes/config.php';
require_once '../../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$userId = $_SESSION['user_id'];

// Get cart items with menu item details
$sql = "SELECT c.id, c.menu_item_id, c.quantity, 
               m.name, m.description, m.price, m.category, m.image_url
        FROM cart_items c
        JOIN menu_items m ON c.menu_item_id = m.id
        WHERE c.user_id = ?
        ORDER BY c.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode($items);
?>
