<?php
// Utility functions

// Sanitize output
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get current user
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'email' => $_SESSION['email'] ?? '',
        'full_name' => $_SESSION['full_name'] ?? ''
    ];
}

// Redirect helper
function redirect($url) {
    header("Location: $url");
    exit;
}

// Get cart count
function getCartCount($conn) {
    if (!isLoggedIn()) {
        return 0;
    }
    
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart_items WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['total'] ?? 0;
}

// Format currency
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}
?>
