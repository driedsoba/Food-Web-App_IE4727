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
$cartId = isset($input['cart_id']) ? (int)$input['cart_id'] : 0;
$quantity = isset($input['quantity']) ? (int)$input['quantity'] : 1;

if ($quantity < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid quantity']);
    exit;
}

$userId = $_SESSION['user_id'];

// Update quantity
$sql = "UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iii', $quantity, $cartId, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update cart']);
}
?>
