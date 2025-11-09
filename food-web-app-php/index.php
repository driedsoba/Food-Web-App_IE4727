<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

$pageTitle = 'LeckerHaus - Authentic German Cuisine';
$additionalCSS = 'home.css';

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="home-hero">
    <div class="home-hero__content">
        <div class="home-badge">
            <span>🇩🇪</span>
            <span>Free Delivery on First Order</span>
        </div>
        <h1 class="home-hero__title">
            Delicious Food
            <span>Delivered Fast</span>
        </h1>
        <p class="home-hero__subtitle">
            Discover authentic German dishes crafted with traditional recipes and fresh ingredients. 
            From bratwurst to schnitzel, enjoy the flavors of Deutschland delivered to your door.
        </p>
        
        <form class="home-search" action="menu.php" method="GET">
            <input type="text" name="search" placeholder="Search for food..." />
            <button type="submit">Browse Menu</button>
        </form>

        <dl class="home-hero__stats">
            <div class="home-stat">
                <dt>Orders Delivered</dt>
                <dd>5,000+</dd>
            </div>
            <div class="home-stat">
                <dt>Restaurants</dt>
                <dd>200+</dd>
            </div>
            <div class="home-stat">
                <dt>Average Rating</dt>
                <dd>4.8★</dd>
            </div>
        </dl>
    </div>
    <div class="home-hero__media">
        <div class="home-hero__image-wrapper">
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80" 
                 alt="Delicious German food" 
                 class="home-hero__image">
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="home-section home-section--features">
    <div class="home-section__header">
        <span class="home-eyebrow">WHY CHOOSE US</span>
        <h2>Quality you can taste</h2>
        <p>We're committed to bringing you the most authentic German dining experience</p>
    </div>
    <div class="home-features">
        <article class="home-feature-card">
            <div class="home-feature-card__icon">🥨</div>
            <h3>Authentic Recipes</h3>
            <p>Traditional German recipes passed down through generations, prepared with care and expertise.</p>
        </article>
        <article class="home-feature-card">
            <div class="home-feature-card__icon">🌱</div>
            <h3>Fresh Ingredients</h3>
            <p>We source the finest local ingredients and import specialty items directly from Germany.</p>
        </article>
        <article class="home-feature-card">
            <div class="home-feature-card__icon">🚚</div>
            <h3>Fast Delivery</h3>
            <p>Hot, delicious meals delivered to your door within 30-45 minutes of ordering.</p>
        </article>
    </div>
</section>

<?php
$conn->close();
include 'includes/footer.php';
?>
