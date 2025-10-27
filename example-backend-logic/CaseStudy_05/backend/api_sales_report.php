<?php
// API for Sales Report - Returns JSON with sales by product, by category, and best selling product
header('Content-Type: application/json');
include "dbconnect.php";

try {
    $report_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    
    // Validate date format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $report_date)) {
        throw new Exception('Invalid date format');
    }
    
    // 1. Sales by Product - Total dollar and quantity sales per product
    $product_query = "SELECT 
                        product_name,
                        SUM(quantity) as total_qty,
                        SUM(total_amount) as total_revenue
                      FROM sales 
                      WHERE sale_date = ?
                      GROUP BY product_name
                      ORDER BY product_name";
    
    $stmt1 = $dbcnx->prepare($product_query);
    $stmt1->bind_param("s", $report_date);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    
    $sales_by_product = [];
    while ($row = $result1->fetch_assoc()) {
        $sales_by_product[] = $row;
    }
    $stmt1->close();
    
    // 1b. Get detailed breakdown by product and option type
    $detail_query = "SELECT 
                        product_name,
                        option_type,
                        SUM(quantity) as qty,
                        SUM(total_amount) as revenue
                      FROM sales 
                      WHERE sale_date = ?
                      GROUP BY product_name, option_type
                      ORDER BY product_name, option_type";
    
    $stmt_detail = $dbcnx->prepare($detail_query);
    $stmt_detail->bind_param("s", $report_date);
    $stmt_detail->execute();
    $result_detail = $stmt_detail->get_result();
    
    $product_details = [];
    while ($row = $result_detail->fetch_assoc()) {
        $product_details[] = $row;
    }
    $stmt_detail->close();
    
    // 2. Sales by Category - Breakdown by Single and Double shots (and Null for products without options)
    $category_query = "SELECT 
                        COALESCE(option_type, 'Null') as category,
                        SUM(quantity) as total_qty,
                        SUM(total_amount) as total_revenue
                       FROM sales 
                       WHERE sale_date = ?
                       GROUP BY option_type
                       ORDER BY FIELD(option_type, NULL, 'single', 'double')";
    
    $stmt2 = $dbcnx->prepare($category_query);
    $stmt2->bind_param("s", $report_date);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    
    $sales_by_category = [];
    while ($row = $result2->fetch_assoc()) {
        $sales_by_category[] = $row;
    }
    $stmt2->close();
    
    // 3. Find best-selling product (highest total revenue)
    $best_product_query = "SELECT 
                            product_name,
                            SUM(total_amount) as total_revenue
                           FROM sales 
                           WHERE sale_date = ?
                           GROUP BY product_name
                           ORDER BY total_revenue DESC
                           LIMIT 1";
    
    $stmt3 = $dbcnx->prepare($best_product_query);
    $stmt3->bind_param("s", $report_date);
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    $best_product = $result3->fetch_assoc();
    $stmt3->close();
    
    $best_selling_info = null;
    
    // 4. If best-selling product exists, find its most popular option (highest quantity sold)
    if ($best_product) {
        $best_option_query = "SELECT 
                                option_type,
                                SUM(quantity) as total_qty
                              FROM sales 
                              WHERE sale_date = ? AND product_name = ?
                              GROUP BY option_type
                              ORDER BY total_qty DESC
                              LIMIT 1";
        
        $stmt4 = $dbcnx->prepare($best_option_query);
        $stmt4->bind_param("ss", $report_date, $best_product['product_name']);
        $stmt4->execute();
        $result4 = $stmt4->get_result();
        $best_option = $result4->fetch_assoc();
        $stmt4->close();
        
        $best_selling_info = [
            'product_name' => $best_product['product_name'],
            'total_revenue' => $best_product['total_revenue'],
            'popular_option' => $best_option['option_type'] ?? 'N/A',
            'popular_option_qty' => $best_option['total_qty'] ?? 0
        ];
    }
    
    echo json_encode([
        'success' => true,
        'date' => $report_date,
        'sales_by_product' => $sales_by_product,
        'product_details' => $product_details,
        'sales_by_category' => $sales_by_category,
        'best_selling' => $best_selling_info
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
