<?php
header('Content-Type: application/json');
session_start();

$isAuthenticated = isset($_SESSION['user_id']);
$user = null;

if ($isAuthenticated) {
    $user = [
        'user_id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'email' => $_SESSION['email'] ?? ''
    ];
}

echo json_encode([
    'isAuthenticated' => $isAuthenticated,
    'user' => $user
]);
?>
