<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h(SITE_NAME); ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/styles.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <h1><a href="<?php echo SITE_URL; ?>/index.php"><?php echo h(SITE_NAME); ?></a></h1>
            <nav class="main-nav">
                <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
                <a href="<?php echo SITE_URL; ?>/menu.php">Menu</a>
                <?php if (isLoggedIn()): ?>
                    <a href="<?php echo SITE_URL; ?>/cart.php">Cart (<?php echo getCartCount($conn); ?>)</a>
                    <a href="<?php echo SITE_URL; ?>/feedback.php">Feedback</a>
                    <span class="user-info">Welcome, <?php echo h($_SESSION['username']); ?>!</span>
                    <form method="POST" action="<?php echo SITE_URL; ?>/backend/process-login.php" style="display: inline;">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="btn btn-small btn-secondary">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>/login.php">Login</a>
                    <a href="<?php echo SITE_URL; ?>/register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="main-content">
        <div class="container">
