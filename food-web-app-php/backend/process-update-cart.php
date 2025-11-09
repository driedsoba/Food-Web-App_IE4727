<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cart.php');
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$cart_id = intval($_POST['cart_id'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 1);

if ($cart_id <= 0 || $quantity <= 0) {
    $_SESSION['cart_error'] = 'Invalid quantity';
    header('Location: ../cart.php');
    exit;
}

// Verify ownership
$user_id = $_SESSION['user_id'];
$check_query = "SELECT id FROM cart_items WHERE id = ? AND user_id = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param('ii', $cart_id, $user_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['cart_error'] = 'Cart item not found';
    header('Location: ../cart.php');
    exit;
}

// Update quantity
$update_query = "UPDATE cart_items SET quantity = ? WHERE id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param('ii', $quantity, $cart_id);

if ($update_stmt->execute()) {
    $_SESSION['cart_success'] = 'Cart updated';
} else {
    $_SESSION['cart_error'] = 'Failed to update cart';
}

$conn->close();
header('Location: ../cart.php');
exit;
?>
