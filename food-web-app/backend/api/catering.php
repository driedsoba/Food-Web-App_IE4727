<?php
require_once __DIR__ . '/../config/cors.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Get form data
    $input = json_decode(file_get_contents('php://input'), true);
    
    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';
    $phone = $input['phone'] ?? '';
    $eventDate = $input['eventDate'] ?? '';
    $guestCount = $input['guestCount'] ?? '';
    $package = $input['package'] ?? '';
    $message = $input['message'] ?? '';
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($eventDate) || empty($guestCount) || empty($package)) {
        http_response_code(400);
        echo json_encode(['error' => 'Please fill in all required fields']);
        exit;
    }
    
    // Validate email format (allow localhost for testing)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match('/@localhost$/', $email)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email address']);
        exit;
    }
    
    // Email configuration
    $to = 'f32ee@localhost'; // LeckerHaus catering email
    $subject = 'New Catering Inquiry - ' . $package . ' Package';
    
    // Email body
    $body = "New Catering Inquiry\n\n";
    $body .= "Contact Information:\n";
    $body .= "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Phone: $phone\n\n";
    $body .= "Event Details:\n";
    $body .= "Event Date: $eventDate\n";
    $body .= "Number of Guests: $guestCount\n";
    $body .= "Package: $package\n\n";
    $body .= "Additional Message:\n";
    $body .= "$message\n";
    
    // Email headers
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email
    if (mail($to, $subject, $body, $headers)) {
        echo json_encode([
            'success' => true,
            'message' => 'Your catering inquiry has been sent successfully! We will contact you soon.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to send email. Please try again later.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>
