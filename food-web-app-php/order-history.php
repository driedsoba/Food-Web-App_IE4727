<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_error'] = 'Please login to view order history';
    header('Location: login.php');
    exit;
}

$pageTitle = 'Order History - ' . SITE_NAME;
$additionalCSS = 'order-history.css';

$user_id = $_SESSION['user_id'];

// Fetch user's orders
$query = "SELECT o.id, o.total_amount, o.status, o.created_at,
                 COUNT(oi.id) as item_count
          FROM orders o
          LEFT JOIN order_items oi ON o.id = oi.order_id
          WHERE o.user_id = ?
          GROUP BY o.id
          ORDER BY o.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

$success = $_SESSION['order_success'] ?? '';
unset($_SESSION['order_success']);

include 'includes/header.php';
?>

<div class="order-history-page">
    <div class="orders-content">
        <h1>Order History</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <?php if (count($orders) === 0): ?>
        <div class="no-orders">
            <p>You haven't placed any orders yet.</p>
            <a href="menu.php" class="btn btn-primary">Browse Menu</a>
        </div>
    <?php else: ?>
        <div class="orders-container">
            <?php foreach ($orders as $order): ?>
                <?php
                // Fetch order items
                $items_query = "SELECT m.name, oi.quantity, oi.price
                               FROM order_items oi
                               INNER JOIN menu_items m ON oi.menu_item_id = m.id
                               WHERE oi.order_id = ?";
                $items_stmt = $conn->prepare($items_query);
                $items_stmt->bind_param('i', $order['id']);
                $items_stmt->execute();
                $items_result = $items_stmt->get_result();
                $items = $items_result->fetch_all(MYSQLI_ASSOC);
                ?>
                
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <h3>Order #<?php echo $order['id']; ?></h3>
                            <p class="order-date"><?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?></p>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $order['status'])); ?>">
                                <?php echo htmlspecialchars($order['status']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="order-items">
                        <h4>Items (<?php echo $order['item_count']; ?>):</h4>
                        <ul>
                            <?php foreach ($items as $item): ?>
                                <li>
                                    <?php echo htmlspecialchars($item['name']); ?> 
                                    × <?php echo $item['quantity']; ?>
                                    - $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="order-footer">
                        <div class="order-total">
                            <strong>Total: $<?php echo number_format($order['total_amount'], 2); ?></strong>
                        </div>
                        <div class="order-actions">
                            <?php if ($order['status'] !== 'delivered' && $order['status'] !== 'cancelled'): ?>
                                <form method="POST" action="backend/process-advance-order.php" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-primary">Advance Status</button>
                                </form>
                            <?php endif; ?>
                            <a href="order-confirmation.php?order_id=<?php echo $order['id']; ?>" class="btn btn-sm btn-secondary">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    </div>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
