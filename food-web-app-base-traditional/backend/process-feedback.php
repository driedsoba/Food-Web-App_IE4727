<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

session_start();

// Get form data
$customerName = trim($_POST['customer_name'] ?? '');
$rating = (int)($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');
$userId = $_SESSION['user_id'] ?? null;

// Store form data for repopulation on error
$_SESSION['form_data'] = [
    'customer_name' => $customerName,
    'rating' => $rating,
    'comment' => $comment
];

// Server-side validation
if (empty($customerName)) {
    $_SESSION['error_message'] = 'Please enter your name';
    header('Location: ../feedback.php');
    exit;
}

if ($rating < 1 || $rating > 5) {
    $_SESSION['error_message'] = 'Please select a valid rating (1-5)';
    header('Location: ../feedback.php');
    exit;
}

if (empty($comment)) {
    $_SESSION['error_message'] = 'Please enter your feedback';
    header('Location: ../feedback.php');
    exit;
}

if (strlen($comment) < 10) {
    $_SESSION['error_message'] = 'Feedback must be at least 10 characters';
    header('Location: ../feedback.php');
    exit;
}

// Insert feedback into database - SQL INSERT
// Note: is_approved is set to 0 by default (requires admin approval)
$sql = "INSERT INTO feedback (user_id, customer_name, rating, comment, is_approved, created_at) 
        VALUES (?, ?, ?, ?, 0, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param('isis', $userId, $customerName, $rating, $comment);

if ($stmt->execute()) {
    unset($_SESSION['form_data']);
    $_SESSION['success_message'] = 'Thank you for your feedback! It will be reviewed and published soon.';
    header('Location: ../feedback.php');
    exit;
} else {
    $_SESSION['error_message'] = 'Failed to submit feedback. Please try again.';
    header('Location: ../feedback.php');
    exit;
}
?>
