<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get form data
$customerName = trim($_POST['customer_name'] ?? '');
$customerEmail = trim($_POST['customer_email'] ?? '');
$orderNumber = trim($_POST['order_number'] ?? '');
$rating = (int)($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');
$userId = $_SESSION['user_id'] ?? null;

// Store form data for repopulation on error
$_SESSION['form_data'] = [
    'customer_name' => $customerName,
    'customer_email' => $customerEmail,
    'order_number' => $orderNumber,
    'rating' => $rating,
    'comment' => $comment
];

// Server-side validation
if (empty($customerName)) {
    $msg = urlencode('Please enter your name');
    header('Location: ../feedback.html?error=1&msg=' . $msg);
    exit;
}

if ($rating < 1 || $rating > 5) {
    $msg = urlencode('Please select a valid rating (1-5)');
    header('Location: ../feedback.html?error=1&msg=' . $msg);
    exit;
}

if (empty($comment)) {
    $msg = urlencode('Please enter your feedback');
    header('Location: ../feedback.html?error=1&msg=' . $msg);
    exit;
}

if (strlen($comment) < 10) {
    $msg = urlencode('Feedback must be at least 10 characters');
    header('Location: ../feedback.html?error=1&msg=' . $msg);
    exit;
}

// Insert feedback into database - align with schema which defines `feedbacks`
$sql = "INSERT INTO feedbacks (user_id, name, email, rating, order_number, feedback, approved) 
        VALUES (?, ?, ?, ?, ?, ?, 0)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    $msg = urlencode('Database error: ' . $conn->error);
    header('Location: ../feedback.html?error=1&msg=' . $msg);
    exit;
}

$stmt->bind_param('ississ', $userId, $customerName, $customerEmail, $rating, $orderNumber, $comment);

if ($stmt->execute()) {
    unset($_SESSION['form_data']);
    $msg = urlencode('Thank you for your feedback! It will be reviewed and published soon.');
    header('Location: ../feedback.html?success=1&msg=' . $msg);
    exit;
} else {
    $msg = urlencode('Failed to submit feedback. Please try again.');
    header('Location: ../feedback.html?error=1&msg=' . $msg);
    exit;
}
?>
