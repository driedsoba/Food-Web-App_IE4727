<?php
header('Content-Type: application/json');

require_once '../../includes/config.php';
require_once '../../includes/db.php';

// Get approved testimonials
$sql = "SELECT name, email, rating, order_number, feedback, created_at 
        FROM feedbacks 
        WHERE approved = 1 
        ORDER BY created_at DESC 
        LIMIT 20";

$result = $conn->query($sql);

$testimonials = [];
while ($row = $result->fetch_assoc()) {
    // Hide email for privacy
    $row['email'] = substr($row['email'], 0, 3) . '***@' . substr(strrchr($row['email'], "@"), 1);
    $testimonials[] = $row;
}

echo json_encode($testimonials);
?>
