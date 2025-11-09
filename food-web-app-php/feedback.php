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

// Fetch approved feedbacks
$feedbacks = [];
$feedback_query = "SELECT id, name, rating, feedback, created_at 
                   FROM feedbacks 
                   WHERE approved = 1 
                   ORDER BY created_at DESC";
$feedback_result = $conn->query($feedback_query);
if ($feedback_result) {
    $feedbacks = $feedback_result->fetch_all(MYSQLI_ASSOC);
}

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
                <div class="alert alert-success" style="text-align: center;">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error" style="text-align: center;">
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

    <!-- Display Approved Feedbacks -->
    <section class="feedback-display-section">
        <h2>What Our Customers Say</h2>
        <?php if (count($feedbacks) > 0): ?>
            <div class="feedbacks-list">
                <?php foreach ($feedbacks as $feedback): ?>
                    <div class="feedback-card">
                        <div class="feedback-header">
                            <div class="feedback-author">
                                <h3><?php echo htmlspecialchars($feedback['name']); ?></h3>
                                <div class="feedback-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star <?php echo $i <= $feedback['rating'] ? 'filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <span class="feedback-date">
                                <?php echo date('M d, Y', strtotime($feedback['created_at'])); ?>
                            </span>
                        </div>
                        <p class="feedback-text"><?php echo htmlspecialchars($feedback['feedback']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: #666; padding: 40px;">
                No feedbacks yet. Be the first to share your experience!
            </p>
        <?php endif; ?>
    </section>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
