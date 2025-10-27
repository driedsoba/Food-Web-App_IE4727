<?php
session_start();
include_once '../config/cors.php';
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get user ID or session ID
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get cart items
    if ($user_id) {
        // Get cart for logged-in user
        $query = "SELECT c.id, c.quantity, m.name, m.description, m.price, m.image_url, m.category
                  FROM cart_items c
                  JOIN menu_items m ON c.menu_item_id = m.id
                  WHERE c.user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
    } else {
        // Get cart for guest session
        $query = "SELECT c.id, c.quantity, m.name, m.description, m.price, m.image_url, m.category
                  FROM cart_items c
                  JOIN menu_items m ON c.menu_item_id = m.id
                  WHERE c.session_id = :session_id AND c.user_id IS NULL";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":session_id", $session_id);
    }
    
    $stmt->execute();
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode($cartItems);
    
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add item to cart
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->menu_item_id)) {
        // Check if item already exists in cart
        if ($user_id) {
            $checkQuery = "SELECT id, quantity FROM cart_items WHERE user_id = :user_id AND menu_item_id = :menu_item_id";
            $checkStmt = $db->prepare($checkQuery);
            $checkStmt->bindParam(":user_id", $user_id);
            $checkStmt->bindParam(":menu_item_id", $data->menu_item_id);
        } else {
            $checkQuery = "SELECT id, quantity FROM cart_items WHERE session_id = :session_id AND menu_item_id = :menu_item_id AND user_id IS NULL";
            $checkStmt = $db->prepare($checkQuery);
            $checkStmt->bindParam(":session_id", $session_id);
            $checkStmt->bindParam(":menu_item_id", $data->menu_item_id);
        }
        
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            // Item exists, update quantity
            $existingItem = $checkStmt->fetch(PDO::FETCH_ASSOC);
            $newQuantity = $existingItem['quantity'] + (isset($data->quantity) ? $data->quantity : 1);
            
            $updateQuery = "UPDATE cart_items SET quantity = :quantity WHERE id = :id";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bindParam(":quantity", $newQuantity);
            $updateStmt->bindParam(":id", $existingItem['id']);
            
            if ($updateStmt->execute()) {
                http_response_code(200);
                echo json_encode([
                    "success" => true,
                    "message" => "Cart updated",
                    "cart_item_id" => $existingItem['id']
                ]);
            } else {
                http_response_code(503);
                echo json_encode(["error" => "Unable to update cart"]);
            }
        } else {
            // New item, insert
            $quantity = isset($data->quantity) ? $data->quantity : 1;
            
            $insertQuery = "INSERT INTO cart_items (user_id, session_id, menu_item_id, quantity) 
                            VALUES (:user_id, :session_id, :menu_item_id, :quantity)";
            $insertStmt = $db->prepare($insertQuery);
            
            $insertStmt->bindParam(":user_id", $user_id);
            $insertStmt->bindParam(":session_id", $session_id);
            $insertStmt->bindParam(":menu_item_id", $data->menu_item_id);
            $insertStmt->bindParam(":quantity", $quantity);
            
            if ($insertStmt->execute()) {
                http_response_code(201);
                echo json_encode([
                    "success" => true,
                    "message" => "Item added to cart",
                    "cart_item_id" => $db->lastInsertId()
                ]);
            } else {
                http_response_code(503);
                echo json_encode(["error" => "Unable to add item to cart"]);
            }
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Missing menu item ID"]);
    }
    
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update cart item quantity
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->cart_item_id) && isset($data->quantity)) {
        if ($user_id) {
            $query = "UPDATE cart_items SET quantity = :quantity 
                      WHERE id = :cart_item_id AND user_id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":cart_item_id", $data->cart_item_id);
            $stmt->bindParam(":quantity", $data->quantity);
            $stmt->bindParam(":user_id", $user_id);
        } else {
            $query = "UPDATE cart_items SET quantity = :quantity 
                      WHERE id = :cart_item_id AND session_id = :session_id AND user_id IS NULL";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":cart_item_id", $data->cart_item_id);
            $stmt->bindParam(":quantity", $data->quantity);
            $stmt->bindParam(":session_id", $session_id);
        }
        
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Cart item updated"
            ]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Unable to update cart item"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Missing cart item ID or quantity"]);
    }
    
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Remove item from cart
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->cart_item_id)) {
        if ($user_id) {
            $query = "DELETE FROM cart_items WHERE id = :cart_item_id AND user_id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":cart_item_id", $data->cart_item_id);
            $stmt->bindParam(":user_id", $user_id);
        } else {
            $query = "DELETE FROM cart_items WHERE id = :cart_item_id AND session_id = :session_id AND user_id IS NULL";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":cart_item_id", $data->cart_item_id);
            $stmt->bindParam(":session_id", $session_id);
        }
        
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Item removed from cart"
            ]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Unable to remove item from cart"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Missing cart item ID"]);
    }
    
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}
?>
