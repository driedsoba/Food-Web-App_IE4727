<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../checkout.php');
    exit;
}

$customer_name = trim($_POST['customer_name'] ?? '');
$customer_email = trim($_POST['customer_email'] ?? '');
$customer_phone = trim($_POST['customer_phone'] ?? '');
$delivery_address = trim($_POST['delivery_address'] ?? '');

// Validation
if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($delivery_address)) {
    $_SESSION['checkout_error'] = 'Please fill in all required fields';
    header('Location: ../checkout.php');
    exit;
}

// Validate email format
if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['checkout_error'] = 'Invalid email format';
    header('Location: ../checkout.php');
    exit;
}

// Validate address length (minimum 10 characters)
if (strlen($delivery_address) < 10) {
    $_SESSION['checkout_error'] = 'Address must be at least 10 characters';
    header('Location: ../checkout.php');
    exit;
}

// Validate phone number (exactly 8 digits)
$clean_phone = preg_replace('/\s/', '', $customer_phone);
if (!preg_match('/^\d{8}$/', $clean_phone)) {
    $_SESSION['checkout_error'] = 'Phone number must be exactly 8 digits (e.g., 67489380)';
    header('Location: ../checkout.php');
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;

// Get cart items if logged in
$cartItems = [];
if ($user_id) {
    $cart_query = "SELECT c.quantity, m.id as menu_id, m.price
                   FROM cart_items c
                   INNER JOIN menu_items m ON c.menu_item_id = m.id
                   WHERE c.user_id = ?";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param('i', $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();
    $cartItems = $cart_result->fetch_all(MYSQLI_ASSOC);
    
    if (count($cartItems) === 0) {
        $_SESSION['checkout_error'] = 'Your cart is empty';
        header('Location: ../cart.php');
        exit;
    }
}

// Calculate total
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Add tax and delivery
$total = ($total * 1.1) + 5;

// Start transaction
$conn->begin_transaction();

try {
    // Insert order
    $order_query = "INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, delivery_address, total_amount, status) 
                    VALUES (?, ?, ?, ?, ?, ?, 'order placed')";
    $order_stmt = $conn->prepare($order_query);
    $order_stmt->bind_param('issssd', $user_id, $customer_name, $customer_email, $customer_phone, $delivery_address, $total);
    $order_stmt->execute();
    
    $order_id = $conn->insert_id;
    
    // Insert order items
    $item_query = "INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)";
    $item_stmt = $conn->prepare($item_query);
    
    foreach ($cartItems as $item) {
        $item_stmt->bind_param('iiid', $order_id, $item['menu_id'], $item['quantity'], $item['price']);
        $item_stmt->execute();
    }
    
    // Clear cart if logged in
    if ($user_id) {
        $clear_query = "DELETE FROM cart_items WHERE user_id = ?";
        $clear_stmt = $conn->prepare($clear_query);
        $clear_stmt->bind_param('i', $user_id);
        $clear_stmt->execute();
    }
    
    $conn->commit();
    
    // Redirect to confirmation
    header('Location: ../order-confirmation.php?order_id=' . $order_id);
    exit;
    
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['checkout_error'] = 'Failed to place order. Please try again.';
    header('Location: ../checkout.php');
    exit;
}

$conn->close();
?>
