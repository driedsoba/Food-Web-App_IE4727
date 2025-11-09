<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_error'] = 'Please login to view your cart';
    header('Location: login.php');
    exit;
}

$pageTitle = 'Shopping Cart - ' . SITE_NAME;
$additionalCSS = 'cart.css';

$user_id = $_SESSION['user_id'];
$query = "SELECT c.id as cart_id, c.quantity, m.id as menu_id, m.name, m.price, m.image_url FROM cart_items c INNER JOIN menu_items m ON c.menu_item_id = m.id WHERE c.user_id = ? ORDER BY c.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cartItems = $result->fetch_all(MYSQLI_ASSOC);

$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$deliveryFee = 5.00;
$total = $subtotal + $deliveryFee;

$success = $_SESSION['cart_success'] ?? '';
$error = $_SESSION['cart_error'] ?? '';
unset($_SESSION['cart_success'], $_SESSION['cart_error']);

include 'includes/header.php';
?>

<div class="cart-page">
    <div class="cart-header">
        <h1>Shopping Cart</h1>
        <p id="cartItemCount"><?php echo count($cartItems); ?> items</p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (count($cartItems) === 0): ?>
        <div id="emptyCart" class="empty-cart">
            <p>Your cart is empty</p>
            <a href="menu.php" class="btn-primary">Browse Menu</a>
        </div>
    <?php else: ?>
        <div id="cartContent" class="cart-content">
            <div class="cart-items-section">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="cartItemsBody">
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td>
                                    <div class="item-info">
                                        <?php if ($item['image_url']): ?>
                                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="item-image">
                                        <?php endif; ?>
                                        <span class="item-name"><?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <form method="POST" action="backend/process-update-cart.php" class="quantity-form">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="99" class="quantity-input" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                    <form method="POST" action="backend/process-remove-from-cart.php" style="display:inline;">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <button type="submit" class="btn-remove">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="checkout-section">
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="subtotal">$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee:</span>
                        <span>$<?php echo number_format($deliveryFee, 2); ?></span>
                    </div>
                    <div class="summary-row total">
                        <strong>Total:</strong>
                        <strong id="total">$<?php echo number_format($total, 2); ?></strong>
                    </div>
                </div>

                <form method="POST" action="backend/process-checkout.php" class="checkout-form">
                    <h3>Delivery Information</h3>
                    
                    <div class="form-group">
                        <label for="customerName">Full Name *</label>
                        <input type="text" id="customerName" name="customer_name" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="customerEmail">Email *</label>
                        <input type="email" id="customerEmail" name="customer_email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="customerPhone">Phone Number *</label>
                        <input type="tel" id="customerPhone" name="customer_phone" placeholder="e.g., 67489380" maxlength="8" required>
                        <small class="form-hint">Must be exactly 8 digits</small>
                    </div>

                    <div class="form-group">
                        <label for="deliveryAddress">Delivery Address *</label>
                        <textarea id="deliveryAddress" name="delivery_address" rows="3" placeholder="Enter your full delivery address" required></textarea>
                        <small class="form-hint">Minimum 10 characters</small>
                    </div>

                    <button type="submit" class="btn-primary">Place Order</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
