<?php
session_start();
include_once '../config/cors.php';
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get approved feedbacks only
    $query = "SELECT f.id, f.name, f.rating, f.feedback, f.created_at
              FROM feedbacks f
              WHERE f.approved = 1
              ORDER BY f.created_at DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    http_response_code(200);
    echo json_encode($feedbacks);
    
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Submit new feedback
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->rating) && !empty($data->feedback) && !empty($data->name) && !empty($data->email)) {
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $order_number = isset($data->orderNumber) ? $data->orderNumber : null;
        
        $query = "INSERT INTO feedbacks (user_id, name, email, rating, order_number, feedback, approved) 
                  VALUES (:user_id, :name, :email, :rating, :order_number, :feedback, 0)";
        
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":email", $data->email);
        $stmt->bindParam(":rating", $data->rating);
        $stmt->bindParam(":order_number", $order_number);
        $stmt->bindParam(":feedback", $data->feedback);
        
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                "success" => true,
                "message" => "Feedback submitted successfully. It will be visible after approval.",
                "id" => $db->lastInsertId()
            ]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Unable to submit feedback"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Rating and comment are required"]);
    }
    
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Approve feedback (admin only - add proper auth check)
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->id)) {
        $query = "UPDATE feedbacks SET approved = 1 WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $data->id);
        
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Feedback approved"
            ]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Unable to approve feedback"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Missing feedback ID"]);
    }
    
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete feedback (admin only - add proper auth check)
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->id)) {
        $query = "DELETE FROM feedbacks WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $data->id);
        
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Feedback deleted"
            ]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Unable to delete feedback"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Missing feedback ID"]);
    }
    
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}
?>
