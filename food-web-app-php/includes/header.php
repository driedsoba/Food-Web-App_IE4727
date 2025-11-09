<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? ($_SESSION['username'] ?? 'User') : '';

// Get current page for active menu highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/base.css">
    <?php if (isset($additionalCSS)): ?>
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/<?php echo $additionalCSS; ?>">
    <?php endif; ?>
    <!-- Session data available for JavaScript -->
    <script>
        window.SESSION_DATA = {
            isAuthenticated: <?php echo $isLoggedIn ? 'true' : 'false'; ?>,
            userId: <?php echo $isLoggedIn ? (int)$_SESSION['user_id'] : 'null'; ?>,
            userName: <?php echo $isLoggedIn ? json_encode($userName) : 'null'; ?>
        };
    </script>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <h1><a href="<?php echo SITE_URL; ?>/index.php" style="color: inherit; text-decoration: none;"><?php echo SITE_NAME; ?></a></h1>
            </div>
            <nav class="navigation">
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/index.php" class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/menu.php" class="<?php echo $currentPage === 'menu.php' ? 'active' : ''; ?>">Menu</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/catering.php" class="<?php echo $currentPage === 'catering.php' ? 'active' : ''; ?>">Catering</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/order-history.php" class="<?php echo $currentPage === 'order-history.php' ? 'active' : ''; ?>">Order History</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/feedback.php" class="<?php echo $currentPage === 'feedback.php' ? 'active' : ''; ?>">Feedback</a></li>
                </ul>
            </nav>
            <div class="cart-section">
                <?php if ($isLoggedIn): ?>
                    <span class="user-greeting">Hello, <?php echo htmlspecialchars($userName); ?>!</span>
                    <form method="POST" action="<?php echo SITE_URL; ?>/backend/process-logout.php" style="display:inline;">
                        <button type="submit" class="logout-button">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>/login.php" class="login-button">
                        <span class="login-text">Login</span>
                    </a>
                <?php endif; ?>
                <a href="<?php echo SITE_URL; ?>/cart.php" class="cart-button">
                    <span class="cart-text">Cart</span>
                </a>
            </div>
        </div>
    </header>
    <main class="main-content">
