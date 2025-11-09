<?php
require_once 'includes/config.php';

$pageTitle = 'Catering Services - ' . SITE_NAME;
$additionalCSS = 'catering.css';

$success = $_SESSION['catering_success'] ?? '';
$error = $_SESSION['catering_error'] ?? '';
unset($_SESSION['catering_success'], $_SESSION['catering_error']);

include 'includes/header.php';
?>

<!-- Catering Page -->
<div class="catering-page">
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <section class="catering-form-section">
        <div class="catering-container">
            <div class="menu-header">
                <h1>Compare Packages</h1>
                <p>Compare our catering packages to find the perfect fit for your event</p>
            </div>

            <div class="table-wrapper">
                <table class="catering-table" role="table">
                    <caption class="sr-only">Comparison of Essential, Premium and Deluxe catering packages</caption>
                    <thead>
                        <tr>
                            <th scope="col">Feature</th>
                            <th scope="col">Essential</th>
                            <th scope="col" class="popular-column">Premium</th>
                            <th scope="col">Deluxe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row" class="feature-name">Price per Person</th>
                            <td>$15–20</td>
                            <td class="popular-column">$25–35</td>
                            <td>$40–50</td>
                        </tr>
                        <tr>
                            <th scope="row" class="feature-name">Main Course Options</th>
                            <td>3 options</td>
                            <td class="popular-column">5 options</td>
                            <td>Unlimited</td>
                        </tr>
                        <tr>
                            <th scope="row" class="feature-name">Side Dishes</th>
                            <td>2 sides</td>
                            <td class="popular-column">4 sides</td>
                            <td>Full selection</td>
                        </tr>
                        <tr>
                            <th scope="row" class="feature-name">Appetizers</th>
                            <td>—</td>
                            <td class="popular-column">Selection</td>
                            <td>Premium selection</td>
                        </tr>
                        <tr>
                            <th scope="row" class="feature-name">Desserts</th>
                            <td>—</td>
                            <td class="popular-column">Dessert bar</td>
                            <td>Dessert & coffee bar</td>
                        </tr>
                        <tr>
                            <th scope="row" class="feature-name">Setup</th>
                            <td>Basic</td>
                            <td class="popular-column">Premium</td>
                            <td>Full service</td>
                        </tr>
                        <tr>
                            <th scope="row" class="feature-name">Tableware</th>
                            <td>Disposable</td>
                            <td class="popular-column">Basic glassware</td>
                            <td>Premium Glassware</td>
                        </tr>
                        <tr>
                            <th scope="row" class="feature-name">Service Staff</th>
                            <td>—</td>
                            <td class="popular-column">—</td>
                            <td aria-label="Included">✓</td>
                        </tr>
                        <tr>
                            <th scope="row" class="feature-name">Custom Menu</th>
                            <td>—</td>
                            <td class="popular-column">—</td>
                            <td aria-label="Included">✓</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="catering-contact">
        <div class="catering-container">
            <h2>Custom Requests or Want to Cater?</h2>
            <p style="font-size: 1.3rem;">Contact our catering team directly at</p>
            <div class="contact-details">
                <div class="contact-item">
                    <span style="font-size: 1.8rem; font-weight: bold; color: #ff6b1a;">catering@leckerhaus.com</span>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
