<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Require login
if (!isLoggedIn()) {
    redirect('../login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../cart.php');
}

$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add':
        $menuItemId = (int)($_POST['menu_item_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if ($menuItemId <= 0 || $quantity <= 0) {
            $_SESSION['cart_error'] = 'Invalid item or quantity';
            redirect('../menu.php');
        }
        
        // Check if item already in cart
        $stmt = $conn->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND menu_item_id = ?");
        $stmt->bind_param("ii", $userId, $menuItemId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($existing = $result->fetch_assoc()) {
            // Update quantity
            $newQuantity = $existing['quantity'] + $quantity;
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
            $stmt->bind_param("ii", $newQuantity, $existing['id']);
            $stmt->execute();
        } else {
            // Insert new item
            $stmt = $conn->prepare("INSERT INTO cart_items (user_id, menu_item_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $userId, $menuItemId, $quantity);
            $stmt->execute();
        }
        
        $_SESSION['cart_message'] = 'Item added to cart';
        redirect('../menu.php');
        break;
        
    case 'update':
        $cartItemId = (int)($_POST['cart_item_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        
        if ($quantity > 0) {
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("iii", $quantity, $cartItemId, $userId);
            $stmt->execute();
            $_SESSION['cart_message'] = 'Quantity updated';
        } else {
            // Remove if quantity is 0
            $stmt = $conn->prepare("DELETE FROM cart_items WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $cartItemId, $userId);
            $stmt->execute();
            $_SESSION['cart_message'] = 'Item removed from cart';
        }
        
        redirect('../cart.php');
        break;
        
    case 'remove':
        $cartItemId = (int)($_POST['cart_item_id'] ?? 0);
        
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cartItemId, $userId);
        $stmt->execute();
        
        $_SESSION['cart_message'] = 'Item removed from cart';
        redirect('../cart.php');
        break;
        
    default:
        redirect('../cart.php');
}
?>
