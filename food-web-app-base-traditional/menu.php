<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get filter parameters
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Build SQL query
$sql = "SELECT id, name, description, price, category, image_url, is_available 
        FROM menu_items 
        WHERE is_available = 1";

$params = [];
$types = '';

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= 's';
}

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'ss';
}

$sql .= " ORDER BY category, name";

// Execute query
$stmt = $conn->prepare($sql);
if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get all categories for filter
$categoriesStmt = $conn->prepare("SELECT DISTINCT category FROM menu_items WHERE is_available = 1 ORDER BY category");
$categoriesStmt->execute();
$categoriesResult = $categoriesStmt->get_result();

require_once 'includes/header.php';
?>

<h2>Our Menu</h2>

<!-- Filter Form -->
<div class="menu-filters">
    <form method="GET" action="menu.php" class="filter-form">
        <div class="form-group">
            <label for="category">Category:</label>
            <select name="category" id="category">
                <option value="">All Categories</option>
                <?php while ($cat = $categoriesResult->fetch_assoc()): ?>
                    <option value="<?php echo h($cat['category']); ?>" 
                            <?php echo ($category === $cat['category']) ? 'selected' : ''; ?>>
                        <?php echo h($cat['category']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="search">Search:</label>
            <input type="text" name="search" id="search" 
                   value="<?php echo h($search); ?>" 
                   placeholder="Search menu items...">
        </div>
        
        <button type="submit" class="btn">Filter</button>
        <a href="menu.php" class="btn btn-secondary">Clear</a>
    </form>
</div>

<!-- Menu Items Grid -->
<div class="menu-grid">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($item = $result->fetch_assoc()): ?>
            <div class="menu-item">
                <?php if (!empty($item['image_url'])): ?>
                    <img src="<?php echo h($item['image_url']); ?>" 
                         alt="<?php echo h($item['name']); ?>" 
                         class="menu-item-image">
                <?php endif; ?>
                
                <div class="menu-item-details">
                    <h3><?php echo h($item['name']); ?></h3>
                    <p class="category"><?php echo h($item['category']); ?></p>
                    <p class="description"><?php echo h($item['description']); ?></p>
                    <p class="price"><?php echo formatCurrency($item['price']); ?></p>
                    
                    <?php if (isLoggedIn()): ?>
                        <!-- Add to Cart Form -->
                        <form method="POST" action="backend/process-cart.php" class="add-to-cart-form">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="menu_item_id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity" value="1" min="1" max="99" required>
                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <p class="login-notice"><a href="login.php">Login</a> to order</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="no-results">No menu items found.</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
