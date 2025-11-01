<?php
header('Content-Type: application/json');
session_start();

require_once '../../includes/config.php';
require_once '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Please login first']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user_id'];

// Validate input
if (empty($input['customer_name']) || empty($input['customer_email']) || 
    empty($input['customer_phone']) || empty($input['delivery_address']) ||
    empty($input['items'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Calculate total
$subtotal = 0;
foreach ($input['items'] as $item) {
    $subtotal += floatval($item['price']) * intval($item['quantity']);
}
$deliveryFee = 5.00;
$total = $subtotal + $deliveryFee;

// Begin transaction
$conn->begin_transaction();

try {
    // Insert order (schema uses created_at, not order_date)
    $sql = "INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, 
                                delivery_address, total_amount, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'order placed')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issssd', $userId, 
                     $input['customer_name'],
                     $input['customer_email'],
                     $input['customer_phone'],
                     $input['delivery_address'],
                     $total);
    $stmt->execute();
    
    $orderId = $conn->insert_id;
    
    // Insert order items
    $sql = "INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    foreach ($input['items'] as $item) {
        $stmt->bind_param('iiid', $orderId, $item['menu_item_id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }
    
    // Clear cart
    $sql = "DELETE FROM cart_items WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    
    $conn->commit();
    
    echo json_encode(['success' => true, 'order_id' => $orderId]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => 'Failed to place order: ' . $e->getMessage()]);
}
?>
