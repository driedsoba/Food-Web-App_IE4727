<?php
session_start();
include_once '../../config/cors.php';
include_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['user_id'])) {
        // User is logged in, fetch fresh data from database
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT id, username, email, created_at FROM users WHERE id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":user_id", $_SESSION['user_id']);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            http_response_code(200);
            echo json_encode([
                "isAuthenticated" => true,
                "user" => $user
            ]);
        } else {
            // User ID in session but not found in database
            session_destroy();
            http_response_code(401);
            echo json_encode([
                "isAuthenticated" => false,
                "message" => "Session invalid"
            ]);
        }
    } else {
        // Not logged in
        http_response_code(200);
        echo json_encode([
            "isAuthenticated" => false
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}
?>
