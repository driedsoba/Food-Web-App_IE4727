<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../menu.php');
    exit;
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_error'] = 'Please login to add items to cart';
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$menu_item_id = intval($_POST['menu_item_id'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 1);

if ($menu_item_id <= 0 || $quantity <= 0) {
    $_SESSION['cart_error'] = 'Invalid item or quantity';
    header('Location: ../menu.php');
    exit;
}

// Check if item exists in menu
$check_query = "SELECT id, name FROM menu_items WHERE id = ? AND is_active = 1";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param('i', $menu_item_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['cart_error'] = 'Item not found';
    header('Location: ../menu.php');
    exit;
}

$item = $result->fetch_assoc();

// Check if already in cart
$cart_query = "SELECT id, quantity FROM cart_items WHERE user_id = ? AND menu_item_id = ?";
$cart_stmt = $conn->prepare($cart_query);
$cart_stmt->bind_param('ii', $user_id, $menu_item_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

if ($cart_result->num_rows > 0) {
    // Update quantity
    $existing = $cart_result->fetch_assoc();
    $new_quantity = $existing['quantity'] + $quantity;
    
    $update_query = "UPDATE cart_items SET quantity = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('ii', $new_quantity, $existing['id']);
    $update_stmt->execute();
} else {
    // Insert new item
    $insert_query = "INSERT INTO cart_items (user_id, menu_item_id, quantity) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param('iii', $user_id, $menu_item_id, $quantity);
    $insert_stmt->execute();
}

$_SESSION['cart_success'] = 'Added to cart: ' . $item['name'];
$conn->close();
header('Location: ../menu.php');
exit;
?>
