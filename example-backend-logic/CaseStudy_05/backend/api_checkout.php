<?php
// API to handle checkout - saves order to database (Backend only - returns JSON)
header('Content-Type: application/json');
include "dbconnect.php";

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    if (!isset($_POST['items']) || empty($_POST['items'])) {
        throw new Exception('No items in order');
    }
    
    $items = $_POST['items'];
    $totalAmount = 0;
    
    // Set timezone to Singapore (UTC+8)
    date_default_timezone_set('Asia/Singapore');
    $today = date('Y-m-d');
    $time = date('H:i:s');
    
    // Get current prices from database
    $price_query = "SELECT product_id, price_single, price_double FROM products";
    $price_result = $dbcnx->query($price_query);
    $prices = [];
    while ($row = $price_result->fetch_assoc()) {
        $prices[$row['product_id']] = $row;
    }
    
    // Insert each item into sales table
    $insert_query = "INSERT INTO sales (product_id, product_name, option_type, quantity, unit_price, total_amount, sale_date, sale_time) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $dbcnx->prepare($insert_query);

    foreach ($items as $item_json) {
        $item = json_decode($item_json, true);
        
        $product_id = $item['product_id'];
        $product_name = $item['product_name'];
        $quantity = $item['quantity'];
        $option = $item['option'];  // 'single', 'double', or null
        
        // Calculate unit price
        if ($option === 'double' && isset($prices[$product_id]['price_double'])) {
            $unit_price = $prices[$product_id]['price_double'];
        } else {
            $unit_price = $prices[$product_id]['price_single'];
        }
        
        $item_total = $unit_price * $quantity;
        $totalAmount += $item_total;

    // $insert_query = "INSERT INTO sales (product_name, quantity) 
    //                 VALUES ('$product_name', $quantity)";   
    // product_name = "Java, 1); DROP TABLE sales; --"
    // VALUES ('Java, 1'); DROP TABLE sales;    
    
        // Insert into database
        $stmt->bind_param(s"isiddss", 
            $product_id, 
            $product_name, 
            $option, 
            $quantity, 
            $unit_price, 
            $item_total, 
            $today, 
            $time
        );
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to save order item: ' . $stmt->error);
        }
    }
    
    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully',
        'total' => $totalAmount
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    $dbcnx->close();
}
?>
