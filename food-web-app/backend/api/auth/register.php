<?php
session_start();
include_once '../../config/cors.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    // Validate required fields
    if (empty($data->username) || empty($data->email) || empty($data->password)) {
        http_response_code(400);
        echo json_encode(["error" => "Username, email, and password are required"]);
        exit();
    }
    
    // Validate email format
    if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid email format"]);
        exit();
    }
    
    // Check if username or email already exists
    $check_query = "SELECT id FROM users WHERE username = :username OR email = :email";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(":username", $data->username);
    $check_stmt->bindParam(":email", $data->email);
    $check_stmt->execute();
    
    if ($check_stmt->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(["error" => "Username or email already exists"]);
        exit();
    }
    
    // Hash password
    $hashed_password = password_hash($data->password, PASSWORD_DEFAULT);
    
    // Insert new user
    $query = "INSERT INTO users (username, email, password, full_name, phone) 
              VALUES (:username, :email, :password, :full_name, :phone)";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(":username", $data->username);
    $stmt->bindParam(":email", $data->email);
    $stmt->bindParam(":password", $hashed_password);
    
    $full_name = isset($data->full_name) ? $data->full_name : null;
    $phone = isset($data->phone) ? $data->phone : null;
    
    $stmt->bindParam(":full_name", $full_name);
    $stmt->bindParam(":phone", $phone);
    
    if ($stmt->execute()) {
        $user_id = $db->lastInsertId();
        
        // Set session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $data->username;
        $_SESSION['email'] = $data->email;
        
        http_response_code(201);
        echo json_encode([
            "success" => true,
            "message" => "Registration successful",
            "user" => [
                "id" => $user_id,
                "username" => $data->username,
                "email" => $data->email,
                "full_name" => $full_name
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Unable to register user"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}
?>
