<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../feedback.php');
    exit;
}

$name = trim($_POST['customer_name'] ?? '');
$email = trim($_POST['customer_email'] ?? '');
$rating = intval($_POST['rating'] ?? 0);
$order_number = trim($_POST['order_number'] ?? '');
$feedback = trim($_POST['comment'] ?? '');

// Validation
if (empty($name) || empty($email) || empty($feedback)) {
    $_SESSION['feedback_error'] = 'Please fill in all required fields';
    header('Location: ../feedback.php');
    exit;
}

if ($rating < 1 || $rating > 5) {
    $_SESSION['feedback_error'] = 'Please select a rating';
    header('Location: ../feedback.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['feedback_error'] = 'Invalid email format';
    header('Location: ../feedback.php');
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;

// Insert feedback
$query = "INSERT INTO feedbacks (user_id, name, email, rating, order_number, feedback, approved) 
          VALUES (?, ?, ?, ?, ?, ?, 0)";
$stmt = $conn->prepare($query);
$stmt->bind_param('ississ', $user_id, $name, $email, $rating, $order_number, $feedback);

if ($stmt->execute()) {
    $_SESSION['feedback_success'] = 'Thank you for your feedback! It will be reviewed before being published.';
} else {
    $_SESSION['feedback_error'] = 'Failed to submit feedback. Please try again.';
}

$conn->close();
header('Location: ../feedback.php');
exit;
?>
