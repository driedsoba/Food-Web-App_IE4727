<?php
// API to fetch all products (Backend only - returns JSON)
header('Content-Type: application/json');
include "dbconnect.php";

try {
    $query = "SELECT * FROM products ORDER BY product_id";
    $result = $dbcnx->query($query);
    
    if ($result) {
        $products = [];
        while ($row = $result->fetch_assoc()) {
            // Convert boolean to actual boolean for JSON
            $row['has_options'] = (bool)$row['has_options'];
            $products[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'products' => $products
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to fetch products: ' . $dbcnx->error
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    $dbcnx->close();
}
?>