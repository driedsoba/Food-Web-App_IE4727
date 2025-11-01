<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

session_start();

require_once '../../includes/config.php';
require_once '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$itemId = isset($input['item_id']) ? (int)$input['item_id'] : 0;
$quantity = isset($input['quantity']) ? (int)$input['quantity'] : 1;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Please login first']);
    exit;
}

$userId = $_SESSION['user_id'];

// Check if item already in cart
$sql = "SELECT id, quantity FROM cart_items WHERE user_id = ? AND menu_item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $userId, $itemId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update quantity
    $row = $result->fetch_assoc();
    $newQuantity = $row['quantity'] + $quantity;
    
    $sql = "UPDATE cart_items SET quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $newQuantity, $row['id']);
    $stmt->execute();
} else {
    // Insert new item
    $sql = "INSERT INTO cart_items (user_id, menu_item_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii', $userId, $itemId, $quantity);
    $stmt->execute();
}

echo json_encode(['success' => true, 'message' => 'Item added to cart']);
?>
