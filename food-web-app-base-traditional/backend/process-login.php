<?php
session_start();

require_once '../includes/config.php';
require_once '../includes/db.php';

// Handle GET logout
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    handleLogout();
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'login':
        handleLogin($conn);
        break;
    case 'register':
        handleRegister($conn);
        break;
    default:
        $_SESSION['error_message'] = 'Invalid action';
        header('Location: ../index.html');
        exit;
}

function handleLogin($conn) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($username) || empty($password)) {
        header('Location: ../login.html?action=login&error=' . urlencode('Please fill in all fields'));
        exit;
    }
    
    // Check credentials - SQL SELECT query by username
    $sql = "SELECT id, username, email, password 
            FROM users 
            WHERE username = ? 
            LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header('Location: ../login.html?action=login&error=' . urlencode('Invalid username or password'));
        exit;
    }
    
    $user = $result->fetch_assoc();
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        header('Location: ../login.html?action=login&error=' . urlencode('Invalid username or password'));
        exit;
    }
    
    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    
    header('Location: ../menu.html');
    exit;
}

function handleRegister($conn) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        header('Location: ../login.html?action=register&error=' . urlencode('Please fill in all fields'));
        exit;
    }
    
    if (strlen($username) < 3) {
        header('Location: ../login.html?action=register&error=' . urlencode('Username must be at least 3 characters'));
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../login.html?action=register&error=' . urlencode('Invalid email format'));
        exit;
    }
    
    if (strlen($password) < 8) {
        header('Location: ../login.html?action=register&error=' . urlencode('Password must be at least 8 characters'));
        exit;
    }
    
    if ($password !== $confirmPassword) {
        header('Location: ../login.html?action=register&error=' . urlencode('Passwords do not match'));
        exit;
    }
    
    // Check if username already exists
    $sql = "SELECT id FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header('Location: ../login.html?action=register&error=' . urlencode('Username already taken'));
        exit;
    }
    
    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header('Location: ../login.html?action=register&error=' . urlencode('Email already registered'));
        exit;
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $sql = "INSERT INTO users (username, email, password, created_at) 
            VALUES (?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $username, $email, $hashedPassword);
    
    if ($stmt->execute()) {
        // Auto login after registration
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        
        header('Location: ../menu.html');
        exit;
    } else {
        header('Location: ../login.html?action=register&error=' . urlencode('Registration failed. Please try again.'));
        exit;
    }
}

function handleLogout() {
    session_destroy();
    header('Location: ../index.html');
    exit;
}
?>