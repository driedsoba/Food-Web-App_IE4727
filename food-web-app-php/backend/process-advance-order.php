<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../order-history.php');
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$order_id = intval($_POST['order_id'] ?? 0);
$user_id = $_SESSION['user_id'];

if ($order_id <= 0) {
    $_SESSION['order_error'] = 'Invalid order';
    header('Location: ../order-history.php');
    exit;
}

// Verify ownership
$check_query = "SELECT status FROM orders WHERE id = ? AND user_id = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param('ii', $order_id, $user_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['order_error'] = 'Order not found';
    header('Location: ../order-history.php');
    exit;
}

$order = $result->fetch_assoc();
$current_status = $order['status'];

// Define status progression
$statuses = ['order placed', 'preparing', 'out for delivery', 'delivered'];
$current_index = array_search($current_status, $statuses);

if ($current_index === false || $current_index >= count($statuses) - 1) {
    $_SESSION['order_error'] = 'Order cannot be advanced further';
    header('Location: ../order-history.php');
    exit;
}

$next_status = $statuses[$current_index + 1];

// Update status
$update_query = "UPDATE orders SET status = ? WHERE id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param('si', $next_status, $order_id);

if ($update_stmt->execute()) {
    $_SESSION['order_success'] = 'Order status updated to: ' . $next_status;
} else {
    $_SESSION['order_error'] = 'Failed to update order status';
}

$conn->close();
header('Location: ../order-history.php');
exit;
?>
