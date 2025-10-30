<?php
session_start();
include_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page with error message
    header('Location: http://localhost:5174/login?error=Please+log+in+to+view+your+order+history');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

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
          WHERE o.user_id = :user_id
          ORDER BY o.created_at DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group items by order
$orders = [];
foreach ($results as $row) {
    $order_id = $row['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
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
        $orders[$order_id]['items'][] = [
            'name' => $row['item_name'],
            'category' => $row['category'],
            'quantity' => $row['quantity'],
            'price' => $row['price']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Lecker Haus</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f6f3 0%, #fef3e6 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 40px;
        }

        .header {
            border-bottom: 3px solid #ff6b1a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #ff6b1a;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 1.1rem;
        }

        .user-info {
            background: #fff5ed;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info span {
            font-weight: 600;
            color: #333;
        }

        .back-link {
            color: #ff6b1a;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #e55a0f;
        }

        .order-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(255, 107, 26, 0.15);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f3f4f6;
        }

        .order-number {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
        }

        .order-date {
            color: #666;
            font-size: 0.95rem;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-badge.order-placed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.preparing {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.out-for-delivery {
            background: #ddd6fe;
            color: #5b21b6;
        }

        .status-badge.delivered {
            background: #d1fae5;
            color: #065f46;
        }

        .order-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .detail-item {
            padding: 10px;
            background: #f9fafb;
            border-radius: 6px;
        }

        .detail-label {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .detail-value {
            font-weight: 600;
            color: #111827;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .items-table th {
            background: #ff6b1a;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table tr:hover {
            background: #fef3e6;
        }

        .total-row {
            font-weight: 700;
            font-size: 1.2rem;
            color: #ff6b1a;
        }

        .no-orders {
            text-align: center;
            padding: 60px 20px;
        }

        .no-orders h2 {
            color: #9ca3af;
            margin-bottom: 15px;
        }

        .no-orders p {
            color: #6b7280;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            background: #ff6b1a;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #e55a0f;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .header h1 {
                font-size: 1.8rem;
            }

            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .items-table {
                font-size: 0.9rem;
            }

            .items-table th,
            .items-table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order History</h1>
            <p>View all your past orders from Lecker Haus</p>
        </div>

        <div class="user-info">
            <span>Logged in as: <strong><?php echo htmlspecialchars($username); ?></strong></span>
            <a href="http://localhost:5174" class="back-link">← Back to Restaurant</a>
        </div>

        <?php if (empty($orders)): ?>
            <div class="no-orders">
                <h2>No Orders Yet</h2>
                <p>You haven't placed any orders yet. Start exploring our delicious menu!</p>
                <a href="http://localhost:5174/menu" class="btn">Browse Menu</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-number">Order #<?php echo htmlspecialchars($order['order_id']); ?></div>
                            <div class="order-date">
                                <?php echo date('F j, Y - g:i A', strtotime($order['created_at'])); ?>
                            </div>
                        </div>
                        <span class="status-badge <?php echo str_replace(' ', '-', strtolower($order['status'])); ?>">
                            <?php echo htmlspecialchars($order['status']); ?>
                        </span>
                    </div>

                    <div class="order-details">
                        <div class="detail-item">
                            <div class="detail-label">Customer Name</div>
                            <div class="detail-value"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Phone</div>
                            <div class="detail-value"><?php echo htmlspecialchars($order['customer_phone']); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Delivery Address</div>
                            <div class="detail-value"><?php echo htmlspecialchars($order['delivery_address']); ?></div>
                        </div>
                    </div>

                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order['items'] as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="total-row">
                                <td colspan="4" style="text-align: right;">Total:</td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
