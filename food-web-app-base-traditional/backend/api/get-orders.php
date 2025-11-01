<?php
session_start();
header('Content-Type: application/json');

require_once '../../includes/config.php';
require_once '../../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    http_response_code(401);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user's orders
$sql = "SELECT id, total_amount, status, customer_name, customer_email, 
        customer_phone, delivery_address, created_at 
        FROM orders 
        WHERE user_id = ? 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    // Get order items
    $order_id = $row['id'];
    $items_sql = "SELECT oi.quantity, oi.price, mi.name 
                  FROM order_items oi 
                  JOIN menu_items mi ON oi.menu_item_id = mi.id 
                  WHERE oi.order_id = ?";
    
    $items_stmt = $conn->prepare($items_sql);
    $items_stmt->bind_param('i', $order_id);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();
    
    $items = [];
    while ($item = $items_result->fetch_assoc()) {
        $items[] = $item;
    }
    
    $row['items'] = $items;
    $orders[] = $row;
}

echo json_encode($orders);
?>
