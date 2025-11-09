<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

$pageTitle = 'Menu - ' . SITE_NAME;
$additionalCSS = 'menu.css';

// Get search and filter parameters
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'name';

// Build query
$query = "SELECT * FROM menu_items WHERE is_active = 1";
$params = [];
$types = '';

if ($search) {
    $query .= " AND (name LIKE ? OR description LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= 'ss';
}

if ($category && $category !== 'All') {
    $query .= " AND category = ?";
    $params[] = $category;
    $types .= 's';
}

// Add sorting
switch($sort) {
    case 'price-low':
        $query .= " ORDER BY price ASC";
        break;
    case 'price-high':
        $query .= " ORDER BY price DESC";
        break;
    case 'rating':
        $query .= " ORDER BY rating DESC";
        break;
    default:
        $query .= " ORDER BY name ASC";
}

$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$menuItems = $result->fetch_all(MYSQLI_ASSOC);

$success = $_SESSION['cart_success'] ?? '';
unset($_SESSION['cart_success']);

include 'includes/header.php';
?>

<!-- Menu Page -->
<div class="menu-page">
    <div class="menu-header">
        <h1>Our Menu</h1>
        <p>Authentic German cuisine, made fresh daily</p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div class="menu-controls">
        <form method="GET" action="menu.php" class="search-box">
            <input type="text" 
                   id="searchInput"
                   name="search" 
                   placeholder="Search dishes..." 
                   class="search-input"
                   value="<?php echo htmlspecialchars($search); ?>">
            <?php if ($category): ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
            <?php endif; ?>
            <?php if ($sort): ?>
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
            <?php endif; ?>
        </form>

        <div class="filter-group">
            <label for="sort-select">Sort by:</label>
            <select id="sort-select" class="sort-select" onchange="this.form.submit()">
                <option value="name" <?php echo $sort === 'name' ? 'selected' : ''; ?>>Name</option>
                <option value="price-low" <?php echo $sort === 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price-high" <?php echo $sort === 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
                <option value="rating" <?php echo $sort === 'rating' ? 'selected' : ''; ?>>Rating</option>
            </select>
        </div>
    </div>

    <div class="category-tabs">
        <a href="?category=All<?php echo $search ? '&search='.urlencode($search) : ''; ?><?php echo $sort ? '&sort='.$sort : ''; ?>" 
           class="category-tab <?php echo !$category || $category === 'All' ? 'active' : ''; ?>">All</a>
        <a href="?category=Mains<?php echo $search ? '&search='.urlencode($search) : ''; ?><?php echo $sort ? '&sort='.$sort : ''; ?>" 
           class="category-tab <?php echo $category === 'Mains' ? 'active' : ''; ?>">Mains</a>
        <a href="?category=Starters<?php echo $search ? '&search='.urlencode($search) : ''; ?><?php echo $sort ? '&sort='.$sort : ''; ?>" 
           class="category-tab <?php echo $category === 'Starters' ? 'active' : ''; ?>">Starters</a>
        <a href="?category=Sides<?php echo $search ? '&search='.urlencode($search) : ''; ?><?php echo $sort ? '&sort='.$sort : ''; ?>" 
           class="category-tab <?php echo $category === 'Sides' ? 'active' : ''; ?>">Sides</a>
        <a href="?category=Desserts<?php echo $search ? '&search='.urlencode($search) : ''; ?><?php echo $sort ? '&sort='.$sort : ''; ?>" 
           class="category-tab <?php echo $category === 'Desserts' ? 'active' : ''; ?>">Desserts</a>
    </div>

    <div class="results-info">
        Showing <span id="itemCount"><?php echo count($menuItems); ?></span> dishes
    </div>

    <?php if (count($menuItems) === 0): ?>
        <div class="error-state">
            <p>No menu items found matching your criteria.</p>
            <a href="menu.php" class="retry-button">View All</a>
        </div>
    <?php else: ?>
        <div class="menu-grid" id="menuGrid">
            <?php foreach ($menuItems as $item): ?>
                <div class="menu-item-card">
                    <?php if ($item['image_url']): ?>
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                             class="menu-item-image">
                    <?php endif; ?>
                    
                    <div class="menu-item-content">
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p class="category-badge"><?php echo htmlspecialchars($item['category']); ?></p>
                        <p class="description"><?php echo htmlspecialchars($item['description']); ?></p>
                        <div class="menu-item-details">
                            <span class="price">$<?php echo number_format($item['price'], 2); ?></span>
                        </div>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form method="POST" action="backend/process-add-to-cart.php" class="add-to-cart-form">
                                <input type="hidden" name="menu_item_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                        <?php else: ?>
                            <p class="login-prompt">
                                <a href="login.php">Login</a> to add items to cart
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
