<?php
session_start();
include_once '../config/cors.php';
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Build query based on filters
    $query = "SELECT * FROM menu_items WHERE is_active = 1";
    $params = [];
    
    // Filter by category
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $query .= " AND category = :category";
        $params[':category'] = $_GET['category'];
    }
    
    // Search by name or description
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $query .= " AND (name LIKE :search OR description LIKE :search)";
        $params[':search'] = '%' . $_GET['search'] . '%';
    }
    
    // Sort
    $sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'name';
    $sortOrder = isset($_GET['sortOrder']) && strtolower($_GET['sortOrder']) === 'desc' ? 'DESC' : 'ASC';
    
    // Validate sortBy to prevent SQL injection
    $allowedSortFields = ['name', 'price', 'rating'];
    if (!in_array($sortBy, $allowedSortFields)) {
        $sortBy = 'name';
    }
    
    $query .= " ORDER BY $sortBy $sortOrder";
    
    $stmt = $db->prepare($query);
    
    // Bind parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode($items);
    
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create new menu item (admin only - add auth check if needed)
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->name) && !empty($data->price) && !empty($data->category)) {
        $query = "INSERT INTO menu_items (name, description, price, category, image_url, rating) 
                  VALUES (:name, :description, :price, :category, :image_url, :rating)";
        
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":description", $data->description);
        $stmt->bindParam(":price", $data->price);
        $stmt->bindParam(":category", $data->category);
        $stmt->bindParam(":image_url", $data->image_url);
        $rating = isset($data->rating) ? $data->rating : 0;
        $stmt->bindParam(":rating", $rating);
        
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                "success" => true,
                "message" => "Menu item created",
                "id" => $db->lastInsertId()
            ]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Unable to create menu item"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Incomplete data"]);
    }
    
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update menu item (admin only - add auth check if needed)
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->id)) {
        $query = "UPDATE menu_items SET 
                  name = :name,
                  description = :description,
                  price = :price,
                  category = :category,
                  image_url = :image_url,
                  rating = :rating,
                  is_active = :is_active
                  WHERE id = :id";
        
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(":id", $data->id);
        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":description", $data->description);
        $stmt->bindParam(":price", $data->price);
        $stmt->bindParam(":category", $data->category);
        $stmt->bindParam(":image_url", $data->image_url);
        $stmt->bindParam(":rating", $data->rating);
        $is_active = isset($data->is_active) ? $data->is_active : 1;
        $stmt->bindParam(":is_active", $is_active);
        
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Menu item updated"
            ]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Unable to update menu item"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Missing menu item ID"]);
    }
    
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Soft delete menu item (admin only - add auth check if needed)
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->id)) {
        $query = "UPDATE menu_items SET is_active = 0 WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $data->id);
        
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Menu item deleted"
            ]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Unable to delete menu item"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Missing menu item ID"]);
    }
    
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}
?>
