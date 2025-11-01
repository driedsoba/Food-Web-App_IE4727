<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Login';
include 'includes/header.php';

// Display any error messages
if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Display any success messages
if (isset($_SESSION['success_message'])) {
    $success = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<div class="container">
    <div class="page-header">
        <h1>Login</h1>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="message error"><?php echo h($error); ?></div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="message success"><?php echo h($success); ?></div>
    <?php endif; ?>
    
    <div class="auth-container">
        <form method="POST" action="backend/process-login.php" class="auth-form" onsubmit="return validateLoginForm(this)">
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required
                    value="<?php echo isset($_SESSION['form_email']) ? h($_SESSION['form_email']) : ''; ?>"
                >
                <?php unset($_SESSION['form_email']); ?>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                >
            </div>
            
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        
        <div class="auth-links">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</div>

<script src="js/validation.js"></script>

<?php include 'includes/footer.php'; ?>
