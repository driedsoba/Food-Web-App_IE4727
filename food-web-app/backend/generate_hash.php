<?php
// Generate password hash for testing
$password = 'password123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";
echo "\n";
echo "SQL to update:\n";
echo "UPDATE users SET password = '$hash' WHERE username = 'testuser';\n";
?>
