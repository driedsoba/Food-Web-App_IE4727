<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$action = $_POST['action'] ?? 'login';

if ($action === 'register') {
    // REGISTRATION LOGIC
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        header('Location: ../login.php?action=register&error=' . urlencode('Please fill in all fields'));
        exit;
    }

    if (strlen($username) < 3) {
        header('Location: ../login.php?action=register&error=' . urlencode('Username must be at least 3 characters'));
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../login.php?action=register&error=' . urlencode('Invalid email format'));
        exit;
    }

    if (strlen($password) < 6) {
        header('Location: ../login.php?action=register&error=' . urlencode('Password must be at least 6 characters'));
        exit;
    }

    if ($password !== $confirm_password) {
        header('Location: ../login.php?action=register&error=' . urlencode('Passwords do not match'));
        exit;
    }

    // Check if username or email exists
    $check_query = "SELECT id FROM users WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param('ss', $username, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        header('Location: ../login.php?action=register&error=' . urlencode('Username or email already registered'));
        exit;
    }

    // Hash password and insert user
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param('sss', $username, $email, $hashed_password);

    if ($insert_stmt->execute()) {
        // Auto-login after registration
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $conn->close();
        header('Location: ../menu.php');
        exit;
    } else {
        $conn->close();
        header('Location: ../login.php?action=register&error=' . urlencode('Registration failed. Please try again.'));
        exit;
    }

} else {
    // LOGIN LOGIC
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        header('Location: ../login.php?error=' . urlencode('Please enter username and password'));
        exit;
    }

    // Query for username or email
    $query = "SELECT id, username, email, password FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $conn->close();
        header('Location: ../login.php?error=' . urlencode('Invalid credentials'));
        exit;
    }

    $user = $result->fetch_assoc();

    // Verify password
    if (!password_verify($password, $user['password'])) {
        $conn->close();
        header('Location: ../login.php?error=' . urlencode('Invalid credentials'));
        exit;
    }

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];

    $conn->close();

    // Redirect to menu
    header('Location: ../menu.php');
    exit;
}
?>
