# Food Web App - PHP Version

A traditional PHP web application for food ordering. This version uses form submissions and page reloads for all interactions.

## ⚠️ Important: File Naming Convention

This folder mirrors the **food-web-app-base-traditional** structure but converts `.html` files to `.php` files:

| Traditional Folder | PHP Folder | Description |
|-------------------|----------------|-------------|
| `index.html` | `index.php` | Home page |
| `menu.html` | `menu.php` | Menu browsing |
| `cart.html` | `cart.php` | Shopping cart |
| `login.html` | `login.php` | Login & Register (combined) |
| `catering.html` | `catering.php` | Catering inquiry |
| `feedback.html` | `feedback.php` | Customer feedback |
| `order-confirmation.html` | `order-confirmation.php` | Order success |
| `order-history.php` | `order-history.php` | Order tracking (same name) |

**Note:** `register.php` does NOT exist separately - registration is handled within `login.php` just like the traditional folder's `login.html`.

## Features

- ✅ User authentication (login/register in one page) with PHP sessions
- ✅ Menu browsing with search and category filters (server-side)
- ✅ Shopping cart management (form submissions)
- ✅ Order placement and tracking
- ✅ Order history viewing
- ✅ Feedback submission
- ✅ Catering inquiry form
- ✅ Session data passed to JavaScript via inline scripts

## Key Differences from Traditional Version

| Aspect | Traditional (.html) | PHP (.php) |
|--------|-------------------|----------------|
| **Data fetching** | Fetch API + JSON | Form submissions + page reloads |
| **Cart updates** | Async API calls | Form POST + redirect |
| **Search** | Client-side JS filtering | Server-side PHP query |
| **Login/Register** | Toggle UI with JS | Same page, different URL params |
| **Session check** | API endpoint (check-auth.php) | Inline PHP in header |
| **Error handling** | JSON responses | URL query parameters |
| **User feedback** | Dynamic DOM updates | Page reload with messages |

## How It Works

### 1. Session Data Access
```php
<!-- Inline session data in header.php -->
<script>
window.SESSION_DATA = {
    isAuthenticated: <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>,
    userId: <?php echo isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 'null'; ?>
};
</script>
```

### 2. Form Submissions
```html
<form method="POST" action="backend/process-add-to-cart.php">
    <input type="hidden" name="menu_item_id" value="123">
    <button type="submit">Add to Cart</button>
</form>
```

### 3. Login/Register Toggle
```php
$action = $_GET['action'] ?? 'login';
// Toggle between login and register on same page
```

## Installation

1. Import database: `mysql -u root -p < database/schema.sql`
2. Edit `includes/config.php` with your database credentials
3. Access: `http://localhost/food-web-app-php/`

## License

MIT License
