<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Require login
if (!isLoggedIn()) {
    redirect('login.php');
}

// Check if order was just placed
if (!isset($_SESSION['order_success']) || !isset($_SESSION['order_id'])) {
    redirect('index.php');
}

$orderId = $_SESSION['order_id'];
unset($_SESSION['order_success']);
unset($_SESSION['order_id']);

// Get order details
$stmt = $conn->prepare("
    SELECT id, customer_name, customer_email, customer_phone, 
           delivery_address, total_amount, status, created_at
    FROM orders
    WHERE id = ? AND user_id = ?
");
$stmt->bind_param("ii", $orderId, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    redirect('index.php');
}

// Get order items
$stmt = $conn->prepare("
    SELECT oi.quantity, oi.price, mi.name
    FROM order_items oi
    JOIN menu_items mi ON oi.menu_item_id = mi.id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$orderItems = $result->fetch_all(MYSQLI_ASSOC);

require_once 'includes/header.php';
?>

<div class="order-confirmation">
    <h2>Order Placed Successfully!</h2>
    
    <div class="success-message">
        <p>Thank you for your order! Your order has been received and is being processed.</p>
        <p><strong>Order #<?php echo h($order['id']); ?></strong></p>
    </div>
    
    <div class="order-details">
        <h3>Order Details</h3>
        
        <div class="detail-section">
            <h4>Delivery Information</h4>
            <p><strong>Name:</strong> <?php echo h($order['customer_name']); ?></p>
            <p><strong>Email:</strong> <?php echo h($order['customer_email']); ?></p>
            <p><strong>Phone:</strong> <?php echo h($order['customer_phone']); ?></p>
            <p><strong>Address:</strong> <?php echo h($order['delivery_address']); ?></p>
        </div>
        
        <div class="detail-section">
            <h4>Order Items</h4>
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderItems as $item): ?>
                        <tr>
                            <td><?php echo h($item['name']); ?></td>
                            <td><?php echo formatCurrency($item['price']); ?></td>
                            <td><?php echo h($item['quantity']); ?></td>
                            <td><?php echo formatCurrency($item['price'] * $item['quantity']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total:</strong></td>
                        <td><strong><?php echo formatCurrency($order['total_amount']); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="detail-section">
            <p><strong>Status:</strong> <span class="status-<?php echo h($order['status']); ?>"><?php echo ucfirst(h($order['status'])); ?></span></p>
            <p><strong>Order Date:</strong> <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></p>
        </div>
    </div>
    
    <div class="form-actions">
        <a href="menu.php" class="btn btn-primary">Order More</a>
        <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
