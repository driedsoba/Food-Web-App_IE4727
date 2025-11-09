<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../catering.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$event_type = trim($_POST['event_type'] ?? '');
$event_date = trim($_POST['event_date'] ?? '');
$guest_count = trim($_POST['guest_count'] ?? '');
$location = trim($_POST['location'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validation
if (empty($name) || empty($email) || empty($phone) || empty($event_type) || 
    empty($event_date) || empty($guest_count) || empty($location) || empty($message)) {
    $_SESSION['catering_error'] = 'Please fill in all required fields';
    header('Location: ../catering.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['catering_error'] = 'Invalid email format';
    header('Location: ../catering.php');
    exit;
}

// Validate phone number (exactly 8 digits)
$clean_phone = preg_replace('/\s/', '', $phone);
if (!preg_match('/^\d{8}$/', $clean_phone)) {
    $_SESSION['catering_error'] = 'Phone number must be exactly 8 digits (e.g., 67489380)';
    header('Location: ../catering.php');
    exit;
}

// Validate guest count is a positive number
if (!is_numeric($guest_count) || $guest_count <= 0) {
    $_SESSION['catering_error'] = 'Guest count must be a positive number';
    header('Location: ../catering.php');
    exit;
}

// Prepare email (in real application, use PHPMailer or similar)
$to = 'catering@foodwebapp.com'; // Replace with actual email
$subject = 'Catering Request from ' . $name;
$body = "New Catering Request:\n\n";
$body .= "Name: $name\n";
$body .= "Email: $email\n";
$body .= "Phone: $phone\n";
$body .= "Event Type: $event_type\n";
$body .= "Event Date: $event_date\n";
$body .= "Number of Guests: $guest_count\n";
$body .= "Location: $location\n\n";
$body .= "Message:\n$message\n";

$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";

// Send email (uncomment in production)
// $sent = mail($to, $subject, $body, $headers);

// For demo purposes, just set success
$sent = true;

if ($sent) {
    $_SESSION['catering_success'] = 'Thank you! Your catering request has been sent. We will contact you soon.';
} else {
    $_SESSION['catering_error'] = 'Failed to send request. Please try again.';
}

header('Location: ../catering.php');
exit;
?>
