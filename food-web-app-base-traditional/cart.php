<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Require login
if (!isLoggedIn()) {
    redirect('login.php');
}

$userId = $_SESSION['user_id'];

// Get cart items
$stmt = $conn->prepare("
    SELECT ci.id, ci.menu_item_id, ci.quantity, 
           mi.name, mi.price, mi.image_url, mi.category
    FROM cart_items ci
    JOIN menu_items mi ON ci.menu_item_id = mi.id
    WHERE ci.user_id = ?
    ORDER BY ci.id DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$cartItems = $result->fetch_all(MYSQLI_ASSOC);

// Calculate totals
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$deliveryFee = 5.00;
$total = $subtotal + $deliveryFee;

// Get user info for pre-filling
$user = getCurrentUser();

require_once 'includes/header.php';
?>

<h2>Shopping Cart</h2>

<?php if (isset($_SESSION['cart_message'])): ?>
    <div class="message success"><?php echo h($_SESSION['cart_message']); ?></div>
    <?php unset($_SESSION['cart_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['cart_error'])): ?>
    <div class="message error"><?php echo h($_SESSION['cart_error']); ?></div>
    <?php unset($_SESSION['cart_error']); ?>
<?php endif; ?>

<?php if (empty($cartItems)): ?>
    <div class="empty-cart">
        <p>Your cart is empty.</p>
        <a href="menu.php" class="btn btn-primary">Browse Menu</a>
    </div>
<?php else: ?>
    <div class="cart-content">
        <!-- Cart Items Table -->
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td>
                            <div class="cart-item-info">
                                <?php if (!empty($item['image_url'])): ?>
                                    <img src="<?php echo h($item['image_url']); ?>" 
                                         alt="<?php echo h($item['name']); ?>" 
                                         width="50">
                                <?php endif; ?>
                                <div>
                                    <strong><?php echo h($item['name']); ?></strong>
                                    <br><small><?php echo h($item['category']); ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?php echo formatCurrency($item['price']); ?></td>
                        <td>
                            <!-- Update Quantity Form -->
                            <form method="POST" action="backend/process-cart.php" class="quantity-form">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="cart_item_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantity" 
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="1" max="99" required>
                                <button type="submit" class="btn btn-small">Update</button>
                            </form>
                        </td>
                        <td><?php echo formatCurrency($item['price'] * $item['quantity']); ?></td>
                        <td>
                            <!-- Remove Item Form -->
                            <form method="POST" action="backend/process-cart.php" style="display: inline;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="cart_item_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-small">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Subtotal:</strong></td>
                    <td colspan="2"><?php echo formatCurrency($subtotal); ?></td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Delivery Fee:</strong></td>
                    <td colspan="2"><?php echo formatCurrency($deliveryFee); ?></td>
                </tr>
                <tr class="total-row">
                    <td colspan="3"><strong>Total:</strong></td>
                    <td colspan="2"><strong><?php echo formatCurrency($total); ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- Checkout Form -->
        <div class="checkout-section">
            <h3>Delivery Information</h3>
            
            <form method="POST" action="backend/process-order.php" 
                  onsubmit="return validateCheckoutForm(this);" 
                  class="checkout-form">
                
                <div class="form-group">
                    <label for="customer_name">Full Name: *</label>
                    <input type="text" id="customer_name" name="customer_name" 
                           value="<?php echo h($user['full_name']); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="customer_email">Email: *</label>
                    <input type="email" id="customer_email" name="customer_email" 
                           value="<?php echo h($user['email']); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="customer_phone">Phone Number: *</label>
                    <input type="tel" id="customer_phone" name="customer_phone" 
                           placeholder="+65 1234 5678" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="delivery_address">Delivery Address: *</label>
                    <textarea id="delivery_address" name="delivery_address" 
                              rows="3" placeholder="123 Main St, #01-23, Singapore 123456" 
                              required></textarea>
                </div>
                
                <div class="form-actions">
                    <a href="menu.php" class="btn btn-secondary">Continue Shopping</a>
                    <button type="submit" class="btn btn-primary">Place Order</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
