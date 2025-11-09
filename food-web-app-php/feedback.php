<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

$pageTitle = 'Feedback - ' . SITE_NAME;
$additionalCSS = 'feedback.css';

$isLoggedIn = isset($_SESSION['user_id']);

// Pre-fill user data if logged in
$userData = [];
if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT full_name, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
}

$success = $_SESSION['feedback_success'] ?? '';
$error = $_SESSION['feedback_error'] ?? '';
unset($_SESSION['feedback_success'], $_SESSION['feedback_error']);

include 'includes/header.php';
?>

<!-- Feedback Page -->
<div class="feedback-page">
    <div class="page-header">
        <h1>We Value Your Feedback</h1>
        <p>Help us improve by sharing your experience</p>
    </div>

    <div class="content-container">
        <section class="feedback-form-section">
            <?php if ($success): ?>
                <div class="form-message" style="display:block;margin-bottom:16px;padding:12px;border-radius:4px;background:#d4edda;color:#155724;">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="form-message" style="display:block;margin-bottom:16px;padding:12px;border-radius:4px;background:#f8d7da;color:#721c24;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <h2>Share Your Experience</h2>
            <form id="feedbackForm" class="feedback-form" method="POST" action="backend/process-feedback.php">
                <div class="form-group">
                    <label for="customer_name">Your Name *</label>
                    <input type="text" 
                           id="customer_name" 
                           name="customer_name" 
                           value="<?php echo isset($userData['full_name']) ? htmlspecialchars($userData['full_name']) : ''; ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="customer_email">Email *</label>
                    <input type="email" 
                           id="customer_email" 
                           name="customer_email" 
                           value="<?php echo isset($userData['email']) ? htmlspecialchars($userData['email']) : ''; ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="order_number">Order Number (Optional)</label>
                    <input type="text" id="order_number" name="order_number" placeholder="e.g., #12345">
                </div>

                <div class="form-group">
                    <label for="rating">Overall Rating *</label>
                    <div class="rating-input">
                        <select id="rating" name="rating" required>
                            <option value="">Select rating</option>
                            <option value="5">5 Stars - Excellent</option>
                            <option value="4">4 Stars - Good</option>
                            <option value="3">3 Stars - Average</option>
                            <option value="2">2 Stars - Below Average</option>
                            <option value="1">1 Star - Poor</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="comment">Your Feedback *</label>
                    <textarea id="comment" 
                              name="comment" 
                              rows="6" 
                              placeholder="Tell us about your experience..."
                              required></textarea>
                </div>

                <button type="submit" class="submit-btn">Submit Feedback</button>
            </form>
        </section>

        <section class="feedback-info-section">
            <h2>Why Your Feedback Matters</h2>
            <div class="info-cards">
                <div class="info-card">
                    <h3>Improve Our Service</h3>
                    <p>Your insights help us enhance our food quality and service standards.</p>
                </div>
                <div class="info-card">
                    <h3>Shape Our Menu</h3>
                    <p>Suggestions from customers like you influence our menu development.</p>
                </div>
                <div class="info-card">
                    <h3>Build Trust</h3>
                    <p>We value transparency and use feedback to maintain high standards.</p>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
