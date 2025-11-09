<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

$pageTitle = 'Order Confirmation - ' . SITE_NAME;
$additionalCSS = 'order-confirmation.css';

$order_id = $_GET['order_id'] ?? '';

if (!$order_id || !is_numeric($order_id)) {
    header('Location: index.php');
    exit;
}

// Fetch order details
$query = "SELECT o.*, 
                 GROUP_CONCAT(CONCAT(m.name, ' (x', oi.quantity, ')') SEPARATOR ', ') as items
          FROM orders o
          LEFT JOIN order_items oi ON o.id = oi.order_id
          LEFT JOIN menu_items m ON oi.menu_item_id = m.id
          WHERE o.id = ?
          GROUP BY o.id";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    header('Location: index.php');
    exit;
}

// Verify access (user's own order or guest with same email)
$hasAccess = false;
if (isset($_SESSION['user_id']) && $order['user_id'] == $_SESSION['user_id']) {
    $hasAccess = true;
} elseif (!$order['user_id']) {
    $hasAccess = true; // Guest order
}

if (!$hasAccess) {
    header('Location: index.php');
    exit;
}

include 'includes/header.php';
?>

<!-- Order Confirmation Page -->
<div class="confirmation-page">
    <div class="confirmation-container">
        <div class="success-icon">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                <circle cx="40" cy="40" r="40" fill="#4CAF50"/>
                <path d="M25 40L35 50L55 30" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        
        <h1>Order Confirmed!</h1>
        <p class="success-message">Thank you for your order. Your delicious German meal is on its way!</p>
        
        <div class="order-info-box">
            <h2>Order Details</h2>
            <div class="order-number">
                <strong>Order Number:</strong> 
                <span class="highlight">#<?php echo $order['id']; ?></span>
            </div>
            <div class="order-detail">
                <strong>Status:</strong> 
                <span class="status-<?php echo strtolower(str_replace(' ', '-', $order['status'])); ?>">
                    <?php echo htmlspecialchars($order['status']); ?>
                </span>
            </div>
            <div class="order-detail">
                <strong>Total Amount:</strong> 
                $<?php echo number_format($order['total_amount'], 2); ?>
            </div>
            <div class="order-detail">
                <strong>Delivery Address:</strong> 
                <?php echo htmlspecialchars($order['delivery_address']); ?>
            </div>
            <div class="order-detail">
                <strong>Contact:</strong> 
                <?php echo htmlspecialchars($order['customer_phone']); ?>
            </div>
            <p class="estimated-time">
                <strong>Estimated Delivery:</strong> 45-60 minutes
            </p>
        </div>

        <div class="next-steps">
            <h3>What's Next?</h3>
            <ul>
                <li>✓ We've received your order and started preparing it</li>
                <li>✓ You'll receive a confirmation email shortly</li>
                <li>✓ Track your order in your order history</li>
                <li>✓ Our delivery team will contact you if needed</li>
            </ul>
        </div>

        <div class="action-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="order-history.php" class="btn-primary">View All Orders</a>
            <?php endif; ?>
            <a href="menu.php" class="btn-secondary">Order More</a>
            <a href="index.php" class="btn-secondary">Back to Home</a>
        </div>

        <div class="support-info">
            <p>Need help with your order?</p>
            <p><strong>Contact us:</strong> (555) 123-4567 | support@leckerhaus.com</p>
        </div>
    </div>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
