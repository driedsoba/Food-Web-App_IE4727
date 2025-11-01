<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

<div class="home-hero">
    <h2>Welcome to Our Food Web App</h2>
    <p>Delicious meals delivered to your door</p>
    <a href="menu.php" class="btn btn-primary">View Menu</a>
</div>

<div class="features">
    <div class="feature">
        <h3>Fresh Ingredients</h3>
        <p>We use only the freshest, locally-sourced ingredients</p>
    </div>
    <div class="feature">
        <h3>Fast Delivery</h3>
        <p>Hot meals delivered within 30 minutes</p>
    </div>
    <div class="feature">
        <h3>Great Prices</h3>
        <p>Affordable meals without compromising quality</p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
