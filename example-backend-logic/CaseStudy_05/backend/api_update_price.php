<?php
// API to update product price (Backend only - returns JSON)
header('Content-Type: application/json');
include "dbconnect.php";

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate and sanitize input
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $price_single = filter_input(INPUT_POST, 'price_single', FILTER_VALIDATE_FLOAT);
    $price_double = filter_input(INPUT_POST, 'price_double', FILTER_VALIDATE_FLOAT);
    
    if (!$product_id || $price_single === false) {
        throw new Exception('Invalid input values');
    }
    
    if ($price_single < 0) {
        throw new Exception('Price cannot be negative');
    }
    
    // Check if product has double option
    $check_query = "SELECT has_options FROM products WHERE product_id = ?";
    $stmt_check = $dbcnx->prepare($check_query);
    $stmt_check->bind_param("i", $product_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Product not found');
    }
    
    $product = $result->fetch_assoc();
    $stmt_check->close();
    
    // Update price based on whether product has options
    if ($product['has_options'] && $price_double !== false && $price_double >= 0) {
        $update_query = "UPDATE products SET price_single = ?, price_double = ?, updated_at = NOW() WHERE product_id = ?";
        $stmt = $dbcnx->prepare($update_query);
        $stmt->bind_param("ddi", $price_single, $price_double, $product_id);
    } else {
        $update_query = "UPDATE products SET price_single = ?, updated_at = NOW() WHERE product_id = ?";
        $stmt = $dbcnx->prepare($update_query);
        $stmt->bind_param("di", $price_single, $product_id);
    }
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Price updated successfully'
        ]);
    } else {
        throw new Exception('Failed to update price: ' . $dbcnx->error);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    $dbcnx->close();
}
?>
