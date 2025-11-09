<?php
require_once 'includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Login - ' . SITE_NAME;
$additionalCSS = 'login.css';

// Get error and action from URL
$error = $_GET['error'] ?? '';
$action = $_GET['action'] ?? 'login';
$isRegister = ($action === 'register');

include 'includes/header.php';
?>

<div class="login-page">
    <div class="login-container">
        <div class="login-header">
            <h1 id="formTitle"><?php echo $isRegister ? 'Register' : 'Login'; ?></h1>
            <p id="formSubtitle"><?php echo $isRegister ? 'Create your account' : 'Welcome back!'; ?></p>
        </div>

        <form id="authForm" class="login-form" method="POST" action="backend/process-login.php">
            <?php if ($error): ?>
                <div class="error-message" style="display: block;"><?php echo htmlspecialchars($error); ?></div>
            <?php else: ?>
                <div id="errorMessage" class="error-message" style="display: none;"></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    required
                    placeholder="Enter your username"
                />
            </div>

            <div class="form-group" id="emailGroup" style="display: <?php echo $isRegister ? 'flex' : 'none'; ?>;">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    <?php echo $isRegister ? 'required' : ''; ?>
                />
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="Enter your password"
                    minlength="6"
                />
            </div>

            <div class="form-group" id="confirmPasswordGroup" style="display: <?php echo $isRegister ? 'flex' : 'none'; ?>;">
                <label for="confirmPassword">Confirm Password</label>
                <input
                    type="password"
                    id="confirmPassword"
                    name="confirm_password"
                    placeholder="Confirm your password"
                    minlength="6"
                    <?php echo $isRegister ? 'required' : ''; ?>
                />
            </div>

            <input type="hidden" name="action" id="formAction" value="<?php echo $isRegister ? 'register' : 'login'; ?>">

            <button type="submit" class="submit-button">
                <span id="submitText"><?php echo $isRegister ? 'Register' : 'Login'; ?></span>
            </button>
        </form>

        <div class="login-footer">
            <p>
                <span id="toggleText"><?php echo $isRegister ? 'Already have an account?' : "Don't have an account?"; ?></span>
                <button type="button" id="toggleButton" class="toggle-button" onclick="window.location.href='login.php?action=<?php echo $isRegister ? 'login' : 'register'; ?>'">
                    <?php echo $isRegister ? 'Login' : 'Register'; ?>
                </button>
            </p>
        </div>
    </div>
</div>

<script src="js/validation.js"></script>

<?php include 'includes/footer.php'; ?>
