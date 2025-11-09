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

$user_id = $_SESSION['user_id'];

// Delete all cart items
$delete_query = "DELETE FROM cart_items WHERE user_id = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param('i', $user_id);

if ($delete_stmt->execute()) {
    $_SESSION['cart_success'] = 'Cart cleared';
} else {
    $_SESSION['cart_error'] = 'Failed to clear cart';
}

$conn->close();
header('Location: ../cart.php');
exit;
?>
