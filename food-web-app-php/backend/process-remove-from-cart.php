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
$user_id = $_SESSION['user_id'];

if ($cart_id <= 0) {
    $_SESSION['cart_error'] = 'Invalid item';
    header('Location: ../cart.php');
    exit;
}

// Delete item
$delete_query = "DELETE FROM cart_items WHERE id = ? AND user_id = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param('ii', $cart_id, $user_id);

if ($delete_stmt->execute()) {
    $_SESSION['cart_success'] = 'Item removed from cart';
} else {
    $_SESSION['cart_error'] = 'Failed to remove item';
}

$conn->close();
header('Location: ../cart.php');
exit;
?>
