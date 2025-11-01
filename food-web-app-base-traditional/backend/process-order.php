<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Require login
if (!isLoggedIn()) {
    redirect('../login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../cart.php');
}

$userId = $_SESSION['user_id'];

// Server-side validation
$errors = [];

$customerName = trim($_POST['customer_name'] ?? '');
$customerEmail = trim($_POST['customer_email'] ?? '');
$customerPhone = trim($_POST['customer_phone'] ?? '');
$deliveryAddress = trim($_POST['delivery_address'] ?? '');

// Validate inputs
if (empty($customerName)) {
    $errors[] = 'Name is required';
} elseif (strlen($customerName) < 2) {
    $errors[] = 'Name must be at least 2 characters';
}

if (empty($customerEmail)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}

if (empty($customerPhone)) {
    $errors[] = 'Phone number is required';
}

if (empty($deliveryAddress)) {
    $errors[] = 'Delivery address is required';
} elseif (strlen($deliveryAddress) < 10) {
    $errors[] = 'Please provide a complete delivery address';
}

// Get cart items
$stmt = $conn->prepare("
    SELECT ci.menu_item_id, ci.quantity, mi.price
    FROM cart_items ci
    JOIN menu_items mi ON ci.menu_item_id = mi.id
    WHERE ci.user_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$cartItems = $result->fetch_all(MYSQLI_ASSOC);

if (empty($cartItems)) {
    $errors[] = 'Cart is empty';
}

// If there are errors, redirect back with error messages
if (!empty($errors)) {
    $_SESSION['cart_error'] = implode(', ', $errors);
    redirect('../cart.php');
}

// Begin transaction
$conn->begin_transaction();

try {
    // Calculate total
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }
    $totalAmount += 5.00; // Add delivery fee
    
    // Insert order
    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, delivery_address, total_amount, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");
    $stmt->bind_param("issssd", $userId, $customerName, $customerEmail, $customerPhone, $deliveryAddress, $totalAmount);
    $stmt->execute();
    $orderId = $conn->insert_id;
    
    // Insert order items
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)");
    
    foreach ($cartItems as $item) {
        $stmt->bind_param("iiid", $orderId, $item['menu_item_id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }
    
    // Clear cart
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    // Commit transaction
    $conn->commit();
    
    // Set success message and redirect
    $_SESSION['order_id'] = $orderId;
    $_SESSION['order_success'] = true;
    redirect('../order-confirmation.php');
    
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    $_SESSION['cart_error'] = 'Failed to process order. Please try again.';
    redirect('../cart.php');
}
?>
