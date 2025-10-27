<?php
// Database connection for JavaJam Coffee House
@$dbcnx = new mysqli('localhost', 'root', '', 'javajam');

// Suppress error display with @
if ($dbcnx->connect_error) {
    die("Database connection failed: " . $dbcnx->connect_error);
}

// Set charset to UTF-8
$dbcnx->set_charset("utf8");
?>
