<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Register';
include 'includes/header.php';

// Display any error messages
if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Preserve form values on error
$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
unset($_SESSION['form_data']);
?>

<div class="container">
    <div class="page-header">
        <h1>Register</h1>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="message error"><?php echo h($error); ?></div>
    <?php endif; ?>
    
    <div class="auth-container">
        <form method="POST" action="backend/process-login.php" class="auth-form" onsubmit="return validateRegisterForm(this)">
            <input type="hidden" name="action" value="register">
            
            <div class="form-group">
                <label for="username">Username:</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required
                    minlength="3"
                    value="<?php echo isset($formData['username']) ? h($formData['username']) : ''; ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required
                    value="<?php echo isset($formData['email']) ? h($formData['email']) : ''; ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input 
                    type="text" 
                    id="full_name" 
                    name="full_name" 
                    required
                    value="<?php echo isset($formData['full_name']) ? h($formData['full_name']) : ''; ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    minlength="6"
                >
                <small>At least 6 characters</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    required
                    minlength="6"
                >
            </div>
            
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        
        <div class="auth-links">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

<script src="js/validation.js"></script>

<?php include 'includes/footer.php'; ?>
