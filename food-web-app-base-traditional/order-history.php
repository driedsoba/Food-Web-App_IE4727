<?php
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
$userId = $_SESSION['user_id'] ?? null;

// Initialize orders array
$orders = [];
$errorMessage = '';

if ($isLoggedIn) {
    try {
        // Include database connection
        require_once 'includes/db.php';
        
        // Fetch user's orders with items
        $query = "SELECT 
                    o.id as order_id,
                    o.total_amount,
                    o.status,
                    o.customer_name,
                    o.customer_phone,
                    o.delivery_address,
                    o.created_at,
                    oi.quantity,
                    oi.price,
                    m.name as item_name,
                    m.category
                  FROM orders o
                  LEFT JOIN order_items oi ON o.id = oi.order_id
                  LEFT JOIN menu_items m ON oi.menu_item_id = m.id
                  WHERE o.user_id = ?
                  ORDER BY o.created_at DESC";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        // Group items by order
        while ($row = mysqli_fetch_assoc($result)) {
            $orderId = $row['order_id'];
            if (!isset($orders[$orderId])) {
                $orders[$orderId] = [
                    'order_id' => $row['order_id'],
                    'total_amount' => $row['total_amount'],
                    'status' => $row['status'],
                    'customer_name' => $row['customer_name'],
                    'customer_phone' => $row['customer_phone'],
                    'delivery_address' => $row['delivery_address'],
                    'created_at' => $row['created_at'],
                    'items' => []
                ];
            }
            if ($row['item_name']) {
                $orders[$orderId]['items'][] = [
                    'name' => $row['item_name'],
                    'category' => $row['category'],
                    'quantity' => $row['quantity'],
                    'price' => $row['price']
                ];
            }
        }
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
    } catch (Exception $e) {
        $errorMessage = 'Error loading orders: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - LeckerHaus</title>
    <link rel="stylesheet" href="css/base.css?v=2.0">
    <link rel="stylesheet" href="css/order-history.css?v=2.0">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <h1>LeckerHaus</h1>
            </div>
            <nav class="navigation">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="menu.html">Menu</a></li>
                    <li><a href="catering.html">Catering</a></li>
                    <li><a href="order-history.php" class="active">Order History</a></li>
                    <li><a href="feedback.html">Feedback</a></li>
                </ul>
            </nav>
            <div class="cart-section">
                <?php if ($isLoggedIn): ?>
                    <span class="user-greeting">Hello, <?php echo htmlspecialchars($username); ?>!</span>
                    <form action="backend/logout.html" method="POST" style="display: inline;">
                        <button type="submit" class="logout-button">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="login.html" class="login-button">
                        <span class="login-text">Login</span>
                    </a>
                <?php endif; ?>
                <a href="cart.html" class="cart-button">
                    <span class="cart-text">Cart</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Order History Page -->
    <div class="order-history-page">
        <div class="page-header">
            <h1>Order History</h1>
            <p>Track your past orders</p>
        </div>

        <?php if (!$isLoggedIn): ?>
            <div class="login-required">
                <p>Please login to view your order history.</p>
                <a href="login.html" class="btn-primary">Login</a>
            </div>
        <?php elseif ($errorMessage): ?>
            <div class="error-state">
                <p><?php echo htmlspecialchars($errorMessage); ?></p>
            </div>
        <?php elseif (empty($orders)): ?>
            <div class="empty-state">
                <p>You haven't placed any orders yet.</p>
                <a href="menu.html" class="btn-primary">Browse Menu</a>
            </div>
        <?php else: ?>
            <div class="orders-content">
                <div class="orders-container">
                    <?php foreach ($orders as $order): ?>
                        <article class="order-card">
                            <div class="order-header">
                                <div class="order-info">
                                    <h3>Order #<?php echo htmlspecialchars($order['order_id']); ?></h3>
                                    <span class="order-date">
                                        <?php echo date('F j, Y - g:i A', strtotime($order['created_at'])); ?>
                                    </span>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $order['status'])); ?>">
                                        <?php echo htmlspecialchars($order['status']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="order-details">
                                <div class="order-items">
                                    <h4>Items:</h4>
                                    <ul>
                                        <?php foreach ($order['items'] as $item): ?>
                                            <li>
                                                <?php echo $item['quantity']; ?>x 
                                                <?php echo htmlspecialchars($item['name']); ?> 
                                                (<?php echo htmlspecialchars($item['category']); ?>) - 
                                                $<?php echo number_format($item['price'], 2); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                
                                <div class="order-meta">
                                    <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                                    <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['delivery_address']); ?></p>
                                    <p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <p>&copy; 2024 LeckerHaus. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/auth.js"></script>
</body>
</html>
