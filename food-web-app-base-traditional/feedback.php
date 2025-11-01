<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$pageTitle = 'Customer Feedback';
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

// Preserve form values on error
$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
unset($_SESSION['form_data']);

// Get all approved feedback - SQL SELECT query
$sql = "SELECT f.*, u.username 
        FROM feedback f 
        LEFT JOIN users u ON f.user_id = u.user_id 
        WHERE f.is_approved = 1 
        ORDER BY f.created_at DESC";

$result = $conn->query($sql);
?>

<div class="container">
    <div class="page-header">
        <h1>Customer Feedback</h1>
        <p>See what our customers are saying, and share your own experience!</p>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="message error"><?php echo h($error); ?></div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="message success"><?php echo h($success); ?></div>
    <?php endif; ?>
    
    <div class="feedback-container">
        <!-- Feedback Submission Form -->
        <div class="feedback-form-section">
            <h2>Share Your Feedback</h2>
            <form method="POST" action="backend/process-feedback.php" class="feedback-form" onsubmit="return validateFeedbackForm(this)">
                <div class="form-group">
                    <label for="customer_name">Your Name:</label>
                    <input 
                        type="text" 
                        id="customer_name" 
                        name="customer_name" 
                        required
                        value="<?php echo isset($formData['customer_name']) ? h($formData['customer_name']) : ''; ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <select id="rating" name="rating" required>
                        <option value="">Select rating...</option>
                        <option value="5" <?php echo (isset($formData['rating']) && $formData['rating'] == 5) ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ Excellent</option>
                        <option value="4" <?php echo (isset($formData['rating']) && $formData['rating'] == 4) ? 'selected' : ''; ?>>⭐⭐⭐⭐ Good</option>
                        <option value="3" <?php echo (isset($formData['rating']) && $formData['rating'] == 3) ? 'selected' : ''; ?>>⭐⭐⭐ Average</option>
                        <option value="2" <?php echo (isset($formData['rating']) && $formData['rating'] == 2) ? 'selected' : ''; ?>>⭐⭐ Below Average</option>
                        <option value="1" <?php echo (isset($formData['rating']) && $formData['rating'] == 1) ? 'selected' : ''; ?>>⭐ Poor</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="comment">Your Feedback:</label>
                    <textarea 
                        id="comment" 
                        name="comment" 
                        rows="5" 
                        required
                        minlength="10"
                    ><?php echo isset($formData['comment']) ? h($formData['comment']) : ''; ?></textarea>
                    <small>Minimum 10 characters. Your feedback will be reviewed before being published.</small>
                </div>
                
                <button type="submit" class="btn btn-primary">Submit Feedback</button>
            </form>
        </div>
        
        <!-- Display Approved Feedback -->
        <div class="feedback-list-section">
            <h2>What Our Customers Say</h2>
            
            <?php if ($result && $result->num_rows > 0): ?>
                <div class="feedback-list">
                    <?php while ($feedback = $result->fetch_assoc()): ?>
                        <div class="feedback-item">
                            <div class="feedback-header">
                                <div class="feedback-author">
                                    <strong><?php echo h($feedback['customer_name']); ?></strong>
                                    <?php if ($feedback['username']): ?>
                                        <span class="feedback-username">(@<?php echo h($feedback['username']); ?>)</span>
                                    <?php endif; ?>
                                </div>
                                <div class="feedback-rating">
                                    <?php 
                                    $rating = (int)$feedback['rating'];
                                    for ($i = 0; $i < $rating; $i++) {
                                        echo '⭐';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="feedback-comment">
                                <p><?php echo nl2br(h($feedback['comment'])); ?></p>
                            </div>
                            <div class="feedback-date">
                                <small><?php echo date('F j, Y', strtotime($feedback['created_at'])); ?></small>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-feedback">
                    <p>No feedback yet. Be the first to share your experience!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="js/validation.js"></script>

<?php include 'includes/footer.php'; ?>
